//+------------------------------------------------------------------+
//|                                        TradesCopierMasterMT5.mq5 |
//|                        Patched version - Ticket-based detection |
//+------------------------------------------------------------------+
#property copyright "Copyright 2025"
#property link      "https://www.mql5.com"
#property version   "1.02"
#property strict

#include <Arrays\ArrayLong.mqh>

input string token = "";
input string base_server = "https://tradescopier.flowsignal.xyz";

CArrayLong sentTickets; 
datetime lastCloseTime = 0; 

//+------------------------------------------------------------------+
//| Expert initialization                                            |
//+------------------------------------------------------------------+
int OnInit()
{
   return INIT_SUCCEEDED;
}

//+------------------------------------------------------------------+
//| Upload new trade                                                  |
//+------------------------------------------------------------------+
void uploadNewTrade(string ticketId, double openPrice, double lotSize, 
                    double takeProfit, double stopLoss, string symbol, 
                    int tradeType, string tradeComment)
{
   // Print("Ticket ID : " + ticketId);
   // Print("Order Open Price : " + DoubleToString(openPrice, _Digits));
   // Print("Order Lot Size : " + DoubleToString(lotSize, 2));
   // Print("Order Take Profit : " + DoubleToString(takeProfit, _Digits));
   // Print("Order Stop Loss : " + DoubleToString(stopLoss, _Digits));

   string cookie = NULL, headers;
   char post[], result[];
   int res;

   string new_trade_post_url = base_server + "/api/post/master/trade/data";

   string data_post_params = "?token=" + token +
                             "&openPrice=" + DoubleToString(openPrice, _Digits) +
                             "&lotSize=" + DoubleToString(lotSize, 2) +
                             "&takeProfit=" + DoubleToString(takeProfit, _Digits) +
                             "&stopLoss=" + DoubleToString(stopLoss, _Digits) +
                             "&symbol=" + symbol +
                             "&ticketId=" + ticketId +
                             "&tradeType=" + IntegerToString(tradeType) +
                             "&tradeComment=" + tradeComment;

   Print("Upload URL : " + new_trade_post_url);
   Print("Post Params : " + data_post_params);

   StringReplace(data_post_params, " ", "%20");

   ResetLastError();
   int timeout = 500;
   res = WebRequest("POST", new_trade_post_url + data_post_params, cookie, NULL, timeout, post, 0, result, headers);

   if(res == -1)
   {
      int errorCode = GetLastError();
      Print("WebRequest failed. Error code: ", errorCode, " - ", ErrorDescription(errorCode));
      if(errorCode == 4060) Print("Error: Invalid URL or endpoint not found");
      else if(errorCode == 4014) Print("Error: Timeout reached");
   }
   else
   {
      Print("WebRequest sent. Response code: ", GetHttpResponseCode(headers));
   }
}

//+------------------------------------------------------------------+
//| Detect and send new trades                                       |
//+------------------------------------------------------------------+
void OnTick()
{
   int totalPositions = PositionsTotal();

   for(int i = 0; i < totalPositions; i++)
   {
      ulong ticket = PositionGetTicket(i);

      if(ticket > 0 && sentTickets.Search(ticket) == -1)
      {
         if(PositionSelectByTicket(ticket))
         {
            string symbol = PositionGetString(POSITION_SYMBOL);
            string tradeComment = PositionGetString(POSITION_COMMENT);
            double openPrice = PositionGetDouble(POSITION_PRICE_OPEN);
            double lotSize = PositionGetDouble(POSITION_VOLUME);
            double takeProfit = PositionGetDouble(POSITION_TP);
            double stopLoss = PositionGetDouble(POSITION_SL);
            int type = (int) PositionGetInteger(POSITION_TYPE);

            uploadNewTrade(
               IntegerToString((int)ticket),
               openPrice,
               lotSize,
               takeProfit,
               stopLoss,
               symbol,
               type,
               tradeComment
            );

            sentTickets.Add(ticket);
         }
      }
   }
   
}

//+------------------------------------------------------------------+
//| Error description                                                |
//+------------------------------------------------------------------+
string ErrorDescription(int error_code)
{
   switch(error_code)
   {
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

//+------------------------------------------------------------------+
//| Get HTTP response code                                           |
//+------------------------------------------------------------------+
int GetHttpResponseCode(string headers)
{
   int code = 0;
   int startPos = StringFind(headers, "HTTP/1.1 ");
   if(startPos != -1)
   {
      startPos += 9;
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





// On Trade Transactions (orders/deals/positions)
void OnTradeTransaction(const MqlTradeTransaction &trans,
                        const MqlTradeRequest      &request,
                        const MqlTradeResult       &result)
{
   if(trans.type != TRADE_TRANSACTION_DEAL_ADD)
      return;

   long deal_id = trans.deal;
   if(deal_id <= 0)
      return;

   datetime t = (datetime)HistoryDealGetInteger(deal_id, DEAL_TIME);
   HistorySelect(t - 60, TimeCurrent());

   long entry = HistoryDealGetInteger(deal_id, DEAL_ENTRY);
   if(entry != DEAL_ENTRY_OUT && entry != DEAL_ENTRY_INOUT)
      return;

   // Cooldown check: only allow once every 1 minutes (60 seconds)
   if(TimeCurrent() - lastCloseTime < 60)
      return; // skip if less than 5 minutes since last close

   lastCloseTime = TimeCurrent();

   // Get Position ID
   long pos_id = HistoryDealGetInteger(deal_id, DEAL_POSITION_ID);

   // Update Trade Closed
   updateTradeClosed(IntegerToString(pos_id));
}


void updateTradeClosed(string pos_id) {
   
   Print("Close Trade Sync");
   
   string cookie = NULL,headers;
   char post[], result[];
   int res;
      
   string meta_update_data_url = base_server + "/api/post/master/trade/closed/action";
   
   // Data Post Params
   string data_post_params = "?token=" + token + "&positionId=" + pos_id;
   
   StringReplace(data_post_params, " ", "%20");
   
   Print("URL : " + meta_update_data_url + data_post_params);
   
   ResetLastError();
   int timeout = 500; 
   res = WebRequest("POST", meta_update_data_url + data_post_params, cookie, NULL, timeout, post, 0, result, headers);
   
}