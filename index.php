<?php


/**
 * Installs MySQL database
 *
 * @author       Lovell Felix <hello@lovellfelix.com>
 * @copyright    Copyright © 2009-2012 Lovell Felix
 * @license      
 * @link         http://labs.lovellfelix.com
 */
 
 include_once("header.php"); 
 
 echo '<div id="wrap">
 		<div class="container">
        <div class="page-header">
 		<h1>Database Installer</h1>';
		
	
		echo '</div>
        <p class="lead">Get setup in minutes! Enjoy the super easy installation wizard to walk you through the setup process.<br /></p>
      ';
		
 //Check for db connect file	
	
	if (file_exists('config/db-connect.php')) {
	   
	    echo '<div class="alert alert-success" data-dismiss="alert"><strong>Success!</strong> Installation is completed.</div>
	    <p><span class="label label-important">Important</span> 
	    Delete or rename the install folder to prevent security risk. </p>
	    ';
	} else {
	    echo '<a href="setup.php" <button class="btn btn-success">Begin Intall</button></a>
	    ';
	}
								
					
 
        
include_once("footer.php"); ?>