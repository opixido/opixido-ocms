

<div id="gs_box" style="text-align:left;position:absolute;left:50%;width:200px;margin-left:-100px;margin-top:50px;" >

    <div class="error" style="text-align:center;padding:3px;font-weight:bold;color:#cc0000;">
        <?= akev($GLOBALS,'errors') ?>
    </div>

    <form style="width:200px;background:#ddd;padding-top:10px;border:1px solid #aaa;border-bottom:1px solid #555;border-right:1px solid #555" action="<?php print($_SERVER['REQUEST_URI']) ?>" method="post" >

        <fieldset style="padding-top:0;border:0px solid;">

            <legend style="vertical-align:middle;border-top:1px solid #eee;border-bottom:1px solid;border-left:1px solid #eee;border-right:1px solid ;background:#cccccc;padding:3px;font-weight:bold;margin-top:10;">


                <img src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FRONT_SIZE . '/devices/computer.png' ?>" class="img_btn" alt="user"/> <?php print(t('identification')); ?></legend>

            <p style="margin-left:20px;">
                <label for="gs_adminuser" style="display:block;" >
                    <img src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FRONT_SIZE . '/apps/system-users.png' ?>" class="img_btn"  alt="user"/>
                    <?= t('username') ?> </label>
                <input 
                    style="border:0px;background:url(./img/fond.bloc2.gif) #eee;
                    ;border-bottom:1px solid;border-right:1px solid;" id="gs_adminuser"  name="gs_adminuser" type="text" value=<?= alt(akev($_REQUEST,'gs_adminuser')); ?> />
            </p>

            <p style="margin-left:20px;">
                <label for="gs_adminpassword" style="display:block;"><img  class="img_btn"  src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FRONT_SIZE . '/mimetypes/application-certificate.png' ?>" alt="user"/> <?= t('password') ?></label>
                <input style="border:0px;background:url(./img/fond.bloc2.gif) #eee;border-bottom:1px solid;border-right:1px solid;" id="gs_adminpassword" name="gs_adminpassword" type="password" value="" />
            </p>
            <p style="margin-left:20px;">

                <label class="abutton" style="float:left">
                    <input  type="image" value="<?= t('connexion') ?>"
                            src="<?= ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FRONT_SIZE . '/apps/preferences-system-session.png' ?>" /> &nbsp; <?= t('connexion') ?></label>

            </p>

            <input name="gs_fromForm" type="hidden" value="1" />
            <input name="gs_askedFor" type="hidden" value="<?php print($_SERVER['REQUEST_URI']); ?>" />


        </fieldset>

    </form>



</div>


<div id="ie6warning" style="text-align:center;position:absolute;left:50%;width:300px;margin-left:-150px;margin-top:350px;border:1px solid;background:#F5F6BE;padding:5px;display:none;" >
    Vous utilisez le navigateur INTERNET EXPLORER Version 6<br/><br/>
    Afin de profiter au mieux des fonctionnalités de l'interface d'administration, nous vous conseillons de passer à Internet Explorer 7 ou Firefox 2

</div>
<!--[if lt IE 7.]>
<script >
gid("ie6warning").style.display = "block";
</script>
<![endif]-->