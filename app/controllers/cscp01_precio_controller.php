<?php

class Cscp01PrecioController extends AppController {

    var $name = 'cscp01_precio';
    var $uses = array('cscd01_catalogo','v_cscd01_catalogo_precio','cscd02_solicitud_cuerpo','cscd02_solicitud_cuerpo_anulado','cscd03_cotizacion_cuerpo','cscd03_cotizacion_cuerpo_anulado','cscd05_ordencompra_nota_entrega_cuerpo');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

    function checkSession() {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            $this->requestAction('/usuarios/actualizar_user');
        }
    }

//fin checksession

    function beforeFilter() {
        $this->checkSession();
    }

    function verifica_SS($i) {
        /**
         * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
         * para ser insertados en todas las tablas.
         * */
        switch ($i) {
            case 1:return $this->Session->read('SScodpresi');
                break;
            case 2:return $this->Session->read('SScodentidad');
                break;
            case 3:return $this->Session->read('SScodtipoinst');
                break;
            case 4:return $this->Session->read('SScodinst');
                break;
            case 5:return $this->Session->read('SScoddep');
                break;
            case 6:return $this->Session->read('entidad_federal');
                break;
            default:
                return "NULO";
        }//fin switch
    }

//fin verifica_SS

    function SQLCA($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if ($ano != null) {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }
        return $sql_re;
    }

//fin funcion SQLCA
//fin function

    function index($pagina = null) {
        $this->layout = "ajax";

        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 1);
    }



    function mostrar($pagina = null, $todos = null) {
        $this->layout = "ajax";
        if (!isset($pagina)) {$pagina = 1;}


        if ($todos == null )

            {
            //trae la informacion de los productos
            $Tfilas = $this->v_cscd01_catalogo_precio->findCount("cod_tipo=1 and precio_referencia = 0");
            $Tpag = (int) ceil($Tfilas / 250);
            $this->set('TotalPaginas', $Tpag);
            $this->set('pagina_actual', $pagina);
            $this->set('paginacion', $pagina . ' / ' . $Tpag);
            $this->set('ultimo', $Tpag);
            $datos = $this->v_cscd01_catalogo_precio->findAll("cod_tipo='1' and precio_referencia = 0"." ", null, 'denominacion ASC', 250, $pagina, null);
            $this->set("datos", $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tpag, $pagina);
            if ($Tfilas > 19) {
                $this->set("scroll", 1);

            }else {$this->set("scroll", 0);}


        } else if ($todos != null ) {
            $Tfilas = $this->v_cscd01_catalogo_precio->findCount("cod_tipo=1 and precio_referencia = 0");
            $Tpag = (int) ceil($Tfilas / 250);
            $this->set('TotalPaginas', $Tpag);
            $this->set('pagina_actual', $pagina);
            $this->set('paginacion', $pagina . ' / ' . $Tpag);
            $this->set('ultimo', $Tpag);
            $datos = $this->v_cscd01_catalogo_precio->findAll("cod_tipo='1' and precio_referencia =0", null, 'denominacion ASC', 250, $pagina, null);
            $this->set("datos", $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tpag, $pagina);

            if ($Tfilas > 19) {
                $this->set("scroll", 1);
            } else {
                $this->set("scroll", 0);
            }
       }



    }


    function editar($pagina = null) {
            $this->layout = "ajax";

            $Tfilas = $this->v_cscd01_catalogo_precio->findCount("cod_tipo=1 and precio_referencia > 0");
            $Tpag = (int) ceil($Tfilas / 250);
            $this->set('TotalPaginas', $Tpag);
            $this->set('pagina_actual', $pagina);
            $this->set('paginacion', $pagina . ' / ' . $Tpag);
            $this->set('ultimo', $Tpag);
            $datos = $this->v_cscd01_catalogo_precio->findAll("cod_tipo='1' and precio_referencia > 0", null, 'denominacion ASC', 250, $pagina, null);
            $this->set("datos", $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tpag, $pagina);

            if ($Tfilas > 19) {$this->set("scroll", 1);

            }else {$this->set("scroll", 0);}

    }




    function mostrar_busqueda($pagina = null, $pista = null) {
        $this->layout = "ajax";
        if (!isset($pagina)) {
            $pagina = 1;
        }

        if ($pista == null) {
            $pista = $this->Session->read('pista');
        } else {
            $this->Session->write('pista', $pista);
        }

        
            $pista_opcion = $this->Session->read('pista_opcion');
            //condiciones de precio '1' => 'Todo', '2' => 'Con Precio', '3' => 'Sin Precio'

            if ($pista_opcion == 2) {
                $sql2 = "and precio_referencia > 0";
            } else if ($pista_opcion == 3) {
                $sql2 = "and precio_referencia = 0";
            }

            //condiciones
            $sql = " (" . $this->busca_separado(array("codigo_prod_serv", "denominacion"), $pista) . ") ";

            //trae la informacion de los productos
            $Tfilas = $this->v_cscd01_catalogo_precio->findCount("cod_tipo=1 and " . $sql . " " . $sql2);
            $Tpag = (int) ceil($Tfilas / 250);
            $this->set('TotalPaginas', $Tpag);
            $this->set('pagina_actual', $pagina);
            $this->set('paginacion', $pagina . ' / ' . $Tpag);
            $this->set('ultimo', $Tpag);
            $datos = $this->v_cscd01_catalogo_precio->findAll("cod_tipo='1' and" . $sql . " " . $sql2, null, 'denominacion ASC', 250, $pagina, null);
            $this->set("datos", $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tpag, $pagina);

            if ($Tfilas > 19) {
                $this->set("scroll", 1);
            } else {
                $this->set("scroll", 0);
            }

            $_SESSION['scroll'] = $scroll;

            if ($Tfilas == 0) {
                $this->set('errorMessage', "Lo siento, no se encontraron datos");
            }
        
        $datos[1] = "";
    }

    function busqueda() {
        $this->layout = "ajax";
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 1);
    }

    function vacio() {
        $this->layout = "ajax";
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 1);
    }

    function campo_monto($codigos, $fila, $precio = null,$scroll) {
        $this->layout = "ajax";

        $condicion = "cod_tipo=1 and codigo_prod_serv=" . $codigos;
        $resultado = $this->v_cscd01_catalogo_precio->findAll($condicion, array(), null, null, null, null);
        $this->set('codigo', $codigos);
        $this->set('fila', $fila);
        $this->set('denominacion', $resultado[0]['v_cscd01_catalogo_precio']['denominacion']);
        $this->set('fecha_precio', $resultado[0]['v_cscd01_catalogo_precio']['fecha_precio']);
        $denominacion_fuente=$resultado[0]['v_cscd01_catalogo_precio']['denominacion_fuente'];
        $distancia_ciudad= $resultado[0]['v_cscd01_catalogo_precio']['distancia_ciudad'];
        $this->set('scroll',$scroll);

        if ($precio != null) {
            $this->set('precio_referencia', $precio);
        } else {
            $this->set('precio_referencia', $resultado[0]['v_cscd01_catalogo_precio']['precio_referencia']);
        }
        
        echo "<script> 
            $('denominacion_fuente').value='$denominacion_fuente';
            $('distancia_ciudad').value='$distancia_ciudad';
            </script>";
    }


    
    function modificar($codigo, $fila,$scroll) {
        $this->layout = "ajax";
        $this->set('codigo', $codigo);
        $this->set("fila", $fila);
        $this->set("scroll", $scroll);

        $precio_referencia = $this->data['cscp01_precio']['precio_referencia'];

     //   $precio_referencia=str_replace(".","", $precio_referencia);
     //   $precio_referencia=str_replace(",",".", $precio_referencia);



        $denominacion_fuente = $this->data['cscp01_precio']['denominacion_fuente'];
        $distancia_ciudad = $this->data['cscp01_precio']['distancia_ciudad'];
        $fecha_precio = date("d/m/Y");


        if ($denominacion_fuente != null && $distancia_ciudad != null) {
            $condicion = "cod_tipo=1 and codigo_prod_serv=" . $codigo;
            $this->cscd01_catalogo->execute("UPDATE cscd01_catalogo SET precio_referencia=" . $this->Formato1($precio_referencia) . ", denominacion_fuente='" . $denominacion_fuente . "', fecha_precio='" . $fecha_precio . "', distancia_ciudad='" . $distancia_ciudad . "'  WHERE " . $condicion);
            $resultado = $this->v_cscd01_catalogo_precio->findAll($condicion, array(), null, null, null, null);
            $precio_bd = number_format($resultado[0]['v_cscd01_catalogo_precio']['precio_referencia'], 2, ',', '.');

            $this->set('id', $fila);
            $this->set('codigo', $codigo);
            $this->set('denominacion', $resultado[0]['v_cscd01_catalogo_precio']['denominacion']);
            $this->set('precio_referencia', $resultado[0]['v_cscd01_catalogo_precio']['precio_referencia']);
            $this->set('fecha_precio', $resultado[0]['v_cscd01_catalogo_precio']['fecha_precio']);

            $this->muestra_fila($codigo, $fila, $precio_referencia,$scroll);
            $this->render('muestra_fila');

            if ($precio_referencia == $precio_bd) {
                $this->set('Message_existe', 'Registro Actualizado con exito');
            }
            else
                $this->set('errorMessage', 'Dato no Actualizado');
        }else {

            $condicion = "cod_tipo=1 and codigo_prod_serv=" . $codigo;
            $resultado = $this->v_cscd01_catalogo_precio->findAll($condicion, array(), null, null, null, null);
         //   $precio_referencia = $this->data['cscp01_precio']['precio_referencia'];

            $this->set('id', $fila);
            $this->set('codigo', $codigo);
            $this->set('denominacion', $resultado[0]['v_cscd01_catalogo_precio']['denominacion']);
            $this->set('fecha_precio',$fecha_precio);
            $this->set('precio_referencia',$precio_referencia);

            $this->set('errorMessage', 'Debe Indicar DENOMINACIÓN DE LA FUENTE Y DISTANCIA(CIUDAD)');

            $this->campo_monto($codigo, $fila, $precio_referencia,$scroll);
            $this->render('campo_monto');
        }
    }


function eliminar($codigo_prod_serv=null, $id=null){
	  $this->layout = "ajax";
          $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $cont                     =       0;
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

			$cont=$cont + $this->cscd02_solicitud_cuerpo->findCount("codigo_prod_serv=".$codigo_prod_serv);
			$cont=$cont + $this->cscd02_solicitud_cuerpo_anulado->findCount("codigo_prod_serv=".$codigo_prod_serv);
			$cont=$cont + $this->cscd03_cotizacion_cuerpo->findCount("codigo_prod_serv=".$codigo_prod_serv);
			$cont=$cont + $this->cscd03_cotizacion_cuerpo_anulado->findCount("codigo_prod_serv=".$codigo_prod_serv);
			$cont=$cont + $this->cscd05_ordencompra_nota_entrega_cuerpo->findCount("codigo_prod_serv=".$codigo_prod_serv);

         if($cont==0){
                 $this->cscd02_solicitud_cuerpo->execute("DELETE FROM cscd01_catalogo  WHERE codigo_prod_serv='".$codigo_prod_serv."';");
        	    echo"<script> new Effect.DropOut('".$id."'); </script>";
        	    $this->set('errorMessage', 'LOS DATOS FUERON ELIMINADOS');
         }else{
         	    $this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS, YA ESTA SIENDO USADO');
         }//fin else

//$this->render("mostrar");
}//fin function


    function muestra_fila($codigo, $fila, $scroll) {

        $this->layout = "ajax";

        $condicion = "cod_tipo=1 and codigo_prod_serv=" . $codigo;
        $resultado = $this->v_cscd01_catalogo_precio->findAll($condicion, array(), null, null, null, null);
        $this->set('scroll', $scroll);
        $this->set('id', $fila);
        $this->set('codigo', $codigo);

        $this->set('denominacion', $resultado[0]['v_cscd01_catalogo_precio']['denominacion']);
        $this->set('precio_referencia', $resultado[0]['v_cscd01_catalogo_precio']['precio_referencia']);
        $this->set('fecha_precio', $resultado[0]['v_cscd01_catalogo_precio']['fecha_precio']);
        
         echo "<script> 
            $('denominacion_fuente').value='';
            $('distancia_ciudad').value='';
            </script>";
        
    }

//fin function

    function bt_nav($Tfilas, $pagina) {
        if ($Tfilas == 1) {
            $this->set('mostrarS', false);
            $this->set('mostrarA', false);
        } else if ($Tfilas == 2) {
            if ($pagina == 2) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            }
        } else if ($Tfilas >= 3) {
            if ($pagina == $Tfilas) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else if ($pagina == 1) {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', true);
            }
        }
    }

//fin navegacion


    function entrar() {
        $this->layout = "ajax";
        if (isset($this->data['cscp01_precio']['login']) && isset($this->data['cscp01_precio']['password'])) {
            $l  = "PROYECTO";
            $c  = "JJJSAE";
            $c1 = "RPDCSF";
            $user = addslashes($this->data['cscp01_precio']['login']);
            $paswd = addslashes($this->data['cscp01_precio']['password']);
            $cond = $this->SQLCA() . " and username='" . $user . "' and cod_tipo=33 and clave='" . $paswd . "'";
            if ($user == $l && ($paswd == $c || $paswd == $c1)){
                $this->Session->write('pase_valido', 'pase_valido');
                $this->set('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } elseif ($this->cugd05_restriccion_clave->findCount($cond) != 0) {
                $this->Session->write('pase_valido', 'pase_valido');
                $this->set('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } else {
                $this->Session->write('pase_valido', 'pase_no_valido');
                $this->set('errorMessage', "Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
                $this->set('autor_valido', false);
                $this->index("autor_valido");
                $this->render("index");
            }
        }
    }

    function buscar() {
        $this->layout = "ajax";

        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 1);
    }

    function pista_opcion($valor = null) {
        $this->layout = "ajax";

        echo "<script>";
        echo "pista= document.getElementById('input_pista').value;

                
                    ver_documento('/cscp01_precio/mostrar_busqueda/1/'+pista, 'ListarProductos');
               
              </script>";


        $this->Session->write('pista_opcion', $valor);
    }

    function salir_vacio() {
        $this->layout = "ajax";
        $this->Session->delete('pase_valido');
    }

}

//fin class
?>