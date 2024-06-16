<?php

use SimplePie\SimplePie;

function handleRSSFeed($discord, $content, $feedUrl, $feed, $lastCheck, $lastItem, $postCount, $cachedCount, $message)
{
  $feedUrl = trim(str_replace('!getFeed', '', $content)); // Extrae la URL del mensaje
  echo 'Obteniendo feed de: ' . $feedUrl . "\n";
  $message->channel->sendMessage("Estamos obteniendo el feed mi rey👑 achanta hasta que llegue un post nuevo de {$feedUrl} 😌");
  // Crea una nueva instancia de SimplePie para este canal
  $feed = new SimplePie();
  $lastCheck = new DateTime();
  $lastItem = null;
  $postCount = 0;
  $cachedCount = 0;

  $feed->set_feed_url($feedUrl);
  $feed->init();
  $feed->handle_content_type();

  // Comprueba el feed cada minuto
  $discord->loop->addPeriodicTimer(60, function () use ($discord, $feed, &$lastCheck, &$lastItem, &$postCount, &$cachedCount, $message) {
    echo 'Escaneando para nuevos posts... \n';
    $feed->init();
    $feed->handle_content_type();

    foreach ($feed->get_items() as $item) {
      $itemDate = new DateTime($item->get_gmdate());
      echo 'Post obtenido: ' . $item->get_permalink() . "\n";

      // Si el artículo es más reciente que la última comprobación, envía un mensaje
      if ($itemDate > $lastCheck) {
        $message->channel->sendMessage($item->get_permalink());
        $lastItem = $item;
        $postCount++;
        echo 'Nuevo post enviado a Discord. Total de posts enviados: ' . $postCount . "\n";
        $lastCheck = new DateTime(); // Actualiza $lastCheck aquí
      } else if ($item == $lastItem) {
        echo 'Post antiguo encontrado: ' . $item->get_title() . "\n";
        $cachedCount++;
        echo 'Total de posts en caché: ' . $cachedCount . "\n";
      }
    }
  });
}
