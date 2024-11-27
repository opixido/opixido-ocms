<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you cgan redistribute it and/or modify
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

class ocmsPlugin
{

    /**
     * Gensite
     *
     * @var gensite
     */
    public $site;

    public $plugins;

    /**
     * Constructeur
     *
     * @param gensite $site
     */
    function __construct($site)
    {

        $this->site = $site;
        $this->plugins = &$site->plugins;
    }

}

class baseObj
{

    /**
     * gensite
     *
     * @var gensite
     */
    public $site;

    /**
     * params
     *
     * @var array
     */
    public $params;

    /**
     * Table utilisée
     *
     * @var string Table SQL
     */
    public $table = false;

    /**
     * Clef passée dans l'URL pour la fiche
     *
     * @var string
     */
    public $clef = 'id';
    public $row = array();

    function __construct($roworid = false)
    {
        $this->site = $GLOBALS['site'];

        if (!$this->table) {
            return;
        }
        /**
         * Recuperation automatique
         * des informations
         */
        if (is_array($roworid)) {
            $this->row = $roworid;
            $this->id = $roworid[getPrimaryKey($this->table)];
        } else if ($roworid) {
            $this->id = $roworid;
            $this->row = getRowAndRelFromId($this->table, $this->id);
        } else
            if (!empty($_REQUEST[$this->clef])) {
                $this->id = $_REQUEST[$this->clef];
                $this->row = getRowAndRelFromId($this->table, $this->id);
            }

    }

    /**
     * Recupération d'un champ
     * (mini genform pour front)
     *
     * @param string $champ
     * @return mixed
     */
    function get($champ)
    {

        if (isBaseLgField($champ, $this->table)) {
            return getLgValue($champ, $this->row);
        }

        global $uploadFields;
        if (arrayInWord($uploadFields, $champ)) {
            $gf = new genFile($this->table, $champ, $this->row);
            return $gf;
        }


        return $this->row[$champ];
    }

    /**
     * Génére le tout
     * Liste ou fiche élément
     *
     * @return string HTML
     */
    function gen()
    {

        if ($this->table) {
            if (akev($_GET, $this->clef)) {
                return $this->genOne();
            } else {
                return $this->genAll();
            }
        }

        return;
    }

    /**
     * Génére une fiche Element
     *
     * @return string
     */
    function genOne()
    {

        $row = getRowFromId($this->table, $_GET[$this->clef]);

        $html = '<h3>' . GetTitleFromRow($this->table, $row) . '</h3>';
        $html .= '<dl>';
        foreach ($row as $k => $v) {
            $html .= '<dt>' . t($k) . '</dt>';
            $html .= '<dd>' . $v . '</dd>';
        }
        $html .= '</dl>';

        return $html;
    }

    /**
     * Génére une liste
     *
     * @return unknown
     */
    public function genAll()
    {

        $res = GetAll("SELECT * FROM " . $this->table . ' ORDER BY ' . GetTitleFromTable($this->table, ' , '));

        $html = '<ul>';
        foreach ($res as $row) {
            $html .= '<li><a href="' . getUrlWithParams(array($this->clef => $row[getPrimaryKey($this->table)])) . '">' . GetTitleFromRow($this->table, $row) . '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    public function getTitle()
    {

        return GetTitleFromRow($this->table, $this->row);
    }

    public function getUrl()
    {


        return getUrlWithParams(array(getPrimaryKey($this->table) => $this->id));
    }

}

class baseGen extends baseObj
{

    public $plugins;

    function __construct($site, $params = "")
    {

        $this->site = $site;
        $this->params = SplitParams($params, ';', '=');

        $this->plugins = &$site->plugins;
        if (method_exists($this, 'ocms_defaultParams')) {
            $defParams = $this->ocms_defaultParams();
            foreach ($defParams as $k => $v) {
                if (empty($this->params[$k])) {
                    $this->params[$k] = $v;
                }
            }
        }

        parent::__construct();

        /**
         * Rajouts automatique au titre ou chemin de fer
         */
        if ($this->row) {
            //$this->site->g_headers->addTitle(GetTitleFromRow($this->table, $this->row));
            //$this->site->g_url->addRoad(GetTitleFromRow($this->table, $this->row), getUrlWithParams(array($this->clef => $this->id)));
        }
    }

}

class ocmsGen extends baseGen
{

}


class rubrique extends row
{

    public $table = 's_rubrique';

    public function __construct($row)
    {
        parent::__construct($this->table, $row);
    }

    /**
     * Returns the $limit following rubriques
     *
     * @param int $limit
     * @return array
     */
    public function getNextRubs($limit = 1)
    {

        return $this->getAdjacentRubs($limit, 'ASC');
    }

    /**
     * Returns the $limit previous rubriques
     *
     * @param int $limit
     * @return array
     */
    public function getPreviousRub($limit = 1)
    {

        return $this->getAdjacentRubs($limit, 'DESC');
    }

    /**
     * Returns the $limit adjacent rubriques
     * if $order is ASC, returns the next rubs,
     * if $order is DESC, returns the previous rubs
     *
     * @param int $limit
     * @param string $order ASC or DESC
     * @return array
     */
    public function getAdjacentRubs($limit = 0, $order = '')
    {

        /**
         * Sql Query
         */
        $sql = 'SELECT * FROM s_rubrique
		WHERE
		fk_rubrique_id = ' . sql($this->row['fk_rubrique_id']) . '
		';

        if ($order == 'ASC') {
            $sql .= ' AND rubrique_ordre > ' . $this->row['rubrique_ordre'] . ' ';
        } else if ($order == 'DESC') {
            $sql .= ' AND rubrique_ordre < ' . $this->row['rubrique_ordre'] . ' ';
        }

        $sql .= ' ' . sqlRubriqueOnlyOnline() . '
		
		ORDER BY rubrique_ordre ' . $order . '
		
		';

        /**
         * How many next rubs
         */
        if ($limit) {
            $sql .= ' LIMIT 0,' . $limit . '';
        }

        /**
         * If only one, the returns a getSingle instead of GetAll
         */
        if ($limit == 1) {
            return GetSingle($sql);
        }

        return GetAll($sql);
    }

    /**
     * Returns the $row of the parent rubrique
     * or false if we are on siteroot
     *
     * @return mixed
     */
    public function getParentRub()
    {
        if ($this->row['fk_rubrique_id']) {
            return getRowFromId('s_rubrique', $this->row['fk_rubrique_id']);
        }
        return false;
    }

    /**
     * Returns child rubriques
     *
     * @return array
     */
    public function getChildRubs()
    {
        $sql = 'SELECT * FROM s_rubrique WHERE
                    fk_rubrique_id = ' . sql($this->id) . ' ' . sqlRubriqueOnlyOnline() . '
                    ORDER BY rubrique_ordre ASC
                ';
        return GetAll($sql);
    }

    /**
     * Returns rubrique URL
     *
     * @param array $params
     * @return string
     */
    public function getUrl($params = array())
    {

        return getUrlFromId($this->id, LG(), $params);
    }

    /**
     * Returns Rubrique Title
     *
     * @return string Title
     */
    public function getTitle()
    {
        return $this->rubrique_titre;
    }

    /**
     * Returns all subs paragraphs and paragraphs type
     *
     * @return array Paragaphs
     */
    public function getParagraphes()
    {
        $sql = 'SELECT * FROM s_paragraphe, s_para_type WHERE fk_rubrique_id = ' . sql($this->id) . ' AND fk_para_type_id = para_type_id ORDER BY paragraphe_ordre ASC ';
        return GetAll($sql);
    }


    /**
     * Return all images
     */
}


class paragraphe extends row
{

    public $table = 's_paragraphe';

    public function __construct($row)
    {
        parent::__construct($this->table, $row);
    }

}