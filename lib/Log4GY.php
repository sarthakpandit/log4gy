<?php

//TODO rajouter le paramettrage dans tous les sens pour les format des loggerss, les sorties choisies (multiples), etc.

/**
 * Implémentation du patern Singleton.
 *
 * @version v1r0 (21 août 2011).
 * @author Effy - Aurélien GY <http://www.aureliengy.com>.
 */
abstract class Singleton{

	/**
	 * 
	 * @return Singleton the singleton
	 */
    public static function getInstance(){

        static $instances = array();

        $class = get_called_class();

        if (isset($instances[$class]) === false){

            $instances[$class] = new $class();

        }

        return $instances[$class];
    }

    protected function __construct() {
    }
     
    protected function __clone() {
    }

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

        $message=  "INFO ->  $message" . '<br />' . PHP_EOL;

        static::logLevel($message);
        
    }

    public static function debug($message){

        $message=  "DEBUG ->  $message" . '<br />' . PHP_EOL;

        static::logLevel($message);
    }

    private function logLevel($message){

        $logger = Log4GY::getInstance();

        $logger->log($message);
        
    }

    public static function conf($locale, $threshold, $logLevels, $tabAppenders = array(LogC::APP_CONSOLE), $singleFileOutput = true, $richFilePrepend = '<html><body>', $richFileAppend = '</body></html>'){
        
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

    //FIXME à virer, juste pour test
    public static function getPOTFileName(){
        
        $logger = Log4GY::getInstance();
        
        echo $logger->getPOTFileName(), '<br />', PHP_EOL;
        
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
    
    //FIXME à virer, juste pour test
    public function getPOTFileName(){
        return $this->namePOTFile;
    }
    
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
    
    public function loadConfig($locale, $threshold, $tabAppenders = array(LogC::APP_CONSOLE), $singleFileOutput = true){
        // définition de la locale du serveur
		setlocale(LC_ALL, $locale);
    	
    	$this->plop = $namefile;
    }
    
    public function log($mess){
        echo 'bwa', $mess, "\n";
        echo 'namefile', $this->namePOTFile, '<br />', PHP_EOL;
        
        $reflexNames = $this->getReflexiveInfo();
        
        echo 'appelant :', ' ligne=', $reflexNames['line'], ' function=',  $reflexNames['function'], ' class=', $reflexNames['class'], ' file=', $reflexNames['file'], '<br /><br />', PHP_EOL, PHP_EOL;
    }
    
    /**
     * Méthode retournant les informations de la méthode appellante initiale.
     * Permet de déterminer le fichier, la classe, la méthode/function et la ligne
     * relatif à l'appel initial ou est situé la demande de log.
     * 
     * @return array {'file' = null, 'class' = null, 'function' = null, 'line' = null}
     */
    private function getReflexiveInfo(){
        
        $filePath = null;
        $className = null;
        $functionName = null;
        $line = null;
        
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
                $line =  $trace[$traceCurseur]['line'];
                
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
        
        return array('file' => $filePath, 'class' => $className, 'function' => $functionName, 'line' => $line);
    }
    
    public function profilStart(){
       
        $time = microtime(true);
        
        $cellId = $this->getUniqueSignatureCall();
        
        //initialisation de la cellule
        if(! isset($this->tabProfiler[$cellId])){
            
            $this->tabProfiler[$cellId] = array();
            
        }
        
        array_push($this->tabProfiler[$cellId], $time);
        //Pile LIFO pour stoquer la date, en cas d'appels récursifs.

        echo "date début : $time", '<br />', PHP_EOL;
        
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
            
            echo "date fin : $time diff : $diff", '<br />', PHP_EOL;
            
        }
        
    }
    
    /**
     * Génère un identifiant unique en fonction des parametres appelants initiaux.
     * 
     * @return String l'identifiant unique.
     */
    private function getUniqueSignatureCall(){
        
        $reflexiveCallInfo = $this->getReflexiveInfo();
        
        //FIXME améliorer le systeme de génération d'identifiants uniques... Ya surement mieux possible !
        $callId = md5($reflexiveCallInfo['file'] . $reflexiveCallInfo['class'] . $reflexiveCallInfo['function']);
      
        return $callId;
        
    }
    
    
}

class LogLevel {
	
	private $severity;
	private $richFormat;
	private $potFormat;
	
	public function __construct($severity, $richFormat = null, $potFormat = null){
		
		
		$this->severity = $severity;
		
		if($potFormat == null){
			$this->potFormat = '%1s %2s - %3s -> %4s'; //format par défaut
		}
		
		if($richFormat == null){
			$this->richFormat = '<p style="color:black;">%1s %2s - %3s -> %4s</p>'; //format par défaut
		}

	}
	
	public function getRichFormat(){
		return $this->richFormat;
	}
	
	public function getPotFormat(){
		return $this->potFormat;
	}
	
	public function isWished($threshold){
		if($this->severity <= $threshold){
			return true;
		}
		
		return false;
	}
}


/**
* Classe contenant toutes les constantes statiques nécesssaires au fonctionnement de Log4GY.
*
* @version v0r0 (21 août 2011).
* @author Effy - Aurélien GY <http://www.aureliengy.com>.
*/
class LogC{
   
	/**
	 * Const définisant les différents niveaux de sévéritée de log
	 */
	const INFO 	= 10;	// Le plus verbeux - entrée / sortie dans une classe par exemple
	const DEBUG = 20;	// Résultat d'une opération, valeur d'une variable, nombre d'instance d'un objets, d'occurences dans la base... 
	const WARN 	= 30;	// Erreur faiblement impactante, tolérée dans l'application
	const ERROR = 40;	// Erreur fortement impactante (dans un try - catch par exemple
	const FATAL = 50;	// Erreur implicant l'impossibilitée de réaliser un action (session non disponible, droits fichiers inssuffisants, ...)
	const OFF 	= 0;	// Pour désactiver les logs temporairement sans avoir à envlever les messages (par exemple pour passer l'application en production)
	
	
	//TODO rajouter les implémentations de tous les appenders ca :)
	/**
	 * Const définissant les différents appenders (sorties) de log
	 */
	const APP_CONSOLE = 0; // Sortie echo
	//const APP_STDERR = 1; // Sortie std_err
	const APP_POTFILE = 2; // Sortie dans un fichier texte classique
	const APP_RICHFILE = 3; // Sortie dans un fichier htm, avec colorisation en fonction de la sortie.
	//const APP_BDD = 4; //Sortie dans une base de données
	//const APP_BROWSERCONSOLE = 5; // Sortie console navigateur
	
	/**
	 * Const générales de l'applications (regroupées pour être éventuellement modifiés par les utilisateurs du log
	 */
	const GENERAL_LOG_PATH = 'log';
	const POTFILE_PATH = 'potfile';
	const RICHFILE_PATH = 'richfile';
	
}

/**
 * Configuration des différents niveaux de sévérités gérés par l'application.
 */
$LogLevelInfo = new LogLevel(LogC::INFO, '<p style="color:blue;">%1s %2s - %3s -> %4s</p>'); 
$LogLevelDebug = new LogLevel(LogC::DEBUG, '<p style="color:green;">%1s %2s - %3s -> %4s</p>'); 
$LogLevelWarn = new LogLevel(LogC::WARN, '<p style="color:orange;">%1s %2s - %3s -> %4s</p>');
$LogLevelError = new LogLevel(LogC::ERROR, '<p style="color:red;">%1s %2s - %3s -> %4s</p>');
$LogLevelFatal = new LogLevel(LogC::FATAL, '<p style="color:darkred;">%1s %2s - %3s -> %4s</p>');

$logLevels = array($LogLevelInfo, $LogLevelDebug, $LogLevelWarn, $LogLevelError, $LogLevelFatal);

/**
 * Configuration de l'ensemble des loggs
 */

//TODO vois si ya pas moyen de chopper direct la locale du serveur, c'est ptet suffisant ...
Log::conf('fr_FR', LogC::OFF, $logLevels);
