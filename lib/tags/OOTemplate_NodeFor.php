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
 * You can use this, with an imbricate tags.
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

		try {
			if (is_null ($o) || is_null ($var))
				throw new OOTemplate_Exception (sprintf ("'for' statements should use the format : 'for x in y' : %s",
																								 $this->_token->contents ()));
			
			if (!$context->has_key ($var))
				throw new OOTemplate_Exception (sprintf ("'for' tag received an invalid argument : %s",
																								 $this->_token->contents ()));

			$current = clone $context;
			foreach ($context->get ($var) as $each)
				{
					$current->set ($o, $each->getDicts ());
					foreach ($this->_nodes as $node)
						{
							$result.= $node->render ($current);
						}
				}
		} 
		catch (OOTemplate_Exception $e)	{
			OOTemplate_Debug::show ($e->getMessage ());
		}
		return $result;
	}
}

