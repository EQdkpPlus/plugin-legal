<?php
/*	Project:	EQdkp-Plus
 *	Package:	legal Plugin
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

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');exit;
}

/*+----------------------------------------------------------------------------
  | legal_portal_hook
  +--------------------------------------------------------------------------*/
if (!class_exists('legal_portal_hook')){
	class legal_portal_hook extends gen_class{

		public function portal(){
			
			if ((int)$this->config->get('show_eu_cookiehint', 'legal') && $this->user->blnFirstVisit){
				
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

					$strContent = $objLegalClass->getCookieHint();
			
				} elseif(file_exists($strFallback)){
					include_once($strFilename);
					$strClassName = 'legal_'.$strVersion.'_english';

					$objLegalClass = register($strClassName);

					$strContent = $objLegalClass->getCookieHint();
				} else {
					$strContent = "";
				}

				//Replace Variables
				$strContact = $this->config->get('contact', 'legal');
				if($strContact) $strContact = nl2br($strContact);
				if($this->config->get('main_title')){
					$main_title	= $this->config->get('main_title');
				}else {
					$main_title = sprintf($pt_prefix, $this->config->get('guildtag'), $dkp_name);
				}

				$strPrivacyPolicyLink = $this->routing->build('PrivacyPolicy');
				$strContent = str_replace(array('{CONTACT}', '{PAGENAME}', '{COOKIE_LINK}'), array($strContact, $main_title, $strPrivacyPolicyLink), $strContent);

				
				
				$this->core->global_warning($strContent, 'fa-info-circle', 'blue legal-cookie-eu-hint', true);
			}

			
		}
	}
}
?>