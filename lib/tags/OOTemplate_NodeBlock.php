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
