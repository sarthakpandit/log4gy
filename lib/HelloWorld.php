<?php

class HelloWorld{
    
    private $message;
    
    public function __construct($message){
        Log::info("Début - HelloWorld->HelloWorld()", 11);
        $this->message = $message;
        Log::info("Fin - HelloWorld->HelloWorld()", 12);
        
    }
    
    public function doSpeack(){
        Log::info("Début - HelloWorld->doSpeack()", 21);
        
        echo 'Le message est : ', $this->message, "\n";
        
        Log::info("Fin - HelloWorld->doSpeack()", 22);
    }
    
}