<?php
class InfoReporteSinSessionController extends AppController {
   var $name = 'info_reporte_sin_session';
   var $uses = array("arrd01", "ccfd03_instalacion", "cscd01_catalogo", "cscd01_snc_tipo");
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');




function relacion_obras_proyectadas($var1=null){

set_time_limit(0);

		      if($var1==1){ $this->layout = "ajax";

			      	$datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ");
			      	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano_estimacion'];
								$deno[] = $n[0]['ano_estimacion'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_estimacion", $lista);
					$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

					$lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);


		}else if($var1==2){ $this->layout = "pdf";


			$consolidado     = $this->data["datos"]["radio_nivel_consulta"];
			$ano_consolidado = $this->data["datos"]["ano_consolidado"];
			$sql = "";


									  if(!empty($this->data["datos"]["cod_presi"])){

									  	if($this->data["datos"]["cod_presi"]!="TODO"){

						                          $sql .=" cod_presi = '".$this->data["datos"]["cod_presi"]."' ";

									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){

									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){

						                          $sql .=" and ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){

									  	if($this->data["datos"]["cod_entidad"]!="TODO"){

						                          $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";

									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){

									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){

						                          $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";

									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){

									  	if($this->data["datos"]["cod_inst"]!="TODO"){

						                          $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";

									  	}
									  }


					     $ordenar = " ORDER BY
										       ano_estimacion,
										       cod_presi,
											   cod_entidad,
											   cod_tipo_inst,
											   cod_inst,
										       codigo_prod_serv,
											   cod_obra ";

					     $datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);

					     $this->set("datos", $datos);


		}//fin else


$this->set("vista", $var1);



}//fin fucntion












function relacion_obras_proyectadas_segun_snc($var1=null, $var2=null, $var3=null){


set_time_limit(0);


		      if($var1==1){ $this->layout = "ajax";

			      	$datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ");
			      	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano_estimacion'];
								$deno[] = $n[0]['ano_estimacion'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente ningun cod_snc');
						}

					$this->set("ano_estimacion", $lista);
					$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

					$lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



	    }else if($var1==2){

	    	$this->layout = "ajax";

	    	   if(strtoupper($var2)=="TODO"){
		    		$sql= "";
		    	}else{
		    	 	$sql= "and a.ano_estimacion='".$var2."'";
		    	}

	    	$lista  = $this->arrd01->execute(" SELECT
	    	                                   a.codigo_prod_serv,
	    	                                  (SELECT dd.denominacion FROM cscd01_catalogo dd WHERE dd.codigo_prod_serv=a.codigo_prod_serv ) as deno_codigo_prod_serv,
										      (SELECT ddd.denominacion FROM cscd01_snc_tipo ddd WHERE ddd.cod_tipo=(SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv)) as deno_cod_snc,
										      (SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv) as cod_snc

	    			                          FROM cfpd07_obras_cuerpo a WHERE (a.codigo_prod_serv IS NOT NULL and  a.codigo_prod_serv!=0) ".$sql." GROUP BY a.codigo_prod_serv ORDER BY deno_cod_snc;
	    			");



			          if(count($lista)!=0){
							foreach($lista as $n){
								if($n[0]['cod_snc']!=""){
									$cod[]  = $n[0]['cod_snc'];
									$deno[] = $n[0]['cod_snc']." - ".$n[0]['deno_cod_snc'];
								}
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente ningun cod_snc');
						}

			$this->set('vector', $lista);
			$this->set("opcion", $var3);

		 }else if($var1==5){

	    	$this->layout = "ajax";
			$this->set("ano_ejecucion" , $var2);

			echo"<script>document.getElementById('capa_carga_2').innerHTML=''; </script>";

	   	}else if($var1==3){ $this->layout = "ajax";

	   		$cscd01_catalogo   =  $this->cscd01_snc_tipo->findAll("cod_tipo='".$var2."' ");

			foreach($cscd01_catalogo as $aux2){
				$cod_snc = $aux2['cscd01_snc_tipo']['cod_tipo'];
				$denominacion_snc = $aux2['cscd01_snc_tipo']['denominacion'];

			}//fin foreach


		echo'<script>';
		echo"   document.getElementById('cod_snc').value='".$cod_snc."';  ";
		echo"   document.getElementById('denominacion').value='".$denominacion_snc."';  ";
		echo'</script>';

		}else if($var1==4){ $this->layout = "pdf";


			$consolidado     = $this->data["datos"]["radio_nivel_consulta"];
			$ano_consolidado = $this->data["datos"]["ano_consolidado"];
			$sql = "";


									  if(!empty($this->data["datos"]["cod_presi"])){

									  	if($this->data["datos"]["cod_presi"]!="TODO"){

						                          $sql .=" cod_presi = '".$this->data["datos"]["cod_presi"]."' ";

									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){

									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){

						                          $sql .=" and ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){

									  	if($this->data["datos"]["cod_entidad"]!="TODO"){

						                          $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";

									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){

									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){

						                          $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";

									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){

									  	if($this->data["datos"]["cod_inst"]!="TODO"){

						                          $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";

									  	}
									  }


									  if($this->data["datos"]["tipo_consolidacion"]==2){

									  	if(!empty($this->data["datos"]["cod_snc_select"])){

                                              $sql .=" and cod_snc = '".$this->data["datos"]["cod_snc_select"]."' ";

									  	}
									  }


					     $ordenar = " ORDER BY
										       ano_estimacion,
										       cod_snc,
										       cod_presi,
											   cod_entidad,
											   cod_tipo_inst,
											   cod_inst,
										       cod_dep,
										       cod_dep_original,
											   cod_obra ";

					     $datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);



					     $this->set("datos", $datos);


		}//fin else


$this->set("vista", $var1);



}//fin fucntion

}// fin class

?>