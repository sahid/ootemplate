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
 

class tolink extends OOTemplate_FilterAdapter 
{
	public function resolve (OOTemplate_Context $context)
	{
		return $this->_tolink ($this->_resolved);
	}


	protected function _tolink ($txt) {
		$txt = strtolower (html_entity_decode ($txt, ENT_QUOTES, 'UTF-8'));
		$txt = str_replace (array ('é','è','ë','ê','à','ä','â','ù','ü','û','ö','ô','ï','ï','ü','û','ç'),
												array ('e','e','e','e','a','a','a','u','u','u','o','o','i','i','u','u','c'),
												$txt);
		return $txt = trim (preg_replace ('/([^a-z0-9])+/', '-', $txt), '-');
	}

}

