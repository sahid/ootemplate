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


class upper extends OOTemplate_FiltersAdapter 
{
	public function resolve ($string)
	{
		return strtoupper ($string);
	}
}

class ucfirst extends OOTemplate_FiltersAdapter 	
{
	public function resolve ($string)
	{
		return ucfirst ($string);
	}
}

class lower extends OOTemplate_FiltersAdapter 
{
	public function resolve ($string)
	{
		return strtolower ($string);
	}
}

class escape extends OOTemplate_FiltersAdapter 
{
	public function resolve ($string)
	{
		return htmlentities ($string, ENT_QUOTES, 'UTF-8');
	}
}

class nl2br extends OOTemplate_FiltersAdapter 
{
	public function resolve ($string)
	{
		return nl2br ($string);
	}
}

class date extends OOTemplate_FiltersAdapter 
{
	public function resolve ($date, $args)
	{
		if (!is_numeric ($date))
			$date = strtotime ($date);
		return strftime (implode (' ', $args), $date);
	}
}



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
	public function resolve ($string, $args)
	{
		$limit = isset ($args[0]) ? (int) $args[0]    : 20;
		$break = isset ($args[1]) ? (string) $args[1] : '';
		
		if (strlen ($string) > $limit)
			return substr ($string, 0, $limit).$break;
		return $string;
	}
}

class default extends OOTemplate_FiltersAdapter 
{
	public function resolve ($string, $args)
	{
		if (is_null ($args))
			throw new OOTemplate_Exception ("Missing argument 2 for ifundefined filter\n");
		
		if (empty ($string))
			return $args[0];
		return $string;
	}
}
