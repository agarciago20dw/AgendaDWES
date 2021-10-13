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

                <!-- CLASE AGENDA -->
                <?php
                    class Agenda {
                        // ATRIBUTO DE LA CLASE, ARRAY ASOCIATIVO (CLAVE: NOMBRE, VALOR: CORREO) DÓNDE GUARDAREMOS LAS ENTRADAS DE LA AGENDA
                        private $entradas = [];

                        // FUNCIÓN QUE AÑADE UNA ENTRADA A LA AGENDA - RECIBE UN NOMBRE Y UN CORREO POR PARÁMETROS
                        public function anadirEntrada($nombre, $correo) {
                            // echo "<br>NOMBRE: " . $nombre . " | Correo: " . $correo . "<br>";
                            if ($this->nombreVacio($nombre)) {
                                echo "<br>EL NOMBRE ESTÁ VACÍO";
                            }
                            else if (!$this->existeEntrada($nombre) && $this->correoValido($correo)) {
                                $this->entradas[$nombre] = $correo;
                            }
                            else if ($this->existeEntrada($nombre) && $this->correoValido($correo)) {
                                $this->entradas[$nombre] = $correo;
                            }
                            else if ($this->existeEntrada($nombre) && $this->correoVacio($correo)) {
                                unset($this->entradas[$nombre]);
                            }
                            else {
                                echo "<br>EL CORREO NO ES VÁLIDO";
                            }
                        }

                        private function nombreVacio($nombre) {
                            if ($nombre == "") {
                                return true;
                            }
                            return false;
                        }

                        // FUNCIÓN PRIVADA QUE COMPRUEBA SI EXISTE ALGUNA ENTRADA EN EL ARRAY CON LA CLAVE RECIBIDA POR PARÁMETRO
                        private function existeEntrada($nombre) {
                            if (array_key_exists($nombre, $this->entradas)) {
                                return true;
                            }
                            return false;
                        }

                        private function correoValido($correo) {
                            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                                return true;
                            }
                            return false;
                        }

                        private function correoVacio($correo) {
                            if ($correo == "") {
                                return true;
                            }
                            return false;
                        }

                        public function mostrarEntradas() {
                            $cod_html = "<tr><td colspan='2'><table border='1' style='width: 100%; text-align: center; margin-top: 20px;'>";
                            $cod_html .= "<tr><td>NOMBRE</td><td>CORREO</td></tr>";

                            foreach ($this->entradas as $nombre => $correo) {
                                $cod_html .= "<tr><td style='padding: 5px;'>" . $nombre . "</td><td style='padding: 5px;'>" . $correo . "</td></tr>";
                            }

                            $cod_html .= "</table></td></tr>";
                            echo $cod_html;
                        }

                        public function getEntradas() {
                            return $this->entradas;
                        }
                    }
                ?>

                <!-- INSTANCIACIÓN Y LLAMADAS -->
                <?php
                    $nombres = [];
                    $correos = [];
                    $agenda = new Agenda();

                    // SI LOS INPUT HIDDEN HAN ENVIADO LOS ARRAYS FORMATEADOS LOS DESFORMATEAMOS Y LOS REASIGNAMOS, DESPUÉS AÑADIMOS LAS ENTRADAS A LA AGENDA
                    if (isset($_POST['nombres']) && isset($_POST['correos'])) {
                        $nombres = explode(",", $_POST['nombres']);
                        $correos = explode(",", $_POST['correos']);

                        for ($i = 0; $i < count($nombres); $i++) {
                            if ($i != 0) {
                                $agenda->anadirEntrada($nombres[$i], $correos[$i]);
                            }
                        }
                    }

                    // SI SE HAN ENVIADO LOS INPUT 'nombre' Y 'correo' ACTUALIZAMOS LOS ARRAYS DE NOMBRES Y CORREOS Y AÑADIMOS LA ENTRADA
                    if (isset($_POST['nombre']) && isset($_POST['correo'])) {
                        $nombre = $_POST['nombre'];
                        $correo = $_POST['correo'];
                        array_push($nombres, $nombre);
                        array_push($correos, $correo);
                        $agenda->anadirEntrada($nombre, $correo);

                        // ACTUALIZAMOS LOS NOMBRES Y LOS CORREOS
                        $entradas = $agenda->getEntradas();
                        foreach ($entradas as $nombre => $correo) {
                            array_push($nombres, $nombre);
                            array_push($correos, $correo);
                        }
                    }

                    // MOSTRAMOS LAS ENTRADAS
                    $agenda->mostrarEntradas();
    
                ?>

                <tr>
                    <td>
                        <input type="hidden" name="nombres" value="<?php if (!empty($nombres)) { echo implode(",", $nombres); } ?>">
                        <input type="hidden" name="correos" value="<?php if (!empty($correos)) { echo implode(",", $correos); } ?>">
                    </td>
                </tr>
            </table> 
        </form>
    </center>

</body>
</html>