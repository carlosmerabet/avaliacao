<?php

//teste para commit

require_once("class.Conexao.php");

session_start();


class SQL{

	var $conexao;

	
	function SQL(){ 
		$oConexao = new Conexao();
		$this->conexao = $oConexao->conexao;
        
	}
    function beginTrans() {
    	//$this->begin;
    	mysqli_exec($this->conexao, "BEGIN");
    	//$this->conexao->execute("BEGIN");
       // $this->execute("BEGIN");
    }

    function commitTrans() {
    	mysqli_exec($this->conexao, "COMMIT");
        //$this->execute("COMMIT");
    }

    function rollBackTrans() {
    	mysqli_exec($this->conexao, "ROLLBACK");
        //$this->execute("ROLLBACK");
    }    

	function set_ano($ano){
		$this->ano = $ano;
	}

	function get_ano(){
		return $this->ano;
	}
	
	function fecharConexao(){
		mysqli_close($this->conexao);
	}

	function trataNull($var,$t=FALSE){
		if(!$var) return "null";
		else return ($t)?"'".$var."'":$var;
	}
	
	function getErro(){
		$erro_msg = explode('<br',nl2br(mysqli_last_error()));
		return $erro_msg[0];
	}
    


    function getAll(){

       
		$sql = "
            select 
                *                
            from publicacoes_fila_2020_08_02

            limit 99999999
        ";
        
        $res = $this->conexao->query($sql);// mysqli_query($this->conexao,$sql);

        //var_dump($res); exit;
        if($res){
            if(mysqli_num_rows($res)>0){
                while($aReg = @mysqli_fetch_assoc($res)){
                    $aObj[] = $aReg;
                }
            }
            return (mysqli_num_rows($res)>0) ? $aObj : false;
        }
        else{
            return "as";
            return false;
        }

	}



}
?>
