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
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

class genRubrique
{

    /**
     * gensite
     *
     * @var genSite
     */
    private $site;

    /**
     * identifiant en cours
     *
     * @var int
     */
    public $rubrique_id;
    private $contexteOnTop;
    private $contextBoxes;
    public $showParagraphes;
    public $showBoxLexique;
    public $showBoxDwl;
    public $showBoxLinks;
    public $html_after_paras;
    public $hasBddInfo;
    public $doGenMain = true;
    public $rubrique = array();
    public $fk_rubrique_version_id = 0;
    public $params = array();
    public $bddClasse = '';
    public $road, $date_publi, $paragraphes;

    /**
     *
     * @var rubrique
     */
    public $rObj;

    /**
     * Liste des objets plugins
     *
     * @var array
     */
    var $plugins = array();

    /**
     * $row du gabarit
     *
     * @var array
     */
    var $gabarit;

    /**
     *
     * @var rubrique
     */
    var $row;

    /**
     * Genere la rubrique avec les classes, paragraphes, ...
     *
     * Objets accessibles :
     * Lexique : $this->g_boxLexique  (Methode addMot($idMot))
     * Telechargements : $this->dwlBox (Method add())
     * En savoir plus : $this->linksBox (Method add())
     *
     * @param showParagraphes bool Definit si l'on utilise les paragraphes ou non
     * @param showBoxLexique = Definit si l'on affiche la boite Lexique
     * @param showBoxDwl = Definit si l'on affiche la boite Telechargement
     * @param showBoxLinks = Definit si l'on affiche la boite "En savoir plus"
     *
     * @param contexteOnTop = Definit si l'on affiche les contextes a "top"  ou "right"
     *
     * @param html_after_paras = Code HTML ajoute apres la generation des paragraphes
     */
    function __construct(genSite $site)
    {

        global $_Gconfig;

        $this->site = &$site;
        $this->doGenMain = true;
        $this->html_after_paras = '';
        $this->showParagraphes = true;
        $this->showBoxLexique = false;
        $this->showBoxDwl = true;
        $this->showBoxLinks = true;
        $this->hasBddInfo = false;

        $this->contextBoxes = array();

        /* Recuperation de l'ID */
        $this->rubrique_id = $this->site->getCurId();

        /* Et de son contenu */
        $this->rubrique = GetRowFromId('s_rubrique', $this->rubrique_id, empty($_REQUEST['_version']));
        $this->row = new rubrique($this->rubrique);

        $this->fk_rubrique_version_id = $this->rubrique[MULTIVERSION_FIELD];

        $_REQUEST['para'] = ake($_REQUEST, 'para') ? $_REQUEST['para'] : '';

        /* Si elle a un gabarit (plutot conseille pour afficher quelque chose */
        if ($this->rubrique['fk_gabarit_id'] > 0) {
            $this->gabarit = GetRowFromId('s_gabarit', $this->rubrique['fk_gabarit_id'], 1);
        }

        if (!empty($this->gabarit['gabarit_classe']) && strlen(trim($this->gabarit['gabarit_classe']))) {
            $this->hasBddInfo = true;
        } else if (GABARIT_DEF) {
            $this->gabarit = GetRowFromId('s_gabarit', GABARIT_DEF, 1);
            $this->rubrique['fk_gabarit_id'] = $this->gabarit['gabarit_id'];
            $this->hasBddInfo = true;
        }

        $this->params = SplitParams($this->rubrique['rubrique_gabarit_param']);


        $this->rObj = $r = new rubrique($this->rubrique);
        /* Definition des Headers de la page relatifs a cette rubrique */

        $this->road = $this->site->g_url->buildRoad($this->rubrique_id);

        $title = $this->getFullTitle();


        $this->site->g_headers->setTitle($title);

        $this->site->g_headers->setMetaKeywords($r->rubrique_keywords);

        $this->site->g_headers->setMetaDescription($r->rubrique_desc);

        $this->site->g_headers->setMeta('og:site_name', t('base_title'));
        $this->site->g_headers->setMeta('og:title', $r->rubrique_titre);
        $this->site->g_headers->setMeta('og:type', 'website');
        $this->site->g_headers->setMeta('og:description', $r->rubrique_desc);
        $img = $r->rubrique_picto->getWebUrl();
        if ($img) {
            $this->site->g_headers->setMeta('og:image', getServerUrl() . $img);
        }

        $this->date_publi = strtotime($this->rubrique[$_Gconfig['field_date_maj']]);


        $this->getParagraphes();
    }


    /**
     * On est sur la vrai rubrique ou bien celle modifiable ?
     */
    function isRealRubrique()
    {


        if ($this->site->g_url->action == "editer") {
            return false;
        }
        return true;
    }

    /**
     * Gestion des classes externes
     */
    function afterInit()
    {
        global $co, $gb_obj;

        $GLOBALS['times']['BDD'] = 0;
        if ($this->hasBddInfo) {
            $startTimeBdd = getmicrotime();

            $this->bddClasse = getGabaritClass($this->gabarit, $this->rubrique['rubrique_gabarit_param']);

            $GLOBALS['times']['BDD'] += (getmicrotime() - $startTimeBdd);
            $GLOBALS['times']['Plugins'] += $GLOBALS['times']['BDD'];
        }

    }

    function Execute($what)
    {
        return $this->site->Execute($what);
    }

    /**
     * GenTop
     *
     */
    function genTop()
    {
        return $this->Execute('genTop');
    }

    /**
     * Execute la methode genOutside de la classe associee si presente
     * et retourne le contenu
     *
     */
    function genOutside()
    {

        return $this->Execute('genOutside');
    }

    /**
     *
     * Execute la methode genOutside de la classe associee si presente
     * et retourne le contenu
     *
     */
    function gen1()
    {

        return $this->Execute('gen1');
    }

    function getFullTitle()
    {


        $i = 1;


        $revRoad = array_reverse($this->road);

        $html = '';

        $revRoad = array_slice($revRoad, 0, -2);

        $nbr = count($this->road);
        /* On the road again ... */
        foreach ($revRoad as $k => $v) {

            if (akev($v, 'id')) {
                $row = getRowFromId('s_rubrique', $v['id']);
                $titre = getLgValue('rubrique_titre', $row);
            } else {
                $titre = $v['titre'];
            }


            if ($i < $nbr || $nbr == 1) {
                $html .= '' . $titre . '';
                $html .= ' - '; // Separateur
            } else {

            }

            $i++;
        }


        return substr($html, 0, -2);
    }

    public function genBeforePara()
    {
        return $this->Execute('genBeforePara');
    }

    /**
     * Generation des paragraphes
     */
    public function genMain()
    {


        if (!$this->doGenMain) {
            return;
        }

        global $co;

        $html = '';


        if ($this->showParagraphes) {

            if (method_exists($this->bddClasse, 'genParagraphes')) {

                $html .= $this->bddClasse->genParagraphes();
            } else {

                $par = new genParagraphes($this->site, $this->paragraphes);
                $html .= $par->gen();
            }

            /* Liens de nav en bas */
            $html .= $this->html_after_paras;
        }

        $html .= $this->Execute('gen');


        return $html;
    }

    /**
     * Selectionne les paragraphes
     */
    function getParagraphes()
    {


        $sql = 'SELECT * FROM s_paragraphe AS P LEFT JOIN s_para_type AS PT ON P.fk_para_type_id = PT.para_type_id
				WHERE P.fk_rubrique_id = ' . sql($this->rubrique_id, 'int') . '

				ORDER BY paragraphe_ordre ASC
				';


        $this->paragraphes = GetAll($sql);
    }

    /**
     * Selectionne les sous rubriques
     */
    function getSubRubs()
    {

        if ($this->isRealRubrique()) {
            $tid = $this->rubrique_id;
        } else {
            $tid = $this->rubrique['fk_rubrique_version_id'];
        }

        if ($this->site->g_url->minisite && $this->site->g_url->rootHomeId == $tid) {
            return array();
        }

        $sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_id = "' . mes($tid) . '" ' . sqlRubriqueOnlyReal() . '  ' . sqlRubriqueOnlyOnline() . ' order by rubrique_ordre';
        $res = GetAll($sql);

        $this->subRubs = $res;

        return $res;
    }

}
