<?php
/**
 * @project   : OOTemplate
 * @license   : GNU Lesser General Public License 3
 * @copyright : (c) Ferdjaoui Sahid 2009
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
 * @author    : Ferdjaoui Sahid <sahid.ferdjaoui@gmail.com>
 * @package   : template
 */


require_once ('OOTemplate_Exception.php');
require_once ('OOTemplate_Variable.php');
require_once ('OOTemplate_Context.php');
require_once ('OOTemplate_Filters.php');


abstract class OOTemplate_Node
{
	abstract public function render (OOTemplate_Context $context);
}


class OOTemplate_NodeText extends  OOTemplate_Node
{
	public function __construct (OOTemplate_TokenText $token)
	{
		$this->_token = $token;
	}
	public function render (OOTemplate_Context $context)
	{
		return $this->_token->contents ();
	}
}


class OOTemplate_NodeVariable extends  OOTemplate_Node
{
	public function __construct (OOTemplate_TokenVariable $token)
	{
		$this->_token    = $token;
		$this->_variable = new OOTemplate_Variable ($token->contents ());
	}
	
	public function render (OOTemplate_Context $context)
	{
		return $this->_variable->resolve ($context);
	}
}


class OOTemplate_NodeBlock extends OOTemplate_Node
{
	public static function prepare (OOTemplate_Token $token, $dom = null)
	{
		return new OOTemplate_NodeBlock ($dom->parse (array ('endblock')));
	}
	
	public function __construct (array $nodes)
	{
		$this->_nodes = $nodes;
	}
	
	public function render (OOTemplate_Context $context)
	{
		$result = "";
		foreach ($this->_nodes as $node)
			$result.= $node->render ($context);
		return $result;
	}
}


/**
 * Loops over each item in an array.
 *
 * <code>
 *    <ul>
 *    {% for item in list %}
 *      <li>{{ item }</li>
 *    {% endfor %}
 *    </ul>
 * </code>
 *
 *
 * You can use an imbriqued tags.
 *
 * <code>
 *    <ul>
 *    {% for item in list %}
 *      <li>{{ item }</li>
 *      {% if item.ss_items %}
 *      <ul>
 *        {% for ss_item in item.ss_items %}
 *          <li>{{ ss_item }</li>
 *        {% endfor %}
 *      </ul>
 *      {% endif %}
 *    {% endfor %}
 *    </ul>
 * </code>
 *
 */
class OOTemplate_NodeFor extends OOTemplate_Node
{
	public static function prepare (OOTemplate_Token $token, $dom = null)
	{
		return new OOTemplate_NodeFor ($token, $dom->parse (array ('endfor')));
	}
	
	public function __construct (OOTemplate_Token $token, array $nodes)
	{
		$this->_nodes = $nodes;
		$this->_token = $token;
	}
	
	public function render (OOTemplate_Context $context)
	{
		$result = "";
		list (,$o,,$var) = $this->_token->split ();
		if (is_null ($o) || is_null ($var))
			throw new OOTemplate_Exception (sprintf ("'for' statements should use the format : 'for x in y' : %s",
																							 $this->_token->contents ()));

		if (!$context->has_key ($var))
			throw new OOTemplate_Exception (sprintf ("'for' tag received an invalid argument : %s",
																							 $this->_token->contents ()));
		
		try {
			$current = new OOTemplate_Context ();
			foreach ($context->$var as $each)
				{
					$current->set ($o, $each);
					foreach ($this->_nodes as $node)
						{
							$result.= $node->render ($current);
						}
				}
		} 
		catch (OOTemplate_Exception $e)
			{	}
		return $result;
	}
}


/**
 * Evaluate a variable, if that variable is "true", execute
 * a contents of the block or a else block.
 *
 *  <code>
 *
 *     {% if user %}
 *       <b>Hello {{ user.name | ucfist }}</b>
 *     {% else %}
 *       <b>Hello Guest</b>
 *     {% endif %}
 *
 *  </code>
 *
 * Actually only 'not' tag is active in this statement,
 * if you need use 'and' or 'or' tags, please use an imbriqued if statement
 * for example :
 *
 *  <code>
 *
 *     {% if user %}
 *       {% if is_admin %} 
 *         {{ user.name }} is an admin.
 *       {% endif %}
 *     {% endif %}
 *
 *  </code>
 *
 */
class OOTemplate_NodeIf extends OOTemplate_Node
{
	public static function prepare (OOTemplate_Token $token, $dom = null)
	{
		$el_nodes = array ();
		$if_nodes = $dom->parse (array ('else', 'endif'));
		if ($dom->curr_token ()->contents () == 'else')
			$el_nodes = $dom->parse (array ('endif'));

		return new OOTemplate_NodeIf ($token, $if_nodes, $el_nodes);
	}
	
	public function __construct (OOTemplate_Token $token, array $if_nodes, array $el_nodes)
	{
		$this->_if_nodes = $if_nodes;
		$this->_el_nodes = $el_nodes;
	
		$this->_token = $token;
	}
	
	public function render (OOTemplate_Context $context)
	{

		$bits = $this->_token->split ();
		unset ($bits[0]);
		$result = "";
		$not = false;
		foreach ($bits as $bit)
			{
				switch ($bit)
					{
					case 'not':	$not = true;	continue;
					}
				try	{
					OOTemplate::getattr ($bit, $context);
					$parse = (bool) !$not;
				}
				catch (OOTemplate_Exception $e)	{
					$parse = (bool) $not;
				}
			}

		if ($parse)
			{
				foreach ($this->_if_nodes as $node)
					$result.= $node->render ($context);
			}
		else
			{
				foreach ($this->_el_nodes as $node)
					$result.= $node->render ($context);
			}
		return $result;
	}
}


/**
 * Return a random value in an array.
 *
 * <code>
 *  
 *  {% rand tip in tips %}
 *    <h1>Tips of days</h1>
 *    <h2>{{ tip.title }}<h2/>
 *    <p>{{ tip.contents }}<p/>
 *  {% endrand %}
 *
 * </code>
 */
class OOTemplate_NodeRand extends OOTemplate_Node
{
	public static function prepare (OOTemplate_Token $token, $dom = null)
	{
		return new OOTemplate_NodeRand ($token, $dom->parse (array ('endrand')));
	}
	
	public function __construct (OOTemplate_Token $token, array $nodes)
	{
		$this->_nodes = $nodes;
		$this->_token = $token;
	}
	
	public function render (OOTemplate_Context $context)
	{
		$result = "";
		$bits = $this->_token->split ();
		if (sizeof ($bits) != 4)
			throw new OOTemplate_Exception (sprintf ("'rand' statements should use the format : 'rand var in array' : %s",
																							 $this->_token->contents ()));
		list (, $var,, $arr) = $bits;
		if (!$context->has_key ($arr))
			throw new OOTemplate_Exception (sprintf ("'rand' tag received an invalid argument : %s",
																							 $this->_token->contents ()));
		$current = clone $context;
		try {
			$current->$var = $current->$arr->shuffle ()->current ();
			foreach ($this->_nodes as $node)
				$result.= $node->render ($current);
		} 
		catch (OOTemplate_Exception $e)
			{	}
		return $result;

		foreach ($this->_if_nodes as $node)
			$result.= $node->render ($context);

		return $result;
	}
}


