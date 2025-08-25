//+------------------------------------------------------------------+
//|                                        TradesCopierMasterMT4.mq4 |
//|                                                   Copyright 2025 |
//|                                             https://www.mql5.com |
//+------------------------------------------------------------------+
#property copyright "Copyright 2025"
#property link      "https://www.mql5.com"
#property version   "1.00"
#property strict
//+------------------------------------------------------------------+
//| Expert initialization function                                   |
//+------------------------------------------------------------------+

#include <JAson.mqh>

input string token = "";
input string base_server = "https://tradescopier.flowsignal.xyz";

int OnInit() {

   return(INIT_SUCCEEDED);

}

//+------------------------------------------------------------------+
//| Expert deinitialization function                                 |
//+------------------------------------------------------------------+
void OnDeinit(const int reason) {

   
}


void uploadNewTrade(string ticketId, double openPrice, double lotSize, double takeProfit, double stopLoss) {

   Print("Ticket ID : " + ticketId);
   Print("Order Open Price : " + DoubleToStr(openPrice));
   Print("Order Lot Size : " + DoubleToStr(lotSize));
   Print("Order Take Profit : " + DoubleToStr(takeProfit));
   Print("Order Stop Loss : " + DoubleToStr(stopLoss));

   string cookie = NULL,headers;
   char post[], result[];
   int res;
   
   string new_trade_post_url = base_server + "/api/post/master/trade/data";
   
   //Adding Post Data on Data Post
   string data_post_params = "?token=" + token + "&openPrice=" + DoubleToStr(openPrice) + "&lotSize=";
   data_post_params += DoubleToStr(lotSize) + "&takeProfit=" + DoubleToStr(takeProfit);
   data_post_params += "&stopLoss=" + DoubleToStr(stopLoss) + "&symbol=" + OrderSymbol();
   data_post_params += "&ticketId=" + ticketId + "&tradeType=" + DoubleToStr(OrderType());
   
   StringReplace(data_post_params, " ", "%20");
   
   ResetLastError();
   int timeout = 500; 
   res = WebRequest("POST", new_trade_post_url + data_post_params, cookie, NULL, timeout, post, 0, result, headers);
   
   if(res == -1) {
      int errorCode = GetLastError();
      Print("WebRequest failed. Error code: ", errorCode, " - ", ErrorDescription(errorCode));
      // Handle specific errors if needed
      if(errorCode == 4060) Print("Error: Invalid URL or endpoint not found");
      else if(errorCode == 4014) Print("Error: Timeout reached");
   } else {
      Print(res);
      Print("Error Occured");
   }

}

//+------------------------------------------------------------------+
//| Expert tick function                                             |
//+------------------------------------------------------------------+
void OnTick()
{
    static int lastTotalOrders = 0;
    
    int totalOrders = OrdersTotal();
    
    if (totalOrders > lastTotalOrders) 
    {
        for (int i = lastTotalOrders; i < totalOrders; i++) 
        {
            if (OrderSelect(i, SELECT_BY_POS)) 
            {
                // Check if it's a new trade
                if (OrderType() == OP_BUY || OrderType() == OP_SELL) 
                {
                    Print("New trade detected: ", OrderTicket());
                    
                    string ticketId = DoubleToStr(OrderTicket());
                    double openPrice = OrderOpenPrice();
                    double lotSize = OrderLots();
                    double takeProfit = OrderTakeProfit();
                    double stopLoss = OrderStopLoss();
                
                    // Upload New Trade Opened
                    uploadNewTrade(ticketId, openPrice, lotSize, takeProfit, stopLoss);
                }
                
            }
        }
    }
    
    lastTotalOrders = totalOrders;
}
//+------------------------------------------------------------------+



//+------------------------------------------------------------------+
//| Function to return human-readable error description             |
//+------------------------------------------------------------------+
string ErrorDescription(int error_code) {
   switch(error_code) {
      case 0:   return "No error";
      case 1:   return "No error; request completed";
      case 2:   return "Common error";
      case 3:   return "Invalid trade parameters";
      case 4:   return "Trade server is busy";
      case 5:   return "Old version of the trading platform";
      case 6:   return "No connection to the trade server";
      case 7:   return "Not enough memory";
      case 8:   return "Old account";
      case 9:   return "Invalid account";
      case 10:  return "Trade timeout";
      case 11:  return "Trade denied";
      case 12:  return "Invalid order type";
      case 13:  return "Invalid price";
      case 14:  return "Invalid stops";
      case 15:  return "Trade context busy";
      case 16:  return "Trade expired";
      case 17:  return "Trade rejected";
      case 18:  return "Unknown error";
      case 19:  return "Invalid symbol";
      case 20:  return "Invalid lots";
      case 21:  return "Invalid price or volume";
      case 22:  return "Invalid account or password";
      case 23:  return "Not enough margin";
      case 24:  return "Account blocked";
      case 25:  return "Not enough rights";
      case 26:  return "Order price too high";
      case 27:  return "Order price too low";
      case 28:  return "Market closed";
      case 29:  return "Invalid stop loss or take profit";
      case 30:  return "Invalid order expiration";
      case 31:  return "No funds";
      case 32:  return "Volume too high";
      case 33:  return "Volume too low";
      case 34:  return "No connection to server";
      case 35:  return "Account locked";
      default:  return "Unknown error code";
   }
}


int GetHttpResponseCode(string headers)
{
   int code = 0;
   int startPos = StringFind(headers, "HTTP/1.1 ");
   if(startPos != -1)
   {
      startPos += 9; // Length of "HTTP/1.1 "
      int endPos = StringFind(headers, " ", startPos);
      if(endPos == -1) endPos = StringFind(headers, "\r\n", startPos);
      if(endPos != -1)
      {
         string codeStr = StringSubstr(headers, startPos, endPos - startPos);
         code = (int)StringToInteger(codeStr);
      }
   }
   return code;
   
}



