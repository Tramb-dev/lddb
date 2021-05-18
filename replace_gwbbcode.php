<?php
	include 'header.php';
	if(Fsb::$session->is_logged())
	{
		if($fsb->userdata('u_auth') >= 3 || $fsb->userdata('u_color') == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata('u_color') == 'style="color: #00AA77; font-weight: bold"')
		{
			// Réalisé pour transferer des fichiers de skills GWbbCode
			?>
			<form method="post" enctype="multipart/form-data" action="replace_gwbbcode.php">
			<p>
			<input type="file" name="fichier" size="30">
			<input type="submit" name="upload" value="Uploader">
			<input type="hidden" name="MAX_FILE_SIZE" value="900000" />
			</p>
			</form>
			
			<?php
			if( isset($_POST['upload']) ) // si formulaire soumis
			{
				$content_dir = './forum/gwbbcode/data/'; // dossier où sera déplacé le fichier
				$content_dir_tmp = './forum/gwbbcode/data/tmp/'; // dossier où sera déplacé le fichier temporaire
				$name_file = $_FILES['fichier']['name'];
				$tmp_file = $_FILES['fichier']['tmp_name'];
				$type_file = $_FILES['fichier']['type'];
			
				if( !is_uploaded_file($tmp_file) )
				{
					stop("Le fichier est introuvable");
				}
			
				// on vérifie maintenant l'extension
				if( !strstr($type_file, 'x-httpd-php') && !strstr($type_file, 'octet-stream') && (substr($name_file, -4) != '.php'))
				{
					stop("Le fichier n'est pas en php !");
				}
				
				// vérifie si le nom de fichier est le même
				if($name_file != 'skill_db_1.php')
				{
					stop("Vous essayez de rentrer n'importe quoi !");
				}
			
				// on affiche pourquoi il y a une erreur s'il y a
				if ($_FILES['fichier']['error'])
				{
					  switch ($_FILES['fichier']['error'])
					  {
							   case 1: // UPLOAD_ERR_INI_SIZE
							   echo "Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";
							   break;
							   case 2: // UPLOAD_ERR_FORM_SIZE
							   echo "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
							   break;
							   case 3: // UPLOAD_ERR_PARTIAL
							   echo "L'envoi du fichier a été interrompu pendant le transfert !";
							   break;
							   case 4: // UPLOAD_ERR_NO_FILE
							   echo "Le fichier que vous avez envoyé a une taille nulle !";
							   break;
					  }
					  stop();
				}
			
				// on copie le fichier dans le dossier de destination
				if( !move_uploaded_file($tmp_file, $content_dir_tmp . $name_file) )
				{
					stop("Impossible de copier le fichier !");
				}
			
				// on teste si le début du fichier correspond bien à ce qu'on devrait trouver
				$fichier = $content_dir_tmp . $name_file;
				$str = md5(script_verif($fichier));
				if($str === md5('<?php return array ('))
				{
					copy($fichier, $content_dir . $name_file);
					unlink($fichier);
				}
				else
				{
					unlink($fichier);
					stop("Le fichier ne correspond pas à la base de skill GWbbCode");
				}
			
				echo "Le fichier a bien été uploadé";
			}
			include 'footer.php';
			
			function stop($echo = '')
			{
				echo $echo;
				include 'footer.php';
				exit();
			}
			
			function script_verif($nom_fichier)
			{
				$ligne = 'test';
				if( !$fichier = fopen($nom_fichier, 'r'))
				{
					die('Impossible d\'ouvrir le fichier.');
				}
				$ligne = fgets($fichier, 21);
				fclose($fichier);
				return $ligne;
			}
		}
		else
		{
			echo "Vous n'êtes pas habilitez à modifier le gwbbcode.";
		}
	}
	else
	{
		echo "Vous n'êtes pas loggé sur le forum !";
	}
?>