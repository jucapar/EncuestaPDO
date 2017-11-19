<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "config/configEncuesta.php";
try {
    //Creamos la conexion a la base de datos
    $db = new PDO(DATOSCONEXION, USER, PASSWORD);
    //Definición de los atributos para lanzar una excepcion si se produce un error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $PdoE) {
    //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
    echo($PdoE->getMessage());
    unset($db);
}

$fecha = date('d-m-Y H:i:s');
$consulta1 = "SELECT AVG(TIMESTAMPDIFF(YEAR, FechaNacimiento, CURDATE())) AS EdadMedia ,COUNT(*) AS NumeroParticipantes ,AVG(GradoSatisfaccion) AS MediaSatisfaccion, COUNT(DISTINCT IP) AS NumeroEquipos FROM Encuesta";
$sentencia1 = $db->prepare($consulta1);
$sentencia1->execute();
$resultadoConsulta1 = $sentencia1->fetch(PDO::FETCH_OBJ);

$consulta2 = "SELECT IP,COUNT(IP) AS NumeroEncuestas FROM Encuesta GROUP BY IP HAVING NumeroEncuestas > 1";
$sentencia2 = $db->prepare($consulta2);
$sentencia2->execute();

$consulta3 = "SELECT Nombre,Apellido1,Apellido2,Opiniones FROM Encuesta WHERE Opiniones != ''";
$sentencia3 = $db->prepare($consulta3);
$sentencia3->execute();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Seguimiento</title>
    </head>
    <body >

        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">
            <h2>RESULTADOS DE LA ENCUESTA</h2>
            <?php
            echo("<strong>Fecha y hora actuales:</strong>       $fecha<br /><br />");

            echo("<strong>Número de alumnos participantes:</strong>     $resultadoConsulta1->NumeroParticipantes<br /><br />");

            echo("<strong>Edad promedio:</strong>".round($resultadoConsulta1->EdadMedia,4,PHP_ROUND_HALF_UP)."<br /><br />");

            echo("<strong>Promedio de grado de satisfacción:</strong>".round($resultadoConsulta1->MediaSatisfaccion,4,PHP_ROUND_HALF_UP)."<br /><br />");

            echo("<strong>Número de equipos desde los que se ha realizado la encuesta:</strong>         $resultadoConsulta1->NumeroEquipos<br /><br />");

            echo "<strong>Dirección IP de los equipos desde los que se ha realizado la encuesta más de una vez:</strong><br /><br />";
            echo "<table class='w3-table-all' style='margin: 0 auto; width: 50%'><tr><th>Direccion IP</th><th>Numero de veces que ha realizado la encuesta</th></tr>";
            while ($resultadoConsulta2 = $sentencia2->fetch(PDO::FETCH_OBJ)) {
                echo "<tr>";
                echo "<td>$resultadoConsulta2->IP</td>";
                echo "<td>$resultadoConsulta2->NumeroEncuestas</td>";
                echo "</tr>";
            }
            echo "</table><br /><br />";

            echo "<strong>Listado de opiniones y sugerencias recibidas:</strong><br /><br />";
            echo "<table class='w3-table-all'><tr><th>Alumno</th><th>Opiniones</th></tr>";
            while ($resultadoConsulta3 = $sentencia3->fetch(PDO::FETCH_OBJ)) {
                echo "<tr>";
                echo "<td>$resultadoConsulta3->Nombre $resultadoConsulta3->Apellido1 $resultadoConsulta3->Apellido2 </td>";
                echo "<td>$resultadoConsulta3->Opiniones</td>";
                echo "</tr>";
            }
            ?>

        </div>
        <?php
        unset($db);
        ?>