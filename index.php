<?php

//Desarrollado por - Vicente Barrenechea
//Tarea Buda, Red de metro calculando el menor trayecto

//Esta aplicacion web sirve para la subida de una red de paradas de metro, mediante un archivo .red
//con la posibilidad de calcular los trayectos con una parada de inicio y fin

//El archivo .red es un archivo de texto con extension [nombre_archivo].red
//El formato del archivo .red es el siguiente
//[nombre_parada]|[color_parada]|[{nombres_paradas_anteriores| ...} = null]
//Los colores de las paradas son los siguientes:
//0 = sin color
//1 = verde
//2 = rojo

//Un ejemplo de un archivo .red:
//         A|0,B|0|A,C|1|B,D|2|B
//Se crearan cuatro paradas, {A,B,C,D}, siendo C verde y D rojo
//Los posibles trayectos siguientes serian {AB,ABC,ABD}
//y los anteriores serian {CB,CBA,DB,DBA}
//Con esto se calculan los trayectos de inicio a fin, filtrando por color, mostrando el que tiene menos paradas al principio

//Se crean las clases en php por la subida de archivos y despues se crea las clases en javascript, con los datos de php

//Inicializar variables de red y subida
$red = null;
$uploadOk = 0;

//Clase Parada, contiene los datos de cada parada
class Parada {
    //Nombre de la parada, se crea mediante el primer dato del archivo .red
    public $nombre = '';
    //Color de la parada, se crea mediante el segundo dato del archivo .red
    //0 = sin color
    //1 = verde
    //2 = rojo
    public $color = 0;
    //Array de paradas anteriores, se crea retroactivamente
    public $anterior = array();
    //Array de paradas siguientes, se crea mediante el tercer y consecutivos datos del archivo .red
    public $siguiente = array();

    //Muestra la parada con su color, en html
    function mostrar() {
        $colorMostrar = "<parada>";
        if ($this->color == 1) {
            $colorMostrar = '<parada style="color: green">';
        } else if ($this->color == 2) {
            $colorMostrar = '<parada style="color: red">';
        }
        return $colorMostrar.$this->nombre.'</parada>';
    }
}

//Clase Red, contiene las paradas de la red del metro
class Red {
    //Nombre de la red, se da por el nombre del archivo
    public $nombre = "";
    //Array de paradas de la red
    public $paradas = array();

    //Devuelve una clase Parada si coincide con el nombre dado
    function buscarNombre(string $nombreBuscar) {
        foreach($this->paradas as $parada) {
            if ($parada->nombre == $nombreBuscar) {
                return $parada;
            }
        }
        return null;
    }

    //Funcion para ingresar paradas por el archivo .red
    function ingresarParadas(string $cadena) {
        //Remplaza los espacios y saltos de lines del archivo ingresado
        $cadena = str_replace(array("\r", "\n", " "), '', $cadena);

        //Divide en un array por las comas la cadena de texto del archivo que se ingresa
        $paradas = explode(",", $cadena);

        //Loop con todas las paras
        foreach ($paradas as $parada) {
            //Divide en un array por los datos de cada parada
            $datos = explode("|", $parada);
            $paradaClass = new Parada();
            $countDatos = 0;
            //Crea e ingresa las paradas a la red con sus anteriores paradas
            foreach ($datos as $dato) {
                if ($countDatos == 0) {
                    $paradaClass->nombre = $datos[0];
                } else if ($countDatos == 1) {
                    if ($dato >= 0 and $dato < 3) {
                        $paradaClass->color = $dato;
                    } else {
                        $paradaClass->color = 0;
                    }
                } else {
                    $paradaClass->anterior[] = $dato;
                }
                $countDatos++;
            }
            $this->paradas[] = $paradaClass;
        }

        //Ingresa las paradas siguientes de las paradas ya creadas
        foreach ($this->paradas as $parada) {
            foreach ($parada->anterior as $nombreParadaAnterior) {
                $paradaBuscada = $this->buscarNombre($nombreParadaAnterior);
                if ($paradaBuscada != null) {
                    $paradaBuscada->siguiente[] = $parada->nombre;
                }
            }
        }
    }
}

//Este metodo se activa al subir el archivo .red
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileType = strtolower(pathinfo($_FILES["archivoRed"]["name"],PATHINFO_EXTENSION));
    //Verifica que el archivo sea de extension .red
    if ($fileType == "red") {
        $cadena = file_get_contents($_FILES["archivoRed"]["tmp_name"]);

        //Inicializar la clase Red
        $red = new Red();

        //Se guarda el nombre de la red por el nombre del archivo
        $red->nombre = substr(strtolower($_FILES["archivoRed"]["name"]), 0, -4);

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
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body style="margin-left: 50px;">

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
        Estacion inicial: <select name="inicio" id="inicio">
            <?php $countIni = 0;
            foreach ($red->paradas as $parada) { ?>
                <option value="<?php echo $countIni ?>"><?php echo $parada->nombre ?></option>
            <?php $countIni++;
            } ?>
        </select>
        <br>
        Estacion final: <select name="final" id="final">
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
    <script>
        //Las clases se deben crear de nuevo para que funciones en javascript, se tendran que crear nuevas funciones para la logica del trayecto
        //Clase Parada, contiene los datos de cada parada
        class Parada {
            //Constructot clase Parada
            constructor(nombre, color, anterior, siguiente) {
                //Nombre de la parada
                this.nombre = nombre;
                //Color de la parada
                //0 = sin color
                //1 = verde
                //2 = rojo
                this.color = color;
                //Array de paradas anteriores
                this.anterior = anterior;
                //Array de paradas siguientes
                this.siguiente = siguiente;
            }

            //Muestra la parada con su color, en html
            mostrar() {
                var colorMostrar = '<parada><b>';
                if (this.color == 1) {
                    colorMostrar = '<parada style="color: green"><b>';
                } else if (this.color == 2) {
                    colorMostrar = '<parada style="color: red"><b>';
                }
                return colorMostrar + this.nombre + '</b></parada>';
            }
        }

        //Clase Red, contiene las paradas de la red del metro y los trayectos posibles que se crean recursivamente
        class Red {
            //Constructot clase Red
            constructor(nombre, paradas) {
                //Nombre de la red, se da por el nombre del archivo
                this.nombre = nombre;
                //Las paradas de la red, guardadas con la clase Parada
                this.paradas = paradas;
                //Array de los mapas de trayectos siguientes posibles de la red
                this.trayectosSiguientes = new Array();
                //Array de los mapas de trayectos anteriores posibles de la red
                this.trayectosAnteriores = new Array();
                //Array de los trayectos reales del actual calculo
                this.trayectosReales = new Array();
                //Cadena de texto temporal para el guardado del mapa de trayectos
                this.trayectoTemp = "";
            }

            //Devuelve una clase Parada de la red si coincide con el nombre dado
            buscarNombre(nombre) {
                for (var i=0;i < this.paradas.length;i++){
                    if (paradas[i].nombre == nombre) {
                        return paradas[i];
                    }
                }
                return null;
            }

            //Crea los mapas de trayectos siguientes posibles de la red
            //Parte desde las paradas que no tienen parada aterior
            crearMapasSiguienteRecursivo(parada) {
                //Agrega la parada a la cadena de texto temporal de la red
                this.trayectoTemp += parada.nombre + ',';
                //Agrega la parada a la cadena de texto temporal, se mantiene si existe una bifurcacion
                var trayectoTempFor = this.trayectoTemp;
                //Loop de las paradas siguientes
                for (var paradaSiguiente of parada.siguiente) {
                    //Agrega el texto del trayeto a los siguientes trayectos posbiles de la red
                    this.trayectosSiguientes.push(trayectoTempFor + paradaSiguiente);
                    //Si existe la parada se llama esta misma funcion pero con la parada siguiente
                    var paradaSiguienteObj = this.buscarNombre(paradaSiguiente);
                    if (paradaSiguienteObj != null) {
                        this.crearMapasSiguienteRecursivo(paradaSiguienteObj)
                    }
                    //Si la cadena de texto temporal y la de la red no coinciden, la de la red se cambia
                    if (this.trayectoTemp != trayectoTempFor) {
                        this.trayectoTemp = trayectoTempFor;
                    }
                }
            }

            //Crea los mapas de trayectos anteriores posibles de la red
            //Parte desde las paradas que no tienen parada siguiente
            crearMapasAnteriorRecursivo(parada) {
                //Agrega la parada a la cadena de texto temporal de la red
                this.trayectoTemp += parada.nombre + ',';
                //Agrega la parada a la cadena de texto temporal, se mantiene si existe una bifurcacion
                var trayectoTempFor = this.trayectoTemp;
                //Loop de las paradas anteriores
                for (var paradaAnterior of parada.anterior) {
                    //Agrega el texto del trayeto a los anteirores trayectos posbiles de la red
                    this.trayectosAnteriores.push(trayectoTempFor + paradaAnterior);
                    //Si existe la parada se llama esta misma funcion pero con la parada anterior
                    var paradaAnteriorObj = this.buscarNombre(paradaAnterior);
                    if (paradaAnteriorObj != null) {
                        this.crearMapasAnteriorRecursivo(paradaAnteriorObj)
                    }
                    //Si la cadena de texto temporal y la de la red no coinciden, la de la red se cambia
                    if (this.trayectoTemp != trayectoTempFor) {
                        this.trayectoTemp = trayectoTempFor;
                    }
                }
            }

            //Busca los trayectos siguientes mediante una parada de inicio, una de fin y el color del tren
            buscarTrayectoSiguiente(paradaIni, paradaFin, colorTren) {
                //Se buscan y agregan a un array los trayectos posibles que terminan con la parada final
                var trayectosRealMapa = new Array();
                //Loop de todos los trayectos siguientes posibles
                for (var mapaSiguiente of this.trayectosSiguientes) {
                    var paradas = mapaSiguiente.split(',');
                    if (paradas[paradas.length-1] == paradaFin.nombre){
                        //Se agrega el trayecto que termina con la parada final
                        trayectosRealMapa.push(mapaSiguiente);
                    }
                }
                //Loop de los trayecto posibles encontrados
                if (trayectosRealMapa.length > 0) {
                    //Se llama a la funcion de guardar trayectos reales
                    this.guardarTrayecto(trayectosRealMapa, paradaIni, colorTren);
                }
            }

            //Busca los trayectos anteriores mediante una parada de inicio, una de fin y el color del tren.
            //Solo se llama si no se encontro trayectos siguientes
            buscarTrayectoAnterior(paradaIni, paradaFin, colorTren) {
                //Se buscan y agregan a un array los trayectos posibles que terminan con la parada final
                var trayectosRealMapa = new Array();
                //Loop de todos los trayectos anteriores posibles
                for (var mapaAnterior of this.trayectosAnteriores) {
                    var paradas = mapaAnterior.split(',');
                    if (paradas[paradas.length-1] == paradaFin.nombre){
                        //Se agrega el trayecto que termina con la parada final
                        trayectosRealMapa.push(mapaAnterior);
                    }
                }
                //Loop de los trayecto posibles encontrados
                if (trayectosRealMapa.length > 0) {
                    //Se llama a la funcion de guardar trayectos reales
                    this.guardarTrayecto(trayectosRealMapa, paradaIni, colorTren);
                }
            }

            //Funcion para guardar trayectos reales, se llama cuando se busca y encuentra un trayecto posible
            guardarTrayecto(trayectosRealMapa, paradaIni, colorTren){
                for (var paradaNombre of trayectosRealMapa) {
                    //Se divide el trayecto por cada parada
                    var paradas = paradaNombre.split(',');
                    //Se busca en el trayecto si existe la parada de inicio
                    var existe = false;
                    var trayectoReal = new Array();
                    for (var i = 0; i < paradas.length; i++) {
                        //Se busca la parada de inicio en el trayecto
                        if (paradas[i] == paradaIni.nombre) {
                            existe = true;
                        }
                        if (existe) {
                            //Si existe la parada de inicio en el trayecto está y las paradas siguientes ,si es de un color permitido, se agrega al trayecto real temporal
                            var paradaObj = this.buscarNombre(paradas[i]);
                            if (paradaObj != null) {
                                if (colorTren == 0){
                                    trayectoReal.push(paradaObj);
                                } else if (paradaObj.color == 0) {
                                    trayectoReal.push(paradaObj);
                                } else {
                                    if (colorTren == paradaObj.color) {
                                        trayectoReal.push(paradaObj);
                                    }
                                }
                            }
                        }
                    }
                    //Si el trayecto real temporal contiene paradas, se agrega al trayecto real de la red
                    if (trayectoReal.length > 0) {
                        this.trayectosReales.push(trayectoReal);
                    }
                }
            }
        }

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
            for (var paradaRootSig of red.paradas) {
                if (paradaRootSig.anterior.length == 0) {
                    red.crearMapasSiguienteRecursivo(paradaRootSig);
                    red.trayectoTemp = "";
                }
            }
            for (var paradaRootAnt of red.paradas) {
                if (paradaRootAnt.siguiente.length == 0) {
                    red.crearMapasAnteriorRecursivo(paradaRootAnt);
                    red.trayectoTemp = "";
                }
            }
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
                red.trayectosReales = new Array();

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
                        red.buscarTrayectoSiguiente(primeraParada, ultimaParada, trenColor.value);

                        //Si no se encuentra un trayecto siguiente, se busca en los anteriores
                        var anterior = false;
                        if (red.trayectosReales.length == 0) {
                            anterior = true;
                            red.buscarTrayectoAnterior(primeraParada, ultimaParada, trenColor.value);
                        }

                        //Si existe un trayecto estos se muestran
                        if (red.trayectosReales.length > 0) {
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
