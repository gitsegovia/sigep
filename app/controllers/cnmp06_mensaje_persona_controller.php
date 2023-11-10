<?php


 class Cnmp06MensajePersonaController extends AppController {
   var $name = 'cnmp06_mensaje_persona';
   var $uses = array("Cnmd01",'cnmd06_fichas', 'cnmd06_datos_personales', 'datos_personales_super_busqueda', 'd_p_super_busqueda_solo_cnmd06_ficha');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');





function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession




 function beforeFilter(){
 	$this->checkSession();
 }





function index(){

	$this->layout="ajax";

	$this->data=null;

	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');

	$this->concatenaN($lista, 'lista_nomina');


}//fin function



function llenar_pista_opcion($var1=null){

    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

}//fin fucntion





function datos_personales($cod_tipo_nomina=null, $cedula=null, $cod_cargo=null, $cod_ficha=null){
	$this->layout="ajax";


		$condicion = $this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina'";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');


			$datos_personales = $this->cnmd06_datos_personales->findAll($conditions = "cedula_identidad='$cedula'", $fields = 'primer_apellido, segundo_apellido, primer_nombre, segundo_nombre', $order = null, $limit = 1, $page = null, $recursive = null);
			$this->set('datos_personales', $datos_personales);


			$datos_ficha = $this->cnmd06_fichas->findAll($conditions = $condicion." and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."'    ", $fields = 'fecha_ingreso, fecha_retiro, motivo_retiro, fecha_terminacion_contrato, cod_cargo, cod_ficha, mensaje_personal', $order = null, $limit = 1, $page = null, $recursive = null);
            $this->set('datos_ficha', $datos_ficha);


	foreach($datos_personales as $row){
		$primer_apellido = $row['cnmd06_datos_personales']['primer_apellido'];
		$primer_nombre = $row['cnmd06_datos_personales']['primer_nombre'];
		$segundo_apellido = $row['cnmd06_datos_personales']['segundo_apellido'];
		$segundo_nombre = $row['cnmd06_datos_personales']['segundo_nombre'];
	}

	foreach($datos_ficha as $row2){
		$fecha_ingreso = $row2['cnmd06_fichas']['fecha_ingreso'];
		$fecha_egreso = $row2['cnmd06_fichas']['fecha_retiro'];
		$fecha_terminacion_contrato = $row2['cnmd06_fichas']['fecha_terminacion_contrato'];
		$motivo_retiro = $row2['cnmd06_fichas']['motivo_retiro'];
		$cod_cargo = $row2['cnmd06_fichas']['cod_cargo'];
		$cod_ficha = $row2['cnmd06_fichas']['cod_ficha'];
		$mensaje_personal = $row2['cnmd06_fichas']['mensaje_personal'];

	}


	echo "<script>";
		   echo "document.getElementById('cod_cargo').value='".mascara_seis($cod_cargo)."'; ";
		   echo "document.getElementById('cod_ficha').value='".mascara_seis($cod_ficha)."'; ";
		   echo "document.getElementById('cedula').value='".$cedula."'; ";
		   echo "document.getElementById('primer_apellido').value='".$primer_apellido."'; ";
		   echo "document.getElementById('segundo_apellido').value='".$primer_nombre."'; ";
		   echo "document.getElementById('primer_nombre').value='".$segundo_apellido."'; ";
		   echo "document.getElementById('segundo_nombre').value='".$segundo_nombre."'; ";
	echo "</script>";




	$c_Tfilas=$this->cnmd06_fichas->findCount($condicion." and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' and (mensaje_personal='' or mensaje_personal is null) ");

echo "<script>";

	if($c_Tfilas!=0){

         echo "document.getElementById('save').disabled    = false; ";
         echo "document.getElementById('mensaje').readOnly = false; ";

	}else{

         echo "document.getElementById('save').disabled        = true; ";
         echo "document.getElementById('modificar').disabled   = false; ";
         echo "document.getElementById('eliminar').disabled    = false; ";
         echo "document.getElementById('mensaje').value='".$mensaje_personal."'; ";

	}//fin else

echo "</script>";



}//fin function










function guardar(){

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


					 $cod_nomina = $this->data['cnmp06_mensaje_persona']['cod_nomina'];
					 $cod_cargo  = $this->data['cnmp06_mensaje_persona']['cod_cargo'];
                     $cod_ficha  = $this->data['cnmp06_mensaje_persona']['cod_ficha'];
                     $mensaje = $this->data['cnmp06_mensaje_persona']['mensaje'];

                     $condicion = $this->condicion()." and cod_tipo_nomina='$cod_nomina'  and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' ";


                     	$sql=" update cnmd06_fichas set mensaje_personal='".$mensaje."'  where ".$condicion;
                        $vvv=$this->cnmd06_fichas->execute($sql);

echo "<script>";
	     echo "document.getElementById('save').disabled    = true; ";
	     echo "document.getElementById('eliminar').disabled  = false; ";
	     echo "document.getElementById('modificar').disabled  = false; ";
	     echo "document.getElementById('regresar').disabled  = true; ";
         echo "document.getElementById('mensaje').readOnly = true; ";
echo "</script>";


$this->set("Message_existe", "Dato guardado");

$this->funcion();
$this->render("funcion");

}//fin function









function guardar_modificar($pagina=null){

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


					 $cod_nomina = $this->data['cnmp06_mensaje_persona']['cod_nomina'];
					 $cod_cargo  = $this->data['cnmp06_mensaje_persona']['cod_cargo'];
                     $cod_ficha  = $this->data['cnmp06_mensaje_persona']['cod_ficha'];
                     $mensaje = $this->data['cnmp06_mensaje_persona']['mensaje'];

                     $condicion = $this->condicion()." and cod_tipo_nomina='$cod_nomina'  and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' ";


                     	$sql=" update cnmd06_fichas set mensaje_personal='".$mensaje."'  where ".$condicion;
                        $vvv=$this->cnmd06_fichas->execute($sql);


$this->set("Message_existe", "Dato guardado");

$this->consulta($pagina);
$this->render("consulta");

}//fin function









function editar(){

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');

echo "<script>";
	     echo "document.getElementById('save').disabled      = false; ";
	     echo "document.getElementById('regresar').disabled  = false; ";
	     echo "document.getElementById('eliminar').disabled  = true; ";
	     echo "document.getElementById('modificar').disabled  = true; ";
         echo "document.getElementById('mensaje').readOnly = false; ";
echo "</script>";


$this->funcion();
$this->render("funcion");

}//fin function









function regresar(){

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


					 $cod_nomina = $this->data['cnmp06_mensaje_persona']['cod_nomina'];
					 $cod_cargo  = $this->data['cnmp06_mensaje_persona']['cod_cargo'];
                     $cod_ficha  = $this->data['cnmp06_mensaje_persona']['cod_ficha'];
                     $mensaje = $this->data['cnmp06_mensaje_persona']['mensaje'];

                     $condicion = $this->condicion()." and cod_tipo_nomina='$cod_nomina'  and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' ";





	$c_Tfilas=$this->cnmd06_fichas->findAll($condicion);


	foreach($c_Tfilas as $row2){
		$fecha_ingreso = $row2['cnmd06_fichas']['fecha_ingreso'];
		$fecha_egreso = $row2['cnmd06_fichas']['fecha_retiro'];
		$fecha_terminacion_contrato = $row2['cnmd06_fichas']['fecha_terminacion_contrato'];
		$motivo_retiro = $row2['cnmd06_fichas']['motivo_retiro'];
		$cod_cargo = $row2['cnmd06_fichas']['cod_cargo'];
		$cod_ficha = $row2['cnmd06_fichas']['cod_ficha'];
		$mensaje_personal = $row2['cnmd06_fichas']['mensaje_personal'];

	}

echo "<script>";
	     echo "document.getElementById('save').disabled       = true; ";
	      echo "document.getElementById('regresar').disabled  = true; ";
	      echo "document.getElementById('eliminar').disabled  = false; ";
	      echo "document.getElementById('modificar').disabled  = false; ";
         echo "document.getElementById('mensaje').readOnly = true; ";
         echo "document.getElementById('mensaje').value='".$mensaje_personal."'; ";
echo "</script>";


$this->funcion();
$this->render("funcion");

}//fin function








function eliminar(){

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


					 $cod_nomina = $this->data['cnmp06_mensaje_persona']['cod_nomina'];
					 $cod_cargo  = $this->data['cnmp06_mensaje_persona']['cod_cargo'];
                     $cod_ficha  = $this->data['cnmp06_mensaje_persona']['cod_ficha'];
                     $mensaje = $this->data['cnmp06_mensaje_persona']['mensaje'];

                     $condicion = $this->condicion()." and cod_tipo_nomina='$cod_nomina'  and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' ";


                     	$sql=" update cnmd06_fichas  set mensaje_personal=''  where ".$condicion;
                        $vvv=$this->cnmd06_fichas->execute($sql);

                        $this->set("Message_existe", "Dato eliminado");

$this->index();
$this->render("index");

}//fin function








function eliminar_consulta(){

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


					 $cod_nomina = $this->data['cnmp06_mensaje_persona']['cod_nomina'];
					 $cod_cargo  = $this->data['cnmp06_mensaje_persona']['cod_cargo'];
                     $cod_ficha  = $this->data['cnmp06_mensaje_persona']['cod_ficha'];
                     $mensaje = $this->data['cnmp06_mensaje_persona']['mensaje'];

                     $condicion = $this->condicion()." and cod_tipo_nomina='$cod_nomina'  and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' ";


                     	$sql=" update cnmd06_fichas  set mensaje_personal=''  where ".$condicion;
                        $vvv=$this->cnmd06_fichas->execute($sql);

                        $this->set("Message_existe", "Dato eliminado");

$this->consulta();
$this->render("consulta");

}//fin function










function show_cod_nomina($cod_tipo_nomina=null){
	$this->layout="ajax";
	if($cod_tipo_nomina != null){
		$this->set('cod_tipo_nomina', $cod_tipo_nomina);
		$this->Session->write('tipo_nomina', $cod_tipo_nomina);
	}
}


function f_cedula($cod_tipo_nomina=null){
	$this->layout="ajax";
	if($cod_tipo_nomina != null){
		$this->set('cod_tipo_nomina', $cod_tipo_nomina);
	}

}

function limpiar_datos($cod_tipo_nomina=null){
	$this->layout="ajax";
	$motivo_retiro = array('1'=>'Despido justificado', '2'=>'Despido injusticado', '3'=>'Retiro justificado', '4'=>'Renuncia', '5'=>'Jubilacion', '6'=>'Pensionado', '7'=>'Culminacion de contrato', '8'=>'Baja por propia solicitud', '9'=>'Baja por expulsion', '10'=>'Remocion del cargo', '11'=>'Reduccion de personal', '12'=>'Fallecimiento');
	$this->set('motivo_retiro', $motivo_retiro);
	$this->set('cod_tipo_nomina', $cod_tipo_nomina);
}//fin function




function show_deno_nomina($cod_tipo_nomina=null){
	$this->layout="ajax";
	if($cod_tipo_nomina != null){
		$denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('denominacion', $denominacion);
	}
}



function buscar_vista_1($var1=null){

	$this->layout="ajax";
	$this->set("cod_tipo_nomina",$var1);
	$this->Session->delete('pista');
	$this->set("opcion",$var1);
	$this->Session->write('pista_opcion', 2);

}//fin function










function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina=   $this->Session->read('tipo_nomina');

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');

					$condicion = $this->condicion()." and cod_tipo_nomina='".$tipo_nomina."' ";


	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $condicion." and ".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
						$Tfilas=$this->d_p_super_busqueda_solo_cnmd06_ficha->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->d_p_super_busqueda_solo_cnmd06_ficha->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,1,null);


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
						$var_like = $var22;
						$sql_like = $condicion." and ".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
						$Tfilas=$this->d_p_super_busqueda_solo_cnmd06_ficha->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->d_p_super_busqueda_solo_cnmd06_ficha->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,$pagina,null);

							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


$this->set("cod_tipo_nomina",$tipo_nomina);
$this->set("opcion",$var1);



}//fin function













function consulta($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');

					$condicion = $this->condicion()." and mensaje_personal!='' ";
					if($var1==null){$var1=1;}


						$sql_like = $condicion;
						$Tfilas   = $this->cnmd06_fichas->findCount($sql_like);

						        if($Tfilas!=0){
						        	$pagina=$var1;
						        	$Tfilas=(int)ceil($Tfilas/1);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cnmd06_fichas->findAll($sql_like,null,"cod_tipo_nomina, cod_cargo, cod_ficha ASC",1,$pagina,null);

							        $this->set("datosFILAS",$datos_filas);

							        $sql_like = $this->condicion()." and cod_tipo_nomina='".$datos_filas[0]["cnmd06_fichas"]["cod_tipo_nomina"]."'  and cod_cargo='".$datos_filas[0]["cnmd06_fichas"]["cod_cargo"]."' and cod_ficha='".$datos_filas[0]["cnmd06_fichas"]["cod_ficha"]."' ";

							        $datos_filas2=$this->d_p_super_busqueda_solo_cnmd06_ficha->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",1,1,null);


                                    $this->set('denominacion_nomina', $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["denominacion_nomina"]);

                                    $this->set('primer_nombre',    $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["primer_nombre"]);
                                    $this->set('segundo_nombre',   $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["segundo_nombre"]);
                                    $this->set('primer_apellido',  $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["primer_apellido"]);
                                    $this->set('segundo_apellido', $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["segundo_apellido"]);



							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }




}//fin function








function modificar_consulta($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');

					$condicion = $this->condicion()." and mensaje_personal!='' ";
					if($var1==null){$var1=1;}


						$sql_like = $condicion;
						$Tfilas   = $this->cnmd06_fichas->findCount($sql_like);

						        if($Tfilas!=0){
						        	$pagina=$var1;
						        	$Tfilas=(int)ceil($Tfilas/1);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cnmd06_fichas->findAll($sql_like,null,"cod_tipo_nomina, cod_cargo, cod_ficha ASC",1,$pagina,null);

							        $this->set("datosFILAS",$datos_filas);

							        $sql_like = $this->condicion()." and cod_tipo_nomina='".$datos_filas[0]["cnmd06_fichas"]["cod_tipo_nomina"]."'  and cod_cargo='".$datos_filas[0]["cnmd06_fichas"]["cod_cargo"]."' and cod_ficha='".$datos_filas[0]["cnmd06_fichas"]["cod_ficha"]."' ";

							        $datos_filas2=$this->d_p_super_busqueda_solo_cnmd06_ficha->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",1,1,null);


                                    $this->set('denominacion_nomina', $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["denominacion_nomina"]);

                                    $this->set('primer_nombre',    $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["primer_nombre"]);
                                    $this->set('segundo_nombre',   $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["segundo_nombre"]);
                                    $this->set('primer_apellido',  $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["primer_apellido"]);
                                    $this->set('segundo_apellido', $datos_filas2[0]["d_p_super_busqueda_solo_cnmd06_ficha"]["segundo_apellido"]);



							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }




}//fin function



















function funcion($var1=null, $var2=null, $var3=null){

    $this->layout="ajax";

}//fin function






}//fin class


?>