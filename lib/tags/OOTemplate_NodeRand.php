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
 * Get a random value in array with RandNode
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
		list (, $var,, $key) = $bits;

		$data = $context->get ($key, null);
		if (is_null ($data) || !($data instanceof Traversable))
			throw new OOTemplate_Exception (sprintf ("'rand' tag received an invalid argument : %s",
																							 $this->_token->contents ()));
		$current = clone $context;
		try {
			$data = (array) $data->getDicts ();;
			shuffle ($data);
			$current->set ($var, current ($data));
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


