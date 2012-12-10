<?php


/**
 * Installs MySQL database
 *
 * @author       Lovell Felix <hello@lovellfelix.com>
 * @copyright    Copyright © 2009-2012 Lovell Felix
 * @link         http://labs.lovellfelix.com
 */
 
 include_once('header.php');
 
 
$install = new Install();

class Install {

	private $error;
	private $link;
	private $settings = array();

	function __construct() {

		$this->checkInstall($hideError = true);

		if( !empty($_POST) ) :

			foreach ($_POST as $key => $value)
				$this->settings[$key] = $value;

			$this->validate();

		endif;

		if(!empty($this->error))
			echo $this->error;

	}

	// Run queries
	private function query($query) {

		$result = mysql_query($query);

		if (!$result) {
			echo _('Could not run query:') . mysql_error() . '<br/>';
			include_once('footer.php');
			exit;
		}

		return $result;

	}

	// Check for all form fields to be filled out
	private function validate() {

		if(strlen($this->settings['adminPass']) < 5)
			$this->error = '<div class="alert alert-error">'._('Password must be at least 5 characters.').'</div>';
		else
			$this->settings['adminPass'] = md5($this->settings['adminPass']);

		if( empty($this->settings['dbHost']) || empty($this->settings['dbUser']) || empty($this->settings['dbName']) || empty($this->settings['scriptPath']) || empty($this->settings['email']) || empty($this->settings['adminUser']) || empty($this->settings['adminPass'] ))
			$this->error = '<div class="alert alert-error">'._('Fill out all the details please').'</div>';

		// Check the database connection
		$this->dbLink();

	}

	// Check if there is a connection to the mysql server
	private function dbLink() {

		if(empty($this->error)) {
			$this->link = @mysql_connect($this->settings['dbHost'], $this->settings['dbUser'], $this->settings['dbPass']);

			if(!$this->link)
				$this->error = '<div class="alert alert-error">'._('Your Database details are incorrect.').'</div>';
			else
				$this->dbSelect();

		}

	}

	// Check for database selection
	private function dbSelect() {

		if(empty($this->error)) {
			$dbSelect = mysql_select_db($this->settings['dbName'],$this->link);

			if(!$dbSelect)
				$this->error = '<div class="alert alert-error">'._('Database name doesn\'t exist !').'</div>';
			else
				$this->existingTables();
		}

	}

	// Check for an existing installation
	private function existingTables() {

		if(empty($this->error)) :

			$this->insertSQL();
			$this->writeFile();
			$this->checkInstall();

		endif;

	}

	// Insert SQL data
	private function insertSQL() {

		if(empty($this->error)) {

			$this->query("SET NAMES utf8;");


			$this->query("
				CREATE TABLE IF NOT EXISTS `website_settings` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `option_name` varchar(255) NOT NULL,
				  `option_value` longtext NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id` (`id`),
				  UNIQUE KEY `option_name` (`option_name`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");

			$ok = $this->query("
				INSERT IGNORE INTO `website_settings` (`id`, `option_name`, `option_value`) VALUES
				(1, 'site_address', '".$this->settings['scriptPath']."'),
				(2, 'admin_email', '".$this->settings['email']."');
			");

			$this->query("
				CREATE TABLE IF NOT EXISTS `users` (
				  `user_id` int(8) NOT NULL AUTO_INCREMENT,
				  `username` varchar(11) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `email` varchar(255) NOT NULL,
				  `password` varchar(128) NOT NULL,
				  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`user_id`),
				  UNIQUE KEY `user_id` (`user_id`),
				  UNIQUE KEY `username` (`username`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
			");

			$this->query("
				INSERT IGNORE INTO `users` (`user_id`, `username`, `name`, `email`, `password`) VALUES
				(1, '".$this->settings['adminUser']."', 'Admin', '".$this->settings['email']."', '".$this->settings['adminPass']."'),
				(2, 'lovell', 'Lovell Felix', 'hello@lovellfelix.com', '21232f297a57a5a743894a0e4a801fc3');
			");

		} else $this->error = 'Your tables already exist! I won\'t insert anything.';
	}

	private function writeFile() {

		if($this->error == '') {

			/** Write db-config.php if it doesn't exist */
			$fp = @fopen("config/db-connect.php", "w");

			if( !$fp ) :
				echo '<div class="alert alert-warning">'._('Could not create config/db-connect.php, please confirm you have permission to create the file.').'</div>';
				return false;
			endif;


fwrite($fp, '<?php

////////////////////
// This file contains the database access information. 
// This file is needed to establish a connection to MySQL

$host = "'.$this->settings['dbHost'].'"; // localhost normally works, if localhost doesn\'t exist contact your web host
$dbName = "'.$this->settings['dbName'].'"; // Database name
$dbUser = "'.$this->settings['dbUser'].'"; // Username
$dbPass = "'.$this->settings['dbPass'].'"; // Password

?>');
			fclose($fp);
		}

	}

	private function checkInstall($hideError = false) {

			if (file_exists('config/db-connect.php')) : ?>
				<div class="row-fluid">
					<div class="span8">
						<div class="alert alert-success"><strong>Success!</strong> Installation is complete </div>
						<p><span class="label label-important">Important</span> 
	    Delete or rename the install folder to prevent security risk. </p>
					</div>
				
				</div> <?php
				include('footer.php');
				exit();
			else :
				if (!$hideError) $this->error = '<div class="alert alert-error">'._('Installation is not complete.').'</div>';
			endif;
	}

}
 
 
 ?>
 
 <div class="row">
	<div class="span9">
		<form class="form-horizontal" method="post" action="setup.php">

			<fieldset>
				<legend><small>Enter your Database connections details</small></legend>
				<div class="control-group">
					<label class="control-label" for="dbHost">Host</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="dbHost" name="dbHost" value="<?php if(isset($_POST['dbHost'])) echo $_POST['dbHost']; else echo 'localhost'; ?>" >
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="dbName">Database name</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="dbName" name="dbName" value="<?php if(isset($_POST['dbName'])) echo $_POST['dbName']; else echo 'database_name'; ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="dbUser">Username</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="dbUser" name="dbUser" value="<?php if(isset($_POST['dbUser'])) echo $_POST['dbUser']; else echo 'db_username'; ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="dbPass">Password</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="dbPass" name="dbPass" value="<?php if(isset($_POST['dbPass'])) echo $_POST['dbPass']; else echo 'db password'; ?>">
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend><small>Website Settings</small></legend>
				<div class="control-group">
					<label class="control-label" for="scriptPath">Website address</label>
					<div class="controls">
						<input type="url" class="input-xlarge" id="scriptPath" name="scriptPath" value="<?php if(isset($_POST['scriptPath'])) echo $_POST['scriptPath']; else echo "http://".$_SERVER['HTTP_HOST'].str_replace("db-installer/setup.php","",str_replace("functions","",str_replace("\\","/",$_SERVER['SCRIPT_NAME']))); ?>">
						
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email">Admin email</label>
					<div class="controls">
						<input type="email" class="input-xlarge" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; else echo 'no-reply@'.$_SERVER['HTTP_HOST']; ?>">
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend><small>Admin Account</small></legend>
				<div class="control-group">
					<label class="control-label" for="adminUser">Username</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="adminUser" name="adminUser" value="<?php if(isset($_POST['adminUser'])) echo $_POST['adminUser']; else echo 'admin'; ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="adminPass">Password</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="adminPass" name="adminPass" value="<?php if(isset($_POST['adminPass'])) echo $_POST['adminPass']; else echo 'admin'; ?>">
					</div>
				</div>
			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Install</button>
			</div>

		</form>

	</div>
</div>

<?php include_once('footer.php'); ?>