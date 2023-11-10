<?php
 class shp000ReemplazarContribuyenteController extends AppController{
	    var $uses    = array('shd700_credito_vivienda','shd600_solicitud_arrendamiento','shd500_aseo_domiciliario','shd400_propiedad',
                         'shd300_propaganda','shd200_vehiculos','shd900_cobranza_diaria','v_grilla_constribuyentes','v_shd001_registro_contribuyentes',
                         'cugd01_republica','shd001_registro_contribuyentes', 'shd100_solicitud', 'shd100_solicitud_activ', 'ccfd04_cierre_mes',
                         'cnmd06_profesiones','cugd01_republica','shd001_registro_contribuyentes','cugd01_estados','cugd01_municipios',
                         'cugd01_parroquias','cugd01_centropoblados','cugd01_vialidad','cugd01_vereda', 'cugd90_municipio_defecto', "cugd01_cuadra",
                         "v_shd001_registro_con_consul", "shd100_declaracion_actividades", "shd100_declaracion_ingresos", "shd100_declaracion_ingresos_convenimientos",
                         "shd100_declaracion_ingresos_facturado", "shd100_dec_ing_fac_conve", "shd100_patente", "shd100_patente_actividades", "shd300_detalles_adicional", "shd300_detalles_adicional",
                         "shd300_detalles_propaganda", "shd600_aprobacion_arrendamiento", "shd600_compra_terreno", "shd700_credito_vivienda_parentesco", "shd900_planillas_deuda_cobro_detalles", "shd950_solvencia");
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp000_reemplazar_contribuyente";


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







function valida_rif($var=null){
	$this->layout ="ajax";


$var = str_replace(" ", "", $var);
$var = trim($var);
$var = str_replace(",", "", $var);
$var = str_replace(";", "", $var);
$var = strtoupper($var);


  		   $sw = $this->shd001_registro_contribuyentes->findCount("rif_cedula='".$var."'  ");
  		if($sw==0){
  			$this->set('msg_error',"El CONTRIBUYENTE no se encuentra registrado");
  		}else{
  		}

$this->set('var',$var);
$this->set('contar',$sw);
}





















function index($var=null, $var2=null){
	$this->layout = "ajax";


}//fin index




function reemplazar($rif=null){

$this->layout = "ajax";

$sql ="rif_cedula='".$rif."'";


		if(!empty($this->data['shp001_registro_contribuyentes']['rif_actual'])  && !empty($this->data['shp001_registro_contribuyentes']['rif_nuevo'])){

			$rif_actual			    = $this->data['shp001_registro_contribuyentes']['rif_actual'];
			$rif_nuevo			    = $this->data['shp001_registro_contribuyentes']['rif_nuevo'];

			$v1=$this->shd900_cobranza_diaria->findCount($sql);
			$v2=$this->shd100_solicitud->findCount($sql);
			$v3=$this->shd200_vehiculos->findCount($sql);
			$v4=$this->shd300_propaganda->findCount($sql);
			$v5=$this->shd400_propiedad->findCount($sql);
			$v6=$this->shd500_aseo_domiciliario->findCount($sql);
			$v7=$this->shd600_solicitud_arrendamiento->findCount($sql);
			$v8=$this->shd700_credito_vivienda->findCount($sql);
			$v9=$this->shd100_declaracion_actividades->findCount($sql);
			$v10=$this->shd100_declaracion_ingresos->findCount($sql);
			$v11=$this->shd100_declaracion_ingresos_convenimientos->findCount($sql);
			$v12=$this->shd100_declaracion_ingresos_facturado->findCount($sql);
			$v13=$this->shd100_dec_ing_fac_conve->findCount($sql);
			$v14=$this->shd100_patente->findCount($sql);
			$v15=$this->shd100_patente_actividades->findCount($sql);
			$v16=$this->shd300_detalles_adicional->findCount($sql);
			$v17=$this->shd300_detalles_propaganda->findCount($sql);
			$v18=$this->shd600_aprobacion_arrendamiento->findCount($sql);
			$v19=$this->shd600_compra_terreno->findCount($sql);
			$v20=$this->shd700_credito_vivienda_parentesco->findCount($sql);
			$v21=$this->shd900_planillas_deuda_cobro_detalles->findCount($sql);
			$v22=$this->shd950_solvencia->findCount($sql);


			$v23=$this->shd001_registro_contribuyentes->findCount("rif_cedula='".$rif_actual."'   ");


			if($v23!=0){


					            $guardar  =" BEGIN; ";
					            $guardar  = "ALTER TABLE shd100_declaracion_actividades DROP CONSTRAINT shd100_declaracion_actividades_1;
											 ALTER TABLE shd100_declaracion_ingresos DROP CONSTRAINT shd100_declaracion_ingresos_1;
											 ALTER TABLE shd100_patente_actividades DROP CONSTRAINT shd100_patente_actividades_1;
											 ALTER TABLE shd600_aprobacion_arrendamiento DROP CONSTRAINT shd600_aprobacion_arrendamiento_1;
											 ALTER TABLE shd600_compra_terreno DROP CONSTRAINT shd600_compra_terreno_1;
											 ALTER TABLE shd700_credito_vivienda_parentesco DROP CONSTRAINT shd700_credito_vivienda_parentesco_1;";
								   $guardar  .="  delete from shd001_registro_contribuyentes  where rif_cedula='".$rif_nuevo."'; ";
					               $guardar  .="  update shd900_cobranza_diaria            set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd100_solicitud                  set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd200_vehiculos                  set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd300_propaganda                 set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd400_propiedad                  set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd500_aseo_domiciliario          set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd600_solicitud_arrendamiento    set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd700_credito_vivienda           set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd001_registro_contribuyentes    set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd100_declaracion_ingresos       set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd100_declaracion_actividades    set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar  .="  update shd100_declaracion_ingresos_convenimientos                 set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1  ="  update shd100_declaracion_ingresos_facturado                      set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd100_declaracion_ingresos_facturado_convenimientos       set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd100_patente                    set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd100_patente_actividades        set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd300_detalles_adicional         set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd300_detalles_propaganda        set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd600_aprobacion_arrendamiento   set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd600_compra_terreno             set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd700_credito_vivienda_parentesco             set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd900_planillas_deuda_cobro_detalles          set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";
					               $guardar1 .="  update shd950_solvencia                               set  rif_cedula='".$rif_nuevo."'     where rif_cedula='".$rif_actual."'; ";

					               $guardar1 .="ALTER TABLE shd700_credito_vivienda_parentesco
												  ADD CONSTRAINT shd700_credito_vivienda_parentesco_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
												      REFERENCES shd700_credito_vivienda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
												      ON UPDATE NO ACTION ON DELETE CASCADE;

												ALTER TABLE shd600_compra_terreno
												  ADD CONSTRAINT shd600_compra_terreno_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
												      REFERENCES shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
												      ON UPDATE NO ACTION ON DELETE CASCADE;

												ALTER TABLE shd600_aprobacion_arrendamiento
												  ADD CONSTRAINT shd600_aprobacion_arrendamiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud)
												      REFERENCES shd600_solicitud_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) MATCH SIMPLE
												      ON UPDATE NO ACTION ON DELETE CASCADE;
												ALTER TABLE shd100_patente_actividades
												  ADD CONSTRAINT shd100_patente_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
												      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
												      ON UPDATE NO ACTION ON DELETE CASCADE;

												ALTER TABLE shd100_declaracion_ingresos
												  ADD CONSTRAINT shd100_declaracion_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
												      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
												      ON UPDATE NO ACTION ON DELETE CASCADE;

												ALTER TABLE shd100_declaracion_actividades
												  ADD CONSTRAINT shd100_declaracion_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion)
												      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion) MATCH SIMPLE
												      ON UPDATE NO ACTION ON DELETE CASCADE;";

								$sw = $this->shd001_registro_contribuyentes->execute($guardar);
								if($sw>1){
									   $sw = $this->shd001_registro_contribuyentes->execute($guardar1);
									if($sw>1){
										$this->set('Message_existe', 'EL RIF FUE REEMPLAZADO');
										$this->shd001_registro_contribuyentes->execute("COMMIT;");
									}else{
										$this->set('errorMessage',   'EL RIF NO FUE REEMPLAZADO');
										$this->shd001_registro_contribuyentes->execute("ROLLBACK;");
									}
								}else{
									$this->set('errorMessage',   'EL RIF NO FUE REEMPLAZADO');
									$this->shd001_registro_contribuyentes->execute("ROLLBACK;");
								}

					}else{
			             $this->set('errorMessage', 'IDENTIFICACIÓN ACTUAL NO EXISTE');
					}

		}else{
          $this->set('errorMessage', 'FALTAN DATOS - POR FAVOR INTENTE DE NUEVO');
		}




}//fin function







}