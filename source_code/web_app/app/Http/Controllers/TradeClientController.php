<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\TradeData;
use App\Models\TradeClient;
use App\Models\TradeMaster;
use App\Models\TradeMasterClientConnection;

class TradeClientController extends Controller
{

    // pullTradesClosedList
    public function pullTradesClosedList(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $token = $request->token;

        $clientTradeDataQ = TradeClient::where('clientToken', $token)->get()->toArray();

        if (sizeof($clientTradeDataQ) == 1) {

            $clientId = $clientTradeDataQ['0']['id'];

            $tradeDataWithMinusOneQ = TradeData::where([
                                        ['tradeClientId', '=', $clientId],
                                        ['closeStatus', '=', "-1"]
                                        ])
                                        ->get()->toArray();

            if (sizeof($tradeDataWithMinusOneQ) != 0) {

                $ticket_id = $tradeDataWithMinusOneQ['0']['ticketId'];

                return response()->json(array(
                        'status' => True, 
                        'ticket_id' => $ticket_id
                        ), 200);
            }else {

                return response()->json(array(
                    'status' => False
                ), 200);
            }
        }else {

            return response()->json(array('status' => False), 200);

        }

    }

    // postClientTradeClosedAction
    public function postClientTradeClosedAction(Request $request)
    {
        $request->validate([
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
            $updateTData->closeStatus = "2"; // Closed on Client
            $updateTData->update();
        }

        return response()->json(array('status' => True), 200);
    }

    // deleteClientAccount
    public function deleteClientAccount(Request $request)
    {
        $clientAccountId = $request->clientAccountId;

        // Delete Client Account
        $deleteClientAccount = TradeClient::find($clientAccountId);
        $deleteClientAccount->delete();

        return response()->json(array('status' => True), 200);
    }

    //deletePostClientMasterConnection
    public function deletePostClientMasterConnection(Request $request)
    {
        //Input Validation
        $request->validate([
            'clientAccountId' => 'required',
            'masterAccountId' => 'required'
        ]);

        $clientAccountId = $request->clientAccountId;
        $masterAccountId = $request->masterAccountId;

        $tradeConAccount = TradeMasterClientConnection::where([
            ['userId', '=', Auth::User()->id],
            ['masterId', '=', $masterAccountId],            
            ['clientId', '=', $clientAccountId],
        ])->get()->toArray();

        if (sizeof($tradeConAccount) == 1) {

            $deleteAccount = TradeMasterClientConnection::find($tradeConAccount['0']['id']);
            $deleteAccount->delete();

            $message = "Trading Connection Have Been Deleted Successfully.";
            return redirect()->back()->with(['successMessage' => $message]);
        }else {
            $message = "Trading Connection Does Not Exist.";
            return redirect()->back()->with(['errorMessage' => $message]);
        }
    }

    //updatePostClientMasterConnection
    public function updatePostClientMasterConnection(Request $request)
    {

    }


    //clientPostClientTradeCopied
    public function clientPostClientTradeCopied(Request $request)
    {
        // Input Validation
        $request->validate([
            'tradeDataId' => 'required'
        ]);

        $tradeDataId = $request->tradeDataId;

        // Update Trade Data
        $updateTradeData = TradeData::find($tradeDataId);
        $updateTradeData->copyStatus = 1;
      
        if ($updateTradeData->update()) {
            return response()->json(array('status' => True), 201);
        }else {
            return response()->json(array('status' => False), 200);
        }

    }

    //clientPullMasterTradesList
    public function clientPullMasterTradesList(Request $request)
    {
        $token = $request->token;

        // Get Client with Token
        $getClient = TradeClient::where('clientToken', $token)->get()->toArray();

        if (sizeof($getClient) != 0) {

            $clientId = $getClient['0']['id'];

            // Pull Client Open Trades | Copy Status 0
            $openTrade = TradeData::where([
                ['copyStatus', '=', 0],
                ['tradeClientId', '=', $clientId],
                ['tradeSource', '!=', 'MASTER_REF']
            ])->limit(1)->get()->toArray();

            if (sizeof($openTrade) == 1) {
                return response()->json(array(
                    'status' => True, 
                    'tradeDataId' => $openTrade['0']['id'],
                    'tradeSource' => $openTrade['0']['tradeSource'],
                    'tradeSourceId' => $openTrade['0']['tradeSourceId'],
                    'isMaster' => $openTrade['0']['isMaster'],
                    'ticketId' => $openTrade['0']['ticketId'],
                    'tradeClient' => $openTrade['0']['tradeClient'],
                    'tradeClientId' => $openTrade['0']['tradeClientId'],
                    'lotSize' => $openTrade['0']['lotSize'],
                    'tradeType' => $openTrade['0']['tradeType'],
                    'symbol' => $openTrade['0']['symbol'],
                    'openPrice' => $openTrade['0']['openPrice'],
                    'slPrice' => $openTrade['0']['slPrice'],
                    'tpPrice' => $openTrade['0']['tpPrice'],

                ), 200);
            }else {
                return response()->json(array('status' => False), 200);    
            }
        }else {
            return response()->json(array('status' => False), 200);  
        }
    }

    //pullClientsNotAddedMasters
    public function pullClientsNotAddedMasters(Request $request)
    {
        $userId = $request->userId;
        $clientAccountId = $request->clientAccountId;

        $allMasterslist = TradeMaster::where('userId', $userId)->get();
        $allClientMasterList = TradeMasterClientConnection::where([
                                    ['userId', '=', $userId],
                                    ['clientId', '=', $clientAccountId]
                                ])->get();
    
        $newMasterList = array();
        $addedMastersList = array();
        foreach($allMasterslist as $masterM) {
            $masterId = $masterM->id;

            $addMaster = 1;
            foreach($allClientMasterList as $conClientL) {
                if ($masterId == $conClientL->masterId) {
                    $addMaster = 0;
                }
            }

            if ($addMaster == 1) {
                $newMasterList[] = $masterId;
            }else {
                $addedMastersList[] = $masterId;
            }
        }

        $mastersListArray = array();
        // Populate Masters Array
        foreach($newMasterList as $nMasterId) {
            $tradeMasterData = TradeMaster::where('id', $nMasterId)->get()[0];

            $mastersListArray[] = $tradeMasterData;
        }

        $addedMastersListArray = array();
        // Populate Added Masters Array
        foreach($addedMastersList as $nMasterId) {
            $tradeMasterDataNotAdded = TradeMaster::where('id', $nMasterId)->get()[0];

            //Pull Trade Connection Data
            $tradeConnectionDataQ = TradeMasterClientConnection::where([
                ['masterId', '=', $nMasterId],
                ['clientId', '=', $clientAccountId]
            ])->get()->toArray();

            $lotSize = $tradeConnectionDataQ['0']['lotSize'];
            $symbolKeyword = $tradeConnectionDataQ['0']['symbolKeyword'];

            // Deal with Symbol Keyword
            if ( is_null($symbolKeyword) || ($symbolKeyword == "SYNC") ) {
                $symbolKeyword = "";
            }

            $addedMastersListArray[] = array(
                "id" => $tradeMasterDataNotAdded['id'],
                "masterTitle" => $tradeMasterDataNotAdded['masterTitle'],
                "masterInfo" => $tradeMasterDataNotAdded['masterInfo'],
                "masterSoftware" => $tradeMasterDataNotAdded['masterSoftware'],
                "masterToken" => $tradeMasterDataNotAdded['masterToken'],
                "lotSize" => $lotSize,
                "symbolKeyword" => $symbolKeyword,
                "created_at" => $tradeMasterDataNotAdded['created_at'],
                "updated_at" => $tradeMasterDataNotAdded['updated_at']
            );
        }

        return response()->json(array(
            'mastersList' => $mastersListArray,
            'mastersListAdded' => $addedMastersListArray
        ), 200);
    }

    //postUpdateClient
    public function postUpdateClient(Request $request)
    {
          //Input Validation
          $request->validate([
            'clientId' => 'required',
            'title' => 'required',
            'mtVersion' => 'required',
        ]);

        $title = $request->title;
        $info  = $request->info;
        $clientId = $request->clientId;
        $mtVersion = $request->mtVersion;
        $clientTradeComment = $request->clientTradeComment;

        // Update Client
        $updateClient = TradeClient::find($clientId);
        $updateClient->clientTitle = $title;
        $updateClient->clientInfo = $info;
        $updateClient->clientSoftware = $mtVersion;
        $updateClient->clientTradeComment = $clientTradeComment;

        if ($updateClient->save()) {
            $message = "Client Account Have Been Updated Successfully.";
            return redirect()->back()->with(['successMessage' => $message]);
        
        }else {
            $message = "Failed to Update Client Account. Please Try Again Later";
            return redirect()->back()->with(['errorMessage' => $message]);
        }
    }

    //postNewClient
    public function postNewClient(Request $request)
    {
        //Input Validation
        $request->validate([
            'title' => 'required',
            'mtVersion' => 'required',
        ]);

        $title = $request->title;
        $info  = $request->info;
        $mtVersion = $request->mtVersion;
        $clientTradeComment = $request->clientTradeComment;

        // Create New Client
        $newClient = new TradeClient();
        $newClient->userId = Auth::User()->id;
        $newClient->clientTitle = $title;
        $newClient->clientInfo = $info;
        $newClient->clientSoftware = $mtVersion;
        $newClient->clientTradeComment = $clientTradeComment;

        if ($newClient->save()) {
            //Generate Token for Client
            $tokenG = md5($newClient->id);

            //Update Token Data
            $tokenUpdate = TradeClient::find($newClient->id);
            $tokenUpdate->clientToken = $tokenG;
            $tokenUpdate->update();

            $message = "Client Account Have Been Created Successfully.";
            return redirect()->back()->with(['successMessage' => $message]);
        
        }else {
            $message = "Failed to Create New Client Account. Please Try Again Later";
            return redirect()->back()->with(['errorMessage' => $message]);
        }
    }
}
