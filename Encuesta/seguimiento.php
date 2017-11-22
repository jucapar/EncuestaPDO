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

//Generamos la fecha actual
$fecha = date('d-m-Y H:i:s');
//Creamos la consulta, esta consulta nos devolverá
//- Media de edad
//- Numero de encuestas realizadas
//- Media de grado satisfaccion
//- Media de horas de estudio diarias
//- Numero de equipos distintos desde el que se ha realizado la encuesta
$consulta1 = "SELECT AVG(TIMESTAMPDIFF(YEAR, FechaNacimiento, CURDATE())) AS EdadMedia ,COUNT(*) AS NumeroParticipantes ,AVG(GradoSatisfaccion) AS MediaSatisfaccion,AVG(HorasEstudio) AS MediaHorasEstudio, COUNT(DISTINCT IP) AS NumeroEquipos FROM Encuesta";
//Preparamos la consulta
$sentencia1 = $db->prepare($consulta1);
//Ejecutamos la consulta
try {
    $sentencia1->execute();
} catch (PDOException $PdoE) {
    //Si ha habido un error mostrar el mensaje de error y cerramos la conexion
    echo "Error al ejecutar la consulta 1";
    unset($db);
}

//Guardamos el resultado como un objeto
$resultadoConsulta1 = $sentencia1->fetch(PDO::FETCH_OBJ);

//Creamos la segunda consulta, esta consulta nos devolverá
//- IP y numero de encuestas realizadas desde esa IP si se ha realizado mas de una encuesta desde esa IP
$consulta2 = "SELECT IP,COUNT(IP) AS NumeroEncuestas FROM Encuesta GROUP BY IP HAVING NumeroEncuestas > 1";
//Preparamos la consulta
$sentencia2 = $db->prepare($consulta2);
//Ejecutamos la consulta
try {
    $sentencia2->execute();
} catch (PDOException $PdoE) {
    //Si ha habido un error mostrar el mensaje de error y cerramos la conexion
    echo "Error al ejecutar la consulta 2";
    unset($db);
}

//Creamos la tercera consulta, esta consulta nos devolverá
//- Nombre y Apellidos del encuesta y su opinion siempre que no sea vacia
$consulta3 = "SELECT Nombre,Apellido1,Apellido2,Opiniones FROM Encuesta WHERE Opiniones != ''";
//Preparamos la consulta
$sentencia3 = $db->prepare($consulta3);
//Ejecutamos
try {
    $sentencia3->execute();
} catch (PDOException $PdoE) {
    //Si ha habido un error mostrar el mensaje de error y cerramos la conexion
    echo "Error al ejecutar la consulta 3";
    unset($db);
}
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
            //Mostramos la fecha y hora
            echo("<strong>Fecha y hora actuales:</strong>       $fecha<br /><br />");

            //Mostramos el numero de participantes
            echo("<strong>Número de alumnos participantes:</strong> $resultadoConsulta1->NumeroParticipantes<br /><br />");

            //Mostramos la edad promedio
            echo("<strong>Edad promedio:</strong> " . round($resultadoConsulta1->EdadMedia, 2, PHP_ROUND_HALF_UP) . "<br /><br />");

            //Mostramos el grado de satisfaccion promedio
            echo("<strong>Promedio de grado de satisfacción:</strong> " . round($resultadoConsulta1->MediaSatisfaccion, 2, PHP_ROUND_HALF_UP) . "<br /><br />");

            //Mostramos las horas de estudio promedio
            echo("<strong>Promedio de horas de estudio:</strong> " . round($resultadoConsulta1->MediaHorasEstudio, 2, PHP_ROUND_HALF_UP) . "<br /><br />");

            //Mostramos el numero de equipos distintos desde el que sea ha realizado la encuesta
            echo("<strong>Número de equipos desde los que se ha realizado la encuesta:</strong>         $resultadoConsulta1->NumeroEquipos<br /><br />");

            //Devolvemos los resultados de la consulta2, recogemos las filas como un objeto y avanzamos el puntero
            echo "<strong>Dirección IP de los equipos desde los que se ha realizado la encuesta más de una vez:</strong><br /><br />";
            echo "<table class='w3-table-all' style='margin: 0 auto; width: 50%'><tr><th>Direccion IP</th><th>Numero de veces que ha realizado la encuesta</th></tr>";
            while ($resultadoConsulta2 = $sentencia2->fetch(PDO::FETCH_OBJ)) {
                echo "<tr>";
                echo "<td>$resultadoConsulta2->IP</td>";
                echo "<td>$resultadoConsulta2->NumeroEncuestas</td>";
                echo "</tr>";
            }
            echo "</table><br /><br />";

            //Devolvemos los resultados de la consulta3, recogemos las filas como un objeto y avanzamos el puntero
            echo "<strong>Listado de opiniones y sugerencias recibidas:</strong><br /><br />";
            echo "<table class='w3-table-all'><tr><th>Alumno</th><th>Opiniones</th></tr>";
            while ($resultadoConsulta3 = $sentencia3->fetch(PDO::FETCH_OBJ)) {
                echo "<tr>";
                echo "<td>$resultadoConsulta3->Nombre $resultadoConsulta3->Apellido1 $resultadoConsulta3->Apellido2 </td>";
                echo "<td>$resultadoConsulta3->Opiniones</td>";
                echo "</tr>";
            }
            echo "</table><br /><br />";
            ?>
            <input style="margin:0 auto; width: 10%;" class="w3-input" type="button" onclick="location.href = 'index.php'" name="Volver" value="Volver">

            <footer>
                <?php include 'pie.php'; ?>
            </footer>
        </div>
        <?php
        unset($db);
        ?>