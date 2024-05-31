<?php

// -------------------------------------------------------------------------------------------------------------
// initialisation
// -------------------------------------------------------------------------------------------------------------

require_once("init.php");

// -------------------------------------------------------------------------------------------------------------
// contrôle du formulaire
// -------------------------------------------------------------------------------------------------------------

if (isset($_POST['demandeRessources'])) {

  // contrôle d'intégrité du formulaire et échappement

  if (!(isset($_POST["organisation"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["organisation"] === ""){
      header("Location:index.php");
      exit();
    }else{
      $organisation = htmlspecialchars($_POST["organisation"]);
    }
  }

  if (!(isset($_POST["prenom"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["prenom"] === ""){
      header("Location:index.php");
      exit();
    }else{
      $prenom = htmlspecialchars($_POST["prenom"]);
    }
  }

  if (!(isset($_POST["nom"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["nom"] === ""){
      header("Location:index.php");
      exit();
    }else{
      $nom = htmlspecialchars($_POST["nom"]);
    }
  }

  if (!(isset($_POST["tel"]))){
    header("Location:index.php");
    exit();
  }else{
    $tel = htmlspecialchars($_POST["tel"]);
  }

  if (!(isset($_POST["email"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["email"] === ""){
      header("Location:index.php");
      exit();
    }elseif (!(filter_var($_POST["email"],FILTER_VALIDATE_EMAIL))){
        header("Location:index.php");
        exit();
    }else{
        $email = htmlspecialchars($_POST["email"]);
    }
  }

  if (!(isset($_POST["plateforme"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["plateforme"] === ""){
      header("Location:index.php");
      exit();
    }else{
      $plateforme = htmlspecialchars($_POST["plateforme"]);
      if (($plateforme !== "mecanique") && ($plateforme !== "robotique")){
        header("Location:index.php");
        exit();
      }
    }
  }

  if (!(isset($_POST["ressource_mecanique"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($plateforme == "mecanique"){
      if ($_POST["ressource_mecanique"] === ""){
        header("Location:index.php");
        exit();
      }
    }
    $ressource_mecanique = htmlspecialchars($_POST["ressource_mecanique"]);
  }

  if (!(isset($_POST["complements"]))){
    header("Location:index.php");
    exit();
  }else{
    $complements = htmlspecialchars($_POST["complements"]);
  }

  if (!(isset($_POST["nb_etudiants"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["nb_etudiants"] === ""){
      header("Location:index.php");
      exit();
    }else{
      $nb_etudiants = htmlspecialchars($_POST["nb_etudiants"]);
    }
  }

  if (!(isset($_POST["nom_intervenant"]))){
    header("Location:index.php");
    exit();
  }else{
    $nom_intervenant = htmlspecialchars($_POST["nom_intervenant"]);
  }

  if (!(isset($_POST["nb_reservations"]))){
    header("Location:index.php");
    exit();
  }else{
    if ($_POST["nb_reservations"] === ""){
      header("Location:index.php");
      exit();
    }else{
      $nb_reservations = htmlspecialchars($_POST["nb_reservations"]);
    }
  }

  $dates = array();
  $heures_debut = array();
  $heures_fin = array();
  for ($i=1;$i < 21; $i++){
    $date_nom = "date_".$i;
    if (!(isset($_POST[$date_nom]))){
      header("Location:index.php");
      exit();
    }else{
      if ((int)$nb_reservations >= $i){
        if ($_POST[$date_nom] === ""){
          header("Location:index.php");
          exit();
        }else{
          array_push($dates,htmlspecialchars($_POST[$date_nom]));
        }
      }
    }
    $heure_debut_nom = "heure_debut_".$i;
    if (!(isset($_POST[$heure_debut_nom]))){
      header("Location:index.php");
      exit();
    }else{
      if ((int)$nb_reservations >= $i){
        if ($_POST[$heure_debut_nom] === ""){
          header("Location:index.php");
          exit();
        }else{
          array_push($heures_debut,htmlspecialchars($_POST[$heure_debut_nom]));
        }
      }
    }
    $heure_fin_nom = "heure_fin_".$i;
    if (!(isset($_POST[$heure_fin_nom]))){
      header("Location:index.php");
      exit();
    }else{
      if ((int)$nb_reservations >= $i){
        if ($_POST[$heure_fin_nom] === ""){
          header("Location:index.php");
          exit();
        }else{
          array_push($heures_fin,htmlspecialchars($_POST[$heure_fin_nom]));
        }
      }
    }
  }

  if (!(isset($_POST["autres_creneaux"]))){
    header("Location:index.php");
    exit();
  }else{
    $autres_creneaux = htmlspecialchars($_POST["autres_creneaux"]);
  }

}else{
  // redirection si formulaire non envoyé
  header("Location:index.php");
  exit();
}

// -------------------------------------------------------------------------------------------------------------
// préparation du corps du mail
// -------------------------------------------------------------------------------------------------------------

$message = "Bonjour,\nVoici une demande de réservation de ressources pour ".$organisation." :  \n\n";

$message = $message."De la part de ".$prenom." ".$nom." (".$email.")  ";
if ($tel !== ""){
  $message = $message."   \nTel : ".$tel;
}

if ($plateforme == "mecanique"){
  $message = $message."    \n\nPlateforme : Espaces mécaniques";
}else{
  $message = $message."    \n\nPlateforme : Espaces robotiques, Production, Vision";
}
if ($ressource_mecanique !== ""){
  $message = $message."    \nRessource : ".urldecode($ressource_mecanique);
}
if ($complements !== ""){
  $message = $message."    \n\n".$complements;
}
$message = $message."     \n\nNombre d'étudiants/stagiaires : ".$nb_etudiants;
if ($nom_intervenant !== ""){
  $message = $message."     \nIntervenant(s) : ".$nom_intervenant;
}

$message = $message."     \n\nCréneaux (".$nb_reservations.") : ";
$message = $message."\n\nN°| Date ------| Heures";

for ($i=0;$i < sizeof($dates);$i++){
  $message = $message."\n".($i+1)."  |  ".$dates[$i]."  |  ".$heures_debut[$i]."  -  ".$heures_fin[$i];
}

if ($autres_creneaux !== ""){
  $message = $message."     \n\n".$autres_creneaux;
}

$message = $message."     \n\n--     \nCet e-mail a été envoyé via le formulaire de contact de la company";

$message = str_replace("\n","\r\n",$message);
$message = nl2br($message);

// -------------------------------------------------------------------------------------------------------------
// récupération de l'adresse IP du client
// -------------------------------------------------------------------------------------------------------------

// si l'entête HTTP_CLIENT_IP est définie via proxy
if (!empty($_SERVER['HTTP_CLIENT_IP'])){
  // validation de la chaîne en cas d'injection
  if (filter_var($_SERVER['HTTP_CLIENT_IP'],FILTER_VALIDATE_IP)){
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  }else{
    $ip = $_SERVER['REMOTE_ADDR'];
  }
// si l'entête HTTP_X_FORWARDED_FOR est définie via proxy
}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  // extraction de la 1ère adresse dans la liste + suppression des espaces
  $ips = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
  $ip = trim($ip[0]);
  // validation de la chaîne en cas d'injection
  if (!(filter_var($ip,FILTER_VALIDATE_IP))){
    $ip = $_SERVER['REMOTE_ADDR'];
  }
}else{
  $ip = $_SERVER['REMOTE_ADDR'];
}

// -------------------------------------------------------------------------------------------------------------
// contrôle l'adresse IP et l'horodatage de la dernière requête + mise à jour de la BDD
// -------------------------------------------------------------------------------------------------------------

$requete = "SELECT id, ip_client, last_request FROM trace_requetes";
$reponse = $company_db->query($requete);
$traces = $reponse->fetchAll();

// cherche l'IP dans la BDD
$ip_tracee = false;
foreach ($traces as $trace){
  if ($ip == $trace['ip_client']){
    $ip_tracee = true;
    $last_request = $trace['last_request'];
    $id = $trace['id'];
  }
}

// horodatage (timestamp) de maintenant
$maintenant = intval($_SERVER["REQUEST_TIME"]);

if ($ip_tracee === true){
  // comparaison du délai depuis la dernière requête
  if ($maintenant - $last_request < 60){
    // refus d'envoi de mail si < 1 minute
    $mail_ok = false;
  }else{
    // sinon accepte l'envoi du mail et mets à jour l'horodatage dans la BDD
    $mail_ok = true;
    $requete = "UPDATE trace_requetes SET last_request = $maintenant WHERE id = $id";
    $company_db->exec($requete);
  }
}else{
  // nouvelle IP : accepte l'envoi du mail et inscrit la requête dans la BDD
  $mail_ok = true;
  $requete_preparee = $company_db->prepare("INSERT INTO trace_requetes (ip_client, last_request) VALUES (:ip_client, $maintenant)");
  $requete_preparee->bindValue(":ip_client",$ip);
  $requete_preparee->execute();
}

// -------------------------------------------------------------------------------------------------------------
// envoi du mail (ou pas)
// -------------------------------------------------------------------------------------------------------------
// note : l'adresse mail du secrétariat est cachée par sécurité, donc en champ Bcc, et le destinataire principal est le demandeur

$pour = $email;

// limite de 100 caractères pour l'organisation dans le sujet
if (strlen($organisation) > 100){
  $organisation = substr($organisation,0,100);
}
$sujet = "Réservation de ressources pour ".$organisation;
$headers = "Bcc: m@i.fr" . "\r\n".
          "From: ne-pas-repondre@i.fr" . "\r\n".
          "Content-Type: text/html;charset=UTF-8";

if ($mail_ok === true){
  $mail_succes = mail($pour,$sujet,$message,$headers);
}

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
    </style>
  </head>
  <body>
<?php
  if (isset($message)){

    if ($mail_ok === true){
      if ($mail_succes === true){
        print "Votre demande de réservation a bien été envoyée. Vous allez recevoir une copie par e-mail. A bientôt !";
      }else{
        print "Une erreur est survenue dans le traitement de votre demande, merci de réessayer plus tard. ";
        print "Si le problème persiste, veuillez le signaler au secrétariat de la company qui transmettra l'information à l'administrateur du site.";
      }
    }else{
      print "Merci d'attendre au moins 1 minute avant d'envoyer une nouvelle demande.";
    }

  }
  
?>
<br /><br />
  <a href="index.php">Retour</a>


</body>