# StreamConverter
Esta librería nos permite realizar la gestiona los contenidos de ficheros en distintos formatos.

# Cómo se usa
## Lectura
Veamos un ejemplo en CSV:

```php
include 'vendor/autoload.php';

use handler\CSV as CSVHandler;

$csvHandler = new CSVHandler();
$usuario = $csvHandler->read("./files/csv/usuario.csv");

var_dump($usuario);
```

## Escribir
Veamos un ejemplos en JSON:

```php
include 'vendor/autoload.php';

use handler\JSON as JSONHandler;

$data = [
    'dato1' => 1,
    'dato2' => 2,
    'dato3' => '3'
];

$jsonHandler = new JSONHandler();
$usuario = $jsonHandler->write("./files/json/data.json", $data);
```

## Lectura/escritura de un fichero, transformando el formato

```php
include 'vendor/autoload.php';

use handler\CSV as CSVHandler;
use handler\JSON as JSONHandler;
use converter\Manager;

$manager = new Manager();
$manager->convert(
    new CSVConverter(),
    new JSONHandler(),
    "./files/csv/ejemplo.csv",
    "./files/json/usuario.json"
);

```