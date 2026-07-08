<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_number',
        'id_photo_path',
        'kra_pin',
        'kra_certificate_path',
        'shif_number',
        'shif_document_path',
        'nssf_number',
        'nssf_document_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($userDocument) {
            $userDocument->id_number = strtoupper($userDocument->id_number);
            $userDocument->kra_pin = strtoupper($userDocument->kra_pin);
        });
    }
}