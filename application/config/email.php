<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Configuration EMAIL
| -------------------------------------------------------------------------
*/
 
$config['protocol']      = 'smtp';
$config['smtp_host']    = 'smtps.univ-lille3.fr';
$config['smtp_user']    = 'localhost';
//$config['smtp_pass']    = '***';
$config['smtp_crypto'] = "tls";
$config['smtp_timeout'] = "5";
$config['smtp_port']    = 587;
$config['mailtype']    = 'html';
$config['charset']        = 'utf-8';
$config['wordwrap'] = TRUE;

