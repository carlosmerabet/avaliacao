<?php
class Conexao{

	var $conexao;
	var $consulta;  
	var $msg;
	var $banco;
	
	function Conexao($servidor = "Teste"){

		switch ($servidor){

			case "Teste":
				$this->conexaoBD("127.0.0.1","root","","prova");
				$this->banco =  "LOCAL/TESTE";
			break;
			
		}
	}
	 
	function conexaoBD($host,$user,$senha,$bd){
		//$conexao = mysqli_connect("host=$host dbname=$bd user=$user password=$senha") 
		//	or die ('Não foi possivel conectar com o Banco de Dados Postgres!');

		$conexao =	mysqli_connect($host, $user, $senha, $bd)
			or die ('Não foi possivel conectar com o Banco de Dados Postgres!');
		$this->set_conexao($conexao);
	}
	
	// ============ METODOS GET E SET ===================
	function get_conexao(){
		if(mysqli_connection_status($this->conexao)===0){
			return $this->conexao;
		} else die('ERRO: A conexão com o Banco de Dados foi perdida!');
	}
	
	function set_conexao($conexao){
		$this->conexao = $conexao;
	}
	
	function get_msg(){
		return $this->msg;
	}
	
	function set_msg($msg){
		$this->msg = $msg;
	}
	
	function get_consulta(){
		return $this->consulta;
	}
	
	function set_consulta($consulta){
		$this->consulta = $consulta;
	}
	
	// ===========================================
	function execute($sql,$backtrace=NULL){
		$consulta = @mysqli_query($this->get_conexao(),$sql);
		if($consulta){
			$this->set_consulta($consulta);
			return true;
		}
		else{
			$last_error = mysqli_last_error();			
			$this->sendMail($last_error,$sql);
			$this->set_msg($last_error);
			return false;
		}
	}
	
	function numRows($consulta = NULL){
		if(!$consulta)
			$consulta = $this->get_consulta();
		return ($consulta)?mysqli_num_rows($consulta):false;
	}
	
	function fetchReg($consulta = NULL){
		if(!$consulta)
			$consulta = $this->get_consulta();
		return ($consulta)?mysqli_fetch_assoc($consulta):false;
	}
	
	function fetchRow($consulta = NULL){
		if(!$consulta) 
			$consulta = $this->get_consulta();
		return ($consulta)?mysqli_fetch_row($consulta):false;
	}
	
	function lastID(){
		return mysqli_last_oid($this->get_consulta());
	}
	
	function close(){
		mysqli_close($this->get_conexao());
	}
	
	// ======== NOVO: MYSQL 4 COM SUPORTE A TRANSACOES ============
	function beginTrans(){
		$this->execute("BEGIN");	
	}

	function commitTrans(){
		$this->execute("COMMIT");		
	}

	function rollBackTrans(){
		$this->execute("ROLLBACK");
	}
	
	// ============== METODOS DE METADADOS ====================
//	function databases(){
//		$this->execute("SHOW DATABASES");
//		$aDatabases = array();
//		while($aReg = $this->fetchRow()){
//			$aDatabases[] = $aReg[0];
//		}
//		return $aDatabases;
//	}

	function sendMail($corpo,$sql){
        include 'PHPMailer/class.phpmailer.php';
        include 'PHPMailer/class.smtp.php';
		$corpo .= ("<br><pre>$sql</pre>");
		$corpo .= "<br> <b>HTTP_REFERER: </b>".$_SERVER['HTTP_REFERER'];
		$corpo .= "<br> <b>REQUEST_URI: </b>".$_SERVER['REQUEST_URI'];
		$corpo .= "<br> <b>ANO_LETIVO: </b>".$_SESSION['ano_letivo'];
		$corpo .= "<br> <b>ESCOLA: </b>".$_SESSION['codigo_ue'];
		$corpo .= "<br> <b>TURMA: </b>".$_SESSION['codTurma'];
		$corpo .= "<br> <b>SERIE: </b>".$_SESSION['codSerie'];
		$corpo .= "<br> <b>COMP: </b>".$_SESSION['codComposicao'];
		$corpo .= "<br> <b>TURNO: </b>".$_SESSION['codTurno'];
		$corpo .= "<br> <b>SERIE2: </b>".$_SESSION['codSeriePromovidoOrigem'];
		$corpo .= "<br> <b>USUARIO ATUAL: </b>".$_SESSION['usuarioAtual']->codigoUsuario;
		
		
        
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        //$mail->SMTPDebug = 4;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = "465";
        $mail->Username  = "siig@seduc.pa.gov.br";
        $mail->Password  = "siig@1020305040";
        $mail->From = "siig@seduc.pa.gov.br";
        $mail->FromName = "[Intranet 5.6] Erro Intranet";
        $mail->IsHTML(true);
        
        
        $mail->AddAddress("michell.matos@seduc.pa.gov.br");
        //$mail->AddAddress("thiago.sales@seduc.pa.gov.br");
        //$mail->AddAddress("antonio.costa@seduc.pa.gov.br");
        $mail->Subject = "[Intranet 5.6] Erro Intranet ".$this->banco;
        $mail->Body = $corpo;
        $mail->Send();
        
	}
}
?>
