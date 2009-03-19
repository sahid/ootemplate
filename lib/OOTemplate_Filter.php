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
require_once ('OOTemplate_Libs.php');
require_once ('OOTemplate_Variable.php');


class OOTemplate_Filter
{
	/**
	 * OOTemplate_Variable
	 *
	 */
	protected $_variable;

	/**
	 * String of filters
	 *
	 */
	protected $_filters;

	/**
	 * Constructor.
	 *
	 */
	public function __construct ($filters_string,
															 OOTemplate_Variable $variable)
	{
		$this->_filters  = $filters_string;
		$this->_variable = $variable;		
	}

	public function resolve (OOTemplate_Context $context)
	{
		$resolved = $this->_variable->resolve ($context);
		foreach (explode ('|', $this->_filters) as $filter)
			{
				$filter = trim ($filter);
				if (!empty ($filter))
					{
						@list ($filter_name, $args) = explode (':', $filter, 2);
						try {
							$resolved = OOTemplate_Libs::filter ($filter_name, 
																									 $resolved, $args)->resolve ($context);
						}
						catch (OOTemplate_Exception $e)	{
							OOTemplate_Debug::show ($e->getMessage ());
						}
					}
			}
		return $resolved;
	}

}
