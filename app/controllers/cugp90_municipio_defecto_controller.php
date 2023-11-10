<?php
class Cugp90MunicipioDefectoController extends AppController{
    var $uses = array('cugd90_municipio_defecto','cugd01_republica','cugd01_estados','cugd01_municipios','ccfd04_cierre_mes');
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

}//fin before filter

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
			$lista=  $this->cugd01_estados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$this->concatena($lista, 'vector');
			echo "<script>";
			echo "document.getElementById('estado').value='';";
			echo "document.getElementById('estado2').value='';";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "document.getElementById('guardar').disabled='disabled';";
			echo "</script>";
		break;
		case 'municipio':
			$this->set('SELECT','municipioii');
			$this->set('codigo','municipio');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->set('no',"no");
			$cod_1 =  $this->Session->read('cod_republica');
			$this->Session->write('cod_estado',$var);
			$cond ="cod_republica=".$cod_1." and cod_estado=".$var;
			$lista=  $this->cugd01_municipios->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			$this->concatena($lista, 'vector');
			echo "<script>";
			echo "document.getElementById('municipio').value='';";
			echo "document.getElementById('municipio2').value='';";
			echo "</script>";
			echo "<script>";
						echo "document.getElementById('guardar').disabled='disabled';";
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
			echo "document.getElementById('guardar').disabled='disabled';";
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
						echo "<script>";
						echo "document.getElementById('guardar').disabled='';";
						echo "</script>";
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
	$can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());
	if($can_mun_def!=0){
		$mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
	    $this->set("mun_defecto",$mun_defecto);
	    $this->set("can_mun_def",$can_mun_def);
	    $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
	    $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
	    $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];
	    $this->Session->write('cod_republica',$cod_republica);
	    $this->Session->write('cod_estado',$cod_estado);
	    $this->Session->write('cod_municipio',$cod_municipio);

		$lista_r=  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
		$lista_e=  $this->cugd01_estados->generateList("cod_republica=".$cod_republica, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		$lista_m=  $this->cugd01_municipios->generateList("cod_republica=".$cod_republica." and cod_estado=".$cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');

		$this->concatena($lista_r, 'vector_r');
		$this->concatena($lista_e, 'vector_e');
		$this->concatena($lista_m, 'vector_m');

		$deno_r=  $this->cugd01_republica->findAll("cod_republica=".$cod_republica);
		$deno_e=  $this->cugd01_estados->findAll("cod_republica=".$cod_republica." and cod_estado=".$cod_estado);
		$deno_m=  $this->cugd01_municipios->findAll("cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio);
		$this->set('deno_r',$deno_r[0]["cugd01_republica"]["denominacion"]);
		$this->set('deno_e',$deno_e[0]["cugd01_estados"]["denominacion"]);
		$this->set('deno_m',$deno_m[0]["cugd01_municipios"]["denominacion"]);
	}else{
		$lista_r=  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
		$this->concatena($lista_r, 'vector_r');
		$this->concatena(array(), 'vector_e');
		$this->concatena(array(), 'vector_m');
		$this->set("can_mun_def",$can_mun_def);
	}


}//index

function guardar () {
   $this->layout="ajax";
   if(isset($this->data["cugp90_municipio_defecto"])){
      if(!empty($this->data["cugp90_municipio_defecto"]["cod_republica"]) && !empty($this->data["cugp90_municipio_defecto"]["cod_estado"]) && !empty($this->data["cugp90_municipio_defecto"]["cod_municipio"])){
           $REP=$this->data["cugp90_municipio_defecto"]["cod_republica"];
           $EST=$this->data["cugp90_municipio_defecto"]["cod_estado"];
           $MUN=$this->data["cugp90_municipio_defecto"]["cod_municipio"];
           if($this->cugd90_municipio_defecto->findCount($this->SQLCA_S())==0){
                $RS=$this->cugd90_municipio_defecto->execute("INSERT INTO cugd90_municipio_defecto VALUES(".$this->SQLCAIN().",$REP,$EST,$MUN)");
                if($RS>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
           }else{
           	    $this->set("errorMessage","El Registro Ya existe");
           }
           $mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
           $this->set("mun_defecto",$mun_defecto);
      }//fin if empty
   }//if isset

}//fin guardar



function guardar_modificar () {
   $this->layout="ajax";
   if(isset($this->data["cugp90_municipio_defecto"])){
      if(!empty($this->data["cugp90_municipio_defecto"]["cod_republica"]) && !empty($this->data["cugp90_municipio_defecto"]["cod_estado"]) && !empty($this->data["cugp90_municipio_defecto"]["cod_municipio"])){
           $REP=$this->data["cugp90_municipio_defecto"]["cod_republica"];
           $EST=$this->data["cugp90_municipio_defecto"]["cod_estado"];
           $MUN=$this->data["cugp90_municipio_defecto"]["cod_municipio"];
           if($this->cugd90_municipio_defecto->findCount($this->SQLCA_S())!=0){
                $RS=$this->cugd90_municipio_defecto->execute("update cugd90_municipio_defecto SET cod_republica=".$REP." , cod_estado=".$EST." , cod_municipio=".$MUN." where ".$this->SQLCA_S());
                if($RS>1){
           	        $this->set("Message_existe","Los Datos Fueron Actualizados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Actualizados");
                }
           }else{
           	    $this->set("errorMessage","El Registro Ya existe");
           }

      }//fin if empty
   }//if isset

}//fin guardar
















}//fin class
?>
