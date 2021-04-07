<?php
//Se importan las clases en php
require 'clases/clases.php';

//Inicializar variables de red y pruebas
$confirmacion1 = "PRUEBA NO INICIADA";
$resultado1 = "-";
$iniciado = "";
$redObj = null;
$arrayCorrectos = null;

//Iniciar las pruebas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iniciado = $_POST["iniciar"];
}

if ($iniciado == "1"){
    //Crear paradas esperadas para las pruebas
    $paradaCorrectaA = [
        'nombre' => 'A',
        'color' => 0,
        'siguiente' => [
            0 => 'B',
            1 => 'C',
            2 => 'E'
        ],
        'anterior' => []
    ];
    $paradaCorrectaB = [
        'nombre' => 'B',
        'color' => 0,
        'siguiente' => [
            0 => 'D'
        ],
        'anterior' => [
            0 => 'A'
        ]
    ];
    $paradaCorrectaC = [
        'nombre' => 'C',
        'color' => 0,
        'siguiente' => [
            0 => 'E',
            1 => 'F',
            2 => 'O'
        ],
        'anterior' => [
            0 => 'A'
        ]
    ];
    $paradaCorrectaD = [
        'nombre' => 'D',
        'color' => 2,
        'siguiente' => [
            0 => 'G'
        ],
        'anterior' => [
            0 => 'B'
        ]
    ];
    $paradaCorrectaE = [
        'nombre' => 'E',
        'color' => 1,
        'siguiente' => [
            0 => 'G',
            1 => 'H'
        ],
        'anterior' => [
            0 => 'A',
            1 => 'C'
        ]
    ];
    $paradaCorrectaF = [
        'nombre' => 'F',
        'color' => 0,
        'siguiente' => [
            0 => 'H'
        ],
        'anterior' => [
            0 => 'C'
        ]
    ];
    $paradaCorrectaG = [
        'nombre' => 'G',
        'color' => 2,
        'siguiente' => [
            0 => 'I'
        ],
        'anterior' => [
            0 => 'D',
            1 => 'E'
        ]
    ];
    $paradaCorrectaH = [
        'nombre' => 'H',
        'color' => 2,
        'siguiente' => [
            0 => 'I'
        ],
        'anterior' => [
            0 => 'O',
            1 => 'E',
            2 => 'F'
        ]
    ];
    $paradaCorrectaI = [
        'nombre' => 'I',
        'color' => 1,
        'siguiente' => [
            0 => 'J',
            1 => 'K'
        ],
        'anterior' => [
            0 => 'G',
            1 => 'H'
        ]
    ];
    $paradaCorrectaJ = [
        'nombre' => 'J',
        'color' => 2,
        'siguiente' => [
        ],
        'anterior' => [
            0 => 'I'
        ]
    ];
    $paradaCorrectaK = [
        'nombre' => 'K',
        'color' => 1,
        'siguiente' => [
        ],
        'anterior' => [
            0 => 'I'
        ]
    ];
    $paradaCorrectaL = [
        'nombre' => 'L',
        'color' => 2,
        'siguiente' => [
            0 => 'N'
        ],
        'anterior' => [
        ]
    ];
    $paradaCorrectaM = [
        'nombre' => 'M',
        'color' => 1,
        'siguiente' => [
            0 => 'N'
        ],
        'anterior' => [
        ]
    ];
    $paradaCorrectaN = [
        'nombre' => 'N',
        'color' => 0,
        'siguiente' => [
            0 => 'O',
            1 => 'P',
            2 => 'Q'
        ],
        'anterior' => [
            0 => 'L',
            1 => 'M'
        ]
    ];
    $paradaCorrectaO = [
        'nombre' => 'O',
        'color' => 1,
        'siguiente' => [
            0 => 'H',
            1 => 'R',
            2 => 'S'
        ],
        'anterior' => [
            0 => 'C',
            1 => 'N'
        ]
    ];
    $paradaCorrectaP = [
        'nombre' => 'P',
        'color' => 0,
        'siguiente' => [
            0 => 'R',
            1 => 'S'
        ],
        'anterior' => [
            0 => 'N'
        ]
    ];
    $paradaCorrectaQ = [
        'nombre' => 'Q',
        'color' => 0,
        'siguiente' => [
            0 => 'T'
        ],
        'anterior' => [
            0 => 'N'
        ]
    ];
    $paradaCorrectaR = [
        'nombre' => 'R',
        'color' => 1,
        'siguiente' => [
            0 => 'T'
        ],
        'anterior' => [
            0 => 'O',
            1 => 'P'
        ]
    ];
    $paradaCorrectaS = [
        'nombre' => 'S',
        'color' => 2,
        'siguiente' => [
            0 => 'T'
        ],
        'anterior' => [
            0 => 'O',
            1 => 'P'
        ]
    ];
    $paradaCorrectaT = [
        'nombre' => 'T',
        'color' => 0,
        'siguiente' => [
        ],
        'anterior' => [
            0 => 'R',
            1 => 'S',
            2 => 'Q'
        ]
    ];
    //Array de las paradas que se deberian crear
    $arrayCorrectos = [
        0 => $paradaCorrectaA,
        1 => $paradaCorrectaB,
        2 => $paradaCorrectaC,
        3 => $paradaCorrectaD,
        4 => $paradaCorrectaE,
        5 => $paradaCorrectaF,
        6 => $paradaCorrectaG,
        7 => $paradaCorrectaH,
        8 => $paradaCorrectaI,
        9 => $paradaCorrectaJ,
        10 => $paradaCorrectaK,
        11 => $paradaCorrectaL,
        12 => $paradaCorrectaM,
        13 => $paradaCorrectaN,
        14 => $paradaCorrectaO,
        15 => $paradaCorrectaP,
        16 => $paradaCorrectaQ,
        17 => $paradaCorrectaR,
        18 => $paradaCorrectaS,
        19 => $paradaCorrectaT
    ];

    //Ingresar cadena de texto de prueba a la red
    $redObj = new Red();
    $redObj->ingresarParadas("A|0,B|0|A,C|0|A,D|2|B,E|1|A|C,F|0|C,G|2|D|E,H|2|O|E|F,I|1|G|H,J|2|I,K|1|I,L|2,M|1,N|0|L|M,O|1|C|N,P|0|N,Q|0|N,R|1|O|P,S|2|O|P,T|0|R|S|Q");

    //Iniciar prueba 1.- Confirmar creacion correcta de paradas
    $resultado1 = "";
    //Probar que los valores son correctos
    $confirmacion1 = "<b>PRUEBA FALLIDA</b>";
    if (count($arrayCorrectos) == count($redObj->paradas)) {
        $countIngresadas = 0;
        foreach ($redObj->paradas as $parada) {
            $alert = true;
            //Se valida el nombre de la parada esperada con la ingresada
            if ($arrayCorrectos[$countIngresadas]["nombre"] != $parada->nombre) {
                if ($resultado1 == ""){
                    $resultado1 = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                }
                $resultado1 .= "La parada numero ".($countIngresadas+1)." no contiene el mismo nombre<br>";
                $resultado1 .= "Parada esperada: ".$arrayCorrectos[$countIngresadas]["nombre"]."<br>";
                $resultado1 .= "Parada ingresada: ".$parada->nombre."<br>";
                $alert = false;
            }
            //Se valida el color de la parada esperada con la ingresada
            if ($arrayCorrectos[$countIngresadas]["color"] != $parada->color) {
                if ($resultado1 == ""){
                    $resultado1 = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                }
                if ($alert) {
                    $resultado1 .= "La parada numero ".($countIngresadas+1)." no tiene el mismo color<br>";
                } else {
                    $resultado1 .= "La parada no tiene el mismo color<br>";
                }
                $resultado1 .= "Color esperado: ".$arrayCorrectos[$countIngresadas]["color"]."<br>";
                $resultado1 .= "Color ingresado: ".$parada->color."<br>";
                $alert = false;
            }
            //Se valida las paradas siguientes de la parada esperada con la ingresada
            if ($arrayCorrectos[$countIngresadas]["siguiente"] != $parada->siguiente) {
                if ($resultado1 == ""){
                    $resultado1 = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                }
                if ($alert) {
                    $resultado1 .= "La parada numero ".($countIngresadas+1)." no contiene las mismas paradas siguientes<br>";
                } else {
                    $resultado1 .= "La parada no contiene las mismas paradas siguientes<br>";
                }
                $resEspSiguientes = "";
                foreach ($arrayCorrectos[$countIngresadas]["siguiente"] as $paradaTxt) {
                    $resEspSiguientes .= $paradaTxt." ";
                }
                $resultado1 .= "Paradas siguientes esperadas:<br>".$resEspSiguientes."<br>";
                $resIngSiguientes = "";
                foreach ($parada->siguiente as $paradaTxt) {
                    $resIngSiguientes .= $paradaTxt." ";
                }
                $resultado1 .= "Parada siguientes ingresadas:<br>".$resIngSiguientes."<br>";
                $alert = false;
            }
            //Se valida las paradas anteriores de la parada esperada con la ingresada
            if ($arrayCorrectos[$countIngresadas]["anterior"] != $parada->anterior) {
                if ($resultado1 == ""){
                    $resultado1 = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                }
                if ($alert) {
                    $resultado1 .= "La parada numero ".($countIngresadas+1)." no contiene las mismas paradas anteriores<br>";
                } else {
                    $resultado1 .= "La parada no contiene las mismas paradas anteriores<br>";
                }
                $resEspAnterior = "";
                foreach ($arrayCorrectos[$countIngresadas]["anterior"] as $paradaTxt) {
                    $resEspAnterior .= $paradaTxt." ";
                }
                $resultado1 .= "Paradas anteriores esperadas:<br>".$resEspAnterior."<br>";
                $resIngAnterior = "";
                foreach ($parada->anterior as $paradaTxt) {
                    $resIngAnterior .= $paradaTxt." ";
                }
                $resultado1 .= "Parada anteriores ingresadas:<br>".$resIngAnterior."<br>";
            }
            $countIngresadas++;
        }
        //Si coiciden todas las paradas la prueba queda completada de forma exitosa
        if ($resultado1 == ""){
            $confirmacion1 = "<b>PRUEBA EXITOSA</b>";
            $resultado1 = "Las paradas esperadas y las ingresadas coinciden";
        }
    } else {
        $resultado1 = "La cantidad de paradas esperadas y las ingresadas no son las mismas";
    }
}

?>
<html>
    <head>
        <!-- estilo de la pagina -->
        <style>
            td {
                border: solid 1px black;
            }
        </style>
    </head>
    <body style="margin-left: 50px; margin-right: 50px;">
        <!-- Inicio datos y valores a probrar -->
        <a href="/index.php">Inicio</a><br>
        <h3><b>Datos y valores a probar en las pruebas unitarias</b></h3>
        <span>
            Cadena de texto de prueba de archivo .red:<br>
            <b>
                "A|0,B|0|A,C|0|A,D|2|B,E|1|A|C,F|0|C,G|2|D|E,H|2|O|E|F,I|1|G|H,J|2|I,K|1|I,L|2,M|1,N|0|L|M,O|1|C|N,P|0|N,Q|0|N,R|1|O|P,S|2|O|P,T|0|R|S|Q"
            </b><br>
            Redes que se crearian:<br>
        </span>
        <img src="img/redPruebaParadas.PNG" alt="Red de Prueba, Paradas"><br>
        <span>
            Red visualizada:<br>
        </span>
        <img src="img/redPrueba.PNG" alt="Red de Prueba, Visualizacion"><br>
        <table>
            <tr>
                <th>Cantidad de trayectos posibles siguientes que se crearian:</th>
                <th>73</th>
                <th><span id="btnSiguiente"><button onclick="mostrarSiguientesSupuestos()">Mostrar trayectos posibles</button></span></th>
            </tr>
            <tr>
                <th>Cantidad de trayectos posibles anteriores que se crearian:</th>
                <th>72</th>
                <th><span id="btnAnterior"><button onclick="mostrarAnterioresSupuestos()">Mostrar trayectos posibles</button></span></th>
            </tr>
        </table>
        <hr>
        <!-- Fin datos y valores a probrar -->
        <!-- Inicio pruebas unitarias -->
        <!-- Inicio pruebas unitarias php -->
        <h2><b>Pruebas Unitarias</b></h2>
        <form method="post">
            <input type="hidden" id="iniciar" name="iniciar" value="1">
            <input type="submit" value="Iniciar Pruebas">
        </form>
        <table style="border: solid 1px black; border-spacing: 0;">
            <tr>
                <th colspan="4" style="border: solid 1px black;">Php</th>
            </tr>
            <tr>
                <th>Descripción</th>
                <th>Resultado esperado</th>
                <th>Confirmacion</th>
                <th>Resultado</th>
            </tr>
            <tr>
                <td>
                    1.- Confirmar creacion correcta de paradas
                </td>
                <td>
                    Las paradas creadas deben ser las mismas a las esperadas
                </td>
                <td>
                    <span id="confirmacion1"><?php echo $confirmacion1 ?></span>
                </td>
                <td>
                    <span id="resultado1"><?php echo $resultado1 ?></span>
                </td>
            </tr>
        </table>
        <br>
        <!-- Fin pruebas unitarias php -->
        <!-- Inicio pruebas unitarias Javascript -->
        <table style="border: solid 1px black; border-spacing: 0;">
            <tr>
                <th colspan="4" style="border: solid 1px black;">Javascript</th>
            </tr>
            <tr>
                <th>Descripción</th>
                <th>Resultado esperado</th>
                <th>Confirmacion</th>
                <th>Resultado</th>
            </tr>
            <tr>
                <td>
                    2.- Confirmar creacion correcta de paradas
                </td>
                <td>
                    Las paradas creadas deben ser las mismas a las esperadas
                </td>
                <td>
                    <span id="confirmacion2">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado2">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    3.- Confirmar creacion correcta de trayectos siguientes posibles
                </td>
                <td>
                    Los trayectos siguientes posibles deben ser los mismos a las esperados
                </td>
                <td>
                    <span id="confirmacion3">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado3">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    4.- Confirmar creacion correcta de trayectos anteriores posibles
                </td>
                <td>
                    Los trayectos anteriores posibles deben ser los mismos a las esperados
                </td>
                <td>
                    <span id="confirmacion4">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado4">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    5.- Correcto funcionamiento de buscar trayecto existente con tren sin color, trayectos siguientes
                </td>
                <td>
                    Se prueba el buscado de un trayecto de A a T con un tren sin color, serian dos trayectos siguientes A,C,O,R,T y A,C,O,S,T
                </td>
                <td>
                    <span id="confirmacion5">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado5">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    6.- Correcto funcionamiento de buscar trayecto existente con tren sin color, trayectos anteriores
                </td>
                <td>
                    Se prueba el buscado de un trayecto de J a C con un tren sin color, serian cuatro trayectos anteriores J,I,G,E,C; J,I,H,O,C; J,I,H,E,C y J,I,H,F,C
                </td>
                <td>
                    <span id="confirmacion6">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado6">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    7.- Correcto funcionamiento de buscar trayecto no existente con tren sin color
                </td>
                <td>
                    Se prueba el buscado de un trayecto de A a N con un tren sin color, no se deberia encontrar trayecto y devolveria false, por que no existe un trayecto posible
                </td>
                <td>
                    <span id="confirmacion7">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado7">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    8.- Correcto funcionamiento de buscar trayecto existente con tren color verde, trayectos siguientes
                </td>
                <td>
                    Se prueba el buscado de un trayecto de A a T con un tren color verde, serian dos trayectos siguientes A,C,O,R,T Y A,C,O,T
                </td>
                <td>
                    <span id="confirmacion8">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado8">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    9.- Correcto funcionamiento de buscar trayecto existente con tren color verde, trayectos anteriores
                </td>
                <td>
                    Se prueba el buscado de un trayecto de K a C con un tren color verde, serian cuatro trayectos anteriores K,I,E,C; K,I,O,C; K,I,E,C y K,I,F,C
                </td>
                <td>
                    <span id="confirmacion9">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado9">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    10.- Correcto funcionamiento de buscar trayecto no existente con tren color verde, primera parada diferente color
                </td>
                <td>
                    Se prueba el buscado de un trayecto de L a T con un tren color verde, no se deberia encontrar trayecto y devolveria false, por que la primera parada no es del color del tren
                </td>
                <td>
                    <span id="confirmacion10">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado10">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    11.- Correcto funcionamiento de buscar trayecto existente con tren color rojo, trayectos siguientes
                </td>
                <td>
                    Se prueba el buscado de un trayecto de A a T con un tren color rojo, serian dos trayectos siguientes A,C,T y A,C,S,T
                </td>
                <td>
                    <span id="confirmacion11">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado11">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    12.- Correcto funcionamiento de buscar trayecto existente con tren color rojo, trayectos anteriores
                </td>
                <td>
                    Se prueba el buscado de un trayecto de J a C con un tren color rojo, serian cuatrio trayectos anteriores J,G,C; J,H,C; J,H,C y J,H,F,C
                </td>
                <td>
                    <span id="confirmacion12">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado12">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    13.- Correcto funcionamiento de buscar trayecto no existente con tren color rojo, ultima parada diferente color
                </td>
                <td>
                    Se prueba el buscado de un trayecto de J a O con un tren color rojo, no se deberia encontrar trayecto y devolveria false, por que la ultima parada no es del color del tren
                </td>
                <td>
                    <span id="confirmacion13">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado13">-</span>
                </td>
            </tr>
            <tr>
                <td>
                    14.- Correcto funcionamiento de buscar trayecto no existente con la primera y ultima parada siendo la misma
                </td>
                <td>
                    Se prueba el buscado de un trayecto de A a A, no se deberia encontrar trayecto y devolveria false, por que la primera y ultima parada son la misma
                </td>
                <td>
                    <span id="confirmacion14">PRUEBA NO INICIADA</span>
                </td>
                <td>
                    <span id="resultado14">-</span>
                </td>
            </tr>
        </table>
        <!-- Fin pruebas unitarias Javascript -->
        <!-- Fin pruebas unitarias -->
    </body>
    <!-- Se importan las clases de javascript -->
    <script src="clases/clases.js"></script>
    <script>
        //Crear paradas esperadas para las pruebas
        var paradasCorrectas = [
            [
                //nombre
                'A',
                //color
                0,
                //siguientes
                ['B','C','E'],
                //anteriores
                []
            ],
            [
                'B',
                0,
                ['D'],
                ['A']
            ],
            [
                'C',
                0,
                ['E','F','O'],
                ['A']
            ],
            [
                'D',
                2,
                ['G'],
                ['B']
            ],
            [
                'E',
                1,
                ['G','H'],
                ['A','C']
            ],
            [
                'F',
                0,
                ['H'],
                ['C']
            ],
            [
                'G',
                2,
                ['I'],
                ['D','E']
            ],
            [
                'H',
                2,
                ['I'],
                ['O','E','F']
            ],
            [
                'I',
                1,
                ['J','K'],
                ['G','H']
            ],
            [
                'J',
                2,
                [],
                ['I']
            ],
            [
                'K',
                1,
                [],
                ['I']
            ],
            [
                'L',
                2,
                ['N'],
                []
            ],
            [
                'M',
                1,
                ['N'],
                []
            ],
            [
                'N',
                0,
                ['O','P','Q'],
                ['L','M']
            ],
            [
                'O',
                1,
                ['H','R','S'],
                ['C','N']
            ],
            [
                'P',
                0,
                ['R','S'],
                ['N']
            ],
            [
                'Q',
                0,
                ['T'],
                ['N']
            ],
            [
                'R',
                1,
                ['T'],
                ['O','P']
            ],
            [
                'S',
                2,
                ['T'],
                ['O','P']
            ],
            [
                'T',
                0,
                [],
                ['R','S','Q']
            ],
        ]
        //Crear trayectos siguientes de la red esperados para las pruebas
        var arrayPosiblesSiguientesCorrectos = [
            "A,B",
            "A,B,D",
            "A,B,D,G",
            "A,B,D,G,I",
            "A,B,D,G,I,J",
            "A,B,D,G,I,K",
            "A,C",
            "A,C,E",
            "A,C,E,G",
            "A,C,E,G,I",
            "A,C,E,G,I,J",
            "A,C,E,G,I,K",
            "A,C,E,H",
            "A,C,E,H,I",
            "A,C,E,H,I,J",
            "A,C,E,H,I,K",
            "A,C,F",
            "A,C,F,H",
            "A,C,F,H,I",
            "A,C,F,H,I,J",
            "A,C,F,H,I,K",
            "A,C,O",
            "A,C,O,H",
            "A,C,O,H,I",
            "A,C,O,H,I,J",
            "A,C,O,H,I,K",
            "A,C,O,R",
            "A,C,O,R,T",
            "A,C,O,S",
            "A,C,O,S,T",
            "A,E",
            "A,E,G",
            "A,E,G,I",
            "A,E,G,I,J",
            "A,E,G,I,K",
            "A,E,H",
            "A,E,H,I",
            "A,E,H,I,J",
            "A,E,H,I,K",
            "L,N",
            "L,N,O",
            "L,N,O,H",
            "L,N,O,H,I",
            "L,N,O,H,I,J",
            "L,N,O,H,I,K",
            "L,N,O,R",
            "L,N,O,R,T",
            "L,N,O,S",
            "L,N,O,S,T",
            "L,N,P",
            "L,N,P,R",
            "L,N,P,R,T",
            "L,N,P,S",
            "L,N,P,S,T",
            "L,N,Q",
            "L,N,Q,T",
            "M,N",
            "M,N,O",
            "M,N,O,H",
            "M,N,O,H,I",
            "M,N,O,H,I,J",
            "M,N,O,H,I,K",
            "M,N,O,R",
            "M,N,O,R,T",
            "M,N,O,S",
            "M,N,O,S,T",
            "M,N,P",
            "M,N,P,R",
            "M,N,P,R,T",
            "M,N,P,S",
            "M,N,P,S,T",
            "M,N,Q",
            "M,N,Q,T"
        ];
        //Crear trayectos anteriores de la red esperados para las pruebas
        var arrayPosiblesAnterioresCorrectos = [
            "J,I",
            "J,I,G",
            "J,I,G,D",
            "J,I,G,D,B",
            "J,I,G,D,B,A",
            "J,I,G,E",
            "J,I,G,E,A",
            "J,I,G,E,C",
            "J,I,G,E,C,A",
            "J,I,H",
            "J,I,H,O",
            "J,I,H,O,C",
            "J,I,H,O,C,A",
            "J,I,H,O,N",
            "J,I,H,O,N,L",
            "J,I,H,O,N,M",
            "J,I,H,E",
            "J,I,H,E,A",
            "J,I,H,E,C",
            "J,I,H,E,C,A",
            "J,I,H,F",
            "J,I,H,F,C",
            "J,I,H,F,C,A",
            "K,I",
            "K,I,G",
            "K,I,G,D",
            "K,I,G,D,B",
            "K,I,G,D,B,A",
            "K,I,G,E",
            "K,I,G,E,A",
            "K,I,G,E,C",
            "K,I,G,E,C,A",
            "K,I,H",
            "K,I,H,O",
            "K,I,H,O,C",
            "K,I,H,O,C,A",
            "K,I,H,O,N",
            "K,I,H,O,N,L",
            "K,I,H,O,N,M",
            "K,I,H,E",
            "K,I,H,E,A",
            "K,I,H,E,C",
            "K,I,H,E,C,A",
            "K,I,H,F",
            "K,I,H,F,C",
            "K,I,H,F,C,A",
            "T,R",
            "T,R,O",
            "T,R,O,C",
            "T,R,O,C,A",
            "T,R,O,N",
            "T,R,O,N,L",
            "T,R,O,N,M",
            "T,R,P",
            "T,R,P,N",
            "T,R,P,N,L",
            "T,R,P,N,M",
            "T,S",
            "T,S,O",
            "T,S,O,C",
            "T,S,O,C,A",
            "T,S,O,N",
            "T,S,O,N,L",
            "T,S,O,N,M",
            "T,S,P",
            "T,S,P,N",
            "T,S,P,N,L",
            "T,S,P,N,M",
            "T,Q",
            "T,Q,N",
            "T,Q,N,L",
            "T,Q,N,M"
        ];

        //Mostrar los trayectos posibles siguientes esperados
        function mostrarSiguientesSupuestos() {
            var html = "";
            for (var trayecto of arrayPosiblesSiguientesCorrectos) {
                html += trayecto+"; ";
            }

            var btnSiguienteTrayectos = document.getElementById('btnSiguiente');
            btnSiguienteTrayectos.innerHTML = html;
        }

        //Mostrar los trayectos posibles anteriores esperados
        function mostrarAnterioresSupuestos() {
            var html = "";
            for (var trayecto of arrayPosiblesAnterioresCorrectos) {
                html += trayecto+"; ";
            }

            var btnAnterioresTrayectos = document.getElementById('btnAnterior');
            btnAnterioresTrayectos.innerHTML = html;
        }

        //Iniciar las pruebas
        <?php if ($iniciado == '1') { ?>
            //Inicializacion de las paradas ingresadas por php
            var paradas = new Array();
            var newParada = null;
            var paradasAnteriores = null;
            var paradasSiguientes = null;

            <?php
            if ($redObj != null) {
                foreach ($redObj->paradas as $parada) {
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
                red = new Red("<?php echo $redObj->nombre ?>", paradas);
                red.crearMapaRecursivo();

                //Muestra los datos actuales del objeto Red
                console.log("---VALORES RED PRUEBAS 1,2,3 y 4---");
                console.log(red);
                <?php
            }
            ?>

            //Iniciar prueba 2.- Confirmar creacion correcta de paradas
            var confirmacion2 = document.getElementById('confirmacion2');
            var resultado2 = document.getElementById('resultado2');
            resultado2.innerHTML = "";
            confirmacion2.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Probar que los valores son correctos
            if (paradasCorrectas.length == red.paradas.length) {
                for (var i=0;i<red.paradas.length;i++){
                    var alert = true;
                    //Se valida el nombre de la parada esperada con la ingresada
                    if (paradasCorrectas[i][0] != red.paradas[i].nombre) {
                        if (resultado2.innerHTML == ""){
                            resultado2.innerHTML = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                        }
                        resultado2.innerHTML += "La parada numero "+(i+1)+" no contiene el mismo nombre<br>";
                        resultado2.innerHTML += "Parada esperada: "+paradasCorrectas[i][0]+"<br>";
                        resultado2.innerHTML += "Parada ingresada: "+red.paradas[i].nombre+"<br>";
                        alert = false;
                    }
                    //Se valida el color de la parada esperada con la ingresada
                    if (paradasCorrectas[i][1] != red.paradas[i].color) {
                        if (resultado2.innerHTML == ""){
                            resultado2.innerHTML = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                        }
                        if (alert) {
                            resultado2.innerHTML += "La parada numero "+(i+1)+" no tiene el mismo color<br>";
                        } else {
                            resultado2.innerHTML += "La parada no tiene el mismo color<br>";
                        }
                        resultado2.innerHTML += "Color esperado: "+paradasCorrectas[i][1]+"<br>";
                        resultado2.innerHTML += "Color ingresado: "+red.paradas[i].color+"<br>";
                        alert = false;
                    }
                    //Se valida las paradas siguientes de la parada esperada con la ingresada
                    var coincideSiguiente = true;
                    for (var s=0;s<red.paradas[i].siguiente.length;s++) {
                        if (paradasCorrectas[i][2][s] != red.paradas[i].siguiente[s]) {
                            coincideSiguiente = false;
                        }
                    }
                    if (!coincideSiguiente) {
                        if (resultado2.innerHTML == ""){
                            resultado2.innerHTML = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                        }
                        if (alert) {
                            resultado2.innerHTML += "La parada numero "+(i+1)+" no contiene las mismas paradas siguientes<br>";
                        } else {
                            resultado2.innerHTML += "La parada no contiene las mismas paradas siguientes<br>";
                        }
                        var resEspSiguientes = "";
                        for (var trayecto of paradasCorrectas[i][2]) {
                            resEspSiguientes += trayecto+" ";
                        }
                        resultado2.innerHTML += "Paradas siguientes esperadas:<br>"+resEspSiguientes+"<br>";
                        var resIngSiguientes = "";
                        for (var trayecto of red.paradas[i].siguiente) {
                            resIngSiguientes += trayecto+" ";
                        }
                        resultado2.innerHTML += "Parada siguientes ingresadas:<br>"+resIngSiguientes+"<br>";
                        alert = false;
                    }
                    //Se valida las paradas anteriores de la parada esperada con la ingresada
                    var coincideAnterior = true;
                    for (var s=0;s<red.paradas[i].anterior.length;s++) {
                        if (paradasCorrectas[i][3][s] != red.paradas[i].anterior[s]) {
                            coincideAnterior = false;
                        }
                    }
                    if (!coincideAnterior) {
                        if (resultado2.innerHTML == ""){
                            resultado2.innerHTML = "<b>Las paradas esperadas y las ingresadas no coinciden</b><br>";
                        }
                        if (alert) {
                            resultado2.innerHTML += "La parada numero "+(i+1)+" no contiene las mismas paradas anteriores<br>";
                        } else {
                            resultado2.innerHTML += "La parada no contiene las mismas paradas anteriores<br>";
                        }
                        var resEspAnterior = "";
                        for (var trayecto of paradasCorrectas[i][3]) {
                            resEspAnterior += trayecto+" ";
                        }
                        resultado2.innerHTML += "Paradas anteriores esperadas:<br>"+resEspAnterior+"<br>";
                        var resIngAnterior = "";
                        for (var trayecto of red.paradas[i].anterior) {
                            resIngAnterior += trayecto+" ";
                        }
                        resultado2.innerHTML += "Parada anteriores ingresadas:<br>"+resIngAnterior+"<br>";
                    }
                }
                //Si coiciden todas las paradas la prueba queda completada de forma exitosa
                if (resultado2.innerHTML == ""){
                    confirmacion2.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado2.innerHTML = "Las paradas esperadas y las ingresadas coinciden";
                }
            } else {
                resultado2.innerHTML = "La cantidad de paradas esperadas y las ingresadas no son las mismas";
            }

            //Iniciar prueba 3.- Confirmar creacion correcta de trayectos siguientes posibles
            var confirmacion3 = document.getElementById('confirmacion3');
            var resultado3 = document.getElementById('resultado3');
            resultado3.innerHTML = "";
            confirmacion3.innerHTML = "<b>PRUEBA FALLIDA</b>";

            if (arrayPosiblesSiguientesCorrectos.length == red.trayectosSiguientes.length) {
                //Se valida los trayectos posibles siguientes de la red creada con los esperados
                for (var i=0;i<red.trayectosSiguientes.length;i++){
                    if (arrayPosiblesSiguientesCorrectos[i] != red.trayectosSiguientes[i]) {
                        if (resultado3.innerHTML == ""){
                            resultado3.innerHTML = "<b>Los trayectos siguientes esperados y los creados no coinciden</b><br>";
                        }
                        resultado3.innerHTML += "El trayecto numero "+(i+1)+" no coincide con el ingresado<br>";
                        resultado3.innerHTML += "Trayecto esperado:<br>"+arrayPosiblesSiguientesCorrectos[i]+"<br>";
                        resultado3.innerHTML += "Trayecto ingresado:<br>"+red.trayectosSiguientes[i]+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado3.innerHTML == ""){
                    confirmacion3.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado3.innerHTML = "Los trayectos siguientes esperados y los creados coinciden";
                }
            } else {
                resultado3.innerHTML = "La cantidad de trayectos siguientes y los creados no son las mismas";
            }

            //Iniciar prueba 4.- Confirmar creacion correcta de trayectos anteriores posibles
            var confirmacion4 = document.getElementById('confirmacion4');
            var resultado4 = document.getElementById('resultado4');
            resultado4.innerHTML = "";
            confirmacion4.innerHTML = "<b>PRUEBA FALLIDA</b>";
            if (arrayPosiblesAnterioresCorrectos.length == red.trayectosAnteriores.length) {
                //Se valida los trayectos posibles anteriores de la red creada con los esperados
                for (var i=0;i<red.trayectosAnteriores.length;i++){
                    if (arrayPosiblesAnterioresCorrectos[i] != red.trayectosAnteriores[i]) {
                        if (resultado4.innerHTML == ""){
                            resultado4.innerHTML = "<b>Los trayectos anteriores esperados y los creados no coinciden</b><br>";
                        }
                        resultado4.innerHTML += "El trayecto numero "+(i+1)+" no coincide con el ingresado<br>";
                        resultado4.innerHTML += "Trayecto esperado:<br>"+arrayPosiblesAnterioresCorrectos[i]+"<br>";
                        resultado4.innerHTML += "Trayecto ingresado:<br>"+red.trayectosAnteriores[i]+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado4.innerHTML == ""){
                    confirmacion4.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado4.innerHTML = "Los trayectos anteriores esperados y los creados coinciden";
                }
            } else {
                resultado4.innerHTML = "La cantidad de trayectos anteriores y los creados no son las mismas";
            }

            //Iniciar prueba 5.- Correcto funcionamiento de buscar trayecto existente con tren sin color, trayectos siguientes
            var confirmacion5 = document.getElementById('confirmacion5');
            var resultado5 = document.getElementById('resultado5');
            resultado5.innerHTML = "";
            confirmacion5.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Se guardan los trayectos esperados
            var trayectosPrueba5 = [
                ['A','C','O','R','T'],
                ['A','C','O','S','T']
            ];
            //Busca el trayecto por parada inicial y final con tren sin color
            var respuestaBusqueda5 = red.buscarTrayectos(red.buscarNombre('A'),red.buscarNombre('T'),0);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 5---");
            console.log(red);
            if (respuestaBusqueda5) {
                for (var t=0;t<red.trayectosReales.length;t++){
                    //Valida los trayectos encontrados con los esperados
                    var coincideTrayecto = true;
                    for (var p=0;p<red.trayectosReales[t].length;p++) {
                        if (red.buscarNombre(trayectosPrueba5[t][p]) != red.trayectosReales[t][p]) {
                            coincideTrayecto = false;
                        }
                    }
                    if (!coincideTrayecto) {
                        if (resultado5.innerHTML == "") {
                            resultado5.innerHTML = "<b>Los trayectos esperados y los buscados no coinciden</b><br>";
                        }
                        resultado5.innerHTML += "El trayecto numero "+(t+1)+" no coincide con el buscado<br>";
                        var resEsperado = "";
                        for (var parada of trayectosPrueba5[t]) {
                            resEsperado += parada.nombre+" ";
                        }
                        resultado5.innerHTML += "Paradas esperadas:<br>"+resEsperado+"<br>";
                        var resBuscado = "";
                        for (var parada of red.trayectosReales[t]) {
                            resBuscado += parada.nombre+" ";
                        }
                        resultado5.innerHTML += "Paradas buscadas:<br>"+resBuscado+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado5.innerHTML == ""){
                    confirmacion5.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado5.innerHTML = "Los trayectos esperados y los encontrados coinciden";
                }
            } else {
                resultado5.innerHTML = "La respuesta fue falsa, deberia ser verdadera ya que existen trayectos";
            }

            //Iniciar prueba 6.- Correcto funcionamiento de buscar trayecto existente con tren sin color, trayectos anteriores
            var confirmacion6 = document.getElementById('confirmacion6');
            var resultado6 = document.getElementById('resultado6');
            resultado6.innerHTML = "";
            confirmacion6.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Se guardan los trayectos esperados
            var trayectosPrueba6 = [
                ['J','I','G','E','C'],
                ['J','I','H','O','C'],
                ['J','I','H','E','C'],
                ['J','I','H','F','C']
            ];
            //Busca el trayecto por parada inicial y final con tren sin color
            var respuestaBusqueda6 = red.buscarTrayectos(red.buscarNombre('J'),red.buscarNombre('C'),0);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 6---");
            console.log(red);
            if (respuestaBusqueda6) {
                for (var t=0;t<red.trayectosReales.length;t++){
                    //Valida los trayectos encontrados con los esperados
                    var coincideTrayecto = true;
                    for (var p=0;p<red.trayectosReales[t].length;p++) {
                        if (red.buscarNombre(trayectosPrueba6[t][p]) != red.trayectosReales[t][p]) {
                            coincideTrayecto = false;
                        }
                    }
                    if (!coincideTrayecto) {
                        if (resultado6.innerHTML == "") {
                            resultado6.innerHTML = "<b>Los trayectos esperados y los buscados no coinciden</b><br>";
                        }
                        resultado6.innerHTML += "El trayecto numero "+(t+1)+" no coincide con el buscado<br>";
                        var resEsperado = "";
                        for (var parada of trayectosPrueba6[t]) {
                            resEsperado += parada+" ";
                        }
                        resultado6.innerHTML += "Paradas esperadas:<br>"+resEsperado+"<br>";
                        var resBuscado = "";
                        for (var parada of red.trayectosReales[t]) {
                            resBuscado += parada.nombre+" ";
                        }
                        resultado6.innerHTML += "Paradas buscadas:<br>"+resBuscado+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado6.innerHTML == ""){
                    confirmacion6.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado6.innerHTML = "Los trayectos esperados y los encontrados coinciden";
                }
            } else {
                resultado6.innerHTML = "La respuesta fue falsa, deberia ser verdadera ya que existen trayectos";
            }

            //Iniciar prueba 7.- Correcto funcionamiento de buscar trayecto no existente con tren sin color
            var confirmacion7 = document.getElementById('confirmacion7');
            var resultado7 = document.getElementById('resultado7');
            resultado7.innerHTML = "";
            confirmacion7.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Busca el trayecto por parada inicial y final con tren sin color
            var respuestaBusqueda7 = red.buscarTrayectos(red.buscarNombre('A'),red.buscarNombre('N'),0);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 7---");
            console.log(red);
            //Valida que no encuentre trayecto
            if (respuestaBusqueda7) {
                resultado7.innerHTML = "La respuesta fue verdadera, deberia ser falsa ya que no existen trayectos";
            } else {
                //Si devuelve false la prueba queda completada de forma exitosa
                confirmacion7.innerHTML = "<b>PRUEBA EXITOSA</b>";
                resultado7.innerHTML = "La respuesta fue falsa, no existen trayectos";
            }

            //Iniciar prueba 8.- Correcto funcionamiento de buscar trayecto existente con tren color verde, trayectos siguientes
            var confirmacion8 = document.getElementById('confirmacion8');
            var resultado8 = document.getElementById('resultado8');
            resultado8.innerHTML = "";
            confirmacion8.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Se guardan los trayectos esperados
            var trayectosPrueba8 = [
                ['A','C','O','R','T'],
                ['A','C','O','T'],
            ];
            //Busca el trayecto por parada inicial y final con tren color verde
            var respuestaBusqueda8 = red.buscarTrayectos(red.buscarNombre('A'),red.buscarNombre('T'),1);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 8---");
            console.log(red);
            if (respuestaBusqueda8) {
                for (var t=0;t<red.trayectosReales.length;t++){
                    //Valida los trayectos encontrados con los esperados
                    var coincideTrayecto = true;
                    for (var p=0;p<red.trayectosReales[t].length;p++) {
                        if (red.buscarNombre(trayectosPrueba8[t][p]) != red.trayectosReales[t][p]) {
                            coincideTrayecto = false;
                        }
                    }
                    if (!coincideTrayecto) {
                        if (resultado8.innerHTML == "") {
                            resultado8.innerHTML = "<b>Los trayectos esperados y los buscados no coinciden</b><br>";
                        }
                        resultado8.innerHTML += "El trayecto numero "+(t+1)+" no coincide con el buscado<br>";
                        var resEsperado = "";
                        for (var parada of trayectosPrueba8[t]) {
                            resEsperado += parada+" ";
                        }
                        resultado8.innerHTML += "Paradas esperadas:<br>"+resEsperado+"<br>";
                        var resBuscado = "";
                        for (var parada of red.trayectosReales[t]) {
                            resBuscado += parada.nombre+" ";
                        }
                        resultado8.innerHTML += "Paradas buscadas:<br>"+resBuscado+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado8.innerHTML == ""){
                    confirmacion8.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado8.innerHTML = "Los trayectos esperados y los encontrados coinciden";
                }
            } else {
                resultado8.innerHTML = "La respuesta fue falsa, deberia ser verdadera ya que existen trayectos";
            }

            //Iniciar prueba 9.- Correcto funcionamiento de buscar trayecto existente con tren color verde, trayectos anteriores
            var confirmacion9 = document.getElementById('confirmacion9');
            var resultado9 = document.getElementById('resultado9');
            resultado9.innerHTML = "";
            confirmacion9.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Se guardan los trayectos esperados
            var trayectosPrueba9 = [
                ['K','I','E','C'],
                ['K','I','O','C'],
                ['K','I','E','C'],
                ['K','I','F','C'],
            ];
            //Busca el trayecto por parada inicial y final con tren color verde
            var respuestaBusqueda9 = red.buscarTrayectos(red.buscarNombre('K'),red.buscarNombre('C'),1);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 9---");
            console.log(red);
            if (respuestaBusqueda9) {
                for (var t=0;t<red.trayectosReales.length;t++){
                    //Valida los trayectos encontrados con los esperados
                    var coincideTrayecto = true;
                    for (var p=0;p<red.trayectosReales[t].length;p++) {
                        if (red.buscarNombre(trayectosPrueba9[t][p]) != red.trayectosReales[t][p]) {
                            coincideTrayecto = false;
                        }
                    }
                    if (!coincideTrayecto) {
                        if (resultado9.innerHTML == "") {
                            resultado9.innerHTML = "<b>Los trayectos esperados y los buscados no coinciden</b><br>";
                        }
                        resultado9.innerHTML += "El trayecto numero "+(t+1)+" no coincide con el buscado<br>";
                        var resEsperado = "";
                        for (var parada of trayectosPrueba9[t]) {
                            resEsperado += parada+" ";
                        }
                        resultado9.innerHTML += "Paradas esperadas:<br>"+resEsperado+"<br>";
                        var resBuscado = "";
                        for (var parada of red.trayectosReales[t]) {
                            resBuscado += parada.nombre+" ";
                        }
                        resultado9.innerHTML += "Paradas buscadas:<br>"+resBuscado+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado9.innerHTML == ""){
                    confirmacion9.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado9.innerHTML = "Los trayectos esperados y los encontrados coinciden";
                }
            } else {
                resultado9.innerHTML = "La respuesta fue falsa, deberia ser verdadera ya que existen trayectos";
            }

            //Iniciar prueba 10.- Correcto funcionamiento de buscar trayecto no existente con tren color verde, primera parada diferente color
            var confirmacion10 = document.getElementById('confirmacion10');
            var resultado10 = document.getElementById('resultado10');
            resultado10.innerHTML = "";
            confirmacion10.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Busca el trayecto por parada inicial y final con tren color verde
            var respuestaBusqueda10 = red.buscarTrayectos(red.buscarNombre('L'),red.buscarNombre('T'),1);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 10---");
            console.log(red);
            //Valida que no encuentre trayecto
            if (respuestaBusqueda10) {
                resultado10.innerHTML = "La respuesta fue verdadera, deberia ser falsa ya que la primera parada es de otro color";
            } else {
                //Si devuelve false la prueba queda completada de forma exitosa
                confirmacion10.innerHTML = "<b>PRUEBA EXITOSA</b>";
                resultado10.innerHTML = "La respuesta fue falsa, no existen trayectos ya que la primera parada es de otro color";
            }

            //Iniciar prueba 11.- Correcto funcionamiento de buscar trayecto existente con tren color rojo, trayectos siguientes
            var confirmacion11 = document.getElementById('confirmacion11');
            var resultado11 = document.getElementById('resultado11');
            resultado11.innerHTML = "";
            confirmacion11.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Se guardan los trayectos esperados
            var trayectosPrueba11 = [
                ['A','C','T'],
                ['A','C','S','T']
            ];
            //Busca el trayecto por parada inicial y final con tren color rojo
            var respuestaBusqueda11 = red.buscarTrayectos(red.buscarNombre('A'),red.buscarNombre('T'),2);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 11---");
            console.log(red);
            if (respuestaBusqueda11) {
                for (var t=0;t<red.trayectosReales.length;t++){
                    //Valida los trayectos encontrados con los esperados
                    var coincideTrayecto = true;
                    for (var p=0;p<red.trayectosReales[t].length;p++) {
                        if (red.buscarNombre(trayectosPrueba11[t][p]) != red.trayectosReales[t][p]) {
                            coincideTrayecto = false;
                        }
                    }
                    if (!coincideTrayecto) {
                        if (resultado11.innerHTML == "") {
                            resultado11.innerHTML = "<b>Los trayectos esperados y los buscados no coinciden</b><br>";
                        }
                        resultado11.innerHTML += "El trayecto numero "+(t+1)+" no coincide con el buscado<br>";
                        var resEsperado = "";
                        for (var parada of trayectosPrueba11[t]) {
                            resEsperado += parada+" ";
                        }
                        resultado11.innerHTML += "Paradas esperadas:<br>"+resEsperado+"<br>";
                        var resBuscado = "";
                        for (var parada of red.trayectosReales[t]) {
                            resBuscado += parada.nombre+" ";
                        }
                        resultado11.innerHTML += "Paradas buscadas:<br>"+resBuscado+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado11.innerHTML == ""){
                    confirmacion11.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado11.innerHTML = "Los trayectos esperados y los encontrados coinciden";
                }
            } else {
                resultado11.innerHTML = "La respuesta fue falsa, deberia ser verdadera ya que existen trayectos";
            }

            //Iniciar prueba 12.- Correcto funcionamiento de buscar trayecto existente con tren color rojo, trayectos anteriores
            var confirmacion12 = document.getElementById('confirmacion12');
            var resultado12 = document.getElementById('resultado12');
            resultado12.innerHTML = "";
            confirmacion12.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Se guardan los trayectos esperados
            var trayectosPrueba12 = [
                ['J','G','C'],
                ['J','H','C'],
                ['J','H','C'],
                ['J','H','F','C']
            ];
            //Busca el trayecto por parada inicial y final con tren color rojo
            var respuestaBusqueda12 = red.buscarTrayectos(red.buscarNombre('J'),red.buscarNombre('C'),2);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 12---");
            console.log(red);
            if (respuestaBusqueda12) {
                for (var t=0;t<red.trayectosReales.length;t++){
                    //Valida los trayectos encontrados con los esperados
                    var coincideTrayecto = true;
                    for (var p=0;p<red.trayectosReales[t].length;p++) {
                        if (red.buscarNombre(trayectosPrueba12[t][p]) != red.trayectosReales[t][p]) {
                            coincideTrayecto = false;
                        }
                    }
                    if (!coincideTrayecto) {
                        if (resultado12.innerHTML == "") {
                            resultado12.innerHTML = "<b>Los trayectos esperados y los buscados no coinciden</b><br>";
                        }
                        resultado12.innerHTML += "El trayecto numero "+(t+1)+" no coincide con el buscado<br>";
                        var resEsperado = "";
                        for (var parada of trayectosPrueba12[t]) {
                            resEsperado += parada+" ";
                        }
                        resultado12.innerHTML += "Paradas esperadas:<br>"+resEsperado+"<br>";
                        var resBuscado = "";
                        for (var parada of red.trayectosReales[t]) {
                            resBuscado += parada.nombre+" ";
                        }
                        resultado12.innerHTML += "Paradas buscadas:<br>"+resBuscado+"<br>";
                    }
                }
                //Si coiciden todos los trayectos la prueba queda completada de forma exitosa
                if (resultado12.innerHTML == ""){
                    confirmacion12.innerHTML = "<b>PRUEBA EXITOSA</b>";
                    resultado12.innerHTML = "Los trayectos esperados y los encontrados coinciden";
                }
            } else {
                resultado12.innerHTML = "La respuesta fue falsa, deberia ser verdadera ya que existen trayectos";
            }

            //Iniciar prueba 13.- Correcto funcionamiento de buscar trayecto no existente con tren color rojo, ultima parada diferente color
            var confirmacion13 = document.getElementById('confirmacion13');
            var resultado13 = document.getElementById('resultado13');
            resultado13.innerHTML = "";
            confirmacion13.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Busca el trayecto por parada inicial y final con tren color rojo
            var respuestaBusqueda13 = red.buscarTrayectos(red.buscarNombre('J'),red.buscarNombre('O'),2);
            //Muestra los datos actuales del objeto Red
            console.log("---VALORES RED PRUEBA 13---");
            console.log(red);
            //Valida que no encuentre trayecto
            if (respuestaBusqueda13) {
                resultado13.innerHTML = "La respuesta fue verdadera, deberia ser falsa ya que la ultima parada es de otro color";
            } else {
                //Si devuelve false la prueba queda completada de forma exitosa
                confirmacion13.innerHTML = "<b>PRUEBA EXITOSA</b>";
                resultado13.innerHTML = "La respuesta fue falsa, no existen trayectos ya que la ultima parada es de otro color";
            }

            //Iniciar prueba 14.- Correcto funcionamiento de buscar trayecto no existente con la primera y ultima parada siendo la misma
            var confirmacion14 = document.getElementById('confirmacion14');
            var resultado14 = document.getElementById('resultado14');
            resultado14.innerHTML = "";
            confirmacion14.innerHTML = "<b>PRUEBA FALLIDA</b>";
            //Busca el trayecto por parada inicial y final con tren sin color
            var respuestaBusqueda14 = red.buscarTrayectos(red.buscarNombre('A'),red.buscarNombre('A'),0);
            console.log("---VALORES RED PRUEBA 14---");
            console.log(red);
            //Valida que no encuentre trayecto
            if (respuestaBusqueda14) {
                resultado14.innerHTML = "La respuesta fue verdadera, deberia ser falsa ya que la primera y ultima parada son la misma";
            } else {
                //Si devuelve false la prueba queda completada de forma exitosa
                confirmacion14.innerHTML = "<b>PRUEBA EXITOSA</b>";
                resultado14.innerHTML = "La respuesta fue falsa, no existen trayectos ya que la primera y ultima parada son la misma";
            }
        <?php } ?>
    </script>
</html>