<?php
 class Cnmp06DatosFamiliaresController extends AppController {
   var $name = 'cnmp06_datos_familiares';
   var $uses = array('v_cnmd06_datos_familiares','cnmd06_parentesco','cnmd06_datos_familiares', 'cnmd06_datos_hijos','cnmd06_instituto_educativo',
                     'cnmd06_datos_personales', 'cnmd06_guarderias');
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
 	$this->data = null;
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
    $listacurso=$this->cnmd06_parentesco->generateList('activo=true', 'denominacion ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
    $this->concatena($listacurso, 'cod_parentesco');

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


$listaguarderia=$this->cnmd06_guarderias->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
if($listaguarderia == null){
	$this->set('cod_guarderia',array('0'=>'00'));
}else{
	$this->concatena($listaguarderia, 'cod_guarderia');
}
}//fin function

function index_hijos(){
  $this->layout ="ajax";
  $this->data = null;
  $this->set('entidad_federal', $this->Session->read('entidad_federal'));

    if($this->Session->read('cedula_pestana_expediente')==""){
          $id=0;
    }else{
          $id=$this->Session->read('cedula_pestana_expediente');
    }//fin else

    $datos=$this->cnmd06_datos_hijos->findAll("cedula=".$id);
    $Tfilas=$this->cnmd06_datos_hijos->findCount("cedula=".$id);
  
  $this->set('cedula', $id);
  $this->set('datos', $datos);
  $this->set('count_datos', $Tfilas);

}//fin function

function guardar_hijos(){
  $this->layout = "ajax";
    $cedula=$this->Session->read('cedula_pestana_expediente');

  if(!empty($this->data)){
    $nombres_apellidos=$this->data['cnmp06_datos_familiares']['nombres_apellidos'];
    $numero_cedula=$this->data['cnmp06_datos_familiares']['numero_cedula'];

    $fecha_nacimiento=$this->data['cnmp06_datos_familiares']['fecha_nacimiento'];
    $sexo=$this->data['cnmp06_datos_familiares']['sexo'];
    $afiliado=$this->data['cnmp06_datos_familiares']['afiliado'];
    $estudiante=$this->data['cnmp06_datos_familiares']['estudiante'];

    $SQL_INSERT ="INSERT INTO cnmd06_datos_hijos (cedula,nombres_apellidos,numero_cedula,fecha_nacimiento,sexo,afiliado,estudiante)";
    $SQL_INSERT .=" VALUES ($cedula,'".$nombres_apellidos."',$numero_cedula,'".$fecha_nacimiento."','".$sexo."','".$afiliado."','".$estudiante."')";

    $resp=$this->cnmd06_datos_familiares->execute($SQL_INSERT);

    if($resp>1) {
      $this->set('Message_existe', 'Registro Agregado con exito.');
    }else{
      $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
    }
  }
  $datos=$this->cnmd06_datos_hijos->findAll("cedula=".$cedula);
  $Tfilas=$this->cnmd06_datos_hijos->findCount("cedula=".$cedula);
  $this->set('datos',$datos);
  $this->set('count_datos',$Tfilas);  
}

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
  }
   $listaguarderia=$this->cnmd06_guarderias->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
  if($listaguarderia == null){
    $this->set('cod_guarderia',array('0'=>'00'));
  }else{
    $this->concatena($listaguarderia, 'cod_guarderia');
  }
}//fin function

function consultar($pagina=null){
 	$this->layout = "ajax";

  if($this->Session->read('cedula_pestana_expediente')==""){
   	$id=0;
  }else{
	  $id=$this->Session->read('cedula_pestana_expediente');
  }//fin else
  $Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
  if($Tfilas!=0){
    $cond2="cedula=".$id;
  }else{
    $cond2="cedula=".$id;
  }//fin else

  $pagina=1;
  $Tfilas=$this->v_cnmd06_datos_familiares->findCount($cond2);
  if($Tfilas==0){
    $this->index();
  }
  if($Tfilas!=0){
    $this->set('pag_cant',$pagina.'/'.$Tfilas);
    $datos=$this->v_cnmd06_datos_familiares->findAll($cond2,null,'cedula ASC');
    $this->set('datos',$datos);
	}
	
	$listaguarderia=$this->cnmd06_guarderias->generateList(null, 'cod_guarderia ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
  $this->concatena($listaguarderia, 'cod_guarderia');
  $this->set("deno_guar", $this->cnmd06_guarderias->findAll());

}//fin function

function consultar_hijos(){
    $this->layout = "ajax";

if($this->Session->read('cedula_pestana_expediente')==""){
      $id=0;
}else{
      $id=$this->Session->read('cedula_pestana_expediente');
}//fin else
      $Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
       $cond2="cedula=".$id;
   }else{
        $cond2="";
   }//fin else

              $Tfilas=$this->cnmd06_datos_hijos->findCount($cond2);
             if($Tfilas==0){
              $this->index_hijos();
              $this->render("index_hijos");
             }else{
             $datos=$this->cnmd06_datos_hijos->findAll($cond2);
             $this->set('datos',$datos);
             }

}//fin function



function codi_parentesco($codigo){
	$this->layout = "ajax";
	$a = $this->cnmd06_parentesco->findAll("cod_parentesco=".$codigo,array('cod_parentesco','denominacion'));
    $this->set("a",$a[0]['cnmd06_parentesco']['cod_parentesco']);
}


function cod_guarderia($var=null){
    $this->layout = "ajax";

    $a = $this->cnmd06_guarderias->findAll("cod_guarderia=".$var);

    echo"<script>
      	    document.getElementById('denominacion_guarderia').value='".$a[0]["cnmd06_guarderias"]["denominacion"]."';
          </script>";



}//fin function


function deno_parentesco($codigo){
	$this->layout = "ajax";
	$b = $this->cnmd06_parentesco->findAll("cod_parentesco=".$codigo,array('cod_parentesco','denominacion'));
	$this->set("b",$b[0]['cnmd06_parentesco']['denominacion']);
}


function guardar(){
  $this->layout = "ajax";

	if(!empty($this->data)){
    $cedula=$this->data['cnmp06_datos_familiares']['cedula'];
    $cod_parentesco=$this->data['cnmp06_datos_familiares']['cod_parentesco'];
    $nombres_apellidos=$this->data['cnmp06_datos_familiares']['nombres_apellidos'];
    $fecha_nacimiento=$this->data['cnmp06_datos_familiares']['fecha_nacimiento'];
    $sexo=$this->data['cnmp06_datos_familiares']['sexo'];
    $numero_cedula          =  $this->data['cnmp06_datos_familiares']['numero_cedula'];    
    if(isset($this->data['cnmp06_datos_familiares']['afiliado'])){
      $afiliado               =  $this->data['cnmp06_datos_familiares']['afiliado'];
    }else{
      $afiliado = "";
    }
      $estudiante = 'F';
    if(isset($this->data['cnmp06_datos_familiares']['estudiante']) && $this->data['cnmp06_datos_familiares']['estudiante']=="true"){
      $estudiante               =  'T';
    }
    $denominacion_guarderia =  $this->data['cnmp06_datos_familiares']['denominacion_guarderia'];
    $cod_guarderia          =  $this->data['cnmp06_datos_familiares']['cod_guarderia'];
    $costo_guarderia        =  $this->Formato1($this->data['cnmp06_datos_familiares']['costo_guarderia']);

    if($numero_cedula==""){ $numero_cedula = "0";}
    if($afiliado==""){ $afiliado = "0";}
    if($denominacion_guarderia==""){ $denominacion_guarderia = "0";}
    if($cod_guarderia==""){ $cod_guarderia = "0";}
    if($costo_guarderia==""){ $costo_guarderia = "0";}

    $ss=$this->cnmd06_datos_familiares->findAll(null,array('consecutivo'),'consecutivo DESC',1,1,null);
    if($ss==null){
      $consecutivo=1;
    }else{
      $consecutivo=$ss[0]["cnmd06_datos_familiares"]["consecutivo"]+1;
    }
  }

  $SQL_INSERT ="INSERT INTO cnmd06_datos_familiares (cedula,cod_parentesco,consecutivo,nombres_apellidos,numero_cedula,fecha_nacimiento,sexo,afiliado,estudiante,cod_guarderia,costo_guarderia)";
  $SQL_INSERT .=" VALUES ($cedula, $cod_parentesco,$consecutivo,'".$nombres_apellidos."',$numero_cedula,'".$fecha_nacimiento."','".$sexo."', '".$afiliado."','".$estudiante."', '".$cod_guarderia."','".$costo_guarderia."')";

  $resp=$this->cnmd06_datos_familiares->execute($SQL_INSERT);
  if($resp>1){
    $this->set('Message_existe', 'Registro Agregado con exito.');
  }else if ($resp <= 1){
    $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
  }
  $listaguarderia=$this->cnmd06_guarderias->generateList(null, 'cod_guarderia ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
  $this->concatena($listaguarderia, 'cod_guarderia');
  $this->modificar($cedula);
  $this->render('modificar');
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






function infomacion_faltante($var1=null, $var2=null){
$this->layout = "ajax";
$var3 = "";
		switch($var1){
                case "guarderia":{  $this->set('userTable', $this->requestAction('/cnmp06_guarderias/', array('return')));  }break;
		 }//fin
$this->set('opcion',     $var1);
$this->set('capa',       $var2);
$this->set('controlador',$var3);
}//fin function



function select_cambio($var1=null, $var2=null, $var3=null){
$this->layout = "ajax";
	switch($var1){
                case "guarderia":{
                	$listaguarderia=$this->cnmd06_guarderias->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
                    $this->concatena($listaguarderia, 'lista');
	                $this->set("name", "cod_guarderia");
                }break;

		 }//fin
}//fin function



function buscar_pista($var1=null, $var2=null){
$this->layout = "ajax";
        switch($var1){
                case "guarderia":{
                	$listaguarderia=$this->cnmd06_guarderias->generateList("upper(denominacion) LIKE upper('%$var2%')", 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
                    $this->concatena($listaguarderia, 'lista');
	                $this->set("name", "cod_guarderia");
                }break;

		 }//fin
 $this->render("select_cambio");
}//fin function







function modificar($cedula=null,$consecutivo=null,$cod_parentesco=null){
  $this->layout = "ajax";
  $cond ="cedula=".$cedula;
  $datos=$this->v_cnmd06_datos_familiares->findAll($cond);
  $listacurso=$this->cnmd06_parentesco->generateList('activo=true', 'denominacion ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
  $this->concatena($listacurso, 'cod_parentescos');
  $this->set('datos',$datos);
  $listaguarderia=$this->cnmd06_guarderias->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
  $this->concatena($listaguarderia, 'cod_guarderia');

  $this->set("deno_guar", $this->cnmd06_guarderias->findAll());

}


function guardar_modificar($cedula=null,$consecutivo=null,$cod_parentescos=null){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $cod_parentesco=$this->data['cnmp06_datos_familiares']['cod_parentesco'];
	 $nombres_apellidos=$this->data['cnmp06_datos_familiares']['nombres_apellidos'];
	 $fecha_nacimiento=$this->data['cnmp06_datos_familiares']['fecha_nacimiento'];
	 $sexo=$this->data['cnmp06_datos_familiares']['sexo'];


	 $numero_cedula          =  $this->data['cnmp06_datos_familiares']['numero_cedula'];
	 if(isset($this->data['cnmp06_datos_familiares']['afiliado'])){
	   $afiliado               =  $this->data['cnmp06_datos_familiares']['afiliado'];
	 }else{$afiliado = "";}
   if(isset($this->data['cnmp06_datos_familiares']['estudiante']) && $this->data['cnmp06_datos_familiares']['estudiante']==true){
     $estudiante              =  true;
   }else{$estudiante = false;}
	 $denominacion_guarderia =  $this->data['cnmp06_datos_familiares']['denominacion_guarderia'];
	 $cod_guarderia          =  $this->data['cnmp06_datos_familiares']['cod_guarderia'];
	 $costo_guarderia        =  $this->Formato1($this->data['cnmp06_datos_familiares']['costo_guarderia']);


	         if($numero_cedula==""){ $numero_cedula = "0";}
		     if($afiliado==""){ $afiliado = "0";}
		     if($denominacion_guarderia==""){ $denominacion_guarderia = "0";}
		     if($cod_guarderia==""){ $cod_guarderia = "0";}
		     if($costo_guarderia==""){ $costo_guarderia = "0";}



	 $cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_parentesco=".$cod_parentescos;
	 $sql="update cnmd06_datos_familiares set cod_parentesco=$cod_parentesco,nombres_apellidos='".$nombres_apellidos."',numero_cedula=$numero_cedula,fecha_nacimiento='".$fecha_nacimiento."',sexo='".$sexo."',afiliado=$afiliado,estudiante=$estudiante,cod_guarderia=$cod_guarderia,costo_guarderia=$costo_guarderia where ". $cond;
     $vvv=$this->cnmd06_datos_familiares->execute($sql);
     $this->data=null;
     $this->set('Message_existe', 'Registro Modificado con exito.');
	 $this->consultar();
     $this->render("consultar");


	}
}//fin guardar_modificar






function expediente(){

  if($this->Session->read('cedula_pestana_expediente')==""){
    $id=0;
  }else{
    $id=$this->Session->read('cedula_pestana_expediente');
  }//fin else

  $y=$this->cnmd06_datos_familiares->findCount("cedula=".$id);
  if($y==0){
    $this->index();
  }else{
    $this->consultar();
    $this->render("consultar");
  }//fin else
}//fin function

function expediente_hijos(){

    if($this->Session->read('cedula_pestana_expediente')==""){
              $id=0;
    }else{
              $id=$this->Session->read('cedula_pestana_expediente');
    }//fin else

    $this->index_hijos();
    $this->render("index_hijos");
}//fin function

function eliminar_hijos($consecutivo){
  $this->layout = "ajax";
  $sql1 ="DELETE  FROM  cnmd06_datos_hijos WHERE consecutivo=".$consecutivo;
    $this->cnmd06_datos_familiares->execute($sql1);
    $this->set('Message_existe', 'Dato Eliminado con exito.');
    $this->guardar_hijos();
    $this->render('guardar_hijos');
}//fin function





function eliminar($cedula=null,$consecutivo=null,$cod_parentesco=null){
	$this->layout = "ajax";
	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_parentesco=".$cod_parentesco;
		$sql1 ="DELETE  FROM  cnmd06_datos_familiares where ".$cond;
		$this->cnmd06_datos_familiares->execute($sql1);
		$this->set('Message_existe', 'Dato Eliminado con exito.');
	  $y=$this->cnmd06_datos_familiares->findCount();

	  $this->modificar($cedula);
    $this->render("modificar");
		
}
//fin eliminar
}