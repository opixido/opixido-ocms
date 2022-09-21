<style type="text/css">

    #newsletterMailchimpBox {
        position:relative;
        width:100%;
        height:100%;
        background:rgba(52,52,52,0.5);
        position:fixed;
        top:0;
        left:0;
        z-index:999;

    }

    #newsletterMailchimpBox ul {
        list-style-type: none;
        margin:0 0 20px 0;
        padding:0;
    }

    #newsletterMailchimpBoxListCategories li {
        position: relative;
    }

    #newsletterMailchimpBoxListCategories li span,
    #newsletterMailchimpBoxListCategories li input {
        position: relative;
        vertical-align: middle;
        margin:0;
    }

    #newsletterMailchimpBox h2 {
        font-size:1.4em;
        line-height: 1.2em;
        margin:0 0 10px 0;
    }    

    #newsletterMailchimpBoxInside {
        background:white;
        border:1px solid #525252;
        padding:10px;
        position:absolute;
        left:50%;
        top:50%;
        width:400px;
        height:auto;
        margin-left:-200px;
        margin-top:-150px;    
    }

    #newsletterMailchimpBoxCloseBtn {
        float:right;
    }

</style>

<div id="newsletterMailchimpBox">
    <div class="bloc2 shadow" id="newsletterMailchimpBoxInside">

        <a id="newsletterMailchimpBoxCloseBtn" href="#" onclick="$('#newsletterMailchimpBox').slideUp()">Fermer X</a>

        <NEWSLETTER_SENT>
            <h2>@@message@@</h2>
        </NEWSLETTER_SENT>
        
        <NB_ABONNES>
            <h2>@@n@@ destinataires ont été choisis pour cet envoi</h2>
            <form method="post" >
                <button class="abutton"><img src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE; ?>/actions/go-previous.png" alt="" /> Annuler </button> 
                <button class="abutton" name="validsendmailchimp" value="1"><img src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE ?>/actions/mail-forward.png" alt="" /> Envoyer </button> 
            </form>
        </NB_ABONNES>

        <LIST_CATEGORIES>
            <div id="newsletterMailchimpBoxListCategories">
                <h2>Choisir les groupes de destination</h2>
                <form  method="post">
                    <fieldset>
                        <input type="hidden" name="grouping_id" value="4345" />
                        <ul>
                            <ITEM_CATEGORIE>
                                <li>
                                    <input type="checkbox" name="mailchimp_groups[]" value="@@name@@" />
                                    <span>@@name@@ (@@subscribers@@ abonnés)</span><br/>
                                </li>
                            </ITEM_CATEGORIE>
                        </ul>
                    </fieldset>
                    <button type="submit" class="abutton"><img src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE; ?>/actions/mail-reply-all.png" alt="" /> Vérifier le nombre de destinataires </button> 
                </form>
            </div>   
        </LIST_CATEGORIES>

    </div>
</div>
