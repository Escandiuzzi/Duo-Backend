<?php

include 'db_service.php';

$filename = 'duo-teste.sql';
initializeDB($filename);

function retorno_maps()
{
    return getMaps();
}

print_r(retorno_maps());
