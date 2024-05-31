Application PHP et JavaScript de formulaire pour demande de réservations de salles informatiques par des intervenants extérieurs à l'entreprise.<br>
Les données du formulaire sont mises en forme puis envoyées par email au secrétariat.<br>
Utilise une BDD MariaDB, notamment pour collecter temporairement les adresses IP client afin de vérifier le délai entre chaque requête, et bloquer si le délai est trop court (pour limiter le spam).<br>
La librairie dotEnv est utilisée pour protéger les informations sensibles.<br>
Les informations confidentielles de l'entreprise ont été anonymisées.
