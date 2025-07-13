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
   Print("Order Open Price : " + openPrice);
   Print("Order Lot Size : " + lotSize);
   Print("Order Take Profit : " + takeProfit);
   Print("Order Stop Loss : " + stopLoss);

   string cookie = NULL,headers;
   char post[], result[];
   int res;
   
   string new_trade_post_url = base_server + "/api/post/master/trade/data";
   
   //Adding Post Data on Data Post
   string data_post_params = "?token="+token+"&openPrice="+openPrice+"&lotSize=";
   data_post_params += lotSize + "&takeProfit="+takeProfit+"&stopLoss="+stopLoss+"&symbol="+OrderSymbol();
   data_post_params += "&ticketId="+ticketId+"&tradeType="+OrderType();
   
   StringReplace(data_post_params, " ", "%20");
   
   ResetLastError();
   int timeout = 500; 
   res = WebRequest("POST", new_trade_post_url + data_post_params, cookie, NULL, timeout, post, 0, result, headers);
   

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
                    
                    string ticketId = OrderTicket();
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

