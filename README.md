# Cumplimiento de actividades

El modulo creado se llama Consulta API.

Tomando en cuenta los puntos asignados para realizar el modulo en drupal 7:



## 1. Crear un modulo de drupal 7 con service api el cual nos liste la información de la siguiente api json https://jsonplaceholder.typicode.com/posts dentro de una tabla del modulo creado.

Para la creación del modulo se crean los archivos de configuración iniciales, el .info y .module. En el archivo api7_estvi.modulo se implementan diferentes hooks para hacer la consulta de los datos dispueston en el endpoint https://jsonplaceholder.typicode.com/posts.

El primer hook implementado es el hook_menu() para definir rutas en el sistema, la primera es /apil y permite visualziar los datos en la tabla, mientras que la segunda /apil/descargar cuenta con las funciones y estructuras necesarias para descargar los datos en formato CSV.



```sh
/*
Función para crear el path donde se mostraran los datos y descargara el archivo en formato csv
 */
function api7_estvi_menu()
{
    $items['apil'] = array(
        'title' => t('Tabla con datos'),
        'page callback' => 'api7_estvi_list',
        'access arguments' => array('access content'),
    );

    $items['apil/descargar'] = array(
        'title' => t('Descargar datos'),
        'page callback' => 'api7_estvi_descarga',
        'access arguments' => array('access content'),

    );

    return $items;
}
```

La segunda función api7_estvi_list() se encarga de realizar la consulta al endpoint en conjunto con drupal_http_request() para manejar la petición. Una vez se realiza la petición se hace un decode de los datos para que puedan ser interpretados como un arreglo, retornando el tema donde seran renderizadas.


```sh
/*Presentación de datos */
function api7_estvi_list()
{

    $url = 'https://jsonplaceholder.typicode.com/posts';

    $options = [
        'http' => [
            'method' => 'GET',

        ],
    ];
    $response = drupal_http_request($url, $options);
    $values = $response->data;
    $datos = drupal_json_decode($response->data);

    $json_response = drupal_json_decode($response->data);

    /*Se retorna el templeta para renderizar */
    return theme('api7_estvi', array('datos_api' => $json_response));

}

```

Para renderizar los datos en la plantilla api7-estvi.tpl.php se implementa el hook theme. 

```sh
function api7_estvi_theme()
{
    return array(
        'api7_estvi' => array(
            'template' => 'api7-estvi', // Se indica el template a consumir
            'path' => drupal_get_path('module', 'api7_estvi') . '/templates', //Se indica la ruta del archivo
            'variables' => array(
                'datos_api' => null, //Se proporcional la variable datos_api, con el valor de null por defecto
            ),
        ),
    );
}
```
## 2. Del servicio consumido anteriormente y dentro de la tabla generada, crear un export en csv o excel para poder descargar los datos consumidos

Para descargar los datos en formato CSV se crea una nueva función donde se consume el endpoint nuevamente y seguidamente se definen las cabeceras del archivo a descargar, creandolo a través de un stream.

```sh
function api7_estvi_descarga()
{


    /**
     * 
     * 
     * Obtención de datos a través de API
     */

    $url = 'https://jsonplaceholder.typicode.com/posts';

    $options = [
        'http' => [
            'method' => 'GET',

        ],
    ];
    $response = drupal_http_request($url, $options);
    $values = $response->data;
    $datos = drupal_json_decode($response->data);

    $json_response = drupal_json_decode($response->data);

//Encabezado https
    drupal_add_http_header('Content-Type', 'text/csv; utf-8');
    drupal_add_http_header('Content-Disposition', 'attachment; filename = datos.csv');


    $fo = fopen('php://output', 'w');
    fputcsv($fo, array(t('Userid'), t('Id'),t('Title'),t('Body')));


    foreach ($json_response as $valores) {
        fputcsv($fo, array($valores['userId'], $valores['id'], $valores['title'], $valores['body']));
    }

    fclose($fo);
}
```

## 3. Los estilos agregados suman mas puntos, junto a la organización del código.

No se agregan estilos adicionales.

En el template creado se recorren cada uno de los datos del arreglo y se presentan en una tabla.

![image](https://user-images.githubusercontent.com/87027597/206201156-74946f37-66a1-40dd-af81-e4ba5aaea710.png)



Para validar el funcionamiento del modulo puede entrar a la dirección https://estrenar-vivienda7.sebastianzapateiro.tech

