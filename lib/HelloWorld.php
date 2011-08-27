<?php

class HelloWorld{
    
    private $message;
    
    public function __construct($message){
        
        Log::info("Début - HelloWorld->HelloWorld()");

        $this->message = $message;

        Log::info("Fin - HelloWorld->HelloWorld()");
        
    }
    
    public function doSpeack(){
        
        Log::info("Début - HelloWorld->doSpeack()");
        
        echo 'Le message est : ', $this->message, '<br />', PHP_EOL;
        
        Log::info("Fin - HelloWorld->doSpeack()");
        
    }
    
}