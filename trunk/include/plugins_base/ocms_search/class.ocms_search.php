<?php

/*
  @include('../ccsearch/getresults.php');

  @include('ccsearch/getresults.php');
 */

global $_Gconfig;



$commonWords = array('les', 'des', 'dans', 'mais', 'car', 'elle', 'elles', 'aussi', 'leurs', 'leur', 'nous', 'entre', 'non', 'par', 'ils', 'ainsi', 'ces', 'ses', 'une', 'que', 'aux', 'par', 'est', 'qui', 'sur', 'pour', 'sont', 'plus', 'avec', 'cette', 'pas', 'comme', 'ont', 'peut', 'dont');

$delims = array('�', '&amp;#8217;', '&#8217;', '?', '.', ',', ';', ':', '/', '!', ']', '}', '\'', '{', '[', '(', ')', '-', '>', '<', '|', '#', '?', "\n", "\r", "\t", '?', '"', '?', "?", '?', '?', '*', '?', '�');

//$vipWords = array('pi', 'p�', 'no','or','nu','vu','os','fr','cm','km','ph','ko','g8','ip','3d');




class indexSearch {

    var $vipWords = array('pi', 'pô', 'no', 'or', 'nu', 'vu', 'os', 'fr', 'cm', 'km', 'ph', 'ko', 'g8', 'ip', '3d', 'if');
    var $minlength = 2;
    var $related = array();
    var $cachestem;
    var $obj;
    public $cacheWord = array();
    public $useWildCards = false;

    function __construct($obj='') {

	global $_Gconfig, $relations, $relinv;
	/**
	 * Lemmatisation pas complete mais presque ... voir les problemes qui peuvent se poser
	 */
	$this->stemRemove = array('if', 'ifs', 'ive', 'ives', 'er', 'ant', 'é', 'ée', 'e', 'es', 'és', 'ons', 'ez', 'ent', 'ais', 'ait', 'ions', 'iez', 'aient', 'ai', 'as', 'a', 'âmes', 'âtes', 'èrent', 'erai', 'eras', 'era', 'erons', 'erez', 'eront', 'erais', 'erait', 'erions', 'eriez', 'eraient', 'asse', 'ât', 'assions', 'assiez', 'assent', 's');
	/**
	 * Lemmatisation simple -> Pluriel/singulier Feminin/Masculin
	 */
	$this->stemRemove = array('s', 'e', 'al', 'aux');


	/**
	 * Lemmatisation basée sur http://www.unine.ch/info/clef/frenchStemmerPlus.txt
	 * http://www.unine.ch/info/clef/
	 *
	 * Plus quelques rajouts perso
	 */
	$this->stemReplace = array();

	# Rajouts CELIO
	$this->stemReplace = array('eaux' => 'eau', 'eux' => 'eu', 'aux' => 'al', 'x' => '', 's' => '', 'atique' => '');

	# ORIGINAL
	$this->stemReplace = array_merge($this->stemReplace, array('issement' => 'ir', 'issant' => 'ir', 'ement' => 'e', 'ficatrice' => 'fier', 'ficateur' => 'fier', 'catrice' => 'quer', 'atrice' => 'er', 'ateur' => 'er', 'trice' => 'teur', 'ième' => '', 'teuse' => 'ter', 'teur' => 'ter', 'euse' => 'eu', 'ère' => 'er', 'ive' => 'if', 'olle' => 'ou', 'nnelle' => 'n', 'nnel' => 'n', 'ète' => 'et', 'ique' => '', 'esse' => '', 'inage' => '', 'isation' => '', 'isateur' => '', 'ation' => '', 'ition' => ''));

	# RAJOUTS CELIO
	$this->stemReplace['ance'] = '';
	$this->stemReplace['ant'] = '';
	$this->stemReplace['ées'] = '';
	$this->stemReplace['es'] = '';
	$this->stemReplace['ée'] = '';
	$this->stemReplace['e'] = '';
	$this->stemReplace['é'] = '';


	$this->stemSpecialWords = array('yeux' => 'oeil', 'travaux' => 'travail', 'affreux' => 'affreux');




	if ($obj) {
	    $this->obj = $obj;
	    $this->tab = akev($_Gconfig['iSearches'], $obj);

	    $this->fields = getTabField($obj);

	    if (empty($this->tab['champs'])) {
		$this->tab['champs'] = array_keys($this->fields);
	    }
	    if (empty($this->tab['relations'])) {

		$this->tab['relations'] = array();
		if (is_array($relations[$obj])) {
		    foreach ($relations[$obj] as $k => $v) {
			$this->tab['relations'][$k] = array_keys(getTabField($v));
		    }
		}
		if (is_array($relinv[$obj])) {
		    foreach ($relinv[$obj] as $k => $v) {


			$this->tab['relations'][$k] = array_keys(getTabField($v[0]));
		    }
		}
	    }

	    if ($obj == 's_rubrique') {

		global $_Gconfig;
		$tb = GetTablesToIndex();
		foreach ($_Gconfig['duplicateWithRubrique'] as $v) {
		    if (in_array($v, $tb)) {
			//$this->tab['relations'][$k] = array_keys(getTabField($v[0]));
			$this->related[] = $v;
		    }
		}
	    }


	    $this->pk = getPrimaryKey($obj);
	}
    }

    public function useWildCards() {
	$this->useWildCards = true;
    }

    /**
     * Selectionne l'ensemble du texte et des relations de l'objet concerné
     *
     * @param Identifiant $id
     * @return string Texte complet nettoyé
     */
    function getTextToIndex($id, $res=null) {

	global $tablerel, $relinv, $relations, $tabForms, $uploadFields, $_Gconfig;

	foreach ($this->related as $v) {
	    $sql = 'SELECT * FROM ' . $v . ' AS T WHERE fk_rubrique_id = ' . $id;
	    $res = GetAll($sql);
	    $ppk = getPrimaryKey($v);
	    $i = new indexSearch($v);
	    foreach ($res as $row) {
		//echo ('index : '.$v.' '.$row[$ppk]);

		$i->indexText($i->getTextToIndex($row[$ppk]), $row[$ppk]);
	    }
	}

	if (!$res) {
	    $sql = 'SELECT * FROM ' . $this->obj . ' WHERE ' . $this->pk . ' = "' . $id . '"';
	    $res = GetSingle($sql);
	}

	$txtToIndex = '';


	if (in_array($this->obj, $_Gconfig['duplicateWithRubrique'])) {
	    if (!isRubriqueRealAndOnline($res['fk_rubrique_id'])) {
		return false;
	    }
	}

	foreach ($this->tab['champs'] as $field) {

	    if (ake($this->fields, $field)) {

		if (arrayInWord($uploadFields, $field) || arrayInWord($_Gconfig['urlFields'], $field)) {

		    //print('<b>'.$field.'</b>');
		    //$txt = GETTEXTCONTENTOFFILE();
		} else {

		    $coef = 1;
		    if (!empty($_Gconfig['searchRatio'][$this->obj][$field])) {
			$coef = $_Gconfig['searchRatio'][$this->obj][$field];
		    } else
		    if (in_array($field, $tabForms[$this->obj]['titre'])) {
			$coef = 10;
		    } else if (@in_array($field, $tabForms[$this->obj]['desc'])) {
			$coef = 4;
		    }

		    if ($this->fields[$field]->type == 'date' || $this->fields[$field]->type == 'datetime') {
			$txt = niceTextDate($res[$field]);
		    } else {
			$txt = implode(' ', getLgsValues($field, $res, $this->obj));
		    }
		    $txtToIndex .= str_repeat($txt . ' ', $coef) . ' ';
		}
	    }
	}


	foreach ($this->tab['relations'] as $k => $v) {


	    if (ake($relations[$this->obj], $k) && $v && $res[$k]) {
		$sql = 'SELECT * FROM 
								' . $relations[$this->obj][$k] . ' 
								WHERE 
								' . getPrimaryKey($relations[$this->obj][$k]) . ' = ' . sql($res[$k]);
		$r = GetSingle($sql);
		foreach ($v as $ch) {

		    if (arrayInWord($uploadFields, $ch) || arrayInWord($_Gconfig['urlFields'], $ch)) {
			
		    } else {
			$txtToIndex .= ' ' . implode(' ', getLgsValues($ch, $r, $this->obj)) . ' ';
		    }
		}
	    } else if (ake($relinv[$this->obj], $k)) {


		$sql = 'SELECT * FROM ' . $relinv[$this->obj][$k][0] . ' WHERE ' . $relinv[$this->obj][$k][1] . ' = ' . $res[$this->pk];
		$r = GetAll($sql);

		foreach ($r as $rv) {
		    foreach ($v as $ch) {
			if (arrayInWord($uploadFields, $ch) || arrayInWord($_Gconfig['urlFields'], $ch)) {
			    
			} else {
			    $txtToIndex .= ' ' . implode(' ', getLgsValues($ch, $rv, $relinv[$this->obj][$k][0])) . ' ';
			}
		    }
		}
	    }
	}

	return $this->cleanText($txtToIndex);
    }

    /**
     * Nettoie le texte de toute impurtée HTML, des mots inutiles, ...
     *
     * @param string $str
     * @return string
     */
    function cleanText($str) {

	$str = strip_tags($str);
	$str = mb_strtolower($str, 'utf-8');
	//$str = strtr($str,'é','e');
	$str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
	//
	//$str = $this->utf2ascii($str);
	//$str = removeaccents($str);
	$delims = array('�', '&amp;#8217;', '&#8217;', '?', '.', ',', ';', ':', '/', '\\', '!', ']', '}', '\'', '{', '[', '(', ')', '-', '>', '<', '|', '#', '?', "\n", "\r", "\t", '?', '"', '?', "?", '?', '?', '*', '?', '�', '’', '@', '_', '&', '^', '=', '»', '«');
	$str = str_replace($delims, ' ', $str);

	$str = $this->removeaccents($str);

	$commonWords = array('en_ligne', 'page', 'les', 'des', 'dans', 'mais', 'car', 'elle', 'elles', 'aussi', 'leurs', 'leur', 'nous', 'entre', 'non', 'par', 'ils', 'ainsi', 'ces', 'ses', 'une', 'que', 'aux', 'par', 'est', 'qui', 'sur', 'pour', 'sont', 'plus', 'avec', 'cette', 'pas', 'comme', 'ont', 'peut', 'dont');


	$str = ' ' . $str . ' ';
	foreach ($commonWords as $word) {
	    $str = str_replace(' ' . $word . ' ', ' ', $str);
	}

	return trim($str);
    }

    function removeaccents($str) {

	// LAME WAY TO DO IT ! But PHP is too ....
	$str = utf8_decode($str);

	$str = strtr($str, "\xC0\xC1\xC2\xC3\xC4\xC5\xC6", "AAAAAAA");
	$str = strtr($str, "\xC7", "C");
	$str = strtr($str, "\xC8\xC9\xCA\xCB", "EEEE");
	$str = strtr($str, "\xCC\xCD\xCE\xCF", "IIII");
	$str = strtr($str, "\xD1", "N");
	$str = strtr($str, "\xD2\xD3\xD4\xD5\xD6\xD8", "OOOOOO");
	$str = strtr($str, "\xDD", "Y");
	$str = strtr($str, "\xDF", "S");
	$str = strtr($str, "\xE0\xE1\xE2\xE3\xE4\xE5\xE6", "aaaaaaa");
	$str = strtr($str, "\xE7", "c");
	$str = strtr($str, "\xE8\xE9\xEA\xEB", "eeee");
	$str = strtr($str, "\xEC\xED\xEE\xEF", "iiii");
	$str = strtr($str, "\xF1", "n");
	$str = strtr($str, "\xF2\xF3\xF4\xF5\xF6\xF8", "oooooo");
	$str = strtr($str, "\xF9\xFA\xFB\xFC", "uuuu");
	$str = strtr($str, "\xFD\xFF", "yy");
	return $str;
    }

    function mb_str_split($str, $length = 1) {
	if ($length < 1)
	    return FALSE;

	$result = array();

	for ($i = 0; $i < mb_strlen($str); $i += $length) {
	    $result[] = mb_substr($str, $i, $length);
	}

	return $result;
    }

    /**
     * Parcourt les mots et les indexe dans la base
     *
     * @param string $str
     * @param mixed $id_obj
     */
    function indexText($str, $id_obj) {
	global $co, $_Gconfig;


	if (in_array($this->obj, $_Gconfig['duplicateWithRubrique'])) {

	    $res = getRowFromId($this->obj, $id_obj);
	    if (!isRubriqueRealAndOnline($res['fk_rubrique_id'])) {
		return false;
	    }
	}

	$id = $this->GetIsId($id_obj);

	DoSql('DELETE FROM os_rel WHERE fkobj = "' . $id . '"');

	$tabMots = $this->getTabWords($str, true);

	$sql = ' INSERT INTO os_rel VALUES  ';
	foreach ($tabMots as $k => $v) {
	    $sql .= ( '("' . $id . '","' . $k . '","' . $v . '") , ');
	}
	$sql = substr($sql, 0, -2);
	DoSql($sql);
    }

    /**
     *
     *
     * @param unknown_type $str
     * @param unknown_type $getids
     * @return unknown
     */
    function getTabWords($str, $getids = false) {

	$mots = explode(' ', $str);


	$tabWords = array();
	foreach ($mots as $word) {

	    $word1 = $word;

	    $word = $this->cleanWord($word);


	    /*
	      if($getids) {
	      $this->insertDict($word1);
	      }
	     */
	    if (strlen($word) > $this->minlength || in_array($word, $this->vipWords)) {
		if ($getids) {
		    $wid = $this->GetWordId($word);
		    if (!isset($tabWords[$wid])) {
			$tabWords[$wid] = 0;
		    }
		    $tabWords[$wid]++;
		} else {
		    $tabWords[] = $word;
		}
	    }
	}

	return $tabWords;
    }

    function insertDict($word) {
	TrySql('INSERT INTO is_dict (mot) VALUES(' . sql($word) . ')');
	DoSql('UPDATE is_dict SET nb = nb + 1 WHERE mot = ' . sql($word));
    }

    /**
     * Selectionne un mot et retourne son identifiant
     *
     * @param string $word
     * @return mixed Primary key
     *
     */
    function getWordId($word) {

	if (empty($this->cacheWord[$word])) {
	    $sql = 'SELECT id FROM os_word WHERE word = "' . $word . '" ';
	    $row = GetSingle($sql);

	    if (!count($row)) {
		DoSql('INSERT INTO os_word VALUES ("","' . $word . '")');
		$this->cacheWord[$word] = InsertId();
		//debug('insert '.$word);
	    } else {
		$this->cacheWord[$word] = $row['id'];
	    }
	}
	return $this->cacheWord[$word];
    }

    /**
     * Nettoie un mot ... a voir avec du stemming, ...
     *
     * @param string $word
     * @return string
     */
    function cleanWord($word) {

	/*
	  $w =  prepareword($word);
	  dinfo($word.'-'.$w);
	  return $w;
	 */
	$ow = $word;

	if (empty($this->cachestem[$ow])) {
	    if (!empty($this->stemSpecialWords[$ow])) {
		$word = $this->stemSpecialWords[$ow];
	    } else {
		foreach ($this->stemReplace as $k => $v) {
		    $l = strlen($k);
		    if (substr($word, -$l) == $k && strlen($word) > $l) {
			$word = substr($word, 0, -$l) . $v;
			//break;
		    }
		}
	    }
	    /*
	      foreach($stemRemove as $v) {
	      if(substr($word,-strlen($v)) == $v && strlen($word) > strlen($v)) {
	      $word = substr($word,0,-strlen($v));
	      break;
	      }
	      }
	     */
	    $this->cachestem[$ow] = $word;
	}
	//dinfo($ow.' : '.$word);
	if (strlen($this->cachestem[$ow]) <= $this->minlength && !in_array($this->cachestem[$ow], $this->vipWords)) {
	    $this->cachestem[$ow] = '';
	}
	return $this->cachestem[$ow];
    }

    /**
     * Retourne l'identifiant de l'objet IS
     *
     * @param string $id_obj
     * @return string
     */
    function getIsId($id_obj) {

	$sql = 'SELECT id FROM os_obj WHERE fkid = "' . $id_obj . '" AND obj = "' . $this->obj . '"';
	$row = GetSingle($sql);

	if (is_array($row) && count($row)) {
	    $this->id = $row['id'];
	} else {
	    DoSql('INSERT INTO os_obj VALUES ("","' . $this->obj . '","' . $id_obj . '")');
	    $this->id = InsertId();
	}

	return $this->id;
    }

    /**
     * Effectue une recherche fulltexte sur l'objet courant
     *
     * @param string $q recherche full texte
     * @param string $select
     * @param string $from
     * @param string $where
     * @param string $order
     * @return array Tableau de requete SQL
     */
    function search($q, $select="", $from="", $where="", $order="") {

	$q = $this->cleanText($q);
	//print($q.'<br/>');
	$words = $this->getTabWords($q, false);
	//debug($words);

	if (!$this->obj) {
	    return $this->searchGlobal($words);
	}

	if (count($words) > 0 && $words[0] != "") {

	    $sql = 'SELECT O.* ,
						COUNT(IO.id) AS CIO,
						COUNT(IW.id) AS RANK1,
						SUM(IR.nb) AS RANK2,
						"' . $this->obj . '" as obj
						' . $select . '

						FROM  os_obj as IO , os_rel AS IR, os_word AS IW,  ' . $this->obj . ' AS O ' . $from . '

					WHERE IO.fkid = O.' . $this->pk . '
					AND IO.obj = "' . $this->obj . '"
					AND IO.id = IR.fkobj
					AND IR.fkword = IW.id
					' . $where . '
					';


	    //debug($words);
	    //array_walk($words,'$this->cleanWord');


	    $sql .= ' AND ( 0 ';

	    foreach ($words as $word) {
		$sql .= ' OR IW.word LIKE "' . $word . '" ';
	    }

	    $sql .= ' ) ';


	    //$sql .= '   COUNT(IW.id) = '.count($words).' ';

	    $sql .= ' GROUP BY IO.id HAVING RANK1 = ' . count($words) . ' ORDER BY RANK1 DESC , RANK2 DESC';

	    //	debug($sql);
	} else {

	    $sql = 'SELECT O.*

						' . $select . '

						FROM ' . $this->obj . ' AS O ' . $from . '

					WHERE 1
					' . $where . '

					' . $order . '
					';
	}

	return GetAll($sql);
    }

    /**
     * Recherche dans la base mais sans Objet définit, et retourne tous les objets correspondants
     * Il faut ensuite selectionner tous les enregistrements en question pour les afficher
     *
     * @param array $words
     */
    function searchGlobal($words) {

	$res = array();
	if (count($words) > 0 && $words[0] != "") {


	    $resTot = getSingle('SELECT COUNT(id) AS NB FROM os_obj');
	    $nbTot = $resTot['NB'];


	    $sqls = ' ( ';
	    foreach ($words as $k => $word) {

		if ($this->useWildCards) {
		    $word .= "%";
		}

		$sql1 = 'SELECT word, COUNT( id ) AS NB
					FROM `os_word` AS W, os_rel AS R
					WHERE R.fkword = W.id
					AND W.word = "' . $word . '"
					GROUP BY W.id
					ORDER BY `NB` DESC ';

		$res1 = GetSingle($sql1);


		if (!empty($res1['NB'])) {
		    $ratio[$word] = ceil($nbTot / $res1['NB']);
		} else {
		    $ratio[$word] = 0;
		}

		//debug('WORD : '.$word.' '.$ratio[$word]);//$res1['NB']);

		$sql = 'SELECT
							CONCAT(IO.obj,IO.fkid) AS MACLEF,
							IO.obj , IO.fkid , 
							COUNT(IO.id) AS CIO,
							COUNT(IW.id) AS RANK1,
							SUM(IR.nb*' . $ratio[$word] . ') AS RANK2						
	
							FROM os_obj as IO , os_rel AS IR, os_word AS IW
	
						WHERE  IO.id = IR.fkobj
						AND IR.fkword = IW.id
						AND IW.word LIKE "' . $word . '"
						';
		$sql .= ' GROUP BY IO.id  ';
		$sql .= ' ORDER BY RANK2 DESC';

		if ($k != 0)
		    $sqls .= ' ) UNION ALL (' . $sql . '  ';
		else
		    $sqls .= $sql;



		//$res[$word] = GetAll($sql);
	    }
	    $sql = ' SELECT obj, fkid, RANK1, RANK2, SUM(RANK2) AS RANK3 , SUM(RANK1) AS LIMITEUR FROM ( ';
	    $sql .= $sqls;
	    $sql .= ' ) ';
	    if (count($words) > 1) {
		$sql .= ' ORDER BY RANK2 DESC  ';
	    }
	    $sql .= ' ) AS TEST GROUP BY MACLEF HAVING LIMITEUR >= ' . count($words) . ' ORDER BY RANK3 DESC';

	    $res = GetAll($sql);

	    //debug(' SELECT obj, fkid, RANK1, RANK2, SUM(RANK2) AS RANK3 , SUM(RANK1) AS LIMITEUR FROM ( '.$sqls.' ) ORDER BY RANK2 DESC ) AS TEST GROUP BY MACLEF HAVING LIMITEUR >= '.count($words).' ORDER BY RANK3 DESC');
	    //debug(count($res));
	    //diebug($res);

	    /**
	     *  VERSION NORMALE SANS PONDERATION DES MOTS
	     * 
	     * 

	      $sql = 'SELECT
	      IO.obj , IO.fkid , IW.word ,

	      COUNT(IO.id) AS CIO,
	      COUNT(IW.id) AS RANK1,

	      SUM(IR.nb) AS RANK2


	      FROM os_obj as IO , os_rel AS IR, os_word AS IW

	      WHERE  IO.id = IR.fkobj
	      AND IR.fkword = IW.id
	      ';


	      //debug($words);

	      //array_walk($words,'$this->cleanWord');


	      $sql .= ' AND ( 0 ';

	      foreach($words as $word)
	      {
	      $sql .= ' OR IW.word LIKE "'.$word.'" ';
	      }

	      $sql .= ' ) ';


	      //$sql .= '   COUNT(IW.id) = '.count($words).' ';

	      $sql .= ' GROUP BY IO.id HAVING RANK1 = '.count($words).' ';

	      $sql .= ' ORDER BY RANK1 DESC , RANK2 DESC';

	      // */
	} else {

	    //$sql = ' SELECT * FROM os_obj';
	}
	$t = getmicrotime();

	//$res = GetAll($sql);
	//debug($res);
	//debug(getmicrotime() - $t);
	return $res;
    }

}

?>