<?php
/*
 * Creado el 17/10/2007 a las 11:58:11 AM por migue
 * Para CakePHP, PostgresSQL
 */
 class Arrp06Controller extends AppController {

   var $uses = array('ccfd03_instalacion','Usuario');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

   function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
    $this->checkSession();

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$opc = $this->Usuario->findCount($condicion2);

	if($cod_dep == '01'){
		return;
	}else{
 		echo "LO SIENTO - UD. NO TIENE PERMISOS PARA ESTE PROCESO!!";
		exit;
	}
 }
 function verifica_SS($i){
    	/*******************************************
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";


    	}//fin switch
    }//fin verifica_SS

    function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4);
         $sql_re .= "cod_dep=".$this->verifica_SS(5);
         return $sql_re;
    }//fin funcion SQLCA

     function AddCeroR($n,$extra=null){
   	  if($n!=null){
   	  	  if($extra==null){
        	if($n<10){
        	   $Var="0".$n;
        	}else{
	           $Var=$n;
        	}
   	  }else{
        	if($n<10){
        	   $Var=$extra.".0".$n;
        	}else{
	           $Var=$extra.".".$n;
        	}
   	  }

   	  $Var = substr($Var, - 2);

   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


        function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }



   }//fin AddCero


function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){
		if($monto<10){
			return number_format($monto);
		}
    	return number_format($monto,2,",",".");
    }

function index($id=null){
    $this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('formulacion', $this->ccfd03_instalacion->findAll());

	//Este ciclo hace una consula antes de agregar para validar si ya el dato fue agregado con anterioridad
	$consulta="select *from ccfd03_instalacion";
	if($this->ccfd03_instalacion->execute($consulta)){

		//setea la variable para luego examinarla si existe o no
		$this->set('existe',true);

    if (empty($this->data)){
    	$dato=$this->ccfd03_instalacion->findAll();
    	foreach($dato as $dato){
    		$ano_ejecucion=$dato['ccfd03_instalacion']['ano_ejecucion'];
    		$mes_ejecucion=$this->AddCeroR($dato['ccfd03_instalacion']['mes_ejecucion'],null);
    	}
    	 $this->set('ano_ejecucion',$ano_ejecucion);
    	 $this->set('mes_ejecucion',$mes_ejecucion);

    }

	}else{
		$this->set('existe',false);
	}

 }

   function index2() {
   	$this->layout = "ajax";
   }

 function guardar($valor=null){
    $this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_ejecucion=$this->data['arrp06']['ano_ejecucion'];
	$mes_ejecucion=$this->data['arrp06']['mes_ejecucion'];

	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);

	$consulta="select *from arrp06";
	$sql="insert into ccfd03_instalacion values ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$ano_ejecucion','$mes_ejecucion')";

		//Si el dato no fue agregado con anterioridad entonces procede a insertarlo
		if($this->ccfd03_instalacion->execute($sql)>1){
			$this->set('Message_existe', 'Los Datos fueron Almacenados');
			$this->set('existe',true);
			$dato=$this->ccfd03_instalacion->findAll();
    				foreach($dato as $dato){
    				$ano_ejecucion=$dato['ccfd03_instalacion']['ano_ejecucion'];
    				$mes_ejecucion=$this->AddCeroR($dato['ccfd03_instalacion']['mes_ejecucion'],null);
    				}
    	 		$this->set('ano_ejecucion',$ano_ejecucion);
    	 		$this->set('mes_ejecucion',$mes_ejecucion);
    			$this->render('index');
	   }else{
	   	$this->set('errorMessage', 'error, Los Datos no fueron Almacenados');
	   	$this->set('existe',false);
	   	}


 }

 function modificar () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_ejecucion=$this->data['arrp06']['ano_ejecucion'];
	$mes_ejecucion=$this->data['arrp06']['mes_ejecucion'];
	$this->set('ano_ejecucion',$ano_ejecucion);
    $this->set('mes_ejecucion',$mes_ejecucion);

}

function guardar_modificar () {
	$this->layout = "ajax";
	$ano_ejecucion=$this->data['arrp06']['ano_ejecucion'];
	$mes_ejecucion=$this->data['arrp06']['mes_ejecucion'];
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);
	$sql="update ccfd03_instalacion set ano_ejecucion='$ano_ejecucion',mes_ejecucion='$mes_ejecucion' where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst'  and cod_dep='$cod_dep'";

		if($this->ccfd03_instalacion->execute($sql)>1){
			$this->set('Message_existe', 'Los datos fueron actualizados');
			$this->set('existe',true);
			$dato=$this->ccfd03_instalacion->findAll();
    				foreach($dato as $dato){
    				$ano_ejecucion=$dato['ccfd03_instalacion']['ano_ejecucion'];
    				$mes_ejecucion=$this->AddCeroR($dato['ccfd03_instalacion']['mes_ejecucion'],null);
    				}
    	 		$this->set('ano_ejecucion',$ano_ejecucion);
    	 		$this->set('mes_ejecucion',$mes_ejecucion);
    			$this->render('index');
		}else{
	   	$this->set('errorMessage', 'Error, Los datos no fueron actualizados');
	   	}
	}

	function eliminar() {
	$this->layout = "ajax";
	$ano_ejecucion=$this->data['arrp06']['ano_ejecucion'];
	$mes_ejecucion=$this->data['arrp06']['mes_ejecucion'];
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);
	$sql="delete from ccfd03_instalacion";

		if($this->ccfd03_instalacion->execute($sql)>1){
			$this->set('Message_existe', 'Los datos fueron Eliminados');
			$this->set('existe',false);
			$this->render('index');
		}else{
	   	$this->set('errorMessage', 'Error, Los datos no fueron eliminados');
	   	}
	}
 }
?>
