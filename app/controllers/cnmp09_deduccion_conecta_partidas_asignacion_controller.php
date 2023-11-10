<?php

 class Cnmp09DeduccionConectaPartidasAsignacionController extends AppController{

	var $name = 'cnmp09_deduccion_conecta_partidas_asignacion';
	var $uses = array('cnmd01', 'cnmd03_transacciones', 'cnmd09_deducciones_conectada_asignaciones', 'Cnmd01');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');




function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession


	function beforeFilter(){

	$this->checkSession();

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');

}

 function verifica_SS($i){
    	/**
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
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
}//fin funcion SQLCA








function index(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $aux ="";
 /*$resultado= $this->Cnmd01->execute("SELECT
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.denominacion

			  from  Cnmd01 a where

			    a.cod_presi              =   ".$cod_presi." and
			    a.cod_entidad            =   ".$cod_entidad." and
			    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
			    a.cod_inst               =   ".$cod_inst." and
			    a.cod_dep                =   ".$cod_dep." ORDER BY a.cod_tipo_nomina; ");

    foreach($resultado as $ve){

    	  if($ve[0]['cod_tipo_nomina']){
        	   $aux=$ve[0]['cod_tipo_nomina'];
        	}else{
	           $aux=$ve[0]['cod_tipo_nomina'];
        	}

       $lista[$ve[0]['cod_tipo_nomina']]=$aux.' - '.$ve[0]['denominacion'];
    }//fin foreach
	*/
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');


//   $this->set('nomina', $lista);

}//fin function



function select($var=null){
	$this->layout="ajax";
	if($var=="deduccion"){
		$cnmd03_transacciones = $this->cnmd03_transacciones->generateList2("cod_tipo_transaccion='2'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
  	    $this->concatenaN($cnmd03_transacciones, 'nomina');
  	    $this->set('deduccion','');
	}else{
		 $cnmd03_transacciones = $this->cnmd03_transacciones->generateList2("cod_tipo_transaccion='1'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   		 $this->concatenaN($cnmd03_transacciones, 'nomina');
   		 $this->set('asignacion','');
	}


}// fin select




function cod_nomina($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

     $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.denominacion

			  from  Cnmd01 a where

			    a.cod_presi              =   ".$cod_presi." and
			    a.cod_entidad            =   ".$cod_entidad." and
			    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
			    a.cod_inst               =   ".$cod_inst." and
			    a.cod_dep                =   ".$cod_dep." and
			    a.cod_tipo_nomina        =   ".$var."
			    ");


echo "<script>";
        echo "document.getElementById('cod_nomina').value='".mascara_tres($resultado[0][0]['cod_tipo_nomina'])."';";
		echo "document.getElementById('deno_nomina').value='".$resultado[0][0]['denominacion']."';";
		echo "document.getElementById('deno_ded').value='';";
		echo "document.getElementById('deno_asig').value='';";
echo "</script>";


}//fin function




function cod_transaccion($var=null,$var1=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	if($var==2){
		if($var1!=null && $var1!=""){
			//echo "helloooooooooooo";
			$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$var1' and cod_tipo_transaccion=2", $order ="cod_transaccion ASC");
			if($deno_trans){
				echo "<script>";
					echo "document.getElementById('deno_ded').value='".$deno_trans."';";
					echo "document.getElementById('deno_asig').value='';";
				echo "</script>";
			}else{
				$this->set('deno_trans', "");
			}
		}
	}else{
		if($var1!=null && $var1!=""){
			//echo "helloooooooooooo";
			$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$var1' and cod_tipo_transaccion=1", $order ="cod_transaccion ASC");
			if($deno_trans){
				echo "<script>";
					echo "document.getElementById('deno_asig').value='".$deno_trans."';";
				echo "</script>";
			}else{
				$this->set('deno_trans', "");
			}
		}
	}

}//fin function



function guardar(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
//pr($this->data);


  $cont = 0;
//  if(empty($this->data['cnmp09_tan']['cod_nomina2']) || empty($this->data['cnmp09_tan']['cod_transaccion2']) || empty($this->data['cnmp09_tan']['cod_transaccion1'])){
		  $cod_tipo_nomina     =  $this->data['cnmp09_tan']['cod_nomina2'];
		  $cod_transaccion_ded     =  $this->data['cnmp09_tan']['cod_transaccion2'];
		  $cod_transaccion_asig     =  $this->data['cnmp09_tan']['cod_transaccion1'];
		  $activar     =  $this->data['cnmp09_tan']['activar'];
		  $sql =" INSERT INTO cnmd09_deducciones_conectada_asignaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion_ded, cod_transaccion_ded,codi_tipo_transaccion_asig,codi_transaccion_asig,activar)";
		  $sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '2', '".$cod_transaccion_ded."', '1', '".$cod_transaccion_asig."', '".$activar."'); ";

		$cont = $this->cnmd09_deducciones_conectada_asignaciones->findCount("cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_transaccion_ded='".$cod_transaccion_ded."' and codi_transaccion_asig=".$cod_transaccion_asig);

		if($cont==0){
		$sw2  = $this->Cnmd01->execute('BEGIN; ');
		$sw2  = $this->Cnmd01->execute($sql);

		                  if($sw2>1){
				                $this->Cnmd01->execute("COMMIT;");
				                $this->set('Message_existe', 'Los datos fuer&oacute;n guardados correctamente');


							}else{
								$this->Cnmd01->execute("ROLLBACK;");
		                        $this->set('errorMessage', 'LOS DATOS NO FUER&Oacute;N GUARDADOS - POR FAVOR INTENTE DE NUEVO');

							}//fin else
		}else{
			$this->Cnmd01->execute("ROLLBACK;");
		    $this->set('errorMessage', 'EL REGISTRO YA EXISTE');
		}//fin else
//  }else{
//  	 $this->set('errorMessage', 'debe seleccionar todos los datos');
//  }

  $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion_ded,
              a.cod_transaccion_ded,
              a.codi_tipo_transaccion_asig,
              a.codi_transaccion_asig,
              a.activar,
              (select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion_ded and b.cod_transaccion=a.cod_transaccion_ded) as denominacion_ded,
			  (select c.denominacion from cnmd03_transacciones c where c.cod_tipo_transaccion=a.codi_tipo_transaccion_asig and c.cod_transaccion=a.codi_transaccion_asig) as denominacion_asig
			  from  cnmd09_deducciones_conectada_asignaciones a where
					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$cod_tipo_nomina."");

$this->set('datos', $resultado);


}//fin function



function consulta($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


   $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion_ded,
              a.cod_transaccion_ded,
              a.codi_tipo_transaccion_asig,
              a.codi_transaccion_asig,
              a.activar,
              (select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion_ded and b.cod_transaccion=a.cod_transaccion_ded) as denominacion_ded,
			  (select c.denominacion from cnmd03_transacciones c where c.cod_tipo_transaccion=a.codi_tipo_transaccion_asig and c.cod_transaccion=a.codi_transaccion_asig) as denominacion_asig
			  from  cnmd09_deducciones_conectada_asignaciones a where
					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var."");

$this->set('datos', $resultado);



}//fin function





function modificar($var=null,$var2=null,$var3=null,$i=null){
	  $this->layout = "ajax";
	  $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
			 $this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');

	   $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion_ded,
              a.cod_transaccion_ded,
              a.codi_tipo_transaccion_asig,
              a.codi_transaccion_asig,
              a.activar,
              (select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion_ded and b.cod_transaccion=a.cod_transaccion_ded) as denominacion_ded,
			  (select c.denominacion from cnmd03_transacciones c where c.cod_tipo_transaccion=a.codi_tipo_transaccion_asig and c.cod_transaccion=a.codi_transaccion_asig) as denominacion_asig
			  from  cnmd09_deducciones_conectada_asignaciones a where
					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var." and cod_transaccion_ded=".$var2." and codi_transaccion_asig=".$var3);
//pr($resultado);
$this->set('datos', $resultado);
$this->set('k', $i);
}




function guardar_modificar($var=null,$var2=null,$var3=null,$i=null){
	  $this->layout = "ajax";
	  $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
//	  pr($this->data);
	    $activar     =  $this->data['cnmp09_tan']['activar'.$i];
		$sql="update cnmd09_deducciones_conectada_asignaciones set activar=".$activar." where ".$this->SQLCA()." and cod_tipo_nomina=".$var." and cod_transaccion_ded=".$var2." and codi_transaccion_asig=".$var3;
		$this->cnmd09_deducciones_conectada_asignaciones->execute($sql);
		 $this->set('Message_existe', 'LOS DATOS FUERON MODIFICADOS');

		  $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion_ded,
              a.cod_transaccion_ded,
              a.codi_tipo_transaccion_asig,
              a.codi_transaccion_asig,
              a.activar,
              (select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion_ded and b.cod_transaccion=a.cod_transaccion_ded) as denominacion_ded,
			  (select c.denominacion from cnmd03_transacciones c where c.cod_tipo_transaccion=a.codi_tipo_transaccion_asig and c.cod_transaccion=a.codi_transaccion_asig) as denominacion_asig
			  from  cnmd09_deducciones_conectada_asignaciones a where
					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var."");

$this->set('datos', $resultado);

}

function cancelar($var=null){
	 $this->layout = "ajax";
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion_ded,
              a.cod_transaccion_ded,
              a.codi_tipo_transaccion_asig,
              a.codi_transaccion_asig,
              a.activar,
              (select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion_ded and b.cod_transaccion=a.cod_transaccion_ded) as denominacion_ded,
			  (select c.denominacion from cnmd03_transacciones c where c.cod_tipo_transaccion=a.codi_tipo_transaccion_asig and c.cod_transaccion=a.codi_transaccion_asig) as denominacion_asig
			  from  cnmd09_deducciones_conectada_asignaciones a where
					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var."");

$this->set('datos', $resultado);


}



function eliminar($var=null, $var2=null, $var3=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $sql="BEGIN;  DELETE FROM cnmd09_deducciones_conectada_asignaciones  WHERE cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_transaccion_ded='".$var2."' and codi_transaccion_asig= '".$var3."' ";

  $sw2 = $this->Cnmd01->execute($sql);
			if($sw2>1){
                $this->Cnmd01->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUER&Oacute;N ELIMINADOS CORRECTAMENTE');
			}else{
				$this->Cnmd01->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUER&Oacute;N ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else




   $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion_ded,
              a.cod_transaccion_ded,
              a.codi_tipo_transaccion_asig,
              a.codi_transaccion_asig,
              a.activar,
              (select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion_ded and b.cod_transaccion=a.cod_transaccion_ded) as denominacion_ded,
			  (select c.denominacion from cnmd03_transacciones c where c.cod_tipo_transaccion=a.codi_tipo_transaccion_asig and c.cod_transaccion=a.codi_transaccion_asig) as denominacion_asig
			  from  cnmd09_deducciones_conectada_asignaciones a where
					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var."");

$this->set('datos', $resultado);



}//fin funtion










}//fin class

?>