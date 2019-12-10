<?php
$pid = pcntl_fork();

if($pid < 0){
    //something wrong
    exit;
}

if($pid > 0){
    //this is parent process.
    echo "child process start with pid $pid";
    exit;
}

posix_setsid();
