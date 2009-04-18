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


require_once ('OOTemplate_Node.php');


class OOTemplate_NodeVariable extends OOTemplate_Node
{
	public function __construct (OOTemplate_TokenVariable $token)
	{
		$this->_token    = $token;
		
		@list ($varname, $filters) = explode ('|', $token->contents (), 2);
		
		$this->_variable = new OOTemplate_Variable ($varname);
		if ($filters)
			$this->_variable = new OOTemplate_Filter ($filters, $this->_variable);
	}
	
	public function render (OOTemplate_Context $context)
	{
		return $this->_variable->resolve ($context);
	}
}
