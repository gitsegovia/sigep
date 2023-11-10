<?php
class Capp03DocumentoOrigenController extends AppController {
   var $name = 'capp03_documento_origen';
   var $uses = array('capd01_tipo_documento','capd02_procesos','capd03_numero','ccfd04_cierre_mes','cugd02_direccionsuperior',
   					'cugd02_coordinacion','cugd02_secretaria','cugd02_direccion','cugd02_division','cugd02_departamento',
   					'cepd01_compromiso_beneficiario_cedula','cepd01_compromiso_beneficiario_rif','cpcd02','capd03_documentos',
   					'cepd01_compromiso_cuerpo','cscd04_ordencompra_encabezado','cepd02_contratoservicio_cuerpo','v_cobp01_cfpd07_cuerpo',
   					'cscd03_cotizacion_encabezado','cscd02_solicitud_encabezado','capd04_flujo');
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
 	 /*echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';*/
 }




function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";

	 $ano=$this->ano_ejecucion();

      $maxi=$this->capd03_numero->findCount($this->SQLCA()." and ano=".$ano." and situacion=1");
      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
      if($maxi==0){
         $this->set("errorMessage","Verifique el n&uacute;mero de control de documentos de origen");
      	 $this->set("numero_control","");
      	 $this->redirect("/capp03_atencion_publico_numero/index/numero");
      }
      if(isset($_SESSION["MSJ"])){
					$a=$_SESSION["MSJ"];
					if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
					else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);
					$this->Session->delete("MSJ");
	  }

}//fin index



 function index2(){
 	$this->layout ="ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');



	$lista=$this->capd01_tipo_documento->execute("select distinct b.cod_tipo_documento,
												(select a.denominacion from capd01_tipo_documento a where a.cod_tipo_documento=b.cod_tipo_documento and a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep.") as denominacion from capd02_procesos b
												where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and
												(select c.paso from capd02_procesos c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=".$cod_dep." and c.cod_tipo_documento=b.cod_tipo_documento order by paso desc limit 1)=(select aa.pasos_cumplir from capd01_tipo_documento aa where aa.cod_tipo_documento=b.cod_tipo_documento and aa.cod_presi=".$cod_presi." and aa.cod_entidad=".$cod_entidad." and aa.cod_tipo_inst=".$cod_tipo_inst." and aa.cod_inst=".$cod_inst." and aa.cod_dep=".$cod_dep.")");
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_tipo_documento"];
		$v[]=$l[0]["denominacion"];
//		$v[1]=$l[0]["denominacion"];
$i++;
	}
	$lista = array_combine($r, $v);
 	$this->set('documentos',$lista);

 	$ano=$this->ano_ejecucion();
 	$this->set('year',$ano);

 	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista1=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($lista1, 'dir_sup');



	$maxi=$this->capd03_numero->findCount($this->SQLCA());
      $max=$this->capd03_numero->execute("SELECT numero_control FROM capd03_numero WHERE ".$this->SQLCA()." and ano=".$ano." and situacion=1 ORDER BY numero_control ASC LIMIT 1");
      //echo "numero".$maxi;
      //print_r($max);
      if($max!=null){
      	    $codigo=$max[0][0]["numero_control"];
            $resultado=$this->capd03_numero->execute("UPDATE  capd03_numero SET situacion=2 WHERE ".$this->SQLCA()." and numero_control=".$codigo." and ano=".$ano);
	         if($resultado>1){
                //$this->set("Message_existe","Situacion de solicitud actualizada con exito");
               $this->set("numero_control",$codigo);
	         }else{
		        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de solicitudes");
		        $this->set("numero_control","");
		        $MSJ1=array("msj"=>"debe registrar nuevos numeros para la solicitud de recursos","tipo_msj"=>"exito");
				$this->Session->write("MSJ1",$MSJ1);
		        $this->redirect("/capp03_atencion_publico_numero/index/numero/otro");
	      }
      }else{
      	 $this->set("errorMessage","Verifique el n&uacute;mero de control de solicitudes");
      	 $this->set("numero_control","");
      	 $MSJ1=array("msj"=>"debe registrar nuevos numeros para la solicitud de recursos","tipo_msj"=>"exito");
		 $this->Session->write("MSJ1",$MSJ1);
      	 $this->redirect("/capp03_atencion_publico_numero/index/numero/otro");
      }

 ///////////////////////////////////FIN PRUEBA NUMERO//////////////////////////////////

$this->data=null;

 }// fin index


function informacion($tipo_documento=null){
	$this->layout ="ajax";

	$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);
	$this->set('pasos',$deno1[0][0]['pasos_cumplir']);

	$deno2=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento." order by paso asc");
	$this->set('observacion',$deno2[0][0]['proceso_realizar_entrada']);

	 $paso= explode('/',date("d/m/Y"));
	 $mes=$paso[1];
	 $ano=$paso[2];

	 $this->set('tipo_documento',$tipo_documento);

	$dias=$paso[0]+$deno1[0][0]['dias_probable_pago'];
	$fecha=$dias."/".$mes."/".$ano;
	$this->set('fecha_probable',date( "Y-m-d", strtotime("+".$deno1[0][0]['dias_probable_pago']." day" )));
}//fin informacion


function suma_fechas($fecha,$ndias){

      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))

              list($dia,$mes,$año)=split("/", $fecha);

      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))

              list($dia,$mes,$año)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y",$nueva);

      return ($nuevafecha);

}//fin suma fechas



function ver($tipo_documento=null,$dia=null,$mes=null,$ano=null){
	$this->layout ="ajax";

	$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$tipo_documento);

	$fecha=$dia."/".$mes."/".$ano;
	$suma=$this->suma_fechas($fecha, $deno1[0][0]['dias_probable_pago']);
	$this->set('fecha_probable',$suma);

}// fin ver


function rif_cedula($opcion=null,$tipo_documento=null,$numero_doc=null){
	$this->layout ="ajax";
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	$ano=$this->ano_ejecucion();

	if($numero_doc!=''){

		if($tipo_documento==1){
			$dato1=$this->cepd01_compromiso_cuerpo->execute("select * from cepd01_compromiso_cuerpo where  ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_doc);
			if($dato1[0][0]['rif']=='0'){
				$rif=$dato1[0][0]['cedula_identidad'];
			}else{
				$rif=$dato1[0][0]['rif'];
			}
			$beneficiario=$dato1[0][0]['beneficiario'];
		}else if($tipo_documento==2){
			$dato1=$this->cscd04_ordencompra_encabezado->execute("select * from cscd04_ordencompra_encabezado where  ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$numero_doc);
			$rif=$dato1[0][0]['rif'];
		}else if($tipo_documento==3){
			if($SScoddep==1 && $Modulo==0){
				$dato1=$this->v_cobp01_cfpd07_cuerpo->execute("select * from v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo where  ".$this->condicionNDEP()." and ano_contrato_obra=".$ano." and numero_contrato_obra='$numero_doc'");
			}else{
				$dato1=$this->v_cobp01_cfpd07_cuerpo->execute("select * from v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo where  ".$this->condicionNDEP()." and cod_dep_original=".$SScoddeporig." and ano_contrato_obra=".$ano." and numero_contrato_obra='$numero_doc'");
//			pr($dato1);
			}
			$rif=$dato1[0][0]['rif'];
		}else if($tipo_documento==4){
			$dato1=$this->cepd02_contratoservicio_cuerpo->execute("select * from cepd02_contratoservicio_cuerpo where  ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='$numero_doc'");
			$rif=$dato1[0][0]['rif'];
		}


		if($tipo_documento!=1){
			$dato2=$this->cpcd02->execute("select denominacion from cpcd02 where rif='$rif'");
			$beneficiario=$dato2[0][0]['denominacion'];
		}
	//////////////////////////////////////////////////////////

		$this->set('rif',$rif);
		$this->set('beneficiario',$beneficiario);
		if($opcion=='rif'){
			$nombre='rif_cedula';
		}else if($opcion=='beneficiario'){
			$nombre='beneficiario';
		}
		$this->set('nombre',$nombre);
			$this->set('tipo_documento','');
	}else{
		$this->set('tipo_documento',$tipo_documento);
		$this->set('readonly','readonly');
	}

}//fin rif_cedula

function busqueda($rif_cedula=null){
	$this->layout ="ajax";
//	'cepd01_compromiso_beneficiario_cedula','cepd01_compromiso_beneficiario_rif,'cpcd02''

	$dato=$this->cepd01_compromiso_beneficiario_cedula->execute("select * from cepd01_compromiso_beneficiario_cedula where  cedula='$rif_cedula'");
	if($dato!=null){
		$beneficiario=$dato[0][0]['beneficiario'];
		$this->set('beneficiario',$beneficiario);
	}else{
		$dato1=$this->cepd01_compromiso_beneficiario_rif->execute("select * from cepd01_compromiso_beneficiario_rif where  rif='$rif_cedula'");
		if($dato1!=null){
			$beneficiario=$dato1[0][0]['beneficiario'];
			$this->set('beneficiario',$beneficiario);
		}else{
			$dato2=$this->cpcd02->execute("select * from cpcd02 where  rif='$rif_cedula'");
			if($dato2!=null){
				$beneficiario=$dato2[0][0]['denominacion'];
				$this->set('beneficiario',$beneficiario);
			}else{
				$this->set('errorMessage', 'ingrese el beneficiario');
			}
		}
	}

}// fin busqueda




function mostrar($opcion=null,$tipo_documento=null,$var2=null){
	$this->layout ="ajax";

	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

	$ano=$this->ano_ejecucion();
if($var2!=''){
	if($opcion=='monto'){
		if($tipo_documento==1){
			$dato1=$this->cepd01_compromiso_cuerpo->execute("select * from cepd01_compromiso_cuerpo where  ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$var2);
			$this->set('monto',$dato1[0][0]['monto']);
		}else if($tipo_documento==2){
			$dato1=$this->cscd04_ordencompra_encabezado->execute("select * from cscd04_ordencompra_encabezado where  ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$var2);
			$monto=(($dato1[0][0]['monto_orden'] + $dato1[0][0]['modificacion_aumento']) - $dato1[0][0]['modificacion_disminucion']);
			$this->set('monto',$monto);
		}else if($tipo_documento==3){
			if($SScoddep==1 && $Modulo==0){
				$dato1=$this->v_cobp01_cfpd07_cuerpo->execute("select * from v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo where  ".$this->condicionNDEP()." and ano_contrato_obra=".$ano." and numero_contrato_obra='$var2'");
			}else{
				$dato1=$this->v_cobp01_cfpd07_cuerpo->execute("select * from v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo where  ".$this->condicionNDEP()." and cod_dep_original=".$SScoddeporig." and ano_contrato_obra=".$ano." and numero_contrato_obra='$var2'");
			}
			$monto=(($dato1[0][0]['monto_original_contrato'] + $dato1[0][0]['aumento']) - $dato1[0][0]['disminucion']);
			$this->set('monto',$monto);
		}else if($tipo_documento==4){
			$dato1=$this->cepd02_contratoservicio_cuerpo->execute("select * from cepd02_contratoservicio_cuerpo where  ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='$var2'");
			$monto=(($dato1[0][0]['monto_original_contrato'] + $dato1[0][0]['aumento']) - $dato1[0][0]['disminucion']);
			$this->set('monto',$monto);
		}
	}
}else{
	$this->set('monto','');
	$this->set('opcion',$opcion);
}

}//fin mostrar


function numero_documento($tipo_documento=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

	$ano=$this->ano_ejecucion();
	if($tipo_documento==1){
		$lista=$this->cepd01_compromiso_cuerpo->generateList($this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_documento ASC', null, '{n}.cepd01_compromiso_cuerpo.numero_documento', '{n}.cepd01_compromiso_cuerpo.numero_documento');
		$this->set('numero_documento',$lista);
	}else if($tipo_documento==2){

		$lista=$this->cscd04_ordencompra_encabezado->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_orden_compra,a.numero_orden_compra FROM cscd04_ordencompra_encabezado a
												where a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep." and a.ano_orden_compra=".$ano." and a.condicion_actividad=1 and ((select count(b.numero_orden_compra) from cscd04_ordencompra_anticipo_cuerpo b where b.cod_presi=".$cod_presi." and b.cod_entidad=".$cod_entidad." and b.cod_tipo_inst=".$cod_tipo_inst." and b.cod_inst=".$cod_inst." and b.cod_dep=".$cod_dep." and b.ano_orden_compra=".$ano." and b.numero_orden_compra=a.numero_orden_compra and b.condicion_actividad=1 and b.numero_orden_pago=0)!=0
												or (select count(c.numero_orden_compra) from cscd04_ordencompra_autorizacion_pago_cuerpo c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=".$cod_dep." and c.ano_orden_compra=".$ano." and c.numero_orden_compra=a.numero_orden_compra and c.condicion_actividad=1 and c.numero_orden_pago=0)!=0) order by a.numero_orden_compra asc");
		$campo="numero_orden_compra";
		/*$lista=$this->cscd04_ordencompra_encabezado->generateList($this->SQLCA()." and ano_orden_compra=".$ano,'numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
		$this->set('numero_documento',$lista);*/
	}else if($tipo_documento==3){
		if($SScoddep==1 && $Modulo==0){
			$lista=$this->v_cobp01_cfpd07_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_dep_original,a.ano_contrato_obra,a.numero_contrato_obra FROM v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo a
												where a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.ano_contrato_obra=".$ano." and a.condicion_actividad=1 and ((select count(b.numero_contrato_obra) from cobd01_contratoobras_anticipo_cuerpo b where b.cod_presi=".$cod_presi." and b.cod_entidad=".$cod_entidad." and b.cod_tipo_inst=".$cod_tipo_inst." and b.cod_inst=".$cod_inst." and b.cod_dep=a.cod_dep and b.ano_contrato_obra=a.ano_contrato_obra and b.numero_contrato_obra=a.numero_contrato_obra and b.condicion_actividad=1 and b.numero_orden_pago=0)!=0
												or (select count(c.numero_contrato_obra) from cobd01_contratoobras_retencion_cuerpo c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=a.cod_dep and c.ano_contrato_obra=a.ano_contrato_obra and c.numero_contrato_obra=a.numero_contrato_obra and c.condicion_actividad=1 and c.numero_orden_pago=0)!=0
												or (select count(d.numero_contrato_obra) from cobd01_contratoobras_valuacion_cuerpo d where d.cod_presi=".$cod_presi." and d.cod_entidad=".$cod_entidad." and d.cod_tipo_inst=".$cod_tipo_inst." and d.cod_inst=".$cod_inst." and d.cod_dep=a.cod_dep and d.ano_contrato_obra=a.ano_contrato_obra and d.numero_contrato_obra=a.numero_contrato_obra and d.condicion_actividad=1 and d.numero_orden_pago=0)!=0 )order by a.numero_contrato_obra asc");

//			$lista=$this->v_cobp01_cfpd07_cuerpo->generateList($this->condicionNDEP()."and ano_contrato_obra=".$ano,'numero_contrato_obra ASC', null, '{n}.v_cobp01_cfpd07_cuerpo.numero_contrato_obra', '{n}.v_cobp01_cfpd07_cuerpo.numero_contrato_obra');
//			$this->set('numero_documento',$lista);
		}else{

			$lista=$this->v_cobp01_cfpd07_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_dep_original,a.ano_contrato_obra,a.numero_contrato_obra FROM v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo a
												where a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep_original=".$SScoddeporig." and a.ano_contrato_obra=".$ano." and a.condicion_actividad=1 and ((select count(b.numero_contrato_obra) from cobd01_contratoobras_anticipo_cuerpo b where b.cod_presi=".$cod_presi." and b.cod_entidad=".$cod_entidad." and b.cod_tipo_inst=".$cod_tipo_inst." and b.cod_inst=".$cod_inst." and b.cod_dep=a.cod_dep and b.ano_contrato_obra=a.ano_contrato_obra and b.numero_contrato_obra=a.numero_contrato_obra and b.condicion_actividad=1 and b.numero_orden_pago=0)!=0
												or (select count(c.numero_contrato_obra) from cobd01_contratoobras_retencion_cuerpo c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=a.cod_dep and c.ano_contrato_obra=a.ano_contrato_obra and c.numero_contrato_obra=a.numero_contrato_obra and c.condicion_actividad=1 and c.numero_orden_pago=0)!=0
												or (select count(d.numero_contrato_obra) from cobd01_contratoobras_valuacion_cuerpo d where d.cod_presi=".$cod_presi." and d.cod_entidad=".$cod_entidad." and d.cod_tipo_inst=".$cod_tipo_inst." and d.cod_inst=".$cod_inst." and d.cod_dep=a.cod_dep and d.ano_contrato_obra=a.ano_contrato_obra and d.numero_contrato_obra=a.numero_contrato_obra and d.condicion_actividad=1 and d.numero_orden_pago=0)!=0 )order by a.numero_contrato_obra asc");

//			$lista=$this->v_cobp01_cfpd07_cuerpo->generateList($this->condicionNDEP()." and cod_dep_original=".$SScoddeporig." and ano_contrato_obra=".$ano,'numero_contrato_obra ASC', null, '{n}.v_cobp01_cfpd07_cuerpo.numero_contrato_obra', '{n}.v_cobp01_cfpd07_cuerpo.numero_contrato_obra');
//			$this->set('numero_documento',$lista);
		}
		$campo="numero_contrato_obra";
	}else if($tipo_documento==4){
			$lista=$this->cepd02_contratoservicio_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_contrato_servicio,a.numero_contrato_servicio FROM cepd02_contratoservicio_cuerpo a
												where a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep." and a.ano_contrato_servicio=".$ano." and a.condicion_actividad=1 and ((select count(b.numero_contrato_servicio) from cepd02_contratoservicio_anticipo_cuerpo b where b.cod_presi=".$cod_presi." and b.cod_entidad=".$cod_entidad." and b.cod_tipo_inst=".$cod_tipo_inst." and b.cod_inst=".$cod_inst." and b.cod_dep=a.cod_dep and b.ano_contrato_servicio=a.ano_contrato_servicio and b.numero_contrato_servicio=a.numero_contrato_servicio and b.condicion_actividad=1 and b.numero_orden_pago=0)!=0
												or (select count(c.numero_contrato_servicio) from cepd02_contratoservicio_retencion_cuerpo c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=a.cod_dep and c.ano_contrato_servicio=a.ano_contrato_servicio and c.numero_contrato_servicio=a.numero_contrato_servicio and c.condicion_actividad=1 and c.numero_orden_pago=0)!=0
												or (select count(d.numero_contrato_servicio) from cepd02_contratoservicio_valuacion_cuerpo d where d.cod_presi=".$cod_presi." and d.cod_entidad=".$cod_entidad." and d.cod_tipo_inst=".$cod_tipo_inst." and d.cod_inst=".$cod_inst." and d.cod_dep=a.cod_dep and d.ano_contrato_servicio=a.ano_contrato_servicio and d.numero_contrato_servicio=a.numero_contrato_servicio and d.condicion_actividad=1 and d.numero_orden_pago=0)!=0 )order by a.numero_contrato_servicio asc");
	$campo="numero_contrato_servicio";
//	pr($lista);
//		$lista=$this->cepd02_contratoservicio_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano,'numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');
//		$this->set('numero_documento',$lista);
	}
if($tipo_documento!=1){
	if($lista!=null){
		$i=1;
		foreach($lista as $l){
			$r[]=$l[0][$campo];
			$i++;
		}
		$lista = array_combine($r, $r);
		if($lista==null){
			$this->set('numero_documento',array());
		}else{
			$this->set('numero_documento',$lista);
		}
	}else{
		$this->set('numero_documento',array());
	}

	$this->set('tipo_documento',$tipo_documento);
}


echo "<script>
		document.getElementById('cedula_rif').value='';
		document.getElementById('beneficiario').value='';
	  </script>";
}// fin numero_documento


function administrativa($tipo_documento=null,$numero_doc=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();
	if($tipo_documento==1 || $tipo_documento==2){
		if($tipo_documento==1){
			$dato3=$this->cepd01_compromiso_cuerpo->execute("select * from cepd01_compromiso_cuerpo where  ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_doc);
		}else{
			$dato1=$this->cscd04_ordencompra_encabezado->execute("select * from cscd04_ordencompra_encabezado where  ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$numero_doc);
//			pr($dato1);
			$dato2=$this->cscd03_cotizacion_encabezado->execute("select * from cscd03_cotizacion_encabezado where  ".$this->SQLCA()." and ano_ordencompra='".$dato1[0][0]['ano_orden_compra']."' and rif='".$dato1[0][0]['rif']."' and numero_cotizacion='".$dato1[0][0]['numero_cotizacion']."'");
//			pr($dato2);
			$dato3=$this->cscd02_solicitud_encabezado->execute("select * from cscd02_solicitud_encabezado where  ".$this->SQLCA()." and ano_solicitud='".$dato2[0][0]['ano_solicitud']."' and numero_solicitud='".$dato2[0][0]['numero_solicitud']."'");

		}
			$this->set('mostrar','input');
			$this->set('dato3',$dato3);

			$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." and cod_dir_superior=".$dato3[0][0]["cod_dir_superior"];
			$this->set('dir_sup',$this->cugd02_direccionsuperior->field('denominacion', $conditions =$cond, $order =null));

			$cond.=" and cod_coordinacion=".$dato3[0][0]["cod_coordinacion"];
			$this->set('coordinacion',$this->cugd02_coordinacion->field('denominacion', $conditions =$cond, $order =null));

			$cond.=" and cod_secretaria=".$dato3[0][0]["cod_secretaria"];
			$this->set('secretaria',$this->cugd02_secretaria->field('denominacion', $conditions =$cond, $order =null));

			$cond.=" and cod_direccion=".$dato3[0][0]["cod_direccion"];
			$this->set('direccion',$this->cugd02_direccion->field('denominacion', $conditions =$cond, $order =null));
			if($tipo_documento!=1){
					$this->set('cod_division',$dato3[0][0]["cod_division"]);
					if($dato3[0][0]["cod_division"]!=''){
					$cond.=" and cod_division=".$dato3[0][0]["cod_division"];
					$this->set('division',$this->cugd02_division->field('denominacion', $conditions =$cond, $order =null));

					}else{
						$this->set('division','');
					}

					if($dato3[0][0]["cod_departamento"]!=''){
						$this->set('cod_departamento',$dato3[0][0]["cod_departamento"]);
						$cond.=" and cod_departamento=".$dato3[0][0]["cod_departamento"];
						$this->set('departamento',$this->cugd02_departamento->field('denominacion', $conditions =$cond, $order =null));

					}else{
						$this->set('departamento','');
					}
			}else{
				$this->set('cod_division','');
				$this->set('division','');
				$this->set('cod_departamento','');
				$this->set('departamento','');
			}

	}else{
		$this->set('mostrar','select');
		$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
		$lista1=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
		$this->concatena($lista1, 'dir_sup');
	}

}// fin administrativa

function select2($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$inst                 =       $this->Session->read('SScodinst');
	$dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'coordinacion':
				$this->set('no','');
				$this->set('SELECT','secretaria');
				$this->set('codigo','coordinacion');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('dir_sup',$var);
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$var;
				$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'secretaria':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','direccion');
				$this->set('codigo','secretaria');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('coor',$var);
				$dir_sur=$this->Session->read('dir_sup');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$var;
				$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'direccion':
				$this->set('no','');
				$this->set('SELECT','division');
				$this->set('codigo','direccion');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->Session->write('secre',$var);
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
				$lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'division':
				$this->set('no','');
				$this->set('SELECT','departamento');
				$this->set('codigo','division');
				$this->set('seleccion','');
				$this->set('n',5);
				$this->Session->write('direc',$var);
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$var;
				$lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'departamento':
				$this->set('no','');
				$this->set('SELECT','oficina');
				$this->set('codigo','departamento');
				$this->set('seleccion','');
				$this->set('n',6);
				$this->Session->write('div',$var);
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$direc=$this->Session->read('direc');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$direc." and cod_division=".$var;
				$lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'oficina':
				$this->set('no','no');
				$this->set('SELECT','oficina');
				$this->set('codigo','oficina');
				$this->set('seleccion','');
				$this->set('n',7);
				$this->Session->write('depar',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$direc=$this->Session->read('direc');
				$div=$this->Session->read('div');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$direc." and cod_division=".$div." and cod_departamento=".$var;
				$lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
		}//fin switch
	}


}//fin select3


function mostrar2($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$inst                 =       $this->Session->read('SScodinst');
	$dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'deno_superior'://echo $opcion;
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$var;
				$deno_parroquia = $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond, $order ="cod_dir_superior ASC");
				$this->set('denomi', $deno_parroquia);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_coordinacion1').value='';";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
				echo "</script>";
			break;
			case 'deno_coordinacion':
				$dir_sup=$this->Session->read('dir_sup');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$var;
				$deno_banco = $this->cugd02_coordinacion->field('denominacion', $cond, $order ="cod_coordinacion ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
				echo "</script>";
			break;
			case 'deno_secretaria':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
				$deno_banco = $this->cugd02_secretaria->field('denominacion', $cond, $order ="cod_secretaria ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
				echo "</script>";
			break;
			case 'deno_direccion':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$var;
				$deno_banco = $this->cugd02_direccion->field('denominacion', $cond, $order ="cod_direccion ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
				echo "</script>";
			break;
			case 'deno_division':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$dir=$this->Session->read('direc');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$dir." and cod_division=".$var;
				$deno_banco = $this->cugd02_division->field('denominacion', $cond, $order ="cod_division ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_departamento1').value='';";
				echo "</script>";
			break;
			case 'deno_departamento':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$dir=$this->Session->read('direc');
				$div=$this->Session->read('div');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$dir." and cod_division=".$div." and cod_departamento=".$var;
				$deno_banco = $this->cugd02_departamento->field('denominacion', $cond, $order ="cod_departamento ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
				echo "</script>";
			break;
			case 'deno_oficina':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$dir=$this->Session->read('direc');
				$div=$this->Session->read('div');
				$depar=$this->Session->read('depar');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$dir." and cod_division=".$div." and cod_departamento=".$depar." and cod_oficina=".$var;
				$deno_banco = $this->cugd02_oficina->field('denominacion', $cond, $order ="cod_oficina ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar


function guardar(){
	$this->layout = "ajax";

//	pr($this->data);
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
	$tipo_documento=$this->data['capp01']['tipo_documento'];
	if(empty($this->data['capp01']['cedula_rif']) || empty($this->data['capp01']['tipo_documento']) || empty($this->data['capp01']['cod_dir_sup']) || empty($this->data['capp01']['cod_coordinacion']) || empty($this->data['capp01']['cod_secretaria']) || empty($this->data['capp01']['cod_direccion']) || empty($this->data['capp01']['monto'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else{
		$ano=$this->data['capp01']['ano'];
		$numero_control=$this->data['capp01']['numero_control'];
		$rif_cedula=$this->data['capp01']['cedula_rif'];
		$beneficiario=$this->data['capp01']['beneficiario'];
		$tipo_documento=$this->data['capp01']['tipo_documento'];
		$numero_documento=$this->data['capp01']['codigo'];
		$cod_dir_sup=$this->data['capp01']['cod_dir_sup'];
		$cod_coordinacion=$this->data['capp01']['cod_coordinacion'];
		$cod_secretaria=$this->data['capp01']['cod_secretaria'];
		$cod_direccion=$this->data['capp01']['cod_direccion'];


		$monto=$this->Formato1($this->data['capp01']['monto']);
		$fecha_recepcion=$this->data['capp01']['fecha_recepcion'];
		$fecha_pago=$this->data['capp01']['fecha_pago'];
		$pasos_cumplir=$this->data['capp01']['pasos_cumplir'];
		$observacion=$this->data['capp01']['observacion'];

		//////preguntar por esto
		$procesos_realizados=3;
		$hora=date("h:ia");

		$colar=',';

			$meter=',';
			if(!empty($this->data['capp01']['cod_division'])){
				$cod_division=$this->data['capp01']['cod_division'];
				$colar.='cod_division,';
				$meter.="'$cod_division',";
			}

			if(!empty($this->data['capp01']['cod_departamento'])){
				$cod_departamento=$this->data['capp01']['cod_departamento'];
				$colar.='cod_departamento,';
				$meter.="'$cod_departamento',";
			}

//			$campos="(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,numero_control,cod_tipo_documento,numero_documento,beneficiario,rif_cedula,monto,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion".$colar."observaciones,fecha_recepcion,hora,fecha_probable_pago,pasos_cumplidos,procesos_realizados)";
			$campos="(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,numero_control,cod_tipo_documento,numero_documento,beneficiario,rif_cedula,monto,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion".$colar."observaciones,fecha_recepcion,hora,fecha_probable_pago)";
			$insert="('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$ano','$numero_control','$tipo_documento','$numero_documento','$beneficiario','$rif_cedula','$monto','$cod_dir_sup','$cod_coordinacion','$cod_secretaria','$cod_direccion'".$meter."'$observacion','$fecha_recepcion','$hora','$fecha_pago')";

			$sql_insert = "BEGIN;INSERT INTO capd03_documentos ".$campos." VALUES ".$insert;
			$sw2 = $this->capd03_documentos->execute($sql_insert);
			if($sw2>1){
				$this->capd03_numero->execute("UPDATE  capd03_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano." and situacion=2");
					$this->capd03_documentos->execute("COMMIT");
					$MSJ=array("msj"=>"REGISTRO EXITOSO","tipo_msj"=>"exito");
					$this->Session->write("MSJ",$MSJ);
//			 		$this->set('Message_existe', 'REGISTRO EXITOSO');
			 }else{
			 	$this->capd03_documentos->execute("ROOLBACK");
			 	$MSJ=array("msj"=>"POR FAVOR INTENTE REGISTRAR NUEVAMENTE","tipo_msj"=>"error");
				$this->Session->write("MSJ",$MSJ);
//			 $this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA');/
			 }


$this->data=null;


}

	$this->index();
	$this->render('index');

}// fin guardar



function consulta($pagina=null) {
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $ano=$this->ano_ejecucion();

	if(isset($pagina)){
		$Tfilas=$this->capd03_documentos->findCount($this->SQLCA()." and ano=".$ano);
        if($Tfilas!=0){
        	$x=$this->capd03_documentos->findAll($this->SQLCA()." and ano=".$ano,null,"numero_control ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->capd03_documentos->findCount($this->SQLCA()." and ano=".$ano);

        if($Tfilas!=0){
        	$x=$this->capd03_documentos->findAll($this->SQLCA()." and ano=".$ano,null,"numero_control ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}
	///////esto para el select del tipo de documento
	$lista=$this->capd01_tipo_documento->execute("select distinct b.cod_tipo_documento,
												(select a.denominacion from capd01_tipo_documento a where a.cod_tipo_documento=b.cod_tipo_documento and a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep.") as denominacion from capd02_procesos b
												where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and
												(select c.paso from capd02_procesos c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=".$cod_dep." and c.cod_tipo_documento=b.cod_tipo_documento order by paso desc limit 1)=(select aa.pasos_cumplir from capd01_tipo_documento aa where aa.cod_tipo_documento=b.cod_tipo_documento and aa.cod_presi=".$cod_presi." and aa.cod_entidad=".$cod_entidad." and aa.cod_tipo_inst=".$cod_tipo_inst." and aa.cod_inst=".$cod_inst." and aa.cod_dep=".$cod_dep.")");
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_tipo_documento"];
		$v[]=$l[0]["denominacion"];
//		$v[1]=$l[0]["denominacion"];
$i++;
	}
	$lista = array_combine($r, $v);
 	$this->set('documentos',$lista);

//pr($x);
//////////////////////////////////////////////////

	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." and cod_dir_superior=".$x[0]["capd03_documentos"]["cod_dir_superior"];
	$this->set('dir_sup',$this->cugd02_direccionsuperior->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_coordinacion=".$x[0]["capd03_documentos"]["cod_coordinacion"];
	$this->set('coordinacion',$this->cugd02_coordinacion->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_secretaria=".$x[0]["capd03_documentos"]["cod_secretaria"];
	$this->set('secretaria',$this->cugd02_secretaria->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_direccion=".$x[0]["capd03_documentos"]["cod_direccion"];
	$this->set('direccion',$this->cugd02_direccion->field('denominacion', $conditions =$cond, $order =null));

if($x[0]["capd03_documentos"]["cod_division"]!=''){
	$cond.=" and cod_division=".$x[0]["capd03_documentos"]["cod_division"];
	$this->set('division',$this->cugd02_division->field('denominacion', $conditions =$cond, $order =null));

}else{
	$this->set('division','');
}

if($x[0]["capd03_documentos"]["cod_departamento"]!=''){
	$cond.=" and cod_departamento=".$x[0]["capd03_documentos"]["cod_departamento"];
	$this->set('departamento',$this->cugd02_departamento->field('denominacion', $conditions =$cond, $order =null));

}else{
	$this->set('departamento','');
}

	$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"]);
	$this->set('pasos_cumplir',$deno1[0][0]['pasos_cumplir']);

	$this->set('x',$x);


	$veri1=$this->capd04_flujo->findcount($this->SQLCA()." and ano=".$ano." and numero_control=".$x[0]["capd03_documentos"]["numero_control"]);
	if($veri1==0){
		$this->set('disabled','');
	}else{
		$this->set('disabled','disabled');
	}
 }//consultar



function eliminar($numero_control=null,$pagina=null){
	  $this->layout = "ajax";
//echo "elim: ".$pagina;
			$ano=$this->ano_ejecucion();


		  $x = $this->capd03_documentos->execute("BEGIN;DELETE FROM capd03_documentos  WHERE ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano);
		  if($x>1){
		  	$this->capd03_documentos->execute("COMMIT");
		  	$this->capd03_numero->execute("UPDATE  capd03_numero SET situacion=4 WHERE ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano." and situacion=3");//pendiente en la condicion el año "mosca"
		  	$this->set('Message_existe','registro eliminado con exito');
		  }else{
		  	$this->capd03_documentos->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }
	if($this->capd03_documentos->findCount($this->SQLCA())==0){
		$this->index();
		$this->render('index');
	}else{
		$this->consulta($pagina);
		$this->render('consulta');
	}


}//fin function





 function modificar($numero_control=null,$pagina=null){
 	$this->layout = "ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();


 	$sql2="select * from capd03_documentos where ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano;
	$dato1=$this->capd03_documentos->execute($sql2);

	$lista=$this->capd01_tipo_documento->execute("select distinct b.cod_tipo_documento,
												(select a.denominacion from capd01_tipo_documento a where a.cod_tipo_documento=b.cod_tipo_documento and a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep.") as denominacion from capd02_procesos b
												where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and
												(select c.paso from capd02_procesos c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=".$cod_dep." and c.cod_tipo_documento=b.cod_tipo_documento order by paso desc limit 1)=(select aa.pasos_cumplir from capd01_tipo_documento aa where aa.cod_tipo_documento=b.cod_tipo_documento and aa.cod_presi=".$cod_presi." and aa.cod_entidad=".$cod_entidad." and aa.cod_tipo_inst=".$cod_tipo_inst." and aa.cod_inst=".$cod_inst." and aa.cod_dep=".$cod_dep.")");
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_tipo_documento"];
		$v[]=$l[0]["denominacion"];
	$i++;
	}
	$lista = array_combine($r, $v);
 	$this->set('documentos',$lista);


/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_dir_sup',$dato1[0][0]['cod_dir_superior']);
	$this->Session->write('dir_sup',$dato1[0][0]['cod_dir_superior']);

	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista1=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($lista1, 'dir_sup');

	$cond1="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." and cod_dir_superior=".$dato1[0][0]['cod_dir_superior'];
	$deno_dir_sup = $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond1, $order ="cod_dir_superior ASC");
	$this->set('deno_dir_sup', $deno_dir_sup);

///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_coordinacion',$dato1[0][0]['cod_coordinacion']);
	$this->Session->write('coor',$dato1[0][0]['cod_coordinacion']);

	$cond.=" and cod_dir_superior=".$dato1[0][0]['cod_dir_superior'];
	$lista2=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	$this->concatena($lista2, 'coor');

	$cond1.=" and cod_coordinacion=".$dato1[0][0]['cod_coordinacion'];
	$deno_coor = $this->cugd02_coordinacion->field('denominacion', $cond1, $order ="cod_coordinacion ASC");
	$this->set('deno_coor', $deno_coor);

///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_secretaria',$dato1[0][0]['cod_secretaria']);
	$this->Session->write('secre',$dato1[0][0]['cod_secretaria']);

	$cond.=" and cod_coordinacion=".$dato1[0][0]['cod_coordinacion'];
	$lista3=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	$this->concatena($lista3, 'secre');

	$cond1.=" and cod_secretaria=".$dato1[0][0]['cod_secretaria'];
	$deno_secre = $this->cugd02_secretaria->field('denominacion', $cond1, $order ="cod_secretaria ASC");
	$this->set('deno_secre', $deno_secre);

///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_direccion',$dato1[0][0]['cod_direccion']);
	$this->Session->write('direc',$dato1[0][0]['cod_direccion']);

	$cond.=" and cod_secretaria=".$dato1[0][0]['cod_secretaria'];
	$lista4=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	$this->concatena($lista4, 'direc');

	$cond1.=" and cod_direccion=".$dato1[0][0]['cod_direccion'];
	$deno_direc = $this->cugd02_direccion->field('denominacion', $cond1, $order ="cod_direccion ASC");
	$this->set('deno_direc', $deno_direc);

///////////////////////////////////////////////////////////////////////////////////////////
$cond.=" and cod_direccion=".$dato1[0][0]['cod_direccion'];
$lista5=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
//pr($lista5);
if($lista5!=null){
	$this->concatena($lista5, 'div');
}else{
	$this->set('div',array());
}

if($dato1[0][0]['cod_division']!=''){

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_division',$dato1[0][0]['cod_division']);
	$this->Session->write('div',$dato1[0][0]['cod_division']);

//	$cond.=" and cod_direccion=".$dato1[0][0]['cod_direccion'];
//	$lista5=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
//	$this->concatena($lista5, 'div');

	$cond1.=" and cod_division=".$dato1[0][0]['cod_division'];
	$deno_div = $this->cugd02_division->field('denominacion', $cond1, $order ="cod_division ASC");
	$this->set('deno_div', $deno_div);

///////////////////////////////////////////////////////////////////////////////////////////

$cond.=" and cod_division=".$dato1[0][0]['cod_division'];
$lista6=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
if($lista6!=null){
	$this->concatena($lista6, 'depar');
}else{
	$this->set('depar',array());
}


if($dato1[0][0]['cod_departamento']!=''){
	/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_departamento',$dato1[0][0]['cod_departamento']);
	$this->Session->write('depar',$dato1[0][0]['cod_departamento']);

//	$cond.=" and cod_division=".$dato1[0][0]['cod_division'];
//	$lista6=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
//	$this->concatena($lista6, 'depar');

	$cond1.=" and cod_departamento=".$dato1[0][0]['cod_departamento'];
	$deno_depar = $this->cugd02_departamento->field('denominacion', $cond, $order ="cod_departamento ASC");
	$this->set('deno_depar', $deno_depar);

	///////////////////////////////////////////////////////////////////////////////////////////
	}else{
//		$this->set('depar',array());
		$this->set('cod_departamento','');
		$this->set('deno_depar','');
	}
}else{
//	$this->set('div',array());
	$this->set('cod_division','');
	$this->set('deno_div','');

	$this->set('depar',array());
	$this->set('cod_departamento','');
	$this->set('deno_depar','');
}

	$this->set('x',$dato1);
	$this->set('pagina',$pagina);

	$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$dato1[0][0]["cod_tipo_documento"]);
	$this->set('pasos_cumplir',$deno1[0][0]['pasos_cumplir']);

	$this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');

 }// fin modificar_items




function guardar_modificar($numero_control=null,$pagina=null){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
//pr($this->data);
if(empty($this->data['capp01']['cod_dir_sup']) || empty($this->data['capp01']['cod_coordinacion']) || empty($this->data['capp01']['cod_secretaria']) || empty($this->data['capp01']['cod_direccion'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else{
		$cod_dir_sup=$this->data['capp01']['cod_dir_sup'];
		$cod_coordinacion=$this->data['capp01']['cod_coordinacion'];
		$cod_secretaria=$this->data['capp01']['cod_secretaria'];
		$cod_direccion=$this->data['capp01']['cod_direccion'];
		$observacion=$this->data['capp01']['observacion'];

		$update="set cod_dir_superior='$cod_dir_sup',cod_coordinacion='$cod_coordinacion',cod_secretaria='$cod_secretaria',cod_direccion='$cod_direccion',observaciones='$observacion'";
		$colar=',';

			$meter=',';
			if(!empty($this->data['capp01']['cod_division'])){
				$cod_division=$this->data['capp01']['cod_division'];
				$update.=",cod_division='$cod_division'";
				$colar.='cod_division,';
				$meter.="'$cod_division',";
			}else{
				$update.=",cod_division=null";
			}

			if(!empty($this->data['capp01']['cod_departamento'])){
				$cod_departamento=$this->data['capp01']['cod_departamento'];
				$update.=",cod_departamento='$cod_departamento'";
				$colar.='cod_departamento,';
				$meter.="'$cod_departamento',";
			}else{
				$update.=",cod_departamento=null";
			}

			$sql = "BEGIN;UPDATE capd03_documentos ".$update." where ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano;
			$sw2=$this->capd03_documentos->execute($sql);

			if($sw2>1){
					$this->capd03_documentos->execute("COMMIT");
			 		$this->set('Message_existe', 'los datos fueron modificados con exito');
			 }else{
			 		$this->capd03_documentos->execute("ROOLBACK");
				    $this->set('errorMessage', 'los datos no pudieron ser modificados');
			 }


}

$this->consulta($pagina);
$this->render('consulta');

}//fin guardar_items_modificar



function salir_documento($num_rc=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->capd03_numero->execute("UPDATE  capd03_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_control=".$num_rc." and ano=".$ano." and situacion=2");

	//$this->('index');
}


 }//Fin de la clase controller
 ?>