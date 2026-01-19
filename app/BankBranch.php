<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;
    
    protected $fillable = ['bank_id', 'branch_name', 'branch_code'];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}