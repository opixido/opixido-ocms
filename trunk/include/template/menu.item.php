
<li <?=$this->get('id')?> <?=$this->get('style')?> ><? if($this->get('lien')) { ?><a <?=$this->get('classa')?> href="<?=$this->get('lien')?>"><? } ?><span><?=$this->get('titre')?></span><? if($this->get('lien')) { ?></a><? } ?><?=$this->get('sub')?></li>
