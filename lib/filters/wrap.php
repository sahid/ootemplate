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
require_once ('OOTemplate_FiltersAdapter.php');



	/**
	 * Wraps a string to a given number of characters
	 *
	 * <code>
	 * $c->my_text = abcdef;
	 * {{ my_text | wrap 3 ... }}
	 * // output : abc...
	 * </code>
	 */
class wrap extends OOTemplate_FiltersAdapter 
{
	public function resolve (OOTemplate_Context $context)
	{
		$limit = isset ($this->_args[0]) ? (int) $this->_args[0]    : 20;
		$break = isset ($this->_args[1]) ? (string) $this->_args[1] : '';
		
		if (strlen ($this->_resolved) > $limit)
			return substr ($this->_resolved, 0, $limit).$break;
		return $this->_resolved;
	}
}
