CREATE TABLE IF NOT EXISTS `p_news` (
  `news_id` int(10) unsigned NOT NULL auto_increment,
  `news_titre_fr` varchar(128) NOT NULL,
  `news_desc_fr` varchar(255) NOT NULL,
  `news_detail_fr` text NOT NULL,
  `news_img_fr` varchar(255) NOT NULL,
  `news_media_fr` varchar(255) NOT NULL,
  `news_date` datetime NOT NULL,
  `date_online` datetime NOT NULL,
  `date_offline` datetime NOT NULL,
  PRIMARY KEY  (`news_id`),
  KEY `news_date` (`news_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;