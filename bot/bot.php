<?php

require __DIR__ . '/../vendor/autoload.php';

use Discord\Discord;
use Dotenv\Dotenv;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use SimplePie\SimplePie;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$discord = new Discord([
  'token' => $_ENV['DISCORD_BOT_TOKEN'],
  'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT 
]);

$feed = new SimplePie();
$lastCheck = new DateTime();
$lastItem = null;
$postCount = 0;
$cachedCount = 0;

$discord->on('ready', function ($discord) use ($feed, &$lastCheck, &$lastItem, &$postCount, &$cachedCount)  {
    echo "Bot is ready! \n";

    // Listen for messages
    $discord->on('message', function ($message, $discord) use ($feed, &$lastCheck, &$lastItem, &$postCount, &$cachedCount) {
      $content = $message -> content;
      if(strpos($content, '!') === false) return;

      if($content === '!sayhi') {
        $message->reply('hi there!');
      }

      if(strpos($content, '!getFeed') === 0) {
        $feedUrl = trim(str_replace('!getFeed', '', $content)); // Extrae la URL del mensaje
        echo 'Obteniendo feed de: ' . $feedUrl . "\n";
        $feed->set_feed_url($feedUrl);
        $feed->init();
        $feed->handle_content_type();

        // Comprueba el feed cada minuto
        $discord->loop->addPeriodicTimer(60, function () use ($discord, $feed, &$lastCheck, &$lastItem, &$postCount, &$cachedCount, $message) {
            echo 'Escaneando para nuevos posts... \n';
            $feed->init();
            $feed->handle_content_type();

            foreach ($feed->get_items() as $item) {
                $itemDate = new DateTime($item->get_date());
                echo 'Post obtenido: ' . $item->get_permalink() . "\n"; 
                // $message->channel->sendMessage($item->get_permalink());

                // Si el artículo es más reciente que la última comprobación, envía un mensaje
                if ($itemDate > $lastCheck) {
                    $message->channel->sendMessage($item->get_permalink());
                    $lastItem = $item;
                    $postCount++;
                    echo 'Nuevo post enviado a Discord. Total de posts enviados: ' . $postCount . "\n";
                } else if ($item == $lastItem) {
                    echo 'Post antiguo encontrado: ' . $item->get_title() . "\n";
                    $cachedCount++;
                    echo 'Total de posts en caché: ' . $cachedCount . "\n";
                }
            }

            $lastCheck = new DateTime();
        });
      }

  });
});

$discord->run();
