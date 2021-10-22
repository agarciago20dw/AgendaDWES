<!-- CLASE AGENDA -->
<?php
    session_start();

    class Agenda {
        // ATRIBUTO DE LA CLASE, ARRAY ASOCIATIVO (CLAVE: NOMBRE, VALOR: CORREO) DÓNDE GUARDAREMOS LAS ENTRADAS DE LA AGENDA
        private $entradas = [];
        private $mensaje;

        // FUNCIÓN QUE AÑADE UNA ENTRADA A LA AGENDA - RECIBE UN NOMBRE Y UN CORREO POR PARÁMETROS
        public function anadirEntrada($nombre, $correo) {
            $nombre = $this->formatearNombre($nombre);
            if ($this->nombreVacio($nombre)) {
                $this->mensaje = "EL NOMBRE ESTÁ VACÍO";
            }
            else if (!$this->existeEntrada($nombre) && $this->correoValido($correo)) {
                $this->entradas[$nombre] = $correo;
                $this->mensaje = "REGISTRO INSERTADO CORRECTAMENTE";
            }
            else if ($this->existeEntrada($nombre) && $this->correoValido($correo)) {
                $this->entradas[$nombre] = $correo;
                $this->mensaje = "REGISTRO ACTUALIZADO CORRECTAMENTE";
            }
            else if ($this->existeEntrada($nombre) && $this->correoVacio($correo)) {
                unset($this->entradas[$nombre]);
                $this->mensaje = "REGISTRO ELIMINADO CORRECTAMENTE";
            }
            else {
                $this->mensaje = "EL CORREO NO ES VÁLIDO";
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
            $cod_html = "<table>";
            $cod_html .= "<tr><td class='titulos'>NOMBRE</td><td class='titulos'>CORREO</td></tr>";

            foreach ($this->entradas as $nombre => $correo) {
                $cod_html .= "<tr><td>" . $nombre . "</td><td>" . $correo . "</td></tr>";
            }

            $cod_html .= "</table>";
            echo $cod_html;
        }

        public function mostrarMensaje() {
            echo $this->mensaje;
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
    if (isset($_SESSION['nombres']) && isset($_SESSION['correos'])) {
        $nombres = explode(",", $_SESSION['nombres']);
        $correos = explode(",", $_SESSION['correos']);

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

        $_SESSION['nombres'] = implode(",", $nombres);
        $_SESSION['correos'] = implode(",", $correos);
    }

    $persona = "";
    if (isset($_POST['persona'])) {
        $persona = mb_strtoupper($_POST['persona'], 'UTF-8');
        $_SESSION['persona'] = $persona;
    }
    else {
        $persona = $_SESSION['persona'];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&family=Oswald:wght@200&family=Poppins:wght@200&display=swap" rel="stylesheet">
    <title>AGENDA</title>
</head>
<body>
    <div class="contenedor">
        <?php 
            $persona = mb_strtoupper($_POST['persona'], 'UTF-8'); 
        ?>
        <header class="cabecera">
            <i class="far fa-address-book"></i>
            <h1>AGENDA DE <?php echo $persona; ?></h1>
        </header>
        
        <section class="seccion">
            <p>AÑADIR ENTRADA:</p>
            <!-- FORMULARIO INGRESO DATOS -->
            <form method="post" class="formulario">
                <input class="campos" type="text" name="nombre" value="<?php if (isset($_POST['nombre'])) { echo $_POST['nombre']; } ?>" placeholder="Introduce el nombre...">
                <input class="campos" type="text" name="correo" value="<?php if (isset($_POST['correo'])) { echo $_POST['correo']; } ?>" placeholder="Introduce el email...">
                <input class="enviar" type="submit" value="AÑADIR">

                <input type="hidden" name="nombres" value="<?php if (!empty($nombres)) { echo implode(",", $nombres); } ?>">
                <input type="hidden" name="correos" value="<?php if (!empty($correos)) { echo implode(",", $correos); } ?>">
                <input type="hidden" name="persona" value="<?php echo $persona ?>">
            </form>
        </section>
        <article class="articulo">
            <?php
                // MOSTRAMOS LAS ENTRADAS
                if (isset($_POST['nombre']) && isset($_POST['correo'])) {
                    $agenda->mostrarEntradas();
                    ?>
                    <p class="mensaje">¡<?php $agenda->mostrarMensaje(); ?>!</p>
                    <?php
                }
            ?>
        </article>

        <footer class="pie">
            <p>Adrián García González © DW32 Zubiri Manteo</p>
        </footer>
    </div>
</body>
</html>