<?php

 class BalanceHojaCalculoController extends AppController{

/**/
 	var $uses = array('ccfd04_cierre_mes','v_balance_ejecucion_inst','v_balance_ejecucion','balance_mes22_inst','balance_mes2'
 	                  ,'v_analisis_presupuesto','v_cfpd05_denominaciones','cfpd10_reformulacion_partidas','cepd01_compromiso_partidas','cepd02_contratoservicio_cuerpo','cobd01_contratoobras_partidas','cstd03_cheque_cuerpo','cstd03_cheque_partidas','cepd01_compromiso_partidas','cscd04_ordencompra_partidas');
 	/**/
 	//var $uses = array('guarico','estado','municipio','parroquia','cv');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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

/*

function beforeFilter(){
	$this->checkSession();
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
}

*/

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

    function SQLCA_no_dep($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  ";

         return $sql_re;
    }//fin funcion SQLCA

    function exclusion_presupuesto($consolidado = false, $tipo_consolidado = 0, $year='')
{
    if($this->verifica_SS(5)==1)
    {
        
    $asql_proveeduria="";
    $sql_proveeduria="";

    $asql_busguarico="";
    $sql_busguarico="";

    $asql_construvialga="";
    $sql_construvialga="";

    $asql_construguarico="";
    $sql_construguarico="";

    $asql_fondoeficiencia="";
    $sql_fondoeficiencia="";

    $asql_dist_gas="";
    $sql_dist_gas="";

    $asql_aguastermales="";
    $sql_aguastermales="";

    $asql_alguarisa="";
    $sql_alguarisa="";

    $asql_corpoguarico="";
    $sql_corpoguarico="";

    $asql_turismo="";
    $sql_turismo="";

    $asql_agro_potencia="";
    $sql_agro_potencia="";

    $asql_fps="";
    $sql_fps="";

    $asql_policia="";
    $sql_policia="";

    $asql_sisoprogua="";
    $sql_sisoprogua="";

    $asql_construsalud="";
    $sql_construsalud="";

    if($year==2020){
      $asql_proveeduria= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,7) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,7,407,1,3,7,1)))";
      $sql_proveeduria= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,7) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,7,407,1,3,7,1)))";


      $asql_busguarico= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,8,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,8,51,402)  and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,8,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,8,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,8,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,8,51,411)";
      $sql_busguarico= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,8,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,8,51,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,8,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,8,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,8,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,8,51,411)";

      //SECTOR 7
      $asql_construvialga= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (7,1,1,52,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (7,1,1,52,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (7,1,1,52,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (7,1,1,52,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (7,1,1,52,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (7,1,1,52,411)";
      $sql_construvialga= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (7,1,1,52,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (7,1,1,52,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (7,1,1,52,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (7,1,1,52,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (7,1,1,52,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (7,1,1,52,411)";
      //END SECTOR 11

      //SECTOR 11
      $asql_construguarico= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,405) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (11,2,1,53,411)";
      $sql_construguarico= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,405) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (11,2,1,53,411)";        
      //END SECTOR 11

      $asql_fondoeficiencia= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (1,2,1,66,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (1,2,1,66,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (1,2,1,66,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (1,2,1,66,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (1,2,1,66,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (1,2,1,66,411)";
      $sql_fondoeficiencia= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (1,2,1,66,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (1,2,1,66,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (1,2,1,66,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (1,2,1,66,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (1,2,1,66,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (1,2,1,66,411)";

      //SECOTR 05
      $asql_dist_gas= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,9,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,9,51,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,9,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,9,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,9,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,9,51,411)";
      $sql_dist_gas= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,9,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,9,51,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,9,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,9,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,9,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,9,51,411)";

      $asql_aguastermales= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,402)  and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,411)";
      $sql_aguastermales= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,402)  and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,411)";

      $asql_alguarisa= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,12) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,12,407,1,3,7,1)))";
      $sql_alguarisa= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,12) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,12,407,1,3,7,1)))";     

      $asql_corpoguarico= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,10,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,10,51,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,10,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,10,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,10,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,10,51,411)";
      $sql_corpoguarico= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,10,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,10,51,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,10,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,10,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,10,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,10,51,411)";

      //END SECTOR 05

      
      $asql_turismo= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,11,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,11,51,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,11,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,11,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,11,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,11,51,411)";
      $sql_turismo= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,11,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,11,51,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,11,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,11,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,11,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,11,51,411)";


      $asql_agro_potencia= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,5,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,5,51,402) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,5,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,5,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,5,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,5,51,411)";
      $sql_agro_potencia= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,5,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,5,51,402) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,5,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,5,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,5,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,5,51,411)";

      //SECTOR 13
      $asql_fps= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,51,402)  and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,51,411) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_auxiliar) != (13,1,2,51,407,0) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,402)  and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,407)and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (13,1,2,52,411) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_auxiliar) != (13,1,2,52,407,0)";
      $sql_fps= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,51,402)  and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,51,411) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_auxiliar) != (13,1,2,51,407,0) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,402)  and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,407) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (13,1,2,52,411) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_auxiliar) != (13,1,2,52,407,0)";
      //END SECTOR 13

      //SECTOR 02
      $asql_policia= " and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (2,2,1,51,401) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (2,2,1,51,402)  and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (2,2,1,51,403) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (2,2,1,51,404) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (2,2,1,51,408) and (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (2,2,1,51,411)";
      $sql_policia= " and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (2,2,1,51,401) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (2,2,1,51,402)  and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (2,2,1,51,403) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (2,2,1,51,404) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (2,2,1,51,408) and (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (2,2,1,51,411)";
      //END SECTOR 02
      
      
    }

    if($year==2021){
       $asql_fps= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (13,1,2) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica) = (13,1,2,407,1,3)))";
       $sql_fps= " and ( (cod_sector,cod_programa,cod_sub_prog) != (13,1,2) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica) = (13,1,2,407,1,3)))";

       $asql_construguarico= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (11,2,4) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica) = (11,2,4,407,1,3)))";
       $sql_construguarico= " and ( (cod_sector,cod_programa,cod_sub_prog) != (11,2,4) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica) = (11,2,4,407,1,3)))";

       $asql_construvialga= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (7,1,2) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica) = (7,1,2,407,1,3)))";
       $sql_construvialga= " and ( (cod_sector,cod_programa,cod_sub_prog) != (7,1,2) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica) = (7,1,2,407,1,3)))";

       $asql_dist_gas= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,9) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,9,407,1,3,7,1)))";
       $sql_dist_gas= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,9) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,9,407,1,3,7,1)))";

       $asql_policia= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (2,2,4) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (2,2,4,407,1,3,7,1) or (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (2,2,4,407,1,3,7,2)))";
       $sql_policia= " and ( (cod_sector,cod_programa,cod_sub_prog) != (2,2,4) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (2,2,4,407,1,3,7,1) or (cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (2,2,4,407,1,3,7,2)))";
       $asql_policia.= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (2,2,1) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (2,2,1,407,1,3,7,1)))";
       $sql_policia.= " and ( (cod_sector,cod_programa,cod_sub_prog) != (2,2,1) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (2,2,1,407,1,3,7,1)))";

       $asql_aguastermales= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,6) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,6,407,1,3,7,1)))";
       $sql_aguastermales= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,6) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,6,407,1,3,7,1)))";

       $asql_alguarisa= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,10) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,10,407,1,3,7,1)))";
       $sql_alguarisa= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,10) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,10,407,1,3,7,1)))";

       $asql_sisoprogua= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,7) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,7,407,1,3,7,1)))";
       $sql_sisoprogua= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,7) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,7,407,1,3,7,1)))";

       $asql_busguarico= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,8) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,8,407,1,3,7,1)))";
       $sql_busguarico= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,8) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,8,407,1,3,7,1)))";

       $asql_fondoeficiencia= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (1,2,1,66) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (1,2,1,407,1,3,7,1)))";
       $sql_fondoeficiencia= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (1,2,1,66) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (1,2,1,407,1,3,7,1)))";

      
    }

    if($year==2022){

       $asql_fondoeficiencia= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (1,2,1,66) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (1,2,1,407,1,3,7,1)))";
       $sql_fondoeficiencia= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (1,2,1,66) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (1,2,1,407,1,3,7,1)))";


       $asql_iapebg= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (2,2,4,51) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (2,2,4,407,1,3,7,1)))";
       $sql_iapebg= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (2,2,4,51) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (2,2,4,407,1,3,7,1)))";


       $asql_agro_potencia= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (5,1,5,51) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,5,407,1,3,7,1)))";
       $sql_agro_potencia= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (5,1,5,51) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,5,407,1,3,7,1)))";


       $asql_aguastermales= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,6) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,6,407,1,3,7,1)))";
       $sql_aguastermales= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,6) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,6,407,1,3,7,1)))";


       $asql_sisoprogua= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,7) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,7,407,1,3,7,1)))";
       $sql_sisoprogua= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,7) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,7,407,1,3,7,1)))";


       $asql_busguarico= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,8) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,8,407,1,3,7,1)))";
       $sql_busguarico= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,8) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,8,407,1,3,7,1)))";


       $asql_dist_gas= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,9) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,9,407,1,3,7,1)))";
       $sql_dist_gas= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,9) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,9,407,1,3,7,1)))";


       $asql_alguarisa= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (5,1,10) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,10,407,1,3,7,1)))";
       $sql_alguarisa= " and ( (cod_sector,cod_programa,cod_sub_prog) != (5,1,10) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,10,407,1,3,7,1)))";


       $asql_construvialga= " and (
        (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (7,1,2) OR 
        (
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (7,1,2,407,1,3,7,1) OR 
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (7,1,2,407,1,3,7,2)
        )
      )";
       $sql_construvialga= " and (
        (cod_sector,cod_programa,cod_sub_prog) != (7,1,2) OR 
        (
          (cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (7,1,2,407,1,3,7,1) or
          (cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (7,1,2,407,1,3,7,2)
        )
      )";


       $asql_construguarico= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog) != (11,2,4) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec) = (11,2,4,407,1,3,7)))";
       $sql_construguarico= " and ( (cod_sector,cod_programa,cod_sub_prog) != (11,2,4) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec) = (11,2,4,407,1,3,7)))";


       $asql_fps= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (13,1,2,51) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (13,1,2,51,407,1,3,7,1)))";
       $sql_fps= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (13,1,2,51) OR ((cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (13,1,2,51,407,1,3,7,1)))";       

       $asql_fps.= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (13,1,2,52) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (13,1,2,52,401,1,12,0,0)))";
       $sql_fps.= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (13,1,2,52) OR ((cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (13,1,2,52,401,1,12,0,0)))";

       $asql_fps.= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (13,1,2,53) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec) = (13,1,2,53,407,1,3,7)))";
       $sql_fps.= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (13,1,2,53) OR ((cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec) = (13,1,2,53,407,1,3,7)))";   

       $asql_fps.= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (13,1,2,54) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (13,1,2,54,407,1,3,7,1)))";
       $sql_fps.= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (13,1,2,54) OR ((cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (13,1,2,54,407,1,3,7,1)))";
 
       $asql_construsalud.= " and (
        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (11,1,4,51) OR 
        (
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (11,1,4,51,407,1,3,7,1) or
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (11,1,4,51,407,1,3,7,2) or
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (11,1,4,51,407,1,3,7,3) or
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (11,1,4,51,407,1,3,7,4) or 
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (11,1,4,51,407,1,3,7,5)
        )
      )";
       $sql_construsalud.= " and (
        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (11,1,4,51) OR 
        (
          (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (11,1,4,51,407,1,3,7,1) or 
          (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (11,1,4,51,407,1,3,7,2) or 
          (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (11,1,4,51,407,1,3,7,3) or 
          (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (11,1,4,51,407,1,3,7,4) or 
          (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (11,1,4,51,407,1,3,7,5)
        )
      )";

    }

    if($year>=2023){
       $asql_construguarico= " and (a.cod_activ_obra) != (70) and (a.cod_activ_obra) != (71) and (a.cod_activ_obra) != (72) and (a.cod_activ_obra) != (73)";
       $sql_construguarico= " and (cod_activ_obra) != (70) and (cod_activ_obra) != (71) and (cod_activ_obra) != (72) and (cod_activ_obra) != (73)";
       $asql_construsalud.= " and (
        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (11,1,4,51) OR 
        (
          (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (11,1,4,51,407,1,3,7,1)
        )
      )";
       $sql_construsalud.= " and (
        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (11,1,4,51) OR 
        (
          (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (11,1,4,51,407,1,3,7,1)
        )
      )";
      
       $asql_fps= " and (a.cod_activ_obra!=70 AND a.cod_activ_obra!=71 AND a.cod_activ_obra!=72 AND a.cod_activ_obra!=73 AND a.cod_activ_obra!=74 AND a.cod_activ_obra!=75 AND a.cod_activ_obra!=76 AND a.cod_activ_obra!=77 AND a.cod_activ_obra!=78 AND a.cod_activ_obra!=79)";
       $sql_fps= " and (cod_activ_obra!=70 AND cod_activ_obra!=71 AND cod_activ_obra!=72 AND cod_activ_obra!=73 AND cod_activ_obra!=74 AND cod_activ_obra!=75 AND cod_activ_obra!=76 AND cod_activ_obra!=77 AND cod_activ_obra!=78 AND cod_activ_obra!=79)";  
      

      //CORPOTURISMO
      $asql_construguarico= " and ( (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra) != (5,1,11,51) OR ((a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar) = (5,1,11,407,1,3,7,1)))";
       $sql_construguarico= " and ( (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra) != (5,1,11,51) OR ((cod_sector,cod_programa,cod_sub_prog,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar) = (5,1,11,407,1,3,7,1)))";
    }


    if($consolidado){
        $sql=" and (
            (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
             a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) != (5,1,6,51,401,1,18,1,0) and
            (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
             a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) != (5,1,6,51,402,7,4,0,0) and 
            (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
             a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) != (5,1,6,51,402,10,11,0,0) and
            (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
             a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) != (5,1,6,51,403,18,1,0,0) ".$asql_proveeduria.$asql_busguarico.$asql_construvialga.$asql_construguarico.$asql_fondoeficiencia.$asql_dist_gas.$asql_aguastermales.$asql_alguarisa.$asql_corpoguarico.$asql_turismo.$asql_agro_potencia.$asql_fps.$asql_policia.$asql_sisoprogua.$asql_construsalud."
        )";
        
        if($tipo_consolidado!=3)
        {
           /* $sql .= " and (
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (11,2,1,51,401,1,18,1) and
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (11,2,1,51,402,7,4,0) and 
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (11,2,1,51,402,10,11,0) and 
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.cod_auxiliar,a.deno_auxiliar) != (11,2,1,51,403,18,1,0,0,'IMPUESTO AL VALOR AGREGADO')
                    )";*/
                     $sql .= " and (
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (11,2,1,51,401,1,18,1) and
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (11,2,1,51,402,7,4,0) and 
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (11,2,1,51,402,10,11,0) and 
                        (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.cod_auxiliar,a.asignacion_anual) != (11,2,1,51,403,18,1,0,0,29934733.16)
                    )";
        }
        else
        {
            /*$sql .= " and (
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (1036,11,2,1,51,401,1,18,1) and
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (1036,11,2,1,51,402,7,4,0) and 
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (1036,11,2,1,51,402,10,11,0) and 
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.cod_auxiliar,a.deno_auxiliar) != (1036,11,2,1,51,403,18,1,0,0,'IMPUESTO AL VALOR AGREGADO')
                    )"; */
                    $sql .= " and (
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (1036,11,2,1,51,401,1,18,1) and
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (1036,11,2,1,51,402,7,4,0) and 
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec) != (1036,11,2,1,51,402,10,11,0) and 
                        (a.cod_dep, a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida,
                        a.cod_generica, a.cod_especifica, a.cod_sub_espec,a.cod_auxiliar,a.asignacion_anual) != (1036,11,2,1,51,403,18,1,0,0,29934733.16)
                    )";  
        }
    }else{
        $sql=" and (
            (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
             cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar) != (5,1,6,51,401,1,18,1,0) and
            (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
             cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar) != (5,1,6,51,402,7,4,0,0) and 
            (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
             cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar) != (5,1,6,51,402,10,11,0,0) and
            (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
             cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar) != (5,1,6,51,403,18,1,0,0) ".$sql_proveeduria.$sql_busguarico.$sql_construvialga.$sql_construguarico.$sql_fondoeficiencia.$sql_dist_gas.$sql_aguastermales.$sql_alguarisa.$sql_corpoguarico.$sql_turismo.$sql_agro_potencia.$sql_fps.$sql_policia.$sql_sisoprogua.$sql_construsalud."
        )";
        
        if($tipo_consolidado!=3)
        {
            /*$sql .= " and (
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (11,2,1,51,401,1,18,1) and
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (11,2,1,51,402,7,4,0) and 
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (11,2,1,51,402,10,11,0) and 
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deno_auxiliar) != (11,2,1,51,403,18,1,0,0,'IMPUESTO AL VALOR AGREGADO')
                    )";*/
                    $sql .= " and (
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (11,2,1,51,401,1,18,1) and
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (11,2,1,51,402,7,4,0) and 
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (11,2,1,51,402,10,11,0) and 
                        (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, asignacion_anual) != (11,2,1,51,403,18,1,0,0,29934733.16)
                    )";
        }
        else
        {
            /*$sql .= " and (
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (1036,11,2,1,51,401,1,18,1) and
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (1036,11,2,1,51,402,7,4,0) and 
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (1036,11,2,1,51,402,10,11,0) and 
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,deno_auxiliar) != (1036,11,2,1,51,403,18,1,0,0,'IMPUESTO AL VALOR AGREGADO')
                    )";   */
                    $sql .= " and (
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (1036,11,2,1,51,401,1,18,1) and
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (1036,11,2,1,51,402,7,4,0) and 
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec) != (1036,11,2,1,51,402,10,11,0) and 
                        (cod_dep, cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida,
                        cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,asignacion_anual) != (1036,11,2,1,51,403,18,1,0,0,29934733.16)
                    )"; 
        }
    }
    //$sql = "";

    return $sql;

  } else {
    return "";
  }
}

function index () {
    $this->layout="ajax";
    $this->set('ano',$this->ano_ejecucion());

}//fin index

function balance () {
	//$this->layout="ods";
	$this->layout="ajax";
	//$this->layout="excel";
    //pr($this->data);
    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];

	     }else{
	     	$Ano=$this->ano_ejecucion();

	     }

	    $this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	     $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	     $modelo=$this->data['cfpp05']['consolidacion']==1?"v_balance_ejecucion_inst":"v_balance_ejecucion";
              $this->set("modelo",$modelo);
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$modelo="v_balance_ejecucion";
	     	$this->set("modelo",$modelo);
    	}

        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!="")
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        else
        	$cod_sector=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!="")
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        else
        	$cod_programa=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!="")
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        else
        	$cod_sub_prog=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!="")
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        else
        	$cod_proyecto=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!="")
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        else
        	$cod_activ_obra=" 1=1 ";
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!="")
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        else
        	$cod_partida=" 1=1 ";
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!="")
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        else
        	$cod_generica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!="")
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        else
        	$cod_especifica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!="")
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        else
        	$cod_sub_espec=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!="")
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        else
        	$cod_auxiliar=" 1=1 ";

  	    $modo= (int) $this->data["reporte"]["modo"];
  	   // echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            default: $condicion=" 1=1";
  	    }//fin switch
        //echo $condicion;

         if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
            $exclusion_presupuesto = $this->exclusion_presupuesto(true, $this->data['cfpp05']['consolidacion'],$Ano);
        }
        else
        {
            $exclusion_presupuesto = $this->exclusion_presupuesto(false,$this->data['cfpp05']['consolidacion'],$Ano);
        }

        $condicion.=$exclusion_presupuesto;
        // var_dump($con." and ano=".$Ano." and ".$condicion);exit();
	    $vector = $this->$modelo->findAll($con." and ano=".$Ano." and ".$condicion,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
        $total_spsppa = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion."  GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra  ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC");

        $distinto_sector = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion." GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra");
        $this->set('distintos_sectores',$total_spsppa);
        $this->set('cfpd05',$vector);
        //$this->render('balance2');
        $_SESSION["nombre_ods"] ='balance_ejecucion_'.date('His').".ods";
        $this->render('balance_excelv2');
        //$this->render('prueba_excel');
}



function balance_horizontal () {
	//$this->layout="ods";
	$this->layout="ajax";
	//$this->layout="excel";
    //pr($this->data);
    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];

	     }else{
	     	$Ano=$this->ano_ejecucion();

	     }

	    $this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	     $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	     $modelo=$this->data['cfpp05']['consolidacion']==1?"v_balance_ejecucion_inst":"v_balance_ejecucion";
              $this->set("modelo",$modelo);
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$modelo="v_balance_ejecucion";
	     	$this->set("modelo",$modelo);
    	}

        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!="")
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        else
        	$cod_sector=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!="")
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        else
        	$cod_programa=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!="")
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        else
        	$cod_sub_prog=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!="")
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        else
        	$cod_proyecto=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!="")
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        else
        	$cod_activ_obra=" 1=1 ";
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!="")
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        else
        	$cod_partida=" 1=1 ";
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!="")
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        else
        	$cod_generica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!="")
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        else
        	$cod_especifica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!="")
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        else
        	$cod_sub_espec=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!="")
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        else
        	$cod_auxiliar=" 1=1 ";

  	    $modo= (int) $this->data["reporte"]["modo"];
  	   // echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            default: $condicion=" 1=1";
  	    }//fin switch
        //echo $condicion;

         if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
            $exclusion_presupuesto = $this->exclusion_presupuesto(true, $this->data['cfpp05']['consolidacion'],$Ano);
        }
        else
        {
            $exclusion_presupuesto = $this->exclusion_presupuesto(false,$this->data['cfpp05']['consolidacion'],$Ano);
        }

        $condicion.=$exclusion_presupuesto;

	    $vector = $this->$modelo->findAll($con." and ano=".$Ano." and ".$condicion,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
        $total_spsppa = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion."  GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra  ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC");

        $distinto_sector = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion." GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra");
        $this->set('distintos_sectores',$total_spsppa);
        $this->set('cfpd05',$vector);
        //$this->render('balance2');
        $_SESSION["nombre_ods"] ='balance_ejecucion_'.date('His').".ods";
        $this->render('balance_excelv2horizontal');
        //$this->render('prueba_excel');
}

function balance_mes () {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];

	     }else{
	     	$Ano=$this->ano_ejecucion();

	     }
    	$this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	    $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	    $modelo=$this->data['cfpp05']['consolidacion']==1?"balance_mes22_inst":"balance_mes2";
    	    $tabla=$this->data['cfpp05']['consolidacion']==1?"v_balance_ejecucion22_inst":"v_balance_ejecucion2_2";
            $this->set("modelo",$modelo);
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$modelo="balance_mes2";
    		$tabla="v_balance_ejecucion2_2";
            $this->set("modelo",$modelo);
    	}
        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!="")
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        else
        	$cod_sector=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!="")
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        else
        	$cod_programa=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!="")
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        else
        	$cod_sub_prog=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!="")
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        else
        	$cod_proyecto=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!="")
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        else
        	$cod_activ_obra=" 1=1 ";
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!="")
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        else
        	$cod_partida=" 1=1 ";
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!="")
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        else
        	$cod_generica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!="")
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        else
        	$cod_especifica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!="")
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        else
        	$cod_sub_espec=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!="")
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        else
        	$cod_auxiliar=" 1=1 ";

  	    $modo= (int) $this->data["reporte"]["modo"];
  	    //echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            default: $condicion=" 1=1";
  	    }//fin switch
         if(isset($this->data["reporte"]["mes"]) && !empty($this->data["reporte"]["mes"])){
         	$this->set("mes",$this->data["reporte"]["mes"]);
         	$this->Session->write("mes_solicitado",$this->data["reporte"]["mes"]);
         }else{
         	$this->set("mes",date("m"));
         	$this->Session->write("mes_solicitado",date("m"));
         }

          if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
            $exclusion_presupuesto = $this->exclusion_presupuesto(true, $this->data['cfpp05']['consolidacion'],$Ano);
        }
        else
        {
            $exclusion_presupuesto = $this->exclusion_presupuesto(false,$this->data['cfpp05']['consolidacion'],$Ano);
        }

        $condicion.=$exclusion_presupuesto;

	    $vector = $this->$modelo->findAll($con." and ano=".$Ano." and ".$condicion,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
        $total_spsppa = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$tabla." WHERE ".$con." and ano=".$Ano." and ".$condicion."  GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra  ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC");
        $distinto_sector = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$tabla." WHERE ".$con." and ano=".$Ano." and ".$condicion." GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra");
        $this->set('distintos_sectores',$total_spsppa);
        $this->set('cfpd05',$vector);
        $_SESSION["nombre_ods"] ='balance_ejecucion_mes_'.date('His').".ods";
        $this->render('balance_mes_excel');

}//fin balance_mes



function analisis_presupuesto_actividades_form() {
	$this->layout="ajax";
    //$this->limpia_menu();
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->write('ano_reporte',$ano);
	if($this->verifica_SS(5)==1){
    	    $cond=$this->SQLCA_report(1);
    	}else{
    		$cond=$this->SQLCA_report();
    	}
    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $cond." and ano=".$ano." ORDER BY cod_sector ASC");
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}
	$lista = array_combine($v, $d);
	$rsp=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE ". $cond." and ano=".$ano." ORDER BY cod_partida ASC");
    foreach($rsp as $lp){
		$vp[]=$lp[0]["cod_partida"];
		$dp[]=$lp[0]["deno_partida"];
	}
	$partida = array_combine($vp, $dp);
	$this->concatena($lista, 'sector');
	$this->concatena($partida, 'partida');
	//echo count($lista);
	/*if(count($lista)==1){
		$this->set('value_selected_1',$rs[0][0]['cod_sector']);
		echo '<script>$(\'deno_seleccion_1\').innerHTML="'.$rs[0][0]['deno_sector'].'";</script>';
		$rs2=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_programa,deno_programa FROM v_cfpd05_denominaciones WHERE ". $cond." and ano=".$ano." and cod_sector=".$rs[0][0]['cod_sector']." ORDER BY cod_programa ASC");
	    foreach($rs2 as $l2){
			$v2[]=$l2[0]["cod_programa"];
			$d2[]=$l2[0]["deno_programa"];
		}
		$lista2 = array_combine($v2, $d2);

		if(count($lista)==1){
			$this->set('value_selected_2',$rs2[0][0]['cod_programa']);
		    echo '<script>$(\'deno_seleccion_2\').innerHTML="'.$rs2[0][0]['deno_programa'].'";</script>';
		    $this->concatena($lista2, 'programa');
		}else{$this->set('value_selected_2',null);
		      $this->set('programa','');
		     }

	}else{
		$this->set('value_selected_1',null);
	    $this->set('value_selected_2',null);
		$this->set('programa','');
	}**/

}//fin

function analisis_presupuesto_actividades() {
	$this->layout="pdf";
	//v_deno_analisis




    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];
	     }else{
	     	$Ano=$this->ano_ejecucion();
	     }
    	$this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	     $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	     $con_a=$this->SQLCA_consolidado_opcion($this->data['cfpp05']['consolidacion'], "a");
    	     if($this->data['cfpp05']['consolidacion']==1){
    	     	$xcod_dep_ref = " and 1=1";
    	     }else{
    	     	$xcod_dep_ref = " and a.codi_dep=".$_SESSION['cod_dep_reporte_consolidado'];
    	     }
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$con_a=$this->SQLCA_consolidado_opcion(null, "a");
    		$xcod_dep_ref = " and a.codi_dep=".$_SESSION['cod_dep_reporte_consolidado'];

    	}
        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!=""){
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        	$cod_sector_a=" a.cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        }else{
        	$cod_sector=" 1=1 and ";
        	$cod_sector_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!=""){
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        	$cod_programa_a=" a.cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        }else{
        	$cod_programa=" 1=1 and ";
        	$cod_programa_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!=""){
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        	$cod_sub_prog_a=" a.cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        }else{
        	$cod_sub_prog=" 1=1 and ";
        	$cod_sub_prog_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!=""){
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        	$cod_proyecto_a=" a.cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        }else{
        	$cod_proyecto=" 1=1 and ";
        	$cod_proyecto_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!=""){
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        	$cod_activ_obra_a=" a.cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        }else{
        	$cod_activ_obra=" 1=1 ";
        	$cod_activ_obra_a=" 1=1 ";
        }
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!=""){
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        	$cod_partida_a=" a.cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        }else{
        	$cod_partida=" 1=1 ";
        	$cod_partida_a=" 1=1 ";
        }
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!=""){
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        	$cod_generica_a=" a.cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        }else{
        	$cod_generica=" 1=1 and ";
        	$cod_generica_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!=""){
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        	$cod_especifica_a=" a.cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        }else{
        	$cod_especifica=" 1=1 and ";
        	$cod_especifica_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!=""){
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        	$cod_sub_espec_a=" a.cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        }else{
        	$cod_sub_espec=" 1=1 and ";
        	$cod_sub_espec_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!=""){
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        	$cod_auxiliar_a=" a.cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        }else{
        	$cod_auxiliar=" 1=1 ";
        	$cod_auxiliar_a=" 1=1 ";
        }
  	    $modo= (int) $this->data["reporte"]["modo"];
  	    //echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
                  $condicion_a=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	      $condicion_a=" ".$cod_sector_a.$cod_programa_a.$cod_sub_prog_a.$cod_proyecto_a.$cod_activ_obra_a;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
  	    	    $condicion_a=" ".$cod_sector_a.$cod_programa_a.$cod_sub_prog_a.$cod_proyecto_a.$cod_activ_obra_a." and ".$cod_partida_a." and ".$cod_generica_a.$cod_especifica_a.$cod_sub_espec_a.$cod_auxiliar_a;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
                 $condicion_a=" ".$cod_partida_a;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
                 $condicion_a=" ".$cod_partida_a." and ".$cod_generica_a.$cod_especifica_a.$cod_sub_espec_a.$cod_auxiliar_a;
            break;
            default: $condicion=" 1=1";
                     $condicion_a=" 1=1";
                     break;
  	    }//fin switch
        //echo "<script>alert('".$con_a.$condicion_a."');</script>";
        $sin_afectadas=" and (aumento!=0 or disminucion!=0 or compromiso_anual!=0 or causado_anual!=0 or pagado_anual!=0)";
	    $vector = $this->v_analisis_presupuesto->findAll($con." and ano=".$Ano." and ".$condicion.$sin_afectadas,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	    //$cantidad_registros = $this->v_analisis_presupuesto->findCount($con." and ano=".$Ano." and ".$condicion.$sin_afectadas,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
        $this->set('cantidad_registros',159);
        //print_r($vector);
        //echo "<script>alert('".$con." and ano=".$Ano." and ".$condicion.$sin_afectadas."');</script>";
        $this->set('DATOS',$vector);
        //pr($vector);

        $sql_reformulacion = "SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_reformulacion,
  a.numero_oficio,
  a.codi_dep,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto_disminucion,
  a.monto_aumento,
  b.fecha_decreto,b.numero_decreto
FROM
  cfpd10_reformulacion_partidas a,cfpd10_reformulacion_texto b
WHERE
  upper(a.numero_oficio)=upper(b.numero_oficio) and a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." ".$xcod_dep_ref." and a.ano=".$Ano." and ".$condicion_a." and a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.ano_reformulacion=b.ano_reformulacion ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_decreto ASC";
        $reformulacion=$this->cfpd10_reformulacion_partidas->execute($sql_reformulacion);
    //echo $sql_reformulacion;
    //pr($reformulacion);
   $this->set("reformulacion",$reformulacion);



$RCompromisos=$this->cepd01_compromiso_partidas->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_documento,
  a.numero_documento,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_documento,
  b.beneficiario,
  b.ano_orden_pago,
  b.numero_orden_pago

FROM
 cepd01_compromiso_partidas a,cepd01_compromiso_cuerpo b
WHERE
  b.condicion_actividad=1 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_documento=b.ano_documento AND
  a.numero_documento=b.numero_documento and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_documento ASC");
  $this->set("RCompromisos",$RCompromisos);

$OCcompromiso=$this->cscd04_ordencompra_partidas->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.aumento,
a.disminucion,
a.anticipo,
a.amortizacion,
a.cancelado,
a.numero_asiento_compromiso,
b.fecha_orden_compra,
b.ano_orden_compra,
b.numero_orden_compra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
b.ano_cotizacion,
b.numero_cotizacion,
upper(b.rif),
(SELECT x.denominacion from cpcd02 x WHERE upper(x.rif)=upper(b.rif) limit 1) as beneficiario

FROM cscd04_ordencompra_partidas a,cscd04_ordencompra_encabezado b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra  and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_orden_compra ASC");

$this->set("OCompromisos",$OCcompromiso);

//$orden_pago=$this->cepd03_ordenpago_partidas->findAll($con." and ano=".$Ano." and ".$condicion.$sin_afectadas,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

//ORDENDE DE PAGOS
$orden_pago=$this->cepd01_compromiso_partidas->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_orden_pago,
  b.beneficiario,
  b.numero_documento_origen,
  b.numero_documento_adjunto,
  b.ano_movimiento,
  b.cod_entidad_bancaria,
  b.cod_sucursal,
  b.cuenta_bancaria,
  b.numero_cheque,
  b.fecha_cheque,b.cod_tipo_documento

FROM
 cepd03_ordenpago_partidas a,cepd03_ordenpago_cuerpo b
WHERE
  b.condicion_actividad=1 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_orden_pago=b.ano_orden_pago AND
  a.numero_orden_pago=b.numero_orden_pago and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_orden_pago ASC");

  $this->set("orden_pago",$orden_pago);

//CHEQUES
$cheques=$this->cstd03_cheque_partidas->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  a.cuenta_bancaria,
  a.numero_cheque,
  a.clase_orden,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_cheque,
  b.beneficiario,b.clase_beneficiario


FROM
 cstd03_cheque_partidas a,cstd03_cheque_cuerpo b
WHERE
  b.condicion_actividad=1 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_movimiento=b.ano_movimiento AND
  a.cod_entidad_bancaria=b.cod_entidad_bancaria AND
  a.cod_sucursal=b.cod_sucursal AND
  a.cuenta_bancaria=b.cuenta_bancaria AND
  a.numero_cheque=b.numero_cheque AND
  ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_cheque ASC");

  $this->set("cheques",$cheques);

//RETENCION IVA
$retencion_iva=$this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.clase_orden,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_proceso_registro,
  b.ano_movimiento,
  b.cod_entidad_bancaria,
  b.cod_sucursal,
  b.cuenta_bancaria,
  b.numero_cheque,
  b.status

FROM
 cstd07_retenciones_partidas_iva a,cstd07_retenciones_cuerpo_iva b
WHERE
  b.status=2 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_orden_pago=b.ano_orden_pago AND
  a.numero_orden_pago=b.numero_orden_pago and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_proceso_registro ASC");

  $this->set("retencion_iva",$retencion_iva);

//RETENCION ISLR
$retencion_islr=$this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.clase_orden,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_proceso_registro,
  b.ano_movimiento,
  b.cod_entidad_bancaria,
  b.cod_sucursal,
  b.cuenta_bancaria,
  b.numero_cheque,
  b.status

FROM
 cstd07_retenciones_partidas_islr a,cstd07_retenciones_cuerpo_islr b
WHERE
  b.status=2 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_orden_pago=b.ano_orden_pago AND
  a.numero_orden_pago=b.numero_orden_pago and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_proceso_registro ASC");

  $this->set("retencion_islr",$retencion_islr);

//RETENCION MUNICIPAL
$retencion_municipal=$this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.clase_orden,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_proceso_registro,
  b.ano_movimiento,
  b.cod_entidad_bancaria,
  b.cod_sucursal,
  b.cuenta_bancaria,
  b.numero_cheque,
  b.status

FROM
 cstd07_retenciones_partidas_municipal a,cstd07_retenciones_cuerpo_municipal b
WHERE
  b.status=2 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_orden_pago=b.ano_orden_pago AND
  a.numero_orden_pago=b.numero_orden_pago and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_proceso_registro ASC");

  $this->set("retencion_municipal",$retencion_municipal);

//RETENCION TIMBRE
$retencion_timbre=$this->cstd03_cheque_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.clase_orden,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  b.fecha_proceso_registro,
  b.ano_movimiento,
  b.cod_entidad_bancaria,
  b.cod_sucursal,
  b.cuenta_bancaria,
  b.numero_cheque,
  b.status

FROM
 cstd07_retenciones_partidas_timbre a,cstd07_retenciones_cuerpo_timbre b
WHERE
  b.status=2 AND
  a.cod_presi=b.cod_presi AND
  a.cod_entidad=b.cod_entidad AND
  a.cod_tipo_inst=b.cod_tipo_inst AND
  a.cod_inst=b.cod_inst AND
  a.cod_dep=b.cod_dep AND
  a.ano_orden_pago=b.ano_orden_pago AND
  a.numero_orden_pago=b.numero_orden_pago and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_proceso_registro ASC");

  $this->set("retencion_timbre",$retencion_timbre);











// CONTRATO OBRAS

$OBcompromiso=$this->cobd01_contratoobras_partidas->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_contrato_obra,
a.numero_contrato_obra,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.aumento,
a.disminucion,
a.anticipo,
a.amortizacion,
a.numero_control_compromiso,
b.fecha_contrato_obra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
upper(b.rif),
(SELECT x.denominacion from cpcd02 x WHERE upper(x.rif)=upper(b.rif) limit 1) as beneficiario

FROM cobd01_contratoobras_partidas a,cobd01_contratoobras_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra  and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_contrato_obra ASC");

$this->set("OBcompromisos",$OBcompromiso);
//pr($OBcompromiso);
// CONTRATO OBRAS MODIFICACIONES

$OBMcompromiso=$this->cobd01_contratoobras_partidas->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_contrato_obra,
a.numero_contrato_obra,
a.numero_modificacion,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.numero_control_compromiso,
b.fecha_modificacion,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,b.tipo_modificacion

FROM cobd01_contratoobras_modificacion_partidas a,cobd01_contratoobras_modificacion_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and a.numero_modificacion = b.numero_modificacion  and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_modificacion ASC");

$this->set("OBMcompromisos",$OBMcompromiso);


// CONTRATO SERVICIO

$CScompromiso=$this->cepd02_contratoservicio_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_contrato_servicio,
a.numero_contrato_servicio,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.aumento,
a.disminucion,
a.anticipo,
a.amortizacion,
a.numero_control_compromiso,
b.fecha_contrato_servicio,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
upper(b.rif),
(SELECT x.denominacion from cpcd02 x WHERE upper(x.rif)=upper(b.rif) limit 1) as beneficiario

FROM cepd02_contratoservicio_partidas a,cepd02_contratoservicio_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio  and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_contrato_servicio ASC");

$this->set("CScompromisos",$CScompromiso);


// CONTRATO SERVICIO MODIFICACIONES

$CSMcompromiso=$this->cepd02_contratoservicio_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_contrato_servicio,
a.numero_contrato_servicio,
a.numero_modificacion,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.numero_control_compromiso,
b.fecha_modificacion,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,b.tipo_modificacion

FROM cepd02_contratoservicio_modificacion_partidas a,cepd02_contratoservicio_modificacion_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio  and a.numero_modificacion = b.numero_modificacion and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_modificacion ASC");

$this->set("CSMcompromisos",$CSMcompromiso);



// ORDEN COMPRA MODIFICACIONES

$OCMcompromiso=$this->cobd01_contratoobras_partidas->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.numero_modificacion,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.numero_control_compromiso,
b.fecha_modificacion,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,b.tipo_modificacion

FROM cscd04_ordencompra_modificacion_partidas a,cscd04_ordencompra_modificacion_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra and a.numero_modificacion = b.numero_modificacion  and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_modificacion ASC");

$this->set("OCMcompromisos",$OCMcompromiso);

//RENDICIONES

$RENDICIONES=$this->cobd01_contratoobras_partidas->execute("SELECT
a.*,
b.fecha_rendicion,
b.funcionario_responsable
FROM cfpd30_rendiciones_partidas a,cfpd30_rendiciones_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_rendicion = b.ano_rendicion and
  a.numero_rendicion = b.numero_rendicion and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_rendicion ASC");

$this->set("RENDICIONES",$RENDICIONES);

//NOTA DE DEBITO

$NOTADEBITO=$this->cobd01_contratoobras_partidas->execute("SELECT a.*,b.ano_orden_pago,b.numero_orden_pago,b.fecha_nota_debito,b.beneficiario FROM cstd09_notadebito_especial_partidas a,cstd09_notadebito_especial_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_movimiento=b.ano_movimiento and
a.cod_entidad_bancaria=b.cod_entidad_bancaria and
a.cod_sucursal=b.cod_sucursal and
a.cuenta_bancaria=b.cuenta_bancaria and
a.tipo_documento=b.tipo_documento and
a.numero_documento=b.numero_documento and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_nota_debito ASC");

$this->set("NOTADEBITO",$NOTADEBITO);

//REINTEGRO

$REINTEGRO=$this->cobd01_contratoobras_partidas->execute("SELECT
a.*,
b.fecha_reintegro,
b.funcionario_responsable
FROM cfpd30_reintegro_partidas a,cfpd30_reintegro_cuerpo b
 where
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_reintegro = b.ano_reintegro and
  a.numero_reintegro = b.numero_reintegro and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_reintegro ASC");

$this->set("REINTEGRO",$REINTEGRO);


//NOTA DE DEBITO NUEVO PROGRAMA

$NOTADEBITO_CA=$this->cobd01_contratoobras_partidas->execute("SELECT a.*,b.fecha_debito,b.beneficiario FROM cstd09_notadebito_partidas a,cstd09_notadebito_cuerpo b
WHERE
b.condicion_actividad=1 and
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_movimiento=b.ano_movimiento and
a.cod_entidad_bancaria=b.cod_entidad_bancaria and
a.cod_sucursal=b.cod_sucursal and
a.cuenta_bancaria=b.cuenta_bancaria and
a.numero_debito=b.numero_debito and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_debito ASC");

$this->set("NOTADEBITO_CA",$NOTADEBITO_CA);

// NOTA DE DEBITO del IVA y ISLR

$DEBITO=$this->cobd01_contratoobras_partidas->execute("SELECT a.*,b.fecha_debito,b.beneficiario FROM cstd30_debito_partidas a,cstd30_debito_cuerpo b
WHERE
b.condicion_actividad=1 and
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_movimiento=b.ano_movimiento and
a.cod_entidad_bancaria=b.cod_entidad_bancaria and
a.cod_sucursal=b.cod_sucursal and
a.cuenta_bancaria=b.cuenta_bancaria and
a.numero_debito=b.numero_debito and ".$con_a." and a.ano=".$Ano." and ".$condicion_a." ORDER BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_proyecto,a.cod_activ_obra,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,b.fecha_debito ASC");

$this->set("DEBITO",$DEBITO);




}//fin analisis de presupuesto







/*
function municipios ($codigo_estado=10,$codigo_municipio=null,$ki=null) {
	set_time_limit(0);
    ini_set ('memory_limit', "1536M");
	$this->layout="ajax";


	    if(isset($codigo_municipio) && $codigo_municipio!=null){
        	$mun = ' and codigo_municipio='.$codigo_municipio;
	    }else{
	    	$mun = '';
	    }
        $A1 = $this->estado->findAll('codigo_estado='.$codigo_estado,null,'codigo_estado ASC');
        $A2 = $this->municipio->findAll('codigo_estado='.$codigo_estado.''.$mun,null,'codigo_estado,codigo_municipio ASC');

         $this->set('DATAESTADOS',$A1);
         $this->set('DATAMUNICIPIOS',$A2);
         $this->set('codigo_estado',$codigo_estado);

       if(isset($ki) && $ki=='no'){
		  $this->set('listar',false);
		  $A3 = $this->parroquia->findAll('codigo_estado='.$codigo_estado.''.$mun,null,'codigo_estado,codigo_municipio,codigo_parroquia ASC');
          $A4 = $this->cv->findAll('codigo_estado='.$codigo_estado.''.$mun,null,'codigo_estado,codigo_municipio,codigo_parroquia,codif_nueva_cv ASC');
          $A5 = $this->guarico->findAll('codigo_estado='.$codigo_estado.''.$mun,null,'codigo_estado,codigo_municipio,codigo_parroquia,codif_nueva_cv,cedula ASC');
       	  $this->set('DATAPARROQUIAS',$A3);
          $this->set('DATACV',$A4);
          $this->set('DATA',$A5);
       }else{

          $this->set('listar',true);

       }
}
*/












}//fin class
?>
