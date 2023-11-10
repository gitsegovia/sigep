<?php

 class Cnmp15AnticipoBonoTransferenciaController extends AppController{

    var $name = "cnmp15_anticipo_bono_transferencia";
    var $uses = array('cnmd15_anticipo_bono_transf', 'v_cnmd15_anticipo_bono_transf', 'v_cnmd15_datos_personales_prestaciones', 'cnmd15_firmas_informes', 'cnmd15_bono_vaca', 'cnmd15_aguinaldo', 'cnmd15_anticipos', 'cnmd15_datos_personales', 'cnmd15_datos_prestaciones', 'ccfd04_cierre_mes', 'Cnmd01', 'v_cnmd05', 'Cnmd01', 'cugd01_estados', 'cugd02_institucion');
    var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
                if (!$this->Session->check('Usuario')){
                        $this->redirect('/salir/');
                        exit();
                }else{

	$this->requestAction('/usuarios/actualizar_user');
                }//fin else
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


function index(){

      $this->layout = "ajax";
      $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $this->Session->delete('monto_deuda_anticipo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

                                            $cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
							  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
								  			$cod_cargo              =  $this->Session->read('cod_cargo_prestaciones');
								  			$cod_ficha              =  $this->Session->read('cod_ficha_prestaciones');
										    $cedula                 =  $this->Session->read('cedula_pestana_prestaciones');
											$this->set('cod_tipo_nomina',    $cod_tipo_nomina );
											$this->set('cod_cargo',          $cod_cargo );
											$this->set('cod_ficha',          $cod_ficha );
											$this->set('cedula',             $cedula );
											$deno_nomina = "";
											$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_tipo_nomina'", $order ="cod_tipo_nomina ASC");
										    $this->set('deno_nomina', $deno_nomina);
										    $cont = 0;
											$lista2a = $this->cnmd15_datos_personales->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula );
													$primer_apellido       =     $lista2a[0]['cnmd15_datos_personales']['primer_apellido'];
													$segundo_apellido      =     $lista2a[0]['cnmd15_datos_personales']['segundo_apellido'];
													$primer_nombre         =     $lista2a[0]['cnmd15_datos_personales']['primer_nombre'];
													$segundo_nombre        =     $lista2a[0]['cnmd15_datos_personales']['segundo_nombre'];
													$institucion           =     $lista2a[0]['cnmd15_datos_personales']['institucion'];
													$dependencia           =     $lista2a[0]['cnmd15_datos_personales']['dependencia'];
													$cargo                 =     $lista2a[0]['cnmd15_datos_personales']['denominacion_cargo'];
													$this->set('primer_apellido',    $primer_apellido );
													$this->set('segundo_apellido',   $segundo_apellido );
													$this->set('primer_nombre',      $primer_nombre );
													$this->set('segundo_nombre',     $segundo_nombre );
													$this->set('institucion',        $institucion );
													$this->set('dependencia',        $dependencia );
													$this->set('cargo',              $cargo );


											    $datos_anticipo_bono = $this->cnmd15_anticipo_bono_transf->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula);
										        if(!empty($datos_anticipo_bono)){
										        	$this->set('datos_bono_anticipo', $datos_anticipo_bono);
													foreach($datos_anticipo_bono as $row_datos_antic){
														$bono_trans = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_bono'];
														$bono_antic = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_anticipo'];
													}
										        }else{
										        	$bono_trans =0 ;
										        	$bono_antic=0;
										        	$this->set('datos_bono_anticipo', null);
										        }

				/** CALCULO DEL */
			// BONO DE FRANSFERENCIA: Llamado a la Funcion calculo_intereses.

		$this->calculo_intereses("si", $bono_trans, $bono_antic);

}//fin function


function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->Session->write('codigo_tipo_nomina', $lista[0]['Cnmd01']['cod_tipo_nomina']);
			$this->set('tipo_nomina',$lista);
		}else{
			$this->Session->write('codigo_tipo_nomina', 0);
			$this->set('tipo_nomina', array());
		}
	if($this->cnmd15_datos_prestaciones->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){
		echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');</script>";
	}else{
		$this->tipo_proceso_envio(2);
	}
		echo "<script> document.getElementById('id_enviar_generar').disabled=true; </script>";
	}
}



function tipo_proceso_envio($vari=null) {
	$this->layout="ajax";
	if($vari!=null){
		if($vari==1){
			// llamado a la funcion procesar.
		}else if($vari==2){
$url                  =  "/cnmp15_anticipo_bono_transferencia/buscar_datos_personales/$vari";
$width_aux            =  "750px";
$height_aux           =  "450px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
		}
	}else{
		$this->set('errorMessage','NO LLEG&Oacute; INFORMACI&Oacute;N COMPLETA PARA PROCESAR');
	}
} // fin funcion


function reporte_form_anticipo(){
    $this->layout="ajax";
    $this->Session->delete('codigo_tipo_nomina');
    $lista = $this->Cnmd01->generateList($this->SQLCA()."", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}

	$firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23100");

	if($firmantes!=null){
		$this->set('firma_existe','si');
		$this->set('b_readonly','readonly');
		$this->set('tipo_documento',$firmantes[0]['cnmd15_firmas_informes']['tipo_documento']);
		$this->set('nombre_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_cuarta_firma']);
		$this->set('nombre_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_quinta_firma']);
		$this->set('cargo_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_quinta_firma']);
		$this->set('nombre_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_sexta_firma']);
		$this->set('cargo_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_sexta_firma']);
		$this->set('nombre_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_septima_firma']);
		$this->set('cargo_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_septima_firma']);
		$this->set('nombre_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_octava_firma']);
		$this->set('cargo_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_octava_firma']);
		$this->set('primera_copia',$firmantes[0]['cnmd15_firmas_informes']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cnmd15_firmas_informes']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cnmd15_firmas_informes']['tercera_copia']);
		$this->set('cuarta_copia',$firmantes[0]['cnmd15_firmas_informes']['cuarta_copia']);
		$this->set('quinta_copia',$firmantes[0]['cnmd15_firmas_informes']['quinta_copia']);
		$this->set('sexta_copia',$firmantes[0]['cnmd15_firmas_informes']['sexta_copia']);
		$this->set('septima_copia',$firmantes[0]['cnmd15_firmas_informes']['septima_copia']);
		$this->set('octava_copia',$firmantes[0]['cnmd15_firmas_informes']['octava_copia']);
		$pie_pagina_doc = str_replace("\t"," ",$firmantes[0]['cnmd15_firmas_informes']['pie_pagina']);
		$pie_pagina_doc = str_replace("\n"," ",$pie_pagina_doc);
		$this->set('pie_pagina',$pie_pagina_doc);
	}else{
		$this->set('Message_existe','POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
		$this->set('firma_existe','no');
		$this->set('b_readonly','');
		$this->set('tipo_documento','23100');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','');
		$this->set('cargo_cuarta_firma','');
		$this->set('nombre_quinta_firma','');
		$this->set('cargo_quinta_firma','');
		$this->set('nombre_sexta_firma','');
		$this->set('cargo_sexta_firma','');
		$this->set('nombre_septima_firma','');
		$this->set('cargo_septima_firma','');
		$this->set('nombre_octava_firma','');
		$this->set('cargo_octava_firma','');
		$this->set('primera_copia','');
		$this->set('segunda_copia','');
		$this->set('tercera_copia','');
		$this->set('cuarta_copia','');
		$this->set('quinta_copia','');
		$this->set('sexta_copia','');
		$this->set('septima_copia','');
		$this->set('octava_copia','');
		$this->set('pie_pagina','');
	}

}



function firmas_bono_transferencia(){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$tipo_doc = $this->data['cnmd15_firmas_informes']['tipo_documento'];
	$nombre_primera_firma = $this->data['cnmd15_firmas_informes']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cnmd15_firmas_informes']['cargo_primera_firma'];
	$nombre_segunda_firma = $this->data['cnmd15_firmas_informes']['nombre_segunda_firma'];
	$cargo_segunda_firma  = $this->data['cnmd15_firmas_informes']['cargo_segunda_firma'];
	$nombre_tercera_firma = $this->data['cnmd15_firmas_informes']['nombre_tercera_firma'];
	$cargo_tercera_firma  = $this->data['cnmd15_firmas_informes']['cargo_tercera_firma'];
	$nombre_cuarta_firma = $this->data['cnmd15_firmas_informes']['nombre_cuarta_firma'];
	$cargo_cuarta_firma  = $this->data['cnmd15_firmas_informes']['cargo_cuarta_firma'];
	$nombre_quinta_firma = $this->data['cnmd15_firmas_informes']['nombre_quinta_firma'];
	$cargo_quinta_firma  = $this->data['cnmd15_firmas_informes']['cargo_quinta_firma'];
	$nombre_sexta_firma = $this->data['cnmd15_firmas_informes']['nombre_sexta_firma'];
	$cargo_sexta_firma  = $this->data['cnmd15_firmas_informes']['cargo_sexta_firma'];
	$nombre_septima_firma = $this->data['cnmd15_firmas_informes']['nombre_septima_firma'];
	$cargo_septima_firma  = $this->data['cnmd15_firmas_informes']['cargo_septima_firma'];
	$nombre_octava_firma = $this->data['cnmd15_firmas_informes']['nombre_octava_firma'];
	$cargo_octava_firma  = $this->data['cnmd15_firmas_informes']['cargo_octava_firma'];

	$primera_cc = isset($this->data['cnmd15_firmas_informes']['primera_copia'])? $this->data['cnmd15_firmas_informes']['primera_copia'] : '';
	$segunda_cc = isset($this->data['cnmd15_firmas_informes']['segunda_copia'])? $this->data['cnmd15_firmas_informes']['segunda_copia'] : '';
	$tercera_cc = isset($this->data['cnmd15_firmas_informes']['tercera_copia'])? $this->data['cnmd15_firmas_informes']['tercera_copia'] : '';
	$cuarta_cc  = isset($this->data['cnmd15_firmas_informes']['cuarta_copia'])? $this->data['cnmd15_firmas_informes']['cuarta_copia'] : '';
	$quinta_cc  = isset($this->data['cnmd15_firmas_informes']['quinta_copia'])? $this->data['cnmd15_firmas_informes']['quinta_copia'] : '';
	$sexta_cc   = isset($this->data['cnmd15_firmas_informes']['sexta_copia'])? $this->data['cnmd15_firmas_informes']['sexta_copia'] : '';
	$septima_cc = isset($this->data['cnmd15_firmas_informes']['septima_copia'])? $this->data['cnmd15_firmas_informes']['septima_copia'] : '';
	$octava_cc  = isset($this->data['cnmd15_firmas_informes']['octava_copia'])? $this->data['cnmd15_firmas_informes']['octava_copia'] : '';

	$pie_pagina = $this->data['cnmd15_firmas_informes']['pie_pagina'];
	$pie_pagina = str_replace("\t"," ",$pie_pagina);
	$pie_pagina = str_replace("\n"," ",$pie_pagina);

	$enc_td_firma = $this->cnmd15_firmas_informes->findCount($this->SQLCA()." and tipo_documento=$tipo_doc");

	if($enc_td_firma==0){
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cnmd15_firmas_informes VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$nombre_quinta_firma', '$cargo_quinta_firma', '$nombre_sexta_firma', '$cargo_sexta_firma', '$nombre_septima_firma', '$cargo_septima_firma', '$nombre_octava_firma', '$cargo_octava_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc', '$pie_pagina');";
	}else{
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cnmd15_firmas_informes SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma',              nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma', nombre_quinta_firma='$nombre_quinta_firma', cargo_quinta_firma='$cargo_quinta_firma', nombre_sexta_firma='$nombre_sexta_firma', cargo_sexta_firma='$cargo_sexta_firma', nombre_septima_firma='$nombre_septima_firma', cargo_septima_firma='$cargo_septima_firma', nombre_octava_firma='$nombre_octava_firma', cargo_octava_firma='$cargo_octava_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc', pie_pagina='$pie_pagina' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc;
	}

	$swi = $this->cnmd15_firmas_informes->execute($sql_ejecutar);

	if($swi>1){
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}

	$this->reporte_form_anticipo();
	$this->render('reporte_form_anticipo');

}



function modificar_firmas_bono_transferencia(){
	$this->layout="ajax";
	$this->set('Message_existe','Puede modificar los nombres y cargos de los firmantes');
}


function buscar_datos_personales($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_datos_porpista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	// $modelo='v_cnmd15_anticipo_bono_transf';
	$modelo='v_cnmd15_datos_personales_prestaciones';
	$cod_nomina = $this->Session->read('codigo_tipo_nomina');
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
$this->set("cod_nomi",$cod_nomina);
} //fin funcion



function salir_busqueda($var_salida=null) {
	$this->layout="ajax";
	if($var_salida=='si'){ }
}


function calculo_intereses($marca_clave=null, $var_m_transfe=null, $var_m_anticipo=null, $codi_nomina=null, $codi_cargo=null, $codi_ficha=null, $cedu_ide=null){

      $this->layout = "ajax";
      $this->set('marca_clave', $marca_clave);
      $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $this->Session->delete('monto_deuda_anticipo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	if($marca_clave!=null && $marca_clave=="si"){
		$marcado=true;
		if($codi_nomina!=null && $codi_cargo!=null && $codi_ficha!=null && $cedu_ide!=null){
			$cod_tipo_nomina        =  $codi_nomina;
			$cod_cargo              =  $codi_cargo;
			$cod_ficha              =  $codi_ficha;
			$cedula                 =  $cedu_ide;
		}else{
			$cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
			$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
			$cod_cargo              =  $this->Session->read('cod_cargo_prestaciones');
			$cod_ficha              =  $this->Session->read('cod_ficha_prestaciones');
			$cedula                 =  $this->Session->read('cedula_pestana_prestaciones');
		}

	}else{
		$marcado=false;
	 	$this->Session->delete('codigo1_tipo_nomina');
     	$this->Session->delete('codigo1_cargo');
     	$this->Session->delete('codigo1_ficha');
     	$this->Session->delete('cedula1_identidad');

			$cod_tipo_nomina        =  $codi_nomina;
			$cod_cargo              =  $codi_cargo;
			$cod_ficha              =  $codi_ficha;
			$cedula                 =  $cedu_ide;

		$this->Session->write('codigo1_tipo_nomina', $codi_nomina);
		$this->Session->write('codigo1_cargo', $codi_cargo);
		$this->Session->write('codigo1_ficha', $codi_ficha);
		$this->Session->write('cedula1_identidad', $cedu_ide);
	} // fin else marca clave



													/** CALCULO DEL */
												// BONO POR TRANSFERENCIA:


         	$d2 = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$condicion." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula");
			$monto_transf = 0;

		if($d2!=null){

            foreach($d2 as $datos1){ // inicio datos1
                $fecha_ingreso     = $datos1[0]['fecha_ingreso'];
               	$fecha_egreso      = $datos1[0]['fecha_egreso'];
                $dia               = $datos1[0]['dia'];
                $mes               = $datos1[0]['mes'];
                $ano               = $datos1[0]['ano'];
                $dia_ingreso       = $datos1[0]['dia_ingreso'];
                $mes_ingreso       = $datos1[0]['mes_ingreso'];
                $ano_ingreso       = $datos1[0]['ano_ingreso'];
                $dia_egreso        = $datos1[0]['dia_egreso'];
                $mes_egreso        = $datos1[0]['mes_egreso'];
                $ano_egreso        = $datos1[0]['ano_egreso'];
                $cod_cargo         = $datos1[0]['cod_cargo'];
                $cod_ficha         = $datos1[0]['cod_ficha'];
                $cedula_identidad  = $datos1[0]['cedula_identidad'];
                $motivo_retiro     = $datos1[0]['motivo_retiro'];
                $cumplio_preaviso  = $datos1[0]['cumplio_preaviso'];
            }

 	$d6=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula and fecha_hasta>='$fecha_egreso'");
	foreach($d6 as $datos6){//datos6
		$sueldo_diario_basico   = $this->redondeo(($datos6[0]['sueldo_basico']/30));
		$sueldo_diario_integral = $this->redondeo(($datos6[0]['sueldo_integral']/30));
		$sueldo_diario_total    = $this->redondeo(($datos6[0]['sueldo_total']/30));

			if(($ano_ingreso<1997) || ($ano_ingreso==1997 && $mes_ingreso<6) || ($ano_ingreso==1997 && $mes_ingreso==6 && $dia_ingreso<=18)){


					if(isset($marcado) && $marcado==true){
						$this->set('a_readonly', '');
						$this->set('a_disabled', '');
					}
                	$fecha_egreso_transf = '1996-12-31';
                	$d5=$this->Cnmd01->execute("select devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'ANO') as ano_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'MES') as mes_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'DIA') as dia_transf");
					$dia_transf = mascara($d5[0][0]['dia_transf'],2);
                	$mes_transf = mascara($d5[0][0]['mes_transf'],2);
                	$ano_transf = $d5[0][0]['ano_transf'];


					$datos_anticipo_bono = $this->cnmd15_anticipo_bono_transf->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula_identidad);
						if(!empty($datos_anticipo_bono)){
							foreach($datos_anticipo_bono as $row_datos_antic){
								$monto_trans = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_bono'];
								$monto_antic = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_anticipo'];
								$deuda_bono_anticipo = ($monto_trans-$monto_antic);
								}
							}else{
								$monto_trans = 0;
								$monto_antic = 0;
								$deuda_bono_anticipo = 0;
								}

				if($ano_transf!=0 && $deuda_bono_anticipo==0 && $monto_antic==0 || $ano_transf!=0 && $deuda_bono_anticipo!=0 && $monto_antic!=0 || $ano_transf!=0 && $deuda_bono_anticipo!=0 && $monto_antic==0){
                		$de=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula and (fecha_desde<='$fecha_egreso_transf' and fecha_hasta>='$fecha_egreso_transf')");
						foreach($de as $datosde){
							$sueldo_diario_integral_transf = $this->redondeo(($datosde[0]['sueldo_integral']/30));
						}
						if($ano_transf>13){$ano_transf = 13;}
						if($sueldo_diario_integral_transf<0.5){$sueldo_diario_integral_transf = 0.5;}
						if($sueldo_diario_integral_transf>10.0){$sueldo_diario_integral_transf = 10.0;}
						$dias_transf  = ($ano_transf*30);
						$monto_transf = $this->redondeo(($dias_transf*$sueldo_diario_integral_transf));
						if($deuda_bono_anticipo!=0){$monto_transf = $deuda_bono_anticipo;}

				} // Año transfencia
    		} // $ano_ingreso<1997
				else{
					if(isset($marcado) && $marcado==true){
						$this->set('a_readonly', 'readonly');
						$this->set('a_disabled', 'disabled');
						echo "<script> fun_msj('Bono de Transferencia no le corresponde'); </script>";
					}
				}
			} // Busca sueldo

		} // Busca trabajador

	if(isset($marcado) && $marcado==true){
		$this->set('monto_bono_transf', $monto_transf);
	}

							$dia_ingreso_actual = 19;
							$mes_ingreso_actual = 7;
							$ano_ingreso_actual = 1997;
							$i1 = $ano_ingreso;
							$a1 = $a3 =	$a4 = $a5 = 0;
							$a1 = $monto_transf;
							$a5 = $monto_transf;
							$m2 = 4;
							$datos_imp = array();
							$index=0;

					// FOR AÑO:

						for ($k=$ano_ingreso_actual;$k<=$ano_egreso;$k++){
							$hf=12;

							if($k==$ano_ingreso_actual){$m1 = $mes_ingreso_actual;}else{$m1 = 1;}

							if($k==$ano_egreso){$hf = ($mes_egreso-1);}

							// FOR MES:

								for ($j=$m1;$j<=$hf;$j++){

										if($ano_egreso==$k && $mes_egreso==$j && (($dia_egreso-$dia_ingreso_actual)!=30)){// COMPLETA CICLO.
										}else{

											if($m2!=0){$m2 = ($m2-1); }

										$fecha_busqueda = $k."-".$j."-".$dia_ingreso_actual;

										// TASAS BANCO CENTRAL:
										$mes_encontrado = $this->fecha_str(''.$j);
										$mes_busqueda = 'tasa_'.$mes_encontrado;

										$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$k LIMIT 1;");

										if(!empty($tasa)){$a2 = $tasa[0][0]["$mes_busqueda"];}else{ $a2 = 0;}

										$dia_feb=1;
										if (($j==2 && $dia_ingreso>=28) || ($dia_ingreso==31)) { $dia_feb=0; }

										if($k==$ano_ingreso_actual && $j==$m1){
											$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso_actual);
										}else{
											$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso_actual+$dia_feb);
										}

										if($j==12){
											$hasta = ($k+1)."-".$this->zero(1)."-".$this->zero($dia_ingreso_actual);
										}else{
											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso_actual);
										}

										if($k==$ano_egreso && $j==($mes_egreso-1)){
											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_egreso);
										}else{
											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso_actual);
										}


								if($j>=12){$hasta = ($k+1)."-".$this->zero(1)."-".$this->zero($dia_ingreso_actual);}


											$a3 = $this->redondeo(((($a5/100)*$a2)/12));
											$a4 = ($a4+$a3);
											$a5 = ($a4+$a1);
								}


			// ALMACENANDO DATOS:

				  		$index++;
				        $datos_imp[$index]['fecha_desde'] = $desde;
				        $datos_imp[$index]['fecha_hasta'] = $hasta;
				        $datos_imp[$index]['monto_cpt'] = $a1;
				        $datos_imp[$index]['tasa'] = $a2;
				        $datos_imp[$index]['intereses'] =  $a3;
				        $datos_imp[$index]['intereses_acumulados'] = $a4;
				        $datos_imp[$index]['capital'] = $a5;

					  		}
						}

	if(isset($marcado) && $marcado==true){
		$this->Session->write('monto_deuda_anticipo', $a4);
		$this->set('datos_bono_transfe', $datos_imp);
	}else if(isset($marcado) && $marcado==false){
		echo "<script> document.getElementById('id_enviar_generar').disabled=false; </script>";
	}

	$_SESSION['datos_intereses_reporte'] = $datos_imp;

} // fin funcion calculo_intereses




function guardar($var1=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $cod_tipo_nomina     =  $this->data['cnmd15_anticipo_bono_transf']['cod_nomina'];
  $codigo_cargo        =  $this->data['cnmd15_anticipo_bono_transf']['codigo_cargo'];
  $codigo_ficha        =  $this->data['cnmd15_anticipo_bono_transf']['codigo_ficha'];
  $cedula              =  $this->data['cnmd15_anticipo_bono_transf']['cedula'];
  $fecha_anticipo      =  $this->Cfecha($this->data['cnmd15_anticipo_bono_transf']['fecha_anticipo'], "A-M-D");
  $monto_bono_transfe  =  $this->Formato1($this->data['cnmd15_anticipo_bono_transf']['bono_transferencia']);
  $monto_bono_anticipo =  $this->Formato1($this->data['cnmd15_anticipo_bono_transf']['bono_anticipo']);
  $monto_interes       =  $this->Formato1($this->Session->read('monto_deuda_anticipo'));

	$this->cnmd15_anticipo_bono_transf->execute('BEGIN; ');
	$cont = $this->cnmd15_anticipo_bono_transf->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);

if($cont==0){
		$muestra = 'Guardados';
		$sql = " INSERT INTO cnmd15_anticipo_bono_transf (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, monto_bono, monto_anticipo, monto_interes, fecha_anticipo)";
		$sql .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$codigo_cargo."', '".$codigo_ficha."', '".$cedula."', '".$monto_bono_transfe."', '".$monto_bono_anticipo."', '".$monto_interes."', '".$fecha_anticipo."');";
}else{
		$muestra = 'Modificados';
        $sql = " UPDATE cnmd15_anticipo_bono_transf SET monto_bono='".$monto_bono_transfe."', monto_anticipo='".$monto_bono_anticipo."', monto_interes='".$monto_interes."', fecha_anticipo='".$fecha_anticipo."' WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula;
}//fin else

		$sw2 = $this->cnmd15_anticipo_bono_transf->execute($sql);

					if($sw2>1){
		                $this->cnmd15_anticipo_bono_transf->execute("COMMIT;");
				        $this->set('Message_existe', 'Los datos fueron '.$muestra.' correctamente');
					}else{
						$this->cnmd15_anticipo_bono_transf->execute("ROLLBACK;");
				        $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
					}//fin else

	$this->index();
	$this->render('index');


}//fin funtion



function guardar_modificar($var_direc=null){
  $this->layout = "ajax";
  if($var_direc!=null){
  	if($var_direc==1 || $var_direc=='1'){
  		$this->set('variable_d', $var_direc);
  	}else{
  		$this->set('variable_d', $var_direc);
  	}
  }else{
  	$this->set('errorMessage', 'NO LLEGO INFORMACION COMPLETA PARA PROCESAR - POR FAVOR INTENTE DE NUEVO');
  }
}



function eliminar($var_eliminar=null){
 	  $this->layout = "ajax";
      $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	if($var_eliminar!=null && $var_eliminar=='si'){

	$cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
	$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
	$cod_cargo              =  $this->Session->read('cod_cargo_prestaciones');
	$cod_ficha              =  $this->Session->read('cod_ficha_prestaciones');
	$cedula                 =  $this->Session->read('cedula_pestana_prestaciones');

  $sql="BEGIN;  DELETE FROM cnmd15_anticipo_bono_transf  WHERE ".$condicion." and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$cod_cargo."' and cod_ficha= '".$cod_ficha."' and cedula_identidad= '".$cedula."'";
  $sw2 = $this->cnmd15_anticipo_bono_transf->execute($sql);
			if($sw2>1){
                $this->cnmd15_anticipo_bono_transf->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CORRECTAMENTE');
			}else{
				$this->cnmd15_anticipo_bono_transf->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else
	}else{
		$this->set('errorMessage', 'NO LLEGO INFORMACION COMPLETA PARA PROCESAR - POR FAVOR INTENTE DE NUEVO');
	}

	$this->index();
	$this->render('index');

}//fin funtion



function reporte_pdf_intereses($vard_reporte=null){
	$this->layout = "pdf";
      $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$_SESSION['marca_paso']=1;
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$cod_presi."' and cod_estado='".$cod_entidad."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

	if($vard_reporte!=null && $vard_reporte=='t1'){
		$cod_tipo_nomina        =  $this->Session->read('codigo1_tipo_nomina');
		$cod_cargo              =  $this->Session->read('codigo1_cargo');
		$cod_ficha              =  $this->Session->read('codigo1_ficha');
		$cedula                 =  $this->Session->read('cedula1_identidad');
	}else{
		$cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
		$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
		$cod_cargo              =  $this->Session->read('cod_cargo_prestaciones');
		$cod_ficha              =  $this->Session->read('cod_ficha_prestaciones');
		$cedula                 =  $this->Session->read('cedula_pestana_prestaciones');
	}

  	$datos_perso = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$condicion." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula");

 	if($datos_perso!=null){

            foreach($datos_perso as $datos_p){
                /* $fecha_ingreso     = $datos_p[0]['fecha_ingreso'];
               	$fecha_egreso      = $datos_p[0]['fecha_egreso'];
                $dia               = $datos_p[0]['dia'];
                $mes               = $datos_p[0]['mes'];
                $ano               = $datos_p[0]['ano'];
                $dia_ingreso       = $datos_p[0]['dia_ingreso'];
                $mes_ingreso       = $datos_p[0]['mes_ingreso'];
                $ano_ingreso       = $datos_p[0]['ano_ingreso'];
                $dia_egreso        = $datos_p[0]['dia_egreso'];
                $mes_egreso        = $datos_p[0]['mes_egreso'];
                $ano_egreso        = $datos_p[0]['ano_egreso'];
                $cod_cargo         = $datos_p[0]['cod_cargo'];
                $cod_ficha         = $datos_p[0]['cod_ficha'];
                $motivo_retiro     = $datos_p[0]['motivo_retiro'];
                $cumplio_preaviso  = $datos_p[0]['cumplio_preaviso']; */

                $cedula_identidad  = $datos_p[0]['cedula_identidad'];
                $primer_nomb       = $datos_p[0]['primer_nombre'];
                $segundo_nomb      = $datos_p[0]['segundo_nombre'];
                $primer_ape        = $datos_p[0]['primer_apellido'];
                $segundo_ape       = $datos_p[0]['segundo_apellido'];
                $deno_cargo        = $datos_p[0]['denominacion_cargo'];
                $fecha_ingreso     = $datos_p[0]['fecha_ingreso'];
                $fecha_egreso      = $datos_p[0]['fecha_egreso'];
            } // fin foreach

		$this->set('cedula_identidad', $cedula_identidad);
		$this->set('primer_nomb', $primer_nomb);
		$this->set('segundo_nomb', $segundo_nomb);
		$this->set('primer_ape', $primer_ape);
		$this->set('segundo_ape', $segundo_ape);
		$this->set('deno_cargo', $deno_cargo);
		$this->set('fecha_ingreso', $fecha_ingreso);
		$this->set('fecha_egreso', $fecha_egreso);

	}else{
		$this->set('cedula_identidad', '');
		$this->set('primer_nomb', '');
		$this->set('segundo_nomb', '');
		$this->set('primer_ape', '');
		$this->set('segundo_ape', '');
		$this->set('deno_cargo', '');
		$this->set('fecha_ingreso', '');
		$this->set('fecha_egreso', '');

	}

	$var_dato_inte = $_SESSION['datos_intereses_reporte'];
	$this->set('datos_resumen_prest', $var_dato_inte);

	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23100");
	$this->set('datos_firmantes', $d_firmantes);

}


 }//fin class

?>