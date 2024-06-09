
# Feed-RSS-DS

Feed-RSS-DS is a simple Discord bot that allows you to set RSS URLs and send the posts to Discord channels. It is written in PHP using discord-php for bot functionality and SimplePie for RSS feed handling.


## Installation Steps:
    - Clone the repository.
    - Create an .env file and set DISCORD_BOT_TOKEN=<yourbottoken>.
    - Create a directory named cache at the root for the bot to store posts and track sent posts.


## How to Run
    1. Set up your Discord app with the necessary permissions to read channel messages.
    2.	Ensure you have PHP (version 8.1.10), Composer, and the PHP ext-xml extension enabled.

## To Run the Bot: Execute the following command:
bash
  
```php bot/bot.php```

Note: The bot saves posts in a folder named cache to manage old posts. The code structure will be improved in future updates, but it's currently operational 😺.
