<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Azure B2C Demo</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/slate/bootstrap.min.css" rel="stylesheet" integrity="sha384-RpX8okQqCyUNG7PlOYNybyJXYTtGQH+7rIKiVvg1DLg6jahLEk47VvpUyS+E2/uJ" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url(); ?>">B2C Demo <?php echo getenv("VMCHOOSERTITLESUFFIX"); ?></a>
    </div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <?php if (!$loggedin) { ?>
      <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url()."auth/login/"; ?>">Login</a></li>
      </ul>
    <?php } else { ?>
      <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url()."auth/profile"; ?>">Profile</a></li>
      </ul>
	    <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url()."auth/logout"; ?>">Logout</a></li>
      </ul>
    <?php } ?>
    </div>
  </div>
</nav>


        