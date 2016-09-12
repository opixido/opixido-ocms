
<li @@id@@ @@style@@ >
	<?php if($this->get('lien')) { ?>
	<a @@classa@@ href="@@lien@@">
	<?php } ?>
	
	<span class="in">
	
	@@titre@@	
	
	</span>
	
	<?php if($this->get('lien')) { ?>
	</a>
	<?php } ?>
	<span class="out"></span>
	
	<?=$this->sub ?>
</li>
