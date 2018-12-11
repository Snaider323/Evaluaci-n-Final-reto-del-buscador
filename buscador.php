<?php
//Archivo php para llamada AJAX. 
$ciudad = htmlspecialchars($_GET['ciudad']);
$tipo = htmlspecialchars($_GET['tipo']);
$precio = htmlspecialchars($_GET['precio']);

$pos = strpos($precio, ';');

$min = substr($precio, 0, $pos);
$max = substr($precio, $pos+1);

$file = fopen("data-1.json", "r") or die("No se puede abrir el archivo");

$json = fread($file, filesize('data-1.json'));
$data = json_decode($json, true);

//primer filtro de precios
$r = array();
foreach($data as $i){
    $p = $i['Precio'];
    $p = substr($p, strpos($p,'$')+1);
    $c = strpos($p,',');
    $p = substr($p,0,$c).substr($p,$c+1);
    if($p>=$min && $p<=$max){
        array_push($r, $i);
    }
}

$result = array();
if(!empty($ciudad) && !empty($tipo)){
    foreach($r as $e){
        if($e['Ciudad']==$ciudad && $e['Tipo']==$tipo){
            array_push($result, $e);
        }
    }
} elseif(!empty($ciudad)){
    foreach($r as $e){
        if($e['Ciudad']==$ciudad){
            array_push($result, $e);
        }
    }
} elseif(!empty($tipo)){
    foreach($r as $e){
        if($e['Tipo']==$tipo){
            array_push($result, $e);
        }
    }
} else {
    $result = $r;
}


$rjson = json_encode($result);
echo '{"result":"success", "message":"Resultados obtenidos exitosamente", "data":'.$rjson.'}';

fclose($file);
?>
