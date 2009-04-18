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
 * if you need use 'and' or 'or' tags, please use an imbricated if statement
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
				if ($bit == 'not') {
					$not = !$not;
					continue;

				}

				try	{
					if (($var_exists = $context->get ($bit, null)) === null)
						throw new OOTemplate_Exception ();
					$parse = !empty ($var_exists) && !$not;
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
