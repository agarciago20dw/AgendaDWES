<!-- CLASE AGENDA -->
<?php
    class Agenda {
        // ATRIBUTO DE LA CLASE, ARRAY ASOCIATIVO (CLAVE: NOMBRE, VALOR: CORREO) DÓNDE GUARDAREMOS LAS ENTRADAS DE LA AGENDA
        private $entradas = [];

        // FUNCIÓN QUE AÑADE UNA ENTRADA A LA AGENDA - RECIBE UN NOMBRE Y UN CORREO POR PARÁMETROS
        public function anadirEntrada($nombre, $correo) {
            $nombre = $this->formatearNombre($nombre);
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

        private function formatearNombre($nombre) {
            $nombre = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $nombre);
            $nombre = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $nombre);
            $nombre = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $nombre);
            $nombre = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $nombre);
            $nombre = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $nombre );

            $nombre_formateado = strtolower($nombre);
            return $nombre_formateado;
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
            $cod_html = "<tr><td colspan='2' style='padding-top: 20px;'><table border='1' style='width: 100%; text-align: center; margin-top: 20px;'>";
            $cod_html .= "<tr><td>NOMBRE</td><td>CORREO</td></tr>";

            foreach ($this->entradas as $nombre => $correo) {
                $cod_html .= "<tr><td style='padding: 5px;'>" . $nombre . "</td><td style='padding: 5px;'>" . $correo . "</td></tr>";
            }

            $cod_html .= "</table></td></tr>";
            echo $cod_html;
        }

        public function getNombres() {
            return array_keys($this->entradas);
        }

        public function getCorreos() {
            return array_values($this->entradas);
        }
    }

    // INSTANCIACIÓN Y LLAMADAS
    $nombres = [];
    $correos = [];
    $agenda = new Agenda();

    // SI LOS INPUT HIDDEN HAN ENVIADO LOS ARRAYS FORMATEADOS LOS DESFORMATEAMOS Y LOS REASIGNAMOS, DESPUÉS AÑADIMOS LAS ENTRADAS A LA AGENDA
    if (isset($_COOKIE['nombres']) && isset($_COOKIE['correos'])) {
        $nombres = explode(",", $_COOKIE['nombres']);
        $correos = explode(",", $_COOKIE['correos']);

        for ($i = 0; $i < count($nombres); $i++) {
            $agenda->anadirEntrada($nombres[$i], $correos[$i]);
        }
    }

    // SI SE HAN ENVIADO LOS INPUT 'nombre' Y 'correo' ACTUALIZAMOS LOS ARRAYS DE NOMBRES Y CORREOS Y AÑADIMOS LA ENTRADA
    if (isset($_POST['nombre']) && isset($_POST['correo'])) {
        // ELIMINAMOS POSIBLES ETIQUETAS HTML
        $nombre = strip_tags($_POST['nombre']);
        $correo = strip_tags($_POST['correo']);

        $agenda->anadirEntrada($nombre, $correo);

        // ACTUALIZAMOS LOS NOMBRES Y LOS CORREOS
        $nombres = $agenda->getNombres();
        $correos = $agenda->getCorreos();

        setCookie('nombres', implode(",", $nombres));
        setCookie('correos', implode(",", $correos));
    }

    $persona = "";
    if (isset($_POST['persona'])) {
        $persona = mb_strtoupper($_POST['persona'], 'UTF-8');
        setcookie('persona', $persona);
    }
    else {
        $persona = $_COOKIE['persona'];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGENDA</title>
</head>
<body>
    <!-- FORMULARIO INGRESO DATOS -->
    <center>
        <form method="post">
            <table style="text-align: center;">
                <tr>
                    <td colspan="2"><h1>AGENDA DE <?php echo $persona ?></h1></td>
                </tr>
                <tr>
                    <td colspan="2"><p>AÑADIR ENTRADA:</p></td>
                </tr>
                <tr>
                    <td><label>Introduce el nombre</label></td>
                    <td><input type="text" name="nombre" value="<?php if (isset($_POST['nombre'])) { echo $_POST['nombre']; } ?>"></td>
                </tr>
                <tr>
                    <td><label>Introduce el correo electrónico</label></td>
                    <td><input type="text" name="correo" value="<?php if (isset($_POST['correo'])) { echo $_POST['correo']; } ?>"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="AÑADIR" style="margin-top: 10px;"></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        // MOSTRAMOS LAS ENTRADAS
                        $agenda->mostrarEntradas();
                        ?>
                    </td>
                </tr>
            </table> 
        </form>
    </center>
</body>
</html>