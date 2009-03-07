<?php

require_once ('simpletest/unit_tester.php');
require_once ('simpletest/reporter.php');

require_once ('../lib/OOTemplate.php');
require_once ('../lib/OOTemplate_Context.php');

class OOTemplate_test extends UnitTestCase
{
	public function test_getContext ()
	{
		$ootemplate = new OOTemplate;
		$context    = $ootemplate->getContext ();

		$this->assertTrue ($context instanceof OOTemplate_Context);
	}

	public function test_set ()
	{
		$ootemplate  = new OOTemplate;
		$context     = $ootemplate->getContext ();
		
		$user_object           = new stdClass;
		$user_object->nickname = 'foobar';
		$res = $ootemplate->set ('user', $user_object);
		$this->assertTrue ($res instanceof OOTemplate_Context);
		$this->assertTrue (is_object ($context->user));
		$this->assertEqual ($context->user->nickname, 'foobar');

		$user_array = array ();
		$user_array['nickname'] = 'foobar';
		$res = $ootemplate->set ('user', $user_object);
		$this->assertTrue ($res instanceof OOTemplate_Context);
		$this->assertTrue (is_object ($context->user));
		$this->assertEqual ($context->user->nickname, 'foobar');
	}

	public function test_render ()
	{
		$ootemplate = new OOTemplate;
		$output     = $ootemplate->render ('test');
		
		$this->assertTrue (is_string ($output));
	}
}

$test = new OOTemplate_test;
$test->run (new TextReporter);



?>