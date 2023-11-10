<?php
class Cscp01CatalogoMantenimientoPartidaController extends AppController{

   var $name = 'cscp01_catalogo_mantenimiento_partida';
   var $uses = array('cscd01_catalogo', 'cscd01_unidad_medida', 'cfpd01_partida', 'cfpd01_generica', 'ccfd04_cierre_mes',
                     'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar', 'v_cscd01_catalogo_snc', 'v_cfpd01_especifica_concatenado',
                     'v_cfpd01_sub_espec_concatenado',
                     'cscd02_solicitud_cuerpo', 'cscd01_snc_grupo', 'cscd01_snc_tipo', "v_cscd01_catalogo_con_snc_denominacion",
                     'cugd05_restriccion_clave', "cscd02_solicitud_cuerpo", "cscd02_solicitud_cuerpo_anulado", "cscd03_cotizacion_cuerpo",
                     "cscd03_cotizacion_cuerpo_anulado","cscd05_ordencompra_nota_entrega_cuerpo", "cfpd01_ano_sub_espec", "cfpd01_ano_especifica","cugd05_restriccion_clave");
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
					if($this->ano_ejecucion()!=""){
						return;
					}else{
						echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
						exit();
					}
}


function verifica_SS($i){
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


function SQLCA($ano=null){
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


function index($var1=null, $var2=null, $var3=null){

$this->verifica_entrada('98');

    $this->layout= "ajax";
	$this->Session->delete('pista');
	$this->Session->delete('pista2');
	$this->Session->write('pista_opcion', 1);

	if(isset($_SESSION["selecion_snc"])){

		$var_aux = $_SESSION["selecion_snc"];
		$sql     = " mayus_acentos(cod_snc)= mayus_acentos('$var_aux') ";

					$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"  denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            $this->set("deno",$var_aux);

	}//fin if

$this->set("opcion",1);

}//fin function











function eliminar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $cont                     =       0;
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";


			$cont += $this->cscd02_solicitud_cuerpo->findCount("codigo_prod_serv=".$var1);
			$cont += $this->cscd02_solicitud_cuerpo_anulado->findCount("codigo_prod_serv=".$var1);
			$cont += $this->cscd03_cotizacion_cuerpo->findCount("codigo_prod_serv=".$var1);
			$cont += $this->cscd03_cotizacion_cuerpo_anulado->findCount("codigo_prod_serv=".$var1);
			$cont += $this->cscd05_ordencompra_nota_entrega_cuerpo->findCount("codigo_prod_serv=".$var1);

         if($cont==0){
                 $this->cscd02_solicitud_cuerpo->execute("DELETE FROM cscd01_catalogo  WHERE codigo_prod_serv='".$var1."';  ");

        	    echo"<script> new Effect.DropOut('guarda_".$var1."'); </script>";

        	    $this->set('errorMessage', 'LOS DATOS FUERON ELIMINADOS');

         }else{

         	    $this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS, YA ESTA SIENDO USADO');

         }//fin else



$this->render("funcion");
}//fin function










function salir_vacio() {
	$this->layout="ajax";
		$this->Session->delete('pase_valido');
}











function buscar_cod_snc_1($var1=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista22');

}//fin function




function selecion_snc($var1=null){

   $this->layout="ajax";

   $datos_filas= $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv=".$var1);

    					 $cod_grupo   =                  substr($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"],0,1);
    					 $cod_partida = separa_partida_de_grupo($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"]);
						 $cod_generica = $datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_generica"];
						 $cod_especifica = $datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_especifica"];
						 $cod_sub_espec = $datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_sub_espec"];

						 $partida =       substr($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"],0,1).".".separa_partida_de_grupo($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"]);
						 $partida .= ".".mascara2($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_generica"]);
						 $partida .= ".".mascara2($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_especifica"]);
						 $partida .= ".".mascara2($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_sub_espec"]);



						 if($datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_sub_espec"]!=0){
                               $var_a = $this->cfpd01_sub_espec->findAll("cod_grupo='".$cod_grupo."' and  cod_partida = '".$cod_partida."' and cod_generica = '".$cod_generica."' and cod_especifica = '".$cod_especifica."' and cod_sub_espec = '".$cod_sub_espec."'");
						       $denominacion = $var_a[0]["cfpd01_sub_espec"]["descripcion"];

						 }else{
                               $var_a = $this->cfpd01_especifica->findAll("cod_grupo='".$cod_grupo."' and  cod_partida = '".$cod_partida."' and cod_generica = '".$cod_generica."' and cod_especifica = '".$cod_especifica."'");
						       $denominacion = $var_a[0]["cfpd01_especifica"]["descripcion"];
						 }//fin else



    echo "<script>";
			    echo "document.getElementById('seleccion_codigo').value='".$datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_snc"]."';";
			    echo "document.getElementById('codigo_sistema').value='".$datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["codigo_prod_serv"]."';";
			    echo "document.getElementById('seleccion_denominacion').value='".$datos_filas[0]["v_cscd01_catalogo_con_snc_denominacion"]["denominacion"]."';";
			    echo "document.getElementById('partida_presupuestaria').value='".$partida."';";
			    echo "document.getElementById('denominacion_partida').value='".$denominacion."';";
    echo "</script>";

     $this->Session->write('seleccion_codigo', $var1);


}//fin function







function buscar_medida_1($var1=null, $var2=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista3');

}//fin function








function buscar_medida_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


    if($var3==null){
					$this->Session->write('pista3', $var2);
					$var2 = strtoupper_sisap($var2);
					$Tfilas=$this->cscd01_unidad_medida->findCount("mayus_acentos(expresion) LIKE mayus_acentos('%$var2%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var2%')  ");
					//echo "mayus_acentos(expresion) LIKE mayus_acentos('%$var2%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var2%')  ";
					       if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_unidad_medida->findAll(" mayus_acentos(expresion) LIKE mayus_acentos('%$var2%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var2%')    ",null,"  cod_medida, expresion, denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista3');
						$var22 = strtoupper_sisap($var22);
						$Tfilas=$this->cscd01_unidad_medida->findCount(" mayus_acentos(expresion) LIKE mayus_acentos('%$var22%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var22%')   ");
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_unidad_medida->findAll(" mayus_acentos(expresion) LIKE mayus_acentos('%$var22%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var22%')   ",null," cod_medida, expresion, denominacion ASC",100,$pagina,null);
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
















function guardar_unidad_medida($var_n=null, $var2=null){

	  $this->layout = "ajax";

      $sw        = $this->v_cscd01_catalogo_con_snc_denominacion->execute("BEGIN; UPDATE cscd01_catalogo SET cod_medida='".$var2."' WHERE codigo_prod_serv='".$var_n."'; COMMIT; ");
      $var_datos = $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv=".$var_n);

     echo"<script>";
                   echo" document.getElementById('campo_b_".$var_n."').innerHTML ='".$var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion']." - ".$var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion_medida']."' ;";
     echo'</script>';


   $this->render("funcion");


}//fin function














function guardar($var_n=null, $var2=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $sw = 0;



	   if(!empty($this->data['cscp01_catalogo_mantenimiento_partida']['codigo_sistema'])){
	        $seleccion_codigo        =   $this->data['cscp01_catalogo_mantenimiento_partida']['codigo_sistema'];

	        $var_datos  = $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv=".$seleccion_codigo);

                         $cod_partida    = $var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"];
						 $cod_generica   = $var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_generica"];
						 $cod_especifica = $var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_especifica"];
						 $cod_sub_espec  = $var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_sub_espec"];
						 $cod_auxiliar   = 0;

             $sw   =  $this->v_cscd01_catalogo_con_snc_denominacion->execute("BEGIN; UPDATE cscd01_catalogo SET cod_partida='".$cod_partida."', cod_generica='".$cod_generica."', cod_especifica='".$cod_especifica."', cod_sub_espec='".$cod_sub_espec."', cod_auxiliar='".$cod_auxiliar."' WHERE codigo_prod_serv='".$var_n."' ");

        }else if(!empty($this->data['cscp01_catalogo_mantenimiento_partida']['seleccion_codigo'])){


              $pista = $this->data['cscp01_catalogo_mantenimiento_partida']['seleccion_codigo'];

        	                                          if($this->v_cfpd01_sub_espec_concatenado->findCount(" partida_presupuestaria::text = '".$pista."' ")!=0){

												       	$var_a = $this->v_cfpd01_sub_espec_concatenado->findAll(" partida_presupuestaria::text = '".$pista."' ");
												        $denominacion = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["descripcion"];

												         $cod_grupo      = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_grupo"];
												         $cod_partida    = $cod_grupo.mascara2($var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_partida"]);
														 $cod_generica   = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_generica"];
														 $cod_especifica = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_especifica"];
														 $cod_sub_espec  = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_sub_espec"];
														 $cod_auxiliar   = 0;


												       }else if($this->v_cfpd01_especifica_concatenado->findCount(" partida_presupuestaria::text = '".$pista."' ")!=0){

												       	 $var_a = $this->v_cfpd01_especifica_concatenado->findAll(" partida_presupuestaria::text = '".$pista."' ");
												         $denominacion = $var_a[0]["v_cfpd01_especifica_concatenado"]["descripcion"];

												         $cod_grupo      = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_grupo"];
												         $cod_partida    = $cod_grupo.mascara2($var_a[0]["v_cfpd01_especifica_concatenado"]["cod_partida"]);
														 $cod_generica   = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_generica"];
														 $cod_especifica = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_especifica"];
                                                         $cod_sub_espec  = 0;
                                                         $cod_auxiliar   = 0;



												       }


              $sw   =  $this->v_cscd01_catalogo_con_snc_denominacion->execute("BEGIN; UPDATE cscd01_catalogo SET cod_partida='".$cod_partida."', cod_generica='".$cod_generica."', cod_especifica='".$cod_especifica."', cod_sub_espec='".$cod_sub_espec."', cod_auxiliar='".$cod_auxiliar."' WHERE codigo_prod_serv='".$var_n."' ");

        }//fi else


      if($sw>1){
        $this->v_cscd01_catalogo_con_snc_denominacion->execute("COMMIT;");

                 $var_datos  = $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv=".$var_n);

                 $codigo_snc = $var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_snc'];

                         $partida =        substr($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"],0,1).".".separa_partida_de_grupo($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"]);
						 $partida .= ".".mascara2($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_generica"]);
						 $partida .= ".".mascara2($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_especifica"]);
						 $partida .= ".".mascara2($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_sub_espec"]);

			     echo"<script>";
			                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
			                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
			                   echo" document.getElementById('campo_0_".$var_n."').innerHTML ='".$codigo_snc."' ;";
			                   echo" document.getElementById('campo_b_".$var_n."').innerHTML ='".$var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion']." - ".$var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion_medida']."' ;";
			                   echo" document.getElementById('campo_c_".$var_n."').innerHTML ='".$partida."' ;";
			     echo'</script>';

                 $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }else{
     	$this->v_cscd01_catalogo_con_snc_denominacion->execute("ROLLBACK;");
     	$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else


   $this->render("funcion");


}//fin function











function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv=".$var1);
         $var2 = javascript_encode($var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion']);
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('campo_b_".$var1."').innerHTML ='<input type=text name=data[cscp01_catalogo_mantenimiento_partida][denominacion".$var1."]    id=denominacion".$var1."  value=\"$var2\"   class=campoText  />' ;";
     echo'</script>';

$this->render("funcion");
}//fin function








function limpiar_seleccion(){

	$this->layout = "ajax";

          echo "<script>";
			    echo "document.getElementById('seleccion_codigo').value='';";
			    echo "document.getElementById('codigo_sistema').value='';";
			    echo "document.getElementById('seleccion_denominacion').value='';";
			    echo "document.getElementById('partida_presupuestaria').value='';";
			    echo "document.getElementById('denominacion_partida').value='';";
    echo "</script>";

    $this->Session->write('seleccion_codigo', "");

    $this->render("funcion");
}//fin function






function funcion(){$this->layout = "ajax";}




function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv=".$var1);
      $var2 = $var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion'];
      $var3 = $var_datos[0]['v_cscd01_catalogo_con_snc_denominacion']['denominacion_medida'];

      					 $partida =        substr($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"],0,1).".".separa_partida_de_grupo($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_partida"]);
						 $partida .= ".".mascara2($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_generica"]);
						 $partida .= ".".mascara2($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_especifica"]);
						 $partida .= ".".mascara2($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_sub_espec"]);
						 $partida .= ".".mascara_cuatro($var_datos[0]["v_cscd01_catalogo_con_snc_denominacion"]["cod_auxiliar"]);

      echo'<script>';
           echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
           echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
           echo" document.getElementById('campo_b_".$var1."').innerHTML ='".$var2." - ".$var3."' ;";
      echo'</script>';
$this->render("funcion");
}//fin function








function duplicar($var1=null){

    $this->layout="ajax";

    $var_datos = $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var1);


    foreach($var_datos as $ve){

			  $codigo_prod_serv          =   $ve["cscd01_catalogo"]["codigo_prod_serv"];
			  $cod_tipo                  =   $ve["cscd01_catalogo"]["cod_tipo"];
			  $denominacion              =   $ve["cscd01_catalogo"]["denominacion"];
			  $especificaciones          =   $ve["cscd01_catalogo"]["especificaciones"];
			  $cod_medida                =   $ve["cscd01_catalogo"]["cod_medida"];
			  $cod_partida               =   $ve["cscd01_catalogo"]["cod_partida"];
			  $cod_generica              =   $ve["cscd01_catalogo"]["cod_generica"];
			  $cod_especifica            =   $ve["cscd01_catalogo"]["cod_especifica"];
			  $cod_sub_espec             =   $ve["cscd01_catalogo"]["cod_sub_espec"];
			  $cod_auxiliar              =   $ve["cscd01_catalogo"]["cod_auxiliar"];
			  $exento_iva                =   $ve["cscd01_catalogo"]["exento_iva"];
			  $alicuota_iva              =   $ve["cscd01_catalogo"]["alicuota_iva"];
			  $cod_snc                   =   $ve["cscd01_catalogo"]["cod_snc"];
			  $precio_referencia         =   $ve["cscd01_catalogo"]["precio_referencia"];
			  $fecha_precio              =   $ve["cscd01_catalogo"]["fecha_precio"];
			  $denominacion_fuente       =   $ve["cscd01_catalogo"]["denominacion_fuente"];
			  $distancia_ciudad          =   $ve["cscd01_catalogo"]["distancia_ciudad"];

    }//fin foreach


        $sql = "INSERT INTO cscd01_catalogo (cod_tipo, denominacion, especificaciones, cod_medida, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, exento_iva, alicuota_iva, cod_snc, precio_referencia, fecha_precio, denominacion_fuente, distancia_ciudad) ";
		$sql .= "VALUES('".$cod_tipo."', '".$denominacion."', '".$especificaciones."', '".$cod_medida."', '".$cod_partida."', '".$cod_generica."', '".$cod_especifica."', '".$cod_sub_espec."', '".$cod_auxiliar."', '".$exento_iva."', '".$alicuota_iva."', '".$cod_snc."', '".$precio_referencia."', '".$fecha_precio."', '".$denominacion_fuente."', '".$distancia_ciudad."')";
		$resultado = $this->cscd01_catalogo->execute($sql);


		if($resultado > 1){
		  $this->set('Message_existe', 'el dato fue insertado con exito');
		}else{
		  $this->set('errorMessage', 'el dato no fue insertado');
	    }//fin else





	                    $var22         =   $this->Session->read('pista');
						$var22         =   strtoupper_sisap($var22);
                        $var3          =   $this->Session->read('paginacion_cod_sistema_2');
                        $pista_opcion  =   $this->Session->read('pista_opcion');

	                    $sql     =" (mayus_acentos(cod_snc) LIKE mayus_acentos('%$var22%')     or       mayus_acentos(codigo_prod_serv::text) LIKE mayus_acentos('%$var22::text%'::text)  or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var22%')) ";


	                     if($pista_opcion==2){ $sql .= " and denominacion_snc is null   "; }

						$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null," denominacion ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }


$this->set("opcion",1);
$this->set("deno",$this->Session->read('pista'));


$this->render("buscar_cod_sistema_2");








}//fin fucntion












function buscar_cod_snc_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


    if($var3==null){
					$this->Session->write('pista22', $var2);
					$var2 = strtoupper_sisap($var2);

					 $sql_like = "";
					 $var_like_array = explode(" ", $var2);
					 foreach($var_like_array as $ve){
	   			 	    if($sql_like!=""){$sql_like .= " and "; }
					       $sql_like .= "( mayus_acentos(cod_tipo) LIKE mayus_acentos('%$ve%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$ve%') ) ";
					 }//fin foreach


					$Tfilas=$this->cscd01_snc_tipo->findCount($sql_like);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_snc_tipo->findAll($sql_like,null,"  cod_tipo, denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista22');
						$var22 = strtoupper_sisap($var22);

						 $sql_like = "";
						 $var_like_array = explode(" ", $var22);
						 foreach($var_like_array as $ve){
		   			 	    if($sql_like!=""){$sql_like .= " and "; }
						       $sql_like .= "( mayus_acentos(cod_tipo) LIKE mayus_acentos('%$ve%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$ve%') ) ";
						 }//fin foreach

						$Tfilas=$this->cscd01_snc_tipo->findCount($sql_like);
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_snc_tipo->findAll($sql_like,null," cod_tipo, denominacion ASC",100,$pagina,null);
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


















function buscar_cod_sistema_1($var1=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');


	$this->Session->write('pista_opcion', 1);

	if(isset($_SESSION["selecion_snc"])){

		$var_aux = $_SESSION["selecion_snc"];
		$sql     = " mayus_acentos(cod_snc)= mayus_acentos('$var_aux') ";

					$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"  denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
			$this->set("opcion",1);
            $this->set("deno",$var_aux);

	}//fin if


}//fin function








function buscar_cod_sistema_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


$pista_opcion = $this->Session->read('pista_opcion');


    if($var3==null){
					$this->Session->write('pista', $var2);
					$this->Session->write('paginacion_cod_sistema_2', 1);
					$var2 = strtoupper_sisap($var2);

					if(isset($_SESSION["selecion_snc"])){
						 $var_aux = $_SESSION["selecion_snc"];

							 if($_SESSION["selecion_snc"]==$var2){
	                             $sql     = " mayus_acentos(cod_snc)= mayus_acentos('$var_aux') ";
							 }else{
							     $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')        and     (".$this->busca_separado(array("codigo_prod_serv", "denominacion"), $var2).")) ";
	                         }//fin else
                     }else{
                     	         $sql     =" (".$this->busca_separado(array("cod_snc", "codigo_prod_serv", "denominacion"), $var2).") ";
                     }//fin else




					$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"  denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper_sisap($var22);
                        $this->Session->write('paginacion_cod_sistema_2', $var3);
						if(isset($_SESSION["selecion_snc"])){
						     $var_aux = $_SESSION["selecion_snc"];
							 if($_SESSION["selecion_snc"]==$var2){
	                             $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')) ";
							 }else{
							     $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')        and     (".$this->busca_separado(array("codigo_prod_serv", "denominacion"), $var22).")    ) ";
	                         }//fin else
	                     }else{
	                     	     $sql     =" (".$this->busca_separado(array("cod_snc", "codigo_prod_serv", "denominacion"), $var22).") ";
	                     }//fin else



						$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null," denominacion ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else




$this->set("opcion",$var1);
$this->set("deno",$this->Session->read('pista'));
}//fin function












function buscar_cod_sistema_22($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


$pista_opcion = $this->Session->read('pista_opcion2');


    if($var3==null){
					$this->Session->write('pista', $var2);
					$this->Session->write('paginacion_cod_sistema_2', 1);
					$var2 = strtoupper_sisap($var2);

					if(isset($_SESSION["selecion_snc"])){
						 $var_aux = $_SESSION["selecion_snc"];

							 if($_SESSION["selecion_snc"]==$var2){
	                             $sql     = " mayus_acentos(cod_snc)= mayus_acentos('$var_aux') ";
							 }else{
							     $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')        and     (".$this->busca_separado(array("codigo_prod_serv", "denominacion", "partida_presupuestaria"), $var2).")) ";
	                         }//fin else
                     }else{
                     	         $sql     =" (".$this->busca_separado(array("cod_snc", "codigo_prod_serv", "denominacion", "partida_presupuestaria"), $var2).") ";
                     }//fin else



					$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"  denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper_sisap($var22);
                        $this->Session->write('paginacion_cod_sistema_2', $var3);
						if(isset($_SESSION["selecion_snc"])){
						     $var_aux = $_SESSION["selecion_snc"];
							 if($_SESSION["selecion_snc"]==$var2){
	                             $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')) ";
							 }else{
							     $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')        and     (".$this->busca_separado(array("codigo_prod_serv", "denominacion", "partida_presupuestaria"), $var22).")    ) ";
	                         }//fin else
	                     }else{
	                     	     $sql     =" (".$this->busca_separado(array("cod_snc", "codigo_prod_serv", "denominacion", "partida_presupuestaria"), $var22).") ";
	                     }//fin else




						$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null," denominacion ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else




$this->set("opcion",$var1);
$this->set("deno",$this->Session->read('pista'));
}//fin function



























function pista_opcion($var1=null){


    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

	echo "<script>";
			    echo "document.getElementById('input_pista').value='';";
    echo "</script>";



}//fin fucntion





function pista_opcion2($var1=null){


    $this->layout="ajax";
	$this->Session->write('pista_opcion2', $var1);

	echo "<script>";
			    echo "document.getElementById('input_pista2').value='';";
    echo "</script>";



}//fin fucntion























function selecion_partida($var1=null){

   $this->layout="ajax";

   if(strlen($var1)!=9){

         $this->set("errorMessage", "La pista debe tener 9 digitos");
                           echo "<script>";
									    echo "document.getElementById('seleccion_codigo').value='';";
									    echo "document.getElementById('codigo_sistema').value='';";
									    echo "document.getElementById('seleccion_denominacion').value='';";
									    echo "document.getElementById('partida_presupuestaria').value='';";
									    echo "document.getElementById('denominacion_partida').value='';";
						    echo "</script>";

   }else{


   $aux = substr($var1, 0, (strlen($var1)-2));

												             if($this->v_cfpd01_sub_espec_concatenado->findCount(" partida_presupuestaria::text = '".$var1."' ")!=0){

												       	$var_a = $this->v_cfpd01_sub_espec_concatenado->findAll(" partida_presupuestaria::text = '".$var1."' ");
												        $denominacion = substr($var_a[0]["v_cfpd01_sub_espec_concatenado"]["descripcion"],0,80);

												         $cod_grupo      = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_grupo"];
												         $cod_partida    = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_partida"];
														 $cod_generica   = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_generica"];
														 $cod_especifica = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_especifica"];
														 $cod_sub_espec  = $var_a[0]["v_cfpd01_sub_espec_concatenado"]["cod_sub_espec"];

														 $pista = $var1;

														 $partida = $cod_grupo.mascara2($cod_partida).".".mascara2($cod_generica).".".mascara2($cod_especifica).".".mascara2($cod_sub_espec);



												       }else if($this->v_cfpd01_especifica_concatenado->findCount(" partida_presupuestaria::text = '".$aux."' ")!=0){

												       	 $var_a = $this->v_cfpd01_especifica_concatenado->findAll(" partida_presupuestaria::text = '".$aux."' ");
												         $denominacion = substr($var_a[0]["v_cfpd01_especifica_concatenado"]["descripcion"],0,80);

												         $cod_grupo      = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_grupo"];
												         $cod_partida    = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_partida"];
														 $cod_generica   = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_generica"];
														 $cod_especifica = $var_a[0]["v_cfpd01_especifica_concatenado"]["cod_especifica"];
                                                         $cod_sub_espec  = "00";

                                                         $pista = $aux;

														 $partida = $cod_grupo.mascara2($cod_partida).".".mascara2($cod_generica).".".mascara2($cod_especifica).".".mascara2($cod_sub_espec);


												       }else{

                                                          $this->set("errorMessage", "PARTIDA NO REGISTRADA");
                                                          $partida = "";
                                                          $denominacion = "";
                                                          $pista = "";

												       }







						    echo "<script>";
									    echo "document.getElementById('seleccion_codigo').value='".$pista."';";
									    echo "document.getElementById('codigo_sistema').value='';";
									    echo "document.getElementById('seleccion_denominacion').value='';";
									    echo "document.getElementById('partida_presupuestaria').value='".$partida."';";
									    echo "document.getElementById('denominacion_partida').value='".$denominacion."';";
						    echo "</script>";

   }//fin else

$this->funcion();
$this->render("funcion");


}//fin function


function salir_clave(){
	$this->layout="ajax";
	$this->Session->delete('autor_valido');
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp01_catalogo_mantenimiento_partida']['login']) && isset($this->data['cscp01_catalogo_mantenimiento_partida']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp01_catalogo_mantenimiento_partida']['login']);
		$paswd=addslashes($this->data['cscp01_catalogo_mantenimiento_partida']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=98 and clave='".$paswd."'";
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
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}// Entrar



}//fin clases
?>