<?php
class Ccnp01ConcejoComunalController extends AppController {
   var $name = 'ccnp01_concejo_comunal';
   var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos','cugd01_republica','cugd01_estados','cugd01_municipios',
                     'cugd01_parroquias','cugd01_centropoblados', 'ccnd01_concejo_comunal', 'ccnd01_directiva', 'v_ccnd01_directiva','v_concejo_comunales');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
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
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
 }


 function index(){
 	$this->layout ="ajax";
	$zonificacion= array('1'=>'Urbanización','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
	$this->concatena($zonificacion, 'zonificacion');
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));


	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$lista_republica =  $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
	$this->concatena($lista_republica, 'lista_republica');





 }// fin index


function mostrar($opcion=null,$var=null){
	$this->layout ="ajax";
	if($opcion=='cod'){
		$this->set('codigo',$var);
		$this->set('opcion',$opcion);
		echo "<script>
		 	document.getElementById('save').disabled=false;
		 	document.getElementById('pasos').value='';
			document.getElementById('dias').value='';
		 </script>";
	}else{
		$documentos= array('1'=>'UNIDAD ADMINISTRATIVA Y FINANCIERA COMUNITARIO','2'=>'COMITÉ DE SALUD','3'=>'COMITÉ DE EDUCACIÓN Y FORMACION CIUDADANA','4'=>'COMITÉ DE TIERRA URBANA O RURAL',
						'5'=>'COMITÉ DE VIVIENDA Y HABITÁT','6'=>'COMITÉ DE PROTECCIÓN SOCIAL DE NIÑOS, NIÑAS Y ADOLESCENTES','7'=>'COMITÉ DE ECONOMIA COMUNAL',
						'8'=>'COMITÉ DE FAMILIA E IGUALDAD DE GENERO','9'=>'COMITÉ DE SEGURIDAD Y DEFENSA INTEGRAL','10'=>'COMITÉ DE MEDIOS ALTERNATIVOS COMUNITARIOS',
						'11'=>'COMITÉ DE RECREACIÓN Y DEPORTES','12'=>'COMITÉ DE ALIMENTACIÓN Y DEFENSA DEL CONSUMIDOR','13'=>'COMITÉ DE MESA TÉCNICA DE AGUA','14'=>'COMITÉ DE MESA TÉCNICA DE ENERGÍA Y GAS',
						'15'=>'COMITÉ COMUNITARIO DE PERSONAS CON DISCAPACIDAD','16'=>'UNIDAD DE CONTRALORIA SOCIAL',);

		$this->set('denominacion',$documentos[$var]);
		$this->set('opcion',$opcion);

	}



}//fin mostrar















function select3($opcion=null,$var=null){
	$this->layout="ajax";

	if($var!=''){
		switch($opcion){
			case 'estado':
				$this->set('no','');
				$this->set('SELECT','municipio');
				$this->set('codigo','estado');
				$this->set('seleccion','');
				$this->set('n',2);

					$this->Session->write('cod1',$var);
					$cond =" cod_republica=".$var;

				$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');


				           echo "<script>";
							echo "document.getElementById('poblacion').value='';";
							echo "document.getElementById('orientacion').value='';";
							echo "document.getElementById('ambito').value='';";
							echo "document.getElementById('dimension').value='';";
							echo "document.getElementById('caracteristicas').value='';";
							echo "document.getElementById('economia').value='';";
							echo "document.getElementById('limites').value='';";
							echo "document.getElementById('cod_concejo').value='';";
						 echo "</script>";
			break;
			case 'municipio':
				$this->set('no','');
				$this->set('SELECT','parroquia');
				$this->set('codigo','municipio');
				$this->set('seleccion','');
				$this->set('n',3);

					$cod1 = $this->Session->read('cod1');
					$this->Session->write('cod2',$var);
					$cond =" cod_republica=".$cod1." and cod_estado=".$var;

				$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');


				        echo "<script>";
							echo "document.getElementById('poblacion').value='';";
							echo "document.getElementById('orientacion').value='';";
							echo "document.getElementById('ambito').value='';";
							echo "document.getElementById('dimension').value='';";
							echo "document.getElementById('caracteristicas').value='';";
							echo "document.getElementById('economia').value='';";
							echo "document.getElementById('limites').value='';";
							echo "document.getElementById('cod_concejo').value='';";
						 echo "</script>";

			break;
			case 'parroquia':
				$this->set('no','');
				$this->set('SELECT','centro_poblado');
				$this->set('codigo','parroquia');
				$this->set('seleccion','');
				$this->set('n',4);

					$cod1=$this->Session->read('cod1');
					$cod2=$this->Session->read('cod2');
					$this->Session->write('cod3',$var);
					$cond =" cod_republica=".$cod1." and cod_estado=".$cod2." and cod_municipio=".$var;

				$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');

				        echo "<script>";
							echo "document.getElementById('poblacion').value='';";
							echo "document.getElementById('orientacion').value='';";
							echo "document.getElementById('ambito').value='';";
							echo "document.getElementById('dimension').value='';";
							echo "document.getElementById('caracteristicas').value='';";
							echo "document.getElementById('economia').value='';";
							echo "document.getElementById('limites').value='';";
							echo "document.getElementById('cod_concejo').value='';";
						 echo "</script>";
			break;
			case 'centro_poblado':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','concejo_comunal');
				$this->set('codigo','centro_poblado');
				$this->set('deno','');
				$this->set('n',5);

					$cod1=$this->Session->read('cod1');
					$cod2=$this->Session->read('cod2');
					$cod3=$this->Session->read('cod3');
					$this->Session->write('cod4',$var);
					$cond =" cod_republica=".$cod1." and cod_estado=".$cod2." and cod_municipio=".$cod3." and cod_parroquia=".$var;

				$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				$this->concatena($lista, 'vector');

				        echo "<script>";
							echo "document.getElementById('poblacion').value='';";
							echo "document.getElementById('orientacion').value='';";
							echo "document.getElementById('ambito').value='';";
							echo "document.getElementById('dimension').value='';";
							echo "document.getElementById('caracteristicas').value='';";
							echo "document.getElementById('economia').value='';";
							echo "document.getElementById('limites').value='';";
							echo "document.getElementById('cod_concejo').value='';";
							echo "document.getElementById('cod_concejo').value='';";
						 echo "</script>";

			break;
			case 'concejo_comunal':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','concejo_comunal');
				$this->set('codigo','centro_poblado');
				$this->set('deno_2','');
				$this->set('n',5);

					$cod1=$this->Session->read('cod1');
					$cod2=$this->Session->read('cod2');
					$cod3=$this->Session->read('cod3');
					$cod4=$this->Session->read('cod4');
					$cond =" cod_republica=".$cod1." and cod_estado=".$cod2." and cod_municipio=".$cod3." and cod_parroquia=".$cod4." and cod_centro=".$var;

	                $sql="select * from cugd01_centros_poblados where ".$cond;
					$datos=$this->cugd01_parroquias->execute($sql);


			        $cuenta = $this->ccnd01_concejo_comunal->findCount($cond);
			        $cuenta++;


                     foreach($datos as $ve){
						if($ve[0]["poblacion"]!=0){
							echo "<script>";
								echo "document.getElementById('poblacion').value='".$ve[0]["poblacion"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('poblacion').value='';";
							echo "</script>";
						}
					///////////////////////////////////////////////77
						if($ve[0]["orientacion"]!='0'){
							echo "<script>";
								echo "document.getElementById('orientacion').value='".$ve[0]["orientacion"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('orientacion').value='';";
							echo "</script>";
						}

					//////////////////////////////////////////////////7
						if($ve[0]["clasificacion"]!=0){
							echo "<script>";
								echo "document.getElementById('ambito').value='".$ve[0]["clasificacion"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('ambito').value='';";
							echo "</script>";
						}
					/////////////////////////////////////////////////
						if($ve[0]["dimension"]!='0'){
							echo "<script>";
								echo "document.getElementById('dimension').value='".$ve[0]["dimension"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('dimension').value='';";
							echo "</script>";
						}
					////////////////////////////////////////////////////
						if($ve[0]["caracteristicas"]!='0'){
							echo "<script>";
								echo "document.getElementById('caracteristicas').value='".$ve[0]["caracteristicas"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('caracteristicas').value='';";
							echo "</script>";
						}
					/////////////////////////////////////////////////////
						if($ve[0]["economia"]!='0'){
							echo "<script>";
								echo "document.getElementById('economia').value='".$ve[0]["economia"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('economia').value='';";
							echo "</script>";
						}
					///////////////////////////////////////////////////
						if($ve[0]["limites"]!='0'){
							echo "<script>";
								echo "document.getElementById('limites').value='".$ve[0]["limites"]."';";
							echo "</script>";
						}else{
							echo "<script>";
								echo "document.getElementById('limites').value='';";
							echo "</script>";
						}

						echo "<script>";
							echo "document.getElementById('cod_concejo').value='".mascara_tres($cuenta)."';";
						 echo "</script>";



                     }//fin foreach
			break;




		}//fin switch
	}
}//fin select3








function guardar(){
	$this->layout = "ajax";


if(!empty($this->data['ccnp01_concejo_comunal']['cod_republica'])){
if(!empty($this->data['ccnp01_concejo_comunal']['cod_estado'])){
if(!empty($this->data['ccnp01_concejo_comunal']['cod_municipio'])){
if(!empty($this->data['ccnp01_concejo_comunal']['cod_parroquia'])){
if(!empty($this->data['ccnp01_concejo_comunal']['cod_centro_poblado'])){
if(!empty($this->data['ccnp01_concejo_comunal']['deno_concejo'])){
if(!empty($this->data['ccnp01_concejo_comunal']['zonificacion'])){
if(!empty($this->data['ccnp01_concejo_comunal']['num_electores'])){
if(!empty($this->data['ccnp01_concejo_comunal']['num_votantes'])){
if(!empty($this->data['ccnp01_concejo_comunal']['resultado'])){
if(!empty($this->data['ccnp01_concejo_comunal']['porcentaje'])){
if(!empty($this->data['ccnp01_concejo_comunal']['fecha_inicio'])){
if(!empty($this->data['ccnp01_concejo_comunal']['fecha_terminacion'])){
if($this->data['ccnp01_concejo_comunal']['num_votantes'] <= $this->data['ccnp01_concejo_comunal']['num_electores']){
if($this->data['ccnp01_concejo_comunal']['resultado'] <= $this->data['ccnp01_concejo_comunal']['num_votantes']){




		$cod_republica      = $this->data['ccnp01_concejo_comunal']['cod_republica'];
		$cod_estado         = $this->data['ccnp01_concejo_comunal']['cod_estado'];
		$cod_municipio      = $this->data['ccnp01_concejo_comunal']['cod_municipio'];
		$cod_parroquia      = $this->data['ccnp01_concejo_comunal']['cod_parroquia'];
		$cod_centro_poblado = $this->data['ccnp01_concejo_comunal']['cod_centro_poblado'];
		$cod_concejo        = $this->data['ccnp01_concejo_comunal']['cod_concejo'];
		$deno_concejo       = $this->data['ccnp01_concejo_comunal']['deno_concejo'];
		$zonificacion       = $this->data['ccnp01_concejo_comunal']['zonificacion'];
		$num_electores      = $this->data['ccnp01_concejo_comunal']['num_electores'];
		$num_votantes       = $this->data['ccnp01_concejo_comunal']['num_votantes'];
		$resultado          = $this->data['ccnp01_concejo_comunal']['resultado'];
		$porcentaje         = $this->data['ccnp01_concejo_comunal']['porcentaje'];
		$fecha_inicio       = $this->data['ccnp01_concejo_comunal']['fecha_inicio'];
		$fecha_terminacion  = $this->data['ccnp01_concejo_comunal']['fecha_terminacion'];



  $campos_b = "   cod_republica,
				  cod_estado,
				  cod_municipio,
				  cod_parroquia,
				  cod_centro,
				  cod_concejo,
				  denominacion,
				  tipo_zona,
				  fecha_inicio,
				  fecha_terminacion,
				  numero_electores,
				  numero_votantes,
				  resultado";

   $values_b = "  '".$cod_republica."',
				  '".$cod_estado."',
				  '".$cod_municipio."',
				  '".$cod_parroquia."',
				  '".$cod_centro_poblado."',
				  '".$cod_concejo."',
				  '".$deno_concejo."',
				  '".$zonificacion."',
				  '".$fecha_inicio."',
				  '".$fecha_terminacion."',
				  '".$num_electores."',
				  '".$num_votantes."',
				  '".$resultado."'";


                   $sql_insert = "INSERT INTO ccnd01_concejo_comunal (".$campos_b.") VALUES(".$values_b."); ";
						   $sw = $this->ccnd01_concejo_comunal->execute($sql_insert);



			if($sw>1){
				$this->ccnd01_concejo_comunal->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');
				echo" <script> ver_documento('/ccnp01_concejo_comunal/','principal'); </script>";
	   		}else{
	   			$this->ccnd01_concejo_comunal->execute("ROLLBACK");
	   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');
	   		}



}else{ $this->set('errorMessage', 'EL NÚMERO DE RESULTADO NO PUEDE SER MAYOR AL NÚMERO DE VOTANTES '); }
}else{ $this->set('errorMessage', 'EL NÚMERO DE VOTANTES NO PUEDE SER MAYOR AL NÚMERO DE ELECTORES '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha de terminación '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha de inicio '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el porcentaje '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el resultado '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el número de votantes '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el número de electores '); }
}else{ $this->set('errorMessage', 'DEBE seleccionar la zonificación '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la denominación del concejo comunal'); }
}else{ $this->set('errorMessage', 'DEBE seleccionar el centro poblado'); }
}else{ $this->set('errorMessage', 'DEBE seleccionar la parroquia'); }
}else{ $this->set('errorMessage', 'DEBE seleccionar el municipio'); }
}else{ $this->set('errorMessage', 'DEBE seleccionar el estado'); }
}else{ $this->set('errorMessage', 'DEBE seleccionar la república'); }



$this->funcion();
$this->render("funcion");

}// fin guardar



function guardar_modificar($pagina=null){

	$this->layout = "ajax";



if(!empty($this->data['ccnp01_concejo_comunal']['deno_concejo'])){
if(!empty($this->data['ccnp01_concejo_comunal']['zonificacion'])){
if(!empty($this->data['ccnp01_concejo_comunal']['num_electores'])){
if(!empty($this->data['ccnp01_concejo_comunal']['num_votantes'])){
if(!empty($this->data['ccnp01_concejo_comunal']['resultado'])){
if(!empty($this->data['ccnp01_concejo_comunal']['porcentaje'])){
if(!empty($this->data['ccnp01_concejo_comunal']['fecha_inicio'])){
if(!empty($this->data['ccnp01_concejo_comunal']['fecha_terminacion'])){
if($this->data['ccnp01_concejo_comunal']['num_votantes'] <= $this->data['ccnp01_concejo_comunal']['num_electores']){
if($this->data['ccnp01_concejo_comunal']['resultado'] <= $this->data['ccnp01_concejo_comunal']['num_votantes']){





		$cod_republica      = $this->data['ccnp01_concejo_comunal']['cod_republica'];
		$cod_estado         = $this->data['ccnp01_concejo_comunal']['cod_estado'];
		$cod_municipio      = $this->data['ccnp01_concejo_comunal']['cod_municipio'];
		$cod_parroquia      = $this->data['ccnp01_concejo_comunal']['cod_parroquia'];
		$cod_centro_poblado = $this->data['ccnp01_concejo_comunal']['cod_centro'];
		$cod_concejo        = $this->data['ccnp01_concejo_comunal']['cod_concejo'];
		$deno_concejo       = $this->data['ccnp01_concejo_comunal']['deno_concejo'];
		$zonificacion       = $this->data['ccnp01_concejo_comunal']['zonificacion'];
		$num_electores      = $this->data['ccnp01_concejo_comunal']['num_electores'];
		$num_votantes       = $this->data['ccnp01_concejo_comunal']['num_votantes'];
		$resultado          = $this->data['ccnp01_concejo_comunal']['resultado'];
		$porcentaje         = $this->data['ccnp01_concejo_comunal']['porcentaje'];
		$fecha_inicio       = $this->data['ccnp01_concejo_comunal']['fecha_inicio'];
		$fecha_terminacion  = $this->data['ccnp01_concejo_comunal']['fecha_terminacion'];

$sql_a = "cod_republica                = '".$cod_republica."'       and
		  cod_estado                   = '".$cod_estado."'          and
		  cod_municipio                = '".$cod_municipio."'       and
		  cod_parroquia                   = '".$cod_parroquia."'       and
		  cod_centro                   = '".$cod_centro_poblado."'  and
		  cod_concejo                  = '".$cod_concejo."'";


   $values_b = "
				  denominacion      = '".$deno_concejo."',
				  tipo_zona         = '".$zonificacion."',
				  fecha_inicio      = '".$fecha_inicio."',
				  fecha_terminacion = '".$fecha_terminacion."',
				  numero_electores  = '".$num_electores."',
				  numero_votantes   = '".$num_votantes."',
				  resultado         = '".$resultado."'";


                   $sql_insert = " BEGIN; UPDATE ccnd01_concejo_comunal SET ".$values_b." where ".$sql_a."; ";
   					       $sw = $this->ccnd01_concejo_comunal->execute($sql_insert);



			if($sw>1){
				$this->ccnd01_concejo_comunal->execute("COMMIT;");
				$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');
	   		}else{
	   			$this->ccnd01_concejo_comunal->execute("ROLLBACK;");
	   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');
	   		}

if(isset($pagina)){
	echo" <script> ver_documento('/ccnp01_concejo_comunal/consulta/".$pagina."','principal'); </script>";
}else{
	echo" <script> ver_documento('/ccnp01_concejo_comunal/consulta_especifica/$cod_republica/$cod_estado/$cod_municipio/$cod_parroquia/$cod_centro_poblado/$cod_concejo','principal'); </script>";
}


}else{ $this->set('errorMessage', 'EL NÚMERO DE RESULTADO NO PUEDE SER MAYOR AL NÚMERO DE VOTANTES '); }
}else{ $this->set('errorMessage', 'EL NÚMERO DE VOTANTES NO PUEDE SER MAYOR AL NÚMERO DE ELECTORES '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha de terminación '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha de inicio '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el porcentaje '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el resultado '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el número de votantes '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el número de electores '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la zonificación '); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la denominación del concejo comunal'); }




$this->funcion();
$this->render("funcion");










}// fin guardar











function consulta($pagina=null){

$this->layout = "ajax";



if(isset($pagina)){
		$Tfilas=$this->ccnd01_concejo_comunal->findCount(null);
        if($Tfilas!=0){
	        	$x=$this->ccnd01_concejo_comunal->findAll(null,null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo ASC",1,$pagina,null);
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
		$Tfilas=$this->ccnd01_concejo_comunal->findCount(null);

        if($Tfilas!=0){
	        	$x=$this->ccnd01_concejo_comunal->findAll(null, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo ASC",1,$pagina,null);
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




    $cod_republica = $x[0]["ccnd01_concejo_comunal"]["cod_republica"];
    $cod_estado    = $x[0]["ccnd01_concejo_comunal"]["cod_estado"];
    $cod_municipio = $x[0]["ccnd01_concejo_comunal"]["cod_municipio"];
    $cod_parroquia = $x[0]["ccnd01_concejo_comunal"]["cod_parroquia"];
    $cod_centro    = $x[0]["ccnd01_concejo_comunal"]["cod_centro"];
    $cod_concejo   = $x[0]["ccnd01_concejo_comunal"]["cod_concejo"];
    $zonificacion  = $x[0]["ccnd01_concejo_comunal"]["tipo_zona"];


    $sql = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro."  and  cod_concejo='".$cod_concejo."'    ";
    $xx=$this->v_ccnd01_directiva->findAll($sql, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo ASC");
    $this->set('xx', $xx);

    $resultado        = $x[0]["ccnd01_concejo_comunal"]["resultado"];
    $numero_votantes  = $x[0]["ccnd01_concejo_comunal"]["numero_votantes"];

   $porcentaje=(($resultado/$numero_votantes)*100);


switch($zonificacion){
		case "1":
			$zonificacion='Urbanización';
		break;
		case "2":
			$zonificacion='Barrio';
		break;
		case "3":
			$zonificacion='Caserio';
		break;
		case "4":
			$zonificacion='Comuna';
		break;
		case "5":
			$zonificacion='Vialidad';
		break;
}


$this->set('zonificacion',$zonificacion);
$this->set('porcentaje',$porcentaje);

	$this->set('republica',$this->cugd01_republica->field('denominacion',  $conditions ="cod_republica=".$cod_republica, $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion',       $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado, $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia, $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro;
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);


}//fin funtion















function eliminar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $var7=null){

$this->layout = "ajax";



    $cod_republica = $var1;
    $cod_estado    = $var2;
    $cod_municipio = $var3;
    $cod_parroquia = $var4;
    $cod_centro    = $var5;
    $cod_concejo   = $var6;
    $pagina        = $var7;


     $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

     $x = $this->ccnd01_concejo_comunal->execute("BEGIN; DELETE FROM ccnd01_concejo_comunal  WHERE ".$sql_concejo."; COMMIT;");

       $this->set('errorMessage','registro eliminado con exito');

           $Tfilas=$this->ccnd01_concejo_comunal->findCount(null);
        if($Tfilas!=0){
	        	$x=$this->ccnd01_concejo_comunal->findAll(null,null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo ASC",1,$pagina,null);
	            $this->set('DATA',$x);
	            $this->set('pagina',$pagina);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	            $this->bt_nav($Tfilas,$pagina);
	            $this->set('numT',$Tfilas);
				$this->set('numP',$pagina);

			if(isset($var7)){
				$this->consulta($pagina);
			    $this->render("consulta");
			}else{
				$this->index();
			    $this->render("index");
			}


        }else{
	 	        $this->set('noExiste',true);
	 	        $this->index();
			    $this->render("index");

			return;
        }



}//fin function

























 function modificar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $var7=null){

$this->layout = "ajax";



    $cod_republica = $var1;
    $cod_estado    = $var2;
    $cod_municipio = $var3;
    $cod_parroquia = $var4;
    $cod_centro    = $var5;
    $cod_concejo   = $var6;
    $pagina        = $var7;

    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

    $sql = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro."  and  cod_concejo='".$cod_concejo."'    ";
    $xx=$this->v_ccnd01_directiva->findAll($sql, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo ASC");
    $this->set('xx', $xx);


		$x=$this->ccnd01_concejo_comunal->findAll($sql_concejo, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo ASC",1,1,null);
		$this->set('DATA',$x);
		$this->set('pagina',$pagina);



    $cod_republica = $x[0]["ccnd01_concejo_comunal"]["cod_republica"];
    $cod_estado    = $x[0]["ccnd01_concejo_comunal"]["cod_estado"];
    $cod_municipio = $x[0]["ccnd01_concejo_comunal"]["cod_municipio"];
    $cod_parroquia = $x[0]["ccnd01_concejo_comunal"]["cod_parroquia"];
    $cod_centro    = $x[0]["ccnd01_concejo_comunal"]["cod_centro"];

    $resultado        = $x[0]["ccnd01_concejo_comunal"]["resultado"];
    $numero_votantes  = $x[0]["ccnd01_concejo_comunal"]["numero_votantes"];

   $porcentaje=(($resultado/$numero_votantes)*100);


$this->set('porcentaje',$porcentaje);

	$this->set('republica',$this->cugd01_republica->field('denominacion',  $conditions ="cod_republica=".$cod_republica, $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion',       $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado, $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia, $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro;
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$zonificacion= array('1'=>'Urbanización','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
	$this->concatena($zonificacion, 'zonificacion');




 }// fin modificar_items



 function funcion(){
 	 $this->layout = "ajax";

 }// fin modificar_items




 function buscar_vista_1($var1=null){

	$this->layout="ajax";
	$this->Session->delete('pista');

}//fin function



function buscar_vista_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";

        $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."'";

	if(isset($var2)){

		            $var1 = $this->Session->read('pista');
					$var1 = strtoupper_sisap($var1);
					$pagina = $var2;
					$sql     =" (".$this->busca_separado(array("denominacion_republica","denominacion_municipio","denominacion_parroquia","denominacion_centro","cod_concejo", "denominacion"), $var1).") ";


		$Tfilas=$this->v_concejo_comunales->findCount($sql);
        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_concejo_comunales->findAll($sql,null,"cod_republica,cod_municipio,cod_parroquia,cod_centro,cod_concejo,denominacion ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);


        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	        $this->set("datosFILAS",'');

			return;
        }

	}else{
	              	$pagina=1;
	       	        $this->Session->write('pista', $var1);
					$var1 = strtoupper_sisap($var1);
					$sql     =" (".$this->busca_separado(array("denominacion_republica","denominacion_municipio","denominacion_parroquia","denominacion_centro","cod_concejo", "denominacion"), $var1).") ";

		$Tfilas=$this->v_concejo_comunales->findCount($sql);
        if($Tfilas!=0){
	        					$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_concejo_comunales->findAll($sql,null,"cod_republica,cod_municipio,cod_parroquia,cod_centro,cod_concejo,denominacion ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);

        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	         $this->set("datosFILAS",'');

			 return;
        }
	}



}//fin function



function consulta_especifica($republica=null,$estado=null,$municipio=null,$parroquia=null,$centro=null,$concejo=null){
$this->layout = "ajax";


    $sql = "cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro."  and  cod_concejo='".$concejo."'    ";
    $x=$this->ccnd01_concejo_comunal->findAll($sql, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo ASC",1,null,null);
	$this->set('DATA',$x);

    $xx=$this->v_ccnd01_directiva->findAll($sql, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo ASC");
    $this->set('xx', $xx);

    $resultado        = $x[0]["ccnd01_concejo_comunal"]["resultado"];
    $numero_votantes  = $x[0]["ccnd01_concejo_comunal"]["numero_votantes"];

   $porcentaje=(($resultado/$numero_votantes)*100);


switch($x[0]["ccnd01_concejo_comunal"]["tipo_zona"]){
		case "1":
			$zonificacion='Urbanización';
		break;
		case "2":
			$zonificacion='Barrio';
		break;
		case "3":
			$zonificacion='Caserio';
		break;
		case "4":
			$zonificacion='Comuna';
		break;
		case "5":
			$zonificacion='Vialidad';
		break;
}


$this->set('zonificacion',$zonificacion);
$this->set('porcentaje',$porcentaje);

	$this->set('republica',$this->cugd01_republica->field('denominacion',  $conditions ="cod_republica=".$republica, $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion',       $conditions ="cod_republica=".$republica." and cod_estado=".$estado, $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$municipio, $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia, $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro;
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);


}//fin funtion






 }//Fin de la clase controller
 ?>