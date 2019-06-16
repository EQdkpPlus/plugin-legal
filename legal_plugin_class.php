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
	header('HTTP/1.0 404 Not Found'); exit;
}

/*+----------------------------------------------------------------------------
  | legal
  +--------------------------------------------------------------------------*/
class legal extends plugin_generic {

	public $version				= '1.0.5';
	public $build				= '';
	public $copyright			= 'GodMod';

	protected static $apiLevel	= 23;

	/**
	* Constructor
	* Initialize all informations for installing/uninstalling plugin
	*/
	public function __construct(){
		parent::__construct();

		$this->add_data(array (
			'name'				=> 'Legal',
			'code'				=> 'legal',
			'path'				=> 'legal',
			'template_path'			=> 'plugins/legal/templates/',
			'icon'				=> 'fa fa-gavel',
			'version'			=> $this->version,
			'author'			=> $this->copyright,
			'description'			=> $this->user->lang('legal_short_desc'),
			'long_description'		=> $this->user->lang('legal_long_desc'),
			'homepage'			=> EQDKP_PROJECT_URL,
			'manuallink'			=> false,
			'plus_version'			=> '2.2',
		));

		$this->add_dependency(array(
			'plus_version'      => '2.2'
		));

		$this->add_permission('a', 'settings',		'N', $this->user->lang('menu_settings'),		array(2,3));

		//Routing
		$this->routing->addRoute('PrivacyPolicy', 'privacypolicy', 'plugins/legal/page_objects');
		$this->routing->addRoute('LegalNotice', 'legalnotice', 'plugins/legal/page_objects');

		$this->add_hook('portal', 'legal_portal_hook', 'portal');
		
		// -- Menu --------------------------------------------
		$this->add_menu('admin', $this->gen_admin_menu());

		$this->add_menu('main', $this->gen_main_menu());

		$this->tpl->css_file($this->root_path.'plugins/legal/templates/base_template/legal.css');
	}

	/**
	* pre_install
	* Define Installation
	*/
	public function pre_install(){
	}


	/**
	* post_uninstall
	* Define Post Uninstall
	*/
	public function post_uninstall(){
	}

	/**
	* gen_admin_menu
	* Generate the Admin Menu
	*/
	private function gen_admin_menu(){
		$admin_menu = array (array(
			'name'	=> $this->user->lang('legal'),
			'icon'	=> 'fa fa-gavel',
			1 => array (
					'link'	=> 'plugins/legal/admin/settings.php'.$this->SID,
					'text'	=> $this->user->lang('settings'),
					'check'	=> 'a_legal_settings',
					'icon'	=> 'fa-wrench'
			),
		));
		return $admin_menu;
	}

	/**
	* gen_admin_menu
	* Generate the Admin Menu
	*/
	private function gen_main_menu(){

		$main_menu = array(
			1 => array (
				'link'		=> $this->routing->build('PrivacyPolicy', false, false, true, true),
				'text'		=> $this->user->lang('lg_privacypolicy'),
			),
			
			2 => array (
				'link'		=> $this->routing->build('LegalNotice', false, false, true, true),
				'text'		=> $this->user->lang('lg_notice'),
			),
			
		);
		return $main_menu;
	}
}
?>