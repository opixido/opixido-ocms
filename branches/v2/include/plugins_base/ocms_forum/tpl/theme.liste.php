<!--<div class="forum_header">
	<a class="forum_retour" href="@@url_retour@@">@@forum_retour_themes@@</a>
	<form class="form_search" action="<?=getUrlWithParams(array())?>" method="get">
		<label for="forum_search"><?=t('forum_this_search')?></label><input type="text" id="forum_search" name="forum_search" value=<?=alt($_GET['forum_search'])?> />
	</form>
	<a class="forum_btn" href="@@url_post@@">@@forum_post@@</a>
</div>
-->
<a class="forum_btnleft" href="@@url_retour@@">@@forum_retour@@</a>

<a class="forum_btn" href="@@url_post@@">@@forum_post@@</a>

<div class="sep clearer"></div>
<table id="forum_liste"  class="forum_table" summary=@@summary@@ >
	
	<caption>@@caption@@</caption>
	
		<tr>
			<th class="titre" scope="col">@@titre@@</th>
			<th class="reponses" scope="col">@@reponses@@</th>
			<th class="vues" scope="col">@@vues@@</th>
			<th class="dernier" scope="col">@@dernier@@</th>
		</tr>
		
		<LIGNE>
			
			<tr class="@@class@@">
				<td class="titre" >
					@@img@@
					<a href="@@url@@">@@titre@@</a>
					<p>@@desc@@</p>
					<span>@@liens@@</span>
				</td>
				<td class="reponses">@@reponses@@</td>
				<td class="vues">@@vues@@</td>
				<td class="dernier">
					<a href="@@url_dernier@@">@@dernier@@</a>
					<span class="poste_le">@@poste_le@@</span>
					<span class="poste_par">@@poste_par@@</span>
				</td>
			</tr>
		
		</LIGNE>	

</table>
<div class="sep clearer"></div>
<div class="forum_footer">
	@@footer@@
</div>
