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


require_once ('OOTemplate_Node.php');


class OOTemplate_NodeTag extends OOTemplate_Node
{
	protected $_dom;
	protected $_token;
	protected $_tag;
	
	public function __construct (OOTemplate_Document $dom, OOTemplate_Token $token)
	{
		$this->_dom   = $dom;
		$this->_token = $token;
		$this->_tag   = $this->_prepare ();
	}

	protected function _prepare ()
	{
		list ($node_type, $name) = $this->_token->split ();
		$class_name  = 'OOTemplate_Node'.ucfirst ($node_type);
		$file_name   = $class_name.'.php';
		$file        = dirname (__FILE__).'/tags/' . $file_name;

		if (!file_exists ($file))
			throw new OOTemplate_Exception ("Tag not exists: {$name}");

		require_once $file;
		
		return call_user_func (array ($class_name, 'prepare'), $this->_token, $this->_dom);
	}
	
	public function render (OOTemplate_Context $context)
	{
		return $this->_tag->render ($context);
	}
}
