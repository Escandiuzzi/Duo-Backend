<?php

include 'db_connection.php';

function initializeDB($filename)
{
    $connection = OpenConnection();

    $templine = '';
    $lines = file($filename);

    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

        $templine .= $line;

        if (substr(trim($line), -1, 1) == ';') {
            mysqli_query($connection, $templine) or print('Error performing query \'<strong>' . $templine . '<br /><br />');
            $templine = '';
        }
    }

    CloseConnection($connection);
}

function getMaps()
{
    $connection = OpenConnection();

    $query =
        ("
            SELECT
                indicadores_respostas.resposta_text AS Title,
                COALESCE(
                    (
                        SELECT indicadores_respostas.resposta_text
                        FROM indicadores_respostas
                        LEFT JOIN indicadores_secoes_itens ON indicadores_secoes_itens.id = indicadores_respostas.id_secao_item
                        WHERE (indicadores_respostas.id_indicador = indicadores.id) AND (indicadores_secoes_itens.titulo = 'Latitude')
                    ),
                            (
                                SELECT cidades.latitude
                                FROM cidades
                                INNER JOIN indicadores_respostas
                                LEFT JOIN indicadores_secoes_itens ON indicadores_secoes_itens.id = indicadores_respostas.id_secao_item
                                WHERE (indicadores.id = indicadores_respostas.id_indicador) AND (cidades.cidades_id = indicadores_respostas.resposta_text) AND (indicadores_secoes_itens.titulo = 'Cidade')
                            )
                        ) AS Latitude,
                        COALESCE(
                            (
                                SELECT indicadores_respostas.resposta_text
                                FROM indicadores_respostas
                                LEFT JOIN indicadores_secoes_itens ON indicadores_secoes_itens.id = indicadores_respostas.id_secao_item
                                WHERE (indicadores_respostas.id_indicador = indicadores.id) AND (indicadores_secoes_itens.titulo = 'Longitude')
                            ),
                            (
                                SELECT cidades.longitude
                                FROM cidades
                                INNER JOIN indicadores_respostas
                                LEFT JOIN indicadores_secoes_itens ON indicadores_secoes_itens.id = indicadores_respostas.id_secao_item
                                WHERE (indicadores.id = indicadores_respostas.id_indicador) AND (cidades.cidades_id = indicadores_respostas.resposta_text) AND (indicadores_secoes_itens.titulo = 'Cidade')
                            )
                        ) AS Longitude
                    FROM indicadores
                        LEFT JOIN indicadores_respostas ON indicadores_respostas.id_indicador = indicadores.id
                        LEFT JOIN indicadores_secoes_itens ON indicadores_secoes_itens.id = indicadores_respostas.id_secao_item
                    WHERE (indicadores_secoes_itens.titulo = 'Nome da capacitação') OR (indicadores_secoes_itens.titulo = 'Instituição') ");

    $result_data = mysqli_query($connection, $query);
    $array = array();

    while ($row = mysqli_fetch_array($result_data)) 
        array_push($array, $row);

    mysqli_free_result($result_data);

    CloseConnection($connection);

    return ($array);
}
