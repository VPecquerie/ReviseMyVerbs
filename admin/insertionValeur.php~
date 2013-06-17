<?php require_once '../includes/haut.php'; 
if(!isset($_POST['liste']))
{
?>
<form method="POST">
    <textarea name="liste" >
    
    </textarea>
    <input type="submit" />
</form>
<?php
}
else
{
    $k = explode('<br />',  nl2br($_POST['liste']));
    $i = 0;
        while(!empty($k[$i+1]))
        {
        $requete = "INSERT INTO verbes VALUES('','".addslashes(trim($k[$i++]))."','".addslashes(trim($k[$i++]))."','".addslashes(trim($k[$i++]))."','".addslashes(trim($k[$i++]))."',0,3)";
        echo $requete."<br />";
        $connexion->query($requete)or die("Erreur SQL !");
        }
        echo "OK<br />";
    
    
}
require_once '../includes/bas.php';

