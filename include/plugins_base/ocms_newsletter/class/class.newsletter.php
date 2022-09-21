<?php

// Inclusion des classes
$GLOBALS['gb_obj']->includeFile('class.spectacle.php', 'plugins/p_global/classes');
$GLOBALS['gb_obj']->includeFile('class.genre.php', 'plugins/p_global/classes');
$GLOBALS['gb_obj']->includeFile('class.lieu.php', 'plugins/p_global/classes');

class newsletter extends row {

    var $table = 'ocms_newsletter_newsletter';

    /**
     * Constructeur
     * @param type $roworid
     */
    public function __construct($roworid) {

        parent::__construct($this->table, $roworid);
    }

    /**
     * Génération du code HTML de la newsletter
     * @return type
     */
    public function gen() {

        // Création du template
        $tpl = new genTemplate(true);
        $tpl->loadTemplate('tpl.newsletter', 'plugins/ocms_newsletter/tpl');
        $tpl->defineBlocks('SPECTACLE_GAUCHE', 'SPECTACLE_DROITE');

        // Titre de la newsletter
        $tpl->newsletter_titre = $this->newsletter_titre;
        $tpl->newsletter_baseline = t('ocms_newsletter_baseline');
        $tpl->url_base = getUrlFromId(getRubFromGabarit('genAccueil'));
        $tpl->newsletter_date = $this->newsletter_date;
        $tpl->txt_programmation = t('ocms_newsletter_txt_programmation');
        $tpl->infos_pied_de_page = nl2br(t('ocms_newsletter_infos_pied_de_page'));
        $tpl->txt_desinscription = t('ocms_newsletter_lien_desinscription');
        $tpl->url_desinscription = getServerUrl().getUrlFromId(getRubFromGabarit('genAbonnementNewsletter'), '', array('action' => 'desabonnement'));
        $tpl->texte_alt_facebook = t('ocms_newsletter_texte_alt_facebook');
        $tpl->texte_alt_twitter = t('ocms_newsletter_texte_alt_twitter');
        $tpl->texte_alt_google = t('ocms_newsletter_texte_alt_google');
        $tpl->url_partage = getUrlFromId(getRubFromGabarit('genApercuNewsletter'), LG, array('newsletter_id' => $this->id));
        $tpl->txt_partager = t('ocms_newsletter_txt_partager');
        $tpl->txt_infos_pratiques = t('ocms_newsletter_txt_infos_pratiques');
        $tpl->texte_twitter = t('ocms_newsletter_texte_twitter');

        // Lien vers la visualisation de la newsletter dans le navigateur
        $urlArchive = getServerUrl() . getUrlFromId(getRubFromGabarit('genApercuNewsletter'), LG, array('newsletter_id' => $this->id));
        $tpl->lien_archive = tf('ocms_newsletter_lien_archive', array('URL' => $urlArchive));

        // Liste des spectacles associés
        if (count($this->ocms_newsletter_r_newsletter_spectacle)) {

            // Compteur pour gauche / droite
            $cpt = 1;
            
            foreach ($this->ocms_newsletter_r_newsletter_spectacle as $row) {

                // Template colonne de gauche ou droite
                $block = $cpt % 2 == 0 ? 'SPECTACLE_DROITE' : 'SPECTACLE_GAUCHE';

                // Objet spectacle
                $objSpectacle = new spectacle($row);
                
                $spectacle = new row('t_spectacle', $objSpectacle->id);
                $objGenre = new genre($spectacle->fk_genre_id);
                $objLieu = new lieu($spectacle->fk_lieu_id);
                
                // Item template
                $tplItem = $tpl->addBlock($block);
                $tplItem->titre = $objSpectacle->getTitre();
                $tplItem->date = $this->getIntervalleDates($spectacle);
                //$tplItem->texte = substrWithNoCutWord($spectacle->spectacle_accroche, 0, 400);
                $tplItem->texte = $spectacle->spectacle_accroche;
                $tplItem->url = $objSpectacle->getUrl();
                $tplItem->img = getServerUrl().$objSpectacle->getAbstractSrc(279, 101);
                $tplItem->txt_lire_suite = t('ocms_newsletter_txt_lire_suite');
                $tplItem->lieu = $objLieu->getNom();
                $tplItem->auteurs = $spectacle->spectacle_auteurs;
                $tplItem->infos = strip_tags($objSpectacle->rubrique_texte_droite);
                $tplItem->color = $objGenre->genre_color;
                
                //debug($spectacle->spectacle_accroche);

                $cpt++;
            }
        }
        
        $n_content = $tpl->gen();
        
        /***************************
         *  CHECK FOR ABSOLUTE URLs
         **************************/
        
        $urlsAlreadyReplaced = array();
                
        $regex_pattern = "/href=\"([^\"]*)\"/";
        
        //find all href values
        preg_match_all($regex_pattern,$n_content,$matches);
        $matches = $matches[1];
        
        //browse hrefs        
        foreach($matches as $value){
          
            $posOfHttp = strpos($value, 'http://');
            $posOfHttps = strpos($value, 'https://');
      
            //relative url
            if($posOfHttp === false && $posOfHttps === false && !in_array($value, $urlsAlreadyReplaced)){
              //transform to absolute
              $absoluteUrl = 'href="'.getServerUrl().$value.'"';
              $n_content = str_replace('href="'.$value.'"', $absoluteUrl, $n_content);
              $urlsAlreadyReplaced[] = $value;

            }
        }
        
        // Génération du template
        return $n_content;
    }
    
    /**
     * Génère l'url de désabonnement
     */
    function genUrlDesabo() {

        $rubID = getRubFromGabarit('genAbonnementNewsletter');
        $params = array('form' => 'desabonnement');

        return getServerUrl() . str_replace(getServerUrl(), '', getUrlFromId($rubID, LG, $params));
    }

    /**
     * Retourne l'intervalle de dates (date de début > date de fin)
     * @return type
     */
    public function getIntervalleDates($objSpectacle) {
        
        $firstDate = $objSpectacle->spectacle_date_deb;
        $lastDate = $objSpectacle->spectacle_date_fin;

        return $this->genDateInterval($firstDate, $lastDate);
    }

    /**
     * Retourne un intervalle de dates sous la forme 01 janvier > 23 décembre 2015
     * @param date $date1
     * @param date $date2
     * @return type
     */
    function genDateInterval($date1, $date2) {
        
        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        if ($date1 == $date2) {

            return strftime('%d %B %Y', strtotime($date1));
        } else {

            // Même année
            if (substr($date1, 0, 4) == substr($date2, 0, 4)) {

                // Même mois
                if (substr($date1, 5, 2) == substr($date2, 5, 2)) {
                    $return = strftime('%d', strtotime($date1));
                } else {
                    $return = strftime('%d %B', strtotime($date1));
                }
            } else {
                $return = strftime('%d %B %Y', strtotime($date1));
            }

            $return .= ' > ' . strftime('%d %B %Y', strtotime($date2));
        }

        if (mb_detect_encoding($return) != 'UTF-8') {

            $return = utf8_encode($return);
        }
        setlocale(LC_TIME, 0);
        return $return;
    }

}
