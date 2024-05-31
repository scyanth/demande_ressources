<?php

// -------------------------------------------------------------------------------------------------------------
// initialisation
// -------------------------------------------------------------------------------------------------------------

require_once("init.php");

// -------------------------------------------------------------------------------------------------------------
// liste logiciels
// -------------------------------------------------------------------------------------------------------------

$requete = "SELECT id, nom, editeur, mecanique FROM logiciels ORDER BY nom";
$reponse = $company_db->query($requete);
$tous_logiciels = $reponse->fetchAll();

// -------------------------------------------------------------------------------------------------------------
// affichage
// -------------------------------------------------------------------------------------------------------------

?>

<!doctype html>
<html lang="fr">
  <head>
    <title>Demande de ressources</title>
    <meta charset="utf-8">
    <style>
      body {
        font-family: Arial, Tahoma, Verdana;
      }   
      table {
        border-collapse: separate;
        border-spacing: 5px 15px;
        white-space: nowrap;
      }
      th, td {
        border: none;
        background-color: transparent;
        padding : 0px;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      input, textarea, select {
        width: 99%;
        max-width: 100%;
      }
      .global {
        box-sizing: content-box;
        border-spacing: 0px;
      }
      .interne {
        table-layout: fixed;
	      width: 100%;
      }
      .droite {
        float: right;
      }
      input.droite {
        max-width: 10%;
      }
      .detail {
        font-style: italic;
        white-space: pre-line;
      }
      .cache {
        visibility:collapse;
      }

    </style>
  </head>
  <body>


  <p> <?php if(isset($_GET["message"])){print $_GET["message"];} ?> </p>

  <table class="global"><form action="submit.php" method="post">
  
  <tr><td> <div class="droite">* Champs obligatoires</div> <h2> Vos coordonnées </h2> </td></tr>
  <tr><td> <table class="interne">
    <tr><td colspan="2">Organisation* <br /> <div class="detail">Nom de formation, d'organisme, d'entreprise ou autre </div> <input type="text" name="organisation" required/> </td></tr>
    <tr><td>Prénom* <br /> <input type="text" name="prenom" required/></td> <td>Nom* <br /> <input type="text" name="nom" required/> </td></tr>
    <tr><td>Téléphone <br /> <input type="tel" name="tel"/></td> <td>E-mail* <br /> <input type="email" name="email" required></input> </td></tr>
  </table></td></tr>

  <tr><td><h2> Objet de la demande (la ressource dépend du choix de la plateforme)  </h2> </td></tr>
  <tr><td> <table class="interne">
    <tr><td colspan="2">Quelle plate-forme pour répondre à votre besoin ?* <br />
      <select id="select_plateforme" onchange="switch_plateforme()" name="plateforme" required>
        <option value="">--Veuillez choisir une option---</option>
        <option value="mecanique">Espaces mécaniques (XAO)</option>
        <option value="robotique">Espaces robotiques, Production, Vision</option>
      </select>
    </td></tr>
    <tr id="tr_ressource" class="cache"><td colspan="2" >Choisissez une ressource des espaces mécaniques <br />
      <select name="ressource_mecanique" id="select_ressource">
        <option value="">--Veuillez choisir une option---</option>
        <?php
        foreach ($tous_logiciels as $logiciel){
          if ($logiciel[3] == 1){
            print '<option value="'.urlencode($logiciel[1]).'">'.$logiciel[1].'</option>';
          }
        }
        ?>
      </select>
    </td></tr>
    <tr><td colspan="2">Compléments à la demande <div class="detail">La ressource dont vous avez besoin n'est pas listée ou bien vous voulez apporter un complément d'information concernant votre choix, décrivez votre besoin ici</div>
      <textarea rows="4" cols="100" name="complements"></textarea>
    </td></tr>
    <tr><td>Nombre prévisionnel d'étudiants/stagiaires concernés* <br /> <input type="number" min=5 max=999 name="nb_etudiants" required/> </td>
      <td> Nom du ou des intervenants (à défaut un correspondant) <br /> <input type="text" name="nom_intervenant"/> </td>
    </tr>
  </table></td></tr>

  <tr><td><h2> Réservations : date et créneau horaire </h2> </td></tr>
  <tr><td> <table class="interne">
    <tr><td colspan="2">Nombre de réservations <br />
      <select id="select_nb_reservations" onchange="switch_nb_reservations()" name="nb_reservations"> <?php
      for ($i=1; $i < 21; $i++) { 
        print '<option value='.$i.'>'.$i.'</option>';
      } ?>
      </select>
    </td></tr>
    <tr><td>
      1. Veuillez indiquer la date* <br/> <input type="date" name="date_1" required/>
    </td></tr>
    <tr><td>Heure de début* <br /> <select name="heure_debut_1"> <?php liste_horaires(); ?> </select> </td>
    <td>Heure de fin* <br /> <select name="heure_fin_1"> <?php liste_horaires(); ?> </select> </td></tr>
    <?php
    for ($i=2;$i < 21; $i++){
      print '<tr class="cache tr_date"><td colspan="2"><table class="interne">';
      print '<tr><td colspan="2"><hr/></td></tr>';
      print '<tr><td>'.$i.'. Veuillez indiquer la date* <br/> <input type="date" name="date_'.$i.'"/></td></tr>';
      print '<tr><td>Heure de début* <br /> <select name="heure_debut_'.$i.'">';
      liste_horaires();
      print '</select> </td> <td>Heure de fin* <br /> <select name="heure_fin_'.$i.'">';
      liste_horaires();
      print '</select> </td></tr> </table></td></tr>';
    }
    ?>
    <tr><td colspan="2">
      <div class="detail">
Indiquez les éventuels autres créneaux de réservation ici s'ils sont récurrents, en donnant le maximum d'informations (ex : tous les lundis de 14:00 à 18:00 du 04/05/2026 au 15/06/2026 inclus)
      </div>
      <textarea rows="4" cols="100" name="autres_creneaux"></textarea>
    </td></tr>
  </table></td></tr>

  <tr><td> <input type="submit" name="demandeRessources" class="droite" value="Envoyer"></input> </td></tr>

  <tr><td> <div class="detail droite"> Un email contenant ces informations sera envoyé au secrétariat de la company ainsi qu'à l'adresse que vous avez renseignée. </div></td></tr>

  </form></table>

  <?php

  function liste_horaires(){
    for ($hn=8; $hn < 21; $hn++){
      if ($hn < 10){
        $h = "0".strval($hn);
      }else{
        $h = strval($hn);
      }
      if ($hn < 20){
        for ($mn=1; $mn < 5; $mn++){
          switch ($mn) {
            case 1:
              $m = "00";
              break;
            case 2 :
              $m = "15";
              break;
            case 3 :
              $m = "30";
              break;
            case 4 :
              $m = "45";
              break;
          }
          print '<option value="'.$h.":".$m.'">'.$h.":".$m.'</option>';
        }
      }else{
        print '<option value="20:00">20:00</option>';
      }
    }
  }
  ?>

  <script>

function switch_plateforme(){
    if (document.getElementById("select_plateforme").options[document.getElementById("select_plateforme").selectedIndex].value == "mecanique"){
        document.getElementById("tr_ressource").style.visibility = "visible";
        document.getElementById("select_ressource").required = true;
    }else{
        document.getElementById("tr_ressource").style.visibility = "collapse";
        document.getElementById("select_ressource").required = false;
    }
}

function switch_nb_reservations(){
  let nb_reservations = parseInt(document.getElementById("select_nb_reservations").options[document.getElementById("select_nb_reservations").selectedIndex].value);
  let max = nb_reservations - 2
  for (let i=0;i < 19;i++){
    let j = i+2
    let nom = "date_" + j
    if (i <= max){
      document.getElementsByClassName("tr_date")[i].style.visibility = "visible";
      document.getElementsByName(nom)[0].required = true;
    }else{
      document.getElementsByClassName("tr_date")[i].style.visibility = "collapse";
      document.getElementsByName(nom)[0].required = false;
    }
  }
}


</script>

  </body>

</html>