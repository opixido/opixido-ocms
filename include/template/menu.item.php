
<li @@id@@ @@style@@ >
	<? if($this->get('lien')) { ?>
	<a @@classa@@ href="@@lien@@">
	<? } ?>
	
	<span class="in">
	
	@@titre@@	
	
	</span>
	
	<? if($this->get('lien')) { ?>
	</a>
	<? } ?>
	<span class="out"></span>
	
	<?=$this->sub ?>
</li>
