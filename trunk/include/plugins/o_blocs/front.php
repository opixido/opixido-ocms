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

class o_blocsFront extends ocmsPlugin
{

    public $blocs = array();
    public $afterInits = array();

    function __construct($site)
    {

        $this->site = $site;

        $res = GetAll('SELECT * FROM s_bloc');

        foreach ($res as $row) {
            if ($row['bloc_classe'] && class_exists($row['bloc_classe'])) {
                $this->blocs[ $row['bloc_nom'] ] = new $row['bloc_classe']($site);
            } else {
                $this->blocs[ $row['bloc_nom'] ] = new bloc($site);
            }
            $this->{$row['bloc_nom']} = $this->blocs[ $row['bloc_nom'] ];
            $this->blocs[ $row['bloc_nom'] ]->nom = $row['bloc_nom'];
            $this->blocs[ $row['bloc_nom'] ]->visible = $row['bloc_visible'];
            if ($row['bloc_afterinit']) {
                $this->afterInits[ $row['bloc_nom'] ] = $row['bloc_afterinit'];
            }
        }
    }

    function afterInit()
    {

        foreach ($this->afterInits as $k => $v) {
            if (@eval($v) === false) {
                devbug(t('dev_error_in_evalued_code_in_bloc') . ' ' . $k);
                devbug($v);
            }
        }
    }

    function beforeGen()
    {
        foreach ($this->blocs as $v) {
            if (method_exists($v, 'beforeGen')) {
                $v->beforeGen();
            }
        }
    }

    function genBloc($nom)
    {

        if ($this->blocs[ $nom ]) {
            return $this->blocs[ $nom ]->genBloc();
        }
        return false;
    }

}

