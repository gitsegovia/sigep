<?php

// Import the app/vendor/xmlrpc.php library
Vendor('xmlrpc');
class XmlRpcController extends AppController {
   var $name = 'XmlRpc';
   var $uses = array("arrd01", "ccfd03_instalacion", "cscd01_catalogo", "cscd01_snc_tipo",'v_historia_solicitud_ayudas','casd01_datos_familiares','casd01_datos_personales','cnmd06_profesiones','cnmd06_oficio','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd02_institucion','cugd02_dependencia','cnmd06_parentesco');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');
   var $server = null;// The XML-RPC server object

   function index () {
		$this->autoRender = false;
		// XML-RPC callbacks settings
		// Use this parameter to map XML-RPC methods to your protected or private controller methods
		$callbacks = array();
		$callbacks['sisap.relacion_obras_proyectadas'] = array(&$this, '_relacion_obras_proyectadas');
		$callbacks['sisap.solicitudes_ayudas'] = array(&$this, '_solicitudes_ayudas');
		$callbacks['sisap.obras_particular'] = array(&$this, '_obras_particular');
		$callbacks['sisap.relacion_obras_proyectadas_segun_snc'] = array(&$this, '_relacion_obras_proyectadas_segun_snc');
		// Handle XML-RPC request
		$this->server = new IXR_Server($callbacks);
}//fin funcion index

function _relacion_obras_proyectadas($var1=null,$ano=null,$clave_acceso = null){
      set_time_limit(0);
      $logo = '1_11_30_11';
      $clave_acceso_local = md5($logo);
      $this->set('logo',$logo);
	  if($var1==1 && isset($ano) && isset($clave_acceso) && $clave_acceso_local == $clave_acceso){
	  	$this->layout = "xmlportal";
  	    $datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ORDER BY ano_estimacion DESC");
  	    //$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$cod[]  = $n[0]['ano_estimacion'];
				$deno[] = $n[0]['ano_estimacion'];
			}
			$lista=array_combine($cod, $deno);
		}else{
			$lista=array();
		}
		//$this->set("ano_estimacion", $lista);
        return $lista;

	}else if($var1==2 && isset($ano) && isset($clave_acceso) && $clave_acceso_local == $clave_acceso){
		$ano_consolidado = $ano;
		$sql = "";
		$cod_presi = 1;
		$cod_entidad = 11;
		$cod_tipo_inst = 30;
		$cod_inst = 11;
        $deno_inst = $this->arrd01->execute(" SELECT denominacion    FROM cugd02_institucion WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst");
        $this->set('nombre_institucion',$deno_inst[0][0]['denominacion']);
        $sql .= " cod_presi=$cod_presi and cod_entidad = $cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst";
	    if($ano!='all'){
	     	$sql .=" and ano_estimacion=$ano";
	    }
		$ordenar = " ORDER BY ano_estimacion,cod_presi,cod_entidad,cod_tipo_inst,cod_inst,codigo_prod_serv,cod_obra ";
		$datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);
		$this->set("datos", $datos);
        $this->set('acceso_autorizado',true);

	}else{
		$this->set('nombre_institucion','');
		if(isset($clave_acceso) && $clave_acceso_local == $clave_acceso){
             $this->set('acceso_autorizado',true);
		}else{
			$this->set('acceso_autorizado',false);
		}
	}
    $this->set("vista", $var1);
}//fin fucntion relacion_obras_proyectadas

function _obras_particular ($ano = null) {
   $this->layout="xmlportal";
   if(!$ano){
      $sql= " ";
   }else{
   	  if($ano!='todo'){
          $sql= "and a.ano_estimacion='".$ano."'";
   	  }else{
   	  	$sql= " ";
   	  }

   }

   $lista  = $this->arrd01->execute(" SELECT
	    	                                   a.codigo_prod_serv,
	    	                                  (SELECT dd.denominacion FROM cscd01_catalogo dd WHERE dd.codigo_prod_serv=a.codigo_prod_serv ) as deno_codigo_prod_serv,
										      (SELECT ddd.denominacion FROM cscd01_snc_tipo ddd WHERE ddd.cod_tipo=(SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv)) as deno_cod_snc,
										      (SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv) as cod_snc
	    			                          FROM cfpd07_obras_cuerpo a WHERE (a.codigo_prod_serv IS NOT NULL and  a.codigo_prod_serv!=0) ".$sql." GROUP BY a.codigo_prod_serv ORDER BY deno_cod_snc;
	    			                          ");

   if(count($lista)!=0){
		foreach($lista as $n){
			if($n[0]['cod_snc']!=""){
				$cod[]  = $n[0]['cod_snc'];
				$deno[] = $n[0]['cod_snc']." - ".$n[0]['deno_cod_snc'];
			}
		}
		$lista=array_combine($cod, $deno);
	}else{
		$lista=array('0'=>'No se encuentra presente ningun cod_snc');
	}
	$this->set('vector', $lista);
    return $lista;
}//fin funcion obras_particular

function _relacion_obras_proyectadas_segun_snc($var1=null, $var2=null, $var3=null, $clave_acceso=null){
      set_time_limit(0);
	  $logo = '1_11_30_11';
      $clave_acceso_local = md5($logo);
      $this->set('logo',$logo);
	  if($var1==1){
	  	//$this->layout = "xmlportal";
      	$datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ORDER BY ano_estimacion DESC");
      	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
			if(count($datos)!=0){
				foreach($datos as $n){
					$cod[]  = $n[0]['ano_estimacion'];
					$deno[] = $n[0]['ano_estimacion'];
				}
				$lista=array_combine($cod, $deno);
			}else{
				$lista=array();
			}
		$this->set("ano_estimacion", $lista);
		$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
        return $lista;
	    }else if($var1==4 && isset($clave_acceso) && $clave_acceso_local == $clave_acceso){ $this->layout = "pdf";
			$ano_consolidado = $var2;
			$sql = "";
			$cod_presi = 1;
			$cod_entidad = 11;
			$cod_tipo_inst = 30;
			$cod_inst = 11;
            $deno_inst = $this->arrd01->execute(" SELECT denominacion    FROM cugd02_institucion WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst");
            $this->set('nombre_institucion',$deno_inst[0][0]['denominacion']);
	        $sql .= " cod_presi=$cod_presi and cod_entidad = $cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst";
	         if($var2!='todo'){
	         	$sql .=" and ano_estimacion=$var2";
	         }

	         if($var3!='todo'){
	         	$sql .=" and cod_snc = '".$var3."' ";
	         }
		     $ordenar = " ORDER BY ano_estimacion,cod_snc,cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_dep_original,cod_obra ";
		     $datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);
		     $this->set("datos", $datos);
             $this->set('acceso_autorizado',true);
		}else{
			$this->set('nombre_institucion','');
			if(isset($clave_acceso) && $clave_acceso_local == $clave_acceso){
                 $this->set('acceso_autorizado',true);
			}else{
				$this->set('acceso_autorizado',false);
			}
		}
        $this->set("vista", $var1);
}//fin fucntion relacion_obras_proyectadas_segun_snc


function _solicitudes_ayudas ($cedula=null,$clave_acceso =null) {
   $this->layout="xmlportal";
   $logo = '1_11_30_11';
   $clave_acceso_local = md5($logo);
   if($cedula && $clave_acceso && $clave_acceso==$clave_acceso_local){
   	$sql="SELECT * from casd01_datos_personales where cedula_identidad::text='".$cedula."'";
	$result=$this->casd01_datos_personales->execute($sql);
	if(count($result)>0){
        $sql_1="SELECT * from casd01_datos_familiares where cedula_identidad::text='".$cedula."' order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$todos_array['perso']=$result;
		$todos_array['fami']=$result_1;
        $todos_array['profesion']=  $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC");
		$todos_array['oficio']=     $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_oficio'], $order ="cod_oficio ASC");
		$todos_array['estado']=     $this->cugd01_estados->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado'], $order ="cod_estado ASC");
		$todos_array['municipio']=  $this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio'], $order ="cod_municipio ASC");
		$todos_array['parroquia']=  $this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia'], $order ="cod_parroquia ASC");
		$todos_array['centro']=     $this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=1 and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia']." and cod_centro=".$result[0][0]['cod_centro_poblado'], $order ="cod_centro ASC");
		$todos_array['institucion']=$this->cugd02_institucion->field('denominacion', $conditions ="cod_tipo_institucion=1 and cod_institucion=".$result[0][0]['cod_inst'], $order ="cod_institucion ASC");
		$todos_array['dependencia']=$this->cugd02_dependencia->field('denominacion', $conditions ="cod_tipo_institucion=1 and cod_institucion=".$result[0][0]['cod_inst']." and cod_dependencia=".$result[0][0]['cod_dep'], $order ="cod_institucion ASC");
		$todos_array['paren']=      $this->cnmd06_parentesco->findAll();
		$sql2="select * from v_historia_solicitud_ayudas where cedula_identidad::text='".$cedula."' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,numero_ocacion asc";
		$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
		if($dato2!=null){
			$todos_array['dato2']=$dato2;
		}else{
			$todos_array['msj']= array('usted no posee un historial de solicitudes y ayudas','error');
			$todos_array['dato2']=null;
		}
		$todos_array['status_arrays'] = array('valor'=>true,'msj'=>'solicitudes y ayudas');
		return $todos_array;
	}else{
		return array('status_arrays'=>array('valor'=>false,'msj'=>'usted no posee un historial de solicitudes y ayudas'));
	}
   }
}//fin funcion solicitudes_ayudas

}// fin class

?>