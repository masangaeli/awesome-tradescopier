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
        print(f"Status received: {status}")
        
        # Compare the status 
        if status is True:       
            # Bring Mt5 Window to Focus
            tools.activate_window(win_sub_string)

            # Get Trade Data
            tradeDataId = data['tradeDataId']
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
            tools.clear_trade_id_status(tradeDataId, base_api_server, connection_token)
        else:
            print ("Json Status is False\n")
            sleep(1)
    else:
        sleep(1)
        