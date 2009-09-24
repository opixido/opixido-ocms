<?php

/**
 * This file is part of oCMS.
 *
 * oCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * oCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with oCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright opixido 2008
 * @link http://opixido.com
 * @package ocms
 */

class genForumListe extends ocmsGen {
	
	/**
	 * Forum
	 *
	 * @var forum
	 */
	public $f;
	
	
	function __construct($site,$params) {
		
		parent::__construct($site,$params);
		$this->site->plugins['colonneGauche']->visible = false;
		
	}	
	
	function afterInit() {
		$this->site->plugins['ocms_title']->omitNb = 1;
		$this->f = new forum();
		$this->site->g_headers->addCss('forum.css');
		$this->site->plugins['colonneGauche']->visible = false;
		$GLOBALS['BLOCS']['jaune']->add('FORUM',genForumTools());
		
	}
	
	function genSearch() {
		
		return genForumSearch();		
		
	}
	
	
	function gen() {
		
		
		if($_GET['forum_search']) {
			return $this->genSearch();	
		}
		
		/**
		 * Liste des thèmes
		 */
		$res = $this->f->getArboThemes($this->site->getCurId());		
		
		/**
		 * Aucun forum ...
		 */
		if(!count($res)) {
			addMessageInfo(t('forum_aucun'));
			return;
		}		
		
		/**
		 * Template
		 */
		$tpl = new genTemplate(true);		
		$tpl->loadTemplate('forum.liste','plugins/ocms_forum/tpl');
		
		/**
		 * Accessiblité du tableau
		 */
		$tpl->set('summary',alt($this->params['summary']));
		$tpl->set('caption',($this->params['caption']));
		
		/**
		 * Liste des forums de niveau 1
		 */
		foreach($res as $row) {
			
			$t = $tpl->addBlock('LEVEL1');
			$t->defineBlocks('LEVEL2');
			
			/**
			 * Titre
			 */
			$t->set('titre',getLgValue('rubrique_titre',$row['row']));
			
			$t->set('sujets',t('forum_sujets'));
			$t->set('messages',t('forum_messages'));
			$t->set('dernier',t('forum_dernier'));
			
			
			/**
			 * Forums de niveau 2
			 */
			if(count($row['subs'])) {
				
				
				foreach($row['subs'] as $v) {
				
					/**
					 * Titre du forum
					 */
					$l = $t->addBlock('LEVEL2');
					
					$theme = new forumTheme($v['row']);
					
					$l->set('titre',$theme->getTitle());
					$l->set('desc',$theme->getDesc());
					$l->set('url',$theme->getUrl());
					
					
					/**
					 * Nombre de posts
					 */
					$l->set('sujets',$v['row']['NBS']);
					$l->set('messages',$v['row']['NBM']);
					
					if($v['row']['message_id']) {
						
						$m = new forumMessage($v['row']);
											
						$l->set('dernier',$m->getTitle());
						$l->set('url_dernier',$m->getUrl());
						$l->set('poste_le',t('forum_le').' '. $m->getDate());
						$l->set('poste_par','<a>'.t('forum_par').'</a> '. $v['row']['utilisateur_login']);
						
					} else {
						
						$l->set('dernier','');
						$l->set('url_dernier','');
						$l->set('poste_le','');
						$l->set('poste_par','');
					
					}
					
				}
				
			}
			
		}
		
		return genForumHead().$tpl->gen();
		
	}	
	
	function ocms_getParams() {
		
		return array('summary' => 'text','caption'=>'text','forum_user_table'=>'text');
		
	}
	

	function ocms_getPicto() {
		
		return ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/apps/system-users.png';
		
	}
	
}