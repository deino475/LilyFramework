# LilyFramework
A simple PHP web microframework


Hello World Example
<pre><code>
<?php
  include 'LilyFramework.php';
  $app = new Lily;

  $app->route('/index',function($data = []) use ($app){
    $app->renderHTML('Hello world');
  });

  $app->start();
?>
</code></pre>
