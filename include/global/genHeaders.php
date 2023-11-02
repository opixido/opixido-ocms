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

class genHeaders
{

    private $title;
    private $meta_keywords;
    private $meta_description;
    private $html_headers;
    public $titleSep = ' - ';
    public $firstBody = '';
    public $getAllCss = false;
    public $metas = array();

    /**
     * @var genSite
     */
    public $site;

    /**
     * Contenu des CSS en ligne
     * @var string
     */
    public $cssTexts = '';

    /**
     * Liste des fichiers CSS
     * @var array
     */
    public $cssFiles = array();

    /**
     * Liste des fichiers JS
     * @var array
     */
    public $jsFiles = array();

    /**
     * Durée du cache
     * @var int
     */
    public $fCacheTime = 86400;

    /**
     * Dossier de cache
     * @var string
     */
    public $fCacheFolder = 'c';

    /**
     * Dossier à ajouter devant les JS et CSS
     * @var string
     */
    public $addFolder = '';

    /**
     * Handles HTML Headers
     *
     * @param unknown_type $site
     * @return genHeaders
     */
    public function __construct($site)
    {
        $this->site = $site;
    }

    public function genJs($type = false)
    {
        /**
         * Compression et Cache des JS
         */
        if (count($this->jsFiles)) {
            $x = '';
            if ($type) {
                if (!empty($this->jsFiles[$type])) {
                    $jsF = $this->getJsPath($this->jsFiles[$type]);
                    $x .= ('<script src="' . $jsF . '"></script>' . "\n");
                }
            } else {
                foreach ($this->jsFiles as $k => $v) {
                    $jsF = $this->getJsPath($v);
                    /**
                     * Un seul js mis en cache
                     */
                    $x .= ('<script src="' . $jsF . '"></script>' . "\n");
                }
            }

            return $x;
        }
    }

    public function genCss()
    {
        $x = '';

        global $_Gconfig;

        if (!$_Gconfig['groupCssFiles']) {
            foreach ($this->cssFiles as $group => $medias) {
                foreach ($medias as $media => $fichiers) {
                    foreach ($fichiers as $fichier) {
                        $x .= "\n" . '<link rel="stylesheet" media="' . $media . '" href="' . path_concat(BU, '/', $fichier) . '"  />' . "\n";
                    }
                }
            }


        } else

            /**
             * Compression et Cache des CSS
             */
            if (count($this->cssFiles)) {
                foreach ($this->cssFiles as $group => $medias) {
                    $x .= $this->genCssByGroup($group);
                }
            }
        /**
         * Compression des CSS en ligne
         */
        if (strlen($this->cssTexts)) {
            $x .= ('<style type="text/css"> ' . compressCSS($this->cssTexts) . ' </style>' . "\n");
        }
        return $x;
    }

    public function genCssByGroup($group)
    {
        $x = '';
        $medias = $this->cssFiles[$group];
        if (!$medias) {
            return '';
        }
        foreach ($medias as $media => $fichiers) {
            $pathCss = $this->getCssPath($fichiers);
            /**
             * Un seul CSS mis en cache
             */
            $x .= "\n" . '<link rel="stylesheet" media="' . $media . '" href="' . $pathCss . '" data-files="' . $group . ':' . implode(':', $fichiers) . '" />' . "\n";
        }
        return $x;
    }

    public function genHtmlHeaders($more = '')
    {

        $tpl = new genTemplate();
        $tpl->loadTemplate('headers.html');

        $this->html_headers .= $more;

        $tpl->set('lg', $this->site->getLg());

        $tpl->set('title', altify($this->getTitle()));
        $tpl->set('keywords', altify($this->getMetaKeywords()));
        $tpl->set('desc', altify($this->getMetaDescription()));
        $tpl->set('headers', $this->getHtmlHeaders());
        $tpl->set('first_body', $this->firstBody);


        return $tpl->gen();
    }

    /**
     * Generates full headers
     *
     * @return unknown
     */
    public function gen()
    {

        $x = '';
        $x .= $this->genCss();
        $x .= $this->genJs();

        return $this->genHtmlHeaders($x);
    }

    public function getJsPath($fichiers)
    {

        global $_Gconfig;

        /**
         * Chemin du cache des js
         */
        $jsCacheName = '' . md5(implode('_', $fichiers)) . '.js';
        $jsCache = new genCache($jsCacheName, (time() - ($this->fCacheTime)), $_SERVER['DOCUMENT_ROOT'] . BU . '/' . $this->fCacheFolder . '/');

        $js = '';

        if (!$jsCache->cacheExists()) {
            /**
             * Si le cache est expiré ou inexistant
             * On récupère le contenu de toutes les js
             */
            foreach ($fichiers as $v) {

                $jj = @file_get_contents($_SERVER['DOCUMENT_ROOT'] . BU . SEP . $v);

                if (!$jj) {
                    devbug('Missing JS : ' . $_SERVER['DOCUMENT_ROOT'] . BU . SEP . $v);
                }

                /**
                 * On ajoute une ligne pour séparer chaque fichier
                 */
                $js .= "\n//" . $v . "\n";

                /**
                 * Si déjà compressé on ne touche pas
                 */
                if (substr($jj, 0, 10) == '/*packed*/' || strstr($v, '.min.') !== false || !$_Gconfig['compressJsFiles']) {
                    $js .= $jj . "\n";
                } else {
                    /**
                     * Sinon on compresse
                     */
                    $j = new ECMAScriptPacker();
                    $js .= $j->pack($jj) . "\n";
                }
            }

            $jsCache->saveCache($js);
        }

        return $_Gconfig['CDN'] . BU . '/' . $this->fCacheFolder . '/' . $jsCacheName;
    }

    public function getCssPath($fichiers)
    {

        global $_Gconfig;

        $css = '';
        if (!is_array($fichiers)) {
            $fichiers = array($fichiers);
        }


        /**
         * Chemin du cache des CSS
         */
        if (count($fichiers) == 1) {
            /**
             * Si un seul fichier CSS on ne renomme pas
             */
            $cssCacheName = nicename($fichiers[0]) . '.css';
        } else {
            /**
             * Sinon md5
             */
            $cssCacheName = '' . md5(implode('_', $fichiers)) . '.css';
        }

        $cssCache = new genCache($cssCacheName, (time() - ($this->fCacheTime)), $_SERVER['DOCUMENT_ROOT'] . BU . '/' . $this->fCacheFolder . '/');

        if (!$cssCache->cacheExists()) {
            /**
             * Si le cache est expiré ou inexistant
             * On récupère le contenu de toutes les CSS
             */

            foreach ($fichiers as $v) {
                $path = path_concat(realpath($_SERVER['DOCUMENT_ROOT']), BU, $v);
                if ($csT = file_get_contents($path)) {
                    $css .= $this->fixCssPath($csT, $path) . "\n";
                } else {
                    devbug('Cant load CSS : ' . $path);
                }
            }
            /**
             * On les compresse
             */
            if ($_Gconfig['compressCssFiles']) {
                $css = compressCss($css);
            }


            /**
             * Et on sauvegarde
             */
            $cssCache->saveCache($css);
//$a = new tsmartsprite($cssCache->cache_path);
//$cssCacheName = str_replace('.css','-sprite.css',$cssCacheName);
        }

        return $_Gconfig['CDN'] . BU . '/' . $this->fCacheFolder . '/' . $cssCacheName;
    }

    public function fixCssPath($content, $cssPath)
    {
        $cssPath = str_replace("\\", "/", dirname($cssPath));
        $cssPath = str_replace(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), '', $cssPath) . '/';

        $search = '#url\((?!\s*[\'"]?(?:https?:)?//)\s*([\'"])?#';
        $replace = "url($1{$cssPath}";
        $css = preg_replace($search, $replace, $content);

        return $css;
    }

    /**
     * Adds a link to a javascript FILE in the JS folder
     *
     * @param string $name
     * @param bool $addBase if FALSE you have to set the FULL path to the file in $name
     * @param string $tag additional parameters to add in the script tag
     */
    public function addScript($name, $addBase = true, $group = 'page', $atTop = false)
    {

        if ($addBase) {
            $name = 'js/' . $name;
        }
        if ($atTop) {
            array_unshift($this->jsFiles[$group], $name);
        } else {
            $this->jsFiles[$group][] = $name;
        }
    }

    /**
     * Adds text in first place to the body
     *
     * @param string $str
     */
    public function addFirstBody($str)
    {
        $this->firstBody .= $str;
    }

    /**
     * Adds a link to a CSS FILE in the CSS folder
     *
     * @param string $name
     */
    public function addCss($name, $group = 'page', $media = 'screen')
    {

        if (strpos($name, '/') === false) {
            $name = 'css/' . $name;
//$this->addHtmlHeaders('<style type="text/css" media="screen"> @import "'.$name.'"; </style>');
        }

        $this->cssFiles[$group][$media][] = $name;
    }

    /**
     * Adds full CSS code in a tag
     *
     * @param string $value
     */
    public function addCssText($value)
    {
        $this->cssTexts .= $value . "\n\n";
    }

    /**
     * Fully replace the "title" of the page
     *
     * @param string $str
     */
    public function setTitle($str)
    {

        if ($str) {
            $str = ucfirst($str);
            $this->title = $str . $this->titleSep . t('base_title');
        } else {
            $this->title = t('base_title');
        }
    }

    /**
     * Adds a new "level" to the current title
     *
     * @param string $str
     */
    public function addTitle($str)
    {
        if ($str && trim($str)) {
            $this->title = $str . $this->titleSep . $this->title;
        }
    }

    /**
     * Returns title
     *
     * @return unknown
     */
    public function getTitle()
    {

        return $this->title;
    }

    public function setMeta($name, $value)
    {
        $this->metas[$name] = $value;
    }

    public function setMetaKeywords($str)
    {
        $this->meta_keywords = $str;
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function setMetaDescription($str)
    {
        $this->meta_description = $str;
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function addHtmlHeaders($str)
    {
        $this->html_headers .= "\n" . $str . "\n";
    }

    public function getHtmlHeaders()
    {
        foreach ($this->metas as $k => $v) {
            $this->html_headers .= '<meta name="' . $k . '" content=' . alt($v) . ' />';
        }
        return $this->html_headers;
    }

}
