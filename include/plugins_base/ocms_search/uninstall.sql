
DROP TABLE `os_tables`;
DROP TABLE `os_obj`;

-- --------------------------------------------------------

-- 
-- Structure de la table `is_rel`
-- 

DROP  TABLE `os_rel`;

-- --------------------------------------------------------

-- 
-- Structure de la table `is_word`
-- 

DROP TABLE `os_word`;

DROP TABLE `os_recherches`;

DELETE FROM `s_gabarit` WHERE `gabarit_plugin` = 'ocms_search';