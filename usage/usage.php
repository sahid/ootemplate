<?php
ini_set ('include_path', 
  ini_get ('include_path').':'.dirname (__FILE__).'/../lib:');

require 'OOTemplate.php';

$t = new OOTemplate ('<html>Hello {% if who %} {{ who }} {% endif %}</html>');
$t->render ();
// output:
// <html>Hello </html>

$c = $t->getContext ();
$c->who = 'World';
$t->render ();
// output:
// <html>Hello World</html>
