    <link rel="start" title="<?=$this->get('acceuil')?>" href="<?=BU?>/" />

    <?php 
    /*<!--
    <link lang="<?=$this->get('other_lg')?>"
	title="<?=$this->get('other_version')?>"
	type="text/html"
	rel="alternate"
	hreflang="<?=$this->get('other_lg')?>"
	href="<?=$this->get('other_url')?>" />	
	-->*/
	?>
	
	<title><?=$this->get('title')?></title>

	<meta name="keywords" content=<?=alt($this->get('keywords'))?> />
	<meta name="description" content=<?=alt($this->get('desc'))?> />
	
	<?=$this->get('headers')?>
	