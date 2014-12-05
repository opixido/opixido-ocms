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

/**
 * Generates the Paragraphes of the page
 *
 */
class genParagraphes
{

    var $paragraphes;
    var $site;
    var $rubrique;
    var $id = 'paragraphes';

    function __construct($site, $paragraphes)
    {
        $this->site = $site;
        $this->rubrique = $this->site->g_rubrique;
        $this->paragraphes = $paragraphes;
    }

    function getHtmlParagraphes()
    {

        $curpara = 0;
        $nbpara = count($this->paragraphes);
        foreach ($this->paragraphes as $nbparaK => $para) {

            $curpara++;

            $html = '';

            /**
             *  Creation du template
             * */
            $tpl = new genTemplate(true);

            if (!empty($para['para_type_tpl_file'])) {
                $file = str_replace('.php', '', basename($para['para_type_tpl_file']));
                $folder = dirname($para['para_type_tpl_file']);
                $tpl->loadTemplate($file, $folder);
            } else
                if (ake($_REQUEST, 'ocms_mode') && $para[ 'para_type_template_' . $_REQUEST['ocms_mode'] ]) {
                    $tpl->setTemplate($para[ 'para_type_template_' . $_REQUEST['ocms_mode'] ]);
                } else {
                    $tpl->setTemplate('' . $para['para_type_template']);
                }


            if ($para['para_type_gabarit']) {

                if ($para['para_type_plugin']) {
                    $GLOBALS['gb_obj']->includeFile($para['para_type_gabarit'] . '.php', 'plugins/' . $para['para_type_plugin']);
                } else {
                    $GLOBALS['gb_obj']->includeFile($para['para_type_gabarit'] . '.php', 'bdd');
                }

                $paraObj = new $para['para_type_gabarit']($para, $tpl);
            }


            /**
             * Contenu
             */
            $conte = GetLgValue('paragraphe_contenu', $para);


            $tpl->setVar('titre', GetLgValue('paragraphe_titre', $para));
            $tpl->setVar('texte', ($conte));

            /**
             *  Images associees
             * */
            $img = new GenFile('s_paragraphe', 'paragraphe_img_1', $para['paragraphe_id'], $para, true, true);


            $img2 = new GenFile('s_paragraphe', 'paragraphe_img_2', $para['paragraphe_id'], $para, true, true);


            $tpl->setImg(1, $img->getWebUrl(), GetLgValue('paragraphe_img_1_alt', $para, false));
            $tpl->setImg(2, $img2->getWebUrl(), GetLgValue('paragraphe_img_2_alt', $para, false));

            $tpl->setGFImg('img1', 's_paragraphe', 'paragraphe_img_1', $para);

            $tpl->setVar('alt_1', GetLgValue('paragraphe_img_1_alt', $para));
            $tpl->setVar('alt_2', GetLgValue('paragraphe_img_2_alt', $para));
            $tpl->setVar('legend_1', GetLgValue('paragraphe_img_1_legend', $para));
            $tpl->setVar('copyright_1', $para['paragraphe_img_1_copyright']);
            $tpl->setVar('legend_2', GetLgValue('paragraphe_img_2_legend', $para));
            $tpl->setVar('copyright_2', $para['paragraphe_img_2_copyright']);

            /**
             * Fichier joint
             */
            $fichier = new GenFile('s_paragraphe', 'paragraphe_file_1', $para['paragraphe_id'], $para, true, true);


            $tpl->setVar('file1', $fichier);
            $tpl->setVar('file1_url', $fichier->getWebUrl());
            $tpl->setVar('file1_size', $fichier->getNiceSize());
            $tpl->setVar('file1_type', mb_strtoupper($fichier->getExtension()));
            $tpl->setVar('file1_name', $fichier->getRealName());
            $tpl->setVar('file1_legend', choose(getLgValue('paragraphe_file_1_legend', $para), $fichier->getRealName()));
            $tpl->setVar('link1', getLgValue('paragraphe_link_1', $para));

            if (strlen(trim(GetLgValue('paragraphe_contenu_csv', $para)))) {
                $params = $_GET;

                $params['para'] = $para['paragraphe_id'];
                $params['export'] = 'tableau.csv';
                $tpl->setVar('dwl_csv', '<a class="exporter_tableau btn" href="' . $GLOBALS['site']->g_url->getUrlWithParams($params) . '" ><span class="icon-lien-rond"></span> ' . t('exporter_tableau') . '</a>');
            }

            if (akev($_REQUEST, 'pdf')) {
                $tpl->setVar('img', $img->getWebUrl());
            }

            $html .= '<a class="cacher" name="para_' . nicename(GetLgValue('paragraphe_titre', $para)) . '"></a>';

            $tpl->row = $para;

            $html .= '<div id="para_nb_' . $curpara . '" class="paragraphe_simple">';

            $html .= $tpl->gen();

            $html .= '</div>';


            $this->paragraphes[ $nbparaK ]['html'] = $html;
            $this->paragraphes[ $nbparaK ]['titre'] = getLgValue('paragraphe_titre', $para);
        }

        return $this->paragraphes;
    }

    function gen()
    {

        $c = new genCache('para_' . $this->id . '_' . $this->site->getCurId(), ($this->rubrique->date_publi));

        if (!$c->cacheExists()) {

            $this->getHtmlParagraphes();
            $html = '';
            foreach ($this->paragraphes as $para) {
                if ($_REQUEST['para']) {
                    if ($para['paragraphe_id'] == $_REQUEST['para']) {
                        $html .= $para['html'];
                    }
                } else {
                    $html .= $para['html'];
                }
            }
            $h = '<div id="' . $this->id . '" class="paragraphe">' . $html . '</div>';
            $c->saveCache($h);
            return $h;
        }

        return $c->getCache();
    }

}
