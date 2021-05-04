<?php
header('Content-Type: application/json');


require_once("classes/class.SQL.php");


$sql = new SQL();

$res = $sql->getAll();
//echo $res;



$aAlimento = array();
$aDivorcio = array();
$aInvestigacao = array();
$aInventario= array();
$aOutros = array();


$horaInicio = date('d-m-Y H-i-s');

foreach($res as $v){
    $texto = utf8_encode($v['ra_conteudo']);
    if( strpos( $texto , "4ª Vara da Família e Sucessões")){
        
        
        $pos = stripos($texto, "JUIZ(A) DE DIREITO ");       
        $juiz = substr($texto, $pos+19, 100); 
        $vet = explode(" ",$juiz);
        $string = "";
        for($i=0; $i <= count($vet); $i++ ){
            if(stripos($vet[$i], "ESCRIVÃ(O)")){
                $o = explode("\n", $vet[$i]);
                $string .= $o[0]." ";
                break;
            }
            $string .= $vet[$i]." ";
           
        }


        $juiz = trim($string);
        $pos = stripos($texto, "Processo ");
        $processo = substr($texto, $pos+9, 25);

        $vetor = [
            "processo"  =>  $processo,
            "juiz"      =>  $juiz,
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






