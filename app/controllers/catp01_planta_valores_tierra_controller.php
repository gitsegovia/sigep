<?php
class Catp01PlantaValoresTierraController extends AppController{
var $uses = array('cscd04_ordencompra_parametros','catd01_ano_ordenanza','cugd90_municipio_defecto','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','ccfd04_cierre_mes','catd01_planta_valores_tierra');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');

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

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
}//fin funcion SQLCA

function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
		 $sql_re = $this->verifica_SS(1).",";
		 $sql_re .= $this->verifica_SS(2).",";
		 $sql_re .=  $this->verifica_SS(3).",";
		 $sql_re .= $this->verifica_SS(4).",";
		 if($ano!=null){
			 $sql_re .= $this->verifica_SS(5).",";
				$sql_re .= $ano."";
		 }else{
			 $sql_re .=  $this->verifica_SS(5)."";
		 }
		 return $sql_re;
}//fin funcion SQLCAIN


function select($select=null,$var=null) { //select de ubicacion administrativa
	$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
		//$cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'estado':
			$this->set('SELECT','municipio');
			$this->set('codigo','estado');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_republica',$var);
			$cond ="cod_republica=".$var;
			$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$this->concatena($lista, 'vector');
			echo "<script>";
			echo "document.getElementById('estado').value='';";
			echo "document.getElementById('estado2').value='';";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";
		break;
		case 'municipio':
			$this->set('SELECT','parroquia');
			$this->set('codigo','municipio');
			$this->set('seleccion','');
			$this->set('n',3);
			//$this->set('no',"no");
			$cod_1 =  $this->Session->read('cod_republica');
			$this->Session->write('cod_estado',$var);
			$cond ="cod_republica=".$cod_1." and cod_estado=".$var;
			$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			$this->concatena($lista, 'vector');
			echo "<script>";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('parroquia').value='';";
			echo "document.getElementById('parroquia2').value='';";
			echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";

		break;
		case 'parroquia':
			$this->set('SELECT','parroquiaa');
			$this->set('codigo','parroquia');
			$this->set('seleccion','');
			$this->set('n',4);
			$this->set('no',"no");
			$this->set('otro',"si");
			$cod_1 =  $this->Session->read('cod_republica');
			$cod_2 =  $this->Session->read('cod_estado');
			$this->Session->write('cod_municipio',$var);
			$cond ="cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$var;
			$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			$this->concatena($lista, 'vector');
			echo "<script>";
			echo "document.getElementById('parroquia').value='';";
			echo "document.getElementById('parroquia2').value='';";
			echo "</script>";
			echo "<script>";
						echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";
		break;

	}
	}else{
		echo "";
		echo "<script>";
			echo "document.getElementById('estado').value='';";
			echo "document.getElementById('estado2').value='';";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('parroquia').value='';";
			echo "document.getElementById('parroquia2').value='';";
			echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";
	}
}//fin select ubicacion administrativa

function deno_codigo($select=null,$opcion=null,$var=null) {
		$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
	//$dirsup =  $this->Session->read('dirsup');
		switch($opcion){
			case 'codigo':
                 switch($select){
					case 'republica':
						$this->set("deno",$this->AddCeroR2($var));
						$this->set("id","republica");
			 		break;
					case 'estado':
						$this->set("deno",$this->AddCeroR2($var));
						$this->set("id","estado");
			 		break;
					case 'municipio':
						$this->set("deno",$this->AddCeroR2($var));
						$this->set("id","municipio");
			 		break;
			 		case 'parroquia':
						$this->set("deno",$this->AddCeroR2($var));
						$this->set("id","parroquia");
			 		break;

				}//fin switch
			break;
			case 'deno':
                 switch($select){
					case 'republica':
						$cond ="cod_republica=".$var;
						$a=  $this->cugd01_republica->findAll($cond);
						$x= $a[0]['cugd01_republica']['denominacion'];
						$this->set("deno",$x);
						$this->set("id","republica2");
			 		break;
					case 'estado':
						$cod_republica= $this->Session->read('cod_republica');
						$cond ="cod_republica=".$cod_republica." and cod_estado=".$var;
						$a=  $this->cugd01_estados->findAll($cond);
						$x= $a[0]['cugd01_estados']['denominacion'];
						$this->set("deno",$x);
						$this->set("id","estado2");
			 		break;
					case 'municipio':
						$cod_republica= $this->Session->read('cod_republica');
						$cod_estado =  $this->Session->read('cod_estado');
						$cond ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$var;
						$a=  $this->cugd01_municipios->findAll($cond);
						$x= $a[0]['cugd01_municipios']['denominacion'];
						$this->set("deno",$x);
						$this->set("id","municipio2");
			 		break;
			 		case 'parroquia':
						$cod_republica= $this->Session->read('cod_republica');
						$cod_estado =  $this->Session->read('cod_estado');
						$cod_municipio =  $this->Session->read('cod_municipio');
						$cond ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$var;
						$a=  $this->cugd01_parroquias->findAll($cond);
						$x= $a[0]['cugd01_parroquias']['denominacion'];
						$this->set("deno",$x);
						$this->set("id","parroquia2");
			 		break;

				}//fin switch
			break;
		}//fin switch

		}else{
			echo "";
			$this->set("deno","");
		}
//$oart=$var<9?CE."0".$var:CE.$var;
}//fin mostrar cod dir superior



function index(){
	$this->layout  = "ajax";
	$ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	$this->set('ano_actual',$ano_actual);
	$this->Session->write('ano_ordenanza',$ano_actual);
	$can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());
	if($can_mun_def!=0){
	    $mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
        $this->set("mun_defecto",$mun_defecto);
        $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
        $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
        $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];
        $this->Session->write('cod_republica',$cod_republica);
        $this->Session->write('cod_estado',$cod_estado);
        $this->Session->write('cod_municipio',$cod_municipio);
	    $sql_re = "cod_republica=".$this->Session->read('SScodpresi')."";
	    $lista=  $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
	    $this->concatena($lista, 'vector');
 	    $denominacion1 =  $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $denominacion = $denominacion1 != null ? $denominacion1 : array();
		$this->concatena($denominacion1, 'estado');
		$denominacion2 =  $this->cugd01_municipios->generateList($sql_re." and cod_estado=".$cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		$denominacion2 = $denominacion2 != null ? $denominacion2 : array();
		$this->concatena($denominacion2, 'municipio');
		$denominacion3 =  $this->cugd01_parroquias->generateList($sql_re." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
		$denominacion3 = $denominacion3 != null ? $denominacion3 : array();
		$this->concatena($denominacion3, 'parroquia');
		$this->set("dvector",$lista);
		$this->set("destado",$denominacion1);
		$this->set("dmunicipio",$denominacion2);
}else{
        $lista=  $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
	    $this->concatena($lista, 'vector');
	    $this->concatena(array(), 'estado');
	    $this->concatena(array(), 'municipio');
	    $this->concatena(array(), 'parroquia');
	    $this->Session->write('cod_republica',0);
        $this->Session->write('cod_estado',0);
        $this->Session->write('cod_municipio',0);
	    $this->set("dvector",array(0=>''));
		$this->set("destado",array(0=>''));
		$this->set("dmunicipio",array(0=>''));
}
	    echo "<script>";
		echo "document.getElementById('plus').disabled='disabled';";
		echo "</script>";
    if(isset($_SESSION["items_zona"])){
    	$this->Session->delete("items_zona");
	    $this->Session->delete("i_zona");
	    $this->Session->delete("contador_zona");
    }
  $modelo_form="catp01_planta_valores_tierra";
  $this->set("modelo_form",$modelo_form);



}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_planta_valores_tierra";
   $modelo="catd01_planta_valores_tierra";
   if(isset($this->data[$modelo_form])){
   	//pr($this->data[$modelo_form]);
      if(!empty($this->data[$modelo_form]["cod_republica"]) && !empty($this->data[$modelo_form]["cod_estado"]) && !empty($this->data[$modelo_form]["cod_municipio"]) && !empty($this->data[$modelo_form]["cod_parroquia"]) && !empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["codigo_zona"]) && !empty($this->data[$modelo_form]["especificacion_zona"]) && !empty($this->data[$modelo_form]["valor"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
           $REP=$this->data[$modelo_form]["cod_republica"];
           $EST=$this->data[$modelo_form]["cod_estado"];
           $MUN=$this->data[$modelo_form]["cod_municipio"];
           $PAR=$this->data[$modelo_form]["cod_parroquia"];
           $cod_zona=$this->data[$modelo_form]["codigo_zona"];
           $esp_zona=$this->data[$modelo_form]["especificacion_zona"];
           $valor=$this->Formato1($this->data[$modelo_form]["valor"]);
           $arrendamiento=$this->Formato1($this->data[$modelo_form]["arrendamiento"]);
           $valor_utm=$this->Formato1($this->data[$modelo_form]["valor_utm"]);
           $valor_ut=$this->Formato1($this->data[$modelo_form]["valor_ut"]);
           $n_variable=$this->Formato1($this->data[$modelo_form]["n_variable"]);
           $valor_plus=$this->Formato1($this->data[$modelo_form]["valor_plus"]);
           $parcela=$this->Formato1($this->data[$modelo_form]["parcela"]);
           $ZONA="(".$this->SQLCAIN().",".$ano_ordenanza.",".$REP.",".$EST.",".$MUN.",".$PAR.",".$cod_zona.",'".$esp_zona."',".$valor.",".$arrendamiento.",".$valor_utm.",".$valor_ut.",".$n_variable.",".$valor_plus.",".$parcela.")";
           if($this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR." and cod_zona=".$cod_zona)==0){
                $RS=$this->$modelo->execute("INSERT INTO ".$modelo." VALUES ".$ZONA);
                if($RS>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
           }else{
           	    $this->set("errorMessage","El Registro Ya existe");
           }
           $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR,null,"cod_zona ASC");
           $this->set("data_zona",$rs);
           $this->set("modelo",$modelo);
           $ale = rand();
           $c = $this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR." and $ale=$ale");
           if($c==0){
           	  $this->set('codigo_zona',1);
           }else{
           	  $rs_ultimo=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR." and $ale=$ale",null,"cod_zona DESC");
           	  $this->set('codigo_zona',$rs_ultimo[0][$modelo]['cod_zona']+1);
           }
      }//fin if empty
   }//if isset


}//fin guardar


function guardar_editar ($ano_ordenanza,$cod_republica,$cod_estado,$cod_municipio,$cod_parroquia,$i,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_planta_valores_tierra";
   $modelo="catd01_planta_valores_tierra";
   $this->set('id_fila',$id_fila);
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["codigo_zona_edt"])  && !empty($this->data[$modelo_form]["especificacion_zona_edt"])  && !empty($this->data[$modelo_form]["valor_edt"])){
            $cod[0]=$this->data[$modelo_form]["codigo_zona_edt"];
			$cod[1]=$this->data[$modelo_form]["especificacion_zona_edt"];
			$cod[2]=$this->Formato1($this->data[$modelo_form]["valor_edt"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["valor_ut_edt"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["n_variable_edt"]);
			$cod[5]=$this->Formato1($this->data[$modelo_form]["valor_plus_edt"]);
			$cod[6]=$this->Formato1($this->data[$modelo_form]["valor_utm_edt"]);
			$cod[7]=$this->Formato1($this->data[$modelo_form]["arrendamiento_edt"]);
			$cod[8]=$this->Formato1($this->data[$modelo_form]["parcela_edt"]);

	        if($this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_zona=".$cod[0])!=0){
	            $rs=$this->$modelo->execute("UPDATE ".$modelo." SET denominacion_zona='".$cod[1]."' , valor_m2=".$cod[2].", valor_ut=".$cod[3].", numero_variable=".$cod[4].", plus=".$cod[5].", valor_ut_m2=".$cod[6].", valor_arrend_m2=".$cod[7].", parcelas=".$cod[8]. " WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_zona=".$cod[0]);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Zona No se encuentra registrado");

	        }//coun
	        $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_zona=".$cod[0],"cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_ordenanza,cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_zona,denominacion_zona,valor_m2,valor_arrend_m2,valor_ut_m2,numero_variable,plus,parcelas,valor_ut",'cod_zona ASC');
	        $this->set("codigos",$rs);
	        $this->set("modelo",$modelo);
	        $this->set("modelo_form",$modelo_form);
      }//fin if empty
   }//if isset
   $this->set("i",$i);

}//fin guardar editar





function mostrar_zona ($cod_parroquia=null) {
   $this->layout="ajax";
   $modelo_form="catp01_planta_valores_tierra";
   $modelo="catd01_planta_valores_tierra";
   if(isset($cod_parroquia)){
           $ano_ordenanza=$this->Session->read('ano_ordenanza');;
           $REP=$this->Session->read('cod_republica');
           $EST=$this->Session->read('cod_estado');
           $MUN=$this->Session->read('cod_municipio');
           $PAR=$cod_parroquia;
           $c = $this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR);
           if($c==0){
           	  $this->set('codigo_zona',1);
           }else{
           	  $rs_ultimo=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR,"cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_ordenanza,cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_zona,denominacion_zona,valor_m2,valor_arrend_m2,valor_ut_m2,numero_variable,plus,parcelas,valor_ut","cod_zona DESC");
           	  $this->set('codigo_zona',$rs_ultimo[0][$modelo]['cod_zona']+1);
           }
           $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR,"cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_ordenanza,cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_zona,denominacion_zona,valor_m2,valor_arrend_m2,valor_ut_m2,numero_variable,plus,parcelas,valor_ut","cod_zona ASC");
           $this->set("data_zona",$rs);
           $this->set("modelo",$modelo);
           $rs=$this->cscd04_ordencompra_parametros->findAll($this->SQLCA(),null,'unidad_tributaria');

           $this->set("unidad_t",$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria']);

   }//if isset
}//fin mostrar_zona

function editar_zona ($ano_ordenanza,$cod_republica,$cod_estado,$cod_municipio,$cod_parroquia,$cod_zona,$i,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_planta_valores_tierra";
   $modelo="catd01_planta_valores_tierra";
   $this->set('id_fila',$id_fila);
   if(isset($ano_ordenanza) && isset($cod_republica) && isset($cod_estado) && isset($cod_municipio) && isset($cod_parroquia) && isset($cod_zona) && isset($i)){
           $ano_ordenanza=$ano_ordenanza;
           $REP=$cod_republica;
           $EST=$cod_estado;
           $MUN=$cod_municipio;
           $PAR=$cod_parroquia;
           $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$REP." and cod_estado=".$EST." and cod_municipio=".$MUN." and cod_parroquia=".$PAR." and cod_zona=".$cod_zona,"cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_ordenanza,cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_zona,denominacion_zona,valor_m2,valor_arrend_m2,valor_ut_m2,numero_variable,plus,parcelas,valor_ut","cod_zona ASC");
           $this->set("cod_zona",$rs[0][$modelo]["cod_zona"]);
           $this->set("especificacion",$rs[0][$modelo]["denominacion_zona"]);
           $this->set("valor",$rs[0][$modelo]["valor_m2"]);
           $this->set("valor_arrend_m2",$rs[0][$modelo]["valor_arrend_m2"]);
           $this->set("valor_ut_m2",$rs[0][$modelo]["valor_ut_m2"]);
           $this->set("valor_ut",$rs[0][$modelo]["valor_ut"]);
           $this->set("numero_variable",$rs[0][$modelo]["numero_variable"]);
           $this->set("plus",$rs[0][$modelo]["plus"]);
           $this->set("parcelas",$rs[0][$modelo]["parcelas"]);
           $this->set("ano_ordenanza",$rs[0][$modelo]["ano_ordenanza"]);
           $this->set("cod_republica",$rs[0][$modelo]["cod_republica"]);
           $this->set("cod_estado",$rs[0][$modelo]["cod_estado"]);
           $this->set("cod_municipio",$rs[0][$modelo]["cod_municipio"]);
           $this->set("cod_parroquia",$rs[0][$modelo]["cod_parroquia"]);
           $this->set("modelo",$modelo);
           $this->set("modelo_form",$modelo_form);
           $this->set("i",$i);
   }//if isset
}//fin mostrar_zona

function escribe_ano_ordenanza ($ano) {
	$this->layout="ajax";
	$this->Session->write('ano_ordenanza',$ano);
}



function eliminar_zona ($ano_ordenanza,$cod_republica,$cod_estado,$cod_municipio,$cod_parroquia,$cod_zona,$i,$id_fila) {
	$this->layout = "ajax";
	$modelo="catd01_planta_valores_tierra";
	$rs=$this->$modelo->execute("DELETE FROM ".$modelo." WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_zona=".$cod_zona);
    if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    }else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
    }
            //$cantidad_reg=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza);
            //$this->set("cantidad_reg",$cantidad_reg);

}//fin eliminar_zona

function cancelar_editar ($ano_ordenanza,$cod_republica,$cod_estado,$cod_municipio,$cod_parroquia,$cod_zona,$i,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_planta_valores_tierra";
   $modelo="catd01_planta_valores_tierra";
   $this->set('id_fila',$id_fila);
   if(isset($ano_ordenanza) && isset($cod_republica) && isset($cod_estado) && isset($cod_municipio) && isset($cod_parroquia) && isset($cod_zona)){
            $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_zona=".$cod_zona,"cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_ordenanza,cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_zona,denominacion_zona,valor_m2,valor_arrend_m2,valor_ut_m2,numero_variable,plus,parcelas,valor_ut",'cod_zona ASC');
	        $this->set("codigos",$rs);
	        $this->set("modelo",$modelo);
   }//if isset
   $this->set("i",$i);

}//fin cancelar







function actualizar_ut(){
	$modelo="catd01_planta_valores_tierra";

    $rs=$this->cscd04_ordencompra_parametros->findAll($this->SQLCA(),null,'unidad_tributaria');

    $datos=$this->$modelo->execute("UPDATE ".$modelo." SET valor_m2= valor_ut_m2 * ".$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria'].", valor_ut=".$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria'].", valor_arrend_m2=valor_ut_m2*".$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria']."*0.5 WHERE ".$this->SQLCA());

    $this->set("Message_existe","La unidad tributaria fue actualizada Exitosamente");
    $this->index();
    $this->render('index');
}

function calculo_d(){
	$this->layout="ajax";

}

}//fin class
?>
