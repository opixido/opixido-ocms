<div id="rubrique_base_image">

	<? if($this->get('lien_image')) { ?>
		<a href="<?=$this->get('lien_image');?>" onclick="return doblank(this)">
			<img src="<?=$this->get('url_image');?>" alt="<?=$this->get('titre_image');?>" />
		</a>
	<? } else { ?>
		<img src="<?=$this->get('url_image');?>" alt="<?=$this->get('titre_image');?>" />
	<? } ?>		
	
	<div><?=$this->get('titre_image');?> </div>
	
	<div class="clearer">&nbsp;</div>
	
</div>