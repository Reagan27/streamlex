<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['name', 'bank_code'];

    public function userBankDetails()
    {
        return $this->hasMany(UserBankDetail::class);
    }
}
