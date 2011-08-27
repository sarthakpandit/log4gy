<?php

require_once('lib/HelloWorld.php');
require_once('lib/Log4Gy.php');

Log::info("Début - testLog.php");

Log::start();

$hw = new HelloWorld('Salut :D');

Log::info("Milieu - testLog.php");

Log::conf("le fichier de conf 1");

$hw->doSpeack();

Log::conf("le fichier de conf 2");

function beurj(){
    Log::debug('Fin - testLog.php');
}

function jaimelespattes(){

    beurj();
    Log::debug('Fin - sdsds.php');
}

jaimelespattes();

Log::stop();
