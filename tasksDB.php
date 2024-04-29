<?php

try {
       $db = new PDO("mysql:host=localhost;dbname=test;charset=utf8mb4", "root", "");
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
    } 
catch( PDOException $ex) {
           die("DB Connect Error : " . $ex->getMessage()) ;
}

function getTask($id)
{
    global $db;
    $stmt=$db->prepare("SELECT*FROM todo WHERE id=?");
    $stmt->execute([$id]);

    return $stmt->fetch();
}