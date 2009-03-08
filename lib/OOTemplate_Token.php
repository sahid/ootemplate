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



abstract class OOTemplate_Token
{
	protected $_contents;
	protected $_split;
	
	public function __construct ($contents)
	{
		$this->_contents = $contents;
	}

	public function split ()
	{
		if (is_null ($this->_split))
			{
				$split = array ();
				foreach (explode (' ', $this->_contents) as $value)
					if (!empty ($value))
						$split[] = $value;
				$this->_split = $split;
			}
		return $this->_split;
	}
	
	public function contents ()
	{
		return $this->_contents;
	}
}

class OOTemplate_TokenText extends OOTemplate_Token 
{}
class OOTemplate_TokenBlock extends OOTemplate_Token 
{}
class OOTemplate_TokenVariable extends OOTemplate_Token 
{}
class OOTemplate_TokenComment extends OOTemplate_Token 
{}
