<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../jobs/rss_reader.php';
require __DIR__ . '/../jobs/ig_photo_generator.php';


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

    if (strpos($message->content, '!generateImage') === 0) {
      $params = explode(' ', $message->content);
      array_shift($params); // Elimina el comando '!generateImage'
      
      // Asume que el último elemento es la URL del fondo si hay un archivo adjunto
      $backgroundImage = $message->attachments->first() ?? null;
      if ($backgroundImage) {
        $backgroundUrl = $backgroundImage->url;
      }
      
      $title = implode(' ', $params); 
  
      if ($backgroundUrl) {
        $imageData = generateImage($backgroundUrl, $title);
  
        // Envía la imagen generada al canal
        $message->channel->sendFile($imageData['fileName'], "image.png", '');
      } else {
        $message->reply("Por favor adjunta una imagen para usar como fondo.");
      }
    }

  });
});

$discord->run();
