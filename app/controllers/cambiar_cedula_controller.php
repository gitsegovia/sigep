<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class CambiarCedulaController extends AppController {
   var $name = 'cambiar_cedula';
   var $uses = array('cnmd06_datos_personales','cnmd06_datos_educativos','cnmd06_datos_formacion_profesional','cnmd06_datos_registro_titulo',
					 'cnmd06_datos_familiares','cnmd06_experiencia_administrativa','cnmd06_datos_otrasexperiencias_laborables',
					 'cnmd06_datos_bienes','cnmd06_soportes','cnmd06_datos_permisos','cnmd06_datos_amonestaciones','cnmd06_fichas',
					 'cnmd06_fichas_h_c_a','cnmd08_historia_trabajador','ccfd04_cierre_mes');
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

function SQLCA_noDEP(){//sql para busqueda de codigos de arranque con y sin año
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;
}//fin funcion SQLCA_noDEP

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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena


    function AddCeroR($n,$extra=null){
   	  if($n!=null){
   	  	  if($extra==null){
        	if($n<10){
        	   $Var="0".$n;
        	}else{
	           $Var=$n;
        	}
   	  }else{
        	if($n<10){
        	   $Var=$extra.".0".$n;
        	}else{
	           $Var=$extra.".".$n;
        	}
   	  }

   	  $Var = substr($Var, - 2);

   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


     function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }
     }

function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero


 function index($numero=null){
 	$this->layout ="ajax";
 	$this->data=null;
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


function guardar(){
$this->layout ="ajax";
	$campo_a  =  $this->data['campo']['cedula_a'];
    $campo_b  =  $this->data['campo']['cedula_b'];
    $delete = $this->cnmd06_datos_personales->execute("BEGIN;
				ALTER TABLE cnmd06_fichas_historial_condicion DROP CONSTRAINT cnmd06_fichas_historial_condicion_1;
				ALTER TABLE cnmd07_transacciones_actuales_faov_tmp DROP CONSTRAINT cnmd07_ficha;
				ALTER TABLE cnmd08_historia_transacciones DROP CONSTRAINT cnmd08_historia_transacciones_1;
				ALTER TABLE cnmd08_historia_transacciones_fideicomiso DROP CONSTRAINT cnmd08_historia_transacciones_fideicomiso_1;
				ALTER TABLE cnmd10_individual_porcentaje_horas_cantidad DROP CONSTRAINT cnmd10_individual_porcentaje_horas_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded DROP CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded DROP CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_porcentaje_cantidad DROP CONSTRAINT cnmd10_individual_porcentaje_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_dias_cantidad DROP CONSTRAINT cnmd10_individual_dias_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_cantidad_horas_cantidad DROP CONSTRAINT cnmd10_individual_cantidad_horas_cantidad_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_bolivares_cantidad_ded DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey1;
				ALTER TABLE cnmd10_individual_bolivares_cantidad_ded DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey;
				ALTER TABLE cnmd10_individual_bolivares_cantidad DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_1;
				ALTER TABLE cnmd10_individual_bolivares_cantidad DROP CONSTRAINT cnmd10_individual_bolivares_cantidad_cod_presi_fkey;
				ALTER TABLE cnmd07_transacciones_actuales DROP CONSTRAINT cnmd07_ficha;
				ALTER TABLE cnmd07_calculo_bonovaca DROP CONSTRAINT cnmd07_calculo_bonovaca;
				ALTER TABLE cnmd07_calculo_aguinaldos DROP CONSTRAINT cnmd07_calculo_aguinaldo_1;
				ALTER TABLE cnmd06_datos_experiencia_administrativa DROP CONSTRAINT cnmd06_datos_experiencia_administrativa_1;
				ALTER TABLE cnmd06_datos_amonestaciones DROP CONSTRAINT cnmd06_datos_amonestaciones_1;
				ALTER TABLE cnmd06_datos_educativos DROP CONSTRAINT cnmd06_datos_educativos_1;
				ALTER TABLE cnmd06_datos_formacion_profesional DROP CONSTRAINT cnmd06_datos_formacion_profesional_1;
				ALTER TABLE cnmd06_datos_registro_titulo DROP CONSTRAINT cnmd06_datos_registro_titulo_1;
				ALTER TABLE cnmd06_datos_otrasexperiencias_laborables DROP CONSTRAINT cnmd06_datos_otrasexperiencias_laborales_1;
				ALTER TABLE cnmd06_datos_bienes DROP CONSTRAINT cnmd06_datos_bienes_1;
				ALTER TABLE cnmd06_datos_permisos DROP CONSTRAINT cnmd06_datos_permisos_1;
				ALTER TABLE cnmd08_historia_trabajador DROP CONSTRAINT cnmd08_historia_trabajador_1;
				ALTER TABLE cnmd08_historia_trabajador_fideicomiso DROP CONSTRAINT cnmd08_historia_trabajador_fideicomiso_1;
				ALTER TABLE cnmd06_datos_personales DROP CONSTRAINT cnmd06_datos_personales_pkey;
				ALTER TABLE cnmd06_datos_educativos DROP CONSTRAINT cnmd06_datos_educativos_pkey;
				ALTER TABLE cnmd06_datos_formacion_profesional DROP CONSTRAINT cnmd06_datos_formacion_profesional_pkey;
				ALTER TABLE cnmd06_datos_registro_titulo DROP CONSTRAINT cnmd06_datos_registro_titulo_pkey;
				ALTER TABLE cnmd06_datos_familiares DROP CONSTRAINT cnmd06_datos_familiares_pkey;
				ALTER TABLE cnmd06_datos_otrasexperiencias_laborables DROP CONSTRAINT cnmd06_datos_otrasexperiencias_laborables_pkey;
				ALTER TABLE cnmd06_datos_bienes DROP CONSTRAINT cnmd06_datos_bienes_pkey;
				ALTER TABLE cnmd06_soportes DROP CONSTRAINT cnmd06_soportes_pkey;
				ALTER TABLE cnmd06_datos_permisos DROP CONSTRAINT cnmd06_datos_permisos_pkey;
				ALTER TABLE cnmd06_datos_amonestaciones DROP CONSTRAINT cnmd06_datos_amonestaciones_pkey;
				ALTER TABLE cnmd07_transacciones_prenomina DROP CONSTRAINT cnmd07_transacciones_prenomina_1;
				ALTER TABLE cnmd06_fichas DROP CONSTRAINT cnmd06_fichas_pkey;
				ALTER TABLE cnmd08_historia_trabajador DROP CONSTRAINT cnmd08_historia_trabajador_pkey;
				ALTER TABLE cnmd08_historia_trabajador_fideicomiso DROP CONSTRAINT cnmd08_historia_trabajador_fideicomiso_pkey;
				ALTER TABLE cnmd06_datos_experiencia_administrativa DROP CONSTRAINT cnmd06_datos_experiencia_administrativa_pkey;
						");
		if($delete > 1){
			$update  = 'update cnmd06_datos_personales set cedula_identidad='.$campo_b.' where cedula_identidad='.$campo_a.';';
			$update .= 'update cnmd06_datos_educativos set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_formacion_profesional set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_registro_titulo set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_familiares set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_experiencia_administrativa set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_otrasexperiencias_laborables set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_bienes set cedula_identidad='.$campo_b.' where cedula_identidad='.$campo_a.';';
			$update .= 'update cnmd06_soportes set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_permisos set cedula='.$campo_b.' where cedula='.$campo_a.';';
			$update .= 'update cnmd06_datos_amonestaciones set cedula='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula='.$campo_a.';';
			$update .= 'update cnmd06_fichas set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
			$update .= 'update cnmd06_fichas_historial_cambios_ascensos set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
			$update .= 'update cnmd08_historia_trabajador set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
			$update .= 'update cnmd08_historia_trabajador_fideicomiso set cedula_identidad='.$campo_b.' where '.$this->SQLCA_noDEP().' and cedula_identidad='.$campo_a.';';
			$upddate1 = $this->cnmd06_datos_personales->execute($update);
					if($upddate1 > 1){
					                     	$crear = $this->cnmd06_datos_personales->execute("
					ALTER TABLE cnmd06_datos_experiencia_administrativa
					ADD CONSTRAINT cnmd06_datos_experiencia_administrativa_pkey PRIMARY KEY(cedula, consecutivo);
					ALTER TABLE cnmd08_historia_trabajador
					ADD CONSTRAINT cnmd08_historia_trabajador_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha);
					ALTER TABLE cnmd08_historia_trabajador_fideicomiso
					ADD CONSTRAINT cnmd08_historia_trabajador_fideicomiso_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha);
					ALTER TABLE cnmd06_fichas
					ADD CONSTRAINT cnmd06_fichas_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha);
					ALTER TABLE cnmd06_datos_amonestaciones
					ADD CONSTRAINT cnmd06_datos_amonestaciones_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula, cod_amonestacion, consecutivo);
					ALTER TABLE cnmd06_datos_permisos
					ADD CONSTRAINT cnmd06_datos_permisos_pkey PRIMARY KEY(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula, cod_permiso, consecutivo);
					ALTER TABLE cnmd06_soportes
					ADD CONSTRAINT cnmd06_soportes_pkey PRIMARY KEY(cedula, cod_soporte);
					ALTER TABLE cnmd06_datos_bienes
					ADD CONSTRAINT cnmd06_datos_bienes_pkey PRIMARY KEY(cedula_identidad, cod_bien, consecutivo);
					ALTER TABLE cnmd06_datos_otrasexperiencias_laborables
					ADD CONSTRAINT cnmd06_datos_otrasexperiencias_laborables_pkey PRIMARY KEY(cedula, consecutivo);
					ALTER TABLE cnmd06_datos_familiares
					ADD CONSTRAINT cnmd06_datos_familiares_pkey PRIMARY KEY(cedula, cod_parentesco, consecutivo);
					ALTER TABLE cnmd06_datos_registro_titulo
					ADD CONSTRAINT cnmd06_datos_registro_titulo_pkey PRIMARY KEY(cedula, cod_profesion, consecutivo);
					ALTER TABLE cnmd06_datos_formacion_profesional
					ADD CONSTRAINT cnmd06_datos_formacion_profesional_pkey PRIMARY KEY(cedula, cod_curso, consecutivo);
					ALTER TABLE cnmd06_datos_educativos
					ADD CONSTRAINT cnmd06_datos_educativos_pkey PRIMARY KEY(cedula, cod_nivel_educacion, consecutivo);
					ALTER TABLE cnmd06_datos_personales
					ADD CONSTRAINT cnmd06_datos_personales_pkey PRIMARY KEY(cedula_identidad);
					ALTER TABLE cnmd08_historia_trabajador
					ADD CONSTRAINT cnmd08_historia_trabajador_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina)
					REFERENCES cnmd08_historia_nomina (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd08_historia_trabajador_fideicomiso
					ADD CONSTRAINT cnmd08_historia_trabajador_fideicomiso_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina)
					REFERENCES cnmd08_historia_nomina_fideicomiso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_permisos
					ADD CONSTRAINT cnmd06_datos_permisos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_bienes
					ADD CONSTRAINT cnmd06_datos_bienes_1 FOREIGN KEY (cedula_identidad)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_otrasexperiencias_laborables
					ADD CONSTRAINT cnmd06_datos_otrasexperiencias_laborales_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_registro_titulo
					ADD CONSTRAINT cnmd06_datos_registro_titulo_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_formacion_profesional
					ADD CONSTRAINT cnmd06_datos_formacion_profesional_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_experiencia_administrativa
					ADD CONSTRAINT cnmd06_datos_experiencia_administrativa_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_amonestaciones
					ADD CONSTRAINT cnmd06_datos_amonestaciones_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_datos_educativos
					ADD CONSTRAINT cnmd06_datos_educativos_1 FOREIGN KEY (cedula)
					REFERENCES cnmd06_datos_personales (cedula_identidad) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd07_calculo_aguinaldos
					ADD CONSTRAINT cnmd07_calculo_aguinaldo_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd07_calculo_bonovaca
					ADD CONSTRAINT cnmd07_calculo_bonovaca FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd07_transacciones_actuales
					ADD CONSTRAINT cnmd07_ficha FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_bolivares_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_bolivares_cantidad_ded_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_cantidad_horas_cantidad
					ADD CONSTRAINT cnmd10_individual_cantidad_horas_cantidad_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_dias_cantidad
					ADD CONSTRAINT cnmd10_individual_dias_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_cantidad
					ADD CONSTRAINT cnmd10_individual_porcentaje_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_cantidad_ded
					ADD CONSTRAINT cnmd10_individual_porcentaje_cantidad_ded_cod_presi_fkey1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd10_individual_porcentaje_horas_cantidad
					ADD CONSTRAINT cnmd10_individual_porcentaje_horas_cantidad_cod_presi_fkey FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd08_historia_transacciones
					ADD CONSTRAINT cnmd08_historia_transacciones_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd08_historia_trabajador (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd08_historia_transacciones_fideicomiso
					ADD CONSTRAINT cnmd08_historia_transacciones_fideicomiso_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd08_historia_trabajador_fideicomiso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
					ALTER TABLE cnmd06_fichas_historial_condicion
  					ADD CONSTRAINT cnmd06_fichas_historial_condicion_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
      				REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
      				ON UPDATE NO ACTION ON DELETE CASCADE;
      				ALTER TABLE cnmd07_transacciones_actuales_faov_tmp
  					ADD CONSTRAINT cnmd07_ficha FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
      				REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
     			 	ON UPDATE NO ACTION ON DELETE CASCADE;
     			 	ALTER TABLE cnmd07_transacciones_prenomina
					ADD CONSTRAINT cnmd07_transacciones_prenomina_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha)
					REFERENCES cnmd06_fichas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha) MATCH SIMPLE
					ON UPDATE NO ACTION ON DELETE CASCADE;
     			 			");
					if($crear > 1){
						$this->set('Message_existe', 'Datos actualizados con exito.');
						$this->cnmd06_datos_personales->execute('COMMIT;');
					}else{
						$this->set('errorMessage', 'No se pudo actualizar los datos.');
						$this->cnmd06_datos_personales->execute('ROLLBACK;');

					}

				}else{
						$this->set('errorMessage', 'No se pudo actualizar la c&eacute;dula de identidad.');
						$this->cnmd06_datos_personales->execute('ROLLBACK;');
		}

  $this->index();
  $this->render("index");
  $this->data=null;


  }//fin else




}//fin function



 }
?>