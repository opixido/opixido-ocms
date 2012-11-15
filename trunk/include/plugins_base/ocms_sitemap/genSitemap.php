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

class genSiteMap extends row {

    private $html;

    /**
     * gensite
     *
     * @var gensite
     */
    private $site;
    public $showHome = true;

    public function __construct($site, $params) {

        $this->site = &$site;
        //debug($this->arbo);

        $this->params = SplitParams($params);



        $this->cache = new genCache('sitemap' . akev($this->params, 'siteroot'), getParam('date_update_arbo'));

        //$this->site->g_rubrique->plugins['navigation']->setVisible(false);
    }

    public function afterInit() {
        $this->site->plugins['o_blocs']->gauche->hide();
        $this->site->g_headers->addCss('sitemap.css');
        $this->site->g_headers->addCssText('#sitemap a:hover {color:' . COULEUR_2 . '}');
        //$this->site->g_headers->addSCript('jquery.masonry.min.js');
    }

    public function gen() {


        if ($this->site->isCurrent404) {

            trySql("INSERT INTO s_404 VALUES ('','" . mes('http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]) . "','" . mes($_SERVER['HTTP_REFERER']) . "')");

            //$this->html .=  t('info_404');
        }

        $this->count = 1;

        if (/* $this->cache->cacheExists() */0) {

            return $this->html . $this->cache->getCache();
        } else {
            $this->html .= ( '<div id="sitemap">');

            $menus = !empty($this->params['siteroot']) ? $this->site->getMenus($this->params['siteroot']) : $this->site->getMenus();

            //$menus = array(0=>array('rubrique_id'=>472),1=>array('rubrique_id'=>474),2=>array('rubrique_id'=>626));


            foreach ($menus as $row) {

                $this->html .= '<ul class="sitemap">';
                $this->arbo = $this->site->g_url->recursRub($row['rubrique_id']);

                //$this->arbo = $this->site->g_url->recursRub(17);
                $this->recursRub($this->arbo);
                $this->html .= '</ul>';
            }
            $this->html .= ( '			
			</div>
			');

            $this->cache->saveCache($this->html);
            return $this->html; //. '<script type="text/javascript">$("#sitemap").masonry({ columnWidth: 200 , itemSelector: "li.level1"});</script>';
        }
    }

    private function recursRub($array, $level = '1', $rootRub = '1') {

        if (!is_array($array) || !count($array)) {
            return '';
        }
        $k = 0;
        $tot = count($array);
        $color = '';
        foreach ($array as $page) {
            $k++;

            $this->html .= '<li ' . $color . ' class="level' . $level . ' nb' . $rootRub . ' ' . ($k == 1 ? 'premier' : '') . ' ' . ($k == $tot ? 'dernier' : '') . '">';
            $color = '';
            $this->html .= '<div class="level' . $level . '"><a  href="' . $page['url'] . '">' . $page['titre'] . '</a></div>';

            if (!empty($page['sub']) && $level != 3) {
                $this->html .= '<ul>';
                $this->recursRub($page['sub'], $level + 1, $rootRub);
                $this->html .= '</ul>';
            }

            $this->html .= '</li>' . "\n";


            if ($level == 1) {

                if ($this->count % 3 === 0) {

                    //$this->html .= '</ul><div class="clearer">&nbsp;</div><ul class="plan_site">' ;
                }

                $rootRub++;
                $this->count++;
            }
        }
    }

    public static function ocms_getPicto() {

        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/media-eject.png';
    }

}

