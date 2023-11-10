<?php

class Canp00GraficoSolicitudesAyudasController extends AppController{


    var $name    = "canp00_grafico_solicitudes_ayudas";
    var $uses    = array('arrd01','ccfd03_instalacion','ccfd04_cierre_mes','cugd01_estados','cugd01_municipios','cugd01_parroquias','casd01_datos_personales',
    					'cugd01_centropoblados','casd01_solicitud_ayuda','v_casp01_relacion_solicitudes','v_casd01_relacion_solicitantes',
						'casd01_tipo_ayuda','v_casd01_ubicacion_geografica','v_casd01_ubicacion_geografica_tipo_2');


    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');


function checkSession(){

        if (!$this->Session->check('Usuario'))
        {
            $this->redirect('/salir');
            exit();
        }
}

 function beforeFilter(){
    $this->checkSession();

 }



function index(){
	$this->layout="ajax";

	$datos  = $this->arrd01->execute(" SELECT DISTINCT substr(a.fecha_solicitud::text,0,5)::integer as ano FROM casd01_solicitud_ayuda a ");
  	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
	if(count($datos)!=0){
			foreach($datos as $n){
				$cod[]  = $n[0]['ano'];
				$deno[] = $n[0]['ano'];
			}
			$lista=array_combine($cod, $deno);
		}else{
			$lista=array('0'=>'No existen registros de solicitudes');
		}
	$this->set("ano_estimacion", $lista);
	$this->set("ano_ejecucion" , '2009');

	                $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);


}//fin index


function grafico(){
	$this->layout="ajax";

	 $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);
//pr($this->data);

	$sql ='';


  if(!empty($this->data["datos"]["ano_consolidado"])){

  	if($this->data["datos"]["ano_consolidado"]!="TODO"){

//              $sql .=" and ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";
 				$sql .="substr(fecha_solicitud::text,0,5)::integer ='".$this->data["datos"]["ano_consolidado"]."' ";
  	}else{
  		$sql="1=1";
  	}
  }else{
		return;
  }

	if(!empty($this->data["datos"]["cod_presi"])){

           $sql .= " and cod_presi=".$this->data["datos"]["cod_presi"];

  }


  if(!empty($this->data["datos"]["cod_entidad"])){

  	if($this->data["datos"]["cod_entidad"]!="TODO"){

              $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";

  	}
  }

   if(!empty($this->data["datos"]["cod_tipo_inst"])){

  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){

              $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";

  	 }
   }

  if(!empty($this->data["datos"]["cod_inst"])){

  	if($this->data["datos"]["cod_inst"]!="TODO"){

              $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";

  	}
  }


  $ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$sql);

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

	echo "<script>
		document.getElementById('generar').disabled=false;
		document.getElementById('save').disabled=false;
	</script>";



}// fin grafico



function grafico_pdf(){
		$this->layout="pdf";

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


///////////////////////////////////////////tipo de ayudas solicitada///////////////////////////////////




function index_tipo(){
	$this->layout="ajax";

	$datos  = $this->arrd01->execute(" SELECT DISTINCT substr(a.fecha_solicitud::text,0,5)::integer as ano FROM casd01_solicitud_ayuda a ");
  	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
	if(count($datos)!=0){
			foreach($datos as $n){
				$cod[]  = $n[0]['ano'];
				$deno[] = $n[0]['ano'];
			}
			$lista=array_combine($cod, $deno);
		}else{
			$lista=array('0'=>'No existen registros de solicitudes');
		}
	$this->set("ano_estimacion", $lista);
	$this->set("ano_ejecucion" , '2009');

	                $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);


}//fin index




function grafico_tipo(){
	$this->layout="ajax";

	 $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);
//pr($this->data);

	$sql ='';


  if(!empty($this->data["datos"]["ano_consolidado"])){

  	if($this->data["datos"]["ano_consolidado"]!="TODO"){

//              $sql .=" and ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";
 				$sql .="substr(fecha_solicitud::text,0,5)::integer ='".$this->data["datos"]["ano_consolidado"]."' ";
  	}else{
  		$sql="1=1";
  	}
  }else{
		return;
  }

	if(!empty($this->data["datos"]["cod_presi"])){

           $sql .= " and cod_presi=".$this->data["datos"]["cod_presi"];

  }


  if(!empty($this->data["datos"]["cod_entidad"])){

  	if($this->data["datos"]["cod_entidad"]!="TODO"){

              $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";

  	}
  }

   if(!empty($this->data["datos"]["cod_tipo_inst"])){

  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){

              $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";

  	 }
   }

  if(!empty($this->data["datos"]["cod_inst"])){

  	if($this->data["datos"]["cod_inst"]!="TODO"){

              $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";

  	}
  }

	$sql1=" SELECT
		 cod_tipo_ayuda,
		 quitar_acentos(tipo_ayuda::text) AS denominacion_ayuda,
		 count(numero_ocacion) AS solicitudes,
		 count(numero_documento_ayuda) AS ayudas,
		 sum(monto_total) AS monto_total
		 FROM v_casp01_relacion_solicitudes where ".$sql."
		 GROUP BY
		 cod_tipo_ayuda,
		 quitar_acentos(tipo_ayuda::text)
		 ORDER BY
		 count(numero_ocacion)
		 DESC";
		$ver1=$this->v_casp01_relacion_solicitudes->execute($sql1);
		$sumatoria=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as total_solicitudes from v_casp01_relacion_solicitudes where ".$sql);

		$this->set('grafico',$ver1);
		$this->set('sumatoria',$sumatoria[0][0]['total_solicitudes']);
		$_SESSION['grafico']=$ver1;


	echo "<script>
		document.getElementById('generar').disabled=false;
		document.getElementById('save').disabled=false;
	</script>";


}// fin grafico



function grafico_pdf_tipo(){
		$this->layout="pdf";

//		pr($this->data);
		$username = $this->Session->read('nom_usuario');
    	$this->set('user', $username);
    	$this->set('rdm', $this->data['graficos1']['rdm']);

		$sumatoria=$this->data['graficos1']['sumatoria'];

		$this->set('sumatoria',$sumatoria);


	}// fin grafico_pdf


}// fin class