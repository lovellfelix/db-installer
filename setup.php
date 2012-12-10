<?php


/**
 * Installs MySQL database
 *
 * @author       Lovell Felix <hello@lovellfelix.com>
 * @copyright    Copyright © 2009-2012 Lovell Felix
 * @license      
 * @link         http://labs.lovellfelix.com
 */
 
 include_once('header.php');
 
 


 
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
