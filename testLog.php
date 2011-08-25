<?php

require_once('lib/HelloWorld.php');
require_once('lib/Log4Gy.php');

Log::info("Début - testLog.php", 1);

Log::start(11);

$hw = new HelloWorld('Salut :D');

Log::info("Milieu - testLog.php", 2);

Log::conf("le fichier de conf 1", 2,2);

$hw->doSpeack();

Log::conf("le fichier de conf 2", 4);

function beurj(){
    Log::debug('Fin - testLog.php', 3);
}

function jaimelespattes(){

    beurj();
    Log::debug('Fin - sdsds.php', 3);
}

jaimelespattes();
Log::stop(4);
//du coup créer des fonction début / fin direct
//