<?php 
	define('ROOT', '../forum/');
	include(ROOT . 'sdk.php');
	
	$sql = 'SELECT points FROM fsb2_gvg ORDER BY g_id';
	$result = Fsb::$db->query($sql);
	$values = array();
	$ymax = 0;
	$ymin = 2000;
	while ($donnees = Fsb::$db->row($result))
	{
		$values[] = $donnees['points'];
		
		if ($donnees['points'] > $ymax)
		{
			$ymax = $donnees['points'];
		}	
		elseif ($donnees['points'] < $ymin)
		{
			$ymin = $donnees['points'];
		}
	}
	Fsb::$db->free($result);
	
  // On inclue le fichier qui nous permettra de dessiner des courbes
   require_once "../tools/Artichow/LinePlot.class.php";

 	$graph = new Graph(500, 250);
	$graph->setAntiAliasing(TRUE);

	$plot = new LinePlot($values);
	$plot->setBackgroundGradient(
      new LinearGradient(
         new Color(210, 210, 210),
         new Color(250, 250, 250),
         0
      )
   );
   $plot->reduce(50);
   $plot->yAxis->setLabelPrecision(0);
   $plot->grid->hideVertical(TRUE);
   $plot->setYMin($ymin);
   $plot->setYMax($ymax);
   $plot->setSpace(5, 5, NULL, NULL);
   $plot->grid->setBackgroundColor(new Color(235, 235, 180, 100));
   $plot->xAxis->hide(TRUE);
   $plot->title->set('Progression des GvG');
   $plot->title->move(0, 223);
   $plot->title->setFont(new TuffyBold(14));
	$plot->title->setBackgroundColor(new White(50));
	$plot->title->setPadding(5, 5, 5, 2);
	$plot->title->border->setColor(new Black());
   
   $graph->add($plot);
   $graph->draw();
   
?>