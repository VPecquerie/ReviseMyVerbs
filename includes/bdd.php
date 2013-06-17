<?php
try 
{   
		$base = 'mysql:host=localhost;dbname=concourt';
		$utilisateur = 'root';
		$motDePasse = '';
		$connexion = new PDO($base, $utilisateur, $motDePasse);
		$connexion->query("SET NAMES 'utf8'");
} 
catch ( Exception $e )
{
		echo $base. '<br />';
		echo 'Connexion à la base TpUn(MySQL) impossible : ' . $e->getMessage();
		die();
} 
?>
