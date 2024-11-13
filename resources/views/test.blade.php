<?php
App::bind('LoggerInterface', 'FileLogger');
interface LoggerInterface
{
    function info($message);
}

class StandardLogger implements LoggerInterface
{

    public function info($message)
    {
        printf("[INFO] %s \n", $message);
    }
}

class FileLogger implements LoggerInterface
{

    public function info($message)
    {
        file_put_contents('app.log', sprintf("[INFO] %s \n", $message), FILE_APPEND);
    }
}
class ExceptionLog
{
    public $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function info(\Exception $ex)
    {
        return $this->logger->info($ex->getMessage());
    }
}

class MyLog
{
    public $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function info($string)
    {
        return $this->logger->info($string);
    }
}
// Print to standard input/output device
//$myLog = new MyLog(new StandardLogger);
//$myLog->info('This object depend on another object');
//// Write to file
//$myFileLog = new MyLog(new FileLogger);
//$myFileLog->info('This object depend on another object');
$myLog = App::make('MyLog');
$myLog->info('This object depend on another object');

//Contextual binding
App::when('MyLog')
    ->needs('LoggerInterface')
    ->give('StandardLogger');

App::when('ExceptionLog')
    ->needs('LoggerInterface')
    ->give('FileLogger');