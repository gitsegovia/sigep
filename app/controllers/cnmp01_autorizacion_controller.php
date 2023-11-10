<?php
/*
 * Creado el 04/03/2010 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Cnmp01AutorizacionController extends AppController{
	var $uses = array('cnmd01_autorizados','Usuario','Cnmd01');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp01_autorizacion";


 function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

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


function index(){///////////////<<--INDEX
	$this->layout = "ajax";

	$this->data=null;

}//fin index


function busqueda_usuario($login=null){
	$this->layout="ajax";
	$login=strtoupper($login);
//	$ver  = $this->ccnd00->findCount("  quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");
	$ver1 = $this->Usuario->findCount($this->SQLCA()." and  quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");

	if($ver1==0){
		$this->set('errorMessage',"el usuario que ingreso no existe registrado");

	}

}// fin busqueda_usuario






function denominacion($var=null){
	$this->layout="ajax";
	if($var!=''){
		$cod_presi=$this->Session->read('cod_presi_nomi_auto');
		$cod_entidad=$this->Session->read('cod_entidad_nomi_auto');
		$cod_tipo_inst=$this->Session->read('cod_tipo_inst_nomi_auto');
		$cod_inst=$this->Session->read('cod_inst_nomi_auto');
		$cod_dep=$this->Session->read('cod_dep_nomi_auto');
		$a=$this->Cnmd01->execute("select * from cnmd01 where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cod_tipo_nomina=".$var);
		$this->set('denominacion',$a[0][0]['denominacion']);
	}else{
		$this->set('denominacion',null);
	}
}



function buscar_datos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$a=$this->busca_separado(array('username'),$var2);
					$Tfilas=$this->Usuario->findCount($this->condicionNDEP()." and ".$a);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->Usuario->findAll($this->condicionNDEP()." and ".$a,null,"username ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$a=$this->busca_separado(array('username'),$var2);
						$Tfilas=$this->Usuario->findCount($this->condicionNDEP()." and ".$a);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->Usuario->findAll($this->condicionNDEP()." and ".$a,null,"username ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


	$this->set("opcion",$var1);
}//fin function



function seleccion_busqueda($opc=null,$var=null){
	$this->layout = "ajax";
	$dato=$this->Usuario->execute("select * from usuarios where upper(username)=upper('$var')");
	$this->set('usuario',$dato);

	$this->Session->write('cod_presi_nomi_auto',$dato[0][0]['cod_presi']);
	$this->Session->write('cod_entidad_nomi_auto',$dato[0][0]['cod_entidad']);
	$this->Session->write('cod_tipo_inst_nomi_auto',$dato[0][0]['cod_tipo_inst']);
	$this->Session->write('cod_inst_nomi_auto',$dato[0][0]['cod_inst']);
	$this->Session->write('cod_dep_nomi_auto',$dato[0][0]['cod_dep']);

	$lista = $this->Cnmd01->generateListTodos("cod_presi=".$dato[0][0]['cod_presi']." and cod_entidad=".$dato[0][0]['cod_entidad']." and cod_tipo_inst=".$dato[0][0]['cod_tipo_inst']." and cod_inst=".$dato[0][0]['cod_inst']." and cod_dep=".$dato[0][0]['cod_dep'], $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista!=null){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina',array());
	}

	$dato=$this->cnmd01_autorizados->execute("select a.username,a.cod_tipo_nomina,
											 (select b.denominacion from cnmd01 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina) as deno_nomina
											 from cnmd01_autorizados a where cod_presi=".$dato[0][0]['cod_presi']." and cod_entidad=".$dato[0][0]['cod_entidad']." and cod_tipo_inst=".$dato[0][0]['cod_tipo_inst']." and cod_inst=".$dato[0][0]['cod_inst']." and cod_dep=".$dato[0][0]['cod_dep']." and upper(a.username)=upper('$var') order by a.cod_tipo_nomina asc");
	if($dato!=null){
		$this->set('datos',$dato);
	}else{
		$this->set('datos',null);
	}


}


function guardar(){
	$this->layout = "ajax";
	$cod_presi=$this->Session->read('cod_presi_nomi_auto');
	$cod_entidad=$this->Session->read('cod_entidad_nomi_auto');
	$cod_tipo_inst=$this->Session->read('cod_tipo_inst_nomi_auto');
	$cod_inst=$this->Session->read('cod_inst_nomi_auto');
	$cod_dep=$this->Session->read('cod_dep_nomi_auto');

	if(empty($this->data['cnm01']['cod_nomina'])){
		$this->set('errorMessage', 'debe seleccionar la nómina');
	}else{
		$usuario       = $this->data['cnm01']['usuario'];
		$nomina       = $this->data['cnm01']['cod_nomina'];
		$this->set('usuario',$usuario);
		if($this->cnmd01_autorizados->FindCount($this->SQLCA()." and upper(username)=upper('$usuario') and cod_tipo_nomina=".$nomina)==0){
			$SQL_INSERT ="INSERT INTO cnmd01_autorizados VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$usuario','$nomina')";
			$sw = $this->cnmd01_autorizados->execute($SQL_INSERT);
			if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				$this->set('guardado', 'si');
			}else{
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
			}
		}else{
			$this->set('errorMessage', 'estos datos ya existen registrados');
		}


	}



}





function eliminar($user=null,$cod_nomina=null){
 	$this->layout = "ajax";
 	$cod_presi=$this->Session->read('cod_presi_nomi_auto');
	$cod_entidad=$this->Session->read('cod_entidad_nomi_auto');
	$cod_tipo_inst=$this->Session->read('cod_tipo_inst_nomi_auto');
	$cod_inst=$this->Session->read('cod_inst_nomi_auto');
	$cod_dep=$this->Session->read('cod_dep_nomi_auto');

 	$this->cnmd01_autorizados->execute("DELETE FROM cnmd01_autorizados  WHERE cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and upper(username)=upper('$user') and cod_tipo_nomina=".$cod_nomina);
	$this->set('Message_existe', 'el dato fue eliminado');


}



}

//fin class