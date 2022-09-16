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
api_url = 'http://localhost.robot2/wp-json/blaze/v1/crash_signals';
# api_url = 'https://blazerobot.vip/wp-json/blaze/v1/crash_signals';


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

# 
@client.on(events.NewMessage(incoming=True))
async def handler(event):
    sender = await event.get_sender()
    title = utils.get_display_name(sender)

    print('title: ' + title)
    print('senderId: ' + str(sender.id))
    
    # STICKER
    if(event.message.sticker):
        # StickerID = event.message.sticker.attributes[1].stickerset.id
        StickerID = event.message.media.document.id

        print('StickerID: ' + str(StickerID))
  
    # TEXT
    else:
        print('Message: ' + event.text)

    # print(event)
    # print("---------------------------------")
    print(event.date.strftime("%H:%M - %d/%m/%Y"))
    print("=================================================================")


with client:
    print('(Press Ctrl+C to stop this)')
    client.run_until_disconnected()
