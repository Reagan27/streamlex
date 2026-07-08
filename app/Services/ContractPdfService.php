<?php

namespace Vanguard\Services;

use DOMDocument;
use DOMXPath;
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
        // Symmetric margins [top, right, bottom, left]
        $this->html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 15, 15, 15]);
        $this->html2pdf->setDefaultFont('helvetica');
    }

    /**
     * Generate PDF contract for a user
     */
    public function generateContract(User $user, UserContractSignature $contractSignature = null)
    {
        try {
            Log::info('Starting contract generation', [
                'user_id'              => $user->id,
                'contract_signature_id'=> $contractSignature?->id,
                'contract_id'          => $contractSignature?->contract_id
            ]);

            if ($contractSignature) {
                $contract = AdminContract::findOrFail($contractSignature->contract_id);

                Log::info('Using contract from signature', [
                    'contract_id'    => $contract->id,
                    'signature_id'   => $contractSignature->id,
                    'contract_title' => $contract->title
                ]);

                $signature = $contractSignature;
            } else {
                Log::warning('Falling back to latest contract - this should not happen', [
                    'user_id' => $user->id
                ]);
                
                $contract  = $this->getLatestContract($user);
                $signature = $this->getLatestSignature($user);
            }
            
            $roleDisplayName = $user->role ? $user->role->display_name : 'N/A';

            $html = $this->assembleContractHtml($user, $contract, $signature, $roleDisplayName);

            $this->html2pdf->writeHTML($html);
            
            return $this->html2pdf->output('', 'S');

        } catch (Exception $e) {
            Log::error('Contract generation failed', [
                'user_id'              => $user->id,
                'contract_signature_id'=> $contractSignature?->id ?? 'none',
                'contract_id'          => $contractSignature?->contract_id ?? 'none',
                'error'                => $e->getMessage(),
                'trace'                => $e->getTraceAsString()
            ]);
            throw new Exception('Error generating contract: ' . $e->getMessage());
        }
    }

    /**
     * Generate version-specific contract
     */
    public function generateVersionContract(AdminContract $contract, ContractVersion $version)
    {
        try {
            $user            = User::findOrFail($version->changed_by);
            $roleDisplayName = $user->role ? $user->role->display_name : 'N/A';

            $html = $this->assembleVersionContractHtml($contract, $version, $roleDisplayName);

            $this->html2pdf->writeHTML($html);
            
            return $this->html2pdf->output('', 'S');

        } catch (Exception $e) {
            Log::error('Contract version generation failed', [
                'version_id' => $version->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString()
            ]);
            throw new Exception('Error generating contract version: ' . $e->getMessage());
        }
    }

    /**
     * Get the latest published contract for a user
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
     * Resolve whether a contract is employment or consultancy.
     *
     * Priority:
     *   1. engagement_type field — matches the dropdown values saved by the form:
     *        "employee"   → employment
     *        "parttime"   → employment
     *        "consultant" → consultancy
     *   2. Legacy fallback: match against known employment role display names
     *      (covers records created before the engagement_type dropdown existed)
     *
     * Returns true for employment, false for consultancy.
     */
    protected function resolveIsEmployment(AdminContract $contract, string $contractRoleDisplayName): bool
    {
        $engagementType = strtolower(trim($contract->engagement_type ?? ''));

        Log::info('resolveIsEmployment', [
            'contract_id'     => $contract->id ?? 'unknown',
            'engagement_type' => $contract->engagement_type,
            'normalized'      => $engagementType,
        ]);

        // --- Dropdown values from the create/edit form ---
        // "employee" and "parttime" are employment contracts
        if (in_array($engagementType, ['employee', 'parttime'])) {
            return true;
        }

        // "consultant" is a consultancy contract
        if ($engagementType === 'consultant') {
            return false;
        }

        // Catch any other common variations in case the dropdown ever changes
        if (str_contains($engagementType, 'employ')) {
            return true;
        }

        if (str_contains($engagementType, 'consult')) {
            return false;
        }

        // Legacy fallback for older records without engagement_type set
        $employmentRoles = [
            'Regional_Coordinator',
            'Regional Managers',
            'County_Coordinator',
            'Business Lead & Coach',
        ];

        return in_array($contractRoleDisplayName, $employmentRoles);
    }

    /**
     * Assemble HTML for version-specific contract
     */
    protected function assembleVersionContractHtml($contract, $version, $roleDisplayName)
    {
        $styles = $this->getStyles();
        $header = $this->getHeader();

        // Resolve contract type for version preview as well
        $isEmployment  = $this->resolveIsEmployment($contract, $roleDisplayName);
        $contractTitle = $isEmployment ? 'EMPLOYMENT CONTRACT' : 'SHORT TERM CONSULTANCY CONTRACT';

        $versionInfo = '
        <div style="text-align: right; margin-bottom: 20px; font-size: 10pt; color: #666;">
            <p>Version Date: ' . $version->created_at->format('Y-m-d H:i:s') . '</p>
            <p>Status: ' . ucfirst($version->status) . '</p>
            <p>Changed By: ' . $version->changedByUser->first_name . ' ' . $version->changedByUser->last_name . '</p>
        </div>';

        $content = '
        ' . $header . '
        ' . $versionInfo . '
        <h3 style="margin: 0 0 10px 0; padding: 0; text-align: center; page-break-after: avoid;">' . $contractTitle . '</h3>
        <div class="contract-body">
            ' . $this->sanitizeHtml($version->description) . '
        </div>';

        if ($version->status === 'accepted') {
            $content .= $this->getAuthoritySignatureSection($contract);
        }

        $footer = $this->getFooter();

        return $styles . $content . $footer;
    }

    /**
     * Assemble the complete HTML for the contract
     */
    protected function assembleContractHtml($user, $contract, $signature, $roleDisplayName)
    {
        try {
            $contractRole = Role::find($contract->role_id);

            // Determine the display name shown in the BETWEEN line
            if (isset($contract->contract_category)) {
                if ($contract->contract_category === 'group') {
                    $contractRoleDisplayName = $contractRole ? $contractRole->display_name : 'N/A';
                } elseif ($contract->contract_category === 'individual') {
                    $contractRoleDisplayName = $contract->title;
                } elseif (isset($contract->project_id) && $contract->project_id == 3) {
                    $contractRoleDisplayName = $contract->title;
                } else {
                    $contractRoleDisplayName = $contractRole ? $contractRole->display_name : 'N/A';
                }
            } elseif (isset($contract->project_id) && $contract->project_id == 3) {
                $contractRoleDisplayName = $contract->title;
            } else {
                $contractRoleDisplayName = $contractRole ? $contractRole->display_name : 'N/A';
            }

            $styles = $this->getStyles();
            $header = $this->getHeader();

            // Resolve employment vs consultancy using engagement_type dropdown first,
            // falling back to legacy role-name matching for older records.
            $isEmployment = $this->resolveIsEmployment($contract, $contractRoleDisplayName);

            $contractTitle    = $isEmployment ? 'EMPLOYMENT CONTRACT'                                        : 'SHORT TERM CONSULTANCY CONTRACT';
            $partyLabel       = $isEmployment ? 'EMPLOYEE'                                                   : 'CONSULTANT';
            $declarationLabel = $isEmployment ? "Employee's Declaration"                                     : "Consultant's Declaration";
            $declarationBody  = $isEmployment ? 'accept the terms and conditions of employment set forth in this contract.'
                                              : 'agree to the terms and conditions set forth in this contract.';

            Log::info('Contract type resolved', [
                'contract_id'     => $contract->id,
                'engagement_type' => $contract->engagement_type ?? 'not set',
                'is_employment'   => $isEmployment,
                'contract_title'  => $contractTitle,
            ]);

            $descHtml = $this->sanitizeHtml($contract->description);

            // Signatures flow naturally after the contract body — no forced page breaks.
            // This prevents the blank pages seen when page-break-before: always was used.
            $content = '
            ' . $header . '
            <h2 style="text-align: center; font-size: 13pt; font-weight: bold; margin-bottom: 10px; page-break-after: avoid;">' . $contractTitle . '</h2>
            <div class="contract-body">
                <p style="text-align: left; page-break-after: avoid;">
                    <strong>BETWEEN:</strong> ' . htmlspecialchars($user->first_name . ' ' . $user->last_name) . '
                    <strong>OF ID NO:</strong> ' . htmlspecialchars(optional($user->documents->first())->id_number ?? 'N/A') . '
                    <strong>AND CPHRM GROUP AS A</strong> ' . htmlspecialchars($contractRoleDisplayName) . '
                    <strong>(HEREIN REFERRED TO AS "' . $partyLabel . '")</strong>
                </p>
                ' . $descHtml . '
            </div>

            <div class="signatures-wrapper">
                <div class="consultant-signature-section">
                    <p><strong>' . $declarationLabel . ':</strong></p>
                    <p>I, ' . htmlspecialchars($user->first_name . ' ' . $user->last_name) . ', ' . $declarationBody . '</p>
                    ' . $this->getSignatureSection($signature) . '
                </div>
                <div class="authority-section">
                    ' . $this->getAuthoritySignatureSection($contract) . '
                </div>
            </div>';

            $footer = $this->getFooter();
            
            return $styles . $content . $footer;

        } catch (Exception $e) {
            Log::error('Error assembling contract HTML', [
                'error'       => $e->getMessage(),
                'user_id'     => $user->id ?? 'unknown',
                'contract_id' => $contract->id ?? 'unknown'
            ]);
            throw new Exception('Error generating contract HTML: ' . $e->getMessage());
        }
    }

    /**
     * Get CSS styles for the contract.
     */
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
            h2 {
                color: #000000;
                text-align: center;
                font-size: 20pt;
                font-weight: bold;
                margin: 0;
                padding: 0;
                page-break-after: avoid;
            }
            h3 {
                color: #000000;
                text-align: center;
                font-size: 16pt;
                font-weight: bold;
                margin: 0;
                padding: 0;
                display: block;
                page-break-after: avoid;
            }
            .contract-body {
                margin: 0;
                padding: 0;
                line-height: 1.4;
            }
            .signatures-wrapper {
                margin-top: 20px;
            }
            .consultant-signature-section {
                margin-bottom: 20px;
            }
            .authority-section {
                margin-top: 10px;
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
                word-wrap: break-word;
                overflow-wrap: break-word;
                white-space: normal;
                text-align: justify;
            }
            /*
             * These rules use !important to beat any inline styles left over from the rich-text editor.
             * The sanitizeHtml() method also strips inline styles from ul/ol/li directly,
             * but !important here is the safety net in case any slip through.
             */
            ul {
                margin: 0 !important;
                padding: 0 0 0 18px !important;
                list-style-position: outside !important;
                word-wrap: break-word;
                overflow-wrap: break-word;
                text-align: justify;
            }
            ol {
                margin: 0 !important;
                padding: 0 0 0 18px !important;
                list-style-position: outside !important;
                word-wrap: break-word;
                overflow-wrap: break-word;
                text-align: justify;
            }
            li {
                margin: 0 0 4px 0 !important;
                padding: 0 !important;
                word-wrap: break-word;
                overflow-wrap: break-word;
                white-space: normal;
                text-align: justify;
            }
            .signature-block {
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
            }
            td, th {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
        </style>';
    }

    /**
     * Get the header HTML
     */
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
     */
    protected function getFooter()
    {
        return '';
    }

    /**
     * Get the signature section HTML
     */
    protected function getSignatureSection($signature)
    {
        $html = '<div class="signature-block">';

        if ($signature && $signature->signature) {
            $sig        = $signature->signature;
            $isFile     = file_exists($sig);
            $isBase64   = strpos($sig, 'data:image') === 0 || base64_decode($sig, true) !== false;
            $validImage = false;

            if ($isFile) {
                $imgInfo    = @getimagesize($sig);
                $validImage = $imgInfo !== false;
            } elseif ($isBase64) {
                $data = $sig;
                if (strpos($sig, 'data:image') !== 0) {
                    $data = 'data:image/png;base64,' . $sig;
                }
                $base64     = preg_replace('/^data:image\/[a-zA-Z]+;base64,/', '', $data);
                $img        = @imagecreatefromstring(base64_decode($base64));
                $validImage = $img !== false;
            }

            if ($validImage) {
                $html .= '<img src="' . ($isFile ? $sig : (strpos($sig, 'data:image') === 0 ? $sig : 'data:image/png;base64,' . $sig)) . '" class="signature" alt="Signature">';
            } else {
                $html .= '<p style="color: #c00;">Signature image invalid or unreadable</p>';
            }
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
     */
    protected function getAuthoritySignatureSection($contract)
    {
        try {
            $html = '
            <div style="border-top: 1px solid #ddd; padding-top: 20px;">
                <p style="margin-bottom: 15px;"><strong>For CPHRM GROUP:</strong></p>';
                
            if ($contract && $contract->authority_signature) {
                if (file_exists($contract->authority_signature)) {
                    $html .= '<img src="' . $contract->authority_signature . '" class="signature" alt="Authority Signature" style="width: 200px; height: auto; margin: 10px 0; display: block;">';
                } else {
                    $html .= '<img src="' . (strpos($contract->authority_signature, 'data:image') === 0 ?
                        $contract->authority_signature : 'data:image/png;base64,' . $contract->authority_signature) .
                        '" class="signature" alt="Authority Signature" style="width: 200px; height: auto; margin: 10px 0; display: block;">';
                }
            } else {
                $html .= '<p style="color: #666; margin: 10px 0;">Signature pending</p>';
            }
            
            $html .= '
                <p style="margin: 5px 0;"><strong>Name:</strong> ' . htmlspecialchars($contract->authority_name ?? 'Not provided') . '</p>
                <p style="margin: 5px 0;"><strong>Designation:</strong> ' . htmlspecialchars($contract->authority_designation ?? 'Not provided') . '</p>
                <p style="margin: 5px 0;"><strong>Date:</strong> ' . 
                ($contract->accepted_at ? $contract->accepted_at->format('Y-m-d') : $contract->updated_at->format('Y-m-d')) . '</p>
            </div>';
            
            return $html;

        } catch (Exception $e) {
            Log::error('Error in authority signature section', [
                'error'       => $e->getMessage(),
                'contract_id' => $contract->id ?? 'unknown'
            ]);
            
            return '
            <div style="border-top: 1px solid #ddd; padding-top: 20px;">
                <p style="margin-bottom: 15px;"><strong>For CPHRM GROUP:</strong></p>
                <p style="color: #666;">Signature information unavailable</p>
            </div>';
        }
    }

    /**
     * Sanitize HTML content.
     *
     * This method does two important jobs:
     *
     * 1. Strips ALL inline styles from ul, ol, and li elements using DOMXPath.
     *    This is the definitive fix for words being cut off on the right side.
     *    Rich-text editors (TinyMCE, Quill, CKEditor) inject styles like:
     *      style="margin-left: 40px; padding-left: 20px"
     *    directly onto list elements. CSS rules (even with !important) cannot
     *    always override these in Html2Pdf because inline styles have higher
     *    specificity. The only reliable fix is to remove them from the DOM directly.
     *
     * 2. Strips overflow-causing inline style properties from ALL other elements:
     *    margin-left, margin-right, padding-left, padding-right, width, min-width, max-width.
     */
    protected function sanitizeHtml($html)
    {
        if (empty($html)) {
            return '';
        }

        try {
            // Decode special characters
            $html = htmlspecialchars_decode($html);
            
            // Remove MS Word specific tags
            $html = preg_replace('/<o:p>.*?<\/o:p>/i', '', $html);
            $html = preg_replace('/<\/?o:[^>]*>/i', '', $html);

            // Strip overflow-causing inline style properties from all elements
            $html = preg_replace_callback('/style="([^"]*)"/i', function ($matches) {
                $style = $matches[1];
                $style = preg_replace(
                    '/\b(margin-left|margin-right|padding-left|padding-right|width|min-width|max-width)\s*:\s*[^;]+;?/i',
                    '',
                    $style
                );
                $style = trim($style, " \t\n\r\0\x0B;");
                return $style !== '' ? 'style="' . $style . '"' : '';
            }, $html);

            // Strip width and height HTML attributes
            $html = preg_replace('/\s(width|height)="[^"]*"/i', '', $html);

            // Parse with DOMDocument so we can use XPath to target specific elements
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            // Use XPath to find every ul, ol, and li and completely remove their style attribute.
            // This is the most reliable method — DOMXPath operates directly on the parsed DOM,
            // so there is no way for any inline style to survive on these elements.
            $xpath        = new DOMXPath($dom);
            $listElements = $xpath->query('//ul | //ol | //li');
            foreach ($listElements as $el) {
                $el->removeAttribute('style');
                $el->removeAttribute('width');
                $el->removeAttribute('height');
                // Also remove any class that might carry editor-specific styles
                // but preserve it in case the contract uses semantic classes
                // $el->removeAttribute('class'); // uncomment if still getting overflow
            }

            $html = $dom->saveHTML();
            
            // Strip html/body/doctype wrappers DOMDocument may have added
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

    /**
     * Format contract description as paragraphs for better PDF layout
     */
    protected function formatDescriptionAsParagraphs($description, $preserveNbsp = false)
    {
        if (empty($description)) {
            return '';
        }

        if ($preserveNbsp) {
            $description = str_replace('&nbsp;', ' ', $description);
        }

        $blockTags = [
            'p','div','o:p','br','h1','h2','h3','h4','h5','h6',
            'ul','ol','li','table','thead','tbody','tfoot','tr','td','th',
            'section','article','aside','footer','header','nav','figure','figcaption','main','address','pre','hr'
        ];
        $description = preg_replace('/<\/?(' . implode('|', $blockTags) . ')[^>]*>/i', '', $description);
        $description = preg_replace('/<\/?(span|font|style|xml|st1:.*?)[^>]*>/i', '', $description);
        $description = preg_replace('/<!--.*?-->/s', '', $description);
        $description = preg_replace('/\s*\n\s*/', "\n", $description);
        $description = trim($description);

        $paragraphs = preg_split('/\r?\n\r?\n|\r?\n/', $description);
        $html = '';
        foreach ($paragraphs as $para) {
            $para = trim($para);
            if ($para !== '') {
                $para = strip_tags($para, '<b><strong><i><u><a>');
                if (preg_match('/^[A-Z\s]+$/', $para) && strlen($para) < 80) {
                    $html .= '<p style="font-weight:bold; font-size:14pt; margin-top:18px; margin-bottom:8px;">' . $para . '</p>';
                } else {
                    $html .= '<p>' . $para . '</p>';
                }
            }
        }
        return $html;
    }
}