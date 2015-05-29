<?php

$form->genlg('rubrique_titre_page');

if (!$this->editMode) {
    ?>
    <script type="text/javascript">
        lgfieldcur["rubrique_titre_page"] = "";
        $(document).ready(function () {
            lgfieldcur["rubrique_titre_page"] = "";

            $('#genform_div_rubrique_titre').append(
                $('#genform_div_rubrique_titre_page'));

            $('#genform_div_rubrique_titre_page').wrap('<div id="gf_rubrique_titre_page" class="genform_champ"/>');

            $('#gf_rubrique_titre_page').prepend(
                '<a id="surclasser_titre_page"  class="button"><?=ta('surclasser_titre_page')?></a>').css('margin-top', '-5px').css('border-top', 0);


            $('#genform_div_rubrique_titre_page').hide();

            $('#surclasser_titre_page').click(function () {
                $(this).remove();
                showLgField("rubrique_titre_page", "<?=LG?>");
                $('#genform_div_rubrique_titre_page').show();
                showLgField("rubrique_titre_page", "<?=LG?>");

            });
        });
    </script>
<?php } 