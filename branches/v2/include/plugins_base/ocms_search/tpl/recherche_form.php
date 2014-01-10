<form id="recherche_small" action="<?= (getUrlFromId(getRubFromGabarit('genOcmsSearch'))) ?>" method="get">
    <div>
	<label for="recherche_input"><img src="<?= BU ?>/img/loupe.png" alt=<?= alt(t('rechercher_alt')) ?> /></label>			

	<input type="text" name="q" class="text" id="recherche_input" value=<?= alt(akev($_GET, 'q') )?> />

	<input type="submit" class="submit cacher" value="Rechercher" title="" />

    </div>
</form>

<script type="text/javascript">
    var defRechValue = <?= alt(t('rechercher_input')) ?>;
    var rechInput = $("#recherche_input");
    if(rechInput.val() == "") {
	rechInput.val(defRechValue);
    }
    rechInput.focus(function() {
	if(rechInput.val() == defRechValue) {
	    rechInput.val("");
	}
    });
    rechInput.blur(function() {
	if(rechInput.val() == "") {
	    rechInput.val(defRechValue);
	}
    });
    
    $("#recherche_input").after('<div id="autocomplete"></div>')
    .attr('autocomplete','off')
    .keyup(function(event) {
	if (event.keyCode == 40) {
	    /**
	     * Fleche bas
	     */
	    var a = $('#autocomplete a.selected');
	    if(a.length > 0) {
		a.removeClass('selected').next().addClass('selected');
	    } else {
		$('#autocomplete a:first-child').addClass('selected');
	    }
	    return;
	}
	else if (event.keyCode == 38) {
	    /**
	     * Fleche haut
	     */
	    $('#autocomplete a.selected').removeClass('selected').prev().addClass('selected');
	    return;
	} else if(event.keyCode == 13) {
	    var a = $('#autocomplete a.selected');
	    if(a.length > 0) {
		window.location = a.attr('href');
		return false;
	    }
	}
			     
	/**
	 * Si moins de deux caractères dans le champ on annule l'autocomplete
	 */
	if($(this).val().length < 2) {
	    $('#autocomplete').slideUp('fast');
	    return;
	}
	/**
	 * On lance la requête et on affiche
	 */
	$('#autocomplete').load('<?= BU ?>/?ajax_q=1&q='+$(this).val(),function(){$(this).slideDown("fast")});
    })
    .blur(function() {
	/**
	 * On masque l'autocomplete
	 */
	$('#autocomplete').slideUp("fast");
    });

    $('#autocomplete').hide();
    
</script>