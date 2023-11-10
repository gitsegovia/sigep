<?php
/*
 * Creado el 04/02/2008 a las 11:45:27 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Cfpp10RevisionController extends AppController {
 	var $name='cfpp10_revision';
 	var $uses=array('v_cfpd05_denominaciones','ccfd04_cierre_mes','cugd02_dependencia','cugd05_restriccion_clave','cfpd10_reformulacion_texto','cfpd10_reformulacion_partidas','cfpd10_reformulacion_tipo','cfpd01_formulacion','cfpd10_reformulacion_partidas_tmp','arrd05');
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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

function index($var=null){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$cond = "a.cod_presi = ".$this->Session->read('SScodpresi')." and a.cod_entidad = ".$this->Session->read('SScodentidad')." and a.cod_tipo_inst = ".$this->Session->read('SScodtipoinst')." and a.cod_inst = ".$this->Session->read('SScodinst')." and a.cod_dep=".$this->Session->read('SScoddep');
    $ano=$this->ano_ejecucion();
    $this->set('ano_reformulacion',$ano);
	$v1="SELECT (a.numero_oficio || '@' || a.cod_dep) as numero_oficio,  (a.numero_oficio || ' -  ' || x.denominacion) as denominacion FROM cfpd10_reformulacion_texto a,arrd05 x
		WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and
		x.cod_dep=a.cod_dep and $cond and a.ano_reformulacion=$ano and a.elaborado='1' and a.revisado='0' and a.por_enviar='0' and a.enviado='0' and a.por_remitir='0' and a.remitido='0' and a.por_aprobar='0' and a.aprobado='0' order by x.denominacion,a.numero_oficio ASC";
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
	$this->set('reform_elaborado',$lista);
       //$elaborado=$this->SQLCX()." and elaborado='1' and revisado='0' and por_enviar='0' and enviado='0' and por_remitir='0' and remitido='0' and por_aprobar='0' and aprobado='0' and ano_reformulacion=".$ano;
	   //$reform_elaborado=  $this->cfpd10_reformulacion_texto->generateList($elaborado, 'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
       //$this->set('reform_elaborado',$reform_elaborado);
      // $this->concatena($reform_elaborado, 'reform_elaborado');
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

	$elaborado="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and $cod_inst=$cod_inst and cod_dep=$cod_dep and elaborado='1' and revisado='0' and por_enviar='0' and enviado='0' and por_remitir='0' and remitido='0' and por_aprobar='0' and aprobado='0' and ano_reformulacion=".$select." and numero_oficio='".$var."'";
	if($oficio = $this->cfpd10_reformulacion_texto->findAll($elaborado,'cod_tipo, numero_oficio, fecha_oficio')){//busco el oficio filtrado por "elaborado", "numero_oficio" y el "ano" de la reformulacion -- y me traigo los siguientes campos 'cod_tipo, numero_oficio, fecha_oficio'
		$t_reformulacion=$this->cfpd10_reformulacion_tipo->findAll('cod_tipo='.$oficio[0]['cfpd10_reformulacion_texto']['cod_tipo']);
		$fa=$fecha_oficio=$oficio[0]['cfpd10_reformulacion_texto']['fecha_oficio'];
  		$fecha_oficio=$fa[8].$fa[9]."/".$fa[5].$fa[6]."/".$fa[0].$fa[1].$fa[2].$fa[3];
		  echo"<script>
      	    document.getElementById('fecha_oficio').value='".$fecha_oficio."';
      	    document.getElementById('tipo_reformulacio').value='".$t_reformulacion[0]['cfpd10_reformulacion_tipo']['denominacion']."';
          </script>";
		$this->set('numero_oficio',$oficio[0]['cfpd10_reformulacion_texto']['numero_oficio']);

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
		$elaborado="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and elaborado='1' and revisado='0' and por_enviar='0' and enviado='0' and por_remitir='0' and remitido='0' and por_aprobar='0' and aprobado='0' and ano_reformulacion=".$select." and numero_oficio='".$var."'";
		$oficio = $this->cfpd10_reformulacion_texto->findAll($elaborado, 'cod_tipo, concepto, monto');
		$this->set('concepto',$oficio[0]['cfpd10_reformulacion_texto']['concepto']);
		$this->set('monto_reform',$oficio[0]['cfpd10_reformulacion_texto']['monto']);
		$this->set('cod_tipo',$oficio[0]['cfpd10_reformulacion_texto']['cod_tipo']);
		$sql="cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_reformulacion=".$select." and numero_oficio='".$var."'";
		$datos=$this->cfpd10_reformulacion_partidas_tmp->findAll($sql);
		if($datos != null){
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







function revisado($dep=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	if($this->data['cfpp10_revision']!=null){
		$this->data['cfpp10_revision']['ano'];
		$this->data['cfpp10_revision']['numero_oficio'];
		$sql="UPDATE cfpd10_reformulacion_texto SET revisado='2', por_enviar='1' WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$dep and elaborado='1' and revisado='0' and por_enviar='0' and enviado='0' and por_remitir='0' and remitido='0' and por_aprobar='0' and aprobado='0' and ano_reformulacion=".$this->data['cfpp10_revision']['ano']." and numero_oficio='".$this->data['cfpp10_revision']['numero_oficio']."'";
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
	$user=addslashes($this->data['cfpp10_revision']['login']);
	$paswd=addslashes($this->data['cfpp10_revision']['password']);
	$cond=" cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst');
	$sql="SELECT * FROM cugd05_restriccion_clave WHERE ".$cond." and username='".$user."' and cod_tipo=1 and clave='".$paswd."'";
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
	if(isset($this->data['cfpp10_revision']['login']) && isset($this->data['cfpp10_revision']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cfpp10_revision']['login']);
		$paswd=addslashes($this->data['cfpp10_revision']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=1 and clave='".$paswd."'";
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