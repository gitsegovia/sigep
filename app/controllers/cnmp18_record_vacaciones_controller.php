<?php

class Cnmp18RecordVacacionesController extends AppController{

	var $name = 'cnmp18_record_vacaciones';
	var $uses = array('Cnmd01', "cnmd18_record_vacaciones", "v_cnmd06_fichas_datos_personales", "cnmd06_fichas", "v_cnmd06_fichas_2", "cnmd06_datos_personales", "v_cnmd05");
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

    function SQLCA($ano=null){ //sql para busqueda de codigos de arranque con y sin año
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

    function concatena($vector1=null, $nomVar=null){
        if($vector1 != null){
            foreach($vector1 as $x => $y){$cod[$x] = $this->zero($x).' - '.$y;}
            $this->set($nomVar, $cod);
        }//fin if
    }//fin concatena

    function index(){
        $this->layout = "ajax";

        $lista = $this->Cnmd01->generateList($this->SQLCA()." AND status_nomina IN (0,1)", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
        $this->concatenaN($lista, 'cod_tipo_nomina');
    }

    function codigo_nomina($codigo=null){
        $this->layout = "ajax";
        $a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
        $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
        $this->Session->write('tipo_nomina',$codigo);

        if($codigo!=null){

                        echo "<script>";
                            echo "document.getElementById('segunda_ventana').disabled=false;";
                        echo "</script>";
        }else{
                        echo "<script>";
                            echo "document.getElementById('segunda_ventana').disabled=true;";
                        echo "</script>";
        }
    }//fin codigo_nomina

    function denominacion_nomina($codigo){
        $this->layout = "ajax";
        $b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
        $this->set("b",$b[0]['Cnmd01']['denominacion']);

    }//fin denominacion_nomina

    function buscar_persona($var1=null){
        $this->layout="ajax";
        $this->set("opcion",$var1);
        $this->Session->write('pista_opcion', 2);
        echo "<script>$('select_obra_cod_obra').focus();</script>";
    }//fin function

    function buscar_por_pista($var1=null, $var2=null){

        $this->layout="ajax";
        $sql_like = "";
        $tipo_nomina=   $this->Session->read('tipo_nomina');
    
        $var2 = strtoupper($var2);
        $this->Session->write('pista', $var2);
        $var_like = $var2;
        $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."' and condicion_actividad='1'   ";
        $Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($sql_like);
        if($Tfilas!=0){
            $pagina=1;
            $Tfilas=(int)ceil($Tfilas/100);
            $this->set('pag_cant',$pagina.'/'.$Tfilas);
            $this->set('total_paginas',$Tfilas);
            $this->set('pagina_actual',$pagina);
            $this->set('ultimo',$Tfilas);
            $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($sql_like,null,"primer_nombre, primer_apellido ASC",100,1,null);
            $sql = "";
            $this->set("datosFILAS",$datos_filas);
            $this->set('siguiente',$pagina+1);
            $this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
          }else{
            $this->set("datosFILAS",'');
          }
            
        $this->set("opcion",$var1);
    }//fin function

    function llenar_pista_opcion($var1=null){

        $this->layout="ajax";
        $this->Session->write('pista_opcion', $var1);

    }//fin fucntion

    function mostrar_busqueda($var_nom=null, $var1=null, $var2=null, $var3=null, $pag_num=null, $var_read=null){
        $this->layout="ajax";
        $var2 = strtoupper($var2);
        $var_min = strtolower($var2);
        $var_wrap = ucfirst($var_min);
        $this->set('var2', $var2);
        $var = $var_nom;
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
        $condi_transac = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

        $cod_cargo[1]     = "";
        $cod_ficha[1]     = "";
        $fichas        = "";
        $datos_cnmd05  = "";
        $datos_peronal = "";
        $acepta        = "no";
        $cedu          = "";

        if($pag_num==null){$pag_num = 1;}

        if($var!=null){
            $c_nomina = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$var, array('cod_tipo_nomina','denominacion'));
            $deno_nomi = $c_nomina!=null ? $c_nomina[0]['Cnmd01']['denominacion'] : "";

            echo "<script>
                    document.getElementById('cod_tipo_nomina').disabled = true;
                    document.getElementById('codigo_tipo_nom').value = '".mascara($var, 3)."';
                    document.getElementById('denominacion_deno_nom').value = '".$deno_nomi."';
                </script>";

            $fichas_aux    =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2);

            $i = 0;

            foreach($fichas_aux as $aux){ $i++;
                 $cod_cargo[$i] =  $aux['cnmd06_fichas']['cod_cargo'];
                 $cod_ficha[$i] =  $aux['cnmd06_fichas']['cod_ficha'];
            }//fin foreach

            if($cod_cargo[$pag_num]!=""){
                $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1.'  ');
                $datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var1.'  ');
                $datos_cnmd05  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' ');
                
                
                $this->set('ficha', $fichas);
                $this->set('datos_cnmd05', $datos_cnmd05);
                $this->set('datos_personales', $datos_peronal);

            }else{
               $this->set('mensajeError', 'La cedula de identidad no existe');
               
            }//fin else

        }else{

            echo'<script>';
                echo'document.getElementById("cod_cargo").value = ""; ';
                echo'document.getElementById("cod_puesto").value = "";';
                echo'document.getElementById("deno_puesto").value = "";';
            echo'</script>';

        }//fin else

    }//fin function

    function record_vacaciones($ci = null){
        $this->layout="ajax";
        $data = $this->cnmd18_record_vacaciones->execute(
                                "SELECT * FROM v_cnmd06_fichas_2 
                                WHERE ".$this->SQLCA()." and cedula_identidad = " . $ci." 
                                ORDER BY fecha_ingreso DESC, fecha_condicion ASC;");

        //$data = $this->v_cnmd06_fichas_2->findAll($this->SQLCA()." and cedula_identidad = " . $ci, null, "fecha_condicion DESC, condicion_actual ASC");
        
        $records = $this->cnmd18_record_vacaciones->findAll($this->SQLCA()." and cedula_identidad = " . $ci, null, "periodo_inicio ASC");
        
        $cantidad = $this->cnmd18_record_vacaciones->findCount($this->SQLCA()." and cedula_identidad = " . $ci ." and vac_pendiente = 1");
        
        $this->set('ci', $ci);
        $this->set('data', $data);
        $this->set('records', $records);
        $this->set('cantidad', $cantidad);
    }

    function update_record_vacaciones($ci = null){
        $this->layout="ajax";
        $error = 0;
        foreach ($this->data as $data) {
            if(array_key_exists('vac_pendiente', $data)){
                if($data['dias_vac_disf_mas'] >= 0){
                    if($data['dias_pendientes'] >= 0){
                        if(!empty($data['num_folio'])){
                                   
                            $sql = "UPDATE cnmd18_record_vacaciones 
                                    SET vac_disfrutadas = 1".
                                        ", vac_pendiente = ".$data['vac_pendiente'].
                                        ", dias_vac_disf_mas = ".$data['dias_vac_disf_mas'].
                                        ", dias_pendientes = ".$data['dias_pendientes'].
                                        ", num_folio = '".$data['num_folio']."' 
                                    WHERE 
                                        ".$this->SQLCA()." and cedula_identidad = ".$ci." and periodo_inicio = ".$data['periodo_inicio'];
                   
                            $sw = $this->cnmd18_record_vacaciones->execute($sql);
                            if($sw > 1){
                                $this->set('Message_existe', 'La informacion se registro correctamente.');
                            }else{
                                $this->set('errorMessage', 'Error al momento de registrar el periodo.');
                            }
                        }else{
                            $this->set('errorMessage', 'El campo numero de folio del perido vacacional '.$data["periodo_inicio"].' - '.$data["periodo_fin"]. ' esta vacio.');
                        }
                    }else{
                        $this->set('errorMessage', 'Los dias pendientes del perido vacacional '.$data["periodo_inicio"].' - '.$data["periodo_fin"]. ' esta vacio.');
                    }
                }else{
                    $this->set('errorMessage', 'Los dias de destrufe de mas del perido vacacional '.$data["periodo_inicio"].' - '.$data["periodo_fin"]. ' esta vacio.');
                }
            }else{
                $this->set('errorMessage', 'El campo vacaciones pendientes del perido vacacional '.$data["periodo_inicio"].' - '.$data["periodo_fin"]. ' esta vacio.');
            }

            /*if($error){
                $this->set('errorMessage', 'No se pudo realizar el registro. Por favor rellenar todo los campos.');
            }*/
            /*}else{
                $this->set('errorMessage', 'El perido vacacional '.$data["periodo_inicio"].' - '.$data["periodo_fin"]. ' no se registro. Por favor verifique los datos.');
            }*/

            $data = $this->cnmd18_record_vacaciones->execute(
                                    "SELECT * FROM v_cnmd06_fichas_2 
                                    WHERE ".$this->SQLCA()." and cedula_identidad = " . $ci." 
                                    ORDER BY fecha_ingreso DESC, fecha_condicion ASC;");

            $records = $this->cnmd18_record_vacaciones->findAll($this->SQLCA()." and cedula_identidad = " . $ci, null, "periodo_inicio ASC");

            $cantidad = $this->cnmd18_record_vacaciones->findCount($this->SQLCA()." and cedula_identidad = " . $ci ." and vac_pendiente = 1");
                  
            $this->set('ci', $ci);
            $this->set('data', $data);
            $this->set('records', $records);
            $this->set('cantidad', $cantidad);
        }
    }

}
?>