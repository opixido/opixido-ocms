<?php





/**
 * ADOConnection
 */
global $co;

/*if($_bdd_type != '**'.'bdd_type'.'**') {*/
if(defined('ADODB_DIR')) {
	
	/**
	 * On inclu ADODB
	 */
	require_once(ADODB_DIR.'adodb.inc.php');
	
	/**
	 * NewADOConnection
	 */
	$co = ADONewConnection($_bdd_type);
	

	
	/**
	 * Connexion à la base de donnée
	 */
	$connec = @$co->Connect($_bdd_host, $_bdd_user, $_bdd_pwd, $_bdd_bdd);
	
	/**
	 * Si on a pas la connexion à la BDD
	 */
	if(!$connec ) {
		echo 'No MySQL Connection'."\n\n<br/>";
		echo $co->ErrorMsg();
		//die('<h1>Regler en premier lieu les informations de connexion MySQL /include/config/config.server.php</h1>');
		$co = false;
		die();
		return;
	}
	
	
	
	
	/**
	 * Pour que ADODB ne retourne que les enregistrements avec les clefs
	 * et pas les index
	 * Limite l'utilisation m�moire
	 */
	$co->SetFetchMode(ADODB_FETCH_ASSOC); 

	
	/**
	 * On positione la BDD en UTF8
	 * Et toutes les connexions idem
	
	$co->Execute('SET collation-connection = utf8');
	$co->Execute('SET character-set-client = utf8');
	$co->Execute('SET character-set-connection = utf8');

	$co->Execute('SET character-set-results = utf8');
	 */
	$co->Execute('SET NAMES  utf8');

	//$co->Execute('SET SESSION sort_buffer_size = 1000000');
	
} else  if(!IN_ADMIN) {
	echo 'Please configure first <a href="./admin/">CONFIGURATION</a>';
	die();
}



?>