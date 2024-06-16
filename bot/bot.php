<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../jobs/rss_reader.php';

use Discord\Discord;
use Dotenv\Dotenv;
use Discord\WebSockets\Intents;


$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$discord = new Discord([
  'token' => $_ENV['DISCORD_BOT_TOKEN'],
  'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT
]);



$discord->on('ready', function ($discord) use ($feed, &$lastCheck, &$lastItem, &$postCount, &$cachedCount) {
  echo "Bot is ready! \n";

  // Listen for messages
  $discord->on('message', function ($message, $discord) use ($feed, &$lastCheck, &$lastItem, &$postCount, &$cachedCount) {
    $content = $message->content;
    if (strpos($content, '!') === false) return;

    if ($content === '!sayhi') {
      $message->reply('hi there!');
    }

    if (strpos($content, '!getFeed') === 0) {
      $feedUrl = trim(str_replace('!getFeed', '', $content));
      handleRSSFeed($discord, $content, $feedUrl, $feed, $lastCheck, $lastItem, $postCount, $cachedCount, $message);
    }
  });
});

$discord->run();
