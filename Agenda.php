<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
</head>
<body>
    
    <!-- FORMULARIO INGRESO DATOS -->
    <center>
        <form method="post">
            <table style="text-align: center;">
                <tr>
                    <td colspan="2"><h1>INTRODUCIR ENTRADA</h1></td>
                </tr>
                <tr>
                    <td><label>Introduce el nombre</label></td>
                    <td><input type="text" name="nombre"></td>
                </tr>
                <tr>
                    <td><label>Introduce el correo electrónico</label></td>
                    <td><input type="text" name="correo"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="ENVIAR" style="margin-top: 10px;"></td>
                </tr>
            </table> 
            <!-- <input type="hidden" name="nombres" value="<?php  ?>"> -->
        </form>
    </center>

    <!-- CLASE AGENDA -->
    <?php
        class Agenda {
            // ATRIBUTO DE LA CLASE, ARRAY ASOCIATIVO (CLAVE: NOMBRE, VALOR: CORREO) DÓNDE GUARDAREMOS LAS ENTRADAS DE LA AGENDA
            private $entradas;

            // FUNCIÓN QUE AÑADE UNA ENTRADA A LA AGENDA - RECIBE UN NOMBRE Y UN CORREO POR PARÁMETROS
            public function anadirEntrada($nombre, $correo) {
                if (!$this->existeEntrada($nombre)) {
                   $entradas[$nombre] = $correo; 
                }
                else {
                    // MENSAJE ERROR
                }
            }

            // FUNCIÓN PRIVADA QUE COMPRUEBA SI EXISTE ALGUNA ENTRADA EN EL ARRAY CON LA CLAVE RECIBIDA POR PARÁMETRO
            private function existeEntrada($nombre) {
                if (array_key_exists($nombre, $this->entradas)) {
                    return true;
                }
                return false;
            }
        }
    ?>

    <!-- INSTANCIACIÓN Y LLAMADAS -->
    <?php
        $agenda = new Agenda();
    ?>

    <!-- TABLA MOSTRAR INFO -->
        <br>
        <center>
            <table style="text-align: center;">
                <tr>
                    <td>NOMBRE</td>
                    <td>CORREO</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </center>

</body>
</html>