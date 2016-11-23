
ALTER TABLE  `s_paragraphe` ADD  `paragraphe_embed_[LG]` TEXT NOT NULL ;


INSERT INTO `s_para_type` (`para_type_id`, `para_type_titre`, `para_type_template`, `para_type_tpl_file`, `para_type_template_popup`, `para_type_vignette`, `para_type_use_img`, `para_type_use_file`, `para_type_use_table`, `para_type_use_txt`, `para_type_use_link`, `para_type_gabarit`, `para_type_plugin`, `para_type_champs`) VALUES
('', 'Code embed', '<div class="paragraphe_type_embed">\r\n<?php if($this->titre) { ?>\r\n<h2>@@titre@@</h2>\r\n<?php } ?>\r\n<div class="paragraphe_type_embed-container">\r\n<?=$this->obj->paragraphe_embed?>\r\n</div>\r\n</div>', '', '', '', 0, 0, 0, 0, 0, '', '', 'paragraphe_embed');


INSERT INTO `s_para_type` (`para_type_id`, `para_type_titre`, `para_type_template`, `para_type_tpl_file`, `para_type_template_popup`, `para_type_vignette`, `para_type_use_img`, `para_type_use_file`, `para_type_use_table`, `para_type_use_txt`, `para_type_use_link`, `para_type_gabarit`, `para_type_plugin`, `para_type_champs`) VALUES (NULL, 'Diaporama accueil', '', 'plugins/o_paragraphs/tpl/diaporama.php', '', '', '0', '0', '0', '1', '0', '', '', 'IMAGES');



CREATE TABLE IF NOT EXISTS `s_paragraphe_image` (
  `paragraphe_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paragraphe_image_img_fr` varchar(255) NOT NULL,
  `paragraphe_image_titre_fr` varchar(255) NOT NULL,
  `paragraphe_image_desc_fr` text NOT NULL,
  `paragraphe_image_ordre` int(11) NOT NULL,
  `fk_paragraphe_id` int(11) NOT NULL,
  PRIMARY KEY (`paragraphe_image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
