# Introduction #

No real source code at this moment (project starting), but first raw files will be send soon.

# Some things about the autor (me :p) #

Yes, i'm a little 25 french man (and sexy, as all french are of course).
Sorry for my poor english, i make some effort and i promise to ask some english correction from smarter friends in the future, when this project will become more important. Same thing for my source code, actually in french to develop faster.

If you are wondering where come from this horrible project name "Log4GY", it's a joke between the Apache "Log4J" system and my family name "Gy" who is pronouced on the same way in french (J = GY). And Log4Php was already taken :P ...

# Short history #

I made some personal stuff in PHP when i was "young" like my website.
I'm a young engineer in computer sciences from France, and i work one year in an big international software company. During this year, i was involved on a lot of Java oriented developments (Struts, GWT, etc.) and i discover the log4J (Log for Java) system from the Apache organization.

When i come back to my first love for personal uses, PHP, i was terrify by the ugly mess to work with. Looking for "Java" equivalent tools, i found everything except a good logger. As i said before, Log4Php is awesome, but too Java oriented. If you don't believe me, just try to wonder why nobody is using it. So i decided to make my own log system, adapted to classical "anarchy" PHP developments.

# Details #

To make this logger, i have to do some "dirty" code (some of my old teachers should be crying right now :p), but hey it's for a noble cause : get a log system really easy to use.
However, i try to make the best code possible, considering the previous point.

# Performances #

Most part of this library will be based on the debug\_backtrace() php function, who provide nice reflexive informations (see http://php.net/manual/en/function.debug-backtrace.php). But i know that with this one the Log4GY system will probably be slowest than some other log system. But honestly, who really care about that? In my opinion, loggers are made to find bugs, develop stronger and faster during the development / deployment phases. In production, where performances really care, i'm agreed that is probably not the good tool.

But the real question is, is there really a good log system in production? I'm not really sure about this. At worst, log is useful at the beginning and must be disabled after you are sure there is no glitch in your developments. That's why Log4GY doesn't really care about this aspect and provide an easy way to totally turn of the system (without remove all your pretty logs from the source files, of course !).