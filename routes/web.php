<?php

use Facade\FlareClient\Http\Response;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Route;
 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\ElseIf_;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
   
Route::get('/', function () {
     
 
 $ex = "David" . "\n" . "angel";


   $image = QrCode::format('png')
   ->merge(Storage::disk('public')->get('app', 'logo-cobres.png'), 0.2, true)
   ->size(180)->errorCorrection('L')
   ->generate($ex);
    
   return response($image)->header('Content-type','image/png');

});
  

 // En el excel los datos son pasados mediante una cadena de texto en el link
 // En el excel se cambia los caracteres especiales restringidos por las reglas http, ejm = { =  #  /  $ }
 // En Php se revierten los cambios, pasandolos nuevamente al origen

 // RUTA GOCCO - ruta modificable, el valor por parametro ($valor1) contiene en cadena toda la informacion de la matriz
 Route::get('/generarQR1/{valor1}', function ($valor1 = null) {

  // 1. Se toma la variable ($valor1), teniendo los datos separados por punto y coma
  //    ";" los caracteres permitidos son ejm = { ; - _ ** }
  //
  // 2. La funcion explode crea una lista de datos que son separados por los carateres permitidos en el paso 1
  //    Ejemplo de la informacion suministrada desde excel =>
  //    "esto;es;un;ejemplo
  //    con explode($CARACTER_ESPECIAL, TEXTO CUALQUIERA )
  //    TRANSFORMACION DE LA LISTA
  //    Indice ====  0     1    2      3
  //    $partes = {"esto","es","un","ejemplo"}  --> vista de ejemplo de una lista
  //    $partes[INDICE-POSICION] --> $partes[0] -> Resultado -> "esto"

  $partes = explode(";",$valor1);
  // 3. Se inicializa una variable vacia, en la cual se almacenara en texto y se les dara un salto de linea para visualizacion
  $ex= "";
  // 
  $partes[0] = str_replace("--","/",$partes[0]);
  $partes[0] = str_replace("_","#",$partes[0]);
  
  $ex = "MATRICERIA GOCCO" . "\n";

  $ex = $ex . "\n" . "Referencia :" . $partes[0]  . "\n";
  
  if(empty($partes[3])){
    $ex = $ex . "Medida MM Salida Matriz :" . $partes[1]  . "\n"  ;
    $ex = $ex . "Altura de Matriz :" . $partes[2];
  } else {
    $ex = $ex . "Forma :" . $partes[1] . "\n" ; 
    $ex = $ex . "Numero de nucleos :" . $partes[2]  . "\n" ; 
    $ex = $ex . "Medida del nucleo en MM :" . $partes[3]  . "\n" ; 
    $ex = $ex . "Tipo de nucleo : " . $partes[4]  . "\n" ; 
    $ex = $ex . "Medida MM Salida Matriz :" . $partes[5]  . "\n" ;
    $ex = $ex . "Altura de Matriz :" . $partes[6];
  }
  
    //Guardo la libreria QrCode en formato PNG, se Puede cambiar por .svg , .jpg
    $image = QrCode::format('png')
  
    // merge -> pasa la ubicacion de la imagen del qr,  
    //Parametros  ->get(Ruta,tamaño de la imagen,fordes o super poner) true es quitar bordes y ajustar
    ->merge(Storage::disk('public')->get('app', 'logo-cobres.png'), 0.3, true)
    // tamaño de la imagen y correcion y ajuste de pixeles, I , L , H
    ->size(250)->errorCorrection('L')
    // genero el qr en la ruta
    ->generate($ex);
     
    // la almaceno en la variable de salida
    $output_file = '/img/qrgenerado.png';
    // puntero de guardado del qr
    Storage::disk('public')->put($output_file, $image);
    // ubicacion del qr almacenado en el proyecto 
    $output_file2 = storage_path('app/public/img/qrgenerado.png');
    // Descarga la imagen
    return response()->download($output_file2); 
 });


 // HILERAS
 Route::get('/generarQR2/{valor1}/', function ($valor1 = null) {

  
  $partes = explode(";",$valor1);
  $ex= "";
  $partes[0] = str_replace("**","/",$partes[0]);
  $partes[0] = str_replace("_","#",$partes[0]);

  $ex = "Hileras" . "\n";

  $ex = "Referencia :" . $partes[0] . $partes[1] . "\n";
  
  if($partes[3] == "COBRES"){
    $ex = $ex .  "Banco : Calibrado 08 cobre";
  }elseif($partes[3] == "LATON"){
    $ex = $ex .  "Banco : Calibrado  48 latón";
  }
  //$ex = $ex . "MM del Nucleo: " . "\n" ; 
  $ex = $ex . "Material del nucleo : " . $partes[2]  . "\n" ; 
  $ex = $ex . "Linea de produccion : " . $partes[3]  . "\n" ; 
  $ex = $ex . "Procendencia : ". $partes[5]  . "\n" ;
  $ex = $ex . "Tamaño base : ". $partes[6];
  

    $image = QrCode::format('png')
    
    ->merge(Storage::disk('public')->get('app', 'logo-cobres.png'), 0.3, true)
    ->size(250)->errorCorrection('L')
    ->generate($ex);
      
    $output_file = '/img/qrgenerado.png';
    Storage::disk('public')->put($output_file, $image);
    $output_file2 = storage_path('app/public/img/qrgenerado.png');
    //return response($image)->header('Content-type','image/png');
    return response()->download($output_file2); 
 });



 // Krupp
 Route::get('/generarQR3/{valor1}/', function ($valor1 = null) {

  
  $partes = explode(";",$valor1);
  $ex= "";
  $partes[0] = str_replace("++","/",$partes[0]);
  $partes[0] = str_replace("_","#",$partes[0]);

  //$ex = "MATRICERIA KRUPP" . "\n";
  $ex =  $ex . "Referencia :" . $partes[0]  . "\n";
  $ex = $ex . "Forma " . $partes[1] . "\n" ; 
  $ex = $ex . "Numero de nucleos : " . $partes[2]  . "\n" ; 
  $ex = $ex . "Medida del nucleo en MM : " . $partes[3]  . "\n" ; 
  $ex = $ex . "Tipo de nucleo : ". $partes[5]  . "\n" ;
  $ex = $ex . "Medida de salida en MM : ". $partes[6];
  $ex = $ex . "Base pulgada : ". $partes[7];
   

    $image = QrCode::format('png')
    
    ->merge(Storage::disk('public')->get('app', 'logo-cobres.png'), 0.3, true)
    ->size(250)->errorCorrection('L')
    ->generate($ex);
     
    $output_file = '/img/qrgenerado.png';
    Storage::disk('public')->put($output_file, $image);
    $output_file2 = storage_path('app/public/img/qrgenerado.png');
    //return response($image)->header('Content-type','image/png');
    return response()->download($output_file2); 
 });


?>