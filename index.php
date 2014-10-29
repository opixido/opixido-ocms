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
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#
    
include('agressiveCache.php');

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
