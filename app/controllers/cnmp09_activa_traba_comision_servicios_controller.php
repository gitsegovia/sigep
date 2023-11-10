<?php

class Cnmp09ActivaTrabaComisionServiciosController extends AppController {
   var $name = 'cnmp09_activa_traba_comision_servicios';
   var $uses = array('Cnmd01', 'cnmd06_fichas','cnmd06_datos_personales','v_cnmd06_fichas_datos_personales');
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




 function beforeFilter(){
 	$this->checkSession();
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
 }


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->concatenaN($this->Cnmd01->generateList($this->SQLCA(),'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion'),'nomina');
	$this->Session->delete("i");
	$this->Session->delete("trabajadores");
 }// fin index

function cod_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_nomina', $cod_nomina);
	}
	$this->Session->delete('cod_nomina_1');
	$this->Session->write('cod_nomina_1',$cod_nomina);
	echo "<script>";
		echo "document.getElementById('cod_ficha').value='';";
		echo "document.getElementById('deno_ficha').value='';";
		echo "document.getElementById('buscar').disabled=false;";
		echo "document.getElementById('cod_cargo').value='';";
		echo "document.getElementById('cod_ficha_1').value='';";
		echo "document.getElementById('cedula').value='';";
		echo "document.getElementById('apellido_1').value='';";
		echo "document.getElementById('apellido_2').value='';";
		echo "document.getElementById('nombre_1').value='';";
		echo "document.getElementById('nombre_2').value='';";
		echo "document.getElementById('dias').value='';";
//		echo "document.getElementById('transaccion').value='';";
//		echo "document.getElementById('denominacion').value='';";
//		echo "document.getElementById('porcentaje').value='';";
		echo"document.getElementById('agregar').disabled ='disabled'; ";
	echo "</script>";
}//fin cod_nomina

function deno_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->SQLCA()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		//echo "el tipo de nomina es: ".$deno_nomina;
		$this->set('deno_nomina', $deno_nomina);
	}
}// fin deno_nomina



function mostrar_grilla($nomina=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	$sql="  select cod_tipo_nomina,cod_cargo,cod_ficha,cedula_identidad,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,condicion_actividad FROM v_cnmd06_fichas_datos_personales  WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and condicion_actividad=3 order by cod_ficha asc";
	$result=$this->v_cnmd06_fichas_datos_personales->execute($sql);
	if($result!=null){
		$this->set('datos',$result);
	}else{
		$this->set('datos','');
	}
	$this->set('nomina',$nomina);
}//fin mostrar_grilla


function cambia_condicion($nomina=null,$cargo=null,$ficha=null){
	$this->layout = "ajax";

	$sql="  select cod_tipo_nomina,cod_cargo,cod_ficha,cedula_identidad,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,condicion_actividad FROM v_cnmd06_fichas_datos_personales  WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_ficha=".$ficha;
	$datos=$this->v_cnmd06_fichas_datos_personales->execute($sql);
	$this->cnmd06_fichas->execute("update cnmd06_fichas set condicion_actividad=1 where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_cargo=".$cargo." and cod_ficha=".$ficha);
	 if(isset($_SESSION["i"])){
		$i=$this->Session->read("i")+1;
		$this->Session->write("i",$i);
	 }else{
	   $this->Session->write("i",0);
	   $i=0;
	   $_SESSION["trabajadores"]=array();
	}
	$vec[$i]['nomina']=$datos[0][0]['cod_tipo_nomina'];
	$vec[$i]['cargo']=$datos[0][0]['cod_cargo'];
	$vec[$i]['ficha']=$datos[0][0]['cod_ficha'];
	$vec[$i]['cedula']=$datos[0][0]['cedula_identidad'];
	$vec[$i]['apellido_1']=$datos[0][0]['primer_apellido'];
	$vec[$i]['apellido_2']=$datos[0][0]['segundo_apellido'];
	$vec[$i]['nombre_1']=$datos[0][0]['primer_nombre'];
	$vec[$i]['nombre_2']=$datos[0][0]['segundo_nombre'];
	$vec[$i]['id']=$i;
	$_SESSION["trabajadores"]=$_SESSION["trabajadores"]+$vec;

}//cambia_condicion



function procesar($nomina=null){
	$this->layout = "ajax";
	$sql="  select cod_tipo_nomina,cod_cargo,cod_ficha,cedula_identidad,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,condicion_actividad FROM v_cnmd06_fichas_datos_personales  WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and condicion_actividad=3 order by cod_ficha asc";
	$datos=$this->v_cnmd06_fichas_datos_personales->execute($sql);

	for($k=0;$k<count($datos);$k++){
		 if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
		 }else{
			   $this->Session->write("i",0);
			   $i=0;
			   $_SESSION["trabajadores"]=array();
		 }
		$this->cnmd06_fichas->execute("update cnmd06_fichas set condicion_actividad=1 where ".$this->SQLCA()." and cod_tipo_nomina=".$datos[$k][0]['cod_tipo_nomina']." and cod_cargo=".$datos[$k][0]['cod_cargo']." and cod_ficha=".$datos[$k][0]['cod_ficha']);

		$vec[$i]['nomina']=$datos[$k][0]['cod_tipo_nomina'];
		$vec[$i]['cargo']=$datos[$k][0]['cod_cargo'];
		$vec[$i]['ficha']=$datos[$k][0]['cod_ficha'];
		$vec[$i]['cedula']=$datos[$k][0]['cedula_identidad'];
		$vec[$i]['apellido_1']=$datos[$k][0]['primer_apellido'];
		$vec[$i]['apellido_2']=$datos[$k][0]['segundo_apellido'];
		$vec[$i]['nombre_1']=$datos[$k][0]['primer_nombre'];
		$vec[$i]['nombre_2']=$datos[$k][0]['segundo_nombre'];
		$vec[$i]['id']=$i;
		$_SESSION["trabajadores"]=$_SESSION["trabajadores"]+$vec;

	}//fin for

}// fin procesar

function vacio(){
	$this->layout = "ajax";
}

 }//Fin de la clase controller
 ?>