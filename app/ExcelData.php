<?php
namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model 
{
    use HasFactory;

    protected $fillable = [
        'file_identifier',
        'original_filename',
        'file_type',
        'RegisteredOn',
        'ApprovedBy',
        'CountyName',
        'SubCountyName',
        'LocationName',
        'SubLocationName',
        'Approved',
        'Rejected',
        'Supervision',
        'PendingIPRS',
        'IPRSFailed',
        'ValidationCheck',
        'Review',
        'Dwelling',
        'Demographics',
        'Registration',
        'ConsentDeclined',
        'PendingApproval',
        'Total',
        'ReRegistered',
        'PendingRegistration',
    ];

    protected $casts = [
        'RegisteredOn' => 'datetime',
    ];

    public function setRegisteredOnAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['RegisteredOn'] = \Carbon\Carbon::parse($value);
        } else {
            $this->attributes['RegisteredOn'] = $value;
        }
    }
}