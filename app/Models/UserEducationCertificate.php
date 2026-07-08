<?php
namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Model;

class UserEducationCertificate extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'institution',
        'award',
        'year',
        'file_path',
    ];
}
