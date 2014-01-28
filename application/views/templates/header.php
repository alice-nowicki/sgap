<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="fr" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="fr" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="fr" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="fr" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="fr" class="no-js"> <!--<![endif]-->
<head>
  <meta charset="UTF-8">
  <title><?=$title?></title>
  <meta name="description" content="Application de suivi personnalisé">
  <meta name="author" content="master ID">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?=base_url();?>public/images/favicon.ico">
  <?= link_tag(base_url().'public/css/base.css'); ?>
  <?= link_tag(base_url().'public/css/skeleton.css'); ?>
  <?= link_tag(base_url().'public/css/layout.css'); ?>
  <?= link_tag(base_url().'public/css/main.css'); ?>
  <!-- <script src="js/libs/modernizr-1.6.min.js"></script> -->
</head>
<body>
 
	<script src="https://code.jquery.com/jquery-latest.min.js"></script>
	<script src="<?=base_url();?>/public/js/jquery.tablesorter.js"></script>
	<!-- 	<script>window.jQuery || document.write("<script src='<?=base_url() ?>public/js/jquery-1.5.1.min.js'>\x3C/script>")</script> 
    -->
	<?if (($this->session->userdata('id'))) { ?>
		<div id='top'>
			<ul>
			<li>Connecté en tant que  <?= strtoupper($this->session->userdata('nom')) ?> <?= $this->session->userdata('prenom') ?>
			<li><?= anchor('user/changePasswd','Changer mot de passe') ?>
			<li><?= anchor('user/logout','Logout') ?>
		</ul>
		</div>
	<? }	?>
  <!--[if lt IE 7 ]>
  <script src="js/libs/dd_belatedpng.js"></script>
  <script> DD_belatedPNG.fix('img, .png_bg');</script>
  <![endif]-->
	  
	  <? if (isset($messages) and ($messages != "")) { ?>
	  <div class="notice">
	  		<span class="close">Fermer</span>
	  		<?=$messages?> 
	  </div>
	  <? } ?>
	  <div id="wrapper">
	

