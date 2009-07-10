
<li <?=$this->get('id')?> <?=$this->get('style')?> ><? if($this->get('lien')) { ?><a <?=$this->get('classa')?> href="<?=$this->get('lien')?>"><? } ?><?=$this->get('titre')?><? if($this->get('lien')) { ?></a><? } ?><?=$this->get('sub')?></li>
