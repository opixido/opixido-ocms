<div id="rubrique_base">

	<?=$this->get('html_images');?>


	<? if($this->get('html_downloads') != '') { ?>
	<div id="bloc_downloads">
		<img src="<?=IMG_GENERATOR?>?text=<?=t('downloads')?>&amp;y=0&amp;textSize=12&amp;x=0&amp;imgW=125&amp;width=130&amp;textColor=000000" alt="<?=t('downloads')?>" />
		<ul>
				<?=$this->get('html_downloads');?>
		</ul>
	</div>
	<? } ?>

</div>