<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\TradeData;
use App\Models\TradeMaster;
use App\Models\TradeMasterClientConnection;


class TradeMasterController extends Controller
{
    //postTradeClosedAction
    public function postTradeClosedAction(Request $request)
    {
        $this->validate([
            'positionId' => 'required'
        ]);

        $positionId = $request->positionId;

        // Get Trade Data with this Position ID
        $tradeDataQ = TradeData::where([
                        ['ticketId', '=', $positionId],
                        ['tradeSource', '!=', 'MASTER_REF']
                        ])->get()->toArray();

        foreach ($tradeDataQ as $tradeData) {
            $updateTData = TradeData::find($tradeData['id']);
            $updateTData->closeStatus = "-1"; // Closed
            $updateTData->update();
        }

        return response()->json(array('status' => True), 200);

    }

    //postNewClientMasterConnection
    public function postNewClientMasterConnection(Request $request)
    {
        //Input Validation
        $request->validate([
            'clientAccountId' => 'required',
            'masterAccountId' => 'required'
        ]);

        $clientAccountId = $request->clientAccountId;
        $masterAccountId = $request->masterAccountId;
        $lotSize = $request->lotSize;
        $symbolKeyword = $request->symbolKeyword;

        if ($lotSize < 0.01) {
            $lotSize = "SYNC";
        }

        if (strlen($symbolKeyword) == 0) {
            $symbolKeyword = "SYNC";
        }

        // Add New Connection
        $newConnection = new TradeMasterClientConnection();
        $newConnection->userId = Auth::User()->id;
        $newConnection->masterId = $masterAccountId;
        $newConnection->clientId = $clientAccountId;
        $newConnection->lotSize = $lotSize;
        $newConnection->symbolKeyword = $symbolKeyword;

        if ($newConnection->save()) {
            $message = "New Trade Master Client Connection Have Been Added Successfully.";
            return redirect()->back()->with(['successMessage' => $message]);
        }else {
            $message = "Failed to Add New Trade Master Client Connection. Please Try Again Later";
            return redirect()->back()->with(['errorMessage' => $message]);
        }
        
    }


    //postNewTradeForMaster
    public function postNewTradeForMaster(Request $request)
    {
        //Input Validation
        $request->validate([
            'token' => 'required'
        ]);

        $token = $request->token;

        $tradeType = $request->tradeType;
        $symbol = $request->symbol;
        $openPrice = $request->openPrice;
        $lotSize = $request->lotSize;
        $takeProfit = (double) $request->takeProfit;
        $stopLoss = (double) $request->stopLoss;
        $ticketId = $request->ticketId;

        // Check Master With This Token
        $getMaster = TradeMaster::where('masterToken', $token)->get()->toArray();

        if (sizeof($getMaster) == 1) {

            $tradeMasterId = $getMaster['0']['id'];
            $userId = $getMaster['0']['userId'];

            $checkMasterWithTicket = TradeData::where([
                ['tradeSource', '=', 'MASTER_REF'],
                ['ticketId', '=', $ticketId],
                ['tradeSourceId', '=', $tradeMasterId]
            ])->get();

            if (sizeof($checkMasterWithTicket) == 0) {

                if ($tradeType == "0") {
                    $tradeType = "BUY";
                }else if ($tradeType == "1") {
                    $tradeType = "SELL";
                }

                // Store Trade for Reference if No Client 
                $newMasterRefTrade = new TradeData();
                $newMasterRefTrade->userId = $userId;
                $newMasterRefTrade->tradeSource = "MASTER_REF";
                $newMasterRefTrade->isMaster = "1";
                $newMasterRefTrade->tradeSourceId = $tradeMasterId;

                $newMasterRefTrade->tradeType = $tradeType;
                $newMasterRefTrade->symbol = $symbol;
                $newMasterRefTrade->lotSize = $lotSize; 
                $newMasterRefTrade->openPrice = $openPrice; 
                $newMasterRefTrade->slPrice = $stopLoss;
                $newMasterRefTrade->tpPrice = $takeProfit;
                $newMasterRefTrade->ticketId = $ticketId;
                $newMasterRefTrade->save();

                // Get Clients Connected to This Master
                // Then Add Trade Data to Their Accounts
                $clientMasterConnectedList = TradeMasterClientConnection::where('masterId', $tradeMasterId)->get();

                foreach($clientMasterConnectedList as $clientCon) {

                    // Dealing with Symbol Sync
                    if ($clientCon->symbolKeyword != "SYNC") {

                        $splitSymbolDot = explode(".", $symbol);

                        // Replace Symbol with New Symbol 
                        if (sizeof($splitSymbolDot) == 2) {
                            $symbol = $splitSymbolDot[0] .  $clientCon->symbolKeyword;
                        }else {
                            $symbol = $symbol .  $clientCon->symbolKeyword;
                        }
                    }


                    // Dealing with Lot Size Sync
                    if ($clientCon->lotSize != "SYNC") {
                        $lotSize = $clientCon->lotSize;
                    }
                    
                    $newTradeData = new TradeData();
                    $newTradeData->userId = $userId;
                    $newTradeData->tradeSource = "";
                    $newTradeData->isMaster = "1";
                    $newTradeData->tradeSourceId = $tradeMasterId;

                    $newTradeData->tradeClient = "";
                    $newTradeData->tradeClientId = $clientCon->clientId;

                    $newTradeData->tradeType = $tradeType;
                    $newTradeData->symbol = $symbol;
                    $newTradeData->lotSize = $lotSize;
                    $newTradeData->openPrice = $openPrice; 
                    $newTradeData->slPrice = $stopLoss;
                    $newTradeData->tpPrice = $takeProfit;
                    $newTradeData->ticketId = $ticketId;
                    $newTradeData->copyStatus = 0; // Not Copied
                    $newTradeData->closeStatus = 0; // Opened
                    $newTradeData->save();
                }

            }

            return response()->json(array('status' => True), 201);

        }else {

            return response()->json(array(
                    'status' => False, 
                    'message' => 'Master does not Exist'
                ), 200);
        }
        
    }

    //deleteMasterAccount
    public function deleteMasterAccount(Request $request)
    {
        $masterAccountId = $request->masterAccountId;

        // Delete Master Account
        $deleteTradeMaster = TradeMaster::find($masterAccountId);
        $deleteTradeMaster->delete();

        return response()->json(array('status' => True), 200);
    }


    //postUpdateMaster
    public function postUpdateMaster(Request $request)
    {
        //Input Validation
        $request->validate([
            'masterAccountId' => 'required',
            'title' => 'required',
            'mtVersion' => 'required',
        ]);

        $title = $request->title;
        $info  = $request->info;
        $mtVersion = $request->mtVersion;
        $masterAccountId = $request->masterAccountId;

        // Update Master
        $updateMaster = TradeMaster::find($masterAccountId);
        $updateMaster->masterTitle = $title;
        $updateMaster->masterInfo = $info;
        $updateMaster->masterSoftware = $mtVersion;

        if ($updateMaster->save()) {
            $message = "Master Account Have Been Updated Successfully.";
            return redirect()->back()->with(['successMessage' => $message]);
        }else {
            $message = "Failed to Create New Master Account. Please Try Again Later";
            return redirect()->back()->with(['errorMessage' => $message]);
        }
    }

    //postNewMaster
    public function postNewMaster(Request $request)
    {
        //Input Validation
        $request->validate([
            'title' => 'required',
            'mtVersion' => 'required',
        ]);

        $title = $request->title;
        $info  = $request->info;
        $mtVersion = $request->mtVersion;

        // Create New Master
        $newMaster = new TradeMaster();
        $newMaster->userId = Auth::User()->id;
        $newMaster->masterTitle = $title;
        $newMaster->masterInfo = $info;
        $newMaster->masterSoftware = $mtVersion;

        if ($newMaster->save()) {
            //Generate Token for Master
            $tokenG = md5($newMaster->id);

            //Update Token Data
            $tokenUpdate = TradeMaster::find($newMaster->id);
            $tokenUpdate->masterToken = $tokenG;
            $tokenUpdate->update();

            $message = "Master Account Have Been Created Successfully.";
            return redirect()->back()->with(['successMessage' => $message]);
        
        }else {
            $message = "Failed to Create New Master Account. Please Try Again Later";
            return redirect()->back()->with(['errorMessage' => $message]);
        }
    }
}
