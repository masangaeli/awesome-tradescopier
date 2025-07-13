<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeClient extends Model
{
    use HasFactory;

    //Fillables
    protected $fillable = [
      
       'id', 'userId', 'clientTitle', 'clientInfo', 'clientTradeComment',
       
       'clientSoftware', 'clientToken', 'zeroSL', 'zeroTP'
        
    ];

    public function tradeData()
    {
        return $this->hasMany(TradeData::class); 
    }
}
