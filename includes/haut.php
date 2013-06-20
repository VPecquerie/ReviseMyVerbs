<?php 
@session_start(); 
if(empty($onglet)) $onglet = "accueil";
require_once("bdd.php");
function is_connecte()
{
	if(!isset($_SESSION['id']))
	{
		header("location: connexion.php?code=5");
		return false;	
	}
	else return true;
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php if(isset($titre)) echo $titre; else echo "ReviseMyVerbs"; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Apprentissage, Verbes Irréguliers, Verbes, Anglais, Irregular Verbs">
    <meta name="author" content="Vincent Pecquerie">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="ico/favicon.png">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/ui.js"></script>
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">ReviseMyVerb</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li <?php if($onglet=="accueil") echo 'class="active"'; ?>><a href="index.php">Accueil</a></li>
            </ul>
              <?php if(isset($_SESSION['pseudo']) && isset($_SESSION['id'])) { ?>
              <ul class="nav pull-right">
                  <li <?php if($onglet=="liste") echo 'class="active"'; ?>><a href="listeVerbes.php">Liste des verbes</a></li>
                  <li <?php if($onglet=="apprendre") echo 'class="active"'; ?>><a href="apprendreVerbes.php">Apprendre des verbes</a></li>
                  <li <?php if($onglet=="stat") echo 'class="active"'; ?>><a href="statistiques.php">Statistiques</a></li>
                  <li <?php if($onglet=="param") echo 'class="active"'; ?>><a href="parametres.php">Paramètres</a></li>
                  <li><a href="deconnexion.php">Se déconnecter</a></li>
              </ul>
              <?php } else { ?> 
              <ul class="nav">
                <li <?php if($onglet=="connexion") echo 'class="active"'; ?>><a href="connexion.php">Se connecter</a></li>
                <li <?php if($onglet=="inscription") echo 'class="active"'; ?>><a href="inscription.php" >S'inscrire</a></li>
              </ul>
            <form action="connexion.php" method="post" class="navbar-form pull-right">
                <input class="span2" name="pseudo" type="text" placeholder="Identifiant">
                <input class="span2" name="pass" type="password" placeholder="Mot de passe">
                <button type="submit" class="btn"><i class="icon-off"></i> Se connecter</button>
            </form>
              <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="span11">  