<?php
/**
 * @project   OOTemplate
 * @license   GNU Lesser General Public License 3
 * @copyright (c) Ferdjaoui Sahid 2009
 *
 * This file is part of OOTemplate.
 * 
 * OOTemplate is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * OOTemplate is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with OOTemplate.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author  Ferdjaoui Sahid <sahid.ferdjaoui@gmail.com>
 * @package template
 */


require_once ('OOTemplate_Exception.php');
require_once ('OOTemplate_Setting.php');
require_once ('OOTemplate_Context.php');
require_once ('OOTemplate_Document.php');

/**
 * This is Oriented Object Template engine.
 *
 * How it works:
 * ...
 *
 * Usage:
 * First you should create a new instance of OOTemplate class,
 * this object contein a OOTemplate_Context object, you will set
 * 
 * Sample code:
 *
 * <code>
 * <?php
 * ini_set ('include_path', 
 *   ini_get ('include_path').':'.dirname (__FILE__).'/../lib:');
 *
 * require 'OOTemplate.php';
 * 
 * $t = new OOTemplate ('<html>Hello {% if who %} {{ who }} {% endif %}</html>');
 * $t->render ();
 *
 * // output:
 * // <html>Hello </html>
 *
 * $c = $t->getContext ();
 * $c->who = 'World';
 * $t->render ();
 *
 * // output:
 * // <html>Hello World</html>
 * </code>
 */
class OOTemplate
{
	/**
	 * The left delimiter used for the template tags.
	 *
	 * @var string
	 */
	const BEGIN_BLOCK = '{%';

	/**
	 * The right delimiter used for the template tags.
	 *
	 * @var string
	 */
	const END_BLOCK = '%}';

	/**
	 * The left delimiter used for the template variables.
	 *
	 * @var string
	 */
	const BEGIN_VARIABLE = '{{';

	/**
	 * The right delimiter used for the template variables.
	 *
	 * @var string
	 */
	const END_VARIABLE = '}}';

	/**
	 * The left delimiter used for the template comments.
	 *
	 * @var string
	 */
	const BEGIN_COMMENT = '{#';

	/**
	 * The right delimiter used for the template comments.
	 *
	 * @var string
	 */
	const END_COMMENT = '#}';

	/**
	 * Context object, for this template's instance
	 *
	 * @var OOTemplate_Context
	 */
	protected $_context;
	
	/**
	 * Constructor set up {@link $_context}
	 */
	public function __construct ($template_file = null)
	{
		$this->_context = new OOTemplate_Context ();
	}

	/**
	 * Set a values in context's template
	 *
	 * @param iterable|string $var_name, the template variable name(s)
	 * @param iterable|string $value, the value of $var_name
	 * @return OOTemplate_Context {@link OOTemplate_Context::set ()}
	 */
	public function setContext (OOTemplate_Context $context)
	{
		$this->_context = $context;
	}

	/**
	 * Return a context's template
	 *
	 * @return OOTemplate_Context {@link $_context}
	 */
	public function getContext ()
	{
		return $this->_context;
	}
	
	/**
	 * Execute and return the template result
	 *
	 * @param string $template_file, the template file or null if alredy defined
	 * @return string an evaluate contents of template
	 */
	public function render ($template_file = null)
	{		
		if (is_null ($template_file))
			$template_file = $this->_template;
		try {
			if (is_null ($template_file))
				throw new OOTemplate_Exception ("You should take a template file");
					
			$dom = new OOTemplate_Document ($template_file);
			return $dom->render ($this->_context);
		}
		catch (OOTemplate_Exception $e)	{
			echo $e->getMessage ();
			exit ();
		}
	}

	/**
	 * Is used to fetch an attribute from an object,
	 * take an exception is $property is not in $object.
	 *
	 * @param string $property, an identifier attribute
	 * @param mixed $object
	 */
	public static function getattr ($property, $object)
	{
		if ($object instanceof OOTemplate_Context &&
				$object->has_key ($property))
			return $object->get ($property);
		throw new OOTemplate_Exception ("This property '{$property}' is not defined in object");
	}
}
