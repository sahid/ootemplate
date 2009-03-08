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
require_once ('OOTemplate_Node.php');
require_once ('OOTemplate_Token.php');


class OOTemplate_Document 
{
	
	/**
	 * String template
	 *
	 * @var string
	 */
	protected $_contents;

	/**
	 * Contener of token
	 *
	 * @var array
	 */
	protected $_tokens;
	
	/**
	 * Constructor set up,
	 * get a contents of template {@link $_contents}
	 */
	public function __construct ($file)
	{
		$fullpath = OOTemplate_Setting::generate_path ($file);
		if (!file_exists ($fullpath))
			throw new OOTemplate_Exception ("template {$file}, not found in {$fullpath}\n");
		
		$this->_contents = file_get_contents ($fullpath);
		
		$this->_tokenize ();
	}

	/**
	 * Get a current token in a tokens conterner
	 *
	 */
	public function curr_token ()
	{
		return $this->_curr_token;
	}
	
	/**
	 * This methode, split a template string in a token
	 *
	 */
	protected function _tokenize ()
	{
		$this->_tokens = array ();
		
		foreach (preg_split (sprintf ('/(%s.*?%s|%s.*?%s|%s.*?%s)/s', 
																	OOTemplate::BEGIN_BLOCK, OOTemplate::END_BLOCK,
																	OOTemplate::BEGIN_VARIABLE, OOTemplate::END_VARIABLE,
																	OOTemplate::BEGIN_COMMENT, OOTemplate::END_COMMENT
																	), $this->_contents, 
												 -1, PREG_SPLIT_DELIM_CAPTURE) as $bit)
			{
				switch (substr ($bit, 0, 2))
					{
					case OOTemplate::BEGIN_BLOCK:
						$bit   = trim ($bit, OOTemplate::BEGIN_BLOCK.' '.OOTemplate::END_BLOCK);
						$token = new OOTemplate_TokenBlock (trim ($bit, '{% %}'));
						break;
					case OOTemplate::BEGIN_VARIABLE:
						$bit   = trim ($bit, OOTemplate::BEGIN_VARIABLE.' '.OOTemplate::END_VARIABLE);
						$token = new OOTemplate_TokenVariable ($bit);
						break;
					case OOTemplate::BEGIN_COMMENT:
						$token = new OOTemplate_TokenComment ('');
						break;
					default:
						$token = new OOTemplate_TokenText ($bit);
					}
				$this->_tokens[] = $token;
			}
	}

	/**
	 * Parse all tokens, to create object nodes
	 * 
	 * @param array $search, is a list of search tokens
	 * @return array, all nodes generates by token
	 */
	public function parse (array $search = array ())
	{
		$nodes = array ();
		while ($token = @array_shift ($this->_tokens))
			{
				$this->_curr_token = $token;
				
				switch (get_class ($token))
					{
					case 'OOTemplate_TokenBlock':
						if (in_array ($token->contents (), $search))
							return $nodes;
						
						list ($node_type, $name) = $token->split ();
						switch ($node_type)
							{
							case 'extends':
								$this->_parent = new OOTemplate_Document (trim ($name, '"'));
								$nodes = $this->_parent->parse ();
								break;
							default:
								$class_name  = 'OOTemplate_Node'.ucfirst ($node_type);
								$file_name   = $class_name.'.php';
								
								$nodes[$name] = call_user_func (array ($class_name, 'prepare'), 
																								$token, $this);
							}
						break;
					case 'OOTemplate_TokenText':
						$nodes[] = new OOTemplate_NodeText ($token);
						break;
					case 'OOTemplate_TokenVariable':
						$nodes[] = new OOTemplate_NodeVariable ($token);
						break;
					}
			}
		return $nodes;
	}
	
	/**
	 * Render a template contents result
	 *
	 * @return string
	 */
	public function render (OOTemplate_Context $context)
	{
		$result = "";
		foreach ($this->parse () as $node)
			$result.= $node->render ($context);

		return $result;
	}
}
