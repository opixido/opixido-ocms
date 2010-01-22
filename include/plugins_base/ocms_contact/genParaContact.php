<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of genParaContact
 *
 * @author Celio@Opixido
 */
class genParaContact {

    function __construct($para,$template) {
        
        if($para['fk_contact_id']) {

            $sql = 'SELECT Con.*, Ron.rubrique_id FROM plug_contact AS Coff , plug_contact AS Con,
                                s_rubrique AS Roff , s_rubrique AS Ron
                        WHERE
                                Coff.contact_id = '.sql($para['fk_contact_id']).'
                                AND Coff.fk_rubrique_id = Roff.rubrique_id
                                AND Roff.fk_rubrique_version_id = Ron.rubrique_id
                                AND Con.fk_rubrique_id = Ron.rubrique_id
                                AND Coff.contact_email = Con.contact_email

                    ';

            $res = GetAll($sql);

            if(count($res)) {

                 $template->contact = '<a href="'.getUrlFromId($res[0]['rubrique_id'],LG,array('c_qui'=>$res[0]['contact_id'])).'" class="contact_link">'.t('nous_contacter').'</a>';

            } else {
                
                $template->contact = ' ';
                
            }

        }

    }

 }


