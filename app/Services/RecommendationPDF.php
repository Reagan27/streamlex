<?php

namespace Vanguard\Services;

use FPDF;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\RecommendationTemplate;
use Exception;

class RecommendationPDF extends FPDF
{
    public function generateRecommendationLetter(User $user, $overallRating, $overallPercentage, RecommendationTemplate $template)
    {
        try {
            $this->AddPage();
            $this->SetFont('Arial', 'B', 16);
            
            // Create a simple letterhead
            $this->Cell(0, 10, 'CPHRM GROUP', 0, 1, 'C');
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, 'Top Plaza, Kindaruma Road', 0, 1, 'C');
            $this->Cell(0, 10, 'Phone: 202 600 166 | Email: info@cphrmgroup.co.ke', 0, 1, 'C');
            $this->Ln(10);

            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Recommendation Letter', 0, 1, 'C');
            $this->Ln(10);

            $this->SetFont('Arial', '', 12);
            
            // Add user information
            $this->Cell(0, 10, 'Date: ' . Carbon::now()->format('F d, Y'));
            $this->Ln(10);
            $this->Cell(0, 10, 'Name: ' . $user->first_name . ' ' . $user->last_name);
            $this->Ln(10);
            // $this->Cell(0, 10, 'Overall Rating: ' . number_format($overallRating, 2));
            // $this->Ln(10);
            // $this->Cell(0, 10, 'Overall Percentage: ' . number_format($overallPercentage, 2) . '%');
            // $this->Ln(20);

            // Add recommendation content
            $content = str_replace(
                ['[NAME]', '[OVERALL_RATING]', '[OVERALL_PERCENTAGE]'],
                [$user->first_name . ' ' . $user->last_name, number_format($overallRating, 2), number_format($overallPercentage, 2)],
                $template->content
            );
            

            $content = strip_tags(html_entity_decode($content));

            $content = wordwrap($content, 70, "\n");

            $this->MultiCell(0, 10, $content);
        
            $this->Ln(20);
            $this->Cell(0, 10, 'Sincerely,');
            $this->Ln(15);
            $this->Cell(0, 10, 'Director Name');
            $this->Ln(10);
            $this->Cell(0, 10, 'Director Title');
            $this->Ln(10);
            $this->Cell(0, 10, 'Contact Information');

            return $this->Output('S');
        } catch (Exception $e) {
            // Log the error
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            throw new Exception('Error generating PDF: ' . $e->getMessage());
        }
    }
}
