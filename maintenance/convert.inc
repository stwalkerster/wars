<?php 

$tables_sql = array( 'acc_trustedips' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_trustedips` (
  `trustedips_ipaddr` VARCHAR(15) NOT NULL ,
  PRIMARY KEY (`trustedips_ipaddr`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_titleblacklist' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_titleblacklist` (
  `titleblacklist_regex` VARCHAR(128) NOT NULL ,
  `titleblacklist_casesensitive` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`titleblacklist_regex`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_welcome' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_welcome` (
  `welcome_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `welcome_uid` INT NOT NULL DEFAULT 0 COMMENT 'Username of the welcoming user' ,
  `welcome_user` VARCHAR(1024) NOT NULL ,
  `welcome_status` VARCHAR(96) NOT NULL ,
  PRIMARY KEY (`welcome_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_ban' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_ban` (
  `ban_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ban_type` VARCHAR(255) NOT NULL ,
  `ban_target` VARCHAR(700) NOT NULL ,
  `ban_user` INT NOT NULL ,
  `ban_reason` VARCHAR(4096) NOT NULL ,
  `ban_date` VARCHAR(1024) NOT NULL ,
  `ban_duration` VARCHAR(255) NOT NULL ,
  `ban_active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`ban_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_template' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_template` (
  `template_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `template_usercode` TINYTEXT NOT NULL ,
  `template_botcode` TINYTEXT NOT NULL ,
  PRIMARY KEY (`template_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_user' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_user` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(255) NOT NULL ,
  `user_email` VARCHAR(255) NOT NULL ,
  `user_pass` VARCHAR(255) NOT NULL ,
  `user_level` VARCHAR(255) NOT NULL DEFAULT 'New' ,
  `user_onwikiname` VARCHAR(255) NOT NULL ,
  `user_welcome_sig` VARCHAR(4096) NOT NULL DEFAULT '' ,
  `user_lastactive` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `user_lastip` VARCHAR(40) CHARACTER SET 'utf8' NOT NULL DEFAULT '0.0.0.0' ,
  `user_forcelogout` INT(3) NOT NULL DEFAULT '0' ,
  `user_secure` INT(11) NOT NULL DEFAULT '0' ,
  `user_checkuser` INT(1) NOT NULL DEFAULT '0' ,
  `user_identified` INT(1) UNSIGNED NOT NULL DEFAULT '0' ,
  `user_welcome_templateid` INT(11) NOT NULL DEFAULT '0' ,
  `user_abortpref` TINYINT(4) NOT NULL DEFAULT '0' ,
  `user_confirmationdiff` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`user_id`) ,
  UNIQUE INDEX `I_username` USING BTREE (`user_name` ASC) ,
  UNIQUE INDEX `user_onwikiname_UNIQUE` (`user_onwikiname` ASC) ,
  UNIQUE INDEX `user_email_UNIQUE` (`user_email` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_emails' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_emails` (
  `mail_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `mail_text` BLOB NOT NULL ,
  `mail_count` INT(11) NOT NULL ,
  `mail_desc` VARCHAR(255) NOT NULL ,
  `mail_type` VARCHAR(255) NOT NULL ,
  `mail_key` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`mail_id`) ,
  UNIQUE INDEX `mail_key_UNIQUE` (`mail_key` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_pend' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_pend` (
  `pend_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `pend_email` VARCHAR(512) NOT NULL ,
  `pend_ip` VARCHAR(255) NOT NULL ,
  `pend_name` VARCHAR(512) NOT NULL ,
  `pend_cmt` MEDIUMTEXT NOT NULL ,
  `pend_status` VARCHAR(255) NOT NULL ,
  `pend_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `pend_checksum` VARCHAR(256) NOT NULL ,
  `pend_emailsent` VARCHAR(10) NOT NULL ,
  `pend_mailconfirm` VARCHAR(255) NOT NULL ,
  `pend_reserved` INT(11) NOT NULL DEFAULT '0' COMMENT 'User ID of user who has \"reserved\" this request' ,
  `pend_useragent` BLOB NOT NULL COMMENT 'Useragent of the requesting web browser' ,
  `pend_proxyip` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`pend_id`) ,
  INDEX `acc_pend_status_mailconf` (`pend_status` ASC, `pend_mailconfirm` ASC) ,
  INDEX `pend_ip_status` (`pend_ip` ASC, `pend_mailconfirm` ASC) ,
  INDEX `pend_email_status` (`pend_email` ASC, `pend_mailconfirm` ASC) ,
  INDEX `ft_useragent` (`pend_useragent`(512) ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_log' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_log` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_target_id` INT NOT NULL DEFAULT 0 ,
  `log_target_objecttype` VARCHAR(45) NULL ,
  `log_target_text` VARCHAR(255) NOT NULL ,
  `log_user_id` INT NOT NULL DEFAULT 0 ,
  `log_user_text` VARCHAR(255) NOT NULL ,
  `log_action` VARCHAR(255) NOT NULL ,
  `log_time` DATETIME NOT NULL ,
  `log_cmt` BLOB NOT NULL ,
  PRIMARY KEY (`log_id`) ,
  INDEX `acc_log_action_idx` (`log_action` ASC) ,
  INDEX `log_pend_idx` (`log_target_text` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
SQL
, 'acc_cmt' => <<<SQL
CREATE  TABLE IF NOT EXISTS `acc_cmt` (
  `cmt_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `cmt_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `cmt_user` INT NOT NULL DEFAULT 0 ,
  `cmt_comment` MEDIUMTEXT NOT NULL ,
  `cmt_visibility` VARCHAR(255) NOT NULL ,
  `cmd_pend` INT(11) NOT NULL ,
  PRIMARY KEY (`cmt_id`) ,
  UNIQUE INDEX `cmt_id` (`cmt_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
SQL
);

function out($text)
{
	$lines = split("[\n|\r]", $text);
	foreach ($lines as $value) {
		echo date('c') . "  : " . $value . "\n";
	}
}

function query($q)
{
	global $accDatabase;
	$result = $accDatabase->query($q);
	if(get_class($result) != "PDOStatement")
	{
		out("Error! Could not retrieve result!");
		echo $value;
		die();
	}
	$error = $result->errorInfo();
	if($error[0] != "0000")
	{
		print_r($error);
		die;
	}	
}