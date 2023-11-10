<?php

class Casp01GraficoSolicitudesAyudasController extends AppController{


    var $name    = "casp01_grafico_solicitudes_ayudas";
    var $uses    = array('ccfd04_cierre_mes','cugd01_estados','cugd01_municipios','cugd01_parroquias','casd01_datos_personales',
    					'cugd01_centropoblados','casd01_solicitud_ayuda','v_casp01_relacion_solicitudes','v_casd01_relacion_solicitantes',
						'casd01_tipo_ayuda','v_casd01_ubicacion_geografica','v_casd01_ubicacion_geografica_tipo_2');


    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');




function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession





function beforeFilter(){$this->checkSession();}

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
    function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
         	$sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA






function index(){
	$this->layout="ajax";


}//fin index


function ubicacion($var=null){
	$this->layout="ajax";
	$this->set('tipo',$var);
		$cond =" cod_republica=".$this->Session->read('SScodpresi');
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');

	echo "<script>
			document.getElementById('grafico').innerHTML='';
			document.getElementById('generar').disabled='disabled';
		</script>";

}// fin ubicacion


function fechas($var=null){
	$this->layout="ajax";
	if($var==''){
		$this->set('muestra',null);
	}else{
		$this->set('muestra','muestra');
	}
	echo "<script>
			document.getElementById('grafico').innerHTML='';
		</script>";

}



function select3($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'municipio':
				$this->set('no','');
				$this->set('SELECT','parroquia');
				$this->set('codigo','municipio');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('cod1',$var);
				$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$var;
				$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'parroquia':
				$this->set('no','');
				$this->set('SELECT','centro');
				$this->set('codigo','parroquia');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('cod2',$var);
				$cod1=$this->Session->read('cod1');
				$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$cod1." and cod_municipio=".$var;
				$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
//				print_r($lista);
			break;
			case 'centro':
				$this->set('no','no');
				$this->set('SELECT','centro');
				$this->set('codigo','centro');
				$this->set('seleccion','');
				$this->set('n',4);
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$var;
				$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
		}//fin switch
	}

	echo "<script>
			document.getElementById('grafico').innerHTML='';
		</script>";


}//fin select 3



function grafico(){
	$this->layout="ajax";

	 $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);
//pr($this->data);
	$peticion=$this->data['casp01']['peticion'];

	$tipo_peticion=$this->data['casp01']['tipo_peticion'];
	if($tipo_peticion==1){
		if(empty($this->data['casp01']['rango'])){
				if($peticion==1){
					$organismo='';
				}else if($peticion==2){
					$organismo=" where ".$this->condicionNDEP();
				}else{
					$organismo=" where ".$this->SQLCA();
				}
//			$ver1=$this->v_casd01_ubicacion_geografica->execute("select sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_ayudas from v_casd01_ubicacion_geografica where ".$organismo." and cod_municipio=0 and cod_parroquia=0 and cod_centro_poblado=0");
			$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes ".$organismo);

		}else{
			if($peticion==1){
				$organismo='';
			}else if($peticion==2){
				$organismo=$this->condicionNDEP()." and ";
			}else{
				$organismo=$this->SQLCA()." and ";
			}
			$fecha_inicial=$this->data['casp01']['fecha_inicial'];
			$fecha_final=$this->data['casp01']['fecha_final'];
			//v_casp01_relacion_solicitudes
			$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$organismo." (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')");

		}
		$cantidad=$ver1[0][0]['solicitudes'];
		$resta=$ver1[0][0]['solicitudes']-$ver1[0][0]['ayudas'];

		$atendidas=$this->Formato2((($ver1[0][0]['ayudas']*100)/$cantidad));

		$no_atendidas=$this->Formato2((($resta*100)/$cantidad));

		$this->set('cantidad',$ver1[0][0]['solicitudes']);
		$this->set('ayudas',$ver1[0][0]['ayudas']);
		$this->set('solicitudes',$resta);
		$this->set('atendidas',$atendidas);
		$this->set('no_atendidas',$no_atendidas);
		$this->set('monto',$ver1[0][0]['monto_ayudas']);

	}else{
		if($peticion==1){
			$organismo='';
		}else if($peticion==2){
			$organismo=" and ".$this->condicionNDEP();
		}else{
			$organismo=" and ".$this->SQLCA();
		}

		if(empty($this->data['casp01']['rango'])){
			$tabla='v_casp01_relacion_solicitudes';
			$filtro=$organismo;
			$campos="count(numero_ocacion) as numero_solicitudes,count(numero_documento_ayuda) as numero_ayudas,sum(monto_total) as monto_ayudas";
		}else{
			$fecha_inicial=$this->data['casp01']['fecha_inicial'];
			$fecha_final=$this->data['casp01']['fecha_final'];
			$tabla='v_casp01_relacion_solicitudes';
			$filtro=$organismo." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
			$campos="count(numero_ocacion) as numero_solicitudes,count(numero_documento_ayuda) as numero_ayudas,sum(monto_total) as monto_ayudas";
		}

		if(!empty($this->data['casp01']['estado']) && empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
			$estado=$this->data['casp01']['estado'];
			$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado.$filtro);
		}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
			//solo el estado y municipio
			$estado=$this->data['casp01']['estado'];
			$municipio=$this->data['casp01']['cod_municipio'];
			$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio.$filtro);
		}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
			//solo el estado y municipio y parroquia
			$estado=$this->data['casp01']['estado'];
			$municipio=$this->data['casp01']['cod_municipio'];
			$parroquia=$this->data['casp01']['cod_parroquia'];
			$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia.$filtro);
		}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && !empty($this->data['casp01']['cod_centro'])){
			//solo el estado y municipio y parroquia y centro
			$estado=$this->data['casp01']['estado'];
			$municipio=$this->data['casp01']['cod_municipio'];
			$parroquia=$this->data['casp01']['cod_parroquia'];
			$centro=$this->data['casp01']['cod_centro'];
			$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro.$filtro);
		}else{
			/*$this->ubicacion(2);
			$this->render('ubicacion');*/
			echo "<script>
				document.getElementById('generar').disabled='disabled';
				document.getElementById('save').disabled=false;
			</script>";
			$this->set('vacio','');
			return;
		}

		$cantidad=$ver1[0][0]['numero_solicitudes'];
		$resta=$ver1[0][0]['numero_solicitudes']-$ver1[0][0]['numero_ayudas'];

		$atendidas=$this->Formato2((($ver1[0][0]['numero_ayudas']*100)/$cantidad));

		$no_atendidas=$this->Formato2((($resta*100)/$cantidad));

		$this->set('cantidad',$ver1[0][0]['numero_solicitudes']);
		$this->set('ayudas',$ver1[0][0]['numero_ayudas']);
		$this->set('solicitudes',$resta);
		$this->set('atendidas',$atendidas);
		$this->set('no_atendidas',$no_atendidas);
		$this->set('monto',$ver1[0][0]['monto_ayudas']);


	}

	echo "<script>
			document.getElementById('generar').disabled=false;
			document.getElementById('save').disabled=false;
		</script>";

}// fin grafico



function grafico_pdf(){
		$this->layout="ajax";

//		pr($this->data);
		$username = $this->Session->read('nom_usuario');
    	$this->set('user', $username);
    	$this->set('rdm', $this->data['graficos1']['rdm']);

		$solicitudes=$this->data['graficos1']['solicitudes'];
		$ayudas=$this->data['graficos1']['ayudas'];
		$atendidas=$this->data['graficos1']['atendidas'];
		$no_atendidas=$this->data['graficos1']['no_atendidas'];
		$monto=$this->data['graficos1']['monto'];
		$cantidad=$this->data['graficos1']['cantidad'];

		$this->set('ayudas',$ayudas);
		$this->set('solicitudes',$solicitudes);
		$this->set('atendidas',$atendidas);
		$this->set('no_atendidas',$no_atendidas);
		$this->set('monto',$monto);
		$this->set('cantidad',$cantidad);


	}// fin grafico_pdf


}// fin class