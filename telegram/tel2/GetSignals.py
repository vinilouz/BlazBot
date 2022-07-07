from telethon.sync import TelegramClient
import datetime
import pandas as pd
import configparser

# Reading Configs
config = configparser.ConfigParser()
config.read("../config.ini")

# Setting configuration values
api_id = config['Telegram']['api_id']
api_hash = config['Telegram']['api_hash']
api_hash = str(api_hash)

# chats = ['cryptodubai7', 'Verasity_Official']
chats = [1750202189, 1299783467]


client = TelegramClient('session', api_id, api_hash)


df = pd.DataFrame()


for chat in chats:
    with TelegramClient('session', api_id, api_hash) as client:
        for message in client.iter_messages(chat, offset_date=datetime.date.today(), reverse=True):
            print(message)
            data = {"group": chat, "sender": message.sender_id,
                    "text": message.text, "date": message.date}

            temp_df = pd.DataFrame(data, index=[1])
            df = df.append(temp_df)

df['date'] = df['date'].dt.tz_localize(None)

df.to_excel("D:\\Programas\\wamp64\\www\\robot2\\telegram\\tel2\\data_{}.xlsx".format(
    datetime.date.today()), index=False)
