<?

global $frontAdminTrads,$admin_trads,$_trads,$admin_trads;

if(!is_array($admin_trads))
$admin_trads = array();

$admin_trads['connexion']['fr'] = 'Login ... ';
$admin_trads['username']['fr'] = 'Username';
$admin_trads['password']['fr'] = 'Password';
$admin_trads['save']['fr'] = 'Save';
$admin_trads['cancel']['fr'] = 'Cancel';
$admin_trads['delete']['fr'] = 'Delete';


$admin_trads['search_id']['fr'] = 'Search by ID : ';
$admin_trads['search_txt']['fr'] = 'Search text';
$admin_trads['choose_item']['fr'] = 'Choose an element to modify';
$admin_trads['rechercher']['fr'] = 'Start search';
$admin_trads['confirm_suppr']['fr'] = 'Do you really want to delete this element ?';
$$admin_trads['ajouter']['fr'] = 'Click on this icon to add an element to the list';
$admin_trads['ajouter_elem']['fr'] = 'Add an element';
$admin_trads['menu']['fr'] = 'Menu';
$admin_trads['logout']['fr'] = 'Logout';
$admin_trads['menu1']['fr'] = 'Manage the database';
$admin_trads['menu2']['fr'] = ' ';
$admin_trads['retour']['fr'] = 'Back';
$admin_trads['selectable_items']['fr'] = 'Selectable elements';
$admin_trads['selected_items']['fr'] = 'Selected elements';
$admin_trads['modifier']['fr'] = 'Modify selected element';
$admin_trads['modify_item']['fr'] = 'Modify selected element';
$admin_trads['date_picker']['fr'] = 'Choose the date';
$admin_trads['mal_remplit']['fr'] = 'You haven\'t filled these sections properly';
$admin_trads['etes_vous_sur_action']['fr'] = 'Do you really want to do this action ?';


$admin_trads['help_tablerel']['fr'] = '<b>Multicriterion shared field</b><br/>You can choose several elements.<br/><br/>Select an element in left column, then click on the right arrow to pass that element in the list of the selected elements';

$admin_trads['help_rte']['fr'] = '<b>WYZIWYG Field</b><br/>You can layout a text with this tool. To acces to this tool, click on the edition icon in the top-left corner';


$admin_trads['help_upload_table']['fr'] = '<b>Champ de mise en ligne de tableau CSV</b><br/>Vous avez la possibilité de parcourir le disque de votre ordinateur, et d\'envoyer un fichier Excel (format CSV) pour remplir ce champ par un tableau de données';

$admin_trads['upload_table']['fr'] = 'Mettre en ligne un tableau (CSV)';
$admin_trads['champ_vide']['fr'] = 'This field is actually empty.';

$admin_trads['help_texte']['fr'] = '<b>Text Field</b><br/>Click on the field, then fill it or copy/past your text.';




$admin_trads['help_relinv_table']['fr'] = '<b>Linked elements</b><br/><br/>Click on an element and then click on the edition icon to modify itt.';

//$admin_trads['help_relinv_table']['fr'] = '<b>Eléments liés</b><br/>Vous avez ci-contre la liste des éléments liés.<br/><br/> Sélectionnez un élément puis cliquez sur l\'icône d\'édition pour le modifier ';


$admin_trads['help_relation']['fr'] = '<b>Simple selection</b><br/>You can select an item in this list';

$admin_trads['help_file']['fr'] = '<b>Put file online</b><br/>To put a file online click on the button <i>BROWSE</i>, then select the file you want to upload.<br/>Click on the button <i>Put online</i><br/><br/>Do the same to updated. The previous file will be deleted.<br/><br/>To just delete it click on <i>DELETE</i>';



$admin_trads['aucun_element']['fr'] = 'No item';
$admin_trads['select_rub_below']['fr'] = 'Select following page';


$admin_trads['rubrique_valider_ok']['fr'] = 'This rubrique has been validated. Your modifications are now visible online';

$admin_trads['rubrique_demande_valider_ok']['fr'] = 'Your validation request has been sent to : ';

$admin_trads['rubrique_devalider_ok']['fr'] = 'This rubrique is now hidden.<br/>The internauts have no access to it.';



$admin_trads['src_edit']['fr'] = $admin_trads['src_editer']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/accessories-text-editor.png';
$admin_trads['src_view']['fr'] =$admin_trads['src_voir']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/edit-find.png';
$admin_trads['src_new']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/document-new.png';
$admin_trads['src_preview']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/system-search.png';
$admin_trads['src_preview']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/mimetypes/image-x-generic.png';

$admin_trads['src_droite']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-last.png';
$admin_trads['src_gauche']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-first.png';
$admin_trads['src_help']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/apps/help-browser.png';
$admin_trads['src_logout']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/system-log-out.png';
$admin_trads['src_NewsletterPreview']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/internet-news-reader.png';
$admin_trads['src_NewsletterFakeSend']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/evolution.png';
$admin_trads['src_NewsletterSend']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/internet-mail.png';



$admin_trads['src_ask_for_validation']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/system-software-update.png';
$admin_trads['src_cancel']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/edit-undo.png';
$admin_trads['src_del']['fr'] = $admin_trads['src_delete']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/process-stop.png';
$admin_trads['src_showObject']['fr'] = $admin_trads['src_validateVersion']['fr'] = $admin_trads['src_validate']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/internet-web-browser.png';
$admin_trads['src_hideObject']['fr'] =$admin_trads['src_hideVersion']['fr'] =$admin_trads['src_unvalidate']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/status/image-missing.png';
$admin_trads['src_upload']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/mimetypes/x-directory-remote-workgroup.png';
$admin_trads['src_up']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-up.png';
$admin_trads['src_down']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-down.png';
$admin_trads['src_date']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-down.png';

$admin_trads['src_PublicationValidate']['fr'] = $admin_trads['src_SiteValidate']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/internet-web-browser.png';
$admin_trads['src_PublicationHide']['fr'] = $admin_trads['src_SiteHide']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/status/image-missing.png';

$admin_trads['src_back']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-previous.png';
$admin_trads['src_first']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-previous.png';

$admin_trads['src_save']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/document-save.png';
$admin_trads['src_saveas']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/document-saveas.png';


$admin_trads['src_close']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/media-record.png';

$admin_trads['src_message_info']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/status/dialog-information.png';
$admin_trads['src_message_error']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/status/dialog-warning.png';
$admin_trads['src_undo']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-undo.png';
$admin_trads['src_go']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-find.png';


$admin_trads['src_InstallPlugin']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/install.png';
$admin_trads['src_UninstallPlugin']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/uninstall.png';

$admin_trads['search']['fr'] = 'Rechercher';
$admin_trads['preview']['fr'] = 'Pré-visualiser';

$admin_trads['field_img_rubrique_titre_fr']['fr'] =
$admin_trads['field_img_rubrique_titre_en']['fr'] =
$admin_trads['field_img_paragraphe_titre_fr']['fr'] =
$admin_trads['field_img_paragraphe_titre_en']['fr'] =
$admin_trads['field_img_site_nom']['fr'] =
$admin_trads['field_img_site_sigle']['fr'] =
$admin_trads['field_img_site_presentation_fr']['fr'] =
$admin_trads['field_img_site_presentation_en']['fr'] =
$admin_trads['field_img_site_langue_fr']['fr'] =
$admin_trads['field_img_site_langue_en']['fr'] =
$admin_trads['field_img_r_site_pays']['fr'] =
$admin_trads['field_img_r_site_domaine']['fr'] =
$admin_trads['field_img_site_commentaire']['fr'] =
$admin_trads['field_img_site_date_maj']['fr'] =
$admin_trads['field_img_site_dumois']['fr'] =
$admin_trads['field_img_publi_titre_fr']['fr'] =
$admin_trads['field_img_publi_titre_en']['fr'] =
$admin_trads['field_img_publi_presentation_fr']['fr'] =
$admin_trads['field_img_publi_presentation_en']['fr'] =
$admin_trads['field_img_publi_resume_fr']['fr'] =
$admin_trads['field_img_publi_resume_en']['fr'] =
$admin_trads['field_img_publi_sommaire_fr']['fr'] =
$admin_trads['field_img_publi_sommaire_en']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-copy.png';

$admin_trads['field_img_r_rubrique_lexique']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/mimetypes/x-office-address-book.png';




$admin_trads['field_img_fk_liens_id']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/status/mail-attachment.png';

$admin_trads['field_img_fk_paragraphe_id']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/format-justify-left.png';


$admin_trads['field_img_rubrique_keywords_fr']['fr'] =
$admin_trads['field_img_rubrique_keywords_en']['fr'] =
$admin_trads['field_img_rubrique_desc_fr']['fr'] =
$admin_trads['field_img_rubrique_desc_en']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-paste.png';

$admin_trads['field_img_fk_para_type_id']['fr'] =
$admin_trads['field_img_fk_gabarit_id']['fr'] =
$admin_trads['field_img_rubrique_gabarit_param']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/categories/preferences-desktop.png';
$admin_trads['field_img_rubrique_pos_box']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/categories/preferences-desktop.png';

$admin_trads['field_img_fk_publication_id']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/apps/internet-news-reader.png';

$admin_trads['field_img_fk_site_id']['fr'] =
$admin_trads['field_img_site_url']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/apps/internet-web-browser.png';

$admin_trads['field_img_paragraphe_img_1_en']['fr'] =
$admin_trads['field_img_paragraphe_img_1_fr']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/mimetypes/image-x-generic.png';




$admin_trads['field_help_rubrique_titre_fr']['fr'] = $admin_trads['field_help_rubrique_titre_en']['fr'] = '<b>Page title</b><br/>It appears in the navigation menu,<br/> the browser title,<br/> and the results of searchs';


$admin_trads['field_help_fk_paragraphe_id']['fr'] =  '<b>Page paragraphs</b><br/>To modify a paragraph, click on the edition icon of the paragraph.';


$admin_trads['field_help_fk_thematique_id']['fr'] =  'Choose the theme...';


$admin_trads['paragraphe_titre_fr']['fr'] = 'French title';
$admin_trads['paragraphe_titre_en']['fr'] = 'Title';

$admin_trads['paragraphe_contenu_fr']['fr'] = 'Contenu en Français';
$admin_trads['paragraphe_contenu_en']['fr'] = 'Content';


$admin_trads['paragraphe_img_1_fr']['fr'] = 'Image d\'accompagnement Française';
$admin_trads['paragraphe_img_1_en']['fr'] = 'Image d\'accompagnement Anglaise';


$admin_trads['paragraphe_img_1_alt_fr']['fr'] = 'Description Française de l\'image ci-dessus';
$admin_trads['paragraphe_img_1_alt_en']['fr'] = 'Picture description';




$admin_trads['t_site.fk_site_id']['fr'] = 'Ce site fait parti du site général suivant :';
$admin_trads['r_site_pays']['fr'] = 'Pays d\'appartenance ou d\'étude';
$admin_trads['r_site_domaine']['fr'] = 'Domaines d\'étude couverts par ce site';

$admin_trads['titre_fr']['fr'] = 'Titre en Français';
$admin_trads['titre_en']['fr'] = 'Title';

$admin_trads['presentation_fr']['fr'] = 'Présentation en Français';
$admin_trads['presentation_en']['fr'] = 'Presentation';



$admin_trads['datedeb']['fr'] = 'Date Start';
$admin_trads['detefin']['fr'] = 'Date End';


$admin_trads['datepubli']['fr'] = 'Date of publication';
$admin_trads['datearchi']['fr'] = 'Date of filing';


$admin_trads['rendezvous_home']['fr'] = 'Visible on the home page ?';
$admin_trads['rendezvous_ined']['fr'] = 'Est-ce un rendez-vous Ined ?';


/********************* FORMULAIRE IMPORT TABLEAU */
$admin_trads['field_img_csv_upload']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/status/mail-attachment.png';

$admin_trads['field_img_csv_summary']['fr'] =
$admin_trads['field_img_csv_caption']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-copy.png';

$admin_trads['field_img_csv_delimiter']['fr'] =
$admin_trads['field_img_csv_toph']['fr'] =
$admin_trads['field_img_csv_lefth']['fr'] =
ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/categories/preferences-desktop.png';

$admin_trads['field_img_csv_gen']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/mimetypes/x-office-spreadsheet.png';



$admin_trads['m_building_p_0']['fr'] = 'Keywords';
$admin_trads['m_building_p_1']['fr'] = 'Sustainability';
$admin_trads['m_building_p_2']['fr'] = 'Images / 1';
$admin_trads['m_building_p_3']['fr'] = 'Images / 2';

$admin_trads['building_name']['fr'] = 'Project name';
$admin_trads['fk_country_id']['fr'] = 'Country';
$admin_trads['fk_buildsetup_id']['fr'] = 'Setting-up type';
$admin_trads['fk_buildstructure_id']['fr'] = 'Structure';
$admin_trads['fk_buildheating_id']['fr'] = 'Heating';

$admin_trads['building_matcompo']['fr'] = 'Mateials and components';
$admin_trads['building_energy']['fr'] = 'Energy Choices';
$admin_trads['building_water']['fr'] = 'Water system';
$admin_trads['building_site']['fr'] = 'Site and infrastructure';



$admin_trads['building_layout1']['fr'] = 'Layout plan 1';
$admin_trads['building_layout2']['fr'] = 'Layout plan 2';

$admin_trads['building_longitudinal']['fr'] = 'Longitudinal section';

$admin_trads['building_cross']['fr'] = 'Cross section';

$admin_trads['building_air']['fr'] = 'Air scheme';
$admin_trads['building_heating']['fr'] = 'Heating scheme';

$admin_trads['max_size']['fr'] = 'Taille max. ';


$admin_trads['tester_le_lien']['fr'] = 'Test the link in a popup';
$admin_trads['choisir_rubrique']['fr'] = 'Choose a page on the web site';
$admin_trads['choisir_rubrique_ci_dessous']['fr'] = 'Click on a rubrique';

?>
