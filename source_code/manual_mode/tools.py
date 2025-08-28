import json, time
import requests
import pyautogui
import pygetwindow as gw

def clear_close_all_trades_op(base_api_server, connection_token):
    # Meta Data URL
    meta_data_url = base_api_server + "/api/client/pull/master/trades/list?token=" + connection_token

    # Make the POST request
    requests.post(meta_data_url)


def get_trades_queue(base_api_server, connection_token):
    # Meta Get Data URL
    meta_get_data_url = base_api_server + "/api/client/pull/master/trades/list?token=" + connection_token

    # Make the GET request
    response = requests.get(meta_get_data_url)

    return response.json()

def initiate_config():
    win_sub_string = ""
    connection_token = ""
    base_api_server = ""
    json_config_file = "config.json"

    with open(json_config_file, 'r', encoding='utf-8') as file:
        data = json.load(file)

        win_sub_string = data['win_sub_string']
        connection_token = data['connection_token']
        base_api_server = data['base_api_server']

    if win_sub_string == "" and connection_token == "" and base_api_server == "":
        print("Please fill Sub String in Json Config")
        print("Please fill Connection Token in Json Config")
        print("Please fill API Server in Json Config")
        exit()

    return [win_sub_string, connection_token, base_api_server]


def clear_trade_id_status(trade_id, base_api_server, connection_token, tradeTicketId):

    latest_trade_file_path = ""
    
    # URL
    meta_update_data_url = base_api_server + "/api/client/post/client/trade/copied"
    
    # Parameters
    params = {
        'token': connection_token,
        'tradeDataId': trade_id
    }

    # POST request
    response = requests.post(
        meta_update_data_url,
        params=params
    )
    
    # Json Response
    return response.json() 
    


def activate_window(window_title):
    try:
        # Find the window by title (partial match) 
        window = gw.getWindowsWithTitle(window_title)[0]
        
        # Additional checks before activation
        if window.isMinimized:
            window.restore()
        
        time.sleep(0.5) 
        
        # Alternative activation method if standard fails
        window.activate()
        time.sleep(0.2)
        
        # Double-check if activation worked
        if not window.isActive:
            # Fallback method - using alt+tab
            pyautogui.hotkey('alt', 'tab')
            time.sleep(0.5)
        
        return True
    
    except IndexError:
        print(f"No window found with title containing: {window_title}")
        return False
    except Exception as e:
        print(f"Error activating window: {e}")
        return False
