<?php

require __DIR__ . '/../vendor/autoload.php';

use Discord\Discord;
use Dotenv\Dotenv;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$discord = new Discord([
  'token' => $_ENV['DISCORD_BOT_TOKEN'],
  'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT 
]);


$discord->on('ready', function ($discord) {
    echo "Bot is ready!";

    // Listen for messages
    $discord->on('message', function ($message, $discord) {
      $content = $message -> content;
      if(strpos($content, '!') === false) return;

      if($content === '!sayhi') {
        $message->reply('hi there!');
      }

  });
});

$discord->run();
