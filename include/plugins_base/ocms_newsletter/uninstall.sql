DROP TABLE `ocms_newsletter_newsletter`;
DROP TABLE `ocms_newsletter_r_newsletter_spectacle`;
DROP TABLE `ocms_newsletter_user`;
DELETE FROM s_gabarit WHERE gabarit_classe = "genNewsletterMailchimpForm";
DELETE FROM s_gabarit WHERE gabarit_classe = "genApercuNewsletter";
DELETE FROM `s_trad` WHERE trad_id LIKE "%ocms_newsletter%";