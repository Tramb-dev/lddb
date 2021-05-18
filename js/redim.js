// JavaScript Document
function redimImage(inImg)
{
// Cette function recoit 3 parametres
// inImg : Chemin relatif de l'image
// inMW : Largeur maximale
// inMH : Hauteur maximale
	 var maxWidth = 580;
	 var maxHeight = 550;
	 
	 // Declarations des variables "Nouvelle Taille"
	 var dW = 0;
	 var dH = 0;
	 
	 // Declaration d'un objet Image
	 var oImg = new Image();
	 
	 // Affectation du chemin de l'image a l'objet
	 oImg.src = inImg;
	 
	 // On recupere les tailles reelles
	 var h = dH = oImg.height;
	 var w = dW = oImg.width;
	 
	 // Si la largeur ou la hauteur depasse la taille maximale
	 if ((h >= maxHeight) || (w >= maxWidth))
	 {
		 // Si la largeur et la hauteur depasse la taille maximale
		 if ((h >= maxHeight) && (w >= maxWidth))
		 {
			 // On cherche la plus grande valeur
			 if (h > w)
			 {
				 dH = maxHeight;
				 // On recalcule la taille proportionnellement
				 dW = parseInt((w * dH) / h, 10);
			 }
			 else
			 {
				 dW = maxWidth;
				 // On recalcule la taille proportionnellement
				 dH = parseInt((h * dW) / w, 10);
			 }
		 }
		 else if ((h > maxHeight) && (w < maxWidth))
		 {
			 // Si la hauteur depasse la taille maximale
			 dH = maxHeight;
			 // On recalcule la taille proportionnellement
			 dW = parseInt((w * dH) / h, 10);
		 } 
		 else if ((h < maxHeight) && (w > maxWidth))
		 {
			 // Si la largeur depasse la taille maximale
			 dW = maxWidth;
			 // On recalcule la taille proportionnellement
			 dH = parseInt((h * dW) / w, 10);
		 }
	 }
	 document.write("<img src=\"" + inImg + "\" width=\"" + dW + "\" height=\"" + dH + "\" border=\"0\"\/>");
 } 