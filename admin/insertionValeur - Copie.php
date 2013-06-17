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
        
        $requete = "UPDATE verbes SET v_niveau = 2 WHERE v_base_verbale IN(".$k[$i];
        foreach($k as $ligne)
        {
            $i++;
            $requete .= ", '".addslashes($k[$i])."'";
        }
        echo $requete;
        echo "OK<br />";
    
    
}
require_once '../includes/bas.php';

