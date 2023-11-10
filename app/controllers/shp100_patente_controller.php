<?php

 class shp100PatenteController extends AppController{
 	var $name = "shp100_patente";
	var $uses = array('v_shd100_solicitud_actividades','v_shd100_patente_actividades','v_shd100_patente','v_shd100_solicitud','shd100_patente','shd001_registro_contribuyentes','shd100_patente_actividades','shd100_solicitud',
                      'shd100_solicitud_actividades', 'shd002_cobradores', 'shd100_actividades', 'cnmd06_profesiones','cugd01_republica', 'cugd01_estados',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda');
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


function verificar_fechas($dia=null, $mes=null, $year=null){

	$this->layout = "ajax";

	$fecha = $dia."/".$mes."/".$year;

echo "  <script>
	             if(diferenciaFecha('$fecha', $('fecha_solicitud').value)){
	                  fun_msj('la Fecha de la patente debe ser mayor a la de la solcitud');
	                  $('fecha_patente').value = '';
	              }
	    </script> ";

$this->render("funcion2");
}//fin function


function funcion2(){$this->layout = "ajax";}



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



function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;
}//fin funcion SQLX


function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
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



function concatena($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[$x] = $this->zero($x).' - '.$y;
		}
		$this->set($nomVar, $cod);
	}
}//fin concatena









function index($var=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
 $this->data = null;


 $this->set("lista_cobrador", $this->shd002_cobradores->generateList($condicion, "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
 $this->concatena_sin_cero($this->shd100_actividades->generateList($condicion.' and minimo_tributable != 0', "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "lista_actividades");


 $this->Session->delete("DATOS");
 $this->Session->delete("CONSULTA");

 if($_SESSION["utiliza_planillas_liquidacion_previa"]==2){
        $this->Session->write('frecu',$_SESSION["frecuencia_pago_segun_ordenanza"]);
 }else{ $this->Session->write('frecu',1);}

}//fin index







function funcion($var=null){$this->layout = "ajax";}//fin index



function frecuenc($frecu){
	$this->layout = "ajax";
	$this->Session->write('frecu',$frecu);
	$mmens= 0;
	$msfre= 0;

if(isset($_SESSION["DATOS"]) and $_SESSION["DATOS"]!=null){
$to=0;
	foreach($_SESSION["DATOS"] as $sa){
		$activa= $sa['activa'];
		$total_aforo= $this->Formato1($sa['total_aforo']);
		if($activa==1){
			$to= $to + $total_aforo;
		}
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;



echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}
$this->render("funcion");
}


function frecuenc2($rif_cedula=null,$frecu=null){
	$this->layout = "ajax";
	$this->Session->write('frecu',$frecu);
	$mmens= 0;
	$msfre= 0;

$datos=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'");

if(isset($datos) and $datos!=null){
$to=0;
	foreach($datos as $sa){
		$total_aforo= $sa['v_shd100_patente_actividades']['total_aforo_anual'];
			$to= $to + $total_aforo;
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;



echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}
$this->render("funcion");
}


/*function agregar_grilla(){

$this->layout = "ajax";


   $cod_actividad    = $this->data['shp100_patente']['cod_actividad'];
   $activ_deno       = $this->data['shp100_patente']['activ_deno'];
   $actv_num_afor    = $this->data['shp100_patente']['actv_num_afor'];
   $activ_mont_aforo = $this->data['shp100_patente']['activ_mont_aforo'];
   $total_aforo      = $this->data['shp100_patente']['total_aforo'];



			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["activ_deno"]       = $activ_deno;
	              $_SESSION["DATOS"][$cont]["actv_num_afor"]    = $actv_num_afor;
	              $_SESSION["DATOS"][$cont]["activ_mont_aforo"] = $activ_mont_aforo;
	              $_SESSION["DATOS"][$cont]["total_aforo"]      = $total_aforo;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;

	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{

                  $cont  = $_SESSION["CUENTA"];
                  $marca = 0;

		              for($i=1; $i<=$cont; $i++){
		                   if($cod_actividad==$_SESSION["DATOS"][$i]["cod_actividad"]  &&  $_SESSION["DATOS"][$i]["activa"]==1){
                               $marca=1;
		                   }//fin if
		              }//fin for
	                 if($marca==1){
                           $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	                 }else{
                              $cont = $_SESSION["CUENTA"];  $cont++; $_SESSION["CUENTA"] = $cont;
                              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
				              $_SESSION["DATOS"][$cont]["activ_deno"]       = $activ_deno;
				              $_SESSION["DATOS"][$cont]["actv_num_afor"]    = $actv_num_afor;
				              $_SESSION["DATOS"][$cont]["activ_mont_aforo"] = $activ_mont_aforo;
				              $_SESSION["DATOS"][$cont]["total_aforo"]      = $total_aforo;
				              $_SESSION["DATOS"][$cont]["activa"]           = 1;
				              $_SESSION["DATOS"][$cont]["id"]               = $cont;
                           $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else
$mmens= 0;
$msfre= 0;

//pr($_SESSION["DATOS"]);

if(isset($_SESSION["DATOS"]) and $_SESSION["DATOS"]!=null){
$to=0;
	foreach($_SESSION["DATOS"] as $sa){
		$activa= $sa['activa'];
		$total_aforo= $this->Formato1($sa['total_aforo']);
		if($activa==1){
			$to= $to + $total_aforo;
		}
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;


echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}

echo'<script>';
       echo" document.getElementById('num_12').options[0].text     = ''; ";
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('actv_num_afor').value        = ''; ";
       echo" document.getElementById('activ_mont_aforo').value     = ''; ";
       echo" document.getElementById('total_aforo').value          = ''; ";
echo'</script>';




$this->set("accion", $_SESSION["DATOS"]);


}//fin function
*/

function agregar_g($rif_cedula=null){

$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
 	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$cod_dep = $this->Session->read('SScoddep');
   	$cod_actividad    = $this->data['shp100_patente']['cod_actividad'];
   	$activ_deno       = $this->data['shp100_patente']['activ_deno'];
   	$numero_aforos    = $this->data['shp100_patente']['actv_num_afor'];
   	$monto_aforo_anual = $this->Formato1($this->data['shp100_patente']['activ_mont_aforo']);
   	$total_aforo_anual      = $this->Formato1($this->data['shp100_patente']['total_aforo']);
   	$sql ="INSERT INTO shd100_patente_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_actividad, numero_aforos, monto_aforo_anual, total_aforo_anual)";
					   $sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."', '".$cod_actividad."', '".$numero_aforos."', '".$monto_aforo_anual."', '".$total_aforo_anual."');";
					   $sw1 = $this->shd100_patente_actividades->execute($sql);

echo'<script>';
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('actv_num_afor').value        = ''; ";
       echo" document.getElementById('activ_mont_aforo').value     = ''; ";
       echo" document.getElementById('total_aforo').value          = ''; ";
echo'</script>';
$datos=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' ",null,'cod_actividad ASC',null,null,null);

$mmens= 0;
$msfre= 0;

if(isset($datos) and $datos!=null){
$to=0;
	foreach($datos as $sa){
		$total_aforo= $sa['v_shd100_patente_actividades']['total_aforo_anual'];
			$to= $to + $total_aforo;
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;


echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}



$this->set("accion", $datos);





}//fin function

function eliminar_g($var1=null,$var2=null,$var3=null){
	$this->layout = "ajax";
	$sql ="rif_cedula='".$var1."' and cod_actividad='".$var2."'";
	$sql1 ="DELETE  FROM  shd100_patente_actividades where ".$sql." and ".$this->SQLCA();
	$this->shd100_patente_actividades->execute($sql1);
	$datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$var1."'",null,'cod_actividad ASC',null,null,null);
    $this->set('accion',$datos2);
$mmens= 0;
$msfre= 0;

if(isset($datos2) and $datos2!=null){
$to=0;
	foreach($datos2 as $sa){
		$total_aforo= $sa['v_shd100_patente_actividades']['total_aforo_anual'];
			$to= $to + $total_aforo;
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;


echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}


}



function eliminar_grilla($var1=null){

$this->layout = "ajax";
$_SESSION["DATOS"][$var1]["activa"] = 0;
$this->set("errorMessage", "EL REGISTRO FUE ELIMINADO");

$cont  = $_SESSION["CUENTA"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for

echo'<script>';
       echo" document.getElementById('cuenta_grilla').value = '".$marca."'; ";
echo'</script>';

$mmens= 0;
$msfre= 0;

if(isset($_SESSION["DATOS"]) and $_SESSION["DATOS"]!=null){
$to=0;
	foreach($_SESSION["DATOS"] as $sa){
		$activa= $sa['activa'];
		$total_aforo= $this->Formato1($sa['total_aforos']);
		if($activa==1){
			$to= $to + $total_aforo;
		}
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;


echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}
$this->set("accion", $_SESSION["DATOS"]);

}//fin function










function selecionar_constribuyente($var1=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";
  	$this->Session->write('rif_cedula', $var1);
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

$var1 = strtoupper($var1);
$var2 = strtoupper($var2);
if($var2==null){
	   $accion   = $this->v_shd100_solicitud->findAll($condicion." and upper(rif_cedula)='".$var1."' ", null, "rif_cedula asc");
}else{
       $accion   = $this->v_shd100_solicitud->findAll($condicion." and upper(rif_cedula)='".$var1."' and numero_solicitud='".$var2."' ", null, "rif_cedula asc");
    }//fin else

if($accion != null){
foreach($accion as $ve){
$rif=$ve['v_shd100_solicitud']['rif_cedula'];
$estado_civil=$ve['v_shd100_solicitud']['estado_civil'];
$nacionalidad=$ve['v_shd100_solicitud']['nacionalidad_razon'];
$fecha_solicitud=cambiar_formato_fecha($ve['v_shd100_solicitud']['fecha_solicitud']);
$deno_centro=$ve['v_shd100_solicitud']['deno_centro_razon'];
if($deno_centro==''){
	$deno_centro='N/A';
}
$deno_calle=$ve['v_shd100_solicitud']['deno_vialidad_razon'];
if($deno_calle==''){
	$deno_calle='N/A';
}
$deno_vereda=$ve['v_shd100_solicitud']['deno_vereda_razon'];
if($deno_vereda==''){
	$deno_vereda='N/A';
}
     	      if($estado_civil==1){ $estado_civil = "Soltero";
		}else if($estado_civil==2){ $estado_civil = "Casado";
		}else if($estado_civil==3){ $estado_civil = "Divorciado";
		}else if($estado_civil==4){ $estado_civil = "Viudo";
		}else if($estado_civil==5){ $estado_civil = "Otro"; }

		      if($nacionalidad==2){ $nacionalidad = "Extranjera";
		}else if($nacionalidad==1){ $nacionalidad = "Venezolana"; }
         echo'<script>';
         		  echo" document.getElementById('rif_constribuyente').value   = ''; ";
         		  echo" document.getElementById('rif_constribuyente').value   = '".$rif."'; ";
                  echo" document.getElementById('numero_solicitud').value   = '".$ve['v_shd100_solicitud']['numero_solicitud']."'; ";
                  echo" document.getElementById('fecha_solicitud').value = '".$fecha_solicitud."'; ";
                  echo" document.getElementById('deno_comercial').value       =  '".$ve['v_shd100_solicitud']['razon_social_nombres']."'; ";
                  echo" document.getElementById('cod_pais').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['pais_razon'])."'; ";
                  echo" document.getElementById('deno_pais').value       =  '".$ve['v_shd100_solicitud']['deno_pais_razon']."'; ";
                  echo" document.getElementById('fecha_inscripcion').value       =  '".cambiar_formato_fecha($ve['v_shd100_solicitud']['fecha_inscripcion'])."'; ";
                  echo" document.getElementById('cod_estado').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['estado_razon'])."'; ";
                  echo" document.getElementById('deno_estado').value       =  '".$ve['v_shd100_solicitud']['deno_estado_razon']."'; ";
                  echo" document.getElementById('telefonos_fijos').value       =  '".$ve['v_shd100_solicitud']['telefonos_fijos_razon']."'; ";
                  echo" document.getElementById('cod_municipio').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['municipio_razon'])."'; ";
                  echo" document.getElementById('deno_municipio').value       =  '".$ve['v_shd100_solicitud']['deno_municipio_razon']."'; ";
                  echo" document.getElementById('telefonos_celulares').value       =  '".$ve['v_shd100_solicitud']['telefonos_celulares_razon']."'; ";
                  echo" document.getElementById('cod_parroquia').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['parroquia_razon'])."'; ";
                  echo" document.getElementById('deno_parroquia').value       =  '".$ve['v_shd100_solicitud']['deno_parroquia_razon']."'; ";
                  echo" document.getElementById('correo').value       =  '".$ve['v_shd100_solicitud']['correo_electronico_razon']."'; ";
                  echo" document.getElementById('cod_centro').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['centro_razon'])."'; ";
                  echo" document.getElementById('deno_centro').value       =  '".$ve['v_shd100_solicitud']['deno_centro_razon']."'; ";
                  echo" document.getElementById('nacionalidad').value       =  '".$nacionalidad."'; ";
                  echo" document.getElementById('cod_calle').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['calle_razon'])."'; ";
                  echo" document.getElementById('deno_calle').value       =  '".$deno_calle."'; ";
                  echo" document.getElementById('cod_vereda').value       =  '".$this->AddCeroR($ve['v_shd100_solicitud']['vereda_razon'])."'; ";
                  echo" document.getElementById('deno_vereda').value       =  '".$deno_vereda."'; ";
                  echo" document.getElementById('estado_civil').value       =  '".$estado_civil."'; ";
                  echo" document.getElementById('numero_casa').value       =  '".$ve['v_shd100_solicitud']['numero_local_razon']."'; ";
                  echo" document.getElementById('profesion').value       =  '".$ve['v_shd100_solicitud']['deno_profesion']."'; ";


        echo'</script>';
}

}else if($accion == null){
	echo'<script>';
         		  echo" document.getElementById('rif_constribuyente').value   	= ''; ";
         		  echo" document.getElementById('rif_constribuyente').value   	= ''; ";
                  echo" document.getElementById('numero_solicitud').value   	= ''; ";
                  echo" document.getElementById('fecha_solicitud').value 		= ''; ";
                  echo" document.getElementById('deno_comercial').value       	=  ''; ";
                  echo" document.getElementById('cod_pais').value       		=  ''; ";
                  echo" document.getElementById('deno_pais').value       		=  ''; ";
                  echo" document.getElementById('fecha_inscripcion').value      =  ''; ";
                  echo" document.getElementById('cod_estado').value       		=  ''; ";
                  echo" document.getElementById('deno_estado').value       		=  ''; ";
                  echo" document.getElementById('telefonos_fijos').value       	=  ''; ";
                  echo" document.getElementById('cod_municipio').value       	=  ''; ";
                  echo" document.getElementById('deno_municipio').value       	=  ''; ";
                  echo" document.getElementById('telefonos_celulares').value    =  ''; ";
                  echo" document.getElementById('cod_parroquia').value       	=  ''; ";
                  echo" document.getElementById('deno_parroquia').value       	=  ''; ";
                  echo" document.getElementById('correo').value       			=  ''; ";
                  echo" document.getElementById('cod_centro').value       		=  ''; ";
                  echo" document.getElementById('deno_centro').value       		=  ''; ";
                  echo" document.getElementById('nacionalidad').value       	=  ''; ";
                  echo" document.getElementById('cod_calle').value       		=  ''; ";
                  echo" document.getElementById('deno_calle').value       		=  ''; ";
                  echo" document.getElementById('cod_vereda').value       		=  ''; ";
                  echo" document.getElementById('deno_vereda').value       		=  ''; ";
                  echo" document.getElementById('estado_civil').value       	=  ''; ";
                  echo" document.getElementById('numero_casa').value       		=  ''; ";
                  echo" document.getElementById('profesion').value       		=  ''; ";
        echo'</script>';
}
$this->render("funcion");
 }//fn function



function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd100_solicitud->findCount($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_solicitud->findAll($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd100_solicitud->findCount($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var22%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd100_solicitud->findAll($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var22%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))",null,"rif_cedula ASC",50,$pagina,null);
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












function guardar($var1=null, $var2=null){//pr($this->data);

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $numero_solicitud        =  $this->data['shp100_patente']['numero_solicitud'];
  $numero_patente          =  str_replace(" ","",$this->data['shp100_patente']['numero_patente']);
  $frecuencia_pago         =  $this->data['shp100_patente']['frecuencia_pago'];
  $monto_mensual           =     $this->Formato1($this->data['shp100_patente']['monto_mensual']);
  $pago_todo               =  $this->data['shp100_patente']['pago_todo'];
  $suspendido              =  $this->data['shp100_patente']['suspendido'];
  $rif_ci_cobrador         =  $this->data['shp100_patente']['rif_cobrador'];
  $fecha_patente           =  $this->data['shp100_patente']['fecha_patente'];
  $rif_constribuyente      =  $this->data['shp100_patente']['rif_constribuyente'];
  $numero_expediente       =  $this->data['shp100_patente']['numero_expediente'];




 $sql =" BEGIN; INSERT INTO shd100_patente ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, numero_patente, frecuencia_pago, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado, fecha_ultima_decla, ingresos_declarados, ultimo_ejercicio_decla, periodo_desde, periodo_hasta, fecha_patente,numero_expediente)";
 $sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_constribuyente."', '".$numero_solicitud."', '".$numero_patente."', '".$frecuencia_pago."', '".$monto_mensual."', '".$pago_todo."', '".$suspendido."', '".$rif_ci_cobrador."', 0, 0,'1900-01-01', 0, 0,'1900-01-01', '1900-01-01', '".$fecha_patente."', '".$numero_expediente."');";
 $sw = $this->shd100_patente->execute($sql);

  if($sw>1){
//pr($_SESSION["DATOS"]);
           $cont  = $_SESSION["CUENTA"];

			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS"][$i]["activa"]==1){
                       $cod_actividad     = $_SESSION["DATOS"][$i]["cod_actividad"];
					   $numero_aforos     = $this->Formato1($_SESSION["DATOS"][$i]["numero_aforos"]);
					   $monto_aforo_anual = $this->Formato1($_SESSION["DATOS"][$i]["monto_aforos"]);
					   $total_aforo_anual = $this->Formato1($_SESSION["DATOS"][$i]["total_aforos"]);
					   $sql ="INSERT INTO shd100_patente_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_actividad, numero_aforos, monto_aforo_anual, total_aforo_anual)";
					   $sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_constribuyente."', '".$cod_actividad."',$numero_aforos, $monto_aforo_anual,$total_aforo_anual);";

					   $sw1 = $this->shd100_patente_actividades->execute($sql);
					   //echo $sql;
					   if($sw1>1){}else{break;}
			    }//fin if
			 }//fin for

			 if($sw1>1){

			 	  $sw3 = $this->shd100_patente_actividades->execute("UPDATE shd100_solicitud set numero_patente='".$numero_patente."'  where ".$condicion." and numero_solicitud='".$numero_solicitud."'  ");
			 	  $this->shd100_patente_actividades->execute("COMMIT;");
			 	  $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
			 }else{
			 	 $this->shd100_patente_actividades->execute("ROLLBACK;");
                 $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
			 }//fin else

  }else{
    $this->shd100_patente_actividades->execute("ROLLBACK;");
    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
  }//fin else

$this->index();
$this->render("index");

}//fin function









function eliminar($numero_patente=null, $rif_cedula=null, $pagina=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $numero_solicitud        =  $this->data['shp100_patente']['numero_solicitud'];
  $R1  = $this->shd100_patente_actividades->execute(" DELETE FROM shd100_patente_actividades WHERE ".$condicion." and rif_cedula='".$rif_cedula."'  ");
  $R1  = $this->shd100_patente->execute("             DELETE FROM shd100_patente             WHERE ".$condicion." and rif_cedula='".$rif_cedula."' and numero_solicitud='".$numero_solicitud."' and numero_patente='".$numero_patente."' ");
  $sw3 = $this->shd100_solicitud->execute("UPDATE shd100_solicitud set numero_patente='0'  where ".$condicion." and numero_solicitud='".$numero_solicitud."'  ");

  $y=$this->shd100_patente->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
 	if($y!=0){
	  	 $this->set('Message_existe', 'Registro Eliminado con exito.');
      	 $this->consulta($pagina);//si es el primero solamente
      $this->render("consulta");

		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if

}//fin function

function buscar_patente($var1=null, $var2=null, $var3=null, $var4=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
				if($var1==1){
				}else{

                    $var2 = strtoupper($var2);
                    $accion_cont = 0;
                    $accion_cont = $this->shd100_solicitud->findCount($condicion." and numero_patente!='0' and  upper(rif_cedula) LIKE '%$var2%'", null, "rif_cedula asc");
                    $accion      = $this->shd100_solicitud->findAll($condicion." and (numero_patente!='0' or numero_patente is null) and  upper(rif_cedula) LIKE '%$var2%'", null, "rif_cedula asc");
                    $accion2     = $this->shd001_registro_contribuyentes->findAll(" upper(rif_cedula) LIKE '%$var2%'", null, "rif_cedula asc");

			        $this->set('accion', $accion);
			        $this->set('accion2', $accion2);
			        if($accion_cont==0){$this->set('errorMessage', "no existen datos");}
				}//fin function




$this->set("opcion",$var1);

}//fin function










function selecion_actividad($var=null){

$this->layout = "ajax";$pago_todo               =  $this->data['shp100_patente']['pago_todo'];
  $suspendido              =  $this->data['shp100_patente']['suspendido'];
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


$shd100_actividades_aux = $this->shd100_actividades->findAll($condicion." and cod_actividad = '".$var."'");

$var1 = "";

foreach($shd100_actividades_aux as $ve){
	$var1 = $ve["shd100_actividades"]["denominacion_actividad"];
	$var2 = $ve["shd100_actividades"]["minimo_tributable"];
}//fin foreach
       echo'<script>';
               //echo" document.getElementById('num_12').options[0].text  = ''; ";
               echo" document.getElementById('num_12').value        = '".$var."'; ";
               echo" document.getElementById('activ_deno').value        = '".$var1."'; ";
               echo" document.getElementById('activ_mont_aforo').value        = '".$var2."'; ";
               echo" document.getElementById('actv_num_afor').value        = ''; ";
      echo'</script>';


$this->render("funcion");



}//fin fucntion



function selecion_cobrador($var=null){

$this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


$shd002_cobradores_aux = $this->shd002_cobradores->findAll($condicion." and rif_ci = '".$var."'");

$var1 = "";
$var2 = "";

foreach($shd002_cobradores_aux as $ve){
	$var1 = $ve["shd002_cobradores"]["rif_ci"];
	$var2 = $ve["shd002_cobradores"]["nombre_razon"];
}//fin foreach

       echo'<script>';
               //echo" document.getElementById('num_1').options[0].text  = ''; ";
               //echo" document.getElementById('rif_cobrador').value   = '".$var1."'; ";
               echo" document.getElementById('cobrador').value = '".$var1."'; ";
               echo" document.getElementById('deno_cobrador').value = '".$var2."'; ";
      echo'</script>';


$this->render("funcion");



}//fin fucntion


function consulta($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd100_patente->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }else{
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_patente->findAll($this->SQLCA(),null,'numero_patente ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$numero_patente  = $row['v_shd100_patente']['numero_patente'];
          	 	$rif_cedula      = $row['v_shd100_patente']['rif_cedula'];
          	 }
			 $datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'cod_actividad ASC',null,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
          	 }
 }else{
 	$pagina=1;
          	 $this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd100_patente->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }else{
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_patente->findAll($this->SQLCA(),null,'numero_patente ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$numero_patente  = $row['v_shd100_patente']['numero_patente'];
          	 	$rif_cedula      = $row['v_shd100_patente']['rif_cedula'];
          	 }
			 $datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'cod_actividad ASC',null,$pagina,null);
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
         }
}//fin function consultar2
}

function modificar($numero_patente=null,$pagina=null){
	$this->layout = "ajax";
	//echo $this->SQLCA()." and numero_patente='".$numero_patente."'";
		$datos=$this->v_shd100_patente->findAll($this->SQLCA()." and numero_patente='".$numero_patente."'", null,'numero_patente ASC',1,null,null);
	//	pr($datos);
		$frecu     =$datos[0]['v_shd100_patente']['frecuencia_pago'];
		$rif_cedula=$datos[0]['v_shd100_patente']['rif_cedula'];
		$this->Session->write('frecu',$frecu);
		$datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'cod_actividad ASC');
        $this->set('datos',$datos);
        $this->set('datos2',$datos2);
		$this->set('pagina',$pagina);
		$this->set("lista_cobrador", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
 		$this->concatena_sin_cero($this->shd100_actividades->generateList($this->SQLCA().' and minimo_tributable!=0', "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "lista_actividades");

}






function numero_expediente($var=null){

	$this->layout = "ajax";

	if($var==1){
        $datos = $this->v_shd100_patente->findAll($this->SQLCA(),null,'numero_expediente DESC',1,1,null);
        $num   = isset($datos[0]["v_shd100_patente"]["numero_expediente"])?$datos[0]["v_shd100_patente"]["numero_expediente"]+1:1;
	}else{
        $num = "";
	}

	$this->set("numero", $num);
    $this->set("opcion", $var);

}//fin function






function guardar_modificar($numero_solicitud=null,$numero_patente=null,$pagina=null){

//pr($this->data);
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  	$frecuencia_pago         =  $this->data['shp100_patente']['frecuencia_pago'];
  	$monto_mensual           =     $this->Formato1($this->data['shp100_patente']['monto_mensual']);

  	$pago_todo               =  isset($this->data['shp100_patente']['pago_todo'])  ? $this->data['shp100_patente']['pago_todo']:'1';
  	$suspendido              =  isset($this->data['shp100_patente']['suspendido']) ? $this->data['shp100_patente']['suspendido']:'2';

  	$rif_ci_cobrador         =  $this->data['shp100_patente']['rif_cobrador'];
  	$ultimo_ano_facturado    =  $this->data['shp100_patente']['ultimo_year_cancelado'];
  	$ultimo_mes_facturado    =  $this->data['shp100_patente']['ultimo_mes_facturado'];
  	$fecha_ultima_decla      =  $this->data['shp100_patente']['fecha_ultima_declaracion'];
//  	$ingresos_declarados     =      $this->Formato1($this->data['shp100_patente']['ingresos_declarados']);
  	$ultimo_ejercicio_decla  =  $this->data['shp100_patente']['ultimo_ejercicio_declarado'];
  	$periodo_desde           =  $this->data['shp100_patente']['periodo_declaracion_desde'];
  	$periodo_hasta           =  $this->data['shp100_patente']['periodo_declaracion_hasta'];
  	$fecha_patente           =  $this->data['shp100_patente']['fecha_patente'];
	$fecha_ultima_decla = "1900-1-1";
	$periodo_desde      = "1900-1-1";
	$periodo_hasta      = "1900-1-1";
 	$sql ="update shd100_patente set fecha_patente='".$fecha_patente."', rif_ci_cobrador='".$rif_ci_cobrador."',  frecuencia_pago='".$frecuencia_pago."',  pago_todo='".$pago_todo."',  suspendido='".$suspendido."',  monto_mensual=$monto_mensual  where ".$condicion." and numero_solicitud='".$numero_solicitud."' and numero_patente='".$numero_patente."' ";
 	//echo $sql;
 	$sw = $this->shd100_patente->execute($sql);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->consulta($pagina);
	$this->render("consulta");
}//fin function


function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";//echo 'si2';
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_shd100_patente->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (numero_patente LIKE '%$var2%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var2%'))))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_patente->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (numero_patente LIKE '%$var2%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var2%'))))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{//echo 'aa';
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))";
						$Tfilas=$this->v_shd100_patente->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (numero_patente LIKE '%$var22%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var22%'))))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    //$datos_filas=$this->v_shd100_solicitud->findAll("((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))",1,1,null);
							        $datos_filas=$this->v_shd100_patente->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (numero_patente LIKE '%$var22%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var22%'))))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function consulta2($rif_cedula=null,$numero_solicitud=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$rif_cedula."' and numero_patente='".$numero_solicitud."'";
    $veri=$this->v_shd100_patente->findCount($this->SQLCA().' and '.$c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd100_patente->findAll($this->SQLCA().' and '.$c);
      	$datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' ",null,'cod_actividad ASC',null,null,null);
      	$this->set('datos',$datacpcp01);
      	$this->set('datos2',$datos2);
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2


function consulta3($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd100_patente->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }else{
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_patente->findAll($this->SQLCA(),null,'numero_patente ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$numero_patente  = $row['v_shd100_patente']['numero_patente'];
          	 	$rif_cedula      = $row['v_shd100_patente']['rif_cedula'];
          	 }
			 $datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'cod_actividad ASC',null,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
          	 }
 }else{
 	$pagina=1;
          	 $this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd100_patente->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }else{
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd100_patente->findAll($this->SQLCA(),null,'numero_patente ASC',1,$pagina,null);
          	 foreach($datos as $row){
          	 	$numero_patente  = $row['v_shd100_patente']['numero_patente'];
          	 	$rif_cedula      = $row['v_shd100_patente']['rif_cedula'];
          	 }
			 $datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'cod_actividad ASC',null,$pagina,null);
          	 $this->set('datos2',$datos2);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
         }
}//fin function consultar2
}

function vacio ($msj,$tipo) {
   $this->layout="ajax";
   $this->set($tipo,$msj);

}//fin funcion vacio



function grilla($numero_solicitud=null){
	$this->layout='ajax';
	//echo $this->SQLCA()." and numero_solicitud='".$numero_solicitud."'";
	$acti  = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA()." and numero_solicitud='".$numero_solicitud."'",null,'cod_actividad ASC');
	//pr($acti);
	$num_acti = $this->v_shd100_solicitud_actividades->findCount($this->SQLCA()." and numero_solicitud='".$numero_solicitud."'");
	$this->Session->write('conta_acti', $num_acti);
	$this->set('acti',$acti);
}

function agregar_grilla($i){

$this->layout = "ajax";
   	$cod_actividad		= $this->data['shp100_patente']['cod_actividad_'.$i];
   	$numero_aforos		    = $this->data['shp100_patente']['numero_aforos_'.$i];
   	$monto_aforos       	= $this->data['shp100_patente']['monto_aforos_'.$i];
   	$total_aforos    		= $this->data['shp100_patente']['total_aforos_'.$i];
	$deno				= $this->shd100_actividades->findAll("cod_actividad='".$cod_actividad."' and ".$this->SQLCA());

if($numero_aforos ==null){

	$this->set('xxx',false);
	$num_acti = $this->Session->read('conta_acti');
	$this->set('conta_acti',$num_acti);
	$this->set("accion", $_SESSION["DATOS"]);
	$this->set("errorMessage", "INSERTE EL NUMERO DE AFOROS");
}else{


			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["numero_aforos"]       = $numero_aforos;
	              $_SESSION["DATOS"][$cont]["monto_aforos"]       = $monto_aforos;
	              $_SESSION["DATOS"][$cont]["total_aforos"]    = $total_aforos;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;
	              $num_acti = $this->Session->read('conta_acti');
				  $num_acti = $num_acti - 1;
				  $this->Session->write('conta_acti', $num_acti);
				  $this->set('conta_acti',$num_acti);
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{

                  $cont  = $_SESSION["CUENTA"];
                  $marca = 0;

		              for($i=1; $i<=$cont; $i++){
		                   if($cod_actividad==$_SESSION["DATOS"][$i]["cod_actividad"]  &&  $_SESSION["DATOS"][$i]["activa"]==1){
                               $marca=1;
		                   }//fin if
		              }//fin for
	                 if($marca==1){
	                 	$num_acti = $this->Session->read('conta_acti');
						$this->set('conta_acti',$num_acti);
                           $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	                 }else{
                            $cont = $_SESSION["CUENTA"];  $cont++; $_SESSION["CUENTA"] = $cont;
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["numero_aforos"]       = $numero_aforos;
	              $_SESSION["DATOS"][$cont]["monto_aforos"]       = $monto_aforos;
	              $_SESSION["DATOS"][$cont]["total_aforos"]    = $total_aforos;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;
				            $num_acti = $this->Session->read('conta_acti');
				  			$num_acti = $num_acti - 1;
				  			$this->Session->write('conta_acti', $num_acti);
				  			$this->set('conta_acti',$num_acti);
                           $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else
/*
echo'<script>';
       echo" document.getElementById('cod_actividad').value     = ''; ";
       echo" document.getElementById('alicuota2').value     = ''; ";
       echo" document.getElementById('activ_deno').value           = ''; ";
       echo" document.getElementById('ingresos').value           = ''; ";
       echo" document.getElementById('impuestos').value           = ''; ";
       echo" document.getElementById('ingresos').disabled           = true; ";
echo'</script>';
*/
$this->set('i',$i);
$this->set("accion", $_SESSION["DATOS"]);
	$this->set('xxx',true);

}

$mmens= 0;
$msfre= 0;

//pr($_SESSION["DATOS"]);

if(isset($_SESSION["DATOS"]) and $_SESSION["DATOS"]!=null){
$to=0;
	foreach($_SESSION["DATOS"] as $sa){
		$activa= $sa['activa'];
		$total_aforo= $this->Formato1($sa['total_aforos']);
		if($activa==1){
			$to= $to + $total_aforo;
		}
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;


echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}

}//fin function

function agregar_grilla2(){

$this->layout = "ajax";
   	$cod_actividad		= $this->data['shp100_patente']['cod_actividad'];
   	$numero_aforos		    = $this->data['shp100_patente']['numero_aforos'];
   	$monto_aforos       	= $this->data['shp100_patente']['monto_aforos'];
   	$total_aforos    		= $this->data['shp100_patente']['total_aforos'];
	$deno				= $this->shd100_actividades->findAll("cod_actividad='".$cod_actividad."' and ".$this->SQLCA());

			if(!isset($_SESSION["DATOS"])){
	              $_SESSION["CUENTA"] = 1;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["numero_aforos"]       = $numero_aforos;
	              $_SESSION["DATOS"][$cont]["monto_aforos"]       = $monto_aforos;
	              $_SESSION["DATOS"][$cont]["total_aforos"]    = $total_aforos;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;
	              $num_acti = $this->Session->read('conta_acti');
				  $num_acti = $num_acti - 1;
				  $this->Session->write('conta_acti', $num_acti);
				  $this->set('conta_acti',$num_acti);
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{

                  $cont  = $_SESSION["CUENTA"];
                  $marca = 0;

		              for($i=1; $i<=$cont; $i++){
		                   if($cod_actividad==$_SESSION["DATOS"][$i]["cod_actividad"]  &&  $_SESSION["DATOS"][$i]["activa"]==1){
                               $marca=1;
		                   }//fin if
		              }//fin for
	                 if($marca==1){
	                 	$num_acti = $this->Session->read('conta_acti');
						$this->set('conta_acti',$num_acti);
                           $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	                 }else{
                            $cont = $_SESSION["CUENTA"];  $cont++; $_SESSION["CUENTA"] = $cont;
	              $cont               = $_SESSION["CUENTA"];
	              $_SESSION["DATOS"][$cont]["cod_actividad"]    = $cod_actividad;
	              $_SESSION["DATOS"][$cont]["deno_actividad"]    = $deno[0]['shd100_actividades']['denominacion_actividad'];
	              $_SESSION["DATOS"][$cont]["numero_aforos"]       = $numero_aforos;
	              $_SESSION["DATOS"][$cont]["monto_aforos"]       = $monto_aforos;
	              $_SESSION["DATOS"][$cont]["total_aforos"]    = $total_aforos;
	              $_SESSION["DATOS"][$cont]["activa"]           = 1;
	              $_SESSION["DATOS"][$cont]["id"]               = $cont;
				            $num_acti = $this->Session->read('conta_acti');
				  			$num_acti = $num_acti - 1;
				  			$this->Session->write('conta_acti', $num_acti);
				  			$this->set('conta_acti',$num_acti);
                           $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else


$mmens= 0;
$msfre= 0;

//pr($_SESSION["DATOS"]);

if(isset($_SESSION["DATOS"]) and $_SESSION["DATOS"]!=null){
$to=0;
	foreach($_SESSION["DATOS"] as $sa){
		$activa= $sa['activa'];
		$total_aforo= $this->Formato1($sa['total_aforos']);
		if($activa==1){
			$to= $to + $total_aforo;
		}
	}

$frecu = $this->Session->read('frecu');
if($frecu==1){$fre=1;}
if($frecu==2){$fre=2;}
if($frecu==3){$fre=3;}
if($frecu==4){$fre=6;}
if($frecu==5){$fre=12;}

$to=$this->Formato2($to);
$to=$this->Formato1($to);
$mmens= ($to/12);
$mmens=$this->Formato2($mmens);
$mmens=$this->Formato1($mmens);
$msfre= $mmens * $fre;


echo'<script>';
       echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
       echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
echo'</script>';

}
echo'<script>';
       echo" document.getElementById('activ_deno').value     = ''; ";
       echo" document.getElementById('actv_num_afor').value     = ''; ";
       echo" document.getElementById('activ_mont_aforo').value           = ''; ";
       echo" document.getElementById('total_aforo').value           = ''; ";
echo'</script>';




$this->set("accion", $_SESSION["DATOS"]);


}//fin function

function buscar3($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista3($var1=null, $var2=null, $var3=null){
$this->layout="ajax";//echo 'si2';
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_shd100_patente->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (numero_patente LIKE '%$var2%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var2%'))))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_patente->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var2%') or (numero_patente LIKE '%$var2%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var2%'))))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{//echo 'aa';
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))";
						$Tfilas=$this->v_shd100_patente->findCount($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (numero_patente LIKE '%$var22%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var22%'))))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    //$datos_filas=$this->v_shd100_solicitud->findAll("((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))",1,1,null);
							        $datos_filas=$this->v_shd100_patente->findAll($this->SQLCA()." and (((rif_cedula LIKE '%$var22%') or (numero_patente LIKE '%$var22%') or ( quitar_acentos(deno_razon) LIKE  quitar_acentos('%$var22%'))))",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);//pr($datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function consulta4($rif_cedula=null,$numero_solicitud=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$rif_cedula."' and numero_patente='".$numero_solicitud."'";
    $veri=$this->v_shd100_patente->findCount($this->SQLCA().' and '.$c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd100_patente->findAll($this->SQLCA().' and '.$c);
      	$datos2=$this->v_shd100_patente_actividades->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' ",null,'cod_actividad ASC',null,null,null);
      	$this->set('datos',$datacpcp01);
      	$this->set('datos2',$datos2);
      }else{
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
}//fin function consultar2

function buscar_actividadx($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pistax($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->shd100_actividades->findCount($this->SQLCA()." and minimo_tributable!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->shd100_actividades->findAll($this->SQLCA()." and minimo_tributable!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))",null,"cod_actividad ASC",50,1,null);
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
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->shd100_actividades->findCount($this->SQLCA()." and minimo_tributable!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->shd100_actividades->findAll($this->SQLCA()." and minimo_tributable!=0 and ((cod_actividad LIKE '%$var2%') or (quitar_acentos(denominacion_actividad) LIKE quitar_acentos('%$var2%')))",null,"cod_actividad ASC",50,$pagina,null);
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


}//fin class ?>