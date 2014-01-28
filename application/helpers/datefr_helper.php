<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('datefr'))
{
	setlocale(LC_TIME, "fr_FR"); 
	
    function datefr($var = '')
    {
		$date =date_create_from_format("d/m/Y",$var);
		$timestamp = $date->getTimestamp(); 
		return strftime( "%a %d/%m/%Y", $timestamp );
    }   
}