<?php 
	include 'header.php';
	
?>

<h1>Vidéos</h1>
Voici les différentes vidéos de la guilde prises lors d'événements diverses :
<?php
/* 	if($fsb->userdata('u_auth') >= 3)
	{
		echo '<span class="submit"><a href="add_videos.php">Ajouter une vidéo</a></span><br /><br />';
	}
*/?>
<table class="videos">
<?php
	if(isset($_GET['video']) && isset($_GET['qualite']))
	{
		
		if($_GET['qualite'] == 'hq')
		{
			playerHQ($_GET['video']);
		}
		elseif($_GET['qualite'] == 'mq')
		{
			playerMQ($_GET['video']);
		}
		echo '<br /><a href="videos.php">Retour</a>';
	}
	else
	{
		$sql = 'SELECT *
				FROM ' . SQL_PREFIX . 'videos
				ORDER BY v_id DESC';
		$results = Fsb::$db->query($sql);
		while ($row = Fsb::$db->row($results))
		{
			echo '<tr>';
			echo '<td>' . $row['comment'] . ' : </td>';
			echo '<td>';
			if($row['mq'] == 1)
			{
				echo '<a href="videos.php?video=' . $row['file'] . '&amp;qualite=mq">moyenne qualité</a>';
			}
			if(($row['mq'] == 1) && ($row['hq'] == 1))
			{
				echo ' / ';
			}
			if($row['hq'] == 1)
			{
				echo '<a href="videos.php?video=' . $row['file'] . '&amp;qualite=hq">haute qualité</a>';
			}
			echo '</td>';
			echo '</tr>';
		}
	}
	
	function playerMQ($fichier)
	{
		?><div align="center">
			<embed
			src="http://www.lesdisciplesdebaal.com/videos/mediaplayer.swf"
			width="740"
			height="485"
			allowscriptaccess="always"
			allowfullscreen="true"
			flashvars="height=485&width=740&file=http://www.lesdisciplesdebaal.com/videos/<?php echo $fichier; ?>.flv&searchbar=false"
			/>
		</div><?php
	}
	
	function playerHQ($fichier)
	{
		?><div align="center">
			<script type='text/javascript' src="http://www.lesdisciplesdebaal.com/js/silverlight.js"></script>
			<script type='text/javascript' src="http://www.lesdisciplesdebaal.com/js/wmvplayer.js"></script>
			 
			<div id="container"></div>
			 
			<script type="text/javascript">
			 var cnt = document.getElementById("container");
			 var src = 'http://www.lesdisciplesdebaal.com/videos/wmvplayer.xaml';
			 var cfg = {
			  height:'485',
			  width:'740',
			  file:'http://www.lesdisciplesdebaal.com/videos/<?php echo $fichier; ?>.wmv'
			 };
			 var ply = new jeroenwijering.Player(cnt,src,cfg);
			</script>
		</div><?php
	}
?>
</table>
<?php
	include 'footer.php';
?>