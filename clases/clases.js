//Las clases de las paradas y la red completa

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

    //Crea los mapas de trayectos siguientes y anteriores posibles de la red
    crearMapaRecursivo() {
        //Borra el dato que se haya guardado temporalmente
        this.trayectoTemp = "";
        //Busca las paradas sin parada anterior, buscando las primeras paradas de la red
        for (var paradaRootSig of this.paradas) {
            if (paradaRootSig.anterior.length == 0) {
                //Llama a la funcion recursiva para encontrar todos los trayectos posibles siguientes
                this.crearMapasSiguienteRecursivo(paradaRootSig);
                this.trayectoTemp = "";
            }
        }
        //Busca las paradas sin parada siguiente, buscando las ultimas paradas de la red
        for (var paradaRootAnt of this.paradas) {
            if (paradaRootAnt.siguiente.length == 0) {
                //Llama a la funcion recursiva para encontrar todos los trayectos posibles anteriores
                this.crearMapasAnteriorRecursivo(paradaRootAnt);
                this.trayectoTemp = "";
            }
        }
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

    buscarTrayectos(paradaIni, paradaFin, colorTren) {
        //Borra los trayectos encontrados antiguos
        this.trayectosReales = new Array();

        //Se comprueba las paradas de inicio y fin
        if (paradaIni != paradaFin) {
            if (paradaIni.color != 0 && colorTren != 0) {
                //Si la primera parada no es de un color permitido, devuelve false
                if (colorTren != paradaIni.color) {
                    return false;
                }
            }
            if (paradaFin.color != 0 && colorTren != 0) {
                //Si la ultima parada no es de un color permitido, devuelve false
                if (colorTren != paradaFin.color) {
                    return false;
                }
            }
        } else {
            //Si las paradas son iguales, devuelve false
            return false;
        }
        //Se busca el trayecto siguiente real
        red.buscarTrayectoSiguiente(paradaIni, paradaFin, colorTren);

        //Si no se encuentra un trayecto siguiente, se busca en los anteriores
        if (this.trayectosReales.length == 0) {
            red.buscarTrayectoAnterior(paradaIni, paradaFin, colorTren);
            if (this.trayectosReales.length != 0) {
                return true;
            }
        } else {
            return true;
        }

        return false;
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