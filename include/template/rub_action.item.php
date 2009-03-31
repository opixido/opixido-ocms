<li>
	<span class="titre_action"><?=$this->get('titre');?></span><br />
	<h1><a href="<?=$this->get('url_fiche');?>"><?=$this->get('accroche');?></a></h1><br />
	<span class="porteurs_action"><?=$this->get('porteurs');?></span><br />
	<div>
		<a id="lien_action_fiche" href="<?=$this->get('url_fiche');?>"><img src="/img/base/picto_fiche.gif" alt="<?=t('alt_lien_fiche_action');?>" /></a>
		<a id="lien_action_print" href="<?=$this->get('url_print_action');?>" onclick="return smallPopup(this)"><img src="/img/base/printBlack.gif" alt="<?=t('print_action');?>" title="<?=t('print_action');?>" /></a>
		<a href="<?=$this->get('url_site');?>" onclick="return doblank(this)"><img src="/img/base/picto_web.gif" alt="<?=t('alt_lien_site_action');?>" /></a>
	</div>
</li>