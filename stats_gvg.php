<?php 
	include 'header.php';
?>
<h1>Statistiques des rencontres GvG</h1>

<div align="center"><a href="index_gvg.php"><img src="images/progression_gvg.php" alt="Progression des GvG" title="Progression des GvG" /></a></div>
<p class="stats_gvg"><i><u>Les statistiques disponibles commencent &agrave; partir du 30 mai 2009.</u></i></p>
<?php
	$sql = 'SELECT guilde, points, points_gagnes, timestamp
			FROM ' . SQL_PREFIX . 'gvg';
	$results = Fsb::$db->query($sql);
	
	$count = 0; // Total des matchs
	$matchsW = 0; // Total des matchs gagnés
	$matchsL = 0; // Total des matchs perdus
	$matchs_gagnes = array(); // Total des matchs gagnés avec points
	$matchs_perdus = array(); // Total des matchs perdus avec points
	$matchs_draw = 0; // Matchs à égalité
	$points_haut = 0; // Les plus haut points
	$points_bas = 0; // Les plus bas points
	$timestamp = 0;
	$sum = array(); // Tableau permettant de calculer la moyenne de matchs joués par soir
	$team = array(); // Tableau contenant l'équipe la plus rencontrée
	$team_win = array(); // Tableau contenant l'équipe contre laquelle on a le plus gagné
	$team_lose = array(); // Tableau contenant l'équipe contre laquelle on a le plus perdu
	$team_rencontree = array('guilde' => 0, 'nombre' => 0);
	$team_rencontreeW = array('guilde' => 0, 'nombre' => 0);
	$team_rencontreeL = array('guilde' => 0, 'nombre' => 0);
	
	while($donnees = Fsb::$db->row($results))
	{
		//nombre de matchs joués par soir
		$count++;
		if(array_key_exists($donnees['timestamp'], $sum))
		{
			$sum[$donnees['timestamp']]++;
		}
		else
		{
			$sum[$donnees['timestamp']] = 1;
		}
		//équipe la plus rencontrée
		if(array_key_exists($donnees['guilde'], $team))
		{
			$team[$donnees['guilde']]++;
		}
		else
		{
			$team[$donnees['guilde']] = 1;
		}
		
		// Stats des points gagnés et des équipes rencontrées
		if($donnees['points_gagnes'][0] == '+' && $donnees['points_gagnes'][1] != '0' && $donnees['points_gagnes'] != '0')
		{
			if(array_key_exists($donnees['points_gagnes'][1], $matchs_gagnes))
			{
				$matchs_gagnes[$donnees['points_gagnes'][1]]++;
			}
			else
			{
				$matchs_gagnes[$donnees['points_gagnes'][1]] = 1;
			}
			
			if(array_key_exists($donnees['guilde'], $team_win))
			{
				$team_win[$donnees['guilde']]++;
			}
			else
			{
				$team_win[$donnees['guilde']] = 1;
			}
			$matchsW++;
		}
		elseif($donnees['points_gagnes'][0] == '-' && $donnees['points_gagnes'][1] != '0' && $donnees['points_gagnes'] != '0')
		{
			if(array_key_exists($donnees['points_gagnes'][1], $matchs_perdus))
			{
				$matchs_perdus[$donnees['points_gagnes'][1]]++;
			}
			else
			{
				$matchs_perdus[$donnees['points_gagnes'][1]] = 1;
			}

			if(array_key_exists($donnees['guilde'], $team_lose))
			{
				$team_lose[$donnees['guilde']]++;
			}
			else
			{
				$team_lose[$donnees['guilde']] = 1;
			}
			$matchsL++;
		}
		else
		{
			$matchs_draw++;
		}
		
		// Calcul des points extrêmes
		if($donnees['points'] > $points_haut)
		{
			$points_haut = $donnees['points'];
		}
		elseif($donnees['points'] < $points_bas || $points_bas == 0)
		{
			$points_bas = $donnees['points'];
		}
		//
		
	}
	Fsb::$db->free($results);

	foreach($team as $key => $value)
	{
		if($value >= $team_rencontree['nombre'])
		{
			$team_rencontree['guilde'] = $key;
			$team_rencontree['nombre'] = $value;
		}
	}
	foreach($team_win as $key => $value)
	{
		if($value >= $team_rencontreeW['nombre'])
		{
			$team_rencontreeW['guilde'] = $key;
			$team_rencontreeW['nombre'] = $value;
		}
	}
	foreach($team_lose as $key => $value)
	{
		if($value >= $team_rencontreeL['nombre'])
		{
			$team_rencontreeL['guilde'] = $key;
			$team_rencontreeL['nombre'] = $value;
		}
	}
	$nb_soirees = count($sum); // Calcul du nombre de soirées jouées
	$sum_soirees = intval($count/ $nb_soirees); // Calcul du nombre de matchs moyen joués par soir
?>

<p class="stats_gvg">Nombre de rencontres : <?php echo $count;
						ksort($matchs_gagnes); ?><br/>
Nombre de matchs gagn&eacute;s : <?php echo $matchsW; ?><br/>
<table class="tab_stat_gvg">
	<tr>
    <?php
		foreach($matchs_gagnes as $key => $value)
		{
			echo '<td>+' . $key . ' : ' . $value . '</td>';
		}
	?>
    </tr>
</table>
Nombre de matchs perdus : <?php echo $matchsL;
				ksort($matchs_perdus); ?><br/>
<table class="tab_stat_gvg">
	<tr>
    <?php
		foreach($matchs_perdus as $key => $value)
		{
			echo '<td>-' . $key . ' : ' . $value . '</td>';
		}
	?>
    </tr>
</table>
Nombre d'égalités : <?php echo $matchs_draw; ?></p>

<p class="stats_gvg">Equipe la plus rencontr&eacute;e : <?php echo $team_rencontree['guilde'] . ' <i>(' . ($team_rencontree['nombre']) . ' fois)</i>'; ?><br/>
Equipe contre laquelle nous avons eu le plus de victoires : <?php echo $team_rencontreeW['guilde'] . ' <i>(' . ($team_rencontreeW['nombre']) . ' fois)</i>'; ?><br/>
Equipe contre laquelle nous avons eu le plus de d&eacute;faites : <?php echo $team_rencontreeL['guilde'] . ' <i>(' . ($team_rencontreeL['nombre']) . ' fois)</i>'; ?></p>

<p class="stats_gvg">Points au plus haut : <?php echo $points_haut; ?><br/>
Points au plus bas : <?php echo $points_bas; ?><br/>
Moyenne de rencontres par soir&eacute;e : <?php echo $sum_soirees; ?><br/>
Nombre de soir&eacute;es jou&eacute;es : <?php echo $nb_soirees; ?></p>

<?php
	include 'footer.php';
?>
