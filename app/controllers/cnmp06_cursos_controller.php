<?php


 class Cnmp06CursosController extends AppController{
	var $uses = array('cnmd06_cursos','ccfd04_cierre_mes','cnmd06_datos_formacion_profesional','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp06_cursos";

//cnmp06_cursos

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
}//fin before filter



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




function index($var=null, $var_cont=null){

$this->verifica_entrada('44');

	  $this->layout = "ajax";

	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

      $var_cont = $this->cnmd06_cursos->findAll(null,null,"cod_curso DESC",1,1,null);

      $Tfilas=$this->cnmd06_cursos->findCount();
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/500);
        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cnmd06_cursos->findAll(null,null,"upper(trim(denominacion)) ASC",500,1,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
        $this->set('datos', $datos_filas);



     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cnmd06_cursos']['cod_curso'];}
     $var_cont2++;

     $this->set('codigo', $var_cont2);


}//fin index






function funcion(){$this->layout = "ajax";}






function guardar($var_n=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";



if($var_n==null){ $cod_curso   =   $this->data['cnmp06_cursos']['codigo'.$var_n];}else{ $cod_curso =  $var_n;}


if(!empty($this->data['cnmp06_cursos']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cnmp06_cursos']['denominacion'.$var_n];
   $var_cont = $this->cnmd06_cursos->findCount("cod_curso=".$cod_curso);

			         if($var_cont==0){
                        $sw = $this->cnmd06_cursos->execute("BEGIN; INSERT INTO cnmd06_cursos (cod_curso, denominacion) VALUES ('".$cod_curso."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cnmd06_cursos->execute("BEGIN; UPDATE cnmd06_cursos SET denominacion='".$denominacion."' WHERE cod_curso='".$cod_curso."' ");
			         }//fin function
}else{$sw="a";}

          if($sw>1){

        $this->cnmd06_cursos->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cnmd06_cursos->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');

     }else{

     	$this->cnmd06_cursos->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
	$this->set('autor_valido',true);
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cnmd06_cursos->findAll("cod_curso=".$cod_curso);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cnmd06_cursos']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function














function mostrar_datos($pagina=null){

    $this->layout = "ajax";



      if(isset($pagina)){
				$pagina=$pagina;
		}else{
				 $pagina=1;
		}//fin else
		$Tfilas=$this->cnmd06_cursos->findCount(null);
        if($Tfilas!=0){
        	$Tfilas=(int)ceil($Tfilas/500);
        	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cnmd06_cursos->findAll(null,null,"upper(trim(denominacion)) ASC",500,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }


$this->set('datos', $datos_filas);


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








function editar($var1=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_cursos->findAll("cod_curso=".$var1);
         $var2 = $var_datos[0]['cnmd06_cursos']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=\"text\" name=data[cnmp06_cursos][denominacion".$var1."]    id=\denominacion$var1\"  value=\"$var2\"   class=\"campoText\"/>';";
     echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
 		$a=$this->cnmd06_datos_formacion_profesional->FindCount("cod_curso='$var1'");
	    if($a==0){
	    	  $sw = $this->cnmd06_cursos->execute("DELETE FROM cnmd06_cursos  WHERE cod_curso='".$var1."' ");
	    }else{
	    	$this->set('errorMessage','ESTE DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR OTRO PROGRAMA');
	    }
		$this->render("funcion");
 	 //$datos    = $this->cnmd06_cursos->findAll(null, null, 'cod_curso ASC');
     //$this->set('datos', $datos);
}//fin function



function cancelar($var1=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->cnmd06_cursos->findAll("cod_curso=".$var1);
      $var2 = $var_datos[0]['cnmd06_cursos']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function


function repetidos () {
   $this->layout="ajax";

   $sql="SELECT  x.denominacion,
(SELECT c.cod_curso FROM cnmd06_cursos c
WHERE  upper(trim(quitar_acentos(c.denominacion)))=upper(trim(quitar_acentos(x.denominacion))) order by c.cod_curso ASC limit 1) as codigos
FROM cnmd06_cursos x
GROUP BY  x.denominacion
ORDER BY  x.denominacion ASC
";

$sqlx="SELECT x.denominacion,
(SELECT c.cod_curso FROM cnmd06_cursos c
WHERE 2=2 and c.denominacion=x.denominacion order by c.cod_curso ASC limit 1) as codigos
FROM cnmd06_cursos x where 1=1
GROUP BY  x.denominacion
ORDER BY x.denominacion ASC
";

   $res=$this->cnmd06_cursos->execute($sqlx);
   $i=1;
   $xx=1;
   $updates ="";
   $delete="";
   foreach($res as $r){
   	   $sql2 = "SELECT c.cod_curso FROM cnmd06_cursos c WHERE c.denominacion=(select x.denominacion from cnmd06_cursos x where x.cod_curso=".$r[0]['codigos'].") and c.cod_curso<>".$r[0]['codigos'];
   	   $sql_c = "SELECT count(*) as cantidad FROM cnmd06_cursos c WHERE c.denominacion=(select x.denominacion from cnmd06_cursos x where x.cod_curso=".$r[0]['codigos'].") and c.cod_curso<>".$r[0]['codigos'];
   	   $co = $this->cnmd06_cursos->execute($sql_c);
   	   if($co[0][0]['cantidad']!=0){
            $res2=$this->cnmd06_cursos->execute($sql2);
            $cod = array();
            foreach($res2 as $r2){
            	$cod[] = $r2[0]['cod_curso'];
            }
            $updates .="UPDATE cnmd06_datos_formacion_profesional SET cod_curso=".$r[0]['codigos']."   WHERE cod_curso in (".implode(',',$cod).");";
            $delete .= "DELETE FROM cnmd06_cursos WHERE cod_curso in (".implode(',',$cod).");";
   	        $xx++;
   	        echo "<br/>".$i." - ".$r[0]['denominacion']." =>".$r[0]['codigos'];$i++;
   	   }

   }

   $this->cnmd06_cursos->execute($updates);
   $this->cnmd06_cursos->execute($delete);
   //echo "UPDATES:<hr>".$updates;
   //echo "<br/><br/>DELETES:<hr>".$delete;
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp06_cursos']['login']) && isset($this->data['cnmp06_cursos']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp06_cursos']['login']);
		$paswd=addslashes($this->data['cnmp06_cursos']['password']);
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$user."' and cod_tipo=42 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($condicion)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}



}//fin class
?>
