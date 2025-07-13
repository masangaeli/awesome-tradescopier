<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeMasterClientConnection extends Model
{
    use HasFactory;

    // Fillables
    protected $fillable = [

        'id', 'userId', 'masterId', 'clientId', 'lotSize', 'symbolKeyword'

    ];
    
}
