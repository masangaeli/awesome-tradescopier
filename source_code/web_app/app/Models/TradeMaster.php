<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeMaster extends Model
{
    use HasFactory;

    //Fillables
    protected $fillable = [
    
        'id', 'userId', 'masterTitle', 'masterInfo', 'masterSoftware', 'masterToken'
    
    ];

    public function tradeData()
    {
        return $this->hasMany(TradeData::class); 
    }

}
