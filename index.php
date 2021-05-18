<?php 
	include 'header.php';
?>
<h1>Les nouvelles</h1>
<?php
$auth = FALSE;
// On regarde si le membre est connecté ou pas. S'il n'est pas connecté, on affiche une courte description de la guilde
if ($fsb->is_logged())
{	
	// On regarde si on a affaire à un off ou MG pour créer ou modifier des news
	if($fsb->userdata('u_auth') >= 3 || $fsb->userdata('u_color') == 'style="color: #CCAA00; font-weight: bold"' || $fsb->userdata('u_color') == 'style="color: #00AA77; font-weight: bold"')
	{
		echo '<a class="submit" href="rediger_news.php" title="Cr&eacute;er une news">Cr&eacute;er une news</a><br/>';
		$auth = TRUE;
	}
}
?>
<div id="news_h3"></div>

<?php
	include './tools/pagination.php';
	$messages_par_page = 7;
	$messages = pagination('news', $messages_par_page);
		
	$sql = 'SELECT *
			FROM ' . SQL_PREFIX . 'news
			ORDER BY n_id DESC LIMIT ' . $messages['premier'] . ', ' . $messages_par_page;
	$results = Fsb::$db->query($sql);
  	
	// Système de news temporaire pour les anniversaires
	$anniv = $fsb->who_has_birthday_today();
	if($anniv['total'] > 1) // Si il y a plus d'un anniversaire à souhaiter
	{		
		?><div class="news"><div class="news_titre"><span class="titre"><?php echo ('Anniversaires'); ?></span></div>
        <div class="contenu_news">
		<?php echo ('Souhaitons tous un joyeux anniversaire &#xE0; : <br />'); 
				for($j = 0; $j < $anniv['total']; $j++)
				{
					echo ($anniv['list'][$j]['nickname'] . ' qui a aujourd\'hui ' . $anniv['list'][$j]['age'] . ' ans !');
					if($j != ($anniv['total'] - 1))
					echo '<br />';
				}
		?></div>
        <div class="news_footer"><span class="auteur">La guilde</span></div>
        </div><?php
	}
	elseif($anniv['total'] == 1) // S'il n'y en a qu'un à souhaiter.
	{
		?><div class="news"><div class="news_titre"><span class="titre"><?php echo ('Anniversaire'); ?></span></div>
		<div class="contenu_news">
		<?php echo ('Souhaitons tous un joyeux anniversaire &#xE0; ' . $anniv['list'][0]['nickname'] . ' qui a aujourd\'hui ' . $anniv['list'][0]['age'] . ' ans !'); ?>
        </div>
        <div class="news_footer"><span class="auteur">La guilde</span></div>
        </div><?php
	}
		
	include 'tools/bbcode.php';	
				
	while ($donnees = Fsb::$db->row($results))
	{
		?><div class="news">
        
        <div class="news_titre"><span class="titre"><?php 
		if($auth == TRUE)
		{
			echo '<a href="rediger_news.php?modifier_news=' . $donnees['n_id'] . '" >' . stripslashes($donnees['title']) . '</a>';
		}
		else
		{
			echo (stripslashes($donnees['title']));
		}
		echo '</span> <span class="date">(le ' . date('d/m/y \&#xE0; H:i:s', $donnees['timestamp']) . ')'; ?></span></div>
		<div class="contenu_news">
		<?php echo replace_bbcode((nl2br(stripslashes($donnees['news'])))) . '</div>';  // Fonction de remplacement du bbcode pour les news. 
		echo '<div class="news_footer"><span class="auteur">' . $donnees['name'] . '</span></div></div>';
	}
	Fsb::$db->free($results);
	
	echo pages('index');
	
	include 'footer.php';
?>