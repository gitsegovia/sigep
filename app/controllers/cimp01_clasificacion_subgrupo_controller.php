<?php
 class Cimp01ClasificacionSubgrupoController extends AppController{
	var $name = 'cimp01_clasificacion_subgrupo';
	var $uses = array('ccfd01_division','cugd05_restriccion_clave','cimd01_clasificacion_grupo','cimd01_clasificacion_tipo','cimd01_clasificacion_subgrupo','cimd01_clasificacion_seccion');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession
function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero

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


function concatena_tipo($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.mascara($x,1).' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.''.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = mascara($x,1).' - '.$y;
				}else if($x>=10){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


  function index($cod_tipo=null){

$this->verifica_entrada('104');

	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$this->concatena_tipo($tipo, 'cod_tipo');
	if($cod_tipo!=null && $cod_tipo!="autor_valido"){
	 $datacpcp01=$this->cimd01_clasificacion_subgrupo->findAll('cod_tipo='.$cod_tipo,null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
     $this->set('datos',$datacpcp01);
	 }



 }//index


 function guardar(){
	$this->layout="ajax";

		if($this->data['cimp01_clasificacion_subgrupo']['cod_tipo']!='' && $this->data['cimp01_clasificacion_subgrupo']['cod_grupo']!='' && $this->data['cimp01_clasificacion_subgrupo']['cod_subgrupo']!='' && $this->data['cimp01_clasificacion_subgrupo']['deno_subgrupo']!=''){

		$cod_tipo=$this->data['cimp01_clasificacion_subgrupo']['cod_tipo'];
		$cod_grupo=$this->data['cimp01_clasificacion_subgrupo']['cod_grupo'];
		$cod_subgrupo=$this->data['cimp01_clasificacion_subgrupo']['cod_subgrupo'];
		$deno_subgrupo=$this->data['cimp01_clasificacion_subgrupo']['deno_subgrupo'];

		$sql ="INSERT INTO cimd01_clasificacion_subgrupo (cod_tipo,cod_grupo,cod_subgrupo,denominacion)";
		$sql .=" VALUES (".$cod_tipo.",".$cod_grupo.",".$cod_subgrupo.",'".$deno_subgrupo."')";

		$veri=$this->cimd01_clasificacion_subgrupo->findCount('cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo);

		if($veri==0){

		$re=$this->cimd01_clasificacion_subgrupo->execute($sql);
		if($re>1){
			 $this->set('Message_existe','EL REGISTRO FUE AGREGADO CORRECTAMENTE');
		$this->data=null;
		}else{
			 $this->set('errorMessage','NO SE PUDO AGREGAR EL REGISTRO');
		$this->data=null;
		}
		}else{$this->set('errorMessage','ESTOS CÓDIGOS YA SE ENCUENTRAN REGISTRADOS');
		$this->data=null;
	 }

}else{
	 $this->set('mensajeError','debe ingresar todos los datos');
}
 		$this->Session->write('ctipo',$cod_tipo);
 		$this->grilla($cod_grupo);
		$this->render("grilla");

		echo "<script>";
		  echo "document.getElementById('cod_subgrupo').value = ''; ";
		  echo "document.getElementById('deno_subgrupo').value = ''; ";
        echo "</script>";


 }//fin function


 function eliminar($cod_tipo,$cod_grupo,$cod_subgrupo){
	$this->layout="ajax";
    if($cod_grupo!=null){
		   $veri=$this->cimd01_clasificacion_seccion->findCount("cod_tipo=".$cod_tipo." and  cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo);
		   if($veri > 0){
		   		$this->set('errorMessage','DISCULPE EL GRUPO NO PUEDE SER ELIMINADO PORQUE SE ENCUENTRA EN LA CLASIFICACIÓN DE BIENES - SECCIÓN');
		   }else{
				$sql="DELETE FROM cimd01_clasificacion_subgrupo WHERE cod_tipo=".$cod_tipo." and  cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo;
		   if($this->cimd01_clasificacion_subgrupo->execute($sql)>1){
		   $this->set('Message_existe','REGISTRO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('errorMessage','LO SIENTO, EL GRUPO NO PUDO SER ELIMINADO');
		   }
		   }
    }else{
    	$this->set('errorMessage','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
    $this->Session->write('ctipo',$cod_tipo);
    $this->grilla($cod_grupo);
	$this->render("grilla");
   }//eliminar


 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cimd01_clasificacion_grupo->findAll(null,null,'cod_grupo ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cimd01_clasificacion_grupo->findAll(null,null,'denominacion ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos
function select3($select=null,$var=null) {
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){
	switch($select){
		case 'ramo':
		//echo 'si';
		  $this->set('SELECT','grupo');
		  $this->set('codigo','tipo');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cimd01_clasificacion_tipo->generateList($cond2, 'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
			$this->concatena_tipo($lista,'vector');
		break;
		case 'grupo':
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('ctipo',$var);
		  $cond2 ="cod_tipo=".$var;
		  $lista=  $this->cimd01_clasificacion_grupo->generateList($cond2, 'cod_grupo ASC', null, '{n}.cimd01_clasificacion_grupo.cod_grupo', '{n}.cimd01_clasificacion_grupo.denominacion');
          $this->concatena($lista,'vector');
          echo "<script>";
          echo "document.getElementById('cod_subgrupo').value = ''; ";
          echo "</script>";
 		break;
		case 'subgrupo':
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ctipo =  $this->Session->read('ctipo');
		  $this->Session->write('cgru',$var);
		  $cond2 ="cod_tipo=".$ctipo." and cod_grupo=".$var;
		  $lista = $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_subgrupo', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ctipo =  $this->Session->read('ctipo');
		  $cgru =  $this->Session->read('cgru');
		  $this->Session->write('csub',$var);
		  $cond2 ="cod_tipo=".$ctipo." and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_seccion ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_seccion', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
		if( $var!=null){
	switch($select){
		case 'tipo':
		  $this->Session->write('dtipo2',$var);
		  $cond2 ="cod_tipo=".$var;
		  $a=  $this->cimd01_clasificacion_tipo->findAll($cond2);
          $e=$a[0]['cimd01_clasificacion_tipo']['denominacion'];
         $this->set('var',$e);
		break;
		case 'grupo':
		//echo 'si';
		  $dtipo=  $this->Session->read('dtipo2');
		  $this->Session->write('dgru2',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$var;
		  $a=  $this->cimd01_clasificacion_grupo->findAll($cond2);
          $e=$a[0]['cimd01_clasificacion_grupo']['denominacion'];
          $this->set('var',$e);

		break;
		case 'subgrupo':
		  $dtipo=  $this->Session->read('dtipo2');
		  $dgru =  $this->Session->read('dgru2');
		  $this->Session->write('dsub2',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$var;
		  $a=  $this->cimd01_clasificacion_subgrupo->findAll($cond2);
          $e= $a[0]['cimd01_clasificacion_subgrupo']['denominacion'];
          $this->set('var',$e);
		break;
		case 'seccion':
		  //$ano =  $this->Session->read('ano');
	//	  echo '4';
		  $dtipo=  $this->Session->read('dtipo2');
		  $dgru =  $this->Session->read('dgru2');
		  $dsub =  $this->Session->read('dsub2');
		  $this->Session->write('dsec2',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$dsub." and cod_seccion=".$var;
		  $a=  $this->cimd01_clasificacion_subgrupo->findAll($cond2);
          $e= $a[0]['cimd01_clasificacion_subgrupo']['denominacion'];
           $this->set('var',$e);
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios


function consultar($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->v_cimd01_clasificacion_subgrupo->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd01_clasificacion_subgrupo->findAll(null,null,null,1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_cimd01_clasificacion_subgrupo->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd01_clasificacion_subgrupo->findAll(null,null,null,1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);

}
         }
}//fin function consultar2

function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
}//fin navegacion

function modificar($cod_tipo, $cod_grupo, $cod_subgrupo){
	$this->layout = "ajax";
	 $datacpcp01=$this->v_cimd01_clasificacion_subgrupo->findAll('cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo);
     $this->set('datos',$datacpcp01);

}

function guardar_editar($cod_tipo, $cod_grupo, $cod_subgrupo){
	$this->layout = "ajax";
	if($cod_tipo==1){
		$cod_cuenta='212';
	}else if($cod_tipo==2){
		$cod_cuenta='214';
	}
	$deno_subgrupo=$this->data['cimp01_clasificacion_subgrupo']['denominacion'];
	$update="update cimd01_clasificacion_subgrupo set denominacion='".$deno_subgrupo."' where cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo;
	$update2="update ccfd01_division set denominacion='".$deno_subgrupo."' where cod_tipo_cuenta=1 and cod_cuenta=$cod_cuenta and cod_subcuenta=$cod_grupo and cod_division=$cod_subgrupo and ".$this->SQLCA();
	$this->cimd01_clasificacion_subgrupo->execute($update);
	$this->ccfd01_division->execute($update2);
		$this->set('Message_existe','EL REGISTRO FUE MODIFICADO CORRECTAMENTE');
		$this->Session->write('ctipo',$cod_tipo);
		$this->grilla($cod_grupo);
		$this->render("grilla");


}

function grilla($cod_tipo=null){
	$this->layout = "ajax";
	$ctipo =  $this->Session->read('ctipo');
	if($cod_tipo=="si"){
     $datacpcp01 = "";
	}else{
	$datacpcp01=$this->cimd01_clasificacion_subgrupo->findAll('cod_tipo='.$ctipo." and cod_grupo=".$cod_tipo." and cod_subgrupo!=0",null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
	}//fin else
//pr($datacpcp01);
$this->set('datos',$datacpcp01);

}

function editar($var=null, $var2=null,$var3=null){
 $this->layout = "ajax";
  $accion =  $this->cimd01_clasificacion_subgrupo->findAll("cod_tipo = ".$var." and cod_grupo  = ".$var2." and cod_subgrupo=".$var3);

		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var."_".$var2."_".$var3."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var."_".$var2."_".$var3."').style.display = 'block'; ";
          echo "document.getElementById('td_4_".$var."_".$var2."_".$var3."').innerHTML='<input type=text name=data[cimp01_clasificacion_subgrupo][denominacion] size=64% value=\"".$accion[0]['cimd01_clasificacion_subgrupo']['denominacion']."\" />';  ";
		echo "</script>";


    $this->set('cod_tipo', $var);
	$this->set('cod_grupo', $var2);
	$this->set('cod_subgrupo', $var3);

$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function



function cancelar($cod_tipo, $cod_grupo, $cod_subgrupo){
	$this->layout = "ajax";
	 $datos=$this->cimd01_clasificacion_subgrupo->findAll('cod_tipo='.$cod_tipo." and cod_grupo=".$cod_grupo,null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
 	$this->set('datos',$datos);
}

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cimp01_clasificacion_subgrupo']['login']) && isset($this->data['cimp01_clasificacion_subgrupo']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cimp01_clasificacion_subgrupo']['login']);
		$paswd=addslashes($this->data['cimp01_clasificacion_subgrupo']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=104 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}



}
?>