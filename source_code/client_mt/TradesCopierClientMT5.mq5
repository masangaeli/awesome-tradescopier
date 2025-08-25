//+------------------------------------------------------------------+
//|                                        TradesCopierClientMT5.mq5 |
//|                                                   Copyright 2025 |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2025"
#property link      "https://www.mql5.com"
#property version   "1.00"


#include <JAson.mqh>
#include <Trade\Trade.mqh>

input string token = "";
input string base_server = "https://tradescopier.flowsignal.xyz";

CTrade cTrade;

int OnInit() {

   return(INIT_SUCCEEDED);
}

//+------------------------------------------------------------------+
//| Expert deinitialization function                                 |
//+------------------------------------------------------------------+
void OnDeinit(const int reason) {

   
}


// Open New Trade
void openNewTrade(double copyLotSize, string copyTradeType, string copySymbol, double copyOpenPrice, double copySlPrice, double copyTpPrice) {

if (copyTradeType == "BUY") {
    MqlTradeRequest request = {};
    MqlTradeResult result = {};
    
    request.action = TRADE_ACTION_DEAL;
    request.symbol = copySymbol;
    request.volume = copyLotSize;
    request.price = copyOpenPrice;
    request.sl = copySlPrice;
    request.tp = copyTpPrice;
    request.deviation = 3;
    request.type = ORDER_TYPE_BUY;
    request.type_filling = ORDER_FILLING_FOK;
    request.comment = "IPS Trade";
    request.magic = 0;
    
    // Use OrderSend instead of PositionOpen
    if (!OrderSend(request, result)) {
        Print("Failed to open BUY trade. Error: ", GetLastError());
    } else {
        Print("BUY trade opened. Ticket: ", result.order);
    }
}
else if (copyTradeType == "SELL") {
    MqlTradeRequest request = {};
    MqlTradeResult result = {};
    
    request.action = TRADE_ACTION_DEAL;
    request.symbol = copySymbol;
    request.volume = copyLotSize;
    request.price = copyOpenPrice;
    request.sl = copySlPrice;
    request.tp = copyTpPrice;
    request.deviation = 3;
    request.type = ORDER_TYPE_SELL;
    request.type_filling = ORDER_FILLING_FOK;
    request.comment = "IPS Trade";
    request.magic = 0;
    
    // Use OrderSend instead of PositionOpen
    if (!OrderSend(request, result)) {
        Print("Failed to open SELL trade. Error: ", GetLastError());
    } else {
        Print("SELL trade opened. Ticket: ", result.order);
    }
}

}

   

// Clear Copy Status
void clearCopyStatus(string copyTradeDataId) {

   string cookie = NULL,headers;
   char post[], result[];
   int res;
      
   string meta_update_data_url = base_server + "/api/client/post/client/trade/copied";
   
   // Get Client Trades to Copy
   string data_post_params = "?token="+token+"&tradeDataId="+copyTradeDataId;
   
   StringReplace(data_post_params, " ", "%20");
   
   ResetLastError();
   int timeout = 500; 
   res = WebRequest("POST", meta_update_data_url + data_post_params, cookie, NULL, timeout, post, 0, result, headers);
   
}




void check_new_trades_closed() {

   string cookie = NULL,headers;
   char post[], result[];
   int res;
      
   string meta_get_data_url = base_server + "/api/client/pull/trades/closed/list";
   
   // Get Master Trades Closed 
   string data_get_params = "?token="+token;
   
   StringReplace(data_get_params, " ", "%20");
   
   ResetLastError();
   int timeout = 500; 
   res = WebRequest("GET", meta_get_data_url + data_get_params, cookie, NULL, timeout, post, 0, result, headers);
   
   if(res == -1) {
      Print("Error in WebRequest. Error code  =",GetLastError());
      //MessageBox("Add the address '"+meta_post_data_url+"' in the list of allowed URLs on tab 'Expert Advisors'","Error",MB_ICONINFORMATION);
   }else{
      
      Print ("URL : " + meta_get_data_url);
      PrintFormat("The file has been successfully loaded, File size =%d bytes.",ArraySize(result));
      
      string res_str = CharArrayToString(result);
      Print ("Res String : " + res_str);
      
      // Process the JSON Response
      CJAVal json_obj;
      
      // Parse the JSON string
      json_obj.Deserialize(res_str);
   
      // Extract data
      bool status = json_obj["status"].ToBool();
      
      if (status == true) {
         string tickets_list = json_obj["tickets_list"].ToStr();
         
         string tickets_arr[];
         
         int count_tickets = explode(tickets_list, " ", tickets_arr);
         
         for(int i=0; i<count_tickets; i++) {
            // Close Trade with Ticket ID
            closeTradeWithTicketCommentId(tickets_arr[i]);
            
            // Update Trade Closed
            updateTradeClosed(tickets_arr[i]);
         }
      }
   }

}


void updateTradeClosed(string ticketId) {
   Print("Update Trade Comment ID : " + ticketId);
}


void closeTradeWithTicketCommentId(string ticketId) {
   Print("Closing Ticket Comment ID : " + ticketId);
}

//+------------------------------------------------------------------+
//| Custom explode function                                          |
//+------------------------------------------------------------------+
int explode(string source, string delimiter, string &result[])
{
   // StringSplit 
   return StringSplit(source, delimiter, result);
}


void check_new_trades() {

   string cookie = NULL,headers;
   char post[], result[];
   int res;
      
   string meta_get_data_url = base_server + "/api/client/pull/master/trades/list";
   
   // Get Client Trades to Copy
   string data_get_params = "?token="+token;
   
   StringReplace(data_get_params, " ", "%20");
   
   ResetLastError();
   int timeout = 500; 
   res = WebRequest("GET", meta_get_data_url + data_get_params, cookie, NULL, timeout, post, 0, result, headers);
   
   if(res == -1) {
      Print("Error in WebRequest. Error code  =",GetLastError());
      //MessageBox("Add the address '"+meta_post_data_url+"' in the list of allowed URLs on tab 'Expert Advisors'","Error",MB_ICONINFORMATION);
   }else{
      
      Print ("URL : " + meta_get_data_url);
      PrintFormat("The file has been successfully loaded, File size =%d bytes.",ArraySize(result));
      
      string res_str = CharArrayToString(result);
      Print ("Res String : " + res_str);
      
      // Process the JSON Response
      CJAVal json_obj;
      
      // Parse the JSON string
      json_obj.Deserialize(res_str);
   
      // Extract data
      bool status = json_obj["status"].ToBool();
      
      if (status == true) {
         string copyTradeDataId = json_obj["tradeDataId"].ToStr();
         double copyLotSize = json_obj["lotSize"].ToDbl();
         string copyTradeType = json_obj["tradeType"].ToStr();
         string copySymbol = json_obj["symbol"].ToStr();
         double copyOpenPrice = json_obj["open_price"].ToDbl();
         double copySlPrice = json_obj["slPrice"].ToDbl();
         double copyTpPrice = json_obj["tpPrice"].ToDbl();
         
         // Open New Trade
         openNewTrade(copyLotSize, copyTradeType, copySymbol, copyOpenPrice, copySlPrice, copyTpPrice);
         
         // Clear Copy Status
         clearCopyStatus(copyTradeDataId);
      }
   }

}

void check_manual_trades_then_syn() {

}

//+------------------------------------------------------------------+
//| Expert tick function                                             |
//+------------------------------------------------------------------+
void OnTick() {

   // Check for New Trades Placed and Sync Placement Status
   check_manual_trades_then_syn();
   
}

//+------------------------------------------------------------------+