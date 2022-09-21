<?php

/**
 * Prévisualisation de la newsletter
 */
class genActionPreviewNewsletter extends ocms_action {

    var $table = 'ocms_newsletter_newsletter';
    
    function checkCondition() {
        return true;
    }

    /**
     * Customisation du bouton d'action : lien vers la newsletter (cf admin.php)
     */
    function getForm() {

        $h = '<a class="btn" href="?curTable='.$this->table.'&curId=' . $this->id . '&doPreviewNews=1" onclick="return doblank(this)">
<img src="' . ADMIN_PICTOS_FOLDER . '/22x22/mimetypes/text-html.png"/>
Prévisualiser
</a>';
        echo $h;
    }

    function getSmallForm() {
        return '<a href="?curTable='.$this->table.'&curId=' . $this->id . '&doPreviewNews=1" onclick="return doblank(this)">
<img alt="" src="' . ADMIN_PICTOS_FOLDER . '/22x22/mimetypes/text-html.png"/>
</a>';
    }

    function doIt() {
        
    }

}
