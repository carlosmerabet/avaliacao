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

$z = 100;
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

        $processoInteiro = str_replace(".", "", $processo);
        $processoInteiro = str_replace("-", "", $processoInteiro);

        $vetor = [
            "inteiro"   =>  $processoInteiro,
            "processo"  =>  $processo,
            "juiz"      =>  $juiz,
            "conteudo"  =>  $texto
        ];

        if(strpos($texto, "Alimentos")){

            $ordernarAlimentos[]= $processoInteiro;
            $aAlimento[]=$vetor;

        }else if(strpos($texto, "Divórcio")){
            $ordernarDivorcio[]= $processoInteiro;
            $aDivorcio[]=$vetor;
        }else if(strpos($texto, "Investigação de Paternidade")){
            $ordernarInvestigacao[]= $processoInteiro;
            $aInvestigacao[] = $vetor;
        }else if(strpos($texto, "Inventário")){
            $ordernarInventario[]= $processoInteiro;
            $aInventario[] = $vetor;
        }else{
            $orderOutros[]= $processoInteiro;
            $aOutros[] = $vetor;
        }

        $z--;
    }

    
}

array_multisort($ordernarAlimentos, SORT_ASC, $aAlimento);  
array_multisort($ordernarDivorcio, SORT_ASC, $aDivorcio);  
array_multisort($ordernarInvestigacao, SORT_ASC, $aInvestigacao);  
array_multisort($ordernarInventario, SORT_ASC, $aInventario);  
array_multisort($ordernarOutros, SORT_ASC, $aOutros);  


$vetorGeral["alimento"] = $aAlimento;
$vetorGeral["divorcio"] = $aDivorcio;
$vetorGeral["investigacao"] = $aInvestigacao;
$vetorGeral["inventario"] = $aInventario;
$vetorGeral["outros"] = $aOutros;

#echo "<pre>".print_r($vetorGeral,true); exit;
$horaFim = date('d-m-Y H-i-s');
$fp = fopen('item5.2-inicio-'.$horaInicio.'-fim-'.$horaFim.'.json', 'w');
fwrite($fp, json_encode($vetorGeral));
fclose($fp);


#echo json_encode($vetorGeral);

?>






