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
require_once ('OOTemplate_Filters.php');


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
	public function __construct ($variable)
	{
		@list ($this->_variable, $this->_filters) = explode ('|', $variable, 2);
		$this->_variable = trim ($this->_variable);		
		$this->_filters  = trim ($this->_filters);
	}
	
	public function resolve (OOTemplate_Context $context)
	{
		$resolved = clone $context;
		
		foreach (explode ('.', $this->_variable) as $bit)
			{
				try {
					$resolved = OOTemplate::getattr ($bit, $resolved);
				}
				catch (OOtemplate_Exception $e)	{
					return OOTemplate_Setting::$string_ifnot_defined;
				}
			}
		
		if ($context->autoescape)
			$resolved = htmlspecialchars (stripslashes ($resolved), 
																		ENT_COMPAT, OOTemplate_Setting::$charset);
		
		foreach (explode ('|', $this->_filters) as $filter)
			if (!empty ($filter))
				{
					@list ($filter, $args) = explode (' ', $filter, 2);
					if (method_exists ('OOTemplate_Filters', $filter))
						$resolved = OOTemplate_Filters::$filter ($resolved, explode (' ', $args));
				}
		return $resolved;
	}

}
