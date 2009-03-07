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


final class OOTemplate_Filters
{
	public static function upper ($string)
	{
		return strtoupper ($string);
	}

	public static function ucfirst ($string)
	{
		return ucfirst ($string);
	}

	public static function lower ($string)
	{
		return strtolower ($string);
	}

	public static function escape ($string)
	{
		return htmlentities ($string, ENT_QUOTES, 'UTF-8');
	}

	public static function nl2br ($string)
	{
		return nl2br ($string);
	}

	public static function ifundefined ($string, $args)
	{
		if (is_null ($args))
			throw new OOTemplate_Exception ("Missing argument 2 for ifundefined filter\n");

		if (empty ($string))
			return $args[0];
		return $string;
	}
}
