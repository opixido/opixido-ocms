<?php
/*
 * @version 1.0
 */ 
 

/**
 * On inclu le fichier qui permettra d'inclure tous les autres
 */
require('./include/include.php');


/**
 * Utile pour les connexions au front office
 *
 */
define('IN_ADMIN',false);


/**
 * L'objet de base se situe dans include/include.php
 * C'est la classe qui permet de tout inclure
 */
$gb_obj = new genBase;

/**
 * Cet objet est global pour permettre des inclusions 
 * de n'importe quel script
 * 
 * @var genBase
 */
$GLOBALS['gb_obj'] = &$gb_obj;


/**
 * On inclu la configuration
 */
$gb_obj->includeConfig();

/**
 * Les classes et fonctions de base
 */
$gb_obj->includeBase();

/**
 * Et toutes les fonctions nécessaires au front Office
 */
$gb_obj->includeGlobal();


/**
 * l'objet Message permet de gérer toutes les erreurs, exceptions, ...
 */
$genMessages = new genMessages();


/**
 * L'objet genSite permet de gérer l'ensemble de la génération du site
 */
$site = new GenSite();

/**
 * Initialisation
 */
$site->init();

/**
 * Post-initialisation
 */
$site->afterInit();
 

/**
 * Génération Complète du code
 */
$site->gen();

/**
 * Si il y a eu des messages d'erreurs
 * on les génére
 */
$genMessages->gen();


/**
 * On se déconnecte de la BDD
 */
$co->disconnect();


