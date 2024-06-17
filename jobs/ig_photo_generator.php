<?php

use Spatie\Browsershot\Browsershot;

function generateImage($backgroundUrl, $photoMessage)
{
  echo "image, " . $backgroundUrl . " \n";
  $htmlContent = "
  <div class=\"img_generator\">
    <div class=\"backgrond_img\"><img src='{$backgroundUrl}' alt=\"\"></div>
    <div class=\"gradient_color\"></div>
    <div class=\"photo_message\">
      <h1>{$photoMessage}</h1>
    </div>
    <div class=\"site_footer\">
      <img src=\"https://capygamer.com/wp-content/uploads/2024/06/logo-light.png\" alt=\"\">
    </div>
  </div>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    .img_generator {
      width: 1080px;
      height: 1350px;
      overflow: hidden;
      position: relative;
      margin-left: -8px;
      margin-top: -8px;
    }

    .img_generator .backgrond_img {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .img_generator .backgrond_img img {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: 50% 50%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .img_generator .gradient_color {
      background-image: linear-gradient(#ff00001c, #ffc700fc);
      width: 100%;
      height: 100%;
      position: absolute;
      z-index: 1;
      top: 0;
      opacity: 1;
    }

    .img_generator .photo_message { 
      position: absolute;
      top: 50%;
      width: 100%;
      left:0; 
      transform: translateY(70%);
      z-index: 2;
    }

    .img_generator .photo_message h1 {
      font-family: 'Montserrat', sans-serif;
      font-optical-sizing: auto;
      font-weight: bold;
      font-style: normal;
      color: #fff;
      font-size: 48px;
      text-align: center;
      padding: 0 40px;
    }

    .img_generator .site_footer {
      position: absolute;
      bottom: 20px;
      z-index: 4;
      left: 50%;
      transform: translateX(-50%);
    }

    .img_generator .site_footer img {
      width: 230px;
      height: auto;
     }
  </style>

    
  ";

  $fileName = 'image.png';
  Browsershot::html($htmlContent)
    // ->setNodeBinary('/root/.nvm/versions/node/v20.14.0/bin/node')
    // ->setNpmBinary('/root/.nvm/versions/node/v20.14.0/bin/npm')
    // ->noSandbox()
    ->windowSize(1080, 1350)
    ->save($fileName);

  return [
    'fileName' => $fileName,
    'htmlContent' => $htmlContent
  ];
}
