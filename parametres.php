<?php 
$titre = "Paramètres utilisateurs.";
$onglet = "param";
require_once("includes/haut.php");
if(!empty($_GET['action']))
$action = htmlspecialchars($_GET['action']);
else
$action = null;
switch($action)
{
	
	case "modifPass":
		if(!isset($_POST['pass']))
		{
		?>
			<form method="post" class="well form-horizontal">
		
				<fieldset>
				 <?php 
				 $code = intval($_GET['erreur']);
				 if(isset($code) && $code != 0) 
	             { 
	       		 ?>
	                <div class="alert alert-error">
	                    <h4 class="alert-heading">Erreur n°<?php echo $code; ?> !</h4>
	                    <p>
	                        <?php 
	                        switch($code)
	                        {
	                           	case 1: 
	                               	echo "Le mot de passe et sa confirmation sont différent.";
	                           	break;
	                           
	                           	case 2:
	                               	echo "Le mot de passe est trop court ou trop long (entre 6 et 32 caractères).";
	                           	break;
	                       
	                           	case 3:
	                               	echo "Votre mot de passe est le même que votre ancien mot de passe.";
	                           	break;
	                       
	                           	case 4:
	                            	echo "Le compte utilisateur est introuvable.";
			                    break;
	                           
	                           	case 5:
                           			echo "Le mot de passe saisi est incorrect";
	                           	break;
	                           
	                           	case 6:
	                           		echo "Une erreur s'est produit veuillez réessayer plus tard ! Si le problème persiste contactez nous.";
                           	   	break;
                           	}
	                        ?>
	                    </p>
	                </div>
	        	<?php 
	                } 
	        	?>
				
					<legend>Changement de mot de passe :</legend>
					
					<p>Afin de garantir la sécurité de votre compte, veuillez indiquer votre mot de passe actuel puis un nouveau mot de passe enfin entrer votre nouveau mot de passe à nouveau afin d'éviter toute erreur de saisie.
					</p>
					
			        <div class="control-group">
						<label class="control-label" for="pass">Ancien mot de passe : </label>
						<div class="controls">
							<input type="password" name="pass" required />
						</div>
        			</div>
        
                	<div class="control-group">
						<label class="control-label" for="nv_pass">Nouveau mot de passe : </label>
						<div class="controls">
							<input type="password" name="nv_pass" required />
						</div>
        			</div>			
        
                	<div class="control-group">
						<label class="control-label" for="confirmation">Confirmation :</label>
						<div class="controls">
							<input type="password" name="confirmation" required />
						</div>
        			</div>
        			
        			<div class="control-group">
						<label class="control-label"></label>
						<div class="controls">
							<input type="button" value="Annuler" class="btn btn-info" onclick='history.back();' /> 
							<input type="submit" value="Valider" class="btn btn-success" />
							
						</div>
        			</div>
				</fieldset>			
			</form>
		
		<?php 
		}
		else
		{
			// Etape 1 : On parse tout le monde
			$ancien_mdp = sha1($_POST["pass"]);
			$nouveau_mdp = sha1($_POST["nv_pass"]);
			$confirmation = sha1($_POST['confirmation']);
			
			// Etape 2 : On vérifie les informations fournie :
			
			if($nouveau_mdp != $confirmation) // Nouveau mot de passe différent de sa confirmation
			{
				header('location: ?action=modifPass&erreur=1');
			}
			else if(strlen($_POST['nv_pass']) < 6 || strlen($_POST['nv_pass']) > 32) // Nouveau mot de passe trop petit ou trop grand
			{
				
				header('location: ?action=modifPass&erreur=2');
			}
			else
			{
				$requete = "SELECT u_mdp FROM utilisateurs WHERE u_id = ".$_SESSION['id'];
				$retour = $connexion->query($requete);
				if($retour->rowCount() == 0) // Compte introuvable ? 
				{
					header('location: ?action=modifPass&erreur=4');
				}
				else
				{
					$retour->setFetchMode(PDO::FETCH_OBJ);
					$ligne = $retour->fetch();	
					if($ligne->u_mdp != $ancien_mdp) // le mot de passe saisie n'est pas le bon
					{
						header('location: ?action=modifPass&erreur=5');
					}
					else // Tout est OK ! 
					{
						$requete = "UPDATE utilisateurs SET u_mdp = '".$nouveau_mdp."' WHERE u_id = ".$_SESSION['id'];
						if(!$connexion->exec($requete))
						{
							header('location: ?action=modifPass&erreur=6');
						}
						else 
						{
							header('location: index.php?code=2');
						}
					}
				}					
			}
		}
		break;
	
    case "modifNiveau":
        if(!isset($_POST['niveau']))
        {
        $requete = "SELECT * FROM niveau ORDER BY n_id";
        $retour = $connexion->query($requete);
        $retour->setFetchMode(PDO::FETCH_OBJ);
        ?>
            <form method="POST">
            <select name="niveau">
                <?php while($data = $retour->fetch()) { ?>                
                    <option value="<?php echo $data->n_id; ?>" <?php if($_SESSION['niveau'] == $data->n_id) echo "selected" ?>>
                        <?php echo $data->n_titre; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" class="btn btn-success">Valider !</button>
            </form>
        <?php
        }
        else
        {
            $requete = "UPDATE utilisateurs SET u_niveau = ".intval($_POST['niveau'])." WHERE u_id = ".$_SESSION['id'];
            $_SESSION['niveau'] = intval($_POST['niveau']);
            if($connexion->query($requete)) echo "Action éffectué avec succès !";
            else echo "Une erreur est survenue !";
        }
    break;
    case "raz":
        
        if(!empty($_GET['confirmation']))
        {
        $sur = intval($_GET['confirmation']);
        
            if($sur == 1)
            {
                $requete = "DELETE FROM APPRENDRE WHERE u_id = ".intval($_SESSION['id']);

                $connexion->exec($requete);
                header('location: index.php?code=2'); 
            }
            else header("location: parametres.php");
        }
        else
        {
        ?>
            <p>Cet action vas effacer toutes traces de votre progression. Voulez-vous vraiment continuer ? <br /><br />
            <a href="?action=raz&confirmation=1"><button class="btn btn-danger">Oui</button></a>&nbsp;&nbsp;&nbsp;<a href="parametres.php"><button class="btn btn-success">Non</button></a></p>
        <?php
        }
    break;

    default: 
    $requete = "SELECT * FROM utilisateurs WHERE u_id = ".intval($_SESSION['id']);
    $retour  = $connexion->query($requete)or die ("Erreur !");
    $retour->setFetchMode(PDO::FETCH_OBJ);
    $retour = $retour->fetch();
    $_SESSION['niveau'] = $retour->u_niveau;
    ?>
    <a href="?action=modifNiveau" class="btn input-xlarge">Modifier mon niveau</a><br />
    <a href="?action=modifPass" class="btn input-xlarge">Modifier mon mot de passe</a><br />
    <a href="?action=raz" class="btn input-xlarge">Remise à Zéro de mes statistiques</a>
<?php
    break;   
}
require_once("includes/bas.php");
