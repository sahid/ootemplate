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
require_once ('OOTemplate_Debug.php');
require_once ('OOTemplate_Setting.php');
require_once ('OOTemplate_Context.php');



class OOTemplate_Variable
{
	protected $_variable;
	protected $_filters;

	/**
	 * Constructor.
	 *
	 * $context = {article:{section:'News'}}
	 * $v = new OOTemplate_Variable ('article.section');
	 * $v.resolve ($context);
	 * >>> News
	 */
	public function __construct ($varname)
	{
		$this->_variable = trim ($varname);		
	}

	public function resolve (OOTemplate_Context $context)
	{
		$resolved = clone $context;
		try {		
			foreach (explode ('.', $this->_variable) as $bit)
				{
					if (!($resolved instanceof OOTemplate_Context))
						throw new OOTemplate_Exception (sprintf ("Variable is invalid : %s", $this->_variable));
					$resolved = $resolved->get ($bit, OOTemplate_Setting::$string_ifnot_defined);
				}
		} 
		catch (OOTemplate_Exception $e) {
			OOTemplate_Debug::show ($e->getMessage ());
		}
		return $resolved;
	}

}
