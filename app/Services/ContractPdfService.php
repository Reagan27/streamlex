<?php

namespace Vanguard\Services;

use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Vanguard\User;
use Vanguard\AdminContract;
use Vanguard\ContractVersion;
use Vanguard\Role;
use Vanguard\UserContractSignature;

class ContractPdfService
{
    protected $html2pdf;
    
    public function __construct()
    {
        $this->html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 15, 15, 15]);
        $this->html2pdf->setDefaultFont('helvetica');
    }

    /**
     * Generate PDF contract for a user
     *
     * @param User $user
     * @return string
     * @throws Exception
     */

     public function generateContract(User $user, UserContractSignature $contractSignature = null)
     {
         try {
             // Add debug logging for contract lookup
             Log::info('Starting contract generation', [
                 'user_id' => $user->id,
                 'contract_signature_id' => $contractSignature?->id,
                 'contract_id' => $contractSignature?->contract_id
             ]);
 
             if ($contractSignature) {
                 // Get the specific contract associated with this signature
                 $contract = AdminContract::findOrFail($contractSignature->contract_id);
 
                 Log::info('Using contract from signature', [
                     'contract_id' => $contract->id,
                     'signature_id' => $contractSignature->id,
                     'contract_title' => $contract->title
                 ]);
 
                 $signature = $contractSignature;
             } else {
                 // This should rarely be used now, but kept for backwards compatibility
                 Log::warning('Falling back to latest contract - this should not happen', [
                     'user_id' => $user->id
                 ]);
                 
                 $contract = $this->getLatestContract($user);
                 $signature = $this->getLatestSignature($user);
             }
             
             // Get user's role display name
             $roleDisplayName = $user->role ? $user->role->display_name : 'N/A';
 
             // Generate HTML content with the specific contract
             $html = $this->assembleContractHtml($user, $contract, $signature, $roleDisplayName);
 
             // Generate PDF
             $this->html2pdf->writeHTML($html);
             
             return $this->html2pdf->output('', 'S');
 
         } catch (Exception $e) {
             Log::error('Contract generation failed', [
                 'user_id' => $user->id,
                 'contract_signature_id' => $contractSignature?->id ?? 'none',
                 'contract_id' => $contractSignature?->contract_id ?? 'none',
                 'error' => $e->getMessage(),
                 'trace' => $e->getTraceAsString()
             ]);
             throw new Exception('Error generating contract: ' . $e->getMessage());
         }
     }

    /**
     * Generate version-specific contract
     *
     * @param AdminContract $contract
     * @param ContractVersion $version
     * @return string
     * @throws Exception
     */
    public function generateVersionContract(AdminContract $contract, ContractVersion $version)
    {
        try {
            // Get user's role display name
            $user = User::findOrFail($version->changed_by);
            $roleDisplayName = $user->role ? $user->role->display_name : 'N/A';

            // Generate HTML content using version data
            $html = $this->assembleVersionContractHtml($contract, $version, $roleDisplayName);

            // Generate PDF
            $this->html2pdf->writeHTML($html);
            
            return $this->html2pdf->output('', 'S');

        } catch (Exception $e) {
            Log::error('Contract version generation failed', [
                'version_id' => $version->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Error generating contract version: ' . $e->getMessage());
        }
    }

    /**
     * Get the latest published contract for a user
     *
     * @param User $user
     * @return AdminContract
     * @throws ModelNotFoundException
     */
    protected function getLatestContract(User $user)
    {
        return AdminContract::where('role_id', $user->role_id)
            ->where('status', 'published')
            ->latest()
            ->firstOrFail();
    }

    /**
     * Get the latest signature for a user
     *
     * @param User $user
     * @return UserContractSignature|null
     */
    protected function getLatestSignature(User $user)
    {
        return UserContractSignature::where('user_id', $user->id)
        ->whereNotNull('signature')
        ->whereNotNull('agreed_at')
        ->orderBy('agreed_at', 'asc')
        ->first();
    }

    /**
     * Assemble the complete HTML for the contract
     *
     * @param User $user
     * @param AdminContract $contract
     * @param UserContractSignature|null $signature
     * @param string $roleDisplayName
     * @return string
     */



    /**
     * Assemble HTML for version-specific contract
     *
     * @param AdminContract $contract
     * @param ContractVersion $version
     * @param string $roleDisplayName
     * @return string
     */
    protected function assembleVersionContractHtml($contract, $version, $roleDisplayName)
    {
        $styles = $this->getStyles();
        $header = $this->getHeader();
        
        // Add version information
        $versionInfo = '
        <div class="version-info" style="text-align: right; margin-bottom: 20px; font-size: 10pt; color: #666;">
            <p>Version Date: ' . $version->created_at->format('Y-m-d H:i:s') . '</p>
            <p>Status: ' . ucfirst($version->status) . '</p>
            <p>Changed By: ' . $version->changedByUser->first_name . ' ' . $version->changedByUser->last_name . '</p>
        </div>';

        // Contract content
        $content = '
        <div style="page-break-inside: avoid;">
            ' . $header . '
            ' . $versionInfo . '
            <h3 style="margin: 0; padding: 0; text-align: center;">SHORT TERM CONSULTANCY CONTRACT</h3>
            <div class="contract-body" style="margin-top: 20px;">
                ' . $this->sanitizeHtml($version->description) . '
            </div>
        </div>';

        // Add authority signature if accepted
        if ($version->status === 'accepted') {
            $content .= '
            <div style="page-break-inside: avoid;">
                ' . $this->getAuthoritySignatureSection($contract) . '
            </div>';
        }

        $footer = $this->getFooter();

        return $styles . $content . $footer;
    }

    /**
     * Get CSS styles for the contract
     *
     * @return string
     */


    /**
     * Get the header HTML
     *
     * @return string
     */
    protected function assembleContractHtml($user, $contract, $signature, $roleDisplayName)
{
    try {
        $contractRole = Role::find($contract->role_id);
        $contractRoleDisplayName = $contractRole ? $contractRole->display_name : 'N/A';
        
        $styles = $this->getStyles();
        $header = $this->getHeader();
        
        $content = '
        <div style="page-break-inside: avoid; margin: 0; padding: 0;">
            ' . $header . '
            <div style="margin: 0; padding: 0;">
                <h3 style="margin: 0; padding: 0; text-align: center; display: block;">SHORT TERM CONSULTANCY CONTRACT</h3>
                <div style="margin: 0; padding: 0; line-height: 1.4;">
                    <p style="margin: 0; padding: 0; line-height: 1.4;">
                        <strong>BETWEEN:</strong> ' . htmlspecialchars($user->first_name . ' ' . $user->last_name) . ' 
                        <strong>OF ID NO:</strong> ' . htmlspecialchars($user->documents->id_number ?? 'N/A') . ' 
                        <strong>AND SELISTAR MANAGEMENT SERVICES AS A</strong> ' . htmlspecialchars($contractRoleDisplayName) . ' 
                        <strong>(HEREIN REFERRED TO AS "CONSULTANT")</strong>
                    </p>
                    <div style="margin-left: 5px; margin-right:5px;  padding: 0;">
                        ' . $this->sanitizeHtml($contract->description) . '
                    </div>
                </div>
            </div>
        </div>';

        // Signatures section
        $content .= '
        <div style="page-break-inside: avoid; margin-top: 20px;">
            <div class="declarations-section">
                <div class="consultant-signature-section">
                    <p style="margin-bottom: 15px;"><strong>Consultant\'s Declaration:</strong></p>
                    <p style="margin-bottom: 20px;">I, ' . htmlspecialchars($user->first_name . ' ' . $user->last_name) . 
                        ', agree to the terms and conditions set forth in this contract.</p>
                    
                    <div class="signature-block" style="margin-bottom: 30px;">
                        ' . $this->getSignatureSection($signature) . '
                    </div>
                </div>
                
                <div class="authority-section">
                    ' . $this->getAuthoritySignatureSection($contract) . '
                </div>
            </div>
        </div>';

        $footer = $this->getFooter();
        
        return $styles . $content . $footer;
    } catch (Exception $e) {
        Log::error('Error assembling contract HTML', [
            'error' => $e->getMessage(),
            'user_id' => $user->id ?? 'unknown',
            'contract_id' => $contract->id ?? 'unknown'
        ]);
        throw new Exception('Error generating contract HTML: ' . $e->getMessage());
    }
}

protected function getStyles()
{
    return '
    <style>
        body {
            font-family: helvetica;
            line-height: 1.4;
            color: #000000;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        h3 {
            color: #000000;
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            display: block;
        }
        .contract-header {
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .contract-body {
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .declarations-section {
            margin: 20px 0 0 0;
            page-break-inside: avoid;
        }
        .consultant-signature-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .authority-section {
            page-break-inside: avoid;
        }
        .signature {
            max-width: 200px;
            height: auto;
            margin: 10px 0;
            display: block;
        }
        p {
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        div {
            page-break-inside: avoid;
        }
    </style>';
}

protected function getHeader()
{
    $headerImage = public_path('assets/img/Header.png');
    
    if (file_exists($headerImage)) {
        return '
        <div style="text-align: center; margin: 0; padding: 0; line-height: 0;">
            <img src="' . $headerImage . '" style="width: 100%; max-height: 100px; margin: 0; padding: 0; display: block;">
        </div>';
    }
    
    return '';
}
    /**
     * Get the footer HTML
     *
     * @return string
     */
    protected function getFooter()
    {
        $footerImage = public_path('assets/img/Footer.png');
        
        if (file_exists($footerImage)) {
            return '
            <div style="text-align: center; margin-top: 20px;">
                <img src="' . $footerImage . '" style="width: 100%; max-height: 100px; display: block;">
            </div>';
        }
        
        return '';
    }

    /**
     * Get the signature section HTML
     *
     * @param UserContractSignature|null $signature
     * @return string
     */
    protected function getSignatureSection($signature)
    {
        $html = '<div class="signature-block">';
        
        if ($signature && $signature->signature) {
            $html .= '<img src="' . $signature->signature . '" class="signature" alt="Consultant Signature">';
        } else {
            $html .= '<p>Signature not provided</p>';
        }
        
        $html .= '
            <p style="margin: 5px 0;"><strong>Date:</strong> ' . 
            ($signature && $signature->agreed_at ? $signature->agreed_at->format('Y-m-d') : 'Not signed') . '</p>
        </div>';
        
        return $html;
    }

    /**
     * Get the authority signature section HTML
     *
     * @param AdminContract $contract
     * @return string
     */
    protected function getAuthoritySignatureSection($contract)
    {
        try {
            $html = '
            <div class="authority-signature" style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px;">
                <p style="margin-bottom: 15px;"><strong>For SELISTAR MANAGEMENT SERVICES:</strong></p>';
                
            // Safely handle signature image
            if ($contract && $contract->authority_signature) {
                // Check if the signature is a file path or base64 data
                if (file_exists($contract->authority_signature)) {
                    $html .= '<img src="' . $contract->authority_signature . '" class="signature" alt="Authority Signature" style="width: 200px; height: auto; margin: 10px 0; display: block;">';
                } else {
                    // Assume it's base64 data and ensure it's properly formatted
                    $html .= '<img src="' . (strpos($contract->authority_signature, 'data:image') === 0 ? 
                        $contract->authority_signature : 'data:image/png;base64,' . $contract->authority_signature) . 
                        '" class="signature" alt="Authority Signature" style="width: 200px; height: auto; margin: 10px 0; display: block;">';
                }
            } else {
                $html .= '<p style="color: #666; margin: 10px 0;">Signature pending</p>';
            }
            
            // Safely handle other fields with null coalescing
            $html .= '
                <p style="margin: 5px 0;"><strong>Name:</strong> ' . htmlspecialchars($contract->authority_name ?? 'Not provided') . '</p>
                <p style="margin: 5px 0;"><strong>Designation:</strong> ' . htmlspecialchars($contract->authority_designation ?? 'Not provided') . '</p>
                <p style="margin: 5px 0;"><strong>Date:</strong> ' . 
                ($contract->accepted_at ? $contract->accepted_at->format('Y-m-d') : $contract->updated_at->format('Y-m-d')) . '</p>
            </div>';
            
            return $html;
        } catch (Exception $e) {
            Log::error('Error in authority signature section', [
                'error' => $e->getMessage(),
                'contract_id' => $contract->id ?? 'unknown'
            ]);
            
            // Return a basic version if there's an error
            return '
            <div class="authority-signature" style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px;">
                <p style="margin-bottom: 15px;"><strong>For SELISTAR MANAGEMENT SERVICES:</strong></p>
                <p style="color: #666;">Signature information unavailable</p>
            </div>';
        }
    }
    

    /**
     * Sanitize HTML content
     *
     * @param string $html
     * @return string
     */
    protected function sanitizeHtml($html)
    {
        if (empty($html)) {
            return '';
        }

        try {
            // Convert special characters
            $html = htmlspecialchars_decode($html);
            
            // Remove MS Word specific tags
            $html = preg_replace('/<o:p>.*?<\/o:p>/i', '', $html);
            $html = preg_replace('/<\/?o:[^>]*>/i', '', $html);
            
            // Clean up newlines and spaces
            $html = preg_replace('/\s+/', ' ', $html);
            
            // Use DOMDocument to properly handle HTML
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();
            
            // Get clean HTML
            $html = $dom->saveHTML();
            
            // Final cleanup
            $html = preg_replace('/<\/?html>|<\/?body>|<!DOCTYPE.*?>/i', '', $html);
            $html = trim($html);
            
            return $html;
        } catch (Exception $e) {
            Log::warning('HTML sanitization failed, returning plain text', [
                'error' => $e->getMessage()
            ]);
            return nl2br(strip_tags($html));
        }
    }
}