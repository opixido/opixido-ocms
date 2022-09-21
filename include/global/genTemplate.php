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

class genTemplate
{

    /**
     * Tableau des variables texte à remplacer
     *
     * @var array
     */
    private $vars = array();

    /**
     * Tableau des variables images à remplacer
     *
     * @var array
     */
    private $imgs;

    /**
     * Nom ou contenu du template à utiliser
     *
     * @var mixed
     */
    private $template;

    /**
     * Liste des variables texte avec @@ à remplacer
     *
     * @var array
     */
    private $replaces = array();

    /**
     * Liste des conditions
     *
     * @var array
     */
    private $conditions = array();

    /**
     * Definit si l'on doit faire les str_replace() de @@
     *
     * @var boolean
     */
    private $doreplace = true;

    /**
     * Tableau des blocks de remplacement
     *
     * @var array
     */
    public $blocks = array();

    /**
     * List of defined Blocks
     * Automaticaly removed if not instanciated
     *
     * @var array
     */
    private $definedBlocks = array();

    /**
     * Thumbnail quality
     *
     * @var int
     */
    public $q = 95;

    /**
     * File to load
     *
     * @var string
     */
    public $toLoad = '';

    /**
     * Constructeur
     *
     *
     * @param boolean $doreplace Si TRUE, tout ce qui se trouve entre
     *                            @@XYZ@@ sera automatiquement remplacé par un texte
     *                            et ##XYZ## par une image
     */
    public function __construct($doreplace = true)
    {

        $this->vars = array();
        $this->imgs = array();
        $this->template = '';
        $this->doreplace = $doreplace;

        return $this;
    }

    function reset()
    {

        $this->vars = array();
        $this->imgs = array();
        $this->conditions = array();
    }

    /**
     * Définit une variable
     *
     * @param string $nom Son nom
     * @param mixed $valeur Sa valeur
     * @param boolean $strreplace UNUSED
     */
    public function setVar($nom, $valeur, $strreplace = false)
    {
        $this->vars[$nom] = $valeur;

        if ($this->doreplace && !in_array('@@' . $nom . '@@', $this->replaces)) {
            if (!is_array($valeur) && !is_object($valeur)) {
                $this->replaces[] = '@@' . $nom . '@@';
            } else {
                $this->replaces[] = '';
            }
        }

        return $this;
    }

    /**
     * Définit une variable
     * Alias de setVar()
     *
     * @param string $nom Son nom
     * @param mixed $valeur Sa valeur
     * @param boolean $strreplace UNUSED
     * @uses $this->setVar()
     *
     */
    public function set($nom, $valeur, $strreplace = false)
    {
        $this->setVar($nom, $valeur, $strreplace);
        return $this;
    }

    final function __set($name, $value)
    {
        $this->setVar($name, $value);
        return $this;
    }

    final function __get($name)
    {
        return $this->getVar($name);
    }

    public function isDefined($nom)
    {
        if (ake($this->vars, $nom)) {
            return true;
        }
        if (ake($this->imgs, $nom)) {
            return true;
        }
        return false;
    }

    /**
     * Définit une image
     *
     * @param string $nom
     * @param string $src
     * @param string $alt
     */
    public function setImg($nom, $src, $alt = '')
    {

        $this->imgs[$nom] = array();
        $this->imgs[$nom]['src'] = $src;
        if (!$alt || !strlen($alt))
            $alt = "";
        $this->imgs[$nom]['alt'] = $alt;
        return $this;
    }

    /**
     * Définit une image en crééant un GenFile
     *
     * @param string $nom
     * @param string $src
     * @param string $alt
     */
    public function setGFImg($nom, $table, $champ, $row, $alt = '')
    {

        $this->imgs[$nom] = array();
        $gf = new genFile($table, $champ, $row, $row, true, !ake($row, $champ));

        $this->imgs[$nom]['src'] = $gf->getWebUrl();
        if (!strlen($alt))
            $alt = "";
        $this->imgs[$nom]['alt'] = $alt;
        $this->imgs[$nom]['gf'] = $gf;
        return $this;
    }

    public function getGfImg($nom)
    {
        return $this->imgs[$nom]['gf'];
    }

    /**
     * Prend toutes les variables du tableau $vars
     * et utilise $this->setVar() dessus
     *
     * @param array $vars
     * @param boolran $doreplace UNUSED
     */
    public function setVars($vars, $doreplace = false)
    {
        foreach ($vars as $k => $v) {
            $this->setVar($k, $v, $doreplace);
        }
        return $this;
    }

    /**
     * Retourne une valeur
     *
     * @param string $nom
     * @return mixed
     */
    private function get($nom)
    {
        return $this->getVar($nom);
    }

    /**
     * Pour les variables définies avec le setVars() donc en tableau
     * retourne la meilleur langue accepatble pour un texte donné
     *
     * @param string $nom
     * @return mixed
     */
    private function getlg($nom)
    {
        return getLgValue($nom, $this->vars);
    }

    /**
     * Retourne une valeur
     *
     * @param string $nom
     * @return mixed
     */
    private function getVar($nom)
    {
        return akev($this->vars, $nom);
    }

    /**
     * Pour une image ne retourne que son SRC
     *
     * @param string $nom
     * @return string
     */
    private function getImgUrl($nom)
    {
        return $this->imgs[$nom]['src'];
    }

    /**
     * Pour une image ne retourne que son ALT
     *
     * @param string $nom
     * @return string
     */
    private function getImgAlt($nom)
    {
        return $this->imgs[$nom]['alt'];
    }

    /**
     * Retourne un tag <img src="" alt="" /> complet
     *
     * @param string $nom
     * @param string $newalt Valeur de remplacement pour le ALT au cas où ...
     * @return string
     */
    private function getImg($nom, $newalt = false)
    {

        if (is_array($this->imgs[$nom])) { //is_file($this->imgs[$nom]['src'])) {
            //$taile = @getimagesize($this->imgs[$nom]['src']);
            $taile = false;
            //debug(@gethostbyname('www.ined.loc'));
            if (!strlen(trim($this->imgs[$nom]['src']))) {
                return '';
            }

            if ($newalt) {
                $this->imgs[$nom]['alt'] = $newalt;
            }
            if (is_array($taile)) {
                return '<img style="width:' . ($taile[0]) . 'px;height:' . ($taile[1]) . 'px;" src="' . $this->imgs[$nom]['src'] . '" alt="' . $this->imgs[$nom]['alt'] . '" />';
            } else {
                return '<img src="' . $this->imgs[$nom]['src'] . '" alt="' . altify($this->imgs[$nom]['alt']) . '" />';
            }
        } else {
            return ''; //return $this->getDefaultImage($this->imgs[$nom]['src']);
        }
    }

    /**
     * Retourne un tab <img ... /> complet
     * mais avec l'image redimensionnée
     *
     * @param string $nom
     * @param int $w
     * @param int $h
     * @param string $newalt
     * @return string
     */
    private function getThumb($nom, $w, $h, $newalt = false)
    {
        if (is_array($this->imgs[$nom])) {
            if ($newalt) {
                $this->imgs[$nom]['alt'] = $newalt;
            }
            $url = $this->getThumbUrl($nom, $w, $h);

            return '<img src="' . $url . '" alt="' . altify($this->imgs[$nom]['alt']) . '" />';
        } else {
            return '';
        }
    }

    /**
     * Retourne l'URL uniquement de l'image redimensionnée
     *
     * @param string $nom
     * @param int $w
     * @param int $h
     * @return string
     */
    private function getThumbUrl($nom, $w, $h, $add = '')
    {
        if (!empty($this->imgs[$nom]['gf']) && !$this->imgs[$nom]['gf']->isImage())
            return $this->imgs[$nom]['gf']->getWebUrl();

        if (!$this->imgs[$nom]['src']) {
            return '';
        }
        return getThumbCacheFile(THUMBPATH . '?src=' . $this->imgs[$nom]['src'] . '&amp;w=' . $w . '&amp;h=' . $h . '&amp;f=' . substr($this->imgs[$nom]['src'], -3) . '&amp;q=' . $this->q . '' . $add);
    }

    /**
     * Définit le code HTML du template via la variable $tpl
     *
     * @param string $tpl
     */
    public function setTemplate($tpl)
    {
        $this->template = $tpl;
        return $this;
    }

    /**
     * Indique le nom du fichier template à charger
     *
     * @param string $tpl
     * @param string $folder
     */
    public function loadTemplate($tpl, $folder = 'template')
    {

        global $gb_obj;
        $this->tplnom = $folder . '/' . $tpl;
        $this->template = $gb_obj->loadFile($tpl . ".php", $folder);

        return $this;
    }

    public function replaceBlock($bloc, $replace)
    {
        $this->blocks[$bloc]['template'] = $replace;
        return $this;
    }

    /**
     * Retourne l'image par défaut
     *
     * @param string $url
     * @return string
     */
    public function getDefaultImage($url = "")
    {
        return "<img src='/img/default.jpg' alt='" . t('empty_image') . " " . altify($url) . "' style='padding:5px;border:1px solid #cc0000;'/>";
    }

    public function defineBlocks()
    {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for ($i = 0; $i < $numargs; $i++) {
            $this->definedBlocks[] = $arg_list[$i];
        }
        return $this;
    }

    public function getFile()
    {

    }

    /**
     * Defines a list of trads to insert in the template
     *
     */
    public function setTrads()
    {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for ($i = 0; $i < $numargs; $i++) {
            $this->set($arg_list[$i], t($arg_list[$i]));
        }
        return $this;
    }

    /**
     * Définit et charge un nouveau bloc
     * dans le template
     *
     * @param unknown_type $nom
     */
    public function loadBlock($nom)
    {

        if (akev($this->blocks, $nom)) {

        } else {
            $this->blocks[$nom] = array();
            $start = mb_stripos($this->template, '<' . $nom . '>') + mb_strlen('<' . $nom . '>');
            $end = mb_stripos($this->template, '</' . $nom . '>');

            if ($start !== false && $end !== false) {

                $this->blocks[$nom]['template'] = mb_substr($this->template, $start, $end - $start);
                $this->blocks[$nom]['blocks'] = array();

                $this->template = mb_substr($this->template, 0, $start - mb_strlen('<' . $nom . '>')) . '<_' . $nom . '_>' . mb_substr($this->template, $end + mb_strlen('</' . $nom . '>'));
            } else {
                if (strpos($nom, '!') === false) {
                    //devbug('Template : '.$this->tplnom.' : BLOC inexistant : '.$nom);
                }
            }
        }
        return $this;
        //return $this->blocks[$nom]['template'];
    }

    /**
     * Créé un nouveau block basé sur un template
     *
     * @param string $nom
     * @return genTemplate
     */
    public function addBlock($nom)
    {

        if (!akev($this->blocks, $nom)) {
            $this->loadBlock($nom);
        }
        if (empty($this->blocks[$nom]['blocks'])) {
            $cur = 0;
        } else {
            $cur = count($this->blocks[$nom]['blocks']);
        }
        $this->blocks[$nom]['blocks'][$cur] = new genTemplate($this->doreplace);
        if (!empty($this->blocks[$nom]['template'])) {
            $this->blocks[$nom]['blocks'][$cur]->setTemplate($this->blocks[$nom]['template']);
        }
        return $this->blocks[$nom]['blocks'][$cur];
    }

    /**
     * Génère une liste de blocs à partir d'un tableau
     *
     * @param $nom string Nom du bloc
     * @param $tableau array liste des entrées
     */
    public function addBlocks($nom, $tableau)
    {
        foreach ($tableau as $tab) {
            $t = $this->addBlock($nom);
            foreach ($tab as $k => $v) {
                $t->$k = $v;
            }
        }
    }

    /**
     * Removes a specific block
     * For example if empty
     *
     * @param string $nom
     */
    public function delBlock($nom, $replace = "")
    {
        if (!strlen($replace)) {
            $replace = " ";
        }
        if (!is_array(akev($this->blocks, $nom))) {
            $this->loadBlock($nom);
        }

        $this->blocks[$nom]['template'] = $replace;
        if (strlen($replace)) {
            $this->addBlock($nom);
        }
        return $this;
    }

    /**
     * Génère le template
     *
     * @return unknown
     */
    public function gen()
    {


        /**
         * Pour récupérer tous les print
         */
        ob_start();


        /**
         * On supprime les blocs definits mais non utilisés
         */
        foreach ($this->definedBlocks as $v) {
            if (empty($this->blocks[$v])) {
                $this->delBlock($v);
            }
        }


        /**
         * Mauvais template
         * On affiche toutes les variables brutes
         */
        if (strlen($this->template) < 2) {
            foreach ($this->vars as $k => $v) {
                p('<h3>' . $k . '</h3><p>' . $v . '</p>');
            }
        } /**
         * Si on a déjà le contenu HTML du template
         * on évalue
         */ else if (!$this->toLoad) {
            eval(' ?' . '>' . $this->template . '<?' . 'php ');


            /**
             * Sinon on inclu le template
             */
        } else {
            global $gb_obj;

            $path = $gb_obj->getIncludePath($this->template, 'template');

            include($path);
        }

        /**
         * On récupère le contenu HTML généré
         */
        $html = ob_get_contents();
        ob_end_clean();

        /**
         * Si on devait rmeplacer les @@XX@@ on fait notre liste de remplacements
         */
        if (($this->doreplace) && $this->replaces) { // && $this->replaces
            /**
             * Les textes
             */
            $html =
                str_ireplace(
                    array_filter($this->replaces),
                    array_filter($this->vars, function ($v) {
                        return !is_object($v) && !is_array($v);
                    }),
                    $html
                );


            /**
             * Les images
             */
            foreach ($this->imgs as $k => $v) {
                $html = str_ireplace('##' . $k . '##', $this->getImg($k), $html);
            }
        }


        /**
         * Conditions
         */
        $initTemplate = $this->template;
        $this->template = $html;
        foreach ($this->conditions as $k => $v) {

            $nom = 'IF_' . strtoupper($k);

            if ($v) {
                $this->addBlock($nom);
            } else {
                $this->delBlock($nom);
            }

            $nom = 'IF_!' . strtoupper($k);

            if ($v) {
                $this->delBlock($nom);
            } else {
                $this->addBlock($nom);
            }
        }
        $html = $this->template;
        $this->template = $initTemplate;

        /**
         * On génère tous les blocs
         */
        if (count($this->blocks)) {
            /**
             * On parcourt les blocs
             */
            foreach ($this->blocks as $k => $v) {
                $htmlB = '';
                /**
                 * On parcourt chaque élément dupliqué d'un bloc
                 */
                //debug($v);
                if (!empty($v['blocks']) && is_array($v['blocks'])) {
                    foreach ($v['blocks'] as $v) {
                        $htmlB .= $v->gen();
                    }

                    $html = str_replace('<_' . $k . '_>', $htmlB, $html);
                }
            }
        }


        /**
         * Si on debug un peu, on met les noms des templates en commentaires HTML
         */
        if (akev($_REQUEST, 'debugTemplate')) {
            return '<!-- TEMPLATE ' . $this->tplnom . ' -->' . $html . '<!-- FIN TEMPLATE ' . $this->tplnom . ' -->';
        } else {
            return $html;
        }
    }

    public function setCondition($nom, $val)
    {
        $this->conditions[$nom] = $val;
        return $this;
    }

    public function __tostring()
    {
        return $this->gen();
    }


    public function genTemplateFile()
    {
        $h = '';
        foreach ($this->vars as $k => $v) {
            $h .= '@@' . $k . '@@' . "\n";
        }
        return $h;
    }

}

