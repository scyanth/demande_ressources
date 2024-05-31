<?php

// gestion des erreurs
//error_reporting(E_ALL);
//ini_set("display_errors",1);

// chargement des librairies
require "vendor/autoload.php";
use vlucas\phpdotenv;

// chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// connexion a la BDD company
try {
  $company_db = new PDO('mysql:host='.$_ENV['company_DB_HOST'].';dbname='.$_ENV['company_DB_NAME'],$_ENV['company_DB_LOGIN'],$_ENV['company_DB_PASSWORD']);
}catch (PDOException $e){
  print "Erreur de connexion a la BDD company : <br/> ". $e->getMessage(). "<br/>";
}

$company_db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);