<?php

 class Cnmp99CierreNominaController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','Cnmd01','v_cnmd07_transacciones_actuales_frecuencias2','trasacciones_no_conectadas',
                      'cargos_anos_diferentes','cnmd06_fichas','cugd05_restriccion_clave','cnmd08_historia_nomina',
                      'cnmd08_historia_nomina_fideicomiso','v_cnmd06_fichas_trab_que_cobran','costo_presupuestario_p2');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
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

function index () {

$this->verifica_entrada('109');

    $this->layout="ajax";
    if($this->verifica_SS(1)==1 && $this->verifica_SS(2)==11 && $this->verifica_SS(3)==30 && $this->verifica_SS(4)==11){
        $status_nomina="2";
    }else{
    	$status_nomina="4";
    }

    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=".$status_nomina, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=".$status_nomina)!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
	//echo $_SERVER['HTTP_USER_AGENT'];
}//fin index

function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     $this->Session->delete('autor_valido');
     if($this->verifica_SS(1)==1 && $this->verifica_SS(2)==11 && $this->verifica_SS(3)==30 && $this->verifica_SS(4)==11){
        $status_nomina="2";
     }else{
    	$status_nomina="4";
     }
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=$status_nomina and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago,modalidad_pago');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=$status_nomina and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}
}

function procesar () {
  ini_set("memory_limit","2560M");
  $this->layout="ajax";
  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $cod_dep       = $this->Session->read('SScoddep');

  if(!empty($this->data["cnmp99_prenomina"]["correspondientes"])){
    $datos=$this->data["cnmp99_prenomina"];
    $cod_tipo_nomina = $datos["cod_tipo_nomina"];

    $data_transacciones=$this->v_cnmd07_transacciones_actuales_frecuencias2->execute("SELECT * FROM v_cnmd07_transacciones_actuales_frecuencias2 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
    $data_cnmd01=$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
    $data_ficha=$this->v_cnmd06_fichas_trab_que_cobran->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and condicion_actividad=1");

    $this->Cnmd01->execute("DELETE FROM cnmd08_historia_transacciones_fideicomiso WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').' and numero_nomina='.$data_cnmd01[0]['Cnmd01']['numero_nomina']);
    $this->Cnmd01->execute("DELETE FROM cnmd08_historia_trabajador_fideicomiso    WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').' and numero_nomina='.$data_cnmd01[0]['Cnmd01']['numero_nomina']);
    $this->Cnmd01->execute("DELETE FROM cnmd08_historia_nomina_fideicomiso        WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').' and numero_nomina='.$data_cnmd01[0]['Cnmd01']['numero_nomina']);
    $sql_insert_fidei_nom  ="INSERT INTO cnmd08_historia_nomina_fideicomiso(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, periodo_desde, periodo_hasta, concepto, numero_recibo, frecuencia_pago, mensaje_colectivo, cantidad_pagos) VALUES (".$cod_presi.", ".$cod_entidad.", ".$cod_tipo_inst.", ".$cod_inst.", ".$cod_dep.", ".$cod_tipo_nomina.",".divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],"ANO").", ".$data_cnmd01[0]['Cnmd01']['numero_nomina'].", '".$data_cnmd01[0]['Cnmd01']['periodo_desde']."', '".$data_cnmd01[0]['Cnmd01']['periodo_hasta']."', '".$data_cnmd01[0]['Cnmd01']['correspondiente']."', ".$data_cnmd01[0]['Cnmd01']['numero_recibo'].",".$data_cnmd01[0]['Cnmd01']['frecuencia_pago'].", '".$data_cnmd01[0]['Cnmd01']['mensajes_colectivos']."', '".$data_cnmd01[0]['Cnmd01']['cantidad_pagos']."');";
    $cierre11=$this->Cnmd01->execute($sql_insert_fidei_nom);

    $this->Cnmd01->execute("DELETE FROM cnmd08_historia_transacciones WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').' and numero_nomina='.$data_cnmd01[0]['Cnmd01']['numero_nomina']);
    $this->Cnmd01->execute("DELETE FROM cnmd08_historia_trabajador    WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').' and numero_nomina='.$data_cnmd01[0]['Cnmd01']['numero_nomina']);
    $this->Cnmd01->execute("DELETE FROM cnmd08_historia_nomina        WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').' and numero_nomina='.$data_cnmd01[0]['Cnmd01']['numero_nomina']);
    $this->Cnmd01->execute("BEGIN;");
    $sql_insert1  ="INSERT INTO cnmd08_historia_nomina(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, periodo_desde, periodo_hasta, concepto, numero_recibo, frecuencia_pago, mensaje_colectivo, cantidad_pagos) VALUES (".$cod_presi.", ".$cod_entidad.", ".$cod_tipo_inst.", ".$cod_inst.", ".$cod_dep.", ".$cod_tipo_nomina.",".divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],"ANO").", ".$data_cnmd01[0]['Cnmd01']['numero_nomina'].", '".$data_cnmd01[0]['Cnmd01']['periodo_desde']."', '".$data_cnmd01[0]['Cnmd01']['periodo_hasta']."', '".$data_cnmd01[0]['Cnmd01']['correspondiente']."', ".$data_cnmd01[0]['Cnmd01']['numero_recibo'].",".$data_cnmd01[0]['Cnmd01']['frecuencia_pago'].", '".$data_cnmd01[0]['Cnmd01']['mensajes_colectivos']."', '".$data_cnmd01[0]['Cnmd01']['cantidad_pagos']."');";
    $cierre1=$this->Cnmd01->execute($sql_insert1);


    $sql_insert1_2="INSERT INTO cnmd09_numero_nominas_canceladas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, numero_nomina, periodo_desde, periodo_hasta, concepto) VALUES (".$cod_presi.", ".$cod_entidad.", ".$cod_tipo_inst.", ".$cod_inst.", ".$cod_dep.", ".$cod_tipo_nomina.",".$data_cnmd01[0]['Cnmd01']['numero_nomina'].", '".$data_cnmd01[0]['Cnmd01']['periodo_desde']."', '".$data_cnmd01[0]['Cnmd01']['periodo_hasta']."', '".$data_cnmd01[0]['Cnmd01']['correspondiente']."');";
    $cierre2=$this->Cnmd01->execute($sql_insert1_2);

    if($cierre2>0){
      $sql_insert_fidei_trab="INSERT INTO cnmd08_historia_trabajador_fideicomiso(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cedula_identidad,dias_cobro, acumulado_prestaciones, mensaje_personal, numero_recibo) VALUES ";
      $sql_insert2="INSERT INTO cnmd08_historia_trabajador(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cedula_identidad,dias_cobro, acumulado_prestaciones, mensaje_personal, numero_recibo) VALUES ";
      foreach($data_ficha as $rsdata){
        $rs=$rsdata['v_cnmd06_fichas_trab_que_cobran'];
        $values_fichas[] = " (".$rs['cod_presi'].", ".$rs['cod_entidad'].", ".$rs['cod_tipo_inst'].", ".$rs['cod_inst'].", ".$rs['cod_dep'].", ".$rs['cod_tipo_nomina'].",".divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').", ".$data_cnmd01[0]['Cnmd01']['numero_nomina'].", ".$rs['cod_cargo'].", ".$rs['cod_ficha'].", ".$rs['cedula_identidad'].",".$this->_cobro($data_cnmd01[0]['Cnmd01']['frecuencia_cobro'],$data_cnmd01[0]['Cnmd01']['dias_cobro']).",0, '".$rs['mensaje_personal']."',".$rs['ultimo_recibo'].")";
      }

      $sql_insert_fidei_trab .= " ".implode(',', $values_fichas).";";
      $sql_insert2 .= " ".implode(',', $values_fichas).";";
      $cierre33=$this->Cnmd01->execute($sql_insert_fidei_trab);

      $cierre3=$this->Cnmd01->execute($sql_insert2);

      if($cierre3>0){
        $sql_insert_fidei_transa="INSERT INTO cnmd08_historia_transacciones_fideicomiso(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_canceladas, monto_cuota, saldo, dias_horas) VALUES ";
        $sql_insert3="INSERT INTO cnmd08_historia_transacciones(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_canceladas, monto_cuota, saldo, dias_horas) VALUES ";
        foreach($data_transacciones as $data_transaccion){
          $rs_tra=$data_transaccion[0];
          if($rs_tra['dias_horas']==null || $rs_tra['dias_horas']==''){
            $ddias_horas = 0;
          }else{
            $ddias_horas = $rs_tra['dias_horas'];
          }
          $values_tran[]=" (".$rs_tra['cod_presi'].", ".$rs_tra['cod_entidad'].", ".$rs_tra['cod_tipo_inst'].", ".$rs_tra['cod_inst'].",".$rs_tra['cod_dep'].", ".$rs_tra['cod_tipo_nomina'].",".divide_fecha($data_cnmd01[0]['Cnmd01']['periodo_desde'],'ANO').", ".$data_cnmd01[0]['Cnmd01']['numero_nomina'].", ".$rs_tra['cod_cargo'].", ".$rs_tra['cod_ficha'].", ".$rs_tra['cod_tipo_transaccion'].",".$rs_tra['cod_transaccion'].", '".$rs_tra['fecha_transaccion']."', ".$rs_tra['monto_original'].", ".$rs_tra['numero_cuotas_descontar'].",".$rs_tra['numero_cuotas_canceladas'].", ".$rs_tra['monto_cuota'].", ".$rs_tra['saldo'].", ".$ddias_horas.")";
        }
        $sql_insert_fidei_transa .= " ".implode(',', $values_tran).";";
        $sql_insert3 .= " ".implode(',', $values_tran).";";
        $cierre44=$this->Cnmd01->execute($sql_insert_fidei_transa);
        $cierre4=$this->Cnmd01->execute($sql_insert3);

        if($cierre4>0){
          $parametros_corrida=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
          $func_prenomina_revision=$this->Cnmd01->execute("SELECT cierre_nomina($parametros_corrida);");

          if($func_prenomina_revision>0){
            $sql_update_cnmd01="UPDATE cnmd01 SET status_nomina=0 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
            $this->Cnmd01->execute($sql_update_cnmd01);
            /***
            * bajar transacciones prenomina a transaciones actuales cuando es pago unico
            * ojo esto no lo he realizado todavia
            */

            $parametros_frecue=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;

            $E = $this->Cnmd01->execute("SELECT bajar_trans_prenomina_nopagounico($parametros_frecue);");

            $data_transacciones2=$this->v_cnmd07_transacciones_actuales_frecuencias2->execute("SELECT a.*,b.monto_cuota as monto_cuota_pre FROM v_cnmd07_transacciones_actuales_frecuencias2 a,cnmd07_transacciones_prenomina b WHERE a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad  and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_tipo_nomina=b.cod_tipo_nomina and a.cod_cargo=b.cod_cargo and a.cod_ficha=b.cod_ficha and a.cod_tipo_transaccion=b.cod_tipo_transaccion and a.cod_transaccion=b.cod_transaccion and a.cod_presi=$cod_presi and a.cod_entidad=$cod_entidad and a.cod_tipo_inst=$cod_tipo_inst and a.cod_inst=$cod_inst and a.cod_dep=$cod_dep and a.cod_tipo_nomina=$cod_tipo_nomina");
            $sql_update=" ";

            foreach($data_transacciones2 as $data_transaccion2){
              $rs_tra=$data_transaccion2[0];
              
              if($rs_tra['monto_cuota'] != $rs_tra['monto_cuota_pre']){
                $sql_update .= " UPDATE cnmd07_transacciones_actuales SET monto_cuota=".$rs_tra['monto_cuota_pre']." WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$rs_tra['cod_cargo']." and cod_ficha=".$rs_tra['cod_ficha']." and cod_tipo_transaccion=".$rs_tra['cod_tipo_transaccion']." and cod_transaccion=".$rs_tra['cod_transaccion'].";";
              }
            }
            $this->Cnmd01->execute("DELETE FROM cnmd07_transacciones_prenomina WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
            //LIMPIAR MENSAJES
            $this->Cnmd01->execute("UPDATE cnmd01 SET mensajes_colectivos='' WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
            $this->Cnmd01->execute("UPDATE cnmd06_fichas SET mensaje_personal='' WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
            $this->Cnmd01->execute("COMMIT;");
            $this->set('Message_existe','Cierre de N&oacute;mina realizado Exitosamente');
          }else{
            $this->Cnmd01->execute("ROLLBACK;");
            $this->set('errorMessage','No se pudo realizar el cierre de n&oacute;mina');
          }
        }else{//cierre4
          $this->Cnmd01->execute("ROLLBACK;");
          $this->set('errorMessage','[4] No se pudo realizar el cierre de n&oacute;mina.');
        }
      }else{//cierre3
        $this->Cnmd01->execute("ROLLBACK;");
        $this->set('errorMessage','[3] No se pudo realizar el cierre de n&oacute;mina.');
      }
    }else{//cierre2
      $this->Cnmd01->execute("ROLLBACK;");
      $this->set('errorMessage','[2] No se pudo realizar el cierre de n&oacute;mina.');
    }
  }else{
    $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
  }
}//fin procesar



function seleccion_nomina() {
    $this->layout="ajax";
    if($this->verifica_SS(1)==1 && $this->verifica_SS(2)==11 && $this->verifica_SS(3)==30 && $this->verifica_SS(4)==11){
        $status_nomina="2";
     }else{
    	$status_nomina="4";
     }
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=$status_nomina", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=$status_nomina")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin seleccion_nomina

function salir_prenomina ($numero) {
       $this->layout="ajax";
}//fin salir_prenomina


function _cobro($f_cobro,$dias_cobro){
	if($dias_cobro=='' || $dias_cobro==null ){
		if($f_cobro==1){
			$dias_cobro=1;
		}else if($f_cobro==2){
			$dias_cobro=7;
		}else if($f_cobro==3){
			$dias_cobro=15;
		}else if($f_cobro==4){
			$dias_cobro=30;
		}else if($f_cobro==5){
			$dias_cobro=60;
		}else if($f_cobro==6){
			$dias_cobro=90;
		}
	}
	return $dias_cobro;
}


 function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp99_prenomina']['login']) && isset($this->data['cnmp99_prenomina']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp99_prenomina']['login']);
		$paswd=addslashes($this->data['cnmp99_prenomina']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=109 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->render("entrar");
		}
	}
 }


/*
function index_diskette () {
    $this->layout="ajax";
    if($this->verifica_SS(1)==1 && $this->verifica_SS(2)==11 && $this->verifica_SS(3)==30 && $this->verifica_SS(4)==11){
        $status_nomina="1";
    }else{
    	$status_nomina="4";
    }

    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=".$status_nomina, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=".$status_nomina)!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
	//echo $_SERVER['HTTP_USER_AGENT'];
}//fin index

function deno_nomina_diskette ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if($this->verifica_SS(1)==1 && $this->verifica_SS(2)==11 && $this->verifica_SS(3)==30 && $this->verifica_SS(4)==11){
        $status_nomina="1";
     }else{
    	$status_nomina="4";
     }
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=$status_nomina and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=$status_nomina and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}
}

function generar_diskette_cuenta ($cod_tipo_nomina,$cod_entidad_bancaria) {
    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

	if(!empty($cod_tipo_nomina) && !empty($cod_entidad_bancaria)){
		$this->layout="ajax";
//		 $datos           =$this->data["cnmp99_prenomina"];
//         $cod_tipo_nomina = $datos["cod_tipo_nomina"];
         $nombre_archivo = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_tipo_nomina,3).'_'.date('d_m_Y_h:i:sa').'';

         $data_fichas=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cedula_identidad ASC");
		$filas_archivo="";
		foreach($data_fichas as $rsdata){
			extract($rsdata[0]);

			$cedula = up($nacionalidad).mascara($cedula_identidad,10);
			$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
			$nombre_completo = str_replace("\t",' ',$nombre_completo);
			$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
			$nombre = $this->cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
			$nombre = str_replace("@",'Ñ',$nombre);
			$cuenta = $this->formato_cuenta_diskette($cuenta_bancaria);
			$neto_cobrar_1 = $asignaciones - $deducciones;
			$neto_cobrar_2 = mascara($neto_cobrar_1,15);
			$filas_archivo .= $cedula.$nombre.$cuenta.$neto_cobrar_2."\n";
		}
		//$this->set('DATA',$filas_archivo);

		$this->wFile($nombre_archivo, $filas_archivo);
		if(file_exists('../webroot/descargas/'.$nombre_archivo.'.txt')){
			chmod('../webroot/descargas/'.$nombre_archivo.'.txt', 0777);
		}
		$this->set('name', $nombre_archivo);


	}else{
		 $this->layout="ajax";
         $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
	}


}//fin funcion generar_diskette_cuentas
*/



function oficio_orden_pago($var = null){
  if($var == 'no'){
	$this->layout="ajax";

	$this->set('var',$var);
	$status_nomina="4";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=".$status_nomina, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=".$status_nomina)!=0){
		$this->concatenaN($lista, 'tipo_nomina');
		$this->set('status_nomina', $status_nomina);
	}else{
		$this->set('tipo_nomina', array());
		$this->set('status_nomina', "4");
	}

	$firmantes = $this->Cnmd01->execute("SELECT enviado_a, cargo_a, enviado_por, cargo_por, firmante_1,firmante_2,  cargo_firmante_1, cargo_firmante_2 FROM cnmd99_oficio_orden_pago WHERE ".$this->SQLCA()." LIMIT 1;");

		if($firmantes != null){
			$this->set('firma_existe', 'si');
			$this->set('b_readonly','readonly');
			$this->set('enviado_a',$firmantes[0][0]['enviado_a']);
			$this->set('cargo_a',$firmantes[0][0]['cargo_a']);
			$this->set('enviado_por',$firmantes[0][0]['enviado_por']);
			$this->set('cargo_por',$firmantes[0][0]['cargo_por']);
      $this->set('firmante_1',$firmantes[0][0]['firmante_1']);
      $this->set('firmante_2',$firmantes[0][0]['firmante_2']);
      $this->set('cargo_firmante_1',$firmantes[0][0]['cargo_firmante_1']);
      $this->set('cargo_firmante_2',$firmantes[0][0]['cargo_firmante_2']);
		}else{
			$this->set('firma_existe', 'no');
			$this->set('b_readonly','');
			$this->set('enviado_a','');
			$this->set('cargo_a','');
			$this->set('enviado_por','');
			$this->set('cargo_por','');
      $this->set('firmante_1','');
      $this->set('firmante_2','');
      $this->set('cargo_firmante_1','');
      $this->set('cargo_firmante_2','');
			$this->set('Message_existe','Por favor, ingrese los nombres y cargos de los firmantes');
		}
  }else{
		$this->layout = "pdf";
		$cod_nom = $this->data['cnmp99_prenomina']['cod_tipo_nomina'];

    $correspondiente = $this->Cnmd01->field("correspondiente", $this->SQLCA()." and cod_tipo_nomina=".$cod_nom);
    $num_nom = $this->Cnmd01->field("numero_nomina", $this->SQLCA()." and cod_tipo_nomina=".$cod_nom);
    if($num_nom != null){
    	$numero_oficio = mascara($cod_nom,3).'-'.mascara($num_nom,3);
    }else{
    	$numero_oficio = mascara($cod_nom,3).'-'."S/N";
    }

		$orden = $this->Cnmd01->execute("SELECT * FROM v_cnmp99_prenomina_oficio_op WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_nom;");
		$firmantes = $this->Cnmd01->execute("SELECT enviado_a, cargo_a, enviado_por, cargo_por, firmante_1,firmante_2,  cargo_firmante_1, cargo_firmante_2 FROM cnmd99_oficio_orden_pago WHERE ".$this->SQLCA()." LIMIT 1;");
		$this->set('correspondiente', $correspondiente);
		$this->set('numero_oficio', $numero_oficio);
		$this->set('orden', $orden);
		$this->set('firmantes', $firmantes);
  }
  		$mcpio_defecto=$this->Cnmd01->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE ".$this->SQLCA()."");
	if($mcpio_defecto!=null){
		$cod_republica=$mcpio_defecto[0][0]["cod_republica"];
		$cod_estado=$mcpio_defecto[0][0]["cod_estado"];
		$cod_municipio=$mcpio_defecto[0][0]["cod_municipio"];

			$conocido_como=$this->Cnmd01->execute("SELECT conocido FROM cugd01_municipios WHERE cod_republica = ".$cod_republica." and cod_estado = ".$cod_estado." and cod_municipio = ".$cod_municipio."");
			if($conocido_como!=null){
			$ciudad=$conocido_como[0][0]["conocido"];
			}
	}
        $this->set('ciudad' ,$ciudad);
}


function codeno_nomina($status_nomina=null, $codigo=null){
	$this->layout = "ajax";

if($codigo!=null){
	if($status_nomina != null){}else{$status_nomina="4";}
	$a = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=".$status_nomina." and cod_tipo_nomina=".$codigo, array('cod_tipo_nomina','denominacion'));
	if($a!=null){
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='".mascara($a[0]['Cnmd01']['cod_tipo_nomina'], 3)."';
				document.getElementById('denominacion_nom').value='".$a[0]['Cnmd01']['denominacion']."';
				document.getElementById('b_generar').disabled=false;
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_nom').value='';
				document.getElementById('b_generar').disabled=true;
			</script>";
	}
}else{
	$this->set('errorMessage','No llego el c&oacute;digo del tipo de n&oacute;mina para procesar - Seleccione N&oacute;mina');
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_nom').value='';
				document.getElementById('b_generar').disabled=true;
			</script>";
}
}//fin codeno_nomina



function guardar_editar_firmas($varf=null){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if($varf=='no'){
		$this->set('varf',''.$varf);
		$this->set('Message_existe','Puede modificar los datos de los firmantes...');
	}
else if($varf=='si'){

	$modelo = 'cnmp99_prenomina';

	$enviado_a = isset($this->data[$modelo]['enviado_a']) ? $this->data[$modelo]['enviado_a'] : '';
	$cargo_a = isset($this->data[$modelo]['cargo_a']) ? $this->data[$modelo]['cargo_a'] : '';
	$enviado_por = isset($this->data[$modelo]['enviado_por']) ? $this->data[$modelo]['enviado_por'] : '';
	$cargo_por = isset($this->data[$modelo]['cargo_por']) ? $this->data[$modelo]['cargo_por'] : '';
  $firmante_1 = isset($this->data[$modelo]['firmante_1']) ? $this->data[$modelo]['firmante_1'] : '';
  $firmante_2 = isset($this->data[$modelo]['firmante_2']) ? $this->data[$modelo]['firmante_2'] : '';
  $cargo_firmante_1 = isset($this->data[$modelo]['cargo_firmante_1']) ? $this->data[$modelo]['cargo_firmante_1'] : '';
  $cargo_firmante_2 = isset($this->data[$modelo]['cargo_firmante_2']) ? $this->data[$modelo]['cargo_firmante_2'] : '';

	$enc_firma = $this->Cnmd01->execute("SELECT enviado_a, cargo_a, enviado_por, cargo_por, firmante_1, firmante_2, cargo_firmante_1, cargo_firmante_2 FROM cnmd99_oficio_orden_pago WHERE ".$this->SQLCA()." LIMIT 1;");

	if($enc_firma != null){
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cnmd99_oficio_orden_pago SET enviado_a='$enviado_a', cargo_a='$cargo_a', enviado_por='$enviado_por', cargo_por='$cargo_por', firmante_1='$firmante_1', firmante_2='$firmante_2', cargo_firmante_1='$cargo_firmante_1', cargo_firmante_2='$cargo_firmante_2' WHERE ".$this->SQLCA().";";
	}else{
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cnmd99_oficio_orden_pago VALUES ($cp, $ce, $cti, $ci, $cd, '$enviado_a', '$cargo_a', '$enviado_por', '$cargo_por', '$firmante_1', '$firmante_2', '$cargo_firmante_1', '$cargo_firmante_2');";
	}

	$swi = $this->Cnmd01->execute($sql_ejecutar);

	$this->set('varf',''.$varf);

	if($swi>1){
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}
}else{
	$this->set('errorMessage','Lo siento la informaci&oacute;n no puede ser procesada...');
}
} // fin funcion guardar_editar_firma

}//fin class
?>