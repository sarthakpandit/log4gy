<?php

//TODO rajouter le paramettrage dans tous les sens pour les format des loggerss, les sorties choisies (multiples), etc.

/**
 * Implémentation du patern Singleton.
 *
 * @version v1r0 (21 août 2011).
 * @author Effy - Aurélien GY <http://www.aureliengy.com>.
 */
abstract class Singleton{
    
  public static function getInstance(){
      
      static $instances = array();
    
      $class = get_called_class();

      if (isset($instances[$class]) === false){
          
         $instances[$class] = new $class();

      }

      return $instances[$class];  
   }

   protected function __construct() {}
   
   protected function __clone() {}

}

/**
* Classe contenant toutes les constantes statiques nécesssaires au fonctionnement de Log4GY.
*
* @version v0r0 (21 août 2011).
* @author Effy - Aurélien GY <http://www.aureliengy.com>.
*/
class LogConst{
    //TODO rajouter toutes les constantes
}

/**
* Classe d'encapsulation de Log4GY, afin de permettre une utilisation simplifiée.
*
* @version v0r0 (21 août 2011).
* @author Effy - Aurélien GY <http://www.aureliengy.com>.
*/
class Log {

    private function __construct(){}

    public static function info($message){

        $message=  "INFO ->  $message" . PHP_EOL;

        static::logLevel($message);
        
    }

    public static function debug($message){

        $message=  "DEBUG ->  $message" . PHP_EOL;

        static::logLevel($message);
    }

    private function logLevel($message){

        $logger = Log4GY::getInstance();

        $logger->bwa($message);
        
    }

    public static function conf($name){
        
        $logger = Log4GY::getInstance();

        $logger->loadConfig($name);
        
    }


    public static function start(){

        $logger = Log4GY::getInstance();

        $logger->profilStart();
        
    }

    public static function stop(){

        $logger = Log4GY::getInstance();

        $logger->profilStop();
        
    }

}

/**
 * Logger PHP avec l'utilisation la plus simple possible !
 *
 * @version v0r0 (21 août 2011).
 * @author Effy - Aurélien GY <http://www.aureliengy.com>.
 */
class Log4GY extends Singleton{
    
    //Todo passer le nom du fichier de log (ou le pointeur vers fichier) en session, histoire d'écrire à la suite entre deux pages...
    
    private $namePOTFile;

    private $tabProfiler;
    
    
    protected function __construct() {
        parent::__construct();
    
        $this->tabProfiler = array();
        $seedDate = date('c');
        $this->namePOTFile = $seedDate . '.log.txt';
      
        //TODO intialisation des fichiers / ouverture lecture ?
    }
    
    public function __destruct(){
        //TODO fermeture fichier.
    }
    
    public function loadConfig($namefile){
        $this->plop = $namefile;
    }
    
    public function bwa($mess){
        echo 'bwa', $mess, "\n";
        echo 'namefile', $this->namePOTFile, "\n";
        
        $reflexNames = $this->getReflexiveInfo();
        
        echo 'appelant : function=',  $reflexNames['function'], ' class=', $reflexNames['class'], ' file=', $reflexNames['file'], "\n\n";
    }
    
    /**
     * Méthode retournant les informations de la méthode appellante initiale.
     * Permet de déterminer le fichier, la classe et la méthode/function ou est situé la demande de log.
     * 
     * @return array {'file' = null, 'class' = null, 'function' = null}
     */
    private function getReflexiveInfo(){
        
        //TODO rajouter la ligne obtenable depuis la trace
        $filePath = null;
        $className = null;
        $functionName = null;
        
        $trace = debug_backtrace(false); //possibilitée d'affiner via un bitmap pour gagner des perfs dans les futurs versions de php
        $traceSize = count($trace) - 1;
        
        $traceCurseur = $traceSize;
        $find = false;
        
        //Le bon nom de fichier est toujours contenu au niveau de la trace des appels statiques à la class Log
        while( ($traceCurseur >= 0) && (! $find) ){
        
            //rajouter tous les types de logs dans le test :/ (toutes les fonctions d'entrées statiques dans
            //la class Log en fait !
            if( (isset($trace[$traceCurseur]['class'])) && (isset($trace[$traceCurseur]['function']))
                && ($trace[$traceCurseur]['class'] == 'Log') 
                && ( ($trace[$traceCurseur]['function'] == 'info' ) || ($trace[$traceCurseur]['function'] == 'debug' ) ) ){
            
                $filePath = $trace[$traceCurseur]['file'];
                $className = null;
                $functionName = null;
                
                $find = true;
            
            }else{
            
                $traceCurseur--;
            
            }
        }
        
        //si le nom de fichier était en premiére position alors pas de class ni de function, 
        //par contre si non, ils sont éventuellement contenu dans le niveau n+1 de la trace 
        if( $traceCurseur < $traceSize ){

            if( isset($trace[ $traceCurseur + 1 ]['class']) ){
                
                $className = $trace[ $traceCurseur + 1 ]['class'];
                
            }

            if( isset($trace[ $traceCurseur + 1 ]['function']) ){
                
                $functionName = $trace[ $traceCurseur + 1 ]['function'];
                
            }
            
        }
        
        return array('file' => $filePath, 'class' => $className, 'function' => $functionName);
    }
    
    //TODO finir la partie profilage
    public function profilStart(){
       
        $time = microtime(true);
        
        $cellId = $this->getUniqueSignatureCall();
        
        //initialisation de la cellule
        if(! isset($this->tabProfiler[$cellId])){
            
            $this->tabProfiler[$cellId] = array();
            
        }
        
        array_push($this->tabProfiler[$cellId], $time);
        //Pile FIFO pour stoquer la date, en cas d'appels récursifs.

        echo "date début : $time", PHP_EOL;
        
        //simulation
        sleep(2);
    }
    
    
    public function profilStop(){
        
        $time = microtime(true);
        $cellId = $this->getUniqueSignatureCall();
        
        if(! isset($this->tabProfiler[$cellId])){
            
            echo "date fin : $time";
            
        }else{
            
            $timeStart = array_pop($this->tabProfiler[$cellId]);
            
            $diff  = abs($timeStart - $time);
            
            echo "date fin : $time diff : $diff", PHP_EOL;
            
        }
        
    }
    
    /**
     * Génère un identifiant unique en fonction des parametres appelants initiaux.
     * 
     * @return String l'identifiant unique.
     */
    private function getUniqueSignatureCall(){
        
        $reflexiveCallInfo = $this->getReflexiveInfo();
        
        //TODO améliorer le systeme de génération d'identifiants uniques... Ya surement mieux possible !
        $callId = md5($reflexiveCallInfo['file'] . $reflexiveCallInfo['class'] . $reflexiveCallInfo['function']);
      
        return $callId;
        
    }
    
    
}

