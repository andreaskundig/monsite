<? /**/ ?>
<?php
class Carre{
    var $monX;
    var $monY;
    var $maCouleur;

    function Carre($x,$y,$couleur){
      $this->monX = $x;
      $this->monY = $y;
      $this->maCouleur = $couleur;
    }

    function copie(){
     $copie = new Carre($this->monX,$this->monY,$this->maCouleur);
     return $copie;
    }

    function toString(){
     return $this->maCouleur;
    }
}

class Figure
{
    var $mesCarres;
    var $monImage;
    var $monId;
    var $maCouleur;

    function Figure(){
      $this->monId = mt_rand(0,10000000000);
    }

    function copie(){
      $copie = new Figure();
      foreach($this->mesCarres as $carre){
          $copie->mesCarres[] = $carre->copie();
      }
      $copie->maCouleur = $this->maCouleur;
      return $copie;
    }

    function egale($figure){
       if($this!==$figure){
         return FALSE;
       }
       if($this->monId !== $figure->monId){
         return FALSE;
       }
       return TRUE;
    }

    function init($x,$y,$couleur){
       $this->maCouleur = $couleur;
       $this->mesCarres[] = new Carre($x,$y,$this->maCouleur);
    }

    function grandis ($direction)
    {
        if(!$this->mesCarres){
	  return FALSE;
	}
        $last = $this->mesCarres[count($this->mesCarres)-1];
	$new = new Carre($last->monX+$direction[0],
	                 $last->monY+$direction[1],
                         $this->maCouleur);
	if($this->monImage){
          if(!$this->monImage->alaplace($new->monX,$new->monY)){
	    return FALSE;
	  }

	}

        foreach($this->mesCarres as $carre){
          if($new->monX == $carre->monX && $new->monY == $carre->monY){
	    return FALSE;
          }
        }
        $this->mesCarres[] = $new;
	return TRUE;
    }


    function retrecis ()
    {
       if(!$this->mesCarres){
	  return FALSE;
       }
       if(count($this->mesCarres)>1){
         unset($this->mesCarres[0]);
         $this->mesCarres = array_values($this->mesCarres);
         return TRUE;
       }
       return FALSE;
    }

    function avance($direction){
      $ok = $this->grandis($direction);
      if($ok){
        $this->retrecis();
      }
      return $ok;
    }

    function toString(){
      $result = "" ;
      foreach($this->mesCarres as $carre){
        $result .=
"[".$carre->monX.",".$carre->monY.",".substr($carre->maCouleur,0,1)."]";
      }

      return $result;
    }

    function transforme($index,$direction){
      if($index<1){
        return $this->grandis($direction);
//      }else if($index<2){
//        return $this->avance($direction);
      }else{
        return $this->retrecis();
      }
    }

    function directions(){
       return array(array(0,1),array(1,0),array(-1,0),array(0,-1));
    }
}


class Image
{
    var $mesFigures = array();
    var $monX;
    var $monY;

    function Image($x,$y){
       $this->monX = $x;
       $this->monY = $y;
    }

    function copie(){
      $copie = new Image($this->monX,$this->monY);
      foreach($this->mesFigures as $figure){
        $copie->ajouteFigure($figure->copie());
      }
      return $copie;
    }

    function alaPlace($x,$y){
      if($x < 0 || $x >= $this->monX){
        return FALSE;
      }
      if($y < 0 || $y >= $this->monY){
        return FALSE;
      }
      if(count($this->mesFigures)>0){
        foreach($this->mesFigures as $figure){
          foreach($figure->mesCarres as $carre){
            if($x == $carre->monX && $y == $carre->monY){
              return FALSE;
            }
          }
        }
      }
      return TRUE;
    }

    function figureAlaPlace($unefigure){
      if(count($this->mesFigures)>0){
        foreach($this->mesFigures as $figure){
          if($figure->egale($unefigure)){
            return TRUE;
          }
        }
      }
      foreach($unefigure->mesCarres as $carre){
        if(!$this->alaPlace($carre->monX,$carre->monY)){
           return FALSE;
        }
      }
      return TRUE;
    }

    function ajouteFigure(&$figure){
       $ok = TRUE;
       foreach($figure->mesCarres as $carre){
         $ok = $ok && $this->alaPlace($carre->monX,$carre->monY);
       }
       if($ok){
         $figure->monImage =& $this;
         $this->mesFigures[] =& $figure;
       }
       return $ok;
    }

    function represente(){
      $rep  ;
      for($i = 0; $i<$this->monX; $i++){
        for($j = 0; $j<$this->monY; $j++){
           $rep[$i][$j] = 0;
        }
      }
      foreach($this->mesFigures as $figure){
        foreach($figure->mesCarres as $carre){
          $rep[$carre->monX][$carre->monY] = $carre;
        }
      }
      return $rep;
    }

    function toString(){
     $result = "";
     $rep = $this->represente();
      for($i = 0; $i<$this->monX; $i++){
        $result .= "|";
        for($j = 0; $j<$this->monY; $j++){
          if($rep[$i][$j]){
            $couleur = substr($rep[$i][$j]->maCouleur,0,1);
            if(!$couleur){
              $couleur = "X";
            }
            $result .= $couleur;
          }else{
            $result .= "_";
          }
        }
        $result .= "|<br>";
      }
      return $result;
    }
}

class Algorithme{

  function suivante($imageoriginale){
    $image = $imageoriginale->copie();
    for($i = 0; $i<count($image->mesFigures); $i++){
       $figure =& $image->mesFigures[$i];
       $transformations = array(0,1);
       while(count($transformations)>0){
         $max = count($transformations)-1;
         if($max>0){
           $transnb = mt_rand(0,$max);
         }else{
           $transnb = 0;
         }
         $transformation = $transformations[$transnb];
         $directions = $figure->directions();
         while(count($directions)>0){
           $max = count($directions)-1;
           if($max>0){
             $dirnb = mt_rand(0,$max);
           }else{
             $dirnb = 0;
           }
           $direction = $directions[$dirnb];
           if($figure->transforme($transformation,$direction)){
            break 2;
           }
           unset($directions[$dirnb]);
           $directions = array_values($directions);
 	 }
	 unset($transformations[$transnb]);
	 $transformations = array_values($transformations);
       }
    }
    return $image;
  }
}
class Hasard{

  function choisisPoints($x,$y,$nombre){
    $points;
    for($i=0;$i<$x;$i++){
      for($j=0;$j<$y;$j++){
        $points[]= array($i,$j);
      }
    }
    $choisis;
    for($i=0;$i<$nombre;$i++){
      $max = count($points)-1;
      $numero = $max;
      if($max>0){
        $numero = mt_rand(0,$max);
      }
      $choisis[]=$points[$numero];
      unset($points[$numero]);
      $points = array_values($points);
    }
    return $choisis;
  }
  

  /*
    Crée le nombre $nb d'entiers positifs aléatoires
    dont le produit ne dépasse pas le maximum spécifié,
    et les retourne dans un tableau.
  */
  function genereProduit ($nb, $maxproduit){
    //choisis le maximum au hasard
    $maxproduit = mt_rand(1,$maxproduit);
    $aremplir;
    $produit = 1;
    for($i=0; $i<$nb;$i++){
      if($maxproduit==1){
        $aremplir[$i] = 1;
      }else if($maxproduit<1){
        $aremplir[$i] = 0;
      }else{
        $aremplir[$i] = mt_rand(1,$maxproduit);
      }
      $produit *= $aremplir[$i];
    }
    if($produit<=$maxproduit){
      return $aremplir;
    }
    //réduire les nouvelles valeurs pour que le produit soit < $max
    $echelle = pow($maxproduit/$produit, 1/$nb);
    $test = 1;
    for($i=0; $i<$nb;$i++){
      $aremplir[$i] = floor(max(1,$aremplir[$i]*$echelle));
      $test *= $aremplir[$i];
    }
    //controle
    while($test>$maxproduit){
      //les nouvelles valeurs doivent être réduites davantage
      for($i=0; $i<$nb;$i++){
        if($aremplir[$i]>1){
          $aremplir[$i] -= 1;
          $test = 1;
          for($i=0; $i<$nb;$i++){
            $test *= $aremplir[$j];
          }
          if($test<=$maxproduit){
            break 2;
          }
        }
      }
    }
    return $aremplir;
  }

}

class Page{
  //nombre d'images par page, en x et en y
  var $monNbImagesX;
  var $monNbImagesY;
  var $mesImages = array();
  //algorithme pour remplir une page
  var $monAlgorithme;
  

  //variables nécessaires à la génération des images
  //longueur des carrés
  var $maLongueurCarre = 0;
  //espace entre les image par rapport à la longueur des carrés
  var $monEspaceParCarre = 0.5;
  // nombre de carrés par image, en x et en y
  var $monNbCarreCaseX = 0;
  var $monNbCarreCaseY = 0;
  //hauteur et largeur maximales
  var $monMaxX = 1;
  var $monMaxY = 1;
  //nombre de figures
  var $monNbFigures = 0;
  var $monMaxFigures = 0;
  //couleurs que peuvent avoir les figures
  var $mesCouleurs = array( "black","gray","red","yellow");
  //Hasard pour générer les Images
  var $monHasard;

  function Page($x,$y){
    $this->monMaxX = $x;
    $this->monMaxY = $y;
    $this->monAlgorithme = new Algorithme();
    $this->monHasard = new Hasard();
  }
  
  function longueurTotaleX(){
    return  $this->longueurTotale(
                          $this->maLongueurCarre,
                          $this->monNbImagesX,
                          $this->monNbCarreCaseX,
                          $this->monEspaceParCarre);
 }
 
 function longueurTotaleY(){
    return  $this->longueurTotale(
                          $this->maLongueurCarre,
                          $this->monNbImagesY,
                          $this->monNbCarreCaseY,
                          $this->monEspaceParCarre);
  }
  
  function longueurTotale($longueurCarre,$nbImages,$nbCarreCase,$espaceParCarre){
    return  $longueurCarre*$nbImages*$nbCarreCase
           + $longueurCarre*$espaceParCarre*($nbImages-1);
  }
  
  function suivante(){
    $suivante = new Page($this->monMaxX,$this->monMaxY);
    $suivante->maLongueurCarre = $this->maLongueurCarre;
    $suivante->monNbImagesX    = $this->monNbImagesX;
    $suivante->monNbCarreCaseX = $this->monNbCarreCaseX;
    $suivante->monNbImagesY    = $this->monNbImagesY;
    $suivante->monNbCarreCaseY = $this->monNbCarreCaseY;
    $suivante->monNbFigures    = $this->monNbFigures;
    $suivante->monMaxFigures   = $this->monMaxFigures;
    $suivante->mesCouleurs     = $this->mesCouleurs;

    $image = $this->mesImages[$suivante->monNbImagesX-1]
                             [$suivante->monNbImagesY-1];
    $image = $suivante->monAlgorithme->suivante($image);
    $suivante->remplisAvecImage($image);
    return $suivante;
  }
  
  function remplis(){
     //choisis la hauteur et largeur au hasard si nécessaire
     //en tenant compte des dimensions maximales
     $reduits =
         $this->reduis($this->maLongueurCarre,
                         $this->monNbImagesY,
                         $this->monNbCarreCaseY,
                         $this->monMaxY);
     $this->maLongueurCarre = $reduits[0];
     $this->monNbImagesY = $reduits[1];
     $this->monNbCarreCaseY = $reduits[2];
     $reduits
       = $this->reduis($this->maLongueurCarre,
                         $this->monNbImagesX,
                         $this->monNbCarreCaseX,
                         $this->monMaxX);
     $this->maLongueurCarre = $reduits[0];
     $this->monNbImagesX = $reduits[1];
     $this->monNbCarreCaseX = $reduits[2];

     //choisis le nombre de figures au hasard
     if($this->monNbFigures == 0){
        $max = ceil(min($this->monMaxFigures,
                        $this->monNbCarreCaseY*$this->monNbCarreCaseX));
        $this->monNbFigures = $max>1? mt_rand(1,$max):1;
     }

    //crée l'image de départ
    $image = new Image($this->monNbCarreCaseX,$this->monNbCarreCaseY);
    $choisis =
      $this->monHasard->choisisPoints($this->monNbCarreCaseX,
                                      $this->monNbCarreCaseY,
                                      $this->monNbFigures);
    $figure;
    for($i=0;$i<$this->monNbFigures;$i++){
      $couleur = $this->mesCouleurs[$i % count($this->mesCouleurs)];
      $choisi = $choisis[$i];
      $figure =& new Figure();
      $figure->init($choisi[0],$choisi[1],$couleur);
      $image->ajouteFigure($figure);
    }

    //génére les autres images
    $this->remplisAvecImage($image);
  }
  
  function remplisAvecImage($image){

      for($i = 0; $i<$this->monNbImagesX; $i++){
        for($j = 0; $j<$this->monNbImagesY; $j++){
          $this->mesImages[$i][$j] = $image;
	        $image = $this->monAlgorithme->suivante($image);
        }
      }
  }

  function toString(){
      $result;
      for($i = 0; $i<$this->monNbImagesX; $i++){
        for($j = 0; $j<$this->monNbImagesY; $j++){
	        $result .= "$i,$j<br>".$this->mesImages[$i][$j]->toString()."<br>";
        }
      }
      return $result;
  }
  
    /*
     Reduit les trois premiers arguments de manière
     à ce que la longueur totale de la page soit inférieure
     à max, remplace les valeurs nulles par des entiers choisis au hasard,
     et les retourne dans un tableau.
  */
  function reduis($longueurCarre,$nbImages,$nbCarreCase,$max){
    $tableau = array($longueurCarre,$nbImages,$nbCarreCase);
    //repérer les valeurs nulles dans le tableau
    $vides;
    $nonNuls;
    $produitNonNuls = 1;
    for($i = 0; $i<count($tableau);$i++){
       $valeur = $tableau[$i];
       if($valeur == 0){
         $vides[] = $i;
       }else{
         $nonNuls[] = $i;
         $produitNonNuls *= $valeur;
       }
    }
    if($vides){
      $generes = $this->monHasard->genereProduit(count($vides),$max/$produitNonNuls);
      for($i = 0; $i<count($vides);$i++){
        $tableau[$vides[$i]] = $generes[$i];
      }
    }

    //tant que la longueur totale > max
    //réduire les valeurs.
    //le produit des valeurs non nulles
    //est > $max,

    $index = 0;
    while($this->longueurTotale($tableau[0],
                                $tableau[1],
                                $tableau[2],
                                $this->monEspaceParCarre)>$max){
      //trouver un index dont la valeur est > 1
      while($tableau[$index]<=1){
        $index++;
        if($index >= count($tableau)){
          //arrivé au bout du tableau, laisse tomber
          break 2;
        }
      }
      //réduire la valeur index du tableau
      $tableau[$index]-=1;
    }

    return $tableau;
  }

}


?>
