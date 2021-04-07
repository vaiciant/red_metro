<?php
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
?>