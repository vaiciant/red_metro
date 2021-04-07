# Creación de Red de Metro y Cálculo de Paradas
## Descripción
Aplicación web en la cual se puede ingresar una red de metro y calcular el menor trayecto entre dos paradas y un color de tren.
Está escrito en PHP y Javascript, ocupando HTML y CSS para visualizarlo.

## Instalación
Se puede ocupar un servidor con PHP habilitado para el funcionamiento del software, se ocupara XAMPP para la explicación de instalación.

Inicializar el servidor apache en XAMPP.

Se debe descargar todos los archivos de este repositorio para que funcione correctamente el software.
Estos archivos se deben mover a la carpeta .../xampp/htdocs/ 

Ir a http://localhost e interactuar con la aplicación web.

## Formato archivo .red
El archivo .red es un archivo de texto con extensión [nombre_archivo].red el formato es el siguiente:
```
[nombre_parada]|[color_parada]|[{nombres_paradas_anteriores|...} = []],[nombre_parada]|[color_parada]|[{nombres_paradas_anteriores|...} = []],...
```

Los colores de las paradas son los siguientes:
```
0 = sin color
1 = verde
2 = rojo
```

Un ejemplo de un archivo .red:

```
A|0,B|0|A,C|1|B,D|2|B,E|0|C|D
```

Con este ejemplo crearan cuatro paradas, {A,B,C,D,E}, siendo C verde y D rojo. Con estos datos se calculan los trayectos de inicio a fin, filtrando por color, mostrando el trayecto que tiene menos paradas al principio.
