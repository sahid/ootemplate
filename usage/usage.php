<?php
ini_set ('include_path', 
  ini_get ('include_path').':'.dirname (__FILE__).'/../lib:');

require 'OOTemplate.php';

$t = new OOTemplate (new OOTemplate_Document ('<html>Hello {% if who %} {{ who }} {% endif %}</html>'));
echo $t->render ()."\n";
// output:
// <html>Hello </html>

$c = $t->getContext ();
$c->who = 'World';

echo $t->render ()."\n";
// output:
// <html>Hello World</html>
