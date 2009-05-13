<?php



class ocms_searchFront {


	/**
	 * Gensite
	 *
	 * @var Gensite
	 */
	public $site;

	function __construct($site) {

		$this->site = $site;

	}


	function genRechercheForm() {

		return '
			<form id="recherche_small" action="'.(getUrlFromId(getRubFromGabarit('genOcmsSearch'))).'" method="get">
				<div>
					<label for="recherche_input">Rechercher</label>			

					<input type="text" name="q" class="text" id="recherche_input" value="' . geta($_GET,'q') . '" />

					<input type="submit" class="submit" value="Ok" />

				</div>
			</form>

		';

	}


	function gen() {



	}


}


?>