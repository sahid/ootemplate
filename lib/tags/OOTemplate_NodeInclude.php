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


class OOTemplate_NodeInclude extends OOTemplate_Node
{
	public static function prepare (OOTemplate_Token $token, $dom = null)
	{
		return new OOTemplate_NodeInclude ($token);
	}
	
	public function __construct (OOTemplate_Token $token)
	{
		$this->_token = $token;
	}
	
	public function render (OOTemplate_Context $context)
	{
		try {
			list (, $arg) = $this->_token->split ();
			if (strpos ($arg, "'") === false && strpos ($arg, '"') === false)
				{
					$o    = new OOTemplate_Variable ($arg);
					$file = $o->resolve ($context);
				}
			else
				$file = trim ($arg, '\'" ');
			$result = @file_get_contents (OOTemplate_Setting::generate_inc_path ($file));
			if ($result === false)
				throw new OOTemplate_Exception (vsprintf ('failed to open stream: %s, check OOTemplate_Setting::$dir_include', $file));
		}
		catch (OOTemplate_Exception $e) {
			OOTemplate_Debug::show ($e->getMessage ());
		}
		return $result;
	}
}
