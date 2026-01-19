<?php

namespace Vanguard\Services;

use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\RecommendationCertificate;
use Vanguard\AdminContract;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RecommendationCertificatePDF extends Fpdi
{
    public function generate(User $user, $type, $overallRating, $overallPercentage, RecommendationCertificate $template)
    {
        try {
            $contract = AdminContract::where('role_id', $user->role_id)
                                     ->where('status', 'published')
                                     ->orderBy('start_date', 'desc')
                                     ->first();

            if (!$contract) {
                throw new Exception('No active contract found for this user.');
            }

            $startDate = Carbon::parse($contract->start_date);
            $endDate = $startDate->copy()->addDays($contract->number_of_days);

            $templateFileName = $type === 'recommendation' ? 'recommendation_template.pdf' : 'cphrm_cos.pdf';
            
            if (!Storage::disk('local')->exists($templateFileName)) {
                throw new Exception("Template file not found: $templateFileName");
            }

            $templatePath = Storage::disk('local')->path($templateFileName);

            $this->setSourceFile($templatePath);
            $pageCount = $this->setSourceFile($templatePath);
            $this->AddPage();
            $tplIdx = $this->importPage(1);

            // Get the size of the imported page
            $size = $this->getTemplateSize($tplIdx);
            $this->useTemplate($tplIdx, 0, 0, $size['width'], $size['height']);

            $this->SetFont('Arial', '', 12);

            if ($type === 'recommendation') {
                $this->SetXY(30, 60);
                $this->Cell(0, 10, 'Name: ' . $user->first_name . ' ' . $user->last_name);
                $this->SetXY(30, 70);
                $this->Cell(0, 10, 'Overall Rating: ' . ($overallRating ? number_format($overallRating, 2) : 'N/A'));
                $this->SetXY(30, 80);
                $this->Cell(0, 10, 'Overall Percentage: ' . ($overallPercentage ? number_format($overallPercentage, 2) . '%' : 'N/A'));

                // Add template content for recommendation
                $content = str_replace(
                    ['[NAME]', '[OVERALL_RATING]', '[OVERALL_PERCENTAGE]'],
                    [
                        $user->first_name . ' ' . $user->last_name, 
                        $overallRating ? number_format($overallRating, 2) : 'N/A', 
                        $overallPercentage ? number_format($overallPercentage, 2) : 'N/A',
                    ],
                    $template->content
                );
                
                $content = strip_tags(html_entity_decode($content));
                $content = wordwrap($content, 70, "\n");
                
                $this->SetXY(30, 100);
                $this->MultiCell(0, 10, $content);
            } else {
                // For certificate of service, only add name, start date, end date, and days worked
                $this->SetXY(60, 110);
                $this->Cell(0, 10, $user->first_name . ' ' . $user->last_name);
                $this->SetXY(70, 134);
                $this->Cell(0, 10, $startDate->format('d/m/Y'));
                $this->SetXY(165, 134);
                $this->Cell(0, 10, $endDate->format('d/m/Y'));
                $this->SetXY(80, 155);
                $this->Cell(0, 10, $startDate->diffInDays($endDate) + 1);  // Add 1 to include both start and end dates
            }

            return $this->Output('S');
        } catch (Exception $e) {
            Log::error('PDF Generation Error in RecommendationCertificatePDF: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
                'template_id' => $template->id
            ]);
            throw new Exception('Error generating PDF: ' . $e->getMessage());
        }
    }
}