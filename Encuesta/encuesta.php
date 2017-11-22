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
            <h3>ENCUESTA  INDIVIDUAL DE VALORACIÓN DE LA ASIGNATURA DESARROLLO DE APLICACIONES WEB EN ENTORNO SERVIDOR</h3>
            <?php
            /*
              Autor: Juan Carlos Pastor Regueras
              Encuesta.php
              Fecha de modificacion: 22-11-2017
             */
            
            //Incluimos el fichero de configuracion
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
            //Incluimos nuestra libreria de validacion
            include "librerias/LibreriaValidacionFormularios.php";
            // Constantes para los valores maximos y minimos
            define("MIN", 1);
            define("MAX", 3);
            // Array de errores, utilizado para mostrar el mensaje de error correspondiente al valor devuelto por la funcion de validacion
            $arrayErrores = array(" ", "<p style='color:red;'><strong>No ha introducido ningun valor</strong></p>", "<p style='color:red;'><strong>El valor introducido no es valido</strong></p>", "<p style='color:red;'><strong>Tamaño minimo no valido</strong></p>", "<p style='color:red;'><strong>Tamaño maximo no valido</strong></p><");
            //Variable de control, utilizada para saber si algun campo introducido es erroneo
            $error = false;
            // Variable que guardará el valor devuelto por las funciones de validacion
            $valida = 0;
            // Inicializamos todos los arrays
            //Este array guardará los datos insertados una vez sean validados
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
            //En este array se guardaran los errores generados durante la validacion para mostrarselo al usuario
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
            //Este array nos sirve para dejar en checked el elemento de grado de satisfaccion elegido
            $arrayGradoSatisfacion = array(
                'Muy Malos' => '',
                'Muy Mejorables' => '',
                'Regulares' => '',
                'Buenos' => '',
                'Muy Buenos'
            );
            if (filter_has_var(INPUT_POST, 'Enviar')) {//Si hemos pulsado el boton de Enviar
            
                 //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                $valida = validarDNI(limpiarCampos(strtoupper($_POST['DNI'])));
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['DNI'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $consultaDNI = "SELECT * FROM Encuesta WHERE DNI = :DNI";
                    //Guardamos el DNI recibido en una variable
                    $DNITemp = limpiarCampos(strtoupper($_POST['DNI']));
                    //Preparamos la consulta
                    $sentencia = $db->prepare($consultaDNI);
                    //Inyectamos los parametros
                    $sentencia->bindParam(':DNI',$DNITemp);
                    try {
                        //Ejecutamos la consulta
                        $sentencia->execute();
                        //Si la consulta nos devuelve algun resultado significa que ya tenemos una encuesta rellenada con ese DNI
                        if($sentencia->rowCount() != 0){
                            //Asignamos el error y ponemos error a true
                            $erroresCampos['DNI'] = "<p style='color:red;'><strong>Ya existe una encuesta con este DNI</strong></p>";
                            $error = true;
                        }
                        else{
                            //Si no asignamos el campo del DNI a nuestro array de encuesta
                            $encuesta['DNI'] = limpiarCampos(strtoupper($_POST['DNI']));
                        }
                    }
                    catch(PDOException $PdoE){
                        echo "Error al realizar la consulta";
                        unset($db);
                    }
                            
                }
                
                //Ejecutamos la funcion de validacion y recogemos el valor devuelto
                $valida = validarCadenaAlfabetica(limpiarCampos($_POST['Nombre']), 1, 50);
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['Nombre'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                    
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
                  
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $encuesta['Apellido2'] = limpiarCampos($_POST['Apellido2']);
                }
                $valida = validarTelefono(limpiarCampos($_POST['Telefono']));
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['Telefono'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                    
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
                   
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $encuesta['Email'] = limpiarCampos($_POST['Email']);
                }
                $valida = validarFecha(limpiarCampos($_POST['FechaNacimiento']));
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['FechaNacimiento'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                   
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $encuesta['FechaNacimiento'] = limpiarCampos($_POST['FechaNacimiento']);
                }
                $valida = validarEntero(limpiarCampos($_POST['HorasEstudio']), 0, 8);
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0 && $valida != 1) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['HorasEstudio'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                   
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $encuesta['HorasEstudio'] = limpiarCampos($_POST['HorasEstudio']);
                }
                $valida = validarEntero(limpiarCampos($_POST['GradoSatisfaccion']), 0, 10);
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0 && $valida != 1) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['GradoSatisfaccion'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                   
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $encuesta['GradoSatisfaccion'] = limpiarCampos($_POST['GradoSatisfaccion']);
                }
                if (!empty($_POST['Valoracion'])) {
                    $encuesta['Valoracion'] = limpiarCampos($_POST['Valoracion']);
                    $arrayErrores[$_POST['Valoracion']] = "checked";
                }
                $valida = validarCadenaAlfanumerica(limpiarCampos($_POST['Opiniones']), 0, 250);
                //Si el valor es distinto de 0 ha habido un error y procedemos a tratarlo
                if ($valida != 0 && $valida != 1) {
                    //Asignamos el error producido al valor correspondiente en el array de errores
                    $erroresCampos['Opiniones'] = $arrayErrores[$valida];
                    //Como ha habido un error, la variable de control $error toma el valor true
                    $error = true;
                   
                } else {
                    //Si no ha habido ningun error, guardamos el valor enviado en el array de cuestionario
                    $encuesta['Opiniones'] = limpiarCampos($_POST['Opiniones']);
                }
            }
            //Si no hemos pulsado el boton, o ha habido un error en la validacion mostrarmos el formulario
            if (!filter_has_var(INPUT_POST, 'Enviar') || $error) {
                ?>
                <form class="w3-container" id="formulario1" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">

                    
                    <div style="float:left; width:40%;">
                    <label for="DNI">DNI del alumno(*):</label><br />
                    <br /><input  class="w3-input" type="text" name="DNI"  value="<?php echo $encuesta['DNI']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['DNI']; ?>
                    </div>
                    
                    <div style="float:right; width:40%;">
                    <label for="Nombre">Nombre del alumno(*):</label><br />
                    <br /><input class="w3-input" type="text" name="Nombre" value="<?php echo $encuesta['Nombre']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['Nombre']; ?>
                    </div>
                   
                    <div style="float:left; width:40%;">
                    <label for="Apellido1">Primer Apellido del alumno(*):</label><br />
                    <br /><input class="w3-input" type="text" name="Apellido1"   value="<?php echo $encuesta['Apellido1']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['Apellido1']; ?>
                    </div>

                    <div style="float:right; width:40%;">
                    <label for="Apellido2">Segundo Apellido del alumno(*):</label><br />
                    <br /><input class="w3-input" type="text" name="Apellido2"  value="<?php echo $encuesta['Apellido2']; ?>" ><br /><br />
                    <?PHP echo $erroresCampos['Apellido2']; ?>
                    </div>

                    <div style="float:left; width:40%;">
                    <label for="Telefono">Telefono del alumno(*):</label><br />
                    <br /><input class="w3-input" type="text" name="Telefono"  value="<?php echo $encuesta['Telefono']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['Telefono']; ?>
                    </div>

                    <div style="float:right; width:40%;">
                    <label for="Email">Email del alumno(*):</label><br />
                    <br /><input class="w3-input" type="email" name="Email"  value="<?php echo $encuesta['Email']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['Email']; ?>
                    </div>

                    <div style="float:left; width:40%;">
                    <label for="FechaNacimiento">Fecha de nacimiento del alumno(*):</label><br />
                    <br /><input class="w3-input" type="date" name="FechaNacimiento"  value="<?php echo $encuesta['FechaNacimiento']; ?>"><br /><br />
                    <?PHP echo $erroresCampos['FechaNacimiento']; ?>
                    </div>

                    <div style="float:right; width:40%;">
                    <label for="HorasEstudio">Horas de estudio diarias [0-8]: (*)</label><br />
                    <br /><input class="w3-input" type="number" name="HorasEstudio" style="width: 20%;" value="<?php echo $encuesta['HorasEstudio']; ?>" min="0" max="8"><br /><br />
                    <?PHP echo $erroresCampos['GradoSatisfaccion']; ?>
                    </div>
                    
                    <div style="float:left; width:40%;">
                    <label for="GradoSatisfaccion">Grado de satisfacción [0-10]: (*)</label><br />
                    <br /><input  class="w3-input" type="number" name="GradoSatisfaccion" style="width: 20%;"  value="<?php echo $encuesta['GradoSatisfaccion']; ?>" min="0" max="10"><br /><br />
                    <?PHP echo $erroresCampos['GradoSatisfaccion']; ?>
                    </div>
                    
                    <div style="margin: 0 auto;width:50%;clear:both" >
                        <label for="Valoracion">Valoración de los materiales entregados por el profesor:</label><br /><br />
                        <input type="radio" name="Valoracion" value="Muy Malos">   Muy Malos<br />
                        <input type="radio" name="Valoracion" value="Muy mejorables">   Muy mejorables<br />
                        <input type="radio" name="Valoracion" value="Regulares">   Regulares<br /> 
                        <input type="radio" name="Valoracion" value="Buenos">   Buenos<br />
                        <input type="radio" name="Valoracion" value="Muy buenos">   Muy buenos<br />   
                    </div>
                    <br /> 
                    <label for="Opiniones">Opiniones y sugerencias para mejorar los resultados:</label><br /><br />
                    <textarea  class="w3-input" cols="20" rows ="10" name="Opiniones" form="formulario1"><?php echo $encuesta['Opiniones']; ?></textarea>
                    <br /> 
                    <input class="w3-input" style="width: 10%; float:left; margin:0px 20px 20px 0px;" type="submit" name="Enviar" value="Enviar">
                    <input class="w3-input" style="width: 10%; float:right; margin:0px 0px 20px 20px;" type="button" onclick="location.href = 'index.php'" name="Volver" value="Volver">
                </form>
            

            <?PHP
        } else {
            //Guardamos la IP en el array de encuesta
            $encuesta['IP'] = $_SERVER['REMOTE_ADDR'];
            //Creamos la consulta
            $consulta = "INSERT INTO Encuesta (DNI,Nombre,Apellido1,Apellido2,Telefono,Email,FechaNacimiento,HorasEstudio,GradoSatisfaccion,Valoracion,Opiniones,IP) VALUES (:DNI,:Nombre,:Apellido1,:Apellido2,:Telefono,:Email,:FechaNacimiento,:HorasEstudio,:GradoSatisfaccion,:Valoracion,:Opiniones,:IP)";
            //Preparamos la consulta
            $sentencia = $db->prepare($consulta);
            //Inyectamos los parametros
            $sentencia->bindParam(':Nombre', $encuesta['Nombre']);
            $sentencia->bindParam(':Apellido1', $encuesta['Apellido1']);
            $sentencia->bindParam(':Apellido2', $encuesta['Apellido2']);
            $sentencia->bindParam(':DNI', $encuesta['DNI']);
            $sentencia->bindParam(':Telefono', $encuesta['Telefono']);
            $sentencia->bindParam(':Email', $encuesta['Email']);
            $sentencia->bindParam(':FechaNacimiento', $encuesta['FechaNacimiento']);
            $sentencia->bindParam(':HorasEstudio', $encuesta['HorasEstudio']);
            $sentencia->bindParam(':GradoSatisfaccion', $encuesta['GradoSatisfaccion']);
            $sentencia->bindParam(':Valoracion', $encuesta['Valoracion']);
            $sentencia->bindParam(':Opiniones', $encuesta['Opiniones']);
            $sentencia->bindParam(':IP', $encuesta['IP']);
            try {
                //Ejecutamos la consulta, si todo es correcto nos redirecciona al index
                $sentencia->execute();
                header("Location: index.php");
            } catch (PDOException $PdoE) {
                //Si hay algun error, se lo mostramos al usuario y cerramos la conexion a la BD
                echo("<p><strong>Error al crear la encuesta</strong></p>");
               
                unset($db);
            }
            //Cerramos la conexion a la BD
            unset($db);
        }
        include 'pie.php';
        ?>

    </div>    
    </body>
</html>