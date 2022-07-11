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
username = config['Telegram']['username']
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
    1299783467,  # Blaze Tech
    # 1750202189,  # FireBot
    1785180053  # Buzz Sinais Free
]


@client.on(events.NewMessage(incoming=True, from_users=channels_list))
async def handler(event):
    sender = await event.get_sender()
    title = utils.get_display_name(sender)
    headers = {
        'Authorization': 'Basic '+wp_user+wp_pass,
        'Username': wp_user,
        'Password': wp_pass
    }

    data = {
        'id': sender.id,
        'title': title,
        'message': event.text,
    }

    r = requests.post(
        url='http://localhost.robot2/wp-json/blaze/v1/signals', data=data, headers=headers)

    print(event.text)
    print("=================================================================================")

with client:
    print('(Press Ctrl+C to stop this)')
    client.run_until_disconnected()
