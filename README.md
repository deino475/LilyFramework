# LilyFramework
A simple PHP web microframework


Hello World Example

<?php
include 'LilyFramework.php';
$app = new Lily;

$app->route('/index',function($data = []) use ($app){
  $app->renderHTML('<h1>Hello world</h1>');
});

$app->start();
?>
