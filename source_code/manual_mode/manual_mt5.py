# Place Trades in MT5 in Manual Mode
#
# Written By: Elijah E. Masanga
# Date: 15th July 2025
#
# Belongs to Steven - Upwork Client
#
# Developed as a Part of Trade Copier to 
# Enable Trade Copier to Place Trades in Manual Mode

import tools
import pyautogui
from time import sleep

# Initiate Config
config = tools.initiate_config()

# Get Config Info
win_sub_string = config[0]
connection_token = config[1]
base_api_server = config[2]

while True:
    # Get Trades in Queue
    data = tools.get_trades_queue(base_api_server, connection_token)
    
    # Check if 'status' exists in the response
    if 'status' in data:
        status = data['status']
        special_command = data['special_command']
        print(f"Status received: {status}")
        
        if special_command == "CLOSE_ALL":
            # Bring Mt5 Window to Focus
            tools.activate_window(win_sub_string)
        
            # Select Tool Box
            pyautogui.click(x=217, y=584) 

            # Select One Trade
            print("Select One Trade")
            pyautogui.press('x')
            sleep(1)

            # Right Click
            print("Right Click")
            pyautogui.hotkey('shift', 'f10')
            sleep(1)

            # Bulk Actions
            print("Select Bulk Actions")
            pyautogui.press('b')
            sleep(1)
            
            # Press Enter to Close All Trades
            print("Close All Trades")
            pyautogui.press('enter')
            sleep(1)

            # Update Special Operation Completed
            tools.clear_close_all_trades_op(base_api_server, connection_token)


        elif special_command == "TRADES_LIST":
            # Compare the status 
            if status is True:       
                # Bring Mt5 Window to Focus
                tools.activate_window(win_sub_string)

                # Get Trade Data
                tradeDataId = data['tradeDataId']
                tradeTicketId = data['ticketId']
                lot_size = data['lotSize']
                trade_symbol = data['symbol']
                stop_loss = data['slPrice']
                take_profit = data['tpPrice']
                open_price = data['openPrice']
                order_type = data['tradeType']

                # Open Place Trade Window
                print("Open Place Trade Window")
                pyautogui.press('f9')

                # Choosing Trade Symbol
                print("Choosing Trade Symbol")
                pyautogui.hotkey('ctrl', 'a')

                pyautogui.write(trade_symbol, 0.1)

                # Put Lot Size
                pyautogui.press('tab')
                pyautogui.press('tab')

                pyautogui.write(lot_size, 0.1)

                # Put Stop Loss
                pyautogui.press('tab')
                pyautogui.write(stop_loss, 0.1)

                # Put Take Profit
                pyautogui.press('tab')
                pyautogui.write(take_profit, 0.1)

                if order_type == "SELL":
                    pyautogui.press('tab')
                    pyautogui.press('tab') 

                    # Write Trade Comment - Original Trade ID
                    pyautogui.write(tradeTicketId, 0.1)

                    pyautogui.press('tab')

                    sleep(1)
                    pyautogui.press('space')
                    sleep(1)

                    pyautogui.hotkey('alt', 'tab')
                    sleep(0.5)

                    pyautogui.hotkey('alt', 'tab')
                    sleep(0.5)
                    
                    pyautogui.press('escape')
                    sleep(0.5)

                elif order_type == "BUY":
                    pyautogui.press('tab')
                    pyautogui.press('tab')

                    # Write Trade Comment - Original Trade ID
                    pyautogui.write(tradeTicketId, 0.1)
                    
                    pyautogui.press('tab')
                    pyautogui.press('tab')
                    pyautogui.press('space')
                    sleep(1)

                    pyautogui.hotkey('alt', 'tab')
                    sleep(0.5)

                    pyautogui.hotkey('alt', 'tab')
                    sleep(0.5)
                    
                    pyautogui.press('escape')
                    sleep(0.5)

                # Clear Trade Id
                tools.clear_trade_id_status(tradeDataId, base_api_server, connection_token, tradeTicketId)
            else:
                print ("Json Status is False\n")
                sleep(1)
    else:
        sleep(1)
        