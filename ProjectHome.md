There is a lot of log system for PHP, but some are too limited in my opinion (only 'echo' output, or plain old text files); and some are really awesome like Log4PHP but really not developer friendly (hard to configure, too many code lines to implement the logger in your own code).

I decided to make the simplest logger possible for PHP developpers. No log instantiation, no stupid parameters like
```
$logger = new LogX();
$logger->logInfo("Start from the class MyClass line 37");
```

or (from Apache Log4Php 's documentation)

```
include('log4php/Logger.php'); 
// Effy : ok, this one is essential ! (without autoload)

Logger::configure('D:\Projects\apache\_playground\log4php.xml'); 
// Effy : really o___o ? in all the files ???

/**
 * This is a classic pattern: using one logger object per class.
 */
class Foo
{
    /** Holds the Logger. */
    private $log; 
// Effy : That is what I want to avoid ! Modify the Foo class just to log something is just ... Yerks !

    /** Logger is instantiated in the constructor. */
    public function __construct()
    {
        // The __CLASS__ constant holds the class name, in our case "Foo".
        // Therefore this creates a logger named "Foo" (which we configured in the config file)
        $this->log = Logger::getLogger(__CLASS__); 
// Effy : nice singleton, but... imo it pollute the source code for nothing as you will always pass the class name (or something similar) !

    }

    /** Logger can be used from any member method. */
    public function go()
    {
        $this->log->info("We have liftoff."); 
// Effy : $this->log->info... I prefer the static shorter user friendly style : Log::info("message"); !

    }
}

$foo = new Foo();
$foo->go();

//Effy : 5 codes lines to show 1 message + a lot of configuration to manage appender console, format, etc... what a lazzy process :'( !

```

They made a great job, it's the Log robocop system. But hey guys, PHP is not Java ! Use a system like this is an heresy because the most interest with PHP is to develop faster, most part of the time in small teams. I want to add, be able to modify the log system by yourself to adapt it to yours needs. I'm pretty sure that nobody has ever try to modify Log4Php sources files :/ ! Ok, It's true they don't need it as all is configurable, but for an heavy price :(.

In (my) Log4GY :

```
$x = 4;
Log::info("my message : $x"); //singleton instance in the Log core, totally transparent because who really care about this?!

//will print something like 'INFO - From [index.php->MyClass->MyFunction] - my message : 4.

//2 lines, including the require (1 if you use autoload process of course)
```

```
Log::start(); //because everybody always log for this too ! Okay it is not a standar, but it's so useful !!!

//will print something like 'INFO - From [index.php->MyClass->MyFunction] - Started at 17h45m48s4114. 

//some code there

Log::stop(); 
//will print something like 'INFO - From [index.php->MyClass->MyFunction] - Stoped at 17h45m58s4114 (TOTAL : 10s). 
```

And that's all !

All the lazy part will be hidden for developpers. You will not be able to make coffee with my log system, but i guess -as myself- you only want develop softwares ;)...