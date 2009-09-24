<!--
<div class="forum_header">
	
	<form class="form_search" action="<?=getUrlWithParams(array())?>" method="get">
		<label for="forum_search"><?=t('forum_search')?></label><input type="text" id="forum_search" name="forum_search" value=<?=alt($_GET['forum_search'])?> />
	</form>

</div>
-->

<table id="forum_theme" class="forum_table" summary=@@summary@@ >
	
	<caption>@@caption@@</caption>
	
	<LEVEL1>
	
		<tr>
			<th class="titre" scope="col">@@titre@@</th>
			<th class="sujets" scope="col">@@sujets@@</th>
			<th class="messages" scope="col">@@messages@@</th>
			<th class="dernier" scope="col">@@dernier@@</th>
		</tr>
		
		<LEVEL2>
			
			<tr>
				<td class="titre">
					<a href="@@url@@">@@titre@@</a>
					<span>@@desc@@</span>
				</td>
				<td class="sujets">@@sujets@@</td>
				<td class="messages">@@messages@@</td>
				<td class="dernier">
					<a href="@@url_dernier@@">@@dernier@@</a>
					<span class="poste_le">@@poste_le@@</span>
					 <span class="poste_par">@@poste_par@@</span>
				</td>
			</tr>
		
		</LEVEL2>
	
	</LEVEL1>

</table>

<div class="forum_footer">

</div>