<? /**/ ?>
<?php
set_time_limit(0);
include 'figure.php';
$debug = "";

$unite = "px";
$minlg = 4;
$maxx = 550/$minlg;
$maxy = 850/$minlg;

$couleurs = array(
"black","maroon","green","olive","navy","purple","teal","silver",
"gray","red","lime","yellow","blue","fuchsia","aqua");

$page = new Page($maxx,$maxy);
$page->maLongueurCarre = (int)$_REQUEST["lg"]/$minlg;
$page->monNbImagesX    = (int)$_REQUEST["px"];
$page->monNbCarreCaseX = (int)$_REQUEST["ix"];
$page->monNbImagesY    = (int)$_REQUEST["py"];
$page->monNbCarreCaseY = (int)$_REQUEST["iy"];
$page->monNbFigures    = (int)$_REQUEST["nb"];
$page->monMaxFigures   = 4;
$page->mesCouleurs     =  array(
"black","red","blue","green","silver",
"gray","maroon","navy","olive","purple","teal","yellow");

$page->remplis();
$lg = $page->maLongueurCarre;
$px = $page->monNbImagesX;
$ix = $page->monNbCarreCaseX;
$py = $page->monNbImagesY;
$iy = $page->monNbCarreCaseY;
$nb = $page->monNbFigures;

$lg *= $minlg;

$espace = max(3,$lg/2);
$marge = 130;

?>
<html>
<head>
  <title>histoire automatique</title>
  <style type="text/css">
   <!--
   table {  width:115<?=$unite?>; }
   td {  line-height:0.8;padding-left:10<?=$unite?>; text-align:left }
   th {  line-height:1;text-align:left }
   -->
   </style>
</head>
<body>
 <form action="automatique.php">
 <table >
  <tr><th align="left">Figures</th>
      <td><input name="nb" type="text"size="2" value="<?=$nb?>"></td></tr>
  <tr><th>Taille</th>
      <td><input name="lg" type="text"size="2" value="<?=$lg?>"></td></tr>
  <tr><th colspan="2" >Carr&eacute;s par case</th></tr>
  <tr><td>hauteur</td>
      <td><input name="ix" type="text" size="2" value="<?=$ix?>"></td></tr>
  <tr><td>largeur</td>
      <td><input name="iy" type="text" size="2" value="<?=$iy?>"></td></tr>
  <tr><th colspan="2" >Cases par page</th></tr>
  <tr><td>hauteur</td>
      <td><input name="px" type="text" size="2" value="<?=$px?>"></td></tr>
  <tr><td>largeur</td>
      <td><input name="py" type="text" size="2" value="<?=$py?>"></td></tr>
  <tr><td colspan="2"><input type="submit" value="va-s-y"></td></tr>
 </table>
 </form>
 <table>
  <tr><th >Choix al&eacute;atoire</th></tr>
  <tr  ><td ><a href="automatique.php">tout changer</a></td></tr>
  <tr ><td >
          <a href="automatique.php?nb=<?=$nb?>&lg=<?=$lg?>&ix=<?=$ix?>&iy=<?=$iy
                   ?>&px=<?=$px?>&py=<?=$py?>">tout garder</a>
  </td></tr>
  <tr ><td >
           <a href="automatique.php?lg=<?=$lg?>&ix=<?=$ix?>&iy=<?=$iy
                   ?>&px=<?=$px?>&py=<?=$py?>">figures</a>
  </td></tr>
  <tr ><td >
           <a href="automatique.php?nb=<?=$nb?>&ix=<?=$ix?>&iy=<?=$iy
                   ?>&px=<?=$px?>&py=<?=$py?>">taille</a>
  </td></tr>
  <tr ><td >
           <a href="automatique.php?nb=<?=$nb?>&lg=<?=$lg?>&px=<?=$px
                   ?>&py=<?=$py?>">carr&eacute;s par case</a>
  </td></tr>
  <tr ><td >
           <a href="automatique.php?nb=<?=$nb?>&lg=<?=$lg?>&ix=<?=$ix?>&iy=<?=$iy
                   ?>">cases par page</a>
  </td></tr>
  <tr ><td >
           <a href="automatique.php?nb=<?=$nb?>&lg=<?=$lg?>&iy=<?=$iy
                   ?>&py=<?=$py?>">hauteur</a>
  </td></tr>
  <tr ><td >
           <a href="automatique.php?nb=<?=$nb?>&lg=<?=$lg?>&ix=<?=$ix?>
                   ?>&px=<?=$px?>">largeur</a>
  </td></tr>
 </table>
<br>
 <div style="position:absolute; top:<?=$espace?><?=$unite?>; left:<?=$marge+$espace?><?=$unite?>;">
  <?
  $imgs = $page->mesImages;
  for($i = 0; $i<count($imgs); $i++){
    for($j = 0; $j<count($imgs[$i]); $j++){
       $unecase = $imgs[$i][$j]->represente();
       ?>
       <div style="position:absolute;
                   top:<?=$i*($ix*$lg+$espace)?><?=$unite?>;
                   left:<?=$j*($iy*$lg+$espace)?><?=$unite?>;
                   width:<?=$iy*$lg?><?=$unite?>;
                   height:<?=$ix*$lg?><?=$unite?>;
                   border:solid 1px black; ">
       <?
       for($k = 0; $k<count($unecase); $k++){
         for($l = 0; $l<count($unecase[$k]); $l++){
           if($unecase[$k][$l]){
             ?>
             <div style="position:absolute;
                         top:<?=$k*$lg?><?=$unite?>;
                         left:<?=$l*$lg?><?=$unite?>;
                         width:<?=$lg?><?=$unite?>;
                         height:<?=$lg?><?=$unite?>;
                         background-color:<?= $unecase[$k][$l]->maCouleur?>">
             </div>
             <?
	   }
         }
       }
       ?>
      </div>
      <?
    }
  }
  ?>
  </div>
  <div style="position:absolute;
              top:<?=$espace?><?=$unite?>;
              left:<?=$marge+$espace+$py*($iy*$lg+$espace)?><?=$unite?>;">
  <?= $debug ?>  </div>
</body>
</html>

