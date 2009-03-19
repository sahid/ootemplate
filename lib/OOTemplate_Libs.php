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
require_once ('OOTemplate_Setting.php');
require_once ('OOTemplate_Context.php');


class OOTemplate_Libs 
{
	protected static $_filters = array ();
	
	public static function setFilter ($name, $path)
	{
		self::$_filters[$name] = $path;
	}
	
	public static function filter ($name, $var, $args)
	{
		if (!array_key_exists ($name, self::$_filters))
			throw new OOTemplate_Exception (sprintf ("Invalid filter: '%s'", $name));
		
		require_once self::$_filters[$name];
		return new $name ($var, $args);
	}
	
	public static function buildFilters ($path)
	{
		$path = rtrim ($path, '\\/ ');
		if (file_exists ($path))
			foreach (scandir ($path) as $file)
				self::setFilter (substr ($file, 0, -4), $path.'/'.$file);
	}
}

OOTemplate_Libs::buildFilters (dirname (__FILE__).'/filters');