<?php
    // This file contains a collection of functions that can be used throughout the project

    require_once (dirname(__FILE__)."/../dbconfig.php");


    // Creates a new PDO object and sets error reporting, then returns object.
    function pdo_construct()
    {
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    // Takes PDO object and sql query and returns the resuling data
    function pdo_query($pdo, $sql)
    {
        $result = $pdo->query($sql);
        return $result;
    }

    // Takes PDO object and valid sql query and returns affected rows
    function pdo_exec($pdo, $sql)
    {
        return $pdo->exec($sql);
    }

    //sanitizes data before insertion
    function clean_data($data){
        $data = trim($data);
        $data = stripslashes($data);
        
        return $data;
    }
?>