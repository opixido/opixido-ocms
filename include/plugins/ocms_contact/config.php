<?php

/**
 * Variables de conf
 */
global $tabForms, $uploadFields, $_Gconfig, $relinv, $orderFields, $admin_trads, $gs_roles, $relations, $neededFields;

/**
 * Fichier qu'on n'accepte pas.
 */
$_Gconfig['notAllowedFileExtension'] = array('exe', 'com', 'bat', 'reg');

/**
 * Pour la table plug_contact, on définit que les champs de "titre" sont contact_titre et contact_email
 * plug_contact est la table de gestion des contacts
 */
$tabForms['plug_contact']['titre'] = array('contact_titre', 'contact_email');
/**
 * Et que son formulaire de gestion dans l'admin n'aura qu'une page, à l'emplacement ci dessous
 */
$tabForms['plug_contact']['pages'] = array('../plugins/ocms_contact/form.contact_email.php');

/**
 * Le table plug_contact_field n'a qu'un champ de "titre" : contact_field_nom
 * plug_contact_field est la table permettant de gérer les champs de formulaire à afficher 
 * 
 */
$tabForms['plug_contact_field']['titre'] = array('contact_field_nom');
$tabForms['plug_contact_field']['pages'] = array('../plugins/ocms_contact/form.field.php');

/**
 * Dans la table plug_contact_field on a un champ "d'ordre" ou "tri"
 * nommé "contact_field_ordre" et qui définit l'ordre grace à la clef externe fk_rubrique_id
 */
$orderFields['plug_contact_field'] = array('contact_field_ordre', 'fk_rubrique_id');
$orderFields['plug_contact'] = array('contact_ordre', 'fk_rubrique_id');

/**
 * Dans le formulaire de la table "s_rubrique" on rajoute 
 * un onglet "contact" qui contiendra ../plugins/formulaireContact/form.contact.php
 */
$tabForms['s_rubrique']['pages']['contact'][] = ('../plugins/ocms_contact/form.contact.php');

/**
 * Dans la table "s_rubrique" si on demande l'affichage du champ "fk_contact_id"
 * on affichera la liste des éléments de la table "plug_contact" qui pointe vers 
 * moi même via la clef externe "fk_rubrique_id"
 */
$relinv['s_rubrique']['fk_contact_id'] = array('plug_contact', 'fk_rubrique_id');

/**
 * Dans la table "s_rubrique" si on demande l'affichage du champ "fk_contact_field_id"
 * on affichera la liste des éléments de la table "plug_contact_field" qui pointe vers 
 * moi même via la clef externe "fk_rubrique_id"
 */
$relinv['s_rubrique']['fk_contact_field_id'] = array('plug_contact_field', 'fk_rubrique_id');

/**
 * Quand on duplique une rubrique, on duplique également les éléments liés des tables suivantes 
 */
$_Gconfig['duplicateWithRubrique'][] = 'plug_contact';
$_Gconfig['duplicateWithRubrique'][] = 'plug_contact_field';


$relations['s_paragraphe']['fk_contact_id'] = "plug_contact";

//$tabForms['s_paragraphe']['pages'][0] = array($tabForms['s_paragraphe']['pages'][0],'../plugins/ocms_contact/form.paracontact.php');
//$tabForms['s_paragraphe']['pages']['contact'] = array('../plugins/ocms_contact/form.contact.php');

$_Gconfig['specialListing']['plug_contact']['s_paragraphe'] = 'listContacts';

$neededFields[] = 'contact_field_name';

function listContacts() {

    $sql = 'SELECT C.*, R.rubrique_titre_fr AS titre1 , R2.rubrique_titre_fr AS titre2
                    FROM plug_contact AS C , s_rubrique AS R, s_rubrique AS R2
                    WHERE C.fk_rubrique_id = R.rubrique_id
                    AND R.fk_rubrique_id = R2.rubrique_id
                    AND R.rubrique_etat= "redaction"
                    ORDER BY R2.rubrique_id , R.rubrique_id ASC, contact_ordre ASC
                    ';

    $res = getAll($sql);

    foreach ($res as $k => $row) {
        $res[$k]['contact_titre_fr'] = $row['titre2'] . ' => ' . $row['titre1'] . ' => ' . $row['contact_titre_fr'];
    }

    return $res;
}
