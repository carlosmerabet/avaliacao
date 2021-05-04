<?php
header('Content-Type: application/json');


require_once("classes/class.SQL.php");

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
$sql = new SQL();

$res = $sql->getAll();
//echo $res;

$vetorGeral = array();

$aAlimento = array();
$aDivorcio = array();
$aInvestigacao = array();
$aInventario= array();
$aOutros = array();


$horaInicio = date('d-m-Y H-i-s');

foreach($res as $v){
    $texto = utf8_encode($v['ra_conteudo']);

    if( strpos( $texto , "4ª Vara da Família e Sucessões")){

    }else{

        $vetor = [

            "conteudo"  =>  $texto
        ];

        $vetorGeral[]=$vetor;    
        
    }

}



$horaFim = date('d-m-Y H-i-s');
$fp = fopen('item5.3-inicio-'.$horaInicio.'-fim-'.$horaFim.'.json', 'w');
fwrite($fp, json_encode($vetorGeral));
fclose($fp);



?>






