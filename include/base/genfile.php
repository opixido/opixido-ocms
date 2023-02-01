<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

/**
 * Gestion des fichiers
 */
class genFile
{

    /**
     * Qualité des Jpegs générés
     *
     * @var int
     */
    var $quality = 90;
    var $forceBaseFormat = true;
    var $baseFormat = 'png';
    var $bg = 'FFFFFF';
    public $realSystemPath = false;
    public $mask = '';
    public $useImageEditor = true;
    public $uploadFromUrl = false;
    public $row = [];
    public $id = null;
    public $valeurFound = null;
    public $rules = null;
    public $realName = '';
    public $fileExtension = '';
    public $fileBase = '';
    public $fileName = '';
    public $systemPath = '';
    public $webPath = '';
    public $actuel = false;
    public $table = '';
    public $champ = '';
    public $imageExists = false;
    public $valeur = false;
    public $valeurInit = false;
    public $classe = null;
    public $thumbWidth;
    public $thumbHeight;
    public $useThumbs;

    /**
     * @var string Phpthumb variable to rotate image based on exif data
     */
    public $ar = 'x';

    /**
     *
     * Instanciation, avec en parametre $table, $champ, $id,
     * Et eventuellement la valeur courante
     * actuel = false si vous uploadez (donc nouveau fichier et pas actuel)
     *
     * @param table string Table de référence
     * @param champ string Champ a utiliser / peut etre utilisé de deux maniere :
     *            "paragraphe_img" sans variable @addLg
     *            ou "paragraphe_img_" avec @addLg = True pour selectioner automatiquement la langue
     *            A ce moment , @valeur doit etre un tableau avec les differentes valeurs
     * @param id int identifiant dans la table
     * @param valeur string|array Si c'est un array, on selectionne automatiquement le champ
     * @param actuel bool Definit si c'est on garde la valeur actuelle ou si c'est un upload de nouveau fichier
     * @param addLg bool on definit le champ automatiquement, $valeur doit etre un array
     *
     */
    function __construct($table = false, $champ = false, $id = false, $valeur = "", $actuel = true, $addlg = false)
    {

        global $specialUpload, $_Gconfig;
        $this->actuel = $actuel;
        if (!$table) {
            return;
        }
        $this->table = $table;

        $this->champ = $champ;

        $this->imageExists = true;

        $this->valeurInit = $this->valeur = $valeur;

        /**
         * Si on a passé le row Ã  $id
         */
        if (is_array($id)) {
            $this->row = $id;
            $valeur = $this->valeur = $id;
            $this->id = $id[getPrimaryKey($table)];
        } else {
            $this->id = $id;
        }


        if (substr($this->champ, -1) == '_') {
            $this->champ = substr($this->champ, 0, -1);
        }

        $fields = getTabField($table);

        if (!ake($fields, $champ) && !empty($_REQUEST['relOne']) && $_REQUEST['relOne'] != $table) {

            $this->__construct($_REQUEST['relOne'], $champ, $id, $valeur, $actuel, $addlg);
            return;
        } else if (!ake($fields, $champ)) {
            /* Surement un champ de langue */
            $addlg = true;
            $champLG = getLgFromField($champ);
        }


        if (!empty($_Gconfig['fileClass'][$this->table][$this->champ])) {
            $this->classe = new $_Gconfig['fileClass'][$this->table][$this->champ]($this);
        } else {
            $this->classe = false;
        }

        if ($addlg) {

            /*
              Alors $valeur est un array et on selectionne le fichier approprié
             */

            if (TRADLG || $champLG) {

                $champLG = TRADLG ? TRADLG : $champLG;
                $val = getTradValue($this->table, $this->id, $this->champ, $champLG);
            }

            if ($val) {
                $this->champ = $this->champ . '_' . $champLG;
                $this->valeur = $val;
            } else {

                if (isset($this->valeur[$this->champ . '_' . LG()])) {

                    $this->champ = $this->champ . '_' . LG();

                    $this->valeur = $valeur[$this->champ];
                    $this->valeurFound = true;
                } else if (isset($this->valeur[$this->champ . '_' . $GLOBALS['otherLg']])) {

                    $this->champ = $this->champ . '_' . $GLOBALS['otherLg'];

                    $this->valeur = $valeur[$this->champ];
                    $this->valeurFound = true;
                } else {

                    $this->valeur = '';
                    $this->valeurFound = true;
                }
            }
        }


        if (is_array($this->valeur) && array_key_exists($champ, $this->valeur)) {
            $this->valeur = $this->valeur[$champ];
        }


        if (is_array($this->valeur)) {
            /* debug($this->valeur);
              debug($champ); */
            return;
        }

        if (!strlen($this->valeur) && $id && !is_array($this->valeurInit)) {
            $row = GetRowFromId($this->table, $this->id); //getSingle($sql);
            $this->valeur = akev($row, $champ);
            $this->row = $row;
        }


        if (ake($specialUpload, $this->table) && !empty($specialUpload[$this->table][$this->champ])) {
            $this->rules = $specialUpload[$this->table][$this->champ];
        } else {
            $this->rules = $specialUpload['genfile_default']['genfile_default'];
        }


        $this->realName = basename($this->valeur);


        if (!strlen($this->realName)) {
            $this->imageExists = false;
        }


        if (!$actuel && !$this->classe) {
            $this->valeur = $this->nicename($this->valeur);
            $this->realName = $this->niceName($this->realName);
        }


        $exte = explode(".", $this->realName);

        $this->fileExtension = "";

        if (count($exte) > 1) {
            $this->fileExtension = $exte[count($exte) - 1];

            /**
             * Cette ligne était en dehors du IF
             * Modifié pour gérer les cas oÃ¹ il n'y a pas d'extension au nom stocké
             * (ex: Cerdys, valeur du champ silhouette : AQ29 et fichier = AQ29.bmp
             */
            array_pop($exte);
        }
        $this->fileBase = implode(".", $exte);

        $this->fileName = $this->realCode($this->rules['name']);

        if ($this->classe) {
            $this->systemPath = $this->classe->getSystemPath();
            $this->webPath = dirname($this->classe->getWebUrl());
            $this->fileName = $this->classe->getFilename();
        } else
            if ($this->fileIsLinkedToFolder()) {
                /**
                 * Sélection directe d'un fichier dans un dossier existant
                 */
                $this->fileName = substr($this->realName, 2);

                $s = $_Gconfig['fileListingFromFolder'][$this->table][getBaseLgField($champ)];
                $s = explode('{', $s);
                $this->systemPath = $s[0];

                $this->webPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->systemPath);
            } else if (strpos($this->valeur, '/') !== false) {

                $this->systemPath = dirname(path_concat($_SERVER['DOCUMENT_ROOT'], BU, $this->valeur)) . '/';
                $this->webPath = dirname($this->valeur) . '/';
                $this->webPath = path_concat(BU, $this->addSlashPath($this->webPath, '/'));
                $this->fileName = $this->realName;
            } else {

                /**
                 * Fichier uploadé normalement
                 */

                $this->systemPath = $this->realCode($this->rules['system']);

                $this->webPath = $_Gconfig['CDN'] . $this->realCode($this->rules['web']);

                $this->systemPath = $this->addSlashPath($this->systemPath);

                $this->webPath = path_concat(BU, $this->addSlashPath($this->webPath, '/'));

                $this->systemPath = $this->getSystemPath();
            }
    }

    /**
     * Est-ce que le fichier actuel est juste un lien vers un fichier
     * existant dans un dossier ou pas ?
     *
     * @return bool
     */
    function fileIsLinkedToFolder()
    {
        return substr($this->realName, 0, 2) == '**';
    }

    /**
     *
     * @desc Determine le chemin en fonction du code d'upload dans le fichier de config
     * @return Le Chemin vers ce fichier
     *
     */
    function realCode($string)
    {
        $str = str_replace(array("*TABLE*", "*FIELD*", "*ID*", "*EXT*", '*NAME*'), array($this->table, $this->champ, $this->id, $this->fileExtension, $this->fileBase), $string);
        $strs = explode('*', $str);
        $stringa = '';
        foreach ($strs as $k => $v) {
            if ($k % 2 == 0) {
                $stringa .= $v;
            } else {
                $stringa .= $this->getVal($v);
            }
        }
        return $stringa;
    }

    function getVal($v)
    {
        global $relations, $relinv;

        /**
         * Sauvegarde du tableau $relations en l'état
         */
        $oldRelations = $relations;

        /**
         * Pour que l'objet row() retrouve les FK alors qu'on est dans un $relinv
         * on est obligé de reporter tous les relinv dans le tableau $relations
         */
        foreach ($relinv as $rubparent => $vv) {
            foreach ($vv as $sub) {
                $relations[$sub[0]][$sub[1]] = $rubparent;
            }
        }

        /**
         * On sépare le string sur le . pour récupérer les différentes relations/champs
         */
        $parts = explode('.', $v);
        $curRow = new row($this->table, $this->id);
        foreach ($parts as $part) {
            /**
             * l'objet row va retrouver ce qu'il faut et générer un nouvel objet row si c'est un FK
             * issu de $relations
             */
            $curRow = $curRow->$part;
        }

        /**
         * On remet le tableau à l'endroit
         */
        $relations = $oldRelations;

        /**
         * Si notre dernier élément est un objet on prend son identifiant
         */
        if (is_object($curRow)) {
            return $curRow->id;
        }

        /**
         * Sinon c'était un champ on prend sa valeur
         */
        return mb_strlen($curRow) > 0 ? nicename($curRow) : '_';
    }

    /**
     *
     * @desc Nettoie le nom de fichier pour l'adapter au web
     * @param string Nom du fichier uploadé Ã  transformer en nom "web"
     * @return Nom de fichier nettoyé
     */
    function niceName($string)
    {
        $string = htmlentities(mb_strtolower($string));
        $string = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $string);
        $string = preg_replace("/([^a-z0-9_]+)/", ".", html_entity_decode($string));
        $string = trim($string);
        return $string;
    }

    function getFilters($fltr = array())
    {
        //if($this->getExtension() == 'png') {
        $s = '';
        if ($this->forceBaseFormat) {
            $s .= '&amp;f=' . $this->getExtension() . '';
        } else {
            /* $ext = $this->getExtension();
              $ext = $ext == 'jpg' ? $this->baseFormat : $ext; */
            //	$ext = $ext == 'png' ? $this->baseFormat : $ext;
            $s .= '&amp;f=' . $this->baseFormat . '&amp;bg=' . $this->bg;
        }
        //}

        if ($this->mask) {
            $fltr[] = 'mask|' . $this->mask;
        }

        if (count($fltr)) {
            if (!is_array($fltr)) {
                $fltr = array($fltr);
            }
            foreach ($fltr as $v) {
                $s .= '&amp;fltr[]=' . $v;
            }
        }
        return $s;
    }

    function getCacheFile($src)
    {
        return getThumbCacheFile($src);

//	global $_Gconfig;
//	$src = str_replace($_Gconfig['CDN'], '', $src);
//
//	$src = urldecode(str_replace('&amp;', '&', $src));
//	$fCname = $GLOBALS['ImgCacheFolder'] . md5($src);
//	/* debugOpix($src);
//	  debugOpix($fCname); */
//
//	if (file_exists($fCname)) {
//
//	    if (filemtime($fCname) >= filemtime($this->getSystemPath())) {
//		return THUMBPATH . 'cache' . '/' . md5($src);
//	    }
//	} else {
//	    //debugOpix($fCname.' - '.$src);
//	}
//
//	return $src;
    }

    public function getFileMtime()
    {
        $path = $this->getSystemPath();
        if ($path && file_exists($path)) {
            return filemtime($path);
        }
        return 0;
    }


    /**
     * Retourne l'URI vers la fonction Thumb
     *
     * @param int $w
     * @param int $h
     * @return string
     */
    function getThumbUrl($w = 200, $h = 200, $fltr = array())
    {
        if (!$this->isImage())
            return $this->getWebUrl();
        if ($this->imageExists) {
            return $this->getCacheFile(path_concat(THUMBPATH) . '?ar=' . $this->ar . '&q=' . $this->quality . '&amp;w=' . $w . '&amp;h=' . $h . '&amp;src=' . $this->getSystemPath(true) . '' . $this->getFilters($fltr));
            //return path_concat(THUMBPATH).'?ar='.$this->ar.'&q='.$this->quality.'&amp;w='.$w.'&amp;h='.$h.'&amp;table='.$this->table.'&amp;champ='.$this->champ.'&amp;id='.$this->id;
        } else {
            return '';
        }
    }

    /**
     * Retourne l'URI vers la fonction Thumb en taille exacte passée, pas taille maximale
     *
     * @param int $w
     * @param int $h
     * @return string
     */
    function getThumbUrlExact($w = 200, $h = 200, $fltr = array())
    {
        if ($this->imageExists) {
            return $this->getCacheFile(path_concat(THUMBPATH) . '?ar=' . $this->ar . '&q=' . $this->quality . '&amp;zc=1&amp;w=' . $w . '&amp;h=' . $h . '&amp;src=' . $this->getSystemPath(true) . $this->getFilters($fltr));
            return path_concat(THUMBPATH) . '?ar=' . $this->ar . '&q=' . $this->quality . '&amp;zc=1&amp;w=' . $w . '&amp;h=' . $h . '&amp;table=' . $this->table . '&amp;champ=' . $this->champ . '&amp;id=' . $this->id;
        } else {
            return '';
        }
    }

    /**
     * Force the width to $w and height to $h, but crops the image to thoose dimensions to
     *
     * @param int $w
     * @param int $h
     * @return string
     */
    function getCropUrl($w = 200, $h = 200, $fltr = array())
    {
        if ($this->imageExists) {
            return $this->getCacheFile(path_concat(THUMBPATH) . '?ar=' . $this->ar . '&q=' . $this->quality . '&amp;zc=1&amp;w=' . $w . '&amp;h=' . $h . '&amp;src=' . $this->getSystemPath(true) . $this->getFilters($fltr));
            return path_concat(THUMBPATH) . '?ar=' . $this->ar . '&q=' . $this->quality . '&amp;zc=1&amp;w=' . $w . '&amp;h=' . $h . '&amp;src=' . $this->getSystemPath() . $this->getFilters($fltr);
            return path_concat(THUMBPATH) . '?ar=' . $this->ar . '&q=' . $this->quality . '&amp;zc=1&amp;sw=' . $w . '&amp;sh=' . $h . '&amp;table=' . $this->table . '&amp;champ=' . $this->champ . '&amp;id=' . $this->id;
        } else {
            return '';
        }
    }

    function getImageHeight()
    {

        if ($this->isImage()) {
            $i = getimagesize($this->getSystemPath());
            return $i[1];
        }
    }

    function getImageWidth()
    {
        if ($this->isImage()) {
            $i = getimagesize($this->getSystemPath());
            return $i[0];
        }
    }

    /**
     *
     *  Verifie et rajoute les slashs necessaires
     * @param string Chemin Ã  verifier
     * @return Chemin vérifié avec les slashs
     */
    function addSlashPath($string, $sep = SEP)
    {
        if (substr($string, -1, 1) != $sep) {
            $string .= $sep;
        }
        return $string;
    }

    /**
     *    Retourne l'URL WEB absolue vers ce fichier
     * @return Chemin Web absolu depuis la racine
     */
    function getWebUrl()
    {
        global $uploadRep, $specialUpload;

        if ($this->classe) {
            return $this->classe->getWebUrl();
        }
        $webRoot = '';
        if (!empty($_Gconfig['remoteFiles']) && !$this->fileExists()) {
            $webRoot = $_Gconfig['remoteFiles'];
        }

        if ($this->imageExists)
            return $webRoot . $this->webPath . $this->fileName;
        else
            return '';
    }

    /**
     * Retourne le TAG <IMG  /> complet avec l'URL obtenue via getCropUrl();
     *
     * @param string $alt
     * @param string $tag
     * @return string HTML
     */
    function getExactImgtag($w = 0, $h = 0, $alt = '', $tag = '', $filters = array())
    {

        $url = $this->getThumbUrlExact($w, $h, $filters);
        if (strstr($tag, 'srcset') === false) {
            $url02 = $this->getThumbUrlExact(round($w * 0.3), round($h * 0.3), $filters);
            $url05 = $this->getThumbUrlExact(round($w * 0.6), round($h * 0.6), $filters);
            $url2x = $this->getThumbUrlExact($w * 2, $h * 2, $filters);
            $tag .= ' srcset="' . $url02 . ' 0.3x,' . $url05 . ' 0.6x, ' . $url . ' 1x, ' . $url2x . ' 2x" ';
        }

        if (strlen($url)) {
            return '<img src="' . $url . '" alt="' . $alt . '" ' . $tag . ' />';
        } else {
            return '';
        }
    }

    /**
     * Retourne le TAG <IMG  /> complet avec l'URL obtenue via getWebUrl();
     *
     * @param string $alt
     * @param string $tag
     * @return string HTML
     */
    function getImgtag($alt = '', $tag = '')
    {
        $url = $this->getWebUrl();
        if (strlen($url)) {
            return '<img src="' . $url . '" alt="' . $alt . '" ' . $tag . ' />';
        } else {
            return '';
        }
    }

    /**
     * Retourne le TAG <IMG  /> complet avec l'URL obtenue via getThumgUrl();
     *
     * @param string $alt
     * @param string $tag
     * @return string HTML
     */
    function getThumbImgtag($w = 0, $h = 0, $alt = '', $tag = '', $filters = array())
    {

        $url = $this->getThumbUrl($w, $h, $filters);

        if (strstr($tag, 'srcset') === false) {
            $url02 = $this->getThumbUrl(round($w * 0.3), round($h * 0.3), $filters);
            $url05 = $this->getThumbUrl(round($w * 0.6), round($h * 0.6), $filters);
            $url2x = $this->getThumbUrl($w * 2, $h * 2, $filters);
            $tag .= ' srcset="' . $url02 . ' 0.3x,' . $url05 . ' 0.6x, ' . $url . ' 1x, ' . $url2x . ' 2x" ';
        }

        if (strlen($url)) {
            return '<img src="' . $url . '" alt="' . $alt . '" ' . $tag . ' />';
        } else {
            return '';
        }
    }

    /**
     * Retourne le TAG <IMG  /> complet avec l'URL obtenue via getCropUrl();
     *
     * @param string $alt
     * @param string $tag
     * @return string HTML
     */
    function getCropImgtag($w = 0, $h = 0, $alt = '', $tag = '', $filters = array())
    {

        $url = $this->getCropUrl($w, $h, $filters);

        if (strstr($tag, 'srcset') === false) {
            $url02 = $this->getCropUrl(round($w * 0.3), round($h * 0.3), $filters);
            $url05 = $this->getCropUrl(round($w * 0.6), round($h * 0.6), $filters);
            $url2x = $this->getCropUrl($w * 2, $h * 2, $filters);
            $tag .= ' srcset="' . $url02 . ' 0.3x,' . $url05 . ' 0.6x, ' . $url . ' 1x, ' . $url2x . ' 2x" ';
        }

        if (strlen($url)) {
            return '<img src="' . $url . '" alt="' . $alt . '" ' . $tag . ' />';
        } else {
            return '';
        }
    }

    /**
     *
     * Supprime ce fichier du serveur
     * @return true ou false si l'opération s'est bien déroulée
     *
     */
    function deleteFile($updateDb = false)
    {
        if (strlen($this->fileName) && strlen($this->valeur)) {
            //On vérifie si le fichier est présent dans la médiathèque du champ rte
            if (strpos(BU . $this->webPath, BU . '/fichier/rte/') !== false) {
                /**
                 * On ne supprime pas les fichiers de la médiathèque
                 */
                if ($updateDb) {
                    $sql = ('UPDATE ' . $this->table . ' SET ' . $this->champ . ' = "" WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->id));
                    DoSql($sql);
                }
                $this->valeur = '';
                return true;
            } else {
                $dir = dirname($this->systemPath);
                $dir = realpath($dir);
                if (!$this->fileIsLinkedToFolder()) {
                    /**
                     * On ne supprime pas les fichiers du dossier partagé
                     */
                    $res = unlink($this->getSystemPath());
                    if ($updateDb) {
                        $sql = ('UPDATE ' . $this->table . ' SET ' . $this->champ . ' = "" WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->id));
                        DoSql($sql);
                    }
                    $this->valeur = '';
                } else {
                    return true;
                }
                return $res;
            }
        }
    }

    public function updateDb()
    {
        $name = $this->getStoredName();

        $sql = ('UPDATE ' . $this->table . ' SET ' . $this->champ . ' = ' . sql($name) . ' WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->id));

        return DoSql($sql);
    }

    public function getStoredName()
    {
        return $this->valeur;
    }


    /**
     *
     * Enregistre un fichier
     *
     * @param string $tmpname Chemin ou contenu du fichier
     * @param bool $updateDB Mise à jour du champ dans la BDD ou non
     * @param bool $fromString Upload depuis chemin ou contenu du string
     * @return bool Résultat de la copie
     */
    function uploadFile($tmpname, $updateDB = false, $fromString = false)
    {

        global $_Gconfig;
        //debug('$tmpname : '.$tmpname);


        $gf = new genFile($this->table, $this->champ, $this->id);
        $gf->deleteFile();


        if ($updateDB) {
            $this->updateDb();
        }


        $ext = $this->getExtension();
        //debug($ext);
        if (in_array($ext, $_Gconfig['notAllowedFileExtension'])) {
            derror(t('notAllowedFileExtension'), 'error');
            return false;
        }

        /* Création de l'arborescence */
        $F = dirname($this->systemPath);
        $t = explode("/", $F); //$specialUpload[$this->table][$name]

        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $reconst = '';
        } else {
            $reconst = '/';
        }
        if (!file_exists($F)) {
            mkdir($F, 0777, true);
        }

        foreach ($t as $folder) {
            if ($folder == '*ID*') {
                $folder = $this->id;
            }
            $oldReconst = $reconst;
            $reconst = path_concat($reconst, $folder);

            if (!@is_dir($reconst) && $reconst != '' && @is_writable($oldReconst)) {
                $res = @mkdir($reconst, 0777);

                if (!$res) {
                    debug('Impossible de créer le dossier : ' . $reconst);
                }
                $res = @chmod($reconst, 0777);
                //chgrp($reconst,'web');
                //@chown($reconst,100002);
            }
        }

        $fullpath = $this->getSystemPath(); //$this->systemPath. $this->fileName;
        //debug('copy to : '.$this->systemPath.$this->fileName);
        //print($tmpname.'    - '.$fullpath.'<br/>');

        if ($this->uploadFromUrl) {
            file_put_contents($fullpath, fopen($tmpname, 'r'));
        } else if ($fromString) {
            $cop = file_put_contents($fullpath, $tmpname);
        } else {
            $cop = copy($tmpname, $fullpath);

            if (!$cop) {
                $cop = move_uploaded_file($tmpname, $fullpath);
            }
        }


        //chgrp($this->systemPath.$this->fileName,'web');

        if ($_Gconfig['chmodFiles']) {

            $a = chmod($fullpath, $_Gconfig['chmodFiles']);

            if (!$a)
                dinfo(t('error_chmod'));
        }

        if ($_Gconfig['chownFiles']) {
            $a = chown($fullpath, $_Gconfig['chownFiles']);
            if (!$a)
                dinfo(t('error_chown'));
        }

        if ($_Gconfig['chgrpFiles']) {
            $a = chgrp($fullpath, $_Gconfig['chgrpFiles']);
            if (!$a)
                dinfo(t('error_chgrp'));
        }


        if (isImage($fullpath) && isset($_Gconfig['imageAutoResizeExact'][getBaseLgField($this->champ)])) {
            $maxw = $_Gconfig['imageAutoResizeExact'][getBaseLgField($this->champ)][0];
            $maxh = $_Gconfig['imageAutoResizeExact'][getBaseLgField($this->champ)][1];
            $this->easyResize($fullpath, $fullpath, 95, $maxw, $maxh);
        }
        if (isImage($fullpath) && isset($_Gconfig['imageAutoResize'][$this->champ])) {

            $maxw = $_Gconfig['imageAutoResize'][getBaseLgField($this->champ)][0];
            $maxh = $_Gconfig['imageAutoResize'][getBaseLgField($this->champ)][1];

            list($w, $h) = GetImageSize($fullpath);

            if ($w <= $maxw && $h <= $maxh) {

                // Fait rien
            } else

                if ($maxw > $maxh) {

                    if ($w > $h) {

                        $nw = $maxw;
                        $nh = round(($nw / $w) * $h);
                        if ($nh > $maxh) {
                            $nh = $maxh;
                            $nw = round(($nh / $h) * $w);
                        }
                    } else {
                        $nh = $maxh;
                        $nw = round(($nh / $h) * $w);
                        if ($nw > $maxw) {
                            $nw = $maxw;
                            $nh = round(($nw / $w) * $h);
                        }
                    }
                    $w = $nw;
                    $h = $nh;

                    $this->easyResize($fullpath, $fullpath, 95, $nw, $nh);
                } else {

                    if ($w > $h) {
                        $nh = $maxh;
                        $nw = round(($nh / $h) * $w);
                        if ($nw > $maxw) {
                            $nw = $maxw;
                            $nh = round(($nw / $w) * $h);
                        }
                    } else {
                        $nw = $maxw;
                        $nh = round(($nw / $w) * $h);
                        if ($nh > $maxh) {
                            $nh = $maxh;
                            $nw = round(($nh / $h) * $w);
                        }
                    }
                    $w = $nw;
                    $h = $nh;

                    $this->easyResize($fullpath, $fullpath, 95, $nw, $nh);
                }
        }

        if ($this->classe && method_exists($this->classe, 'onFileUploaded')) {
            $this->classe->onFileUploaded();
        }

        return $cop;
    }

    /**
     * Vérifie si le fichier existe réellement sur le disque
     *
     * @return boolean
     */
    function fileExists()
    {

        return file_exists($this->getSystemPath());
    }

    /**
     * Verifie si c'est une image en accord avec le tableau
     * $_Gconfig['imageExtensions']
     *
     * @return boolean
     */
    function isImage()
    {
        return isImage($this->fileName);
    }

    /**
     *
     * Retourne du fichier l'extension en minuscule
     *
     * @return Extension du fichier
     */
    function getExtension()
    {

        $parts = explode('.', $this->fileName);
        if (count($parts) > 1) {
            $ext = strtolower($parts[count($parts) - 1]);
        } else {
            $ext = '';
        }

        return $ext;
    }

    /**
     * Redimensionne proprement une image
     *
     * @param string $img_sourse
     * @param string $save_to
     * @param int $quality
     * @param int $width
     * @param string $str
     */
    function easyResize($img_sourse, $save_to, $quality, $width, $new_height)
    {

        $size = GetImageSize($img_sourse);

        $ext = $this->getExtension();

        $this->setMemoryForImage($img_sourse);

        if (in_array($ext, array('jpeg', 'jpg')))
            $im_in = imagecreatefromjpeg($img_sourse);
        else if ($ext == 'gif')
            $im_in = imagecreatefromgif($img_sourse);
        else if ($ext == 'png')
            $im_in = imagecreatefrompng($img_sourse);

        // $new_height = ($width * $size[1]) / $size[0]; // Generate new height for image
        $im_out = imagecreatetruecolor($width, $new_height);

        ImageCopyResampled($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]);

        //$p = new phpthumb();
        #Find X & Y for note
        /* $X_var = ImageSX($im_out);
          $X_var = $X_var - 130;
          $Y_var = ImageSY($im_out);
          $Y_var = $Y_var - 25;
         */
        #Color
        #$white = ImageColorAllocate($im_out, 0, 0, 0);
        #Add note(simple: site address)
        #ImageString($im_out,2,$X_var,$Y_var,$str,$white);

        unlink($save_to);

        //$im_in = ImageJPEG($im_out, $save_to, $quality);
        //return;

        if (in_array($ext, array('jpeg', 'jpg', 'png')))
            $im_in = ImageJPEG($im_out, $save_to, $quality);
        else if ($ext == 'gif')
            $im_in = ImageGIF($im_out, $save_to, $quality);
        else if ($ext == 'png')
            $im_in = ImagePNG($im_out, $save_to, 9, PNG_ALL_FILTERS);
    }

    function setMemoryForImage($filename)
    {
        $imageInfo = getimagesize($filename);
        $MB = 1048576;  // number of bytes in 1M
        $K64 = 65536;    // number of bytes in 64K
        $TWEAKFACTOR = 50;  // Or whatever works for you
        $memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * ($imageInfo['bits'] ? $imageInfo['bits'] : 8) * ($imageInfo['channels'] ? $imageInfo['channels'] : 8) / 8 + $K64
            ) * $TWEAKFACTOR
        );
        //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
        //Default memory limit is 8MB so well stick with that.
        //To find out what yours is, view your php.ini file.
        $memoryLimitMB = 8;
        $memoryLimit = $memoryLimitMB * $MB;
        if (function_exists('memory_get_usage') &&
            memory_get_usage() + $memoryNeeded > $memoryLimit
        ) {
            $newLimit = $memoryLimitMB + ceil((memory_get_usage() + $memoryNeeded - $memoryLimit
                    ) / $MB
                );

            ini_set('memory_limit', ($newLimit * 2) . 'M');
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Retourne la taille proprement formattee (10Mo, 2ko, ...)
     *
     * @return taille du fichier (ex: 10Mo, 2ko, ...)
     */
    function getNiceSize()
    {

        return pretty_bytes($this->getSize());
    }

    /**
     *
     *    Retourne l'icone correspondant au type du fichier
     * @return URL absolue depuis la racine vers l'icone
     *
     *    PARAMETRES
     *
     *    $front : booléen qui indique si l'icone apparait sur le front ou dans le backoffice.
     *
     */
    function getIcon($front = false)
    {

        $ext = $this->getExtension();

        $iconList = array(
            'jpg' => 'image',
            'jpeg' => 'image',
            'gif' => 'image',
            'png' => 'image',
            'tif' => 'image',
            'tiff' => 'image',
            'tga' => 'image',
            'bmp' => 'image',
            'doc' => 'doc',
            'sxw' => 'doc',
            'odt' => 'doc',
            'txt' => 'doc',
            'pdf' => 'pdf',
            'xls' => 'tableur',
            'ods' => 'tableur',
            'sxc' => 'tableur',
            'csv' => 'tableur',
            'ppt' => 'presentation',
            'odp' => 'presentation',
            'sxi' => 'presentation',
            'mpeg' => 'video',
            'avi' => 'video',
            'mpg' => 'video',
            'xvid' => 'video',
            'mov' => 'video',
            'rm' => 'video',
            'ram' => 'video',
            'divx' => 'video',
            'wmv' => 'video',
            'swf' => 'video',
            'flv' => 'video',
            'mp3' => 'son',
            'aiff' => 'son',
            'aif' => 'son',
            'ogg' => 'son',
            'asf' => 'son',
            'wma' => 'son',
            'mpc' => 'son',
            'zip' => 'compress',
            'rar' => 'compress',
            'ace' => 'compress',
            'gz' => 'compress',
            'bz2' => 'compress',
            'exe' => 'exe',
            'cgi' => 'exe',
            'hqx' => 'exe',
            'file' => 'exe',
            'html' => 'htm',
            'htm' => 'htm'
        );

        $icons = array(
            'image' => 'image-x-generic.png',
            'doc' => 'text-x-generic.png',
            'pdf' => 'x-office-address-book.png',
            'tableur' => 'x-office-spreadsheet.png',
            'presentation' => 'x-directory-desktop.png',
            'video' => 'video-x-generic.png',
            'son' => 'audio-x-generic.png',
            'compress' => 'package-x-generic.png',
            'exe' => 'application-x-executable.png',
            'htm' => 'text-html.png'
        );

        $doss = '/mimetypes/';

        if (!ake($ext, $iconList)) {
            $ext = 'file';
        }

        if ($front)
            return $_Gconfig['CDN'] . FRONT_PICTOS_FOLDER . $doss . $icons[$iconList[$ext]];

        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FRONT_SIZE . $doss . $icons[$iconList[$ext]];
    }

    /**
     *
     * @return La taille en octets du fichier ou False en cas d'erreur
     */
    function getSize()
    {

        $taille = @filesize($this->getSystemPath());

        return $taille;
    }

    /**
     *
     *
     * @return  le chemin (path) sur le systeme vers le fichier (ex; /home/user/www/fichier/foo.pdf)
     *
     */
    function getSystemPath($short = false)
    {

        if ($this->classe) {

            return $this->classe->getSystemPath($short);
        }

        if (!$this->realSystemPath) {
            if ($this->imageExists) {
                $this->realSystemPath = ($this->systemPath . $this->fileName);
            } else {
                $this->realSystemPath = ($this->systemPath . $this->fileName);
                //return '';
            }
        }

        if ($short) {
            return $this->getWebUrl();
            return path_concat('/' . str_replace(array(realpath($_SERVER['DOCUMENT_ROOT']), ' '), array('', '%20'), $this->realSystemPath));
        } else {
            return $this->realSystemPath;
        }
    }

    /**
     *
     * @return le nom de fichier sans son chemin
     *
     */
    function getRealName()
    {
        return $this->realName;
    }

    function __tostring()
    {
        return $this->getWebUrl();
    }

    public function genAdminTag()
    {


        if (!strlen($this->valeur)) {
            return '<div class="genform_uploadfile">' . t('aucun_fichier') . '</div>';
        }
        $chemin = $this->getWebUrl();
        $this->thumbWidth = 300;
        $this->thumbHeight = 500;
        $this->useThumbs = true;
        $name = $this->champ;

        $h = '';
        $h .= ('<div class="genform_uploadfile well" >');
        /* Nom du fichier */


        $h .= '<div class="badge">' . (t('deja_fichier') . ' : ');
        $h .= ('<span style="font-weight:bold;"><img style="vertical-align:middle" src="' . $this->getIcon() . '" alt="' . strtoupper($this->getExtension()) . '"/> [ ' . $this->getNiceSize() . ' ] </span> ');
        $h .= '<span class="badge badge-inverse">' . (str_replace($name . "_", "", basename($chemin)) . '</span>');
        /* Lien vers le fichier */
        $h .= (' <a  href="' . $chemin . '" target="_blank" >'); //style="width:' . $this->thumbWidth . 'px"


        $h .= '<br/>';


        /*
         * Si c'est une image , vignette + lien
         */
        if ($this->isImage()) {
            if ($this->useThumbs)
                //$h .= ( ' <br/><img src="thumb/?w='.$this->thumbWidth.'&amp;h='.$this->thumbHeight.'&amp;src='.$systemCh.'" alt="'.t($name).'" id="imgprev_'.$name.'" /><br/>');
                $h .= (' <img src="' . $this->getThumbUrl($this->thumbWidth, $this->thumbHeight) . '" alt="IMAGE" id="imgprev_' . $name . '"  /><br/>');
            else
                $h .= (' <img src="' . $chemin . '?' . time() . '" alt="' . t($name) . '" width="' . $this->thumbWidth . '" />');
        } // <param name="FlashVars" value="clip=' . $this->getWebUrl() . '&amp;margin=2&amp;bgcolor1=000000&amp;bgcolor2=000000&amp;showstop=1&amp;loadingcolor=555555&amp;showvolume=1&amp;showtime=1&amp;showfullscreen=1&amp;playercolor=ffffff&amp;buttoncolor=000000&amp;showiconplay=1&amp;iconplaybgcolor=ffffff&amp;videobgcolor=ffffff&amp;loadonstop=0" />
        else if ($this->isVideo()) {
            $h .= ('' . t('voir'));
            $h .= '</a><br/><video controls src="' . $this->getWebUrl() . '" width="50%" preload="none" ></video>';
        } else {
            /* Sinon juste lien */
            $h .= ('' . t('voir'));
        }

        $h .= ('</a></div>');


        /* Edition via l'ImageManager */


        if ($this->isImage($chemin) && $this->useImageEditor) {
            $h .= ('<a class="btn btn-mini" href="TUI/index.php?img=' . $this->getWebUrl() . '%3F' . time() . '&curTable=' . $this->table . '&curChamp=' . $this->champ . '&curId=' . $this->id . '&update=imgprev_' . $name . '&curName=' . $this->fileName . '" onclick="window.open(this.href,\'\',\'width=900,height=700\');return false" >' . t('edis_image') . '</a>');
        }


        $h .= (' <button name="genform_' . $name . '_del"
                        onclick="if(confirm(\'' . t('supprimer_fichier') . '\')) {deleteFile(\'' . $this->table . '\',\'' . $name . '\',\'' . $this->id . '\',this,\'\')}return false"
                            class="btn btn-important btn-mini"
			><img src="' . t('src_delete') . '"
			 /> ' . t('supprimer') . '</button>');

        $h .= ('</div>');

        return $h;
    }

    public function isVideo()
    {
        $res = $this->getExtension() == 'webm' || $this->getExtension() == 'mp4' || $this->getExtension() == 'm4v' || $this->getExtension() == 'ogv';
        return $res;
    }

    public function genSmallAdminTag()
    {

        if (!strlen($this->valeur)) {
            return '<div class="genform_uploadfile">&nbsp;</div>';
        }

        $chemin = $this->getWebUrl();
        $this->thumbWidth = 50;
        $this->thumbHeight = 50;
        $this->useThumbs = true;
        $name = $this->champ;


        $h = '';
        $h .= ('<div class="genform_uploadfile">');

        /* Lien vers le fichier */
        $h .= (' <a style="display:block;margin:0;" class="well" href="' . $chemin . '" target="_blank" >'); //style="width:' . $this->thumbWidth . 'px"

        $h .= (str_replace($name . "_", "", basename($chemin)));

        $h .= '<br/>';


        /*
         * Si c'est une image , vignette + lien
         */
        if ($this->isImage()) {
            if ($this->useThumbs)
                //$h .= ( ' <br/><img src="thumb/?w='.$this->thumbWidth.'&amp;h='.$this->thumbHeight.'&amp;src='.$systemCh.'" alt="'.t($name).'" id="imgprev_'.$name.'" /><br/>');
                $h .= (' <img src="' . $this->getThumbUrl(round($this->thumbWidth / 2), round($this->thumbHeight / 2)) . '" alt="" id="imgprev_' . $name . '"  /><br/>');
            else
                $h .= (' <img src="' . $chemin . '" alt="' . t($name) . '" width="' . $this->thumbWidth . '" />');
        } else if ($this->isVideo()) {
            $h .= ('' . t('voir'));
            $h .= '</a><video controls src="' . $this->getWebUrl() . '" width="' . round($this->thumbWidth / 2) . '" height="' . round($this->thumbHeight / 2) . '"  preload="none" ></video>';
        } else {
            /* Sinon juste lien */
            $h .= ('' . t('voir'));
        }

        $h .= ('</a>');


        /* Edition via l'ImageManager */

        $ssch = substr($this->getWebUrl(), strlen(BU));


        $h .= ('<div style="text-align:left"><button class="btn btn-mini" onclick="if(confirm(\'' . t('supprimer_fichier') . '\')) {deleteFile(\'' . $this->table . '\',\'' . $name . '\',\'' . $this->id . '\',this,1);} return false" >
                    <i class="icon icon-trash"></i> ' . t('supprimer') . '</button></div>');

        $h .= ('</div>');

        return $h;
    }

}
