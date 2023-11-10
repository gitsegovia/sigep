<?php
class Catp01ComplementoVariablePrincipalController extends AppController{
var $uses = array('catd01_valor_construccion','ccfd04_cierre_mes','catd01_complemento_variable_principal','catd01_ano_ordenanza');
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
			/*echo "<script>";
			echo "document.getElementById('estado').value='';";
			echo "document.getElementById('estado2').value='';";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";*/
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
			/*echo "<script>";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('parroquia').value='';";
			echo "document.getElementById('parroquia2').value='';";
			echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";*/

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
			/*echo "<script>";
			echo "document.getElementById('parroquia').value='';";
			echo "document.getElementById('parroquia2').value='';";
			echo "</script>";
			echo "<script>";
						echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";*/
		break;

	}
	}else{
		/*echo "";
		echo "<script>";
			echo "document.getElementById('estado').value='';";
			echo "document.getElementById('estado2').value='';";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('parroquia').value='';";
			echo "document.getElementById('parroquia2').value='';";
			echo "document.getElementById('plus').disabled='disabled';";
			echo "</script>";*/
	}
}//fin select ubicacion administrativa

function deno_codigo($select=null,$opcion=null,$var=null) {
		$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
	//$dirsup =  $this->Session->read('dirsup');
	    $ano_ordenanza=$this->Session->read('ano_ordenanza');
		switch($opcion){
			case 'codigo':
                 switch($select){
					case 'valor_construccion':
						$this->set("deno",$var);
						$this->set("id","valor_construccion");
						$this->set("id2","valor_construccion");
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
					case 'valor_construccion':
						$cond =$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$var."'";
						$a=  $this->catd01_valor_construccion->findAll($cond);
						$x= $a[0]['catd01_valor_construccion']['denominacion_tipo'];
						$this->set("deno",$x);
						$this->set("id","valor_construccion2");
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
    $modelo_form="catp01_complemento_variable_principal";
    $this->set("modelo_form",$modelo_form);
    $this->Session->write('ano_ordenanza',$ano_actual);
    $lista =  $this->catd01_valor_construccion->generateList($this->SQLCA()." and ano_ordenanza=".$ano_actual, 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
	$this->concatena_sin_cero($lista, 'vector');


}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_complemento_variable_principal";
   $modelo="catd01_complemento_variable_principal";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["cod_tipo"]) && !empty($this->data[$modelo_form]["cod_variable_principal"]) && !empty($this->data[$modelo_form]["denominacion_principal"]) && !empty($this->data[$modelo_form]["ano_ordenanza"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
           $cod_tipo=$this->data[$modelo_form]["cod_tipo"];
           $cod_variable_principal=$this->data[$modelo_form]["cod_variable_principal"];
           $denominacion=$this->data[$modelo_form]["denominacion_principal"];
           $VP="(".$this->SQLCAIN().",".$ano_ordenanza.",'".$cod_tipo."',".$cod_variable_principal.",'".$denominacion."')";


           if($this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod_variable_principal)==0){
                $RS=$this->$modelo->execute("INSERT INTO ".$modelo." VALUES ".$VP);
                if($RS>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
                }
           }else{
           	    $this->set("errorMessage","El Registro Ya existe");
           }
           $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."'",null,"cod_variable_principal ASC");
           $this->set("data_zona",$rs);
           $this->set("modelo",$modelo);
           $this->set("modelo_form",$modelo_form);
           $ale = rand();
           $c = $rs=$this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."'  and $ale=$ale",null,"cod_variable_principal ASC");
           $rs_n=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and $ale=$ale",null,"cod_variable_principal DESC",1);
		   if($c!=0){
		   	  $this->set('ultimo',$rs_n[0][$modelo]['cod_variable_principal']+1);
		   }else{
		   	  $this->set('ultimo',1);
		   }
      }//fin if empty
   }//if isset


}//fin guardar


function guardar_editar ($ano_ordenanza,$cod_tipo,$cod_variable_principal,$i) {
   $this->layout="ajax";
   $modelo_form="catp01_complemento_variable_principal";
   $modelo="catd01_complemento_variable_principal";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["cod_variable_principal_edt"])  && !empty($this->data[$modelo_form]["denominacion_principal_edt"])){
            $cod[0]=$this->data[$modelo_form]["cod_variable_principal_edt"];
			$cod[1]=$this->data[$modelo_form]["denominacion_principal_edt"];

	        if($this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod[0])!=0){
	            $rs=$this->$modelo->execute("UPDATE ".$modelo." SET denominacion_principal='".$cod[1]."' WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod[0]);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","No se encuentr&oacute; registro");

	        }//coun
	        $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod[0],null,'cod_variable_principal ASC');
	        $this->set("codigos",$rs);
	        $this->set("modelo",$modelo);
	        $this->set("modelo_form",$modelo_form);
      }//fin if empty
   }//if isset
   $this->set("i",$i);

}//fin guardar editar





function mostrar_variables ($cod_tipo=null) {
   $this->layout="ajax";
   $modelo_form="catp01_complemento_variable_principal";
   $modelo="catd01_complemento_variable_principal";
   if(isset($cod_tipo)){
           $ano_ordenanza=$this->Session->read('ano_ordenanza');
           $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."'",null,"cod_variable_principal ASC");
           $this->set("data_zona",$rs);
           $this->set("modelo",$modelo);
           $this->set("modelo_form",$modelo_form);

           $c = $rs=$this->$modelo->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."'",null,"cod_variable_principal ASC");
           $rs_n=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."'",null,"cod_variable_principal DESC",1);
		   if($c!=0){
		   	  $this->set('ultimo',$rs_n[0][$modelo]['cod_variable_principal']+1);
		   }else{
		   	  $this->set('ultimo',1);
		   }


		   $cond =$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."'";
		   $a=  $this->catd01_valor_construccion->findAll($cond);
		   $x= $a[0]['catd01_valor_construccion']['denominacion_tipo'];
		   $this->set("deno_tipo",$x);
		   $this->set("cod_tipo",$cod_tipo);
   }//if isset



}//fin mostrar_zona

function editar ($ano_ordenanza,$cod_tipo,$cod_variable_principal,$i) {
   $this->layout="ajax";
   $modelo_form="catp01_complemento_variable_principal";
   $modelo="catd01_complemento_variable_principal";
   if(isset($ano_ordenanza) && isset($cod_tipo) && isset($cod_variable_principal) && isset($i)){
           $ano_ordenanza=$ano_ordenanza;

           $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod_variable_principal,null,"cod_variable_principal ASC");
           $this->set("cod_tipo",$cod_tipo);
           $this->set("cod_variable_principal",$rs[0][$modelo]["cod_variable_principal"]);
           $this->set("denominacion_principal",$rs[0][$modelo]["denominacion_principal"]);
           $this->set("ano_ordenanza",$rs[0][$modelo]["ano_ordenanza"]);
           $this->set("modelo",$modelo);
           $this->set("modelo_form",$modelo_form);
           $this->set("i",$i);
   }//if isset
}//fin mostrar_zona

function escribe_ano_ordenanza ($ano) {
	$this->layout="ajax";
	if(isset($ano) && !empty($ano)){
		$ano=$ano;
	}else{
		$ano=date("Y");
	}
	$this->Session->write('ano_ordenanza',$ano);
	$lista =  $this->catd01_valor_construccion->generateList($this->SQLCA()." and ano_ordenanza=".$ano, 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
	$this->concatena_sin_cero($lista, 'vector');
	$modelo_form="catp01_complemento_variable_principal";
    $this->set("modelo_form",$modelo_form);
}



function eliminar($ano_ordenanza,$cod_tipo,$cod_variable_principal,$i) {
	$this->layout = "ajax";
	$modelo_form="catp01_complemento_variable_principal";
    $modelo="catd01_complemento_variable_principal";
	$rs=$this->$modelo->execute("DELETE FROM ".$modelo." WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod_variable_principal);
    if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    }else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
    }
            //$cantidad_reg=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza);
            //$this->set("cantidad_reg",$cantidad_reg);

}//fin eliminar_zona

function cancelar_editar ($ano_ordenanza,$cod_tipo,$cod_variable_principal,$i) {
   $this->layout="ajax";
    $modelo_form="catp01_complemento_variable_principal";
    $modelo="catd01_complemento_variable_principal";
   if(isset($ano_ordenanza) && isset($cod_tipo) && isset($cod_variable_principal)){
            $rs=$this->$modelo->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo='".$cod_tipo."' and cod_variable_principal=".$cod_variable_principal,null,'cod_variable_principal ASC');
	        $this->set("codigos",$rs);
	        $this->set("modelo",$modelo);
	        $this->set("modelo_form",$modelo_form);
   }//if isset
   $this->set("i",$i);

}//fin cancelar
}//fin class
?>
