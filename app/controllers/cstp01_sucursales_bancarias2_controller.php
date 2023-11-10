<?php
/*
 * Creado el 02/12/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 04:23:48 PM
 */
 class Cstp01sucursalesbancarias2Controller extends AppController {
   var $name = 'cstp01_sucursales_bancarias2';
   var $uses = array('cstd01_sucursales_bancarias','cstd01_entidades_bancarias','usuario','v_cuentas_bancarias');
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
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';

 }


 function mascara3($cod){
	$opc = strlen($cod);
	switch ($opc) {
		case 1:
			$cod = '000'.$cod;
			break;
		case 2:
			$cod = '00'.$cod;
			break;
		case 3:
			$cod = '0'.$cod;
			break;

		default:
			break;
	}

	return $cod;
}

function ss($i){
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
         $sql_re = "cod_presi=".$this->ss(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->ss(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->ss(3)."  and ";
         $sql_re .= "cod_inst=".$this->ss(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->ss(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->ss(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA




 function index(){
 	$this->layout ="ajax";
 	$entidades=$this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$entidades = $entidades != null ? $entidades : array();
	$this->set('entidades', $entidades);
 	}



function select_cod_ent ($var=null) {
   $this->layout="ajax";

       	  $this->set('tipo','select');
       	  $this->set('seleccion',$var);

		$entidades=$this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$entidades = $entidades != null ? $entidades : array();
	$this->set('entidades', $entidades);

	  	  $rs_u=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$var);
          $this->set('cod_entidad',$rs_u[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria']);
          $this->set('deno_entidad',$rs_u[0]['cstd01_entidades_bancarias']['denominacion']);
         // echo $rs_u[0]['catd01_valor_construccion']['cod_tipo_caracteristica'];



}//fin funcion select_cod_ent


 function index2($cod_ent=null) {
 	$this->layout = "ajax";
 	if($cod_ent!=null){
		    $sucursales = $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$cod_ent,null,'cod_sucursal ASC', null,null);
		    //$this->concatena($sucursales, 'sucursales');
		    if($sucursales == 0){
			   $this->set('sucursales','');
			   $this->set('cod_entidad','');
		    }else{
			   $this->set('sucursales',$sucursales);
			   $this->set('cod_entidad',$cod_ent);
		    }
	}else{
	   $this->set('sucursales','');
	}

	$this->set('modelo','cstd01_sucursales_bancarias');

   }


function select_sucursales($cod_ent=null){
	$this->layout = "ajax";

	if($cod_ent!=null){
		    $sucursales = $this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$cod_ent, 'cod_entidad_bancaria, cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
		    //$this->concatena($sucursales, 'sucursales');
		    if($sucursales == 0){
			   $this->set('sucursales','');
			   $this->set('cod_entidad','');
		    }else{
			   $this->set('sucursales',$sucursales);
			   $this->set('cod_entidad',$cod_ent);
		    }
	}else{
	   $this->set('sucursales','');
 	   $this->set('cod_entidad','');
	}
}


function guardar(){
	$this->layout = "ajax";
	$cod_ent_bancaria=$this->data['cstp01_sucursales_bancarias2']['cod_entidad'];
	$cod_sucursal=$this->data['cstp01_sucursales_bancarias2']['cod_sucursal'];
	$denominacion=$this->data['cstp01_sucursales_bancarias2']['deno_sucursal'];
	$consulta="SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$cod_ent_bancaria." and cod_sucursal=".$cod_sucursal;
	if($this->cstd01_sucursales_bancarias->execute($consulta)){
		$this->set('errorMessage','Lo siento, el codigo de sucursal ('.$cod_sucursal.') ya se encuentra registrado para esa entidad');
		$this->index2($cod_ent_bancaria);
		$this->render("index2");
	}else{
		$sql="INSERT INTO cstd01_sucursales_bancarias VALUES (".$cod_ent_bancaria.",".$cod_sucursal.",'".$denominacion."')";
		if($this->cstd01_sucursales_bancarias->execute($sql)>1){
			$this->set('Message_existe','La sucursal bancaria fue registrada correctamente');
			$this->index2($cod_ent_bancaria);
			$this->render("index2");
		}else{
			$this->set('errorMessage','Lo siento, no se pudo procesar el registro');
			$this->index2($cod_ent_bancaria);
			$this->render("index2");
		}
	}
}


function guardar_editar($cod_ent=null,$cod_sucursal=null){
	$this->layout="ajax";

	if($cod_ent!=null && $cod_sucursal!=null){
		if($this->data['cstp01_sucursales_bancarias2']['deno_sucursal1']!=''){
				$update="UPDATE cstd01_sucursales_bancarias SET denominacion='".$this->data['cstp01_sucursales_bancarias2']['deno_sucursal1']."' WHERE cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal;
				if($this->cstd01_sucursales_bancarias->execute($update)>0){
				$this->set('Message_existe','La sucursal bancaria fue modificada correctamente');
				$this->index2($cod_ent);
				$this->render("index2");
				}else{
				$this->set('errorMessag','Lo siento, la sucursal no pudo ser modificada');
				$this->index2($cod_ent);
				$this->render("index2");
				}
		}else{
		$this->set('errorMessag','Debe ingresar la denominacion');
		$this->index2($cod_ent);
		$this->render("index2");
		}
	}else{
	$this->set('errorMessag','Lo siento no llego informacion para ser procesada correctamente');
	$this->index2($cod_ent);
	$this->render("index2");
	}
}



function eliminar($cod_ent=null,$cod_sucursal=null){
	$this->layout="ajax";
	if($cod_ent!=null && $cod_sucursal!=null){
		if($this->v_cuentas_bancarias->findCount('cod_entidad_bancaria='.$cod_ent.' and cod_sucursal='.$cod_sucursal)!=0){
			$this->set('errorMessage','LA SUCURSAL BANCARIA NO PUEDE SER ELIMINADA, YA POSEE UNA CUENTA REGISTRADA');
			$this->index2($cod_ent);
			$this->render("index2");
    	}else{
			 if($this->cstd01_sucursales_bancarias->execute('delete from cstd01_sucursales_bancarias where cod_entidad_bancaria='.$cod_ent.' and cod_sucursal='.$cod_sucursal)>1){
			 $this->set('Message_existe','La sucursal bancaria fue eliminada correctamente');
			 $this->index2($cod_ent);
			 $this->render("index2");
			 }else{
			 $this->set('errorMessage','Lo siento la sucursal bancaria no pudo ser eliminada');
		     $this->index2($cod_ent);
			 $this->render("index2");
			 }
		}
	}else{
	$this->set('errorMessage','Lo siento no llego informacion para ser procesada correctamente');
	$this->index2($cod_ent);
	$this->render("index2");
	}
}

function editar ($cod_ent=null,$cod_sucursal=null,$id_up,$id_fila) {
	$this->layout = "ajax";
    $rs=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$cod_ent.' and cod_sucursal='.$cod_sucursal);
    $this->set("cod_entidad",$cod_ent);
    $this->set("cod_sucursal",$cod_sucursal);
    $this->set("denominacion",$rs[0]["cstd01_sucursales_bancarias"]["denominacion"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
}

function cancelar_editar($cod_ent=null,$cod_sucursal=null){
		$this->layout = "ajax";
		$this->index2($cod_ent);
    	$this->render("index2");


}


 }//Fin class
?>
