<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TradeData;

class TradeDataController extends Controller
{
    // deleteAllTradeData
    public function deleteAllTradeData(Request $request)
    {
        $userId = $request->userId;

        // Delete Trade Data
        $tradeDatas = TradeData::where([
            ['userId', '=', $userId]
        ])->get()->toArray();

        foreach($tradeDatas as $tradeData) {
            $deleteTradeData = TradeData::find($tradeData['id']);
            $deleteTradeData->delete();
        }

        return response()->json(array('status' => True), 200);
    }

    // deleteTradeData
    public function deleteTradeData(Request $request)
    {
        $tradeDataId = $request->tradeDataId;

        // Delete Trade Data
        $deleteTradeData = TradeData::find($tradeDataId);
        $deleteTradeData->delete();

        return response()->json(array('status' => True), 200);
    }
}
