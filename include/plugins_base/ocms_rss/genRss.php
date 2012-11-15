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

class genRss extends ocmsGen {

    function afterInit() {

        $this->res = rssFront::getCurrentPageFlux();

        $this->formats = array('RSS0.91', 'RSS1.0', 'RSS2.0', 'MBOX', 'OPML', 'ATOM', 'ATOM10', 'ATOM0.3', 'HTML', 'JS');
        $this->formats = array('RSS1.0', 'RSS2.0', 'ATOM1.0');


        if ($_REQUEST['flux']) {

            $this->genFlux($_REQUEST['flux']);
        }

        rssFront::genRssHeaders($this->res);
    }

    function genFlux($id) {

        $row = GetSingle('SELECT * FROM plug_rss WHERE rss_version = ' . sql($id) . ' AND fk_rubrique_id = ' . sql($this->site->getCurID()));


        $GLOBALS['gb_obj']->includeFile('feedcreator.class.php', 'plugins/ocms_rss');
        $rss = new UniversalFeedCreator();
        $rss->useCached(); // use cached version if age<1 hour
        $rss->title = getLgValue('rss_titre', $row);
        $rss->description = getLgValue('rss_desc', $row);
        $rss->encoding = 'UTF-8';
        //optional
        $rss->descriptionTruncSize = $row['rss_truncate'];
        $rss->descriptionHtmlSyndicated = isTrue($row['rss_html']);

        $rss->link = $row['rss_url'];
        $rss->syndicationURL = getServerUrl() . "/" . $_SERVER["PHP_SELF"];

        $image = new FeedImage();
        $image->title = $rss->title;
        $imgf = new genFile('plug_rss', 'rss_vignette', $row);
        $image->url = getServerUrl() . $imgf->getWebUrl();
        $image->link = $rss->link;
        $image->description = $rss->description;

        $rss->image = $image;

        $sql = $row['rss_sql'];

        if (!$sql && $row['rss_table']) {
            $sql = 'SELECT * FROM ' . $row['rss_table'] . ' WHERE 1 ' . sqlOnlyOnline($row['rss_table']) . ' LIMIT 0, 20';
        }

        $res = GetAll($sql);

        foreach ($res as $Rrow) {

            $item = new FeedItem();
            $item->encoding = 'UTF-8';
            $item->title = getLgValue($row['rss_champ_titre'], $Rrow);

            $item->description = getLgValue($row['rss_champ_desc'], $Rrow);


            $url = $item->link = getLgValue($row['rss_champ_uri'], $Rrow);

            if (strpos($row['rss_champ_uri'], 'php:') !== false) {
                $code = substr($row['rss_champ_uri'], 4);
                $item->link = $url = eval($code);
            }

            $url = $item->link = strpos($url, 'http://') !== false ? $url : getServerUrl() . $url;

            /*
              //optional (enclosure)
              $item->enclosure = new EnclosureItem();
              $item->enclosure->url='http://http://www.dailyphp.net/media/voice.mp3';
              $item->enclosure->length="950230";
              $item->enclosure->type='audio/x-mpeg'
             */

            if (false && $row['rss_champ_image']) {

                $gf = new genFile($row['rss_table'], $row['rss_champ_image'], $Rrow);
                $i = $gf->getWebUrl();
                if ($i) {

                    //	$item->image = getServerUrl().$i;
                }
            }


            $item->date = strtotime(choose($Rrow[$row['rss_champ_date']], $Rrow['ocms_date_crea']));
            $item->source = "http://" . $_SERVER['HTTP_HOST'];


            $rss->addItem($item);
        }

        // valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
        // MBOX, OPML, ATOM, ATOM10, ATOM0.3, HTML, JS
        //to generate "on-the-fly"
        $rss->outputFeed($_REQUEST['format']);

        die();
    }

    function gen() {


        if ($_REQUEST['flux']) {
            
        } else {
            return $this->genListe();
        }
    }

    function genListe() {


        $tpl = new genTemplate(true);
        $tpl->loadTemplate('liste.flux', 'plugins/ocms_rss');
        $tpl->defineBlocks('BLOC');

        foreach ($this->res as $row) {

            $t = $tpl->addBlock('BLOC');
            $t->set('titre', getLgValue('rss_titre', $row));
            $t->set('desc', getLgValue('rss_desc', $row));

            foreach ($this->formats as $v) {
                $f = $t->addBlock('FORMAT');
                $f->set('nom', $v);
                $f->set('format', strtolower(substr($v, 0, strpos($v, '.'))));
                $f->set('title', alt(getLgValue('rss_titre', $row) . ' ' . $v));
                $f->set('url', rssFront::getUrl($row, $v));
            }
        }

        return $tpl->gen();
    }

    function ocms_getPicto() {

        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/emblems/rss.png';
    }

}

?>