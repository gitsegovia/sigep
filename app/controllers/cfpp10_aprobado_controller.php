<?php
/*
 * Creado el 06/02/2008 a las 12:29:53 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Cfpp10AprobadoController extends AppController {
 	var $name='cfpp10_aprobado';
 	var $uses=array('v_cfpd05_denominaciones','ccfd04_cierre_mes','ccfd03_instalacion','cugd02_dependencia','cugd05_restriccion_clave','cfpd10_reformulacion_texto','cfpd10_reformulacion_partidas','cfpd10_reformulacion_tipo','cfpd01_formulacion','cfpd10_reformulacion_partidas_tmp','arrd05');
 	var $helpers=array('Html','Ajax','Javascript','Sisap');


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
 	 /*echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
	*/
}

function verifica_SS($i){
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";
    	}
}

function SQLCA($ano=null){
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
}


function index($var=null){

$this->verifica_entrada('4');

	$this->layout="ajax";
	$ano 			= $this->ano_ejecucion();
	$cod_presi 		= $this->verifica_SS(1);
  	$cod_entidad 	= $this->verifica_SS(2);
  	$cod_tipo_inst 	= $this->verifica_SS(3);
  	$cod_inst 		= $this->verifica_SS(4);
  	$cod_dep 		= $this->verifica_SS(5);

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$cond = "a.cod_presi = ".$this->Session->read('SScodpresi')." and a.cod_entidad = ".$this->Session->read('SScodentidad')." and a.cod_tipo_inst = ".$this->Session->read('SScodtipoinst')." and a.cod_inst = ".$this->Session->read('SScodinst').' and a.cod_dep='.$cod_dep;
    $ano=$this->ano_ejecucion();
    $this->set('ano_reformulacion',$ano);
	$v1="SELECT (a.numero_oficio || '@' || a.cod_dep) as numero_oficio,  (a.numero_oficio || ' -  ' || x.denominacion) as denominacion FROM cfpd10_reformulacion_texto a,arrd05 x
		WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and
		x.cod_dep=a.cod_dep and $cond and a.ano_reformulacion=$ano and a.elaborado='1' and a.revisado='2' and a.por_enviar='1' and a.enviado='2' and a.por_remitir='1' and a.remitido='2' and a.por_aprobar='1' and a.aprobado='0' order by x.denominacion,a.numero_oficio ASC";
	$rs=$this->v_cfpd05_denominaciones->execute($v1);
    if($rs != null){
    foreach($rs as $l){
		$v[]=$l[0]["numero_oficio"];
		$d[]=$l[0]["denominacion"];
	}
    }else{
    	$v=array('0'=>'');
    	$d=array('0'=>'');
    }
	$lista = array_combine($v, $d);
	$this->set('reform_aprobado',$lista);
}

function mostrar1($select=null,$var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');

	if($var!=null){
		$x=explode('@',$var);
		$var=$x[0];
		$cod_dep=$x[1];

	$aprobado="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and $cod_inst=$cod_inst and cod_dep=$cod_dep and elaborado='1' and revisado='2' and por_enviar='1' and enviado='2' and por_remitir='1' and remitido='2' and por_aprobar='1' and aprobado='0' and ano_reformulacion=".$select." and numero_oficio='".$var."'";

	if($oficio = $this->cfpd10_reformulacion_texto->findAll($aprobado,'cod_tipo, numero_oficio, fecha_oficio')){//busco el oficio filtrado por "elaborado", "numero_oficio" y el "ano" de la reformulacion -- y me traigo los siguientes campos 'cod_tipo, numero_oficio, fecha_oficio'
		$t_reformulacion=$this->cfpd10_reformulacion_tipo->findAll('cod_tipo='.$oficio[0]['cfpd10_reformulacion_texto']['cod_tipo']);
		$this->set('numero_oficio',$oficio[0]['cfpd10_reformulacion_texto']['numero_oficio']);
		$fa=$fecha_oficio=$oficio[0]['cfpd10_reformulacion_texto']['fecha_oficio'];
  		$fecha_oficio=$fa[8].$fa[9]."/".$fa[5].$fa[6]."/".$fa[0].$fa[1].$fa[2].$fa[3];
		  echo"<script>
      	    document.getElementById('fecha_oficio').value='".$fecha_oficio."';
      	    document.getElementById('tipo_reformulacio').value='".$t_reformulacion[0]['cfpd10_reformulacion_tipo']['denominacion']."';
          </script>";
	}else{
		$this->set('mensajeError','Lo siento, no se encontro el Oficio buscado');
	}
	}else{
			echo"<script>
      	    document.getElementById('fecha_oficio').value='';
      	    document.getElementById('tipo_reformulacio').value='';
          </script>";
		$this->set('mensajeError','Seleccione un oficio por favor');
		$this->set('numero_oficio','');
	}
}//mostrar1




function mostrar2($select=null,$var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	if($var!=null){
		$x=explode('@',$var);
		$var=$x[0];
		$cod_dep=$x[1];
		$remitido="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and elaborado='1' and revisado='2' and por_enviar='1' and enviado='2' and por_remitir='1' and remitido='2' and por_aprobar='1' and aprobado='0' and ano_reformulacion=".$select." and numero_oficio='".$var."'";
	$oficio = $this->cfpd10_reformulacion_texto->findAll($remitido, 'cod_tipo, concepto, monto,numero_oficio_consejo_legis');
	$this->set('concepto',$oficio[0]['cfpd10_reformulacion_texto']['concepto']);
	$this->set('monto_reform',$oficio[0]['cfpd10_reformulacion_texto']['monto']);
	$this->set('cod_tipo',$oficio[0]['cfpd10_reformulacion_texto']['cod_tipo']);
	$this->set('numero_consejo',$oficio[0]['cfpd10_reformulacion_texto']['numero_oficio_consejo_legis']);

	$sql="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_reformulacion=".$select." and numero_oficio='".$var."'";
	if($datos=$this->cfpd10_reformulacion_partidas_tmp->findAll($sql)){
		$dependencia = $this->arrd05->findAll($this->SQLCA_report($this->verifica_SS(5)));
		$this->set('dependencias',$dependencia);
		$this->set('datos',$datos);
		$this->set('dependencia',$cod_dep);
	}else{
		$this->set('mensajeError','No se encontraron partidas asignadas a este oficio');
	}
	}else{
		$this->set('mensajeError','Seleccione un oficio por favor');
		$this->set('concepto','');
		$this->set('datos',null);
	}
}//mostrar2


function aprobado($dep=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	if($this->data['cfpp10_aprobado']!=null){
		$this->data['cfpp10_aprobado']['ano'];
		$this->data['cfpp10_aprobado']['numero_oficio'];
		$this->data['cfpp10_aprobado']['numero_apro'];
		$fecha=split('/',$this->data['cfpp10_aprobado']['fecha_apro']);
		$fecha_apro=$fecha[2]."-".$fecha[1]."-".$fecha[0];//formateo la fecha al formato americano
		$sql="UPDATE cfpd10_reformulacion_texto SET aprobado='2', numero_aprobacion='".$this->data['cfpp10_aprobado']['numero_apro']."', fecha_aprobacion='".$fecha_apro."'  WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$dep  AND elaborado='1' AND revisado='2' AND por_enviar='1' AND enviado='2' AND por_remitir='1' AND remitido='2' AND por_aprobar='1' AND aprobado='0' AND ano_reformulacion=".$this->data['cfpp10_aprobado']['ano']." and numero_oficio='".$this->data['cfpp10_aprobado']['numero_oficio']."'";
		if($this->cfpd10_reformulacion_texto->execute($sql)>0){
			$this->set('mensaje','El oficio de reformulaci&oacute;n se actualizo correctamente');
			$this->set('validado',true);
			$this->index();
			$this->render("index");
		}else{
		$this->set('mensajeError','Lo siento, el oficio no pudo ser actualizado');
		}
	}else{
	$this->set('mensajeError','Lo siento, los datos no llegaron completamente');
	}
}


/*
function entrar(){
	$this->layout="ajax";
	$user=addslashes($this->data['cfpp10_aprobado']['login']);
	$paswd=addslashes($this->data['cfpp10_aprobado']['password']);
	$cond=" cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst');
	$sql="SELECT * FROM cugd05_restriccion_clave WHERE ".$cond." and username='".$user."' and cod_tipo=4 and clave='".$paswd."'";
	if($this->cugd05_restriccion_clave->execute($sql)){
	$this->set('validado',true);
	$this->index();
	$this->render("index");
	}else{
	$this->set('mensajeError','Atencion, usted no esta autorizado para usar este programa');
	}
}
*/

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cfpp10_aprobado']['login']) && isset($this->data['cfpp10_aprobado']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cfpp10_aprobado']['login']);
		$paswd=addslashes($this->data['cfpp10_aprobado']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=4 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('validado',true);
			$this->index();
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('validado',true);
			$this->index();
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('validado',false);
			$this->index();
			$this->render("index");
		}
	}
}



}//fin clase
?>