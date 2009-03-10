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


final class OOTemplate_Context  implements Iterator
{
	protected $_dicts;

	public $autoescape;

	public function __construct ($data = array (), $autoescape = null)
	{	
		
		$this->autoescape = OOTemplate_Setting::$autoescape;
		if (!is_null ($autoescape))
			$this->autoescape = (bool) $autoescape;
		$this->_dicts = array ();
		$this->set ($data);
	}

	public function getDicts ()
	{
		return $this->_dicts;
	}
	
	public function __set ($key, $value)
	{
		$this->set ($key, $value);
	}

	public function __get ($key)
	{
		return $this->get ($key);
	}
	
	public function set ($key, $value = null)
	{
		if (is_array ($key) || is_object ($key))
			{
				foreach ($key as $k => $v)
					$this->set ($k, $v);
			}
		else
			$this->_dicts[$key] = $this->_toContext ($value);
		
		return $this;
	}

	private function _toContext ($data)
	{
		if (is_array ($data) || is_object ($data))
			return new OOTemplate_Context ($data, $this->autoescape);
		return $data;
	}

	public function get ($key, $default = null)
	{
		if (array_key_exists ($key, $this->_dicts))
			return $this->_dicts[$key];
		return $default;
	}

	public function has_key ($key)
	{
		return $this->get ($key) ? true : false;
	}
	
	/** iterable */
	public function rewind () {	reset ($this->_dicts); }
	public function current () { return current ($this->_dicts); }
	public function key () { return key ($this->_dicts); }
	public function next () {	next ($this->_dicts); }
	public function valid () { return (bool) $this->current (); }
}
