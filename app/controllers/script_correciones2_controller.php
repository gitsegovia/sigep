<?php


class ScriptCorreciones2Controller extends AppController {

   var $name = "script_correciones2";
   var $uses = array('cobd01_contratoobras_anticipo_cuerpo','cobd01_contratoobras_anticipo_partidas',
                     'cfpd07_obras_cuerpo','cobd01_contratoobras_cuerpo','cobd01_co_modificacion_cuerpo',
                     'cfpd05','cfpd22_numero_asiento_causado','cobd01_co_modificacion_partidas',
                     'cobd01_contratoobras_partidas','cugd04', 'cfpd22','cstd03_cheque_cuerpo', 'cstd01_entidades_bancarias',
                     'cobd01_contratoobras_valuacion_cuerpo','cobd01_contratoobras_valuacion_partidas',
                     'cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','cfpd21_numero_asiento_compromiso',
                     'cfpd21','cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas',
                     'cepd02_contratoservicio_cuerpo','cepd02_cs_modificacion_cuerpo','cepd02_cs_modificacion_partidas',
                     'cepd02_contratoservicio_partidas','cepd02_contratoservicio_valuacion_cuerpo', 'cfpd20',
                     'cepd02_contratoservicio_valuacion_partidas','cfpd23_numero_asiento_pagado','cfpd23','cstd03_cheque_partidas',
                     'cepd01_compromiso_cuerpo','cepd01_compromiso_partidas','cepd03_ordenpago_cuerpo', 'arrd05',
                     'cepd03_ordenpago_partidas', 'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_facturas','cscd04_ordencompra_encabezado',
                     'cscd04_ordencompra_partidas','cscd04_ordencompra_anticipo_cuerpo','cscd04_ordencompra_a_pago_partidas', 'ccfd04_cierre_mes',
                     'cscd04_ordencompra_autorizacion_cuerpo','cepd01_compromiso_numero', 'cstd01_entidades_bancarias', 'Usuario',
                     'cstd01_sucursales_bancarias', 'cstd02_cuentas_bancarias', 'cfpd10_reformulacion_partidas', 'cfpd10_reformulacion_texto',
                     'v_reformulacion_verificar','cstd03_movimientos_manuales',"cscd01_catalogo"

                    );
   var $helpers = array('Html','Ajax','Javascript','Sisap');
   var $layout="script_correciones";





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

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN

function SQLCA_noDEP(){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=1  and    ";
				 $sql_re .= "cod_entidad=11  and  ";
				 $sql_re .= "cod_tipo_inst=30  and ";
				 $sql_re .= "cod_inst=11 ";

				 return $sql_re;
		}//fin funcion SQLCA




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









function reactualizar_numeros_solicitud_cotizacion(){

$this->layout="ajax";

$c_ano=$this->ano_ejecucion();


	$result_anulados=$this->cepd01_compromiso_numero->execute("select * from v_solicitud_cotizacion_anulada_activa where ano_solicitud='".$c_ano."' and esta_en_anulada!=0 and esta_en_cotizacion_encabezado=1 order by cod_dep;");


    foreach($result_anulados as $ve){

           $cod_dep         = $ve[0]["cod_dep"];
           $ano_solicitud    = $ve[0]["ano_solicitud"];
           $numero_solicitud = $ve[0]["numero_solicitud"];

           $cond=" cod_dep='".$cod_dep."' and ano_solicitud=".$ano_solicitud."and numero_solicitud=".$numero_solicitud;
           $sw = $this->cscd02_solicitud_encabezado->execute("DELETE FROM cscd02_solicitud_encabezado  WHERE ".$cond);


    }//fin foreach



}//fin function


















function reactualiza_catalogo_denominacion(){


$this->layout="ajax";

   $datos = $this->cscd01_catalogo->findAll(" mayus_acentos(denominacion) LIKE mayus_acentos('%&LDQUO;%')  ");


		   foreach($datos as $ve){

			   	$denominacion     = str_replace("&LDQUO;","&ldquo;", $ve["cscd01_catalogo"]["denominacion"]);
			   	$codigo_prod_serv = $ve["cscd01_catalogo"]["codigo_prod_serv"];

			    $sql = "update cscd01_catalogo set denominacion='".$denominacion."' where codigo_prod_serv='".$codigo_prod_serv."'  ";
			    $actualizar = $this->cscd01_catalogo->execute($sql);

		   }//fin foreach





}//fin function

















































































}//fin class



?>