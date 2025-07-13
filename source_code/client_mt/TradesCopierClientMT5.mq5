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
         
      // Open Buy Order
      cTrade.Buy(copyLotSize, copySymbol, copyOpenPrice, copySlPrice, copyTpPrice, "IPS Trade");
        
   }else if (copyTradeType == "SELL") {
      
      // Open Sell Order
      cTrade.Sell(copyLotSize, copySymbol, copyOpenPrice, copySlPrice, copyTpPrice, "IPS Trade");
         
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

//+------------------------------------------------------------------+
//| Expert tick function                                             |
//+------------------------------------------------------------------+
void OnTick() {

   // Check for New Trades
   check_new_trades();
   
}

//+------------------------------------------------------------------+
