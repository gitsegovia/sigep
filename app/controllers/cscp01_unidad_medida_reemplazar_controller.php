<?php
 class cscp01UnidadMedidaReemplazarController extends AppController{
	 var $name='cscp01_unidad_medida_reemplazar';
     var $uses = array('cscd01_unidad_medida');

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

}//fin function






function index(){

   $this->layout="ajax";

}//fin function








function buscar_producto($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function



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




function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_int($var2)){$sql   = " (cod_medida LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->cscd01_unidad_medida->findCount($sql." (denominacion LIKE '%$var2%')  OR  (expresion LIKE '%$var2%')   ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_unidad_medida->findAll($sql." (denominacion LIKE '%$var2%')  OR  (expresion LIKE '%$var2%')   ",null,"cod_medida ASC",100,1,null);
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
						if(is_int($var22)){$sql   = " (cod_medida LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->cscd01_unidad_medida->findCount($sql." (denominacion LIKE '%$var22%')  OR  (expresion LIKE '%$var22%')   ");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_unidad_medida->findAll($sql." (denominacion LIKE '%$var22%')  OR  (expresion LIKE '%$var22%')   ",null,"cod_medida ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
$this->set("opcion",$var1);
}//fin function





function funcion($var1=null, $var2=null, $var3=null){

    $this->layout="ajax";


}//fin function








function seleccion_busqueda_venta($var1=null, $var2=null, $var3=null){

$this->layout="ajax";



	$datos_prod1 = $this->cscd01_unidad_medida->execute("

					SELECT

						a.cod_medida,
					    a.expresion,
						a.denominacion



					FROM

					 cscd01_unidad_medida a


					WHERE

						a.cod_medida='".$var2."'

            ");




		     if($var1==1){

					echo "<script>";
					    echo "document.getElementById('campo_a_1').value='".mascara2($datos_prod1[0][0]['cod_medida'])."';   ";
					    echo "document.getElementById('campo_b_1').value='".$datos_prod1[0][0]['expresion']."';   ";
					    echo "document.getElementById('campo_c_1').value='".$datos_prod1[0][0]['denominacion']."';   ";
					echo "</script>";

		}else if($var1==2){

					echo "<script>";
					    echo "document.getElementById('campo_a_2').value='".mascara2($datos_prod1[0][0]['cod_medida'])."';   ";
					    echo "document.getElementById('campo_b_2').value='".$datos_prod1[0][0]['expresion']."';   ";
					    echo "document.getElementById('campo_c_2').value='".$datos_prod1[0][0]['denominacion']."';   ";
					    echo "document.getElementById('replace').disabled=false;   ";

					echo "</script>";


		}//fin if





$this->funcion();
$this->render("funcion");



}//fin function








function reemplazar(){
	$this->layout="ajax";
	//pr($this->data);
	if(!empty($this->data['cscp01_unidad_medida_reemplazar'])){
		$cod_prod1 = $this->data['cscp01_unidad_medida_reemplazar']['campo_a_1'];
		$cod_prod2 = $this->data['cscp01_unidad_medida_reemplazar']['campo_a_2'];


		//echo $cod_prod1." || ".$cod_prod2;

		$sql_a        = " UPDATE cscd01_catalogo                           SET cod_medida='".$cod_prod2."'    WHERE cod_medida='".$cod_prod1."';  ";
		$sql_a       .= " UPDATE cscd02_solicitud_cuerpo                   SET cod_medida='".$cod_prod2."'    WHERE cod_medida='".$cod_prod1."';  ";
		$sql_a       .= " UPDATE cscd02_solicitud_cuerpo_anulado           SET cod_medida='".$cod_prod2."'    WHERE cod_medida='".$cod_prod1."';  ";
		$sql_a       .= " UPDATE cscd03_cotizacion_cuerpo                  SET cod_medida='".$cod_prod2."'    WHERE cod_medida='".$cod_prod1."';  ";
		$sql_a       .= " UPDATE cscd03_cotizacion_cuerpo_anulado          SET cod_medida='".$cod_prod2."'    WHERE cod_medida='".$cod_prod1."';  ";
		$sql_a       .= " UPDATE cscd05_ordencompra_nota_entrega_cuerpo    SET cod_medida='".$cod_prod2."'    WHERE cod_medida='".$cod_prod1."';  ";
        $sql_delete   = " DELETE FROM cscd01_unidad_medida WHERE cod_medida='".$cod_prod1."'; ";




	   $sw1 = $this->cscd01_unidad_medida->execute("BEGIN; ".$sql_a);
	if($sw1>1){
			$sw2 = $this->cscd01_unidad_medida->execute($sql_delete);
			if($sw2 > 1){
				$this->cscd01_unidad_medida->execute("COMMIT;");
				$this->set('msg', "EL CÓDIGO FUE ELIMINADO Y REEMPLAZADO CON EXITO");
			}else{
				$this->cscd01_unidad_medida->execute("ROLLBACK;");
				$this->set('msg_error', 'EL CÓDIGO NO FUE ELIMINADO - POR FAVOR INTENTE DE NUEVO');
			}
		}else{
			$this->cscd01_unidad_medida->execute("ROLLBACK;");
			$this->set('msg_error', 'EL CÓDIGO NO FUE ELIMINADO - POR FAVOR INTENTE DE NUEVO');
		}

		$this->data['cscp01_unidad_medida_reemplazar'] = null;

	}else{
		$this->set('msg_error', 'EL CÓDIGO NO FUE ELIMINADO - FALTAN DATOS');
	}

	$this->index();
	$this->render('index');
}







}//fin clase