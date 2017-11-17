<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <title>Encuesta</title>
    </head>
    <body>
        <div class="w3-container w3-light-blue" style="width:60%; margin:auto; padding: 40px; height: 100%;">

            <?php
            /*
              Autor: Juan Carlos Pastor Regueras
              Encuesta.php
              Fecha de modificacion: 17-11-2017
             */
            //Información de la base de datos. Host y nombre de la BD
            include "config/configEncuesta.php";
            try {
                //Creamos la conexion a la base de datos
                $db = new PDO(DATOSCONEXION, USER, PASSWORD);
                //Definición de los atributos para lanzar una excepcion si se produce un error
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Incluimos nuestra libreria de validacion
                include "librerias/LibreriaValidacionFormularios.php";

                // Constantes para los valores maximos y minimos
                define("MIN", 1);
                define("MAX", 3);

                // Array de errores, utilizado para mostrar el mensaje de error correspondiente al valor devuelto por la funcion de validacion
                $arrayErrores = array(" ", "No ha introducido ningun valor<br />", "El valor introducido no es valido<br />", "Tamaño minimo no valido<br />", "Tamaño maximo no valido<br />");

                //Variable de control, utilizada para saber si algun campo introducido es erroneo
                $error = false;

                // Variable que guardará el valor devuelto por las funciones de validacion
                $valida = 0;



                // Inicializamos todos los arrays
                $encuesta = array(
                    'Nombre' => '',
                    'Apellido1' => '',
                    'Apellido2' => '',
                    'DNI' => '',
                    'Telefono' => '',
                    'Email' => '',
                    'FechaNacimiento' => '',
                    'HorasEstudio' => '',
                    'GradoSatisfaccion' => '',
                    'Valoracion' => '',
                    'Opiniones' => '',
                    'IP' => '',
                );


                $erroresCampos = array(
                    'Nombre' => '',
                    'Apellido1' => '',
                    'Apellido2' => '',
                    'DNI' => '',
                    'Telefono' => '',
                    'Email' => '',
                    'FechaNacimiento' => '',
                    'HorasEstudio' => '',
                    'GradoSatisfaccion' => '',
                    'IP' => '',
                );


                if (filter_has_var(INPUT_POST, 'Enviar')) {//Si hemos pulsado el boton de Enviar
                    //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                    $valida = validarCadenaAlfabetica(limpiarCampos($_POST['Nombre']), 1, 50);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['Nombre'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['Nombre'] = limpiarCampos($_POST['Nombre']);
                    }


                    //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                    $valida = validarCadenaAlfabetica(limpiarCampos($_POST['Apellido1']), 1, 50);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['Apellido1'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['Apellido1'] = limpiarCampos($_POST['Apellido1']);
                    }


                    //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                    $valida = validarCadenaAlfabetica(limpiarCampos($_POST['Apellido2']), 1, 50);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['Apellido2'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['Apellido2'] = limpiarCampos($_POST['Apellido2']);
                    }
                    
                    //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                    $valida = validarDNI(limpiarCampos($_POST['DNI']));
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['DNI'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['DNI'] = limpiarCampos($_POST['DNI']);
                    }
                    
                    $valida = validarTelefono(limpiarCampos($_POST['Telefono']));
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['Telefono'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['Telefono'] = limpiarCampos($_POST['Telefono']);
                    }
                    
                    $valida = validarEmail(limpiarCampos($_POST['Email']));
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['Email'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['Email'] = limpiarCampos($_POST['Email']);
                    }
                    
                    $valida = validarCadenaAlfabetica(limpiarCampos($_POST['FechaNacimiento']));
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['FechaNacimiento'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['FechaNacimiento'] = limpiarCampos($_POST['FechaNacimiento']);
                    }
                    
                    $valida = validarEntero(limpiarCampos($_POST['HorasEstudio']),0,8);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['HorasEstudio'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $encuesta['HorasEstudio'] = limpiarCampos($_POST['HorasEstudio']);
                    }
                   
                    if(!empty($_POST['GradoSatisfaccion'])){
                        $encuesta['GradoSatisfaccion'] = limpiarCampos($_POST['HorasEstudio']);
                    }
                    
                     $valida = validarEntero(limpiarCampos($_POST['Valoracion']),0,10);
                    //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                    if ($valida != 0) {
                        //Asignamos el error producido al valor correspondiente en el array de errores
                        $erroresCampos['Valoracion'] = $arrayErrores[$valida];
                        //Como ha habido un error, la variable de control $error toma el valor true
                        $error = true;
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    } else {
                        //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                        $departamento['Valoracion'] = limpiarCampos($_POST['Valoracion']);
                    }
                    
                     if(!empty($_POST['Opiniones'])){
                        $encuesta['GradoSatisfaccion'] = limpiarCampos($_POST['GradoSatisfaccion']);
                     }
                    
                }
                //Si no hemos pulsado el boton, o ha habido un error en la validacion mostrarmos el formulario
                if (!filter_has_var(INPUT_POST, 'Enviar') || $error) {
                    ?>
                    <form  action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">

                        <label for="Nombre">Nombre del alumno(*):</label><br />
                        <br /><input type="text" name="Nombre" value="<?php echo $encuesta['Nombre']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['Nombre']; ?>

                        <label for="Apellido1">Primer Apellido del alumno(*):</label><br />
                        <br /><input type="text" name="Apellido1" value="<?php echo $encuesta['Apellido1']; ?>"><br /><br />
        <?PHP echo $erroresCampos['Apellido1']; ?>

                        <label for="Apellido2">Segundo Apellido del alumno(*):</label><br />
                        <br /><input type="text" name="Apellido2" value="<?php echo $encuesta['Apellido2']; ?>"><br /><br />
        <?PHP echo $erroresCampos['Apellido2']; ?>

                        <label for="DNI">DNI del alumno(*):</label><br />
                        <br /><input type="text" name="DNI" value="<?php echo $encuesta['DNI']; ?>"><br /><br />
        <?PHP echo $erroresCampos['DNI']; ?>

                        <label for="Telefono">Telefono del alumno(*):</label><br />
                        <br /><input type="text" name="Telefono" value="<?php echo $encuesta['Telefono']; ?>"><br /><br />
        <?PHP echo $erroresCampos['Telefono']; ?>

                        <label for="Email">Email del alumno(*):</label><br />
                        <br /><input type="email" name="Email" value="<?php echo $encuesta['Email']; ?>"><br /><br />
        <?PHP echo $erroresCampos['Email']; ?>
                        
                        <label for="FechaNacimiento">Fecha de nacimiento del alumno(*):</label><br />
                        <br /><input type="date" name="FechaNacimiento" value="<?php echo $encuesta['FechaNacimiento']; ?>"><br /><br />
        <?PHP echo $erroresCampos['FechaNacimiento']; ?>

                        <label for="HorasEstudio">Numero de horas de estudio diarias [0-8]: (* y entero)</label><br />
                        <br /><input type="number" name="HorasEstudio" value="<?php echo $encuesta['HorasEstudio']; ?>"><br /><br />
        <?PHP echo $erroresCampos['GradoSatisfaccion']; ?>

                        <label for="GradoSatisfaccion">Grado de satisfacción [0-10]: (* y entero)</label><br />
                        <br /><input type="number" name="GradoSatisfaccion" value="<?php echo $encuesta['GradoSatisfaccion']; ?>"><br /><br />
        <?PHP echo $erroresCampos['GradoSatisfaccion']; ?>

                        <label for="Valoracion">Valoración de los materiales entregados por el profesor:</label><br />
                        <input type="radio" name="Valoracion" value="Muy Malos"> Muy Malos<br />
                        <input type="radio" name="Valoracion" value="Muy mejorables"> Muy mejorables<br />
                        <input type="radio" name="Valoracion" value="Regulares">Regulares<br /> 
                        <input type="radio" name="Valoracion" value="Buenos">Buenos<br />
                        <input type="radio" name="Valoracion" value="Muy buenos">Muy buenos<br />   


                        <label for="Opiniones">Opiniones y sugerencias para mejorar los resultados:</label><br />
                        <textarea cols="20" rows ="10" name="Opiniones" form="formulario1"><?php echo $encuesta['Opiniones']; ?></textarea>
                        <br /> 
                        <input type="submit" name="Enviar" value="Enviar">
                    </form>
                </div>

        <?PHP
    } else {
        
        foreach($encuesta as $valor){
            echo $valor."<br />";
        }
        unset($db);
    }
} catch (PDOException $PdoE) {
    //Capturamos la excepcion en caso de que se produzca un error,mostramos el mensaje de error y deshacemos la conexion
    echo($PdoE->getMessage());
    unset($db);
}
?>


    </body>
</html>
