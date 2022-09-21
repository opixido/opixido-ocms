ALTER TABLE `t_genre` ADD `genre_color` VARCHAR(6) NOT NULL ;
UPDATE `t_genre` SET `genre_color` = 'f7931e' WHERE `t_genre`.`genre_id` = 1; 
UPDATE `t_genre` SET `genre_color` = '2775e3' WHERE `t_genre`.`genre_id` = 2;
UPDATE `t_genre` SET `genre_color` = '00a99d' WHERE `t_genre`.`genre_id` = 3;
UPDATE `t_genre` SET `genre_color` = 'f485ad' WHERE `t_genre`.`genre_id` = 4;
UPDATE `t_genre` SET `genre_color` = '7e69c5' WHERE `t_genre`.`genre_id` = 5;
UPDATE `t_genre` SET `genre_color` = 'ed4747' WHERE `t_genre`.`genre_id` = 6;
