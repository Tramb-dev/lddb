<?php
	include 'header.php';
	
	if(isset($_POST['send'])) // Si on veut enregistrer la news (modifiée ou non)
	{		
		if (isset($_POST['title']) && isset($_POST['news'])) 
		{
			$title = addslashes($_POST['title']);
			$name = $fsb->userdata('u_nickname');
			$news = addslashes($_POST['news']);
			$id_news = addslashes($_POST['id_news']);
			
			if ($_POST['id_news'] == 0)
			{
				Fsb::$db->insert('news', array(
					'name' =>		$name,
					'title' 	=>	$title,
					'news'	=>		$news,
					'timestamp' =>	time(),
				));
			}
			else
			{
				Fsb::$db->update('news', array(
					'title' 	=>	$title,
					'news'	=>		$news,
				), 'WHERE n_id = ' . $id_news);
			}			
		}
		echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		exit();
	}
	elseif (isset($_POST['del']) && isset($_POST['id_news'])) // Si on veut supprimer la news
	{
		$sql = 'DELETE 
				FROM ' . SQL_PREFIX . 'news 
				WHERE n_id=' . $_POST['id_news'];
		Fsb::$db->query($sql);
		echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		exit();
	}
	else
	{
		$preview = FALSE;

		if ($fsb->is_logged() && ($fsb->userdata('u_auth') >= 3 || $fsb->userdata['u_color'] == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata['u_color'] == 'style="color: #00AA77; font-weight: bold"'))
		{	
			if(isset($_POST['preview'])) // Si on veut juste avoir une prévisualisation
			{
				$title = $_POST['title'];
				$name = $fsb->userdata('u_nickname');
				$news = $_POST['news'];
				$id_news = $_POST['id_news'];
				$preview = TRUE;
			}
			elseif (isset($_GET['modifier_news'])) // Si on demande de modifier une news
			{
				// On prot&#xE8;ge la variable "modifier_news" pour &#xE9;viter une faille SQL
				$_GET['modifier_news'] = mysql_real_escape_string(htmlspecialchars($_GET['modifier_news']));
		
				$sql = 'SELECT * 
						FROM ' . SQL_PREFIX . 'news 
						WHERE n_id=' . $_GET['modifier_news'];
				$result = Fsb::$db->query($sql);
				$donnees = Fsb::$db->row($result);
		
				// On place le titre et le contenu dans des variables simples
				$title = stripslashes($donnees['title']);
				$news = stripslashes($donnees['news']);
				$id_news = $donnees['n_id']; // Cette variable va servir pour se souvenir que c'est une modification
				
				Fsb::$db->free($result);
			}
			else // C'est qu'on rédige une nouvelle news
			{
				// Les variables $titre et $contenu sont vides, puisque c'est une nouvelle news
				$title = '';
				$news = '';
				$id_news = 0; // La variable vaut 0, donc on se souviendra que ce n'est pas une modification
			}
			
			if($preview == TRUE) // Si on veut voir la prévisualisation
			{
				include 'tools/bbcode.php';	
		
				?><div class="news">
      			<div class="news_titre"><span class="titre"><?php echo (stripslashes($title) .  '</span> <span class="date">(le ' . date('d/m/y \&#xE0; H:i:s', mktime()) . ')'); ?></span></div>
        		<div class="contenu_news">
				<?php echo replace_bbcode((nl2br(stripslashes($news)))); ?></div>
                <div class="news_footer"><span class="auteur"><?php echo $name; ?>
                </span></div></div><?php // Fontcion de remplacement du bbcode pour les news.
			}
		?>
		
		<form name="newst" action="rediger_news.php" method="post">
		<input type="button" value="b" style="width:50px;font-weight:bold" onclick="storeCaret('b')">
		<input type="button" value="i" style="width:50px;font-style:italic" onclick="storeCaret('i')">
		<input type="button" value="u" style="width:50px;text-decoration:underline;" onclick="storeCaret('u')">
		<input type="button" value="s" style="width:50px;text-decoration:line-through;" onclick="storeCaret('s')">
		<input type="button" value="url"style="width:50px" onclick="storeCaret('url')">
		<input type="button" value="img"style="width:50px" onclick="storeCaret('img')">
        <select title="Aligner">
        	<option value="Aligner">Aligner</option>
            <option value="Gauche" onclick="storeCaret('align=left')">Gauche</option>
            <option value="Centrer" onclick="storeCaret('align=center')">Centrer</option>
            <option value="Droite" onclick="storeCaret('align=right')">Droite</option>
            <option value="Justifier" onclick="storeCaret('align=justify')">Justifier</option>
        </select>

		<p>Titre : <input type="text" size="50" name="title" value="<?php echo $title; ?>" /></p>
		<p>
			Contenu :<br />
			<textarea name="news" id="news" cols="50" rows="10"><?php echo $news; ?></textarea><br />
		   
			<input type="hidden" name="id_news" value="<?php echo $id_news; ?>" />
			<input type="submit" name="preview" value="Pr&eacute;visualisation" />
			<input type="submit" name="send" value="Envoyer" />
            <input type="submit" name="del" value="Supprimer" />
		</p>
		</form>
		
		<br/>
		<a href="index.php">Retourner aux news</a>
		
		<?php
		}
		else
		{
			echo 'Vous n\'&#xEA;tes pas connect&eacute; ou n\'avez pas l\'autorisation d\'acc&eacute;der &#xE0; cette page.';
		}
			include 'footer.php';
	}
?>