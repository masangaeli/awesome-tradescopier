<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\TradeMaster;
use App\Models\TradeClient;
use App\Models\TradeData;

use Illuminate\Http\Request;

class MainController extends Controller
{
    //tradeData
    public function tradeData()
    {
        $tradeDatas = TradeData::with(['tradeMaster', 'tradeClient'])
                        ->where('userId', Auth::User()->id)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(24);

        return view("pages.tradeData",
            [
                'tradeDatas' => $tradeDatas
            ]);
    }

    //masterAccounts
    public function masterAccounts()
    {
        $totalMasterAccounts = TradeMaster::where('userId', Auth::User()->id)
                                ->get()->count();

        $masterAccounts = TradeMaster::where('userId', Auth::User()->id)
                            ->orderBy('created_at', 'DESC')
                            ->paginate(12);

        return view("pages.masterAccounts",
                [
                    'masterAccounts' => $masterAccounts,
                    'totalMasterAccounts' => $totalMasterAccounts
                ]);
    }

    //clientAccounts
    public function clientAccounts()
    {
        $clientAccounts = TradeClient::where('userId', Auth::User()->id)
                            ->orderBy('created_at', 'DESC')
                            ->paginate(12);

        $totalClients = TradeClient::where('userId', Auth::User()->id)
                            ->get()->count();

        return view("pages.clientAccounts",
            [
                'totalClients' => $totalClients,
                'clientAccounts' => $clientAccounts
            ]);
    }

}
