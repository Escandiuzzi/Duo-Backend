<?php

$filename = 'duo-teste.sql';


function OpenConnection()
{
    $dbhost  = 'localhost';
    $dbuser  = 'root';
    $dbpass  = '';
    $db  = 'dump';

    $connection = new mysqli($dbhost, $dbuser, $dbpass, $db);

    return $connection;
}

function CloseConnection($connection)
{
    $connection->close();
}
