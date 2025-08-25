<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeData extends Model
{
    use HasFactory;

    // tradeSource -> MT4/MT5/cTrader

    // Fillables
    protected $fillable = [
        
        'id', 'userId', 'tradeSource', 'tradeSourceId', 'isMaster',

        'ticketId', 'tradeClient', 'tradeClientId', 'lotSize',
        
        'tradeType', 'symbol', 'openPrice', 'slPrice', 'tpPrice',

        'copyStatus', 'closeStatus'

    ];

    // Add New Trade from Trading View 

    // Fill Master MT4/MT5 
    // Allow the MT4/MT5 to be source if Trade is Initiated Directly from MT4/MT5

    // Generate Trade Data for Clients

    // Fill Clients for MT4/MT5/cTrader



    // Relation Ship to Trade Master
    public function tradeMaster()
    {
        return $this->belongsTo(TradeMaster::class, 'tradeSourceId');
    }


    // Relation Ship to Trade Client
    public function tradeClient()
    {
        return $this->belongsTo(TradeClient::class, 'tradeClientId');
    }


    
}
