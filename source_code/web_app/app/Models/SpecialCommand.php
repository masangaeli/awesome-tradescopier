<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCommand extends Model
{
    use HasFactory;

    // Fillables
    protected $fillable = [

        'id', 'userId', 'clientId', 'clearedStatus'

    ];
}
