<?php

namespace Vanguard\Imports;

use Vanguard\ExcelData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use DateTime;

class ExcelDataImport implements ToModel, WithHeadingRow
{
    protected $fileType;
    protected $fileIdentifier;
    protected $originalFilename;

    public function __construct($fileType, $fileIdentifier, $originalFilename)
    {
        $this->fileType = $fileType;
        $this->fileIdentifier = $fileIdentifier;
        $this->originalFilename = $originalFilename;
    }



    public function model(array $row)
    {
        // Convert all keys to lowercase for case-insensitive matching
        $row = array_change_key_case($row, CASE_LOWER);

        return new ExcelData([
            'file_identifier' => $this->fileIdentifier,
            'original_filename' => $this->originalFilename,
            'file_type' => $this->fileType,
            'RegisteredOn' => $row['registeredon'] ?? now(), // Provide default value
            'ApprovedBy' => $row['approvedby'] ?? null,
            'CountyName' => $row['countyname'] ?? null,
            'SubCountyName' => $row['subcountyname'] ?? null,
            'LocationName' => $row['locationname'] ?? null,
            'SubLocationName' => $row['sublocationname'] ?? null,
            'Approved' => $row['approved'] ?? 0,
            'Rejected' => $row['rejected'] ?? 0,
            'Supervision' => $row['supervision'] ?? 0,
            'PendingIPRS' => $row['pendingiprs'] ?? 0,
            'IPRSFailed' => $row['iprsfailed'] ?? 0,
            'ValidationCheck' => $row['validationcheck'] ?? 0,
            'Review' => $row['review'] ?? 0,
            'Dwelling' => $row['dwelling'] ?? 0,
            'Demographics' => $row['demographics'] ?? 0,
            'Registration' => $row['registration'] ?? 0,
            'ConsentDeclined' => $row['consentdeclined'] ?? 0,
            'PendingApproval' => $row['pendingapproval'] ?? 0,
            'Total' => $row['total'] ?? 0,
            'ReRegistered' => $row['reregistered'] ?? 0,
            'PendingRegistration' => $row['pendingregistration'] ?? 0,
        ]);
    }
}
