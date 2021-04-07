<?php

//Desarrollado por - Vicente Barrenechea
//Tarea Buda, Red de metro calculando el menor trayecto

//Esta aplicacion web sirve para la subida de una red de paradas de metro, mediante un archivo .red
//con la posibilidad de calcular los trayectos con una parada de inicio y fin

//El archivo .red es un archivo de texto con extensión [nombre_archivo].red
//El formato del archivo .red es el siguiente
//[nombre_parada]|[color_parada]|[{nombres_paradas_anteriores|...} = null],[nombre_parada]|[color_parada]|[{nombres_paradas_anteriores|...} = null],...
//Los colores de las paradas son los siguientes:
//0 = sin color
//1 = verde
//2 = rojo

//Un ejemplo de un archivo .red:
//         A|0,B|0|A,C|1|B,D|2|B,E|0|C|D
//Se crearan cuatro paradas, {A,B,C,D,E}, siendo C verde y D rojo
//Los posibles trayectos siguientes serian {AB,ABC,ABCE,ABD,ABDE}
//y los anteriores serian {EC,ECB,ECBA,ED,EDB,EDBA}
//Con esto se calculan los trayectos de inicio a fin, filtrando por color, mostrando el trayecto que tiene menos paradas al principio

//Se crean las clases en php por la subida de archivos y despues se crea las clases en javascript, con los datos de php

//Se importan las clases en php
require 'clases/clases.php';

//Inicializar variables de red y subida
$red = null;
$uploadOk = 0;

//Este metodo se activa al subir el archivo .red
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileType = strtolower(pathinfo($_FILES["archivoRed"]["name"],PATHINFO_EXTENSION));
    //Verifica que el archivo sea de extension .red
    if ($fileType == "red") {
        $cadena = file_get_contents($_FILES["archivoRed"]["tmp_name"]);

        //Inicializar la clase Red
        $red = new Red();

        //Se guarda el nombre de la red por el nombre del archivo
        $red->nombre = substr($_FILES["archivoRed"]["name"], 0, -4);

        //Ingresar paradas
        $red->ingresarParadas($cadena);

        //echo '<pre>' , var_dump($red) , '</pre>';

        $uploadOk = 1;
    } else {
        $uploadOk = -1;
    }
}
?>
<html>
    <head>
        <!-- estilo de la pagina -->
        <link rel="stylesheet" href="css/estilo.css">
    </head>
    <body style="margin-left: 50px; margin-right: 50px;">

    <!-- Ver pruebas unitarias -->
    <a href="/pruebas.php">Pruebas Unitarias</a><br>
    <!-- Inicio subida archivo .red -->
    <a href="/ejemplo.red" download>Descargar archivo .red de ejemplo</a><br>
    <br>
    <?php if ($uploadOk == 1) { ?>
        <b>Archivo .red cargado correctamente</b>
    <?php } else if ($uploadOk == -1) { ?>
        <b>No se pudo cargar el archivo .red, el tipo de archivo debe ser .red</b>
    <?php } ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        Red del metro (archivo .red) <br>
        <input type="file" name="archivoRed" id="archivoRed">
        <br>
        <input type="submit" value="Calcular red del metro">
    </form>
    <!-- Fin subida archivo .red -->

    <!-- Comprueba que este inicializada la red -->
    <?php if ($red != null) { ?>
        <!-- Inicio vista de la red -->
        <div id="redView">
            <!-- Inicio ejemplo de parada -->
            Ejemplo de parada: <br>
            <table>
                <tr>
                    <th colspan="2">
                        Nombre y Color
                    </th>
                </tr>
                <tr>
                    <td>
                        Anteriores
                    </td>
                    <td>
                        Siguientes
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <!-- Fin ejemplo de parada -->
            <b>Paradas de Red del Metro, <?php echo $red->nombre ?>:</b>
            <br>
            <!-- Inicio mostrar red -->
            <!-- Se hace un loop por todas las paradas de la red, mostrando sus datos -->
            <?php
            foreach ($red->paradas as $parada) {
                ?>
                <table>
                    <tr>
                        <th colspan="2">
                            <?php echo $parada->mostrar(); ?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $countAnt = 0;
                            foreach ($parada->anterior as $paradaAnterior) {
                                if ($countAnt > 0) {
                                    echo "|";
                                }
                                $paradaAntObj = $red->buscarNombre($paradaAnterior);
                                if ($paradaAntObj != null) {
                                    echo $paradaAntObj->mostrar();
                                }
                                $countAnt++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $countSig = 0;
                            foreach ($parada->siguiente as $paradaSiguiente) {
                                if ($countSig > 0) {
                                    echo "|";
                                }
                                $paradaSigObj = $red->buscarNombre($paradaSiguiente);
                                if ($paradaSigObj != null) {
                                    echo $paradaSigObj->mostrar();
                                }
                                $countSig++;
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            <?php
            }
            ?>
            <!-- Fin mostrar red -->
        </div>
        <!-- Fin vista de la red -->

        <!-- Inicio calcular trayecto -->
        <br>
        Estación inicial: <select name="inicio" id="inicio">
            <?php $countIni = 0;
            foreach ($red->paradas as $parada) { ?>
                <option value="<?php echo $countIni ?>"><?php echo $parada->nombre ?></option>
            <?php $countIni++;
            } ?>
        </select>
        <br>
        Estación final: <select name="final" id="final">
            <?php $countFin = 0;
            foreach ($red->paradas as $parada) { ?>
                <option value="<?php echo $countFin ?>"><?php echo $parada->nombre ?></option>
                <?php $countFin++;
            } ?>
        </select>
        <br>
        Tren a seleccionar: <select name="tren" id="tren">
            <option value="0">Tren sin color</option>
            <option value="1">Tren verde</option>
            <option value="2">Tren rojo</option>
        </select>
        <br>
        <button type="button" onclick="calcularTrayecto()">Calcular trayecto del tren</button>
        <br>
        <!-- Fin calcular trayecto -->
        <!-- Inicio vista trayecto -->
        <span id="trayecto"></span>
        <!-- Fin vista trayecto -->
        <!-- Inicio vista de debug -->
        <span id="debug"></span>
        <!-- Fin vista de debug -->
    <?php } ?>

    </body>
    <!-- Las clases se deben crear de nuevo para que funciones en javascript, se tendran que crear nuevas funciones para la logica del trayecto -->
    <!-- Se importan las clases de javascript -->
    <script src="clases/clases.js"></script>
    <script>
        //Inicializacion de las paradas ingresadas por php
        var paradas = new Array();
        var newParada = null;
        var paradasAnteriores = null;
        var paradasSiguientes = null;
        <?php
        if ($red != null) {
            foreach ($red->paradas as $parada) {
                ?>
                paradasAnteriores = new Array();
                <?php
                foreach ($parada->anterior as $paradaAnterior) {
                ?>
                    paradasAnteriores.push("<?php echo $paradaAnterior ?>");
                <?php
                }
                ?>
                paradasSiguientes = new Array();
                <?php
                foreach ($parada->siguiente as $paradaSiguiente) {
                ?>
                    paradasSiguientes.push("<?php echo $paradaSiguiente ?>");
                <?php
                }
                ?>
                newParada = new Parada("<?php echo $parada->nombre ?>", <?php echo $parada->color ?>, paradasAnteriores, paradasSiguientes);
                paradas.push(newParada);
            <?php
            }
            ?>
            //Inicializacion de la red por las paradas ingresadas por php
            red = new Red("<?php echo $red->nombre ?>", paradas);
            red.crearMapaRecursivo();
        <?php
        }
        ?>

        //Calcula y muestra el trayecto por un parada de inicio, una de fin y el color del tren
        //Se llama al apretar el boton "Calcular trayecto del tren"
        function calcularTrayecto() {
            //Lee los inputs del formulario de calculo de trayectos
            var selectIni = document.getElementById('inicio');
            var selectFin = document.getElementById('final');
            var trenColor = document.getElementById('tren');

            //Iniciliza variables del resultado
            var resultado = true;
            var html = "<b>";

            //Valida si se subio y creo la red
            <?php
            if ($red != null) {
                ?>
                //Asigna las pardas de incio y de fin
                var primeraParada = red.paradas[selectIni.value];
                var ultimaParada = red.paradas[selectFin.value];

                //Inicializa el trayecto de menor paradas
                var trayectoMenor = new Array();
                if (primeraParada != ultimaParada) {
                    if (primeraParada.color != 0 && trenColor.value != 0) {
                        //Si la primera parada no es de un color permitido, se notifica con el resultado
                        if (trenColor.value != primeraParada.color) {
                            html += "La primera parada no es del color del tren<br>";
                            resultado = false;
                        }
                    }
                    if (ultimaParada.color != 0 && trenColor.value != 0) {
                        //Si la ultima parada no es de un color permitido, se notifica con el resultado
                        if (trenColor.value != ultimaParada.color) {
                            html += "La ultima parada no es del color del tren<br>";
                            resultado = false;
                        }
                    }
                    //Si la primera y ultima parada es correcta, se avanza en el calculo
                    if (resultado){
                        //Se busca el trayecto siguiente real
                        trayectosExiste = red.buscarTrayectos(primeraParada, ultimaParada, trenColor.value);

                        //Si existe un trayecto estos se muestran
                        if (trayectosExiste) {
                            //Se busca el trayecto con menor paradas
                            for (var trayeco of red.trayectosReales) {
                                if (trayectoMenor.length > trayeco.length) {
                                    trayectoMenor = trayeco;
                                } else if (trayectoMenor.length == 0) {
                                    trayectoMenor = trayeco;
                                }
                            }

                            if (red.trayectosReales.length > 1) {
                                html += "Trayecto con menor tiempo:<br>";
                            } else {
                                html += "Trayecto:<br>";
                            }
                            //Se muestra el trayecto con menor paradas
                            html += "<table>";
                            html += "<tr>";
                            var countTrayecto = 0;
                            for (var trayeco of trayectoMenor) {
                                html += "<td>";
                                html += trayeco.mostrar();
                                html += "</td>";
                                if (countTrayecto < trayectoMenor.length - 1) {
                                    html += "<th>";
                                    html += "->";
                                    html += "</th>";
                                }
                                countTrayecto++;
                            }
                            html += "</tr>";
                            html += "</table>";
                            html += "<br>";
                        } else {
                            resultado = false;
                        }
                    }
                } else {
                    //Si la parada de inicio es la misma de la final, se notifica con el resultado
                    html += "La parada de inicio es la misma a la final<br>";
                    resultado = false;
                }

                if (resultado) {
                    //Si existe mas de un trayecto estos se muestran
                    if (red.trayectosReales.length > 1) {
                        html += "Otros trayectos:";
                        html += "<br>";
                        for (var trayecto of red.trayectosReales) {
                            if (trayectoMenor != trayecto) {
                                html += "<table>";
                                html += "<tr>";
                                countParada = 0;
                                for (var parada of trayecto) {
                                    html += "<td>";
                                    html += parada.mostrar();
                                    html += "</td>";
                                    if (countParada < trayecto.length - 1) {
                                        html += "<th>";
                                        html += "->";
                                        html += "</th>";
                                    }
                                    countParada++;
                                }
                                html += "</tr>";
                                html += "</table>";
                                html += "</b>";
                                html += "<br>";
                            }
                        }
                    }
                    html += "<br>";
                    //Se escribe el boton de debug
                    var debugResultado = document.getElementById('debug');
                    debugResultado.innerHTML = "<button type=\"button\" onclick=\"verDebug()\">debug</button>";
                } else {
                    //Si no se encuentra un trayecto posible se notifica con el resultado
                    html += "No existe trayecto";
                    var debugResultado = document.getElementById('debug');
                    debugResultado.innerHTML = "";
                }

                html += "</b>";

                //Se escribe el resultado en la vista del trayecto
                var trayectoResultado = document.getElementById('trayecto');
                trayectoResultado.innerHTML = html;
            <?php } ?>
            }

            //Muestra los datos de debug
            function verDebug(){
                <?php
                if ($red != null) {
                    ?>
                    var html = "";

                    //Muestra los datos de la red
                    html += "Nombre de la red: " + red.nombre + "<br>";
                    html += "Cantidad de paradas de la red: " + red.paradas.length + "<br><br>";
                    //Muestra los datos del trayecto calculado
                    html += "Cantidad de trayectos encontrados: " + red.trayectosReales.length + "<br>";
                    var countTra = 0;
                    for (var trayecto of red.trayectosReales) {
                        var textoTrayecto = "(";
                        for (var parada of trayecto) {
                            textoTrayecto += parada.nombre+",";
                        }
                        textoTrayecto = textoTrayecto.substring(0,textoTrayecto.length-1);
                        textoTrayecto += ")";
                        html += "Trayecto " + textoTrayecto + ":<br>";
                        html += "&nbsp;&nbsp;&nbsp;&nbsp;Cantidad de paradas: " + trayecto.length + "<br>";
                        countTra++;
                    }
                    html += "<br>";
                    //Muestra todos los posibles trayectos que existen en la red
                    html += "Cantidad total de siguientes trayectos posibles: " + red.trayectosSiguientes.length + "<br>";
                    for (var trayecto of red.trayectosSiguientes) {
                        html += "&nbsp;&nbsp;&nbsp;&nbsp;" + trayecto + "<br>";
                    }
                    html += "Cantidad total de anteriores trayectos posibles: " + red.trayectosAnteriores.length + "<br>";
                    for (var trayecto of red.trayectosAnteriores) {
                        html += "&nbsp;&nbsp;&nbsp;&nbsp;" + trayecto + "<br>";
                    }


                    //Se escribe el resultado en la vista del debug
                    var debugResultado = document.getElementById('debug');
                    debugResultado.innerHTML = html;

                    //Escribe en la consola la clase de red activa
                    console.log(red);
                <?php } ?>
            }
    </script>
</html>
