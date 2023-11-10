<?php
/*
 * Creado el 30/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 10:21:52 PM
 */

 class Cstp01entidadesbancarias2Controller extends AppController {
   var $name = 'cstp01_entidades_bancarias2';
   var $uses = array('cstd01_entidades_bancarias','usuario','v_cuentas_bancarias','ccfd01_division','cfpd05','cfpd10_reformulacion_partidas_tmp');
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
	$this->layout  = "ajax";

    $this->set("modelo","cstd01_entidades_bancarias");
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
    $rs=$this->cstd01_entidades_bancarias->findAll(null,null,'cod_entidad_bancaria ASC');
    $this->set("data_tipo",$rs);

}//index


function guardar ($cod=null) {
   $this->layout="ajax";
   $modelo_form="cstp01_entidades_bancarias2";
	 $this->set('modelo',"cstd01_entidades_bancarias");
   if($this->cstd01_entidades_bancarias->findCount('cod_entidad_bancaria='.$this->data['cstp01_entidades_bancarias2']['codigo_entidad'])>0){
			$this->set('errorMessage','LO SIENTO EL CODIGO('.$this->data['cstp01_entidades_bancarias2']['codigo_entidad'].') YA SE ENCUENTRA REGISTRAD0 EN EL SISTEMA');
			$datos=$this->cstd01_entidades_bancarias->findAll(null,null,'cod_entidad_bancaria ASC');
		    $this->set('data_tipo',$datos);
		}else{
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$cod_tipo_cuenta=1;
			$cod_cuenta=102;
			$cod_subcuenta=002;
			$cod_division=$this->data['cstp01_entidades_bancarias2']['codigo_entidad'];// codigo de la entidad bancaria.
			$denominacion=$this->data['cstp01_entidades_bancarias2']['denominacion'];// denominacion de la entidad bancaria.
			$concepto='';

			$sql="INSERT INTO cstd01_entidades_bancarias VALUES ('".$this->data['cstp01_entidades_bancarias2']['codigo_entidad']."','".$this->data['cstp01_entidades_bancarias2']['denominacion']."')";
			if($this->cstd01_entidades_bancarias->execute($sql)>1){
				$sql_consulta_division="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
				if($this->ccfd01_division->findCount($sql_consulta_division)==0){// se verifica que no exista este registro en la tabla de divisiones.
					$sql_insert_division="INSERT INTO ccfd01_division VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$denominacion', '$concepto')";
					$result = $this->ccfd01_division->execute($sql_insert_division);
					if($result>0){
						$this->set('Message_existe','LA ENTIDAD BANCARIA FU&Eacute; AGREGADA CORRECTAMENTE');
					}else{
						$this->set('errorMessage','LA ENTIDAD BANCARIA FU&Eacute; AGREGADA CORRECTAMENTE, PERO NO PUDO SER AGREGADA AL PLAN DE CUENTAS');
					}
				}else{
					$this->set('Message_existe','LA ENTIDAD BANCARIA FU&Eacute; AGREGADA CORRECTAMENTE');
				}
				$datos=$this->cstd01_entidades_bancarias->findAll(null,null,'cod_entidad_bancaria ASC');
				$this->set('data_tipo',$datos);
			}else{
				$this->set('errorMessage','LO SIENTO, LA ENTIDAD BANCARIA NO PUDO SER AGREGADA');
				$datos=$this->cstd01_entidades_bancarias->findAll(null,null,'cod_entidad_bancaria ASC');
				$this->set('data_tipo',$datos);
			}
		}//fin else consulta



}//fin guardar


function eliminar_items($cod_entidad_bancaria=null){
	$this->layout="ajax";
    if($cod_entidad_bancaria!=null){
    	if($this->v_cuentas_bancarias->findCount('cod_entidad_bancaria='.$cod_entidad_bancaria)!=0){
			$this->set('errorMessage','LA ENTIDAD BANCARIA NO PUEDE SER ELIMINADA, YA POSEE UNA CUENTA REGISTRADA');
    	}else{
		     $sql="DELETE FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$cod_entidad_bancaria;
		     if($this->cstd01_entidades_bancarias->execute($sql)>1){
		     	$cod_presi = $this->Session->read('SScodpresi');
			    $cod_entidad = $this->Session->read('SScodentidad');
			    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
				$cod_inst = $this->Session->read('SScodinst');
				$cod_dep = $this->Session->read('SScoddep');
				$cod_tipo_cuenta=1;
				$cod_cuenta=102;
				$cod_subcuenta=002;
				$cod_division=$cod_entidad_bancaria;
				$sql_delete_division="DELETE FROM ccfd01_division WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
				if($this->ccfd01_division->execute($sql_delete_division)>1){
					$this->set('Message_existe','LA ENTIDAD BANCARIA FUE ELIMINADA CORRECTAMENTE');
				}else{
					$this->set('errorMessage','LO SIENTO LA ENTIDAD BANCARIA FUE ELIMINADA CORRECTAMENTE, PERO NO SE PUDO ELIMINAR DEL PLAN DE CUENTAS');
				}

		     }else{
		     $this->set('errorMessage','LO SIENTO, LA ENTIDAD BANCARIA NO PUDO SER ELIMINADA');
		     }
    	}
    }else{
        $this->set('errorMessage','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }

 }//eliminar

function editar ($cod_entidad,$id_up,$id_fila) {
	$this->layout = "ajax";
    $rs=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad);
    $this->set("cod_entidad",$rs[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"]);
    $this->set("denominacion",$rs[0]["cstd01_entidades_bancarias"]["denominacion"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
}


function guardar_editar ($cod_entidad_ban,$id_up,$id_fila) {
 $this->layout="ajax";

		$sql_update="UPDATE cstd01_entidades_bancarias SET denominacion='".$this->data['cstp01_entidades_bancarias2']['denominacion']."' WHERE cod_entidad_bancaria=$cod_entidad_ban";
		if($this->cstd01_entidades_bancarias->execute($sql_update)>0){
			$cod_presi = $this->Session->read('SScodpresi');
		    $cod_entidad = $this->Session->read('SScodentidad');
		    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$cod_tipo_cuenta=1;
			$cod_cuenta=102;
			$cod_subcuenta=002;
			$cod_division=$this->data['cstp01_entidades_bancarias2']['codigo_entidad'];
			$denominacion=$this->data['cstp01_entidades_bancarias2']['denominacion'];

			$sql_update_division="UPDATE ccfd01_division SET denominacion='".$this->data['cstp01_entidades_bancarias2']['denominacion']."' WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
			if($this->cstd01_entidades_bancarias->execute($sql_update_division)>0){
				$this->set('Message_existe','LA ENTIDAD BANCARIA Y EL PLAN DE CUENTAS FUER&Oacute;N MODIFICADOS CORRECTAMENTE');
			}else{
				$this->set('errorMessage','LA ENTIDAD BANCARIA FUE MODIFICADA CORRECTAMENTE, PERO NO SE PUDO ACTUALIZAR EL PLAN DE CUENTAS');
			}

		}else{
			$this->set('errorMessage','LO SIENTO, LA ENTIDAD BANCARIA NO PUDO SER MODIFICADA');
		}


	   $this->set("i",$id_up);
   	   $this->set("id_fila",$id_fila);
       $this->set("cod_entidad",$cod_entidad_ban);
       $this->set("denominacion",$denominacion);


}//fin guardar editar

function cancelar_editar ($cod_entidad=null,$id_up,$id_fila) {
   $this->layout="ajax";
    $rs=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad);
    $this->set("cod_entidad",$rs[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"]);
    $this->set("denominacion",$rs[0]["cstd01_entidades_bancarias"]["denominacion"]);





    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
    $this->set("modelo","cstd01_entidades_bancarias");

}//fin cancelar



}//fin class
