<?php
 class InfoCnmp06DatosRegistroTituloController extends AppController {
   var $name = 'info_cnmp06_datos_registro_titulo';
   var $uses = array('v_cnmd06_datos_registro_titulo','cnmd06_especialidades','cnmd06_profesiones','v_cnmd06_datos_formacion_profesional','cnmd06_colegio_profesional','cnmd06_datos_registro_titulo','cnmd06_instituto_educativo','cnmd06_datos_personales');
   var $helpers = array('Html','Ajax','Javascript','Infogob','Sisap');

function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
				}
}//fin checksession
 function beforeFilter(){
 	$this->checkSession();
 }

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

  function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero

function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
    }//fin funcion SQLCA

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena








 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
    $listacurso=$this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
    $this->concatena_tres_digitos($listacurso, 'cod_profesion');
    $listacolegio=$this->cnmd06_colegio_profesional->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_colegio_profesional.cod_colegio', '{n}.cnmd06_colegio_profesional.denominacion');
    $this->concatena_tres_digitos("", 'cod_colegio');



    if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
    }//fin else
	$this->set('cedula', "");
	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
      $this->cedula($this->Session->read('cedula_pestana_expediente'));
      $this->render("cedula");
   }else{
   	    $this->set('ci',"");
	    $this->set('pa',"");
	    $this->set('sa',"");
	    $this->set('pn',"");
	    $this->set('sn',"");
  }//fin else


}//fin function








function expediente(){

		if($this->Session->read('cedula_pestana_expediente')==""){
		         	$id=0;
		}else{
		    	    $id=$this->Session->read('cedula_pestana_expediente');
		}//fin else

		    $y=$this->v_cnmd06_datos_registro_titulo->findCount("cedula=".$id);

				    if($y==0){
				            $this->index();
				    }else{
				            $this->consultar();
				            $this->render("consultar");
				    }//fin else
}//fin function











function infomacion_faltante($var1=null, $var2=null){

$this->layout = "ajax";

$var3 = "";

		switch($var1){
                case "colegio":{  $this->set('userTable', $this->requestAction('/cnmp06_colegio_profesional2/', array('return')));  }break;
		 }//fin

$this->set('opcion',     $var1);
$this->set('capa',       $var2);
$this->set('controlador',$var3);

}//fin function



function select_cambio($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";

	switch($var1){
                case "colegio":{
                	$listacolegio=$this->cnmd06_colegio_profesional->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_colegio_profesional.cod_colegio', '{n}.cnmd06_colegio_profesional.denominacion');
                    $this->concatena_tres_digitos("", 'lista');
	                $this->set("name", "cod_colegio");
                }break;

		 }//fin


}//fin function






function buscar_pista($var1=null, $var2=null){
$this->layout = "ajax";
        switch($var1){
                case "colegio":{
                	$listacolegio=$this->cnmd06_colegio_profesional->generateList("upper(denominacion) LIKE upper('%$var2%')", 'denominacion ASC', null, '{n}.cnmd06_colegio_profesional.cod_colegio', '{n}.cnmd06_colegio_profesional.denominacion');
                    $this->concatena_tres_digitos($listacolegio, 'lista');
	                $this->set("name", "cod_colegio");
                }break;
		 }//fin
 $this->render("select_cambio");
}//fin function





function cedula($ci=null){
	$this->layout = "ajax";
	$cond ="cedula_identidad=".$ci;
	$cond2="cedula=".$ci;
	$resul = 0;
	if($resul==0){
			$a = $this->cnmd06_datos_personales->findAll($cond);
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$ci);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);
		    //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
	}else{
            $pagina=1;
          	 $Tfilas=$this->v_cnmd06_datos_registro_titulo->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_registro_titulo->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);


	}//fin else


}//fin function


function consultar($pagina=null){
 		$this->layout = "ajax";
		$id = $_SESSION['infogobierno']['cedula_identidad'];
      	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   		if($Tfilas!=0){
       		$cond2="cedula=".$id;
   		}else{
   	    	$cond2="";
   		}//fin else

         if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->v_cnmd06_datos_registro_titulo->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		//$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_registro_titulo->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('pagina',$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_cnmd06_datos_registro_titulo->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		//$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_registro_titulo->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('pagina',$pagina);
			 }
}//fin if



function cod_colegio($codigo){
	$this->layout = "ajax";
	$a = $this->cnmd06_colegio_profesional->findAll("cod_colegio=".$codigo,array('cod_colegio','denominacion'));
    $this->set("a",$a[0]['cnmd06_colegio_profesional']['cod_colegio']);
}

function deno_colegio($codigo){
	$this->layout = "ajax";
	$b = $this->cnmd06_colegio_profesional->findAll("cod_colegio=".$codigo,array('cod_colegio','denominacion'));
	$this->set("b",$b[0]['cnmd06_colegio_profesional']['denominacion']);
}


function guardar(){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $cedula=$this->data['cnmp06_datos_registro_titulo']['cedula'];
	 $cod_profesion=$this->data['cnmp06_datos_registro_titulo']['cod_profesion'];
	 $cod_especialidad=$this->data['cnmp06_datos_registro_titulo']['cod_especialidad'];
	 $numero_registro=$this->data['cnmp06_datos_registro_titulo']['numero_registro'];
	 $tomo=$this->data['cnmp06_datos_registro_titulo']['tomo'];
	 $folio=$this->data['cnmp06_datos_registro_titulo']['folio'];
	 $fecha_registro=$this->data['cnmp06_datos_registro_titulo']['fecha_registro'];
	 $cod_colegio=$this->data['cnmp06_datos_registro_titulo']['cod_colegio'];
	 $numero_colegio=$this->data['cnmp06_datos_registro_titulo']['numero_colegio'];
	 $ss=$this->cnmd06_datos_registro_titulo->findAll(null,array('consecutivo'),'consecutivo DESC',1,1,null);
 		 if($ss==null){
     	$consecutivo=1;
     }else{
     	$consecutivo=$ss[0]["cnmd06_datos_registro_titulo"]["consecutivo"]+1;
     }
	}

	$SQL_INSERT ="INSERT INTO cnmd06_datos_registro_titulo (cedula,cod_profesion,consecutivo,numero_registro,tomo,folios,fecha_registro,cod_colegio,numero_colegio,cod_especialidad)";
	 $SQL_INSERT .=" VALUES ($cedula, $cod_profesion,$consecutivo,$numero_registro,'".$tomo."','".$folio."','".$fecha_registro."',$cod_colegio,'".$numero_colegio."',$cod_especialidad)";
	 $resp=$this->cnmd06_datos_registro_titulo->execute($SQL_INSERT);
	if($resp>1){
		$this->set('msj', array('Registro Agregado con exito.','exito'));
	  	$this->consultar();
	  	$this->render('consultar');
  	}else if ($resp <= 1){
  		$this->set('msj', array('Disculpe, El Registro no fue creado.','error'));
	  	$this->cedula();
     }//fin else
}


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




function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'profesion':
		  $this->set('SELECT','especialidad');
		  $this->set('codigo','profesion');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $lista=  $this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.cod_estado');
          $this->concatena_tres_digitos($lista, 'vector');
		break;
		case 'especialidad':
		  $this->set('SELECT','especialidad');
		  $this->set('codigo','especialidad');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->set('no','no');
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('pro',$var);
		  $cond="cod_profesion=".$var;
		  $lista=  $this->cnmd06_especialidades->generateList($cond, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios

function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	if( $var!=null){
	switch($select){
		case 'profesion':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('pro',$var);
		  $cond="cod_profesion=".$var;
		  $a=  $this->cnmd06_profesiones->findAll($cond);
          $cod= $a[0]['cnmd06_profesiones']['cod_profesion'] >9 ?$a[0]['cnmd06_profesiones']['cod_profesion'] : "0".$a[0]['cnmd06_profesiones']['cod_profesion'] ;
          //echo 'el codigo es '.$cod;
          $this->set('codi',$cod);
		break;
		case 'especialidad':
		  $pro =  $this->Session->read('pro');
		  $this->Session->write('espe',$var);
		  $cond="cod_profesion=".$pro." and cod_especialidad=".$var;
		  $a=  $this->cnmd06_especialidades->findAll($cond);
          $cod=$a[0]['cnmd06_especialidades']['cod_especialidad'] >9 ?$a[0]['cnmd06_especialidades']['cod_especialidad'] : "0".$a[0]['cnmd06_especialidades']['cod_especialidad'] ;
		$this->set('codi',$cod);
		//echo 'el codigo es '.$cod;
		break;
		}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null && !empty($var)){
    $cond = $this->SQLCAX();
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'profesion':
		  $this->Session->write('pro',$var);
		  $cond="cod_profesion=".$var;
		  $a=  $this->cnmd06_profesiones->findAll($cond);
          $den=$a[0]['cnmd06_profesiones']['denominacion'];
          $this->set('deno',$den);
		break;
		case 'especialidad':
		  $pro =  $this->Session->read('pro');
		  $this->Session->write('espe',$var);
		  $cond="cod_profesion=".$pro." and cod_especialidad=".$var;
		  $a=  $this->cnmd06_especialidades->findAll($cond);
          $den=$a[0]['cnmd06_especialidades']['denominacion'];
          $this->set('deno',$den);
		break;
		}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 co

function modificar($cedula=null,$consecutivo=null,$cod_profesion=null,$pagina=null){
	  $this->layout = "ajax";
	  		$this->set('pagina',$pagina);
          	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_profesion=".$cod_profesion;
          	$datos=$this->v_cnmd06_datos_registro_titulo->findAll($cond);
          	$this->set('datos',$datos);
          	$listacurso=$this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
		    $this->concatena_tres_digitos($listacurso, 'cod_profesiones');


		    $cond2="cod_profesion=".$cod_profesion;
		    $listaespecialidad=  $this->cnmd06_especialidades->generateList($cond2, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
          	$this->concatena($listaespecialidad, 'cod_especialidades');


		    $listacolegio=$this->cnmd06_colegio_profesional->generateList("cod_colegio=".$datos[0]["v_cnmd06_datos_registro_titulo"]["cod_colegio"], 'denominacion ASC', null, '{n}.cnmd06_colegio_profesional.cod_colegio', '{n}.cnmd06_colegio_profesional.denominacion');
		    $this->concatena_tres_digitos($listacolegio, 'cod_colegios');


          }
function guardar_modificar($cedula=null,$consecutivo=null,$cod_profesiones=null,$pagina=null){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $cod_profesion=$this->data['cnmp06_datos_registro_titulo']['cod_profesion'];
	 $cod_especialidad=$this->data['cnmp06_datos_registro_titulo']['cod_especialidad'];
	 $numero_registro=$this->data['cnmp06_datos_registro_titulo']['numero_registro'];
	 $tomo=$this->data['cnmp06_datos_registro_titulo']['tomo'];
	 $folio=$this->data['cnmp06_datos_registro_titulo']['folio'];
	 $fecha_registro=$this->data['cnmp06_datos_registro_titulo']['fecha_registro'];
	 $cod_colegio=$this->data['cnmp06_datos_registro_titulo']['cod_colegio'];
	 $numero_colegio=$this->data['cnmp06_datos_registro_titulo']['numero_colegio'];
	 $cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_profesion=".$cod_profesiones;
	 $sql="update cnmd06_datos_registro_titulo set cod_profesion=$cod_profesion,numero_registro=$numero_registro,tomo='".$tomo."',folios='".$folio."',fecha_registro='".$fecha_registro."',cod_colegio=$cod_colegio,numero_colegio='".$numero_colegio."',cod_especialidad=$cod_especialidad where ". $cond;
     $vvv=$this->cnmd06_datos_registro_titulo->execute($sql);
     $this->data=null;
     $this->set('msj', array('Registro Modificado con exito.','exito'));
	 $this->consultar($pagina);
     $this->render("consultar");
	}
}//fin guardar_modificar

function eliminar($cedula=null,$consecutivo=null,$cod_profesion=null){
	$this->layout = "ajax";
	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_profesion=".$cod_profesion;
		$sql1 ="DELETE  FROM  cnmd06_datos_registro_titulo where ".$cond;
		$this->cnmd06_datos_registro_titulo->execute($sql1);
		$this->set('msj', array('Registro Eliminado con exito.','exito'));
	  	$this->index();
}
}