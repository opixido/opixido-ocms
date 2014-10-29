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


/* :::: FONCTIONS :::: */

function openFile($name, $method) {
    $hdl = fopen($name, $method);
    return $hdl;
}

/* :::::::::::::::::::::::::::::: */

class Csv {
    /* PROPRIETES DE LA CLASSE CSV */

    var $csvFile;
    var $csvSummary;
    var $csvCaption;
    var $hasTopHeader;
    var $hasLeftHeader;
    var $csvDelimiter;

    /* CONSTRUCTEUR */
    /* function Csv($csvFile){
      $this->csvFile = $csvFile;
      $this->csvSummary = '';
      $this->csvCaption = '';
      $this->hasTopHeader = 0;
      $this->hadLeftHeader = 0;
      $this->csvDelimiter = ';';
      } */

    /* CONSTRUCTEUR DE LA CLASSE CSV */

    function Csv($csvFile, $csvSummary = '', $csvCaption = '', $csvDelimiter = '', $hasTopHeader = '', $hasLeftHeader = '') {
        $this->csvFile = $csvFile;
        $this->csvSummary = $csvSummary;
        $this->csvCaption = $csvCaption;
        $this->hasTopHeader = $hasTopHeader;
        $this->hasLeftHeader = $hasLeftHeader;
        $this->csvDelimiter = $csvDelimiter;
    }

    /* METHODE APPELANTE */

    function gen() {
        $handle = $this->openCsvFile();
        $CsvTab = $this->buildHTMLTab($handle, 8);
        //p( $this->showCsvContent($handle) );
        //die();
        return $CsvTab;
    }

    function trimMillier($value) {
        if (is_numeric($value)) {
            $value = str_replace('.', ',', $value);
            $value = explode(',', $value);
            $chaine = '';
            $chaine .= $value[0];
            $chaine = strrev($chaine);
            $cpt = 0;
            $temp = '';

            //die(strlen($chaine) . ' :::::: ' .$chaine);

            $i = 0;
            while ($i < strlen($chaine)) {
                if ($cpt == 3) {
                    $temp .= ' ';
                    $cpt = 0;
                } else {
                    $temp .= $chaine[$i];
                    $cpt++;
                    $i++;
                }
            }
            $temp = strrev($temp);
            if (strlen($value[1]))
                $temp .= '.' . $value[1];

            return $temp;
        }else {
            return $value;
        }
    }

    /* METHODE QUI CONSTRUIT UN TABLEAU HTML A PARTIR D'UN HANDLE SUR UN FICHIER CSV */

    function buildHTMLTab($hdl, $limit_col) {
        //NOTE pour moi-même : j'ai changer id en class ... 
        $HTMLtab = '<table class="HTMLtab" summary="' . $this->csvSummary . '" border="1"><caption>' . $this->csvCaption . '</caption>';

        $HTMLtab .= $this->getTopHeader($hdl, $limit_col);
        $i = 0;
        while (($csvContent = fgetcsv($hdl, null, $this->csvDelimiter))) {
            if (count($csvContent) > 1) {
                $HTMLtab .= '<tr>';
                $j = 0;
                foreach ($csvContent as $key => $value) {
                    if ($j == $limit_col) {
                        break;
                    }
                    if ($this->hasLeftHeader != 0 && $key < $this->hasLeftHeader) {
                        $HTMLtab .= '<th>' . utf8_encode($this->trimMillier($value)) . '</th>';
                    } else {
                        $HTMLtab .= '<td>' . utf8_encode($this->trimMillier($value)) . '</td>';
                    }
                    $j++;
                }
                $HTMLtab .= '</tr>';
                $i++;
            }
        }

        $HTMLtab .= '</table>';
        //debug( $HTMLtab );

        return $HTMLtab;
    }

    /* METHODE QUI PERMET DE METTRE LES TH */

    function getTopHeader($hdl, $limit_col) {
        $i = 0;
        $HTML = '';
        while ($i < $this->hasTopHeader) {
            $csvContent = fgetcsv($hdl, null, $this->csvDelimiter);
            $HTML .= '<tr>';
            $j = 0;
            foreach ($csvContent as $key => $value) {
                if ($j == $limit_col) {
                    break;
                }
                $HTML .= '<th>' . utf8_encode($this->trimMillier($value)) . '</th>';
                $j++;
            }
            $HTML .= '</tr>';
            $i++;
        }
        return $HTML;
    }

    /* METHODE QUI RENVOIE UN HANDLE SUR UN FICHIER CSV */

    function openCsvFile() {
        $ficName = $this->csvFile;
        if (is_file($ficName)) {
            $hdl = openFile($ficName, "r");
            return $hdl;
        }
    }

    /* METHODE QUI AFFICHE LE CONTENU D'UN FICHIER CSV AVEC LE HANDLE PASSE EN PARAM */

    function showCsvContent($hdl) {
        $content = '';
        while ($csvContent = fgetcsv($hdl, null, $this->csvDelimiter)) {
            foreach ($csvContent as $key => $val) {
                $content .= utf8_encode($val) . ';';
            }
            $content = substr($content, 0, -1);
            $content .= "\n";
        }

        return $content;
    }

}
