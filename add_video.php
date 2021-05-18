<?php 
include 'header.php';
?>

<form action="add_video.php" method="post" enctype="multipart/form-data">
        <p>
                Ajouter la vidéo : 
                <input type="file" name="movie" /><br />
                <input type="submit" value="Envoyer la vidéo" />
        </p>
</form>

<?php
if(isset($_FILES['movie']))
{
	// On vérifie l'extension
	$infosfichier = pathinfo($_FILES['video']['name']);
  	$extension_upload = $infosfichier['extension'];
	$extensions_autorisees = array('avi', 'flv', 'mpg', 'mpeg', 'wmv');
	
	if (in_array($extension_upload, $extensions_autorisees))
	{
		switch ($_FILES['movie']['error']) // On vérifie les erreurs
		{
			 case 1: // UPLOAD_ERR_INI_SIZE
			 echo"Le fichier dépasse la limite autorisée par le serveur !";
			 exit;
			 break;
			 
			 case 2: // UPLOAD_ERR_FORM_SIZE
			 echo "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
			 exit;
			 break;
			 
			 case 3: // UPLOAD_ERR_PARTIAL
			 echo "L'envoi du fichier a été interrompu pendant le transfert !";
			 exit;
			 break;
			 
			 case 4: // UPLOAD_ERR_NO_FILE
			 echo "Le fichier que vous avez envoyé a une taille nulle !";
			 exit;
			 break;
			 
			 default:
			 break;
		}
		
	}
	else
	{
		echo 'Le fichier envoyé n\'est pas au bon format. Les format acceptés sont avi, flv, mpg, mpeg et wmv.';
	}
}
else
{
	echo 'Veuillez rentrer une vidéo.';
}

include 'footer.php';
?>