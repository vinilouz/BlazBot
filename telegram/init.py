#!/usr/bin/env python3
# A simple script to print some messages.
import os
import sys
import time
import configparser
import requests
import json
from telethon import TelegramClient, events, utils

# Reading Configs
config = configparser.ConfigParser()
config.read("config.ini")

# Setting configuration values
api_id = config['Telegram']['api_id']
api_hash = config['Telegram']['api_hash']
api_hash = str(api_hash)
username = config['Telegram']['username']
phone = config['Telegram']['phone']
wp_user = config['WP']['username']
wp_pass = config['WP']['password']


def get_env(name, message, cast=str):
    if name in os.environ:
        return os.environ[name]
    while True:
        value = input(message)
        try:
            return cast(value)
        except ValueError as e:
            print(e, file=sys.stderr)
            time.sleep(1)
            

proxy = None  # https://github.com/Anorov/PySocks
client = TelegramClient(username, api_id, api_hash, proxy=proxy).start()

channels_list = [
    # 1299783467, # Blaze Tech
    # 1577414274, # BOT DOUBLE SEM GALE
    1785180053, # Buzz Teste api
    1695064830, # VIP DOUBLE/SEM GALE 🐔
    1515446435, # 💥𝙑𝙄𝙋 𝙁𝙐𝙉𝙄𝙇 𝘽𝙇𝘼𝙕𝙀💥
]

# 
@client.on(events.NewMessage(incoming=True, from_users=channels_list))
async def handler(event):
    sender = await event.get_sender()
    title = utils.get_display_name(sender)
    headers = {
        'Authorization': 'Basic '+wp_user+wp_pass,
        'Username': wp_user,
        'Password': wp_pass
    }

    print(title)
    print(sender.id)
    # Debug
    print('==')

    # CRASH
    if(event.message.sticker):
        StickerID = event.message.sticker.attributes[1].stickerset.id
        # Debug
        print(StickerID)

        # if(StickerID == 8977794405394022401):
        #     crashMessage = 'Entrar agora'
        # elif(StickerID == 8977794405394022401):
        #     crashMessage = 'WIN'
        # elif(StickerID == 4573473239128342603):
        #     crashMessage = 'LOSS'
        
        data = {
            'id': sender.id,
            'title': title,
            'message': str(StickerID),
            'date': event.date.strftime("%Y-%m-%d %H:%M:%S"),
        }
        
        r = requests.post(
            url='https://blazerobot.vip/wp-json/blaze/v1/crash_signals', data=data, headers=headers)
    
    # DOUBLE
    else:
        data = {
            'id': sender.id,
            'title': title,
            'message': event.text,
            'date': event.date.strftime("%Y-%m-%d %H:%M:%S"),
        }

        r = requests.post(
            url='https://blazerobot.vip/wp-json/blaze/v1/double_signals', data=data, headers=headers)

    print(r.text)

    pd = 'date=' + event.date.strftime("%H:%M - %d/%m/%Y")
    print(pd)
    print("=================================================================================")

with client:
    print('(Press Ctrl+C to stop this)')
    client.run_until_disconnected()
