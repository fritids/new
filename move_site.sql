SET @MOVEDOMAIN = 'localhost';
SET @MOVEFOLDER = '/ecp';

UPDATE  `wp_blogs` SET  `domain` =  @MOVEDOMAIN, `path` =  CONCAT(@MOVEFOLDER, '/') WHERE  `wp_blogs`.`blog_id` =1;
UPDATE  `wp_blogs` SET  `domain` =  @MOVEDOMAIN, `path` =  CONCAT(@MOVEFOLDER, '/portal/') WHERE  `wp_blogs`.`blog_id` =2;
UPDATE  `wp_blogs` SET  `domain` =  @MOVEDOMAIN, `path` =  CONCAT(@MOVEFOLDER, '/enroll/') WHERE  `wp_blogs`.`blog_id` =3;
UPDATE  `wp_blogs` SET  `domain` =  @MOVEDOMAIN, `path` =  CONCAT(@MOVEFOLDER, '/news/') WHERE  `wp_blogs`.`blog_id` =4;


UPDATE  `wp_options` SET  `option_value` =  CONCAT('http://', @MOVEDOMAIN, @MOVEFOLDER,'/') WHERE  `wp_options`.`option_name` ='siteurl';
UPDATE  `wp_options` SET  `option_value` =  CONCAT('http://', @MOVEDOMAIN, @MOVEFOLDER,'/') WHERE  `wp_options`.`option_name` ='home';

UPDATE  `wp_4_options` SET  `option_value` =  CONCAT('http://', @MOVEDOMAIN, @MOVEFOLDER,'/news/') WHERE  `wp_4_options`.`option_name` ='siteurl';
UPDATE  `wp_4_options` SET  `option_value` =  CONCAT('http://', @MOVEDOMAIN, @MOVEFOLDER,'/news/') WHERE  `wp_4_options`.`option_name` ='home';

UPDATE  `wp_3_options` SET  `option_value` =  CONCAT('http://', @MOVEDOMAIN, @MOVEFOLDER,'/enroll/') WHERE  `wp_3_options`.`option_name` ='siteurl';
UPDATE  `wp_3_options` SET  `option_value` =  CONCAT(''http://'', @MOVEDOMAIN, @MOVEFOLDER,'/enroll/') WHERE  `wp_3_options`.`option_name` ='home';

UPDATE  `wp_2_options` SET  `option_value` =  CONCAT(''http://'', @MOVEDOMAIN, @MOVEFOLDER,'/portal/') WHERE  `wp_2_options`.`option_name` ='siteurl';
UPDATE  `wp_2_options` SET  `option_value` =  CONCAT(''http://'', @MOVEDOMAIN, @MOVEFOLDER,'/portal/') WHERE  `wp_2_options`.`option_name` ='home';

UPDATE  `wp_sitemeta` SET  `option_value` =  CONCAT(''http://'', @MOVEDOMAIN, @MOVEFOLDER,'/ecp/') WHERE  `wp_sitemeta`.`option_name` ='siteurl';
