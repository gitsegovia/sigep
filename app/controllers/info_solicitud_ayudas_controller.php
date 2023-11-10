<?php
class InfoSolicitudAyudasController extends AppController {
   var $name = 'info_solicitud_ayudas';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cnmd06_profesiones','cnmd06_oficio','cnmd06_parentesco',
   					'cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_tipo_ayuda','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda','cugd10_imagenes',
   					'v_historia_solicitud_ayudas');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');

function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
				}else{
					$c1=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'J');
					$c2=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'G');
					if($c1!=0 || $c2!=0){
						echo "<script type=\"text/javascript\" language=\"javascript\">error('Por favor registrese cómo persona natural para acceder a la información');</script>";
                        exit();
					}
				}
}//fin checksession



 function beforeFilter(){
 	$this->checkSession();
 }



 function index(){
 	$this->layout ="ajax";

 	$sql="SELECT * from casd01_datos_personales where cedula_identidad::text='".$_SESSION['infogobierno']['cedula_identidad']."'";
	$result=$this->casd01_datos_personales->execute($sql);
	if($result!=null){
		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad::text='".$_SESSION['infogobierno']['cedula_identidad']."' order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('perso',$result);
		$this->set('fami',$result_1);
		$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
		$this->set('oficio',$oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_oficio'], $order ="cod_oficio ASC"));
		$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado'], $order ="cod_estado ASC"));
		$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio'], $order ="cod_municipio ASC"));
		$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia'], $order ="cod_parroquia ASC"));
		$this->set('centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia']." and cod_centro=".$result[0][0]['cod_centro_poblado'], $order ="cod_centro ASC"));
		$this->set('institucion',$this->cugd02_institucion->field('denominacion', $conditions ="cod_tipo_institucion=1 and cod_institucion=".$result[0][0]['cod_inst'], $order ="cod_institucion ASC"));
		$this->set('dependencia',$this->cugd02_dependencia->field('denominacion', $conditions ="cod_tipo_institucion=1 and cod_institucion=".$result[0][0]['cod_inst']." and cod_dependencia=".$result[0][0]['cod_dep'], $order ="cod_institucion ASC"));
		$this->set('paren',$this->cnmd06_parentesco->findAll());

		$sql2="select * from v_historia_solicitud_ayudas where cedula_identidad::text='".$_SESSION['infogobierno']['cedula_identidad']."' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,numero_ocacion asc";
		$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
		if($dato2!=null){
	//		$this->set('msj', array('Disculpe el E-mail introducido ya se encuentra registrado','exito'));
			$this->set('dato2',$dato2);
		}else{
			$this->set('msj', array('usted no posee un historial de solicitudes y ayudas','error'));
			$this->set('dato2',null);
		}

		////////////////////////////////////////////////////////////////////////////////////////////////////

		$this->set('user',$result[0][0]['username']);
		$this->set('cedu',$result[0][0]['cedula_usuario']);
		$this->set('fun',$result[0][0]['nombre_usuario']);
		//////////////////////////////////////////////////////////////////////////////////////////////////////

		$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$_SESSION['infogobierno']['cedula_identidad']."'");
		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}

	}else{
		$this->set('msj', array('usted no posee un historial de solicitudes y ayudas','error'));
		$this->set('dato2',null);
	}



 }// fin index


 function consulta_historial_solicitudes($presi=null,$entidad=null,$tipo_inst=null,$inst=null,$dep=null,$cedula=null,$tipo_ayuda=null,$ocacion=null,$evaluacion=null,$ayuda=null){
$this->layout="ajax";
//echo "holaaaaaaaaaaaa    ".$presi. "  ".$cedula."  ".$ocacion;

	if($evaluacion==null && $ayuda==null){
		$datos=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion);
		$this->set('muestra',1);
	}else if($evaluacion!=null && $ayuda==null){
		$datos1=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion);
		$datos=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion);
		$this->set('muestra',2);
		$this->set('datos1',$datos1);
	}else if($evaluacion!=null && $ayuda!=null){
		$datos1=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion);
		$datos2=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayudas_cuerpo where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion." and numero_documento_ayuda=".$ayuda);
		$datos=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion." and numero_documento_ayuda=".$ayuda);
		$datos3=$this->casd01_ayuda_detalles->execute("select * from casd01_ayuda_detalles where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad::text='".$cedula."' and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion." and numero_documento_ayuda=".$ayuda);
		$this->set('muestra',3);
		$this->set('datos1',$datos1);
		$this->set('dato3',$datos3);
	}

	$this->set('datos',$datos);
	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// fin consulta_historial_solicitudes





}