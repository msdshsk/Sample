<?php

namespace Shsk\Log;

interface LoggerInterface
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;
    
    public function bufferStart($name);
    public function bufferStop($name);
    public function emergency(string|\Stringable $message, array $context = []);
    public function alert(string|\Stringable $message, array $context = []);
    public function critical(string|\Stringable $message, array $context = []);
    public function error(string|\Stringable $message, array $context = []);
    public function warning(string|\Stringable $message, array $context = []);
    public function notice(string|\Stringable $message, array $context = []);
    public function info(string|\Stringable $message, array $context = []);
    public function debug(string|\Stringable $message, array $context = []);
    public function log($level, string|\Stringable $message, array $context = []);
}
