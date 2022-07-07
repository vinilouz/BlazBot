
import asyncio
import configparser
from telethon import TelegramClient, events

config = configparser.ConfigParser()
config.read("config.ini")

# Setting configuration values
api_id = config['Telegram']['api_id']
api_hash = config['Telegram']['api_hash']
username = config['Telegram']['username']

# client = TelegramClient(username, api_id, api_hash)


async def main():
    async with TelegramClient(username, api_id, api_hash) as client:
        # print((await client.get_me()).username)
        #     ^_____________________^ notice these parenthesis
        #     You want to ``await`` the call, not the username.
        #
        # message = await client.send_message('me', 'Hi!')
        # await asyncio.sleep(5)
        # await message.delete()

        @client.on(events.NewMessage())
        async def handler(event):
            print(event)
            # sender = await event.get_sender()
            # name = utils.get_display_name(sender)
            # print(name)
            # print("-")
            # print(event.message)
            print("==================================================================================================================================")

            # await event.reply('hey')

        await client.run_until_disconnected()

loop = asyncio.get_event_loop()
loop.run_until_complete(main())
