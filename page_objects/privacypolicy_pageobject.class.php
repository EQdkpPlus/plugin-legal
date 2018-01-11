<?php
/*	Project:	EQdkp-Plus
 *	Package:	MediaCenter Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class privacypolicy_pageobject extends pageobject {
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array();
   	return array_merge(parent::__shortcuts(), $shortcuts);
  }  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('legal', PLUGIN_INSTALLED))
	redirect();   
    
    $handler = array();
    parent::__construct(false, $handler);

    $this->process();
  }

	public function display(){
		$strVersion = $this->config->get('version', 'legal');
		if(!$strVersion) $strVersion = "v1";
		$strVersion = preg_replace("/[^a-zA-Z0-9]/ui", "", $strVersion);

		$strUserLang = preg_replace("/[^a-zA-Z0-9]/ui", "", $this->user->lang_name);

		$strFilename = $this->root_path.'plugins/legal/language/'.$strUserLang.'/'.$strVersion.'.php';
		$strFallback = $this->root_path.'plugins/legal/language/english/'.$strVersion.'.php';
		if(file_exists($strFilename)) {
			include_once($strFilename);
			$strClassName = 'legal_'.$strVersion.'_'.$strUserLang;

			$objLegalClass = register($strClassName);

			$strContent = $objLegalClass->getPrivacyPolicy();
			
		} elseif(file_exists($strFallback)){
			include_once($strFilename);
			$strClassName = 'legal_'.$strVersion.'_english';

			$objLegalClass = register($strClassName);

			$strContent = $objLegalClass->getPrivacyPolicy();
		} else {
			$strContent = "";
		}


		//Additions
		$strAdditions = $this->config->get('add_privacy', 'legal');
		if($strAdditions){
			$strContent .= '<div class="legalPrivacyAdditions">'.$this->bbcode->toHTML($strAdditions).'</div>';
		}

		//Replace Variables
		$strContact = $this->config->get('contact', 'legal');
		if($strContact) $strContact = nl2br($strContact);
		if($this->config->get('main_title')){
			$main_title	= $this->config->get('main_title');
		}else {
			$main_title = sprintf($pt_prefix, $this->config->get('guildtag'), $dkp_name);
		}

		$strContent = str_replace(array('{CONTACT}', '{PAGENAME}'), array($strContact, $main_title), $strContent);

		$this->tpl->assign_vars(array(
			'LEGAL_CONTENT' => $strContent,
		));
			
    		// -- EQDKP ---------------------------------------------------------------
	    $this->core->set_vars(array (
	      'page_title'    => $this->user->lang('lg_privacypolicy'),
	      'template_path' => $this->pm->get_data('legal', 'template_path'),
	      'template_file' => 'legal.html',
    		'page_path'     => [
    				['title'=>$this->user->lang('lg_privacypolicy'), 'url'=> ''],
    		],
	      'display'       => true
	    ));	
	}

}
?>