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

class bloc
{

    public $contenu = array();
    public $visible = true;
    public $nom = 'default';
    public $toAddBefore = array();
    public $toAddAfter = array();
    public $tag = '';

    /**
     * gensite
     *
     * @var gensite
     */
    public $site;

    /**
     * Constructeur
     *
     * @param genSite $site
     */
    function __construct($site)
    {

        $this->site = $site;
    }

    /**
     * Si il est bien visible, on décale le contenu
     *
     */
    function afterInit()
    {

        if ($this->visible) {

        }
    }

    /**
     * Sets this bloc not to render
     *
     */
    function hide()
    {

        $this->visible = false;
        return $this;
    }

    /**
     * Makes this box visible
     *
     */
    function show()
    {

        $this->visible = true;
        return $this;
    }

    public function gen()
    {
        return $this->genBloc();
    }

    /**
     * returns content
     *
     */
    function genBloc()
    {

        if ($this->visible) {

            $this->sort();
            $html = '<div id="bloc_' . $this->nom . '" ' . $this->tag . '>';

            foreach ($this->contenu as $nom => $v) {
                $html .= ($v['contenu']);
            }

            $html .= '</div>';
            return $html;
        }
    }

    /**
     * Ajoute une boite à la fin
     *
     * @param string $nom Nom de la boite
     * @param string $html code HTML
     */
    function add($nom, $html, $class = '', $poids = 100)
    {

        $this->contenu[ $nom ] = array(
            'poids'   => $poids,
            'contenu' => '<div class="bloc ' . $class . '" id="bloc_' . $this->nom . '_' . $nom . '">' . $html . '</div>'
        );


        return $this;
    }

    /**
     * Ajoute une boite $nom apres la boite $other
     *
     * @deprecated Ne plus utiliser, utiliser ->add() avec l'argument "poids"
     *
     * @param string $other
     * @param string $nom
     * @param string $html
     */
    function addAfter($other, $nom, $html, $class = '')
    {
        if ($this->contenu[ $other ]) {
            $poids = $this->contenu[ $other ]['poids'] + 1;
            $this->add($nom, $html, $class, $poids);
        } else {
            $this->add($nom, $html, $class);
        }

        return $this;
    }

    /**
     * Ajoute une boite $nom avant la boite $other
     *
     * @param string $other
     * @param string $nom
     * @param string $html
     */
    function addBefore($other, $nom, $html, $class = '')
    {
        if ($this->contenu[ $other ]) {
            $poids = $this->contenu[ $other ]['poids'] - 1;
            $this->add($nom, $html, $class, $poids);
        } else {
            $this->add($nom, $html, $class);
        }

        return $this;
    }

    /**
     * Ajoute en premier
     *
     * @param string $nom
     * @param string $html
     */
    function addAtTop($nom, $html, $class = '')
    {
        /**
         * On tri nos contenus actuels
         * pour récupérer l'actuel premier
         */
        $this->sort();
        reset($this->contenu);
        $first = val($this->contenu);
        $poids = $first['poids'] - 1;

        $this->add($nom, $html, $class, $poids);
        return $this;
    }

    private function sort()
    {
        usort($this->contenu, "bloc::cmp");
    }

    private static function cmp($a, $b)
    {
        return $a["poids"] > $b["poids"];
    }

    /**
     * Vide tout
     *
     */
    function clean()
    {

        $this->contenu = array();
        return $this;
    }

    /**
     * Ajoute un petit délimiteur
     *
     */
    function addSmallDelim($size = 6)
    {

        global $nbDelim;
        $nbDelim++;
        $this->addAtEnd('delim_' . $nbDelim, '
			<div class="clearer">&nbsp;</div>
			<div class="col_delim">			
			</div>
			<div class="clearer">&nbsp;</div>');
        return $this;
    }

    /**
     * Ajoute un grand délimiteur
     *
     */
    function addBigDelim()
    {
        return $this;
    }

    function remove($nom)
    {

        unset($this->contenu[ $nom ]);
        return $this;
    }

}
