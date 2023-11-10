<?php
 class Cimp02TipoMovimientoController extends AppController{
	var $name = 'cimp02_tipo_movimiento';
	var $uses = array('cimd02_tipo_movimiento','cimd03_inventario_inmuebles','cimd03_inventario_muebles','cugd05_restriccion_clave');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession

 function index(){

$this->verifica_entrada('106');

	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$denominacion = $this->cimd02_tipo_movimiento->generateList(null,'cod_tipo_mov,cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$denominacion = $denominacion != null ? $denominacion : array();
	/*$datos=$this->cimd02_tipo_movimiento->findAll(null,null,'cod_tipo_mov,cod_mov ASC');
	$this->concatena($denominacion, 'denominacion');
	$this->set('datos',$datos);*/
	$tipo= array('1'=>'INCORPORACIÓN','2'=>'DESINCORPORACIÓN');
	$this->concatena($tipo, 'cod_tipo_mov');
 }//index


 function guardar(){
	$this->layout="ajax";
	//pr($this->data);
	$cod_tipo_mov=$this->data['cimp02_tipo_movimiento']['cod_tipo_mov'];
	$cod_mov=$this->data['cimp02_tipo_movimiento']['cod_mov'];
	$denominacion=$this->data['cimp02_tipo_movimiento']['denominacion'];
	$veri=$this->cimd02_tipo_movimiento->findCount('cod_tipo_mov='.$cod_tipo_mov.' and cod_mov='.$cod_mov);
	//echo 'veri es '.$veri;
		if($veri == 0){
			$sql="INSERT INTO cimd02_tipo_movimiento VALUES (".$cod_tipo_mov.",".$cod_mov.",'".$denominacion."')";
			if($this->cimd02_tipo_movimiento->execute($sql)>1){
				$this->set('Message_existe','REGISTRO FUE AGREGADO CORRECTAMENTE');
			}else{
				$this->set('errorMessage','LO SIENTO EL REGISTRO NO PUDO SER AGREGADO');
			}
		}else{
			$this->set('errorMessage','LO SIENTO ESTOS CÓDIGOS YA SE ENCUENTRAN REGISTRADOS EN EL SISTEMA');
		}
		$this->grilla($cod_tipo_mov);
		$this->render("grilla");
		echo "<script>document.getElementById('agregar').disabled='';";
		echo "document.getElementById('cod_mov').value = ''; ";
		echo "document.getElementById('deno_mov').value = ''; ";
		echo "</script>";
 }//guardar

 function eliminar($cod_tipo_mov=null,$cod_mov=null){
	$this->layout="ajax";
    if($cod_tipo_mov!=null && $cod_mov!=null){

	   	  if($cod_tipo_mov==1){
	   	  	$veri1=$this->cimd03_inventario_muebles->findCount('cod_tipo_incorporacion='.$cod_mov);
	   	  	$veri2=$this->cimd03_inventario_inmuebles->findCount('cod_tipo_incorporacion='.$cod_mov);
	   	  }
		  if($cod_tipo_mov==2){
	   	  	$veri1=$this->cimd03_inventario_muebles->findCount('cod_tipo_desincorporacion='.$cod_mov);
	   	  	$veri2=$this->cimd03_inventario_inmuebles->findCount('cod_tipo_desincorporacion='.$cod_mov);
	   	  }

	if($veri1 > 0 or $veri2 > 0){
		$this->set('errorMessage','LO SIENTO, TIPO NO PUEDE SER ELIMINADO ESTA PRESENTE EN INVENTARIO');
	}else{
		$sql="DELETE FROM cimd02_tipo_movimiento WHERE cod_tipo_mov=".$cod_tipo_mov." and  cod_mov=".$cod_mov;
		if($this->cimd02_tipo_movimiento->execute($sql)>1){
			$this->set('Message_existe','LA CLASIFICACIÓN FUE ELIMINADA CORRECTAMENTE');
		}else{
		   $this->set('errorMessage','LO SIENTO, LA CLASIFICACIÓN NO PUDO SER ELIMINADA');
		}
      }
    }else{
    	   $this->set('errorMessage','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
    $this->grilla($cod_tipo_mov);
	$this->render("grilla");
}

 function mostrar3($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
			$this->set('tipo_mov','INCORPORACI&Oacute;N');
 		}else if($var==2){
			$this->set('tipo_mov','DESINCORPORACI&Oacute;N');
 		}
 	}else{
 		$this->set('errorMessage','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos

 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cimd02_tipo_movimiento->findAll(null,null,'cod_mov ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cimd02_tipo_movimiento->findAll(null,null,'denominacion ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('errorMessage','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos

function editar($var1=null, $var2=null){
 $this->layout = "ajax";
  	$accion =  $this->cimd02_tipo_movimiento->findAll('cod_tipo_mov='.$var1.' and cod_mov='.$var2, null, null);
 	$denominacion = $accion[0]['cimd02_tipo_movimiento']['denominacion'];

		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var1."_".$var2."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var1."_".$var2."').style.display = 'block'; ";
          echo "document.getElementById('td_3_".$var1."_".$var2."').innerHTML='<input type=text name=data[cimp02_tipo_movimiento][denominacion_".$var1."_".$var2."] class=inputtext size=69% value=\"".$accion[0]['cimd02_tipo_movimiento']['denominacion']."\" />';  ";
		echo "</script>";

$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function


function guardar_editar($var1=null,$var2=null){
  $this->layout = "ajax";
  $denominacion      =  $this->data['cimp02_tipo_movimiento']['denominacion_'.$var1.'_'.$var2];
    $sql = " UPDATE cimd02_tipo_movimiento SET denominacion='".$denominacion."' where cod_tipo_mov =".$var1." and cod_mov=".$var2;
	$this->cimd02_tipo_movimiento->execute($sql);
    //echo $sql;
	$this->set('mensaje', 'Datos Actualizados Correctamente');
	$accion =  $this->cimd02_tipo_movimiento->findAll('cod_tipo_mov='.$var1);
	//$accion =  $this->cimd02_tipo_movimiento->execute("select * from cimd02_tipo_movimiento where cod_mov =".$var1);
	$this->set('datos',$accion);
	$this->set('Message_existe', 'REGISTRO MODIFICADO CON EXITO');
	$this->grilla($var1);
	$this->render("grilla");

}//fin funtion

function agregar(){
	$this->layout = "ajax";
}

function grilla($cod_tipo=null){
	$this->layout = "ajax";
	$datacpcp01=$this->cimd02_tipo_movimiento->findAll('cod_tipo_mov='.$cod_tipo,null,'cod_mov ASC');
     $this->set('datos',$datacpcp01);
   //  pr($datacpcp01);
}

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cimp02_tipo_movimiento']['login']) && isset($this->data['cimp02_tipo_movimiento']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cimp02_tipo_movimiento']['login']);
		$paswd=addslashes($this->data['cimp02_tipo_movimiento']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=106 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

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

}
?>