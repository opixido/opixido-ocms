<?php

function viderRecherche()
{

	$sql = 'TRUNCATE TABLE os_obj';
	doSql($sql);
	echo 'Objets : ' . $sql . '<br/>';

	$sql = 'TRUNCATE TABLE os_rel';
	doSql($sql);
	echo 'Relations : ' . $sql . '<br/>';

	$sql = 'TRUNCATE TABLE os_word';
	doSql($sql);
	echo 'Mots : ' . $sql . '<br/>';
}

class ocms_searchAdmin
{

	/**
	 * Gensite
	 *
	 * @var Gensite
	 */
	var $site;

	function __construct($site)
	{

		$this->site = $site;

		if (akev($_GET, 'ocms_searchReindex')) {

			$is = new indexSearch($_GET['table']);

			$txt = $is->getTextToIndex($_GET['id']);
			//debug($txt);
			$is->indexText($txt, $_GET['id']);

			die();
		}
	}
}

$GLOBALS['gb_obj']->includeFile('class.ocms_search.php', 'plugins/ocms_search');

/**
 * Test du moteur
 *
 */
function testSearch()
{

	p('<form>');
	p('<input type="text" name="q" value="' . ($_GET['q'] ?? '') . '" /> <input type="submit" value="GO" />');
	p('<input type="hidden" name="globalAction" value="testSearch" />');
	p('</form>');

	if (!empty($_GET['q']) && $_GET['q']) {
		$s = new indexSearch();
		$res = $s->search($_GET['q']);
		p('<table border="1">');
		p('<tr><th>ID</th><th>TABLE</th><th>FK_ID</th><th>CIO</th><th>RANK1</th><th>RANK2</th></tr>');
		foreach ($res as $row) {
			p('<tr>');
			foreach ($row as $k => $v) {
				p('<td>' . $v . '</td>');
			}
			p('</tr>');
		}
		p('</table>');
	}
}

/**
 * Réindex tout ou parti du site
 *
 */
function reIndexSearch()
{

	global $_Gconfig;
	$tables = GetTablesToIndex();

	if (!empty($_GET['allTables'])) {

		$sql = 'TRUNCATE TABLE os_obj';
		DoSql($sql);
		$sql = 'TRUNCATE TABLE os_rel';
		DoSql($sql);
		$sql = 'TRUNCATE TABLE os_word';
		DoSql($sql);

		p('<input type="text" id="reindexsearch_1" readonly="readonly" style="border:0;text-align:center;width:350px;"/>');
		p('<input type="text" id="reindexsearch_2" readonly="readonly"  />');
		p('<script type="text/javascript">
		ris1 = gid("reindexsearch_1");
		ris2 = gid("reindexsearch_2");
		</script>
		');

		foreach ($tables as $table) {

			indexTable($table);
		}
		p('<script type="text/javascript">ris1.value = "Indexing Complete"</script>');
		//p('</script>');
		p('');
	} else if (!empty($_GET['tableOnly'])) {

		p('<div style="background:blue;font-size:3px;line-height:3px;width:1px;" id="pbar">&nbsp;</div>');
		p('<input type="text" id="reindexsearch_2" readonly="readonly"  style="border:0;text-align:center;width:350px;" />');
		p('<script type="text/javascript">
		ris1 = gid("reindexsearch_1");
		ris2 = gid("reindexsearch_2");
		</script>
		');

		indexTable($_GET['tableOnly']);
	} else {

		foreach ($tables as $table) {
			print('<li><a href="?globalAction=reIndexSearch&amp;tableOnly=' . $table . '">' . t($table) . '</a></li>');
		}
		print('<li>-----------</li>');
		print('<li><a href="?globalAction=reIndexSearch&amp;allTables=1">' . t('tout_ensemble') . '</a></li>');
	}
}

/**
 * Enter description here...
 *
 * @param unknown_type $table
 */
function indexTable($table)
{
	global $_Gconfig;
	$pk = getPrimaryKey($table);

	$sql = 'SELECT ' . $pk . ' FROM ' . $table . ' WHERE 1 ' . sqlOnlyOnline($table, '', true);
	$res = GetAll($sql);
	$nbt = 0;
	$nbt++;
	p('<script type="text/javascript">pbar = gid("pbar");</script>');

	$nb = count($res);
	$nbm = 0;
	$timestart = getmicrotime();
	foreach ($res as $row) {
		set_time_limit(30);
		$row = getRowFromId($table, $row[$pk]);

		if ($table == 's_rubrique' && (!isRubriqueRealAndOnline($row) || $row['rubrique_type'] == RTYPE_MENUROOT)) {

			$nb--;
			$pc = ceil(($nbm / $nb) * 100);
			p('<script type="text/javascript">ris2.value = "' . $pc . '%"</script>');
		} else {
			$is = new indexSearch($table);
			$txt = $is->getTextToIndex($row[$pk], $row);
			$is->indexText($txt, $row[$pk]);
			$nbm++;
			//p(' - '.$row[$pk].'');
			$percent = (($nbm / $nb) * 100);

			$current = getmicrotime();
			$elapsed = $current - $timestart;
			$reste = round($elapsed * (100 / $percent)) - $elapsed;
			if ($reste > 60) {
				$reste = ceil($reste / 60) . ' min ' . ($reste % 60) . ' sec';
			} else {
				$reste = round($reste) . ' sec. restantes';
			}

			p('<script type="text/javascript">pbar.style.width="' . round($percent / 100 * 350) . 'px";ris2.value = "' . ($nbm) . ' / ' . $nb . ' - ' . ceil($percent) . '% - ' . $reste . ' "</script>');
			ob_flush();

			flush();
		}
	}
	print('<br/>OK : ' . $table . ' : ' . $nb . ' en ' . $elapsed . ' sec');
}

function deleteFromSearch($id, $b, $genRecord)
{
	DoSql('DELETE FROM os_obj WHERE obj LIKE ' . sql($genRecord->table) . ' AND fkid = ' . sql($genRecord->id));
}

/**
 * Indexe l'element en question pour la recherche ultérieure
 *
 * @param mixed $id
 * @param array $row
 * @param mixed $obj
 */
function indexForSearch($id, $row = array(), $obj, $table)
{

	global $_Gconfig;
	$tables = GetTablesToIndex();

	//debug('INDEX : ' .$table.' : '.$id);

	if (in_array($table, $tables)) {



		if ($table == 's_rubrique' && !isRubriqueRealAndOnline($id)) {
			if (!count($row)) {
				$row = getRowFromId('s_rubrique', $id);
			}
			if ($row['ocms_version'] && isRubriqueRealAndOnline($row['ocms_version'])) {
				return indexForSearch($row['ocms_version'], getRowFromId('s_rubrique', $row['ocms_version']), $obj, $table);
			} else {
				return;
			}
		}

		//debug('INDEX : ' .$table.' : '.$id);
?>
		<script type="text/javascript">
			if (window.XMLHttpRequest) // Firefox
				var http = new XMLHttpRequest();
			else if (window.ActiveXObject) // Internet Explorer
				var http = new ActiveXObject("Microsoft.XMLHTTP");


			http.open("GET", 'index.php?ocms_searchReindex=1&id=<?= $id ?>&table=<?= $table ?>', true);



			http.onreadystatechange = function() {
				if (http.readyState == 4) {
					gid('gen_actions').innerHTML += (http.responseText);

				}
			}

			http.send(null);
		</script>
<?php
		/*

	  $is = new indexSearch($table);

	  $txt = $is->getTextToIndex($id);
	  //debug($txt);
	  $is->indexText($txt,$id);
	 */
	}
}
?>