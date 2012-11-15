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

class frontOcms_imageParametrable {

    function __construct($site) {

        $this->site = $site;
    }

}

class imageP {

    /**
     * Identifiant
     *
     * @var string
     */
    private $id;

    /**
     * Genfile
     *
     * @var genfile
     */
    public $gf;

    /**
     * Row 
     *
     * @var array
     */
    public $row;

    public function __construct($id) {

        $this->id = $id;
        $sql = 'SELECT * FROM p_imagep WHERE imagep_label LIKE ' . sql($this->id) . ' ' . sqlVersionOnline();
        $this->row = GetSingle($sql);

        $this->gf = new genFile('p_imagep', 'imagep_img', $this->row);
    }

    /**
     * Retourne le Genfile
     *
     * @return genfile
     */
    public function getGf() {

        return $this->gf;
    }

    /**
     * Retourne le tag complet de l'image
     *
     * @param string $tag Attribut supplémentaire pour la balise
     */
    public function getImgTag($tag = '') {
        return $this->gf->getImgtag($this->getAlt(), $tag);
    }

    /**
     * Retourne juste le ALT
     *
     * @return string
     */
    public function getAlt() {
        return getLgValue('imagep_alt', $this->row);
    }

}

function imgP($str, $tag = "") {
    $f = new imageP($str);
    return $f->getImgTag($tag);
}

?>