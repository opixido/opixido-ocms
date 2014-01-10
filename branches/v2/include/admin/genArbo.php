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

class genArbo {

    private $idCurRub;
    private $rubs;
    public $table;

    public function __construct($idRub) {

        $this->idCurRub = $idRub;
        $this->sa = new smallAdmin($this);
        $this->rubs = $this->sa->getRubs();
        $this->rubs = akev($this->rubs, $this->idCurRub);
        $this->table = 's_rubrique';

        //debug($idRub);
        // on construit l'arbo existante à partir de la rubrique sélectionnée
        //debug(genAdmin::recurserub(114,3,0));
    }

    function gen() {

        $html = '';


        // on enregistre les données
        if (!empty($_REQUEST['soumis'])) {

            // on supprime toute l'arbo existante à partir de la racine
            // puis on crée la nouvelle arbo
            if ($_REQUEST['n'][0]['fils']) {

                foreach ($_REQUEST['n'][0]['fils'] as $fils) {
                    $this->createRubs($fils, $_REQUEST['idRubParent']);
                }

                $html = 'L\'<a href="?curTable=s_rubrique" style="text-decoration:underline">arborescence</a> a été créée.
                  		';
            } else {
                $html = 'Aucune rubrique n\'a été créée.';
            }
            $html .= '<br/><a href="?curTable=s_rubrique" style="text-decoration:underline">&laquo;Retour</a>';
        }
        // on affiche l'arbre
        else {

            $html .= '
            <script src="js/ajaxForm.js" type="text/javascript"></script>
            <div id="arborescence">
                <form action="index.php?arbo=1" method="post">
                    <ul id="racine">'
                    . $this->doArbo($this->idCurRub, '0') . '
                    </ul>
                    
                    <input type="hidden" name="soumis" value="ok" />
                    <input type="hidden" name="idRubParent" value="' . $this->idCurRub . '" />
                    <input type="submit" value="Enregistrer" />
                </form>
           </div>';
        }

        p($html);
    }

    // construit l'arbre des rubriques à partir de la rubrique courante
    function doArbo($idNoeud, $code) {

        global $_Gconfig;

        // on va chercher le titre de la rubrique du noeud courant dans les différentes langues
        $titres = $this->getRubTitles($idNoeud);


        // cnstruction de l'attribut name
        $name = 'n[0]';

        for ($i = 1; $i <= (strlen($code) - 1) / 2; $i++) {
            $n = substr($code, 2 * $i, 1);
            $name .= '[fils][' . $n . ']';
        }

        $tabID = $name . '[id]';

        $name .= '[value]';

        $html = '<li id="li_' . $code . '">';

        foreach ($_Gconfig['LANGUAGES'] as $lg) {

            $nameLg = $name . '[' . $lg . ']';
            $titre = $titres['rubrique_titre_' . $lg];
            $html .= '<img src="/img/flags/' . $lg . '.gif" alt="' . $lg . '" />';
            $html .= '<input name="' . $name . '" type="text" value="' . $titre . '" disabled="true" />';
            $html .= '<input name="' . $tabID . '" type="hidden" value="' . $idNoeud . '" />';
        }

        $html .= '<a onclick="addChild(this)" class="addChild" href="#"><img src="./pictos/list-add.png"></a>';

        /*
         *   on n'autorise pas la suppression des rubriques déjà existantes 
         *      
          if($idNoeud!=$this->idCurRub)
          $html .= '<a onclick="delChild(this)" class="delChild" href="#"><img src="/pictos/process-stop.png" /></a>';
         */

        // si la rubrique a des sous-rubriques

        if (count($this->getIdSubrubs($idNoeud))) {

            $html .= '<ul id="ul_' . $code . '">';
            $i = 0;

            foreach ($this->getIdSubrubs($idNoeud) as $idSubrub) {

                $html .= $this->doArbo($idSubrub, $code . '_' . $i);
                $i++;
            }

            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }

    // récupère le titre d'une rubrique
    function getRubTitles($idRub) {

        global $_Gconfig;

        $sql = 'SELECT ';

        foreach ($_Gconfig['LANGUAGES'] as $lg) {
            $tabLG[] = 'rubrique_titre_' . $lg;
        }

        $sql .= implode(', ', $tabLG) . ' FROM s_rubrique WHERE rubrique_id=' . $idRub;

        $res = GetSingle($sql);

        return $res;
    }

    // récupère le tableau des ids des sous-rubriques d'une rubrique
    function getIdSubrubs($idRub) {

        $sql = 'SELECT rubrique_id FROM s_rubrique WHERE fk_rubrique_id=(SELECT fk_rubrique_version_id FROM s_rubrique WHERE rubrique_id=' . $idRub . ') AND rubrique_etat=\'redaction\'';
        $res = getAll($sql);
        $tab = array();
        foreach ($res as $r) {
            $tab[] = $r['rubrique_id'];
        }

        return $tab;
    }

    // fonction qui crée récursivement des rubriques à partir d'un tableau de type n[0][fils][1][value]...
    function createRubs($rub, $idParentRedac) {

        // ! si c'est une rubrique existante il faut récupérer son id et lancer directement la fonction sur ses fils !
        if (isset($rub['value'])) {

            global $gs_obj, $_Gconfig;

            $sql = 'SELECT fk_rubrique_version_id 
			              FROM s_rubrique 
			              WHERE rubrique_id=' . $idParentRedac;

            $res = getSingle($sql);
            $idParentOnline = $res['fk_rubrique_version_id'];

            // on va chercher le max du champ ordre
            $sql = 'SELECT MAX(rubrique_ordre) 
			              FROM s_rubrique 
			              WHERE fk_rubrique_id=' . $idParentOnline;

            $res = getSingle($sql);
            $ordre = $res['MAX(rubrique_ordre)'] ? $res['MAX(rubrique_ordre)'] + 1 : 0;

            $fields = '';
            $values = '';

            foreach ($_Gconfig['LANGUAGES'] as $lg) {
                $fields .= 'rubrique_titre_' . $lg . ', rubrique_url_' . $lg . ', ';
                $values .= sql($rub['value'][$lg]) . ", '" . niceName($rub['value'][$lg]) . "', ";
            }


            $sql = "INSERT INTO s_rubrique ( " . $fields . " fk_rubrique_id,  fk_rubrique_version_id,  rubrique_etat,  fk_creator_id, rubrique_ordre) VALUES (" . $values . $idParentOnline . ",NULL,'en_ligne'," . $gs_obj->adminid . "," . $ordre . ");";
            doSql($sql);

            $idVersion = InsertId();

            $sql = "INSERT INTO s_rubrique ( " . $fields . " fk_rubrique_id,  fk_rubrique_version_id,  rubrique_etat,  fk_creator_id, rubrique_ordre) VALUES (" . $values . $idParentOnline . ",$idVersion,'redaction'," . $gs_obj->adminid . "," . $ordre . ");";
            doSql($sql);

            //debug($sql);
        }

        // puis si cette dernière a des enfants, on lance la fonction récursivement dessus
        if (!empty($rub['fils'])) {

            if (isset($rub[value])) {
                // on récupère l'id de la rubrique précédemment créée
                $sql = 'SELECT MAX(rubrique_id) FROM s_rubrique WHERE rubrique_etat=\'redaction\';';
                $res = getSingle($sql);
                $idRedaction = $res['MAX(rubrique_id)'];
            } else {
                $idRedaction = $rub[id];
            }


            foreach ($rub['fils'] as $subrub) {

                $this->createRubs($subrub, $idRedaction);
            }
        }
    }

}

?>