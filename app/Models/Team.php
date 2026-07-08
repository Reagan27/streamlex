<?php
// Team model for Field Activities module
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';
    protected $fillable = ['name'];
}
