<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
class Cnmp06DatosPersonalesController extends AppController
{
    public $name = 'cnmp06_datos_personales';
    public $uses = array('cugd10_imagenes','cnmd06_colores','cnmd06_clubes','cnmd06_datos_personales','cnmd06_deportes','cnmd06_profesiones','cnmd06_fichas',
                      'cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cnmd06_profesiones',
                      'cnmd06_especialidades','cnmd06_deportes','cnmd06_religiones','cnmd06_hobby','cnmd06_oficio', 'datos_personales_super_busqueda', 'cnmd06_datos_hijos');
    public $helpers = array('Html','Ajax','Javascript', 'Sisap');

    public function checkSession()
    {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
            //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
            $this->requestAction('/usuarios/actualizar_user');
        }
    }//fin checksession

    public function beforeFilter()
    {
        $this->checkSession();
    }

    public function verifica_SS($i)
    {
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
    }//fin verifica_SS

    public function SQLCA($ano = null) //sql para busqueda de codigos de arranque con y sin año
    {
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if($ano != null) {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }
        return $sql_re;
    }//fin funcion SQLCA

    public function zero($x = null)
    {
        if($x != null) {
            if($x < 10) {
                $x = "0" . $x;
            } elseif($x >= 10 && $x <= 99) {
                $x = $x;
            }
        }
        return $x;

    }//fin zero





    public function index($id = null)
    {
        $this->layout = "ajax";
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
        $this->concatena($listarepublica, 'cod_republica');


        $listaprofesion = $this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
        $this->concatena_tres_digitos($listaprofesion, 'cod_profesion');


        $lista =  $this->cugd01_estados->generateList("cod_republica=1", 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($lista, 'cod_estado');

        $listaoficio =  $this->cnmd06_oficio->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion');
        $this->concatena_tres_digitos("", 'oficio');


        $listacolor =  $this->cnmd06_colores->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_colores.cod_color', '{n}.cnmd06_colores.denominacion');
        $this->concatena($listacolor, 'color');
        $listaclubes =  $this->cnmd06_clubes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_clubes.cod_club', '{n}.cnmd06_clubes.denominacion');
        $this->concatena_tres_digitos($listaclubes, 'club');
        $listadeporte =  $this->cnmd06_deportes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_deportes.cod_deporte', '{n}.cnmd06_deportes.denominacion');
        $this->concatena_tres_digitos($listadeporte, 'deporte');
        $listareligion =  $this->cnmd06_religiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_religiones.cod_religion', '{n}.cnmd06_religiones.denominacion');
        $this->concatena($listareligion, 'religion');
        $listahobby =  $this->cnmd06_hobby->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_hobby.cod_hobby', '{n}.cnmd06_hobby.denominacion');
        $this->concatena_tres_digitos($listahobby, 'hobby');

        $this->Session->delete('pro');


        echo"<script>
				          	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
					          </script>";

        if($id != null) {
            if($id == "regresa") {
                $id = "";
            }
            $this->set('cedula', $id);
            $this->Session->write('cedula_pestana_expediente', "");
        } else {
            if($this->Session->read('cedula_pestana_expediente') == "") {
                $id = 0;
            } else {
                $id = $this->Session->read('cedula_pestana_expediente');
            }
            $this->set('cedula', "");
            $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
            if($Tfilas != 0) {
                $this->busca_foto($this->Session->read('cedula_pestana_expediente'));
            }
        }//fin else

    }//fin if


    public function select3($select = null, $var = null) //select codigos presupuestarios
    {
        $this->layout = "ajax";
        if($var != null) {
            switch($select) {
                case 'republica':
                    $this->set('SELECT', 'estado');
                    $this->set('codigo', 'republica');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    $this->Session->write('rep', $var);
                    $lista =  $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cfpd02_sector.cod_republica', '{n}.cfpd02_sector.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'estado':
                    $this->set('SELECT', 'municipio');
                    $this->set('codigo', 'estado');
                    $this->set('seleccion', '');
                    $this->set('n', 2); //$var = 1;
                    $this->Session->write('rep', $var);
                    $cond = " cod_republica=" . $var;
                    $lista =  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'municipio':
                    $this->set('SELECT', 'parroquia');
                    $this->set('codigo', 'municipio');
                    $this->set('seleccion', '');
                    $this->set('n', 3);
                    $this->set('mostrar_ciudad', true);

                    if(!isset($_SESSION["rep"])) {
                        $this->Session->write('rep', 1);
                    }
                    $rep =  $this->Session->read('rep');
                    $this->Session->write('est', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $var;
                    $lista =  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'parroquia':
                    $this->set('SELECT', 'centro');
                    $this->set('codigo', 'parroquia');
                    $this->set('seleccion', '');
                    $this->set('n', 4);
                    $rep =  $this->Session->read('rep');
                    $est =  $this->Session->read('est');
                    $this->Session->write('mun', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $var;
                    $lista =  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'centro':
                    $this->set('SELECT', 'centro');
                    $this->set('codigo', 'centro');
                    $this->set('seleccion', '');
                    $this->set('n', 5);
                    $this->set('no', 'no');
                    $rep =  $this->Session->read('rep');
                    $est =  $this->Session->read('est');
                    $mun =  $this->Session->read('mun');
                    $this->Session->write('par', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $var;
                    $lista =  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 15);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }//fin select codigos presupuestarios

    public function denociudad($var = null)
    {
        $this->layout = "ajax";
        $rep =  $this->Session->read('rep');
        $est = $this->Session->read('est');

        $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $var;
        $d =  $this->cugd01_municipios->findAll($cond);
        $this->set('ciudad1', $d[0]['cugd01_municipios']['conocido']);

    }




    public function select4($select = null, $var = null) //select codigos presupuestarios
    {
        $this->layout = "ajax";
        if($var != null) {
            switch($select) {
                case 'profecion':
                    $this->set('SELECT', 'especialidad');
                    $this->set('codigo', 'profesion');
                    $this->set('seleccion', '');
                    $this->set('n', 6);
                    $listaprofesion = $this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
                    $this->concatena_tres_digitos($listaprofesion, 'vector');
                    break;
                case 'especialidad':
                    $this->set('SELECT', 'especialidad');
                    $this->set('codigo', 'especialidad');
                    $this->set('seleccion', '');
                    $this->set('n', 7);
                    $this->set('no', 'no');
                    $this->Session->write('pro', $var);
                    $cond = " cod_profesion=" . $var;
                    $listaespecialidades = $this->cnmd06_especialidades->generateList($cond, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
                    $this->concatena_tres_digitos($listaespecialidades, 'vector');
                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 16);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }//fin select codigos presupuestarios


    public function select5($select = null, $var = null) //select codigos presupuestarios
    {
        $this->layout = "ajax";
        if($var != null) {
            switch($select) {
                case 'esta':
                    //echo "estado";
                    $this->set('SELECT', 'munici');
                    $this->set('codigo', 'estado');
                    $this->set('seleccion', '');
                    $this->set('n', 8);
                    $this->Session->write('rep2', 1);
                    $var2 = $this->Session->read('rep2');
                    $cond = " cod_republica=" . $var2;
                    $lista =  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'munici':
                    //	echo "municipio";
                    $this->set('SELECT', 'parro');
                    $this->set('codigo', 'munici');
                    $this->set('seleccion', '');
                    $this->set('n', 9);
                    $this->set('buitre2', true);
                    $this->Session->write('est2', $var);
                    $this->Session->write('rep2', 1);
                    $var2 = $this->Session->read('rep2');
                    $cond = " cod_republica=" . $var2 . " and ";
                    $cond .= " cod_estado=" . $var;
                    $lista =  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'parro':
                    $this->set('SELECT', 'cen');
                    $this->set('codigo', 'parro');
                    $this->set('seleccion', '');
                    $this->set('n', 10);
                    $est =  $this->Session->read('est2');
                    $this->Session->write('mun2', $var);
                    $var2 = $this->Session->read('rep2');
                    $cond = " cod_republica=" . $var2 . " and ";
                    $cond .= " cod_estado=" . $est . " and cod_municipio=" . $var;
                    $lista =  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'cen':
                    //	echo "centro";
                    $this->set('SELECT', 'cen');
                    $this->set('codigo', 'cen');
                    $this->set('seleccion', '');
                    $this->set('n', 11);
                    $this->set('no', 'no');
                    $est =  $this->Session->read('est2');
                    $mun =  $this->Session->read('mun2');
                    $this->Session->write('par2', $var);
                    $var2 = $this->Session->read('rep2');
                    $cond = " cod_republica=" . $var2 . " and ";
                    $cond .= "cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $var;
                    $lista =  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 17);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }//fin select codigos presupuestarios

    public function denociudad2($var = null)
    {
        $this->layout = "ajax";
        $rep =  $this->Session->read('SScodpresi');
        $est = $this->Session->read('est2');

        $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $var;
        //  echo $cond;
        $d =  $this->cugd01_municipios->findAll($cond);
        if($d != null) {
            $this->set('ciudad2', $d[0]['cugd01_municipios']['conocido']);
        } else {
            $this->set('ciudad2', '');
        }


    }







    public function infomacion_faltante($var1 = null, $var2 = null)
    {

        $this->layout = "ajax";

        $var3 = "";

        switch($var1) {
            case "deporte":{        $this->set('userTable', $this->requestAction('/cnmp06_deportes2/', array('return')));  }break;
            case "religion":{       $this->set('userTable', $this->requestAction('/cnmp06_religiones2/', array('return')));  }break;
            case "hobby":{          $this->set('userTable', $this->requestAction('/cnmp06_hobby2/', array('return')));  }break;
            case "color":{          $this->set('userTable', $this->requestAction('/cnmp06_colores2/', array('return')));  }break;
            case "club":{           $this->set('userTable', $this->requestAction('/cnmp06_clubes2/', array('return')));  }break;
            case "profesion":{      $this->set('userTable', $this->requestAction('/cnmp06_profesiones2/', array('return')));  }break;
            case "especialidad":{   $this->set('userTable', $this->requestAction('/cnmp06_especialidades/', array('return')));  }break;
            case "oficio":{         $this->set('userTable', $this->requestAction('/cnmp06_oficio2/', array('return')));  }break;
        }//fin

        $this->set('opcion', $var1);
        $this->set('capa', $var2);
        $this->set('controlador', $var3);

    }//fin function








    public function select_cambio($var1 = null, $var2 = null, $var3 = null)
    {
        $this->layout = "ajax";
        switch($var1) {
            case "deporte":{
                $listadeporte =  $this->cnmd06_deportes->generateList(null, 'cod_deporte ASC', null, '{n}.cnmd06_deportes.cod_deporte', '{n}.cnmd06_deportes.denominacion');
                $this->concatena($listadeporte, 'lista');
                $this->set("name", "deporte");
            }break;

            case "religion":{
                $listareligion =  $this->cnmd06_religiones->generateList(null, 'cod_religion ASC', null, '{n}.cnmd06_religiones.cod_religion', '{n}.cnmd06_religiones.denominacion');
                $this->concatena($listareligion, 'lista');
                $this->set("name", "religion");
            }break;

            case "hobby":{
                $listahobby =  $this->cnmd06_hobby->generateList(null, 'cod_hobby ASC', null, '{n}.cnmd06_hobby.cod_hobby', '{n}.cnmd06_hobby.denominacion');
                $this->concatena($listahobby, 'lista');
                $this->set("name", "hobby");
            }break;
            case "color":{
                $listacolor =  $this->cnmd06_colores->generateList(null, 'cod_color ASC', null, '{n}.cnmd06_colores.cod_color', '{n}.cnmd06_colores.denominacion');
                $this->concatena($listacolor, 'lista');
                $this->set("name", "color");
            }break;
            case "club":{
                $listaclubes =  $this->cnmd06_clubes->generateList(null, 'cod_club ASC', null, '{n}.cnmd06_clubes.cod_club', '{n}.cnmd06_clubes.denominacion');
                $this->concatena($listaclubes, 'lista');
                $this->set("name", "club");
            }break;

            case "profesion":{
                $listaprofesion = $this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
                $this->concatena_tres_digitos("", 'lista');
                $this->set("name", "cod_profesion");
            }break;

            case "oficio":{
                $listaoficio = $this->cnmd06_oficio->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion');
                $this->concatena_tres_digitos("", 'lista');
                $this->set("name", "oficio");
            }break;

            case "especialidad":{
                $this->set('SELECT', 'especialidad');
                $this->set('codigo', 'especialidad');
                $this->set('seleccion', '');
                $this->set('n', 7);
                $this->set('no', 'no');
                $var = $this->Session->read('pro');
                $cond = " cod_profesion=" . $var;
                $listaespecialidades = $this->cnmd06_especialidades->generateList($cond, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
                $this->concatena_tres_digitos($listaespecialidades, 'vector');
                $this->render("select4");

            }break;
        }//fin
        $this->set('opcion', $var1);
    }//fin function




    public function buscar_pista($var1 = null, $var2 = null)
    {
        $this->layout = "ajax";



        switch($var1) {
            case "deporte":{
                $listadeporte =  $this->cnmd06_deportes->generateList("upper(denominacion) LIKE upper('%$var2%')", 'cod_deporte ASC', null, '{n}.cnmd06_deportes.cod_deporte', '{n}.cnmd06_deportes.denominacion');
                $this->concatena($listadeporte, 'lista');
                $this->set("name", "deporte");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;

            case "religion":{
                $listareligion =  $this->cnmd06_religiones->generateList("upper(denominacion) LIKE upper('%$var2%')", 'cod_religion ASC', null, '{n}.cnmd06_religiones.cod_religion', '{n}.cnmd06_religiones.denominacion');
                $this->concatena($listareligion, 'lista');
                $this->set("name", "religion");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;

            case "hobby":{
                $listahobby =  $this->cnmd06_hobby->generateList("upper(denominacion) LIKE upper('%$var2%')", 'cod_hobby ASC', null, '{n}.cnmd06_hobby.cod_hobby', '{n}.cnmd06_hobby.denominacion');
                $this->concatena($listahobby, 'lista');
                $this->set("name", "hobby");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;
            case "color":{
                $listacolor =  $this->cnmd06_colores->generateList("upper(denominacion) LIKE upper('%$var2%')", 'cod_color ASC', null, '{n}.cnmd06_colores.cod_color', '{n}.cnmd06_colores.denominacion');
                $this->concatena($listacolor, 'lista');
                $this->set("name", "color");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;
            case "club":{
                $listaclubes =  $this->cnmd06_clubes->generateList("upper(denominacion) LIKE upper('%$var2%')", 'cod_club ASC', null, '{n}.cnmd06_clubes.cod_club', '{n}.cnmd06_clubes.denominacion');
                $this->concatena($listaclubes, 'lista');
                $this->set("name", "club");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;

            case "profesion":{
                $listaprofesion = $this->cnmd06_profesiones->generateList("upper(denominacion) LIKE upper('%$var2%')", 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
                $this->concatena_tres_digitos($listaprofesion, 'lista');
                $this->set("name", "cod_profesion");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;

            case "oficio":{
                $listaoficio = $this->cnmd06_oficio->generateList("upper(denominacion) LIKE upper('%$var2%')", 'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion');
                $this->concatena_tres_digitos($listaoficio, 'lista');
                $this->set("name", "oficio");
                $this->set('opcion', $var1);
                $this->render("select_cambio");
            }break;

            case "especialidad":{
                $this->set('SELECT', 'especialidad');
                $this->set('codigo', 'especialidad');
                $this->set('seleccion', '');
                $this->set('n', 7);
                $this->set('no', 'no');
                $var = $this->Session->read('pro');
                $cond = " cod_profesion=" . $var . " and upper(denominacion) LIKE upper('%$var2%')";
                $listaespecialidades = 0;

                if(!empty($var)) {
                    $listaespecialidades = $this->cnmd06_especialidades->generateList($cond, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
                    $this->concatena_tres_digitos($listaespecialidades, 'vector');
                }//fin if

                if($listaespecialidades == 0) {
                    $this->set('opcion', "especialidad_ninguna");
                    $this->render("select_cambio");
                } else {
                    $this->set('opcion', $var1);
                    $this->render("select4");
                }//fin else

            }break;
        }//fin


    }//fin function







    public function concatena_tres_digitos($vector1 = null, $nomVar = null, $extra = null)
    {
        $cod = array();
        if($vector1 != null) {
            foreach($vector1 as $x => $y) {
                if($extra != null) {
                    if($x < 99 && $x > 9) {
                        $cod[$x] = $extra . '0' . $x . ' - ' . $y;
                    } elseif($x <= 9) {
                        $cod[$x] = $extra . '00' . $x . ' - ' . $y;
                    } else {
                        $cod[$x] = $extra . '' . $x . ' - ' . $y;
                    }

                } else {
                    if($x < 99 && $x > 9) {
                        $cod[$x] = '0' . $x . ' - ' . $y;
                    } elseif($x <= 9) {
                        $cod[$x] = '00' . $x . ' - ' . $y;
                    } else {
                        $cod[$x] = '' . $x . ' - ' . $y;
                    }
                }
            }
            //print_r($cod);
        }

        $this->set($nomVar, $cod);
    }//fin function







    public function guardar()
    {
        $this->layout = "ajax";
        if(!empty($this->data)) {
            $cedula_identidad = $this->data['cnmp06_datos_personales']['cedula'];
            $nacionalidad = $this->data['cnmp06_datos_personales']['nacionalidad'];
            $primer_apellido = $this->data['cnmp06_datos_personales']['papellido'];
            $segundo_apellido = $this->data['cnmp06_datos_personales']['sapellido'];
            $primer_nombre = $this->data['cnmp06_datos_personales']['pnombre'];
            $segundo_nombre = $this->data['cnmp06_datos_personales']['snombre'];
            $f = $this->data['cnmp06_datos_personales']['fecha_nacimiento'];
            $fecha_nacimiento = $f[6] . $f[7] . $f[8] . $f[9] . "-" . $f[3] . $f[4] . "-" . $f[0] . $f[1];

            $sexo = $this->data['cnmp06_datos_personales']['sexo'];
            $estado_civil = $this->data['cnmp06_datos_personales']['estado_civil'];
            $grupo_sanguineo = $this->data['cnmp06_datos_personales']['grupo_sanguineo'];
            $peso_kilos = $this->data['cnmp06_datos_personales']['peso'];
            $estatura_metros = $this->data['cnmp06_datos_personales']['estatura'];
            $fe = $this->data['cnmp06_datos_personales']['fecha_naturalizacion'];
            $fe = $fe == "" ? "1900-01-01" : $fe;
            $fecha_naturalizacion = $fe != "1900-01-01" ? $fe[6] . $fe[7] . $fe[8] . $fe[9] . "-" . $fe[3] . $fe[4] . "-" . $fe[0] . $fe[1] : $fe;
            $numero_gaceta = $this->data['cnmp06_datos_personales']['gaceta'];
            $numero_gaceta = $numero_gaceta == "" ? 0 : $numero_gaceta;
            $c1 = !isset($this->data["cnmp06_datos_personales"]["c1"]) ? 0 : 1;
            $c2 = !isset($this->data["cnmp06_datos_personales"]["c2"]) ? 0 : 1;
            $c3 = !isset($this->data["cnmp06_datos_personales"]["c3"]) ? 0 : 1;
            $c4 = !isset($this->data["cnmp06_datos_personales"]["c4"]) ? 0 : 1;
            $c5 = !isset($this->data["cnmp06_datos_personales"]["c5"]) ? 0 : 1;
            $c6 = !isset($this->data["cnmp06_datos_personales"]["c6"]) ? 0 : 1;

            $idioma = $c1 . $c2 . $c3 . $c4 . $c5 . $c6;

            $cod_profesion = $this->data['cnmp06_datos_personales']['cod_profesion'];
            $cod_especialidad = $this->data['cnmp06_datos_personales']['cod_especialidad'];
            $cod_oficio = empty($this->data["cnmp06_datos_personales"]["oficio"]) ? 1 : $this->data['cnmp06_datos_personales']['oficio'];
            $direccion_habitacion = $this->data['cnmp06_datos_personales']['direccion_habitacion'];
            $telefonos_habitacion = $this->data['cnmp06_datos_personales']['telefonos'];
            $otra_direccion = $this->data['cnmp06_datos_personales']['otra_direccion'];
            $otra_direccion = $otra_direccion == "" ? 0 : $otra_direccion;
            $otros_telefonos = $this->data['cnmp06_datos_personales']['otros_telefonos'];
            $otros_telefonos = $otros_telefonos == "" ? 0 : $otros_telefonos;


            $numero_inscripcion_sso = $this->data['cnmp06_datos_personales']['numero_sso'];
            $numero_inscripcion_lph = $this->data['cnmp06_datos_personales']['numero_lph'];
            $grado_licencia_conducir = $this->data['cnmp06_datos_personales']['grado_licencia'];
            $numero_licencia_conducir = $this->data['cnmp06_datos_personales']['numero_licencia'];
            $correo_electronico = $this->data['cnmp06_datos_personales']['email'];
            if($correo_electronico == "") {
                $correo_electronico = 0;
            }

            if($numero_inscripcion_sso == "") {
                $numero_inscripcion_sso = 0;
            }
            if($numero_inscripcion_lph == "") {
                $numero_inscripcion_lph = 0;
            }
            if($grado_licencia_conducir == "") {
                $grado_licencia_conducir = 0;
            }
            if($numero_licencia_conducir == "") {
                $numero_licencia_conducir = 0;
            }
            $naturalizado = "";
            if(isset($this->data['cnmp06_datos_personales']['naturalizado'])) {
                $naturalizado = $this->data['cnmp06_datos_personales']['naturalizado'];
            }



            $usa_lentes = $this->data['cnmp06_datos_personales']['lentes'];
            $talla_camisa_blusa = $this->data['cnmp06_datos_personales']['talla_camisa'];
            $talla_pantalon_falda = $this->data['cnmp06_datos_personales']['talla_pantalon'];
            $talla_calzado = $this->data['cnmp06_datos_personales']['talla_calzado'];
            $talla_keppy = $this->data['cnmp06_datos_personales']['talla_keepy'];
            $talla_keppy = $talla_keppy == "" ? 0 : $talla_keppy;//0412-5454256hugo sandolva
            $deporte_practica = $this->data['cnmp06_datos_personales']['deporte'];
            $deporte_practica = $deporte_practica == "" ? 0 : $deporte_practica;
            $religion_pertenece = $this->data['cnmp06_datos_personales']['religion'];
            $religion_pertenece = $religion_pertenece == "" ? 0 : $religion_pertenece;
            $club_pertenece = $this->data['cnmp06_datos_personales']['club'];
            $club_pertenece = $club_pertenece == "" ? 0 : $club_pertenece;
            $hobby_favorito = $this->data['cnmp06_datos_personales']['hobby'];
            $hobby_favorito = $hobby_favorito == "" ? 0 : $hobby_favorito;
            $color_favorito = $this->data['cnmp06_datos_personales']['color'];
            $color_favorito = $color_favorito == "" ? 0 : $color_favorito;
            $cod_pais_origen = $this->data['cnmp06_datos_personales']['cod_republica'];
            $cod_estado_origen = $this->data['cnmp06_datos_personales']['cod_estado'];
            $cod_municipio_origen = $this->data['cnmp06_datos_personales']['cod_municipio'];
            $cod_parroquia_origen = $this->data['cnmp06_datos_personales']['cod_parroquia'];
            $cod_centropoblado_origen = $this->data['cnmp06_datos_personales']['cod_centro'];
            $cod_estado_habitacion = $this->data['cnmp06_datos_personales']['cod_esta'];
            $cod_municipio_habitacion = $this->data['cnmp06_datos_personales']['cod_munici'];
            $cod_parroquia_habitacion = $this->data['cnmp06_datos_personales']['cod_parro'];
            $cod_centropoblado_habitacion = $this->data['cnmp06_datos_personales']['cod_cen'];
            $aa[1] = $this->verifica_SS(1);
            $aa[2] = $this->verifica_SS(2);
            $aa[3] = $this->verifica_SS(3);
            $aa[4] = $this->verifica_SS(4);
            $aa[5] = $this->verifica_SS(5);
            $cero = 0;
        }//fin if


        $SQL_INSERT = "INSERT INTO cnmd06_datos_personales (cedula_identidad,nacionalidad,primer_apellido,segundo_apellido,	primer_nombre,segundo_nombre,fecha_nacimiento,
						sexo,estado_civil,grupo_sanguineo,peso_kilos,estatura_metros,naturalizado,fecha_naturalizacion,numero_gaceta,
						idioma,cod_profesion,cod_especialidad,cod_oficio,direccion_habitacion,telefonos_habitacion,otra_direccion_hab,
						otros_telefonos,correo_electronico,numero_inscripcion_sso,numero_inscripcion_lph,grado_licencia_conducir,numero_licencia_conducir,
						usa_lentes,talla_camisa_blusa,talla_pantalon_falda,talla_calzado,talla_keppy,deporte_practica,religion_pertenece,
						club_pertenece,hobby_favorito,color_favorito,cod_pais_origen,cod_estado_origen,cod_municipio_origen,cod_parroquia_origen,
						cod_centropoblado_origen,cod_estado_habitacion,cod_municipio_habitacion,cod_parroquia_habitacion,cod_centropoblado_habitacion,condicion_actual)";
        $SQL_INSERT .= " VALUES (
					'" . $cedula_identidad . "',
					'" . $nacionalidad . "',
					'" . $primer_apellido . "',
					'" . $segundo_apellido . "',
					'" . $primer_nombre . "',
					'" . $segundo_nombre . "',
					'" . $fecha_nacimiento . "',
					'" . $sexo . "',
					'" . $estado_civil . "',
					'" . $grupo_sanguineo . "',
					'" . $peso_kilos . "',
					'" . $estatura_metros . "',
					'" . $naturalizado . "',
					'" . $fecha_naturalizacion . "',
					'" . $numero_gaceta . "',
					'" . $idioma . "',
					'" . $cod_profesion . "',
					'" . $cod_especialidad . "',
					'" . $cod_oficio . "',
					'" . $direccion_habitacion . "',
					'" . $telefonos_habitacion . "',
					'" . $otra_direccion . "',
					'" . $otros_telefonos . "',
					'" . $correo_electronico . "',
					'" . $numero_inscripcion_sso . "',
					'" . $numero_inscripcion_lph . "',
					'" . $grado_licencia_conducir . "',
					'" . $numero_licencia_conducir . "',
					'" . $usa_lentes . "',
					'" . $talla_camisa_blusa . "',
					'" . $talla_pantalon_falda . "',
					'" . $talla_calzado . "',
					'" . $talla_keppy . "',
					'" . $deporte_practica . "',
					'" . $religion_pertenece . "',
					'" . $club_pertenece . "',
					'" . $hobby_favorito . "',
					'" . $color_favorito . "',
					'" . $cod_pais_origen . "',
					'" . $cod_estado_origen . "',
					'" . $cod_municipio_origen . "',
					'" . $cod_parroquia_origen . "',
					'" . $cod_centropoblado_origen . "',
					'" . $cod_estado_habitacion . "',
					'" . $cod_municipio_habitacion . "',
					'" . $cod_parroquia_habitacion . "',
					'" . $cod_centropoblado_habitacion . "',
					'" . $cero .
                "')";

        $CC = $this->cnmd06_datos_personales->execute($SQL_INSERT);



        if($CC > 1) {
            $this->set('Message_existe', 'Registro creado con exito.');
            $this->Session->write('cedula_pestana_expediente', $cedula_identidad);
            $this->index();
            //$this->render("index");
        } else {
            $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
            $this->index($cedula_identidad);
            //$this->render("index");
        }



    }//fin function



    public function guardar_modificar($pagina = null)
    {
        $this->layout = "ajax";
        if(!empty($this->data)) {
            $cedula_identidad = $this->data['cnmp06_datos_personales']['cedula'];
            $cedula_old = $this->data['cnmp06_datos_personales']['cedula_old'];
            $nacionalidad = $this->data['cnmp06_datos_personales']['nacionalidad'];
            $primer_apellido = $this->data['cnmp06_datos_personales']['papellido'];
            $segundo_apellido = $this->data['cnmp06_datos_personales']['sapellido'];
            $primer_nombre = $this->data['cnmp06_datos_personales']['pnombre'];
            $segundo_nombre = $this->data['cnmp06_datos_personales']['snombre'];
            $f = $this->data['cnmp06_datos_personales']['fecha_nacimiento'];
            $fecha_nacimiento = $f[6] . $f[7] . $f[8] . $f[9] . "-" . $f[3] . $f[4] . "-" . $f[0] . $f[1];


            $sexo = $this->data['cnmp06_datos_personales']['sexo'];
            $estado_civil = $this->data['cnmp06_datos_personales']['estado_civil'];
            $grupo_sanguineo = $this->data['cnmp06_datos_personales']['grupo_sanguineo'];
            $peso_kilos = $this->data['cnmp06_datos_personales']['peso'];
            $estatura_metros = $this->data['cnmp06_datos_personales']['estatura'];
            $naturalizado = $this->data['cnmp06_datos_personales']['naturalizado'];
            $fe = $this->data['cnmp06_datos_personales']['fecha_naturalizacion'];
            $fe = $fe == "" ? "01/01/1900" : $fe;
            $fecha_naturalizacion = $fe[6] . $fe[7] . $fe[8] . $fe[9] . "-" . $fe[3] . $fe[4] . "-" . $fe[0] . $fe[1];

            $numero_gaceta = $this->data['cnmp06_datos_personales']['gaceta'];

            $c1 = !isset($this->data["cnmp06_datos_personales"]["c1"]) ? 0 : 1;
            $c2 = !isset($this->data["cnmp06_datos_personales"]["c2"]) ? 0 : 1;
            $c3 = !isset($this->data["cnmp06_datos_personales"]["c3"]) ? 0 : 1;
            $c4 = !isset($this->data["cnmp06_datos_personales"]["c4"]) ? 0 : 1;
            $c5 = !isset($this->data["cnmp06_datos_personales"]["c5"]) ? 0 : 1;
            $c6 = !isset($this->data["cnmp06_datos_personales"]["c6"]) ? 0 : 1;

            $idioma = $c1 . $c2 . $c3 . $c4 . $c5 . $c6;
            $cod_profesion = $this->data['cnmp06_datos_personales']['cod_profesion'];
            $cod_especialidad = $this->data['cnmp06_datos_personales']['cod_especialidad'];
            $cod_oficio = empty($this->data["cnmp06_datos_personales"]["oficio"]) ? 1 : $this->data['cnmp06_datos_personales']['oficio'];
            $direccion_habitacion = $this->data['cnmp06_datos_personales']['direccion_habitacion'];
            $telefonos_habitacion = $this->data['cnmp06_datos_personales']['telefonos'];
            $otra_direccion = $this->data['cnmp06_datos_personales']['otra_direccion'];
            $otros_telefonos = $this->data['cnmp06_datos_personales']['otros_telefonos'];
            $numero_inscripcion_sso = $this->data['cnmp06_datos_personales']['numero_sso'];
            $numero_inscripcion_lph = $this->data['cnmp06_datos_personales']['numero_lph'];
            $grado_licencia_conducir = $this->data['cnmp06_datos_personales']['grado_licencia'];
            $numero_licencia_conducir = $this->data['cnmp06_datos_personales']['numero_licencia'];

            $correo_electronico = $this->data['cnmp06_datos_personales']['email'];
            if($correo_electronico == "") {
                $correo_electronico = 0;
            }

            if($numero_inscripcion_sso == "") {
                $numero_inscripcion_sso = 0;
            }
            if($numero_inscripcion_lph == "") {
                $numero_inscripcion_lph = 0;
            }
            if($grado_licencia_conducir == "") {
                $grado_licencia_conducir = 0;
            }
            if($numero_licencia_conducir == "") {
                $numero_licencia_conducir = 0;
            }
            $naturalizado = "";
            if(isset($this->data['cnmp06_datos_personales']['naturalizado'])) {
                $naturalizado = $this->data['cnmp06_datos_personales']['naturalizado'];
            }


            $usa_lentes = $this->data['cnmp06_datos_personales']['lentes'];
            $talla_camisa_blusa = $this->data['cnmp06_datos_personales']['talla_camisa'];
            $talla_pantalon_falda = $this->data['cnmp06_datos_personales']['talla_pantalon'];
            $talla_calzado = $this->data['cnmp06_datos_personales']['talla_calzado'];
            $talla_keppy = $this->data['cnmp06_datos_personales']['talla_keepy'];
            $deporte_practica = $this->data['cnmp06_datos_personales']['deporte'];
            $religion_pertenece = $this->data['cnmp06_datos_personales']['religion'];
            $club_pertenece = $this->data['cnmp06_datos_personales']['club'];
            $hobby_favorito = $this->data['cnmp06_datos_personales']['hobby'];
            $color_favorito = $this->data['cnmp06_datos_personales']['color'];
            $cod_pais_origen = $this->data['cnmp06_datos_personales']['cod_republica'];
            $cod_estado_origen = $this->data['cnmp06_datos_personales']['cod_estado'];
            $cod_municipio_origen = $this->data['cnmp06_datos_personales']['cod_municipio'];
            $cod_parroquia_origen = $this->data['cnmp06_datos_personales']['cod_parroquia'];
            $cod_centropoblado_origen = $this->data['cnmp06_datos_personales']['cod_centro'];
            $cod_estado_habitacion = $this->data['cnmp06_datos_personales']['cod_esta'];
            $cod_municipio_habitacion = $this->data['cnmp06_datos_personales']['cod_munici'];
            $cod_parroquia_habitacion = $this->data['cnmp06_datos_personales']['cod_parro'];
            $cod_centropoblado_habitacion = $this->data['cnmp06_datos_personales']['cod_cen'];
            $carnet = $this->data['cnmp06_datos_personales']['carnet'];
            if(isset($this->data['cnmp06_datos_personales']['estado_laboral'])) {
                $estado_laboral = $this->data['cnmp06_datos_personales']['estado_laboral'];
            } else {
                $estado_laboral = 0;
            };
            $justificacion = $this->data['cnmp06_datos_personales']['justificacion'];
            $cero = 0;

            $sql = "update cnmd06_datos_personales set cedula_identidad=$cedula_identidad, nacionalidad='" . $nacionalidad . "',primer_apellido='" . $primer_apellido . "',segundo_apellido='" . $segundo_apellido . "',primer_nombre='" . $primer_nombre . "',segundo_nombre='" . $segundo_nombre . "',fecha_nacimiento='" . $fecha_nacimiento . "',
  sexo='" . $sexo . "',estado_civil='" . $estado_civil . "',grupo_sanguineo='" . $grupo_sanguineo . "',peso_kilos=" . $peso_kilos . ",estatura_metros=" . $estatura_metros . ",naturalizado='" . $naturalizado . "',fecha_naturalizacion='" . $fecha_naturalizacion . "',numero_gaceta='" . $numero_gaceta . "',
  idioma=$idioma,cod_profesion=$cod_profesion,cod_especialidad=$cod_especialidad,cod_oficio=$cod_oficio,direccion_habitacion='" . $direccion_habitacion . "',telefonos_habitacion='" . $telefonos_habitacion . "',otra_direccion_hab='" . $otra_direccion . "',
  otros_telefonos='" . $otros_telefonos . "',correo_electronico='" . $correo_electronico . "',numero_inscripcion_sso='" . $numero_inscripcion_sso . "',numero_inscripcion_lph='" . $numero_inscripcion_lph . "',grado_licencia_conducir=$grado_licencia_conducir,numero_licencia_conducir=$numero_licencia_conducir,
  usa_lentes='" . $usa_lentes . "',talla_camisa_blusa='" . $talla_camisa_blusa . "',talla_pantalon_falda='" . $talla_pantalon_falda . "',talla_calzado='" . $talla_calzado . "',talla_keppy='" . $talla_keppy . "',deporte_practica=$deporte_practica,religion_pertenece=$religion_pertenece,
  club_pertenece=" . $club_pertenece . ",hobby_favorito=$hobby_favorito,color_favorito=" . $color_favorito . ",cod_pais_origen=$cod_pais_origen,cod_estado_origen=$cod_estado_origen,cod_municipio_origen=$cod_municipio_origen,cod_parroquia_origen=$cod_parroquia_origen,
  cod_centropoblado_origen=$cod_centropoblado_origen,cod_estado_habitacion=$cod_estado_habitacion,cod_municipio_habitacion=$cod_municipio_habitacion,cod_parroquia_habitacion=$cod_parroquia_habitacion,cod_centropoblado_habitacion=$cod_centropoblado_habitacion,
  carnet='" . $carnet . "', estado_laboral='" . $estado_laboral . "', justificacion='" . $justificacion . "' where cedula_identidad=" . $cedula_old;
            $sql_upda = "update cnmd06_fichas set cedula_identidad=$cedula_identidad where cedula_identidad=" . $cedula_old;

            $vvv = $this->cnmd06_datos_personales->execute($sql);
            $this->data = null;

            if($vvv > 1) {


                $def_up = $this->cnmd06_datos_personales->execute($sql_upda);

                $this->set('Message_existe', 'Registro Modificado con exito.');
                $this->Session->write('cedula_pestana_expediente', $cedula_identidad);
                //$this->index();
                if(!isset($_SESSION["pag_num_datos_personales"])) {
                    $this->consulta();
                    $this->render("consulta");
                } else {
                    $this->consulta($_SESSION["pag_num_datos_personales"]);
                    $this->render("consulta");
                }//fin else

            } else {
                $this->set('errorMessage', 'Disculpe, El Registro no fue Modificado.');
                //$this->index($cedula_identidad);

                if(!isset($_SESSION["pag_num_datos_personales"])) {
                    $this->consulta();
                    $this->render("consulta");
                } else {
                    $this->consulta($_SESSION["pag_num_datos_personales"]);
                    $this->render("consulta");
                }//fin else

            }//fin else





        }
    }


    public function bt_nav($Tfilas, $pagina)
    {
        if($Tfilas == 1) {
            $this->set('mostrarS', false);
            $this->set('mostrarA', false);
        } elseif($Tfilas == 2) {
            if($pagina == 2) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            }
        } elseif($Tfilas >= 3) {
            if($pagina == $Tfilas) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } elseif($pagina == 1) {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', true);
            }
        }
    }//fin navegacion


    public function consulta($pagina = null)
    {
        $this->layout = "ajax";
        if($pagina != null) {
            $pagina = $pagina;
            $Tfilas = $this->cnmd06_datos_personales->findCount();
            if($Tfilas == 0) {
                $this->index();
                //$this->render("index");
            } elseif($pagina > $Tfilas) {
                $pagina = $pagina - 2;
            }

            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datacpcp01 = $this->cnmd06_datos_personales->findAll(null, null, 'cedula_identidad ASC', 1, $pagina, null);

                $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"] . "'");
                if($vec != 0) {
                    $this->set('existe_imagen', true);
                } else {
                    $this->set('existe_imagen', false);
                }
                $this->Session->write('cedula_pestana_expediente', $datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"]);
                $pais = $this->cugd01_republica->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' ");
                $this->set('pais', $pais);
                $estados = $this->cugd01_estados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"]);
                $this->set('estados', $estados);
                $municipios = $this->cugd01_municipios->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"]);
                $this->set('municipios', $municipios);
                $parroquia = $this->cugd01_parroquias->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"]);
                $this->set('parroquia', $parroquia);
                $centros = $this->cugd01_centropoblados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_origen"]);
                $this->set('centros', $centros);


                $estados = $this->cugd01_estados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"]);
                $this->set('estados_actual', $estados);
                $municipios = $this->cugd01_municipios->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"]);
                $this->set('municipios_actual', $municipios);
                $parroquia = $this->cugd01_parroquias->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"]);
                $this->set('parroquia_actual', $parroquia);
                $centros = $this->cugd01_centropoblados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_habitacion"]);
                $this->set('centros_actual', $centros);

                $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
                $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

                $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
                $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);



                $deporte = $this->cnmd06_deportes->findAll();
                $this->set('deporte', $deporte);
                $religion = $this->cnmd06_religiones->findAll();
                $this->set('religion', $religion);
                $hobby = $this->cnmd06_hobby->findAll();
                $this->set('hobby', $hobby);

                $profesion = $this->cnmd06_profesiones->findAll();
                $this->set('profesion', $profesion);
                $especialidad = $this->cnmd06_especialidades->findAll("cod_profesion=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_profesion"]);
                $this->set('especialidad', $especialidad);


                $oficio = $this->cnmd06_oficio->findAll();
                $this->set('oficio', $oficio);
                $this->set('DATOS', $datacpcp01);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina = 1;
            $Tfilas = $this->cnmd06_datos_personales->findCount();
            if($Tfilas == 0) {
                $this->set('errorMessage', 'No se encontrarón datos personales');
                $this->index();
                $this->render("index");
            } else {
                if($Tfilas != 0) {
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $datacpcp01 = $this->cnmd06_datos_personales->findAll(null, null, 'cedula_identidad ASC', 1, $pagina, null);
                    $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"] . "'");
                    if($vec != 0) {
                        $this->set('existe_imagen', true);
                    } else {
                        $this->set('existe_imagen', false);
                    }
                    $this->Session->write('cedula_pestana_expediente', $datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"]);
                    $pais = $this->cugd01_republica->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  ");
                    $this->set('pais', $pais);
                    $estados = $this->cugd01_estados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"]);
                    $this->set('estados', $estados);
                    $municipios = $this->cugd01_municipios->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"]);
                    $this->set('municipios', $municipios);
                    $parroquia = $this->cugd01_parroquias->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"]);
                    $this->set('parroquia', $parroquia);
                    $centros = $this->cugd01_centropoblados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'  and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_origen"]);
                    $this->set('centros', $centros);

                    $estados = $this->cugd01_estados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"]);
                    $this->set('estados_actual', $estados);
                    $municipios = $this->cugd01_municipios->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"]);
                    $this->set('municipios_actual', $municipios);
                    $parroquia = $this->cugd01_parroquias->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"]);
                    $this->set('parroquia_actual', $parroquia);
                    $centros = $this->cugd01_centropoblados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_habitacion"]);
                    $this->set('centros_actual', $centros);

                    $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
                    $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

                    $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
                    $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);
                    $deporte = $this->cnmd06_deportes->findAll();
                    $this->set('deporte', $deporte);
                    $religion = $this->cnmd06_religiones->findAll();
                    $this->set('religion', $religion);
                    $hobby = $this->cnmd06_hobby->findAll();
                    $this->set('hobby', $hobby);

                    $profesion = $this->cnmd06_profesiones->findAll();
                    $this->set('profesion', $profesion);
                    $especialidad = $this->cnmd06_especialidades->findAll("cod_profesion=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_profesion"]);
                    $this->set('especialidad', $especialidad);
                    $oficio = $this->cnmd06_oficio->findAll();
                    $this->set('oficio', $oficio);
                    $this->set('DATOS', $datacpcp01);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                }
            }
        }//fin else




        if($Tfilas != 0) {


            $datos = $this->cnmd06_datos_personales->execute("

												SELECT


														  a.cedula_identidad,
														  a.primer_apellido,
														  a.segundo_apellido,
														  a.primer_nombre,
														  a.segundo_nombre,


														  b.cod_presi,
														  b.cod_entidad,
														  b.cod_tipo_inst,
														  b.cod_inst,
														  b.cod_dep,
														  (SELECT x.denominacion  FROM arrd05 x WHERE
																  x.cod_presi           =     b.cod_presi       and
																  x.cod_entidad         =     b.cod_entidad     and
																  x.cod_tipo_inst       =     b.cod_tipo_inst   and
																  x.cod_inst            =     b.cod_inst        and
																  x.cod_dep             =     b.cod_dep
														  ) as denominacion_dependencia,
														  b.cod_tipo_nomina,
														  (SELECT x.denominacion  FROM cnmd01 x WHERE
																  x.cod_presi           =     b.cod_presi       and
																  x.cod_entidad         =     b.cod_entidad     and
																  x.cod_tipo_inst       =     b.cod_tipo_inst   and
																  x.cod_inst            =     b.cod_inst        and
																  x.cod_dep             =     b.cod_dep         and
																  x.cod_tipo_nomina     =     b.cod_tipo_nomina
														  ) as denominacion_nomina,
														  b.cod_cargo,
														  b.cod_ficha,
														  b.fecha_ingreso,
														  b.forma_pago,
														  b.cod_entidad_bancaria,
														  b.cod_sucursal,
														  b.cuenta_bancaria,
														  b.condicion_actividad,
														  b.funciones_realizar,
														  b.responsabilidad_administrativa,
														  b.horas_laborar,
														  b.porcentaje_jub_pension,
														  b.fecha_terminacion_contrato,
														  b.fecha_retiro,
														  b.motivo_retiro,
														  b.paso,
														  b.tipo_contrato,
														  b.situacion,
														  b.nivel,
														  b.categoria,


														  c.cod_puesto,
														  (select devolver_denominacion_puesto(
												               (select xy.clasificacion_personal from cnmd01 xy where
												                  xy.cod_presi           =     c.cod_presi       and
																  xy.cod_entidad         =     c.cod_entidad     and
																  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
																  xy.cod_inst            =     c.cod_inst        and
																  xy.cod_dep             =     c.cod_dep         and
																  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
												               ), c.cod_puesto )
												          ) as demonimacion_puesto,
												          (select devolver_grado_puesto(
												               (select xy.clasificacion_personal from cnmd01 xy where
												                  xy.cod_presi           =     c.cod_presi       and
																  xy.cod_entidad         =     c.cod_entidad     and
																  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
																  xy.cod_inst            =     c.cod_inst        and
																  xy.cod_dep             =     c.cod_dep         and
																  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
												               ), c.cod_puesto )
												          ) as grado_puesto,
														  c.sueldo_basico,
														  c.compensaciones,
														  c.primas,
														  c.bonos,
														  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
														  c.cod_dir_superior,
														  c.cod_coordinacion,
														  c.cod_secretaria,
														  c.cod_direccion,
														  c.cod_division,
														  c.cod_departamento,
														  c.cod_oficina,
															  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
															  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
															  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
															  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
															  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
															  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
															  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
														  c.cod_estado,
														  c.cod_municipio,
														  c.cod_parroquia,
														  c.cod_centro,
													          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
															  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
															  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
															  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
															  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
														  c.condicion_actividad,
														  c.ano,
														  c.cod_sector,
														  c.cod_programa,
														  c.cod_sub_prog,
														  c.cod_proyecto,
														  c.cod_activ_obra,
														  c.cod_partida,
														  c.cod_generica,
														  c.cod_especifica,
														  c.cod_sub_espec,
														  c.cod_auxiliar,
														  c.cod_nivel_i,
														  c.cod_nivel_ii,
														  c.cod_ficha



												FROM


														 cnmd06_datos_personales         a,
														 cnmd06_fichas                   b,
														 cnmd05                          c


												WHERE
									                    a.cedula_identidad = '" . $datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"] . "'          and
									                    b.cedula_identidad = a.cedula_identidad and
									                    b.condicion_actividad  = 1              and
									                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
														b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
														b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
														b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
									                    c.cod_presi        = b.cod_presi        and
														c.cod_entidad      = b.cod_entidad      and
														c.cod_tipo_inst    = b.cod_tipo_inst    and
														c.cod_inst         = b.cod_inst         and
														c.cod_dep          = b.cod_dep          and
														c.cod_tipo_nomina  = b.cod_tipo_nomina  and
														c.cod_cargo        = b.cod_cargo


									  LIMIT 1 OFFSET 0 * 1

									;");

            $totalPages_Recordset1 =  count($datos);

            if($totalPages_Recordset1 == 0) {

                echo"<script>
									      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                              document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
									          </script>";
            } else {


                if(isset($datos[0][0]["cod_presi"])) {
                    $cod_presi_exp          =  $datos[0][0]["cod_presi"];
                    $cod_entidad_exp        =  $datos[0][0]["cod_entidad"];
                    $cod_tipo_inst_exp      =  $datos[0][0]["cod_tipo_inst"];
                    $cod_inst_exp           =  $datos[0][0]["cod_inst"];
                    $cod_dep_exp            =  $datos[0][0]["cod_dep"];
                    $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
                    $cod_cargo              =  $datos[0][0]["cod_cargo"];
                    $cod_ficha              =  $datos[0][0]["cod_ficha"];
                } else {

                    $cod_tipo_nomina        =  0;
                    $cod_cargo              =  0;
                    $cod_ficha              =  0;

                }//fin else

                $this->Session->write('cod_dep_expediente', $cod_dep_exp);
                $this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
                $this->Session->write('cod_cargo_expediente', $cod_cargo);
                $this->Session->write('cod_ficha_expediente', $cod_ficha);

                echo"<script>
									      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
									            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
                              document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
									          </script>";

            }//fin else


            $_SESSION["pag_num_datos_personales"] = $pagina;


        }//fin else











    }//fin function consultar2


    public function modificar($cedula = null, $pagina = null)
    {

        $this->layout = "ajax";
        $pagina = $pagina;



        $datacpcp01 = $this->cnmd06_datos_personales->findAll(null, null, 'cedula_identidad ASC', 1, $pagina, null);
        $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $cedula . "'");
        if($vec != 0) {
            $this->set('existe_imagen', true);
        } else {
            $this->set('existe_imagen', false);
        }
        $cond = "cedula_identidad='" . $cedula . "'";
        $data = $this->cnmd06_datos_personales->findAll($cond);
        $i = 0;
        foreach($data as $row) {

            $var[$i]['cedula_identidad'] = $row['cnmd06_datos_personales']['cedula_identidad'];
            $var[$i]['nacionalidad'] = $row['cnmd06_datos_personales']['nacionalidad'];
            $var[$i]['primer_apellido'] = $row['cnmd06_datos_personales']['primer_apellido'];
            $var[$i]['segundo_apellido'] = $row['cnmd06_datos_personales']['segundo_apellido'];
            $var[$i]['primer_nombre'] = $row['cnmd06_datos_personales']['primer_nombre'];
            $var[$i]['segundo_nombre'] = $row['cnmd06_datos_personales']['segundo_nombre'];
            $var[$i]['fecha_nacimiento'] = $row['cnmd06_datos_personales']['fecha_nacimiento'];
            $var[$i]['sexo'] = $row['cnmd06_datos_personales']['sexo'];
            $var[$i]['estado_civil'] = $row['cnmd06_datos_personales']['estado_civil'];
            $var[$i]['grupo_sanguineo'] = $row['cnmd06_datos_personales']['grupo_sanguineo'];
            $var[$i]['peso_kilos'] = $row['cnmd06_datos_personales']['peso_kilos'];
            $var[$i]['estatura_metros'] = $row['cnmd06_datos_personales']['estatura_metros'];
            $var[$i]['naturalizado'] = $row['cnmd06_datos_personales']['naturalizado'];
            $var[$i]['fecha_naturalizacion'] = $row['cnmd06_datos_personales']['fecha_naturalizacion'];
            $var[$i]['numero_gaceta'] = $row['cnmd06_datos_personales']['numero_gaceta'];
            $var[$i]['idioma'] = $row['cnmd06_datos_personales']['idioma'];
            $var[$i]['cod_profesion'] = $row['cnmd06_datos_personales']['cod_profesion'];
            $var[$i]['cod_especialidad'] = $row['cnmd06_datos_personales']['cod_especialidad'];
            $var[$i]['cod_oficio'] = $row['cnmd06_datos_personales']['cod_oficio'];
            $var[$i]['direccion_habitacion'] = $row['cnmd06_datos_personales']['direccion_habitacion'];
            $var[$i]['telefonos_habitacion'] = $row['cnmd06_datos_personales']['telefonos_habitacion'];
            $var[$i]['otra_direccion_hab'] = $row['cnmd06_datos_personales']['otra_direccion_hab'];
            $var[$i]['otros_telefonos'] = $row['cnmd06_datos_personales']['otros_telefonos'];
            $var[$i]['correo_electronico'] = $row['cnmd06_datos_personales']['correo_electronico'];
            $var[$i]['numero_inscripcion_sso'] = $row['cnmd06_datos_personales']['numero_inscripcion_sso'];
            $var[$i]['numero_inscripcion_lph'] = $row['cnmd06_datos_personales']['numero_inscripcion_lph'];
            $var[$i]['grado_licencia_conducir'] = $row['cnmd06_datos_personales']['grado_licencia_conducir'];
            $var[$i]['numero_licencia_conducir'] = $row['cnmd06_datos_personales']['numero_licencia_conducir'];
            $var[$i]['usa_lentes'] = $row['cnmd06_datos_personales']['usa_lentes'];
            $var[$i]['talla_camisa_blusa'] = $row['cnmd06_datos_personales']['talla_camisa_blusa'];
            $var[$i]['talla_pantalon_falda'] = $row['cnmd06_datos_personales']['talla_pantalon_falda'];
            $var[$i]['talla_calzado'] = $row['cnmd06_datos_personales']['talla_calzado'];
            $var[$i]['talla_keppy'] = $row['cnmd06_datos_personales']['talla_keppy'];
            $var[$i]['deporte_practica'] = $row['cnmd06_datos_personales']['deporte_practica'];
            $var[$i]['religion_pertenece'] = $row['cnmd06_datos_personales']['religion_pertenece'];
            $var[$i]['club_pertenece'] = $row['cnmd06_datos_personales']['club_pertenece'];
            $var[$i]['hobby_favorito'] = $row['cnmd06_datos_personales']['hobby_favorito'];
            $var[$i]['color_favorito'] = $row['cnmd06_datos_personales']['color_favorito'];
            $var[$i]['cod_pais_origen'] = $row['cnmd06_datos_personales']['cod_pais_origen'];
            $var[$i]['cod_estado_origen'] = $row['cnmd06_datos_personales']['cod_estado_origen'];
            $this->Session->write('est', $var[$i]['cod_estado_origen']);
            $var[$i]['cod_municipio_origen'] = $row['cnmd06_datos_personales']['cod_municipio_origen'];
            $var[$i]['cod_parroquia_origen'] = $row['cnmd06_datos_personales']['cod_parroquia_origen'];
            $var[$i]['cod_centropoblado_origen'] = $row['cnmd06_datos_personales']['cod_centropoblado_origen'];
            $var[$i]['cod_estado_habitacion'] = $row['cnmd06_datos_personales']['cod_estado_habitacion'];
            $this->Session->write('est2', $var[$i]['cod_estado_habitacion']);
            $var[$i]['cod_municipio_habitacion'] = $row['cnmd06_datos_personales']['cod_municipio_habitacion'];
            $var[$i]['cod_parroquia_habitacion'] = $row['cnmd06_datos_personales']['cod_parroquia_habitacion'];
            $var[$i]['cod_centropoblado_habitacion'] = $row['cnmd06_datos_personales']['cod_centropoblado_habitacion'];
        }
        $pais = $this->cugd01_republica->findAll();
        $this->set('pais', $pais);


        $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
        $this->concatena($listarepublica, 'cod_republica');

        $c1 = " cod_republica=" . $var[$i]['cod_pais_origen'];
        $listaestadao = $this->cugd01_estados->generateList($c1, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($listaestadao, 'cod_estado');
        $estados = $this->cugd01_estados->findAll($c1);
        $this->set('estados', $estados);




        $c2 = " cod_republica=" . $var[$i]['cod_pais_origen'] . " and cod_estado=" . $var[$i]['cod_estado_origen'];
        $listamunicio = $this->cugd01_municipios->generateList($c2, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
        $this->concatena($listamunicio, 'cod_municipio');
        $municipios = $this->cugd01_municipios->findAll($c2);
        $this->set('municipios', $municipios);




        $c3 = " cod_republica=" . $var[$i]['cod_pais_origen'] . " and cod_estado=" . $var[$i]['cod_estado_origen'] . " and cod_municipio=" . $var[$i]['cod_municipio_origen'];
        $listaparroquia = $this->cugd01_parroquias->generateList($c3, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
        $this->concatena($listaparroquia, 'cod_parroquia');
        $parroquia = $this->cugd01_parroquias->findAll($c3);
        $this->set('parroquia', $parroquia);




        $c4 = " cod_republica=" . $var[$i]['cod_pais_origen'] . " and cod_estado=" . $var[$i]['cod_estado_origen'] . " and cod_municipio=" . $var[$i]['cod_municipio_origen'] . "and cod_parroquia=" . $var[$i]['cod_parroquia_origen'];
        $listacentro = $this->cugd01_centropoblados->generateList($c4, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
        $this->concatena($listacentro, 'cod_centro');
        $centros = $this->cugd01_centropoblados->findAll($c4);
        $this->set('centros', $centros);


        $listaprofesion = $this->cnmd06_profesiones->generateList(" cod_profesion=" . $var[$i]['cod_profesion'], 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
        $this->concatena_tres_digitos($listaprofesion, 'cod_profesion');


        $c1 = " cod_profesion=" . $var[$i]['cod_profesion'];
        $listaespecialidad = $this->cnmd06_especialidades->generateList($c1, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
        $this->concatena_tres_digitos($listaespecialidad, 'cod_especilidad');


        $listaoficio = $this->cnmd06_oficio->generateList(" cod_oficio=" . $var[$i]['cod_oficio'], 'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion');
        $this->concatena_tres_digitos($listaoficio, 'oficio');




        $listaestadao2 = $this->cugd01_estados->generateList("cod_republica=1", 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($listaestadao2, 'cod_estado2');
        $c22 = " cod_estado=" . $var[$i]['cod_estado_habitacion'];
        $listamunicio2 = $this->cugd01_municipios->generateList("cod_republica=1 and " . $c22, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');

        $this->concatena($listamunicio2, 'cod_municipio2');
        $c33 = " cod_estado=" . $var[$i]['cod_estado_habitacion'] . " and cod_municipio=" . $var[$i]['cod_municipio_habitacion'];
        $listaparroquia2 = $this->cugd01_parroquias->generateList("cod_republica=1 and " . $c33, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');

        $this->concatena($listaparroquia2, 'cod_parroquia2');
        $c44 = " cod_estado=" . $var[$i]['cod_estado_habitacion'] . " and cod_municipio=" . $var[$i]['cod_municipio_habitacion'] . "and cod_parroquia=" . $var[$i]['cod_parroquia_habitacion'];

        $listacentro2 = $this->cugd01_centropoblados->generateList("cod_republica=1 and " . $c44, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
        $this->concatena($listacentro2, 'cod_centro2');


        $listadeporte = $this->cnmd06_deportes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_deportes.cod_deporte', '{n}.cnmd06_deportes.denominacion');
        $this->concatena_tres_digitos($listadeporte, 'deporte');

        $listareligion = $this->cnmd06_religiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_religiones.cod_religion', '{n}.cnmd06_religiones.denominacion');
        $this->concatena($listareligion, 'religion');

        $listahobby = $this->cnmd06_hobby->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_hobby.cod_hobby', '{n}.cnmd06_hobby.denominacion');
        $this->concatena_tres_digitos($listahobby, 'hobby');

        $listacolor =  $this->cnmd06_colores->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_colores.cod_color', '{n}.cnmd06_colores.denominacion');
        $this->concatena($listacolor, 'color');

        $listaclubes =  $this->cnmd06_clubes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_clubes.cod_club', '{n}.cnmd06_clubes.denominacion');
        $this->concatena_tres_digitos($listaclubes, 'club');

        $colores = $this->cnmd06_colores->findAll("cod_color=" . $var[$i]["color_favorito"]);
        $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);







        $this->set('DATOS', $data);
        $this->set('pagina', $pagina);
    }

    public function eliminar($cedula, $pagina)
    {
        $this->layout = "ajax";
        if(isset($cedula)) {
            $cond = " cedula_identidad=" . $cedula;

            $datos = $this->cnmd06_datos_personales->findAll($cond);


            if($datos[0]["cnmd06_datos_personales"]["condicion_actual"] != 1) {


                $vec = $this->cnmd06_fichas->findCount($cond);
                if($vec == 0) {

                    $this->cnmd06_datos_personales->execute("DELETE FROM cnmd06_datos_personales  WHERE " . $cond);
                    $this->set('Message_existe', 'El registro fue eliminado');

                    if(!isset($_SESSION["pag_num_datos_personales"])) {
                        $this->consulta();
                        $this->render("consulta");
                    } else {
                        $this->consulta($_SESSION["pag_num_datos_personales"] + 1);
                        $this->render("consulta");
                    }//fin else
                } else {
                    $this->set('errorMessage', 'El registro no puede ser eliminado - se encuentra contratado en una nómina');
                    $this->consulta();
                    $this->render("consulta");
                }
            } else {

                $this->set('errorMessage', 'El registro no puede ser eliminado - la condición actual es Ocupado');
                $this->consulta();
                $this->render("consulta");

            }//fin else


        }//fin if
    }//fin function


    public function preconsulta()
    {
        $this->layout = "ajax";
        $opciones = array('cedula_identidad' => 'CEDULA', 'primer_apellido' => 'APELLIDO', 'primer_nombre' => 'NOMBRE');
        $this->set('opcion', $opciones);

        echo"<script>
				          	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
					          </script>";

    }

    public function query($var = null)
    {
        $this->layout = "ajax";
        $this->set('tipo', $var);
    }




    public function buscar_vista_1($var1 = null)
    {

        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 2);

    }//fin function



    public function llenar_pista_opcion($var1 = null)
    {

        $this->layout = "ajax";
        $this->Session->write('pista_opcion', $var1);

    }//fin fucntion








    public function buscar_por_pista($var1 = null, $var2 = null, $var3 = null)
    {
        $this->layout = "ajax";
        $sql_like = "";

        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');


        if($var3 == null) {
            $this->Session->write('pista', $var2);
            $var2 = strtoupper($var2);
            $var_like = $var2;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
            $Tfilas = $this->datos_personales_super_busqueda->findCount($sql_like);
            if($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int)ceil($Tfilas / 300);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->datos_personales_super_busqueda->findAll($sql_like, "cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido", "primer_nombre,primer_apellido ASC", 300, 1, null);
                $sql = "";
                foreach($datos_filas as $ve) {
                    /**/
                    if($sql == "") {
                        $sql .= "    a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "'  ";
                    }/**/
                    //$sql_in[]="'".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'";
                }//fin foreach
                $sql = "(" . $sql . ")";
                //$sql = " a.cedula_identidad in (".implode(',',$sql_in).")";

                $dato_a =   $this->datos_personales_super_busqueda->execute("
                                    SELECT
                                          a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.cod_tipo_nomina,
										  a.cod_cargo,
										  a.cod_ficha,
										  a.cedula_identidad,
										  a.condicion_actividad
								     FROM
								           cnmd06_fichas a,
								           cnmd05        b
								     WHERE
								     	  a.cod_presi         =  '" . $cod_presi . "'      and
										  a.cod_entidad       =  '" . $cod_entidad . "'    and
										  a.cod_tipo_inst     =  '" . $cod_tipo_inst . "'  and
										  a.cod_inst          =  '" . $cod_inst . "'       and
								          b.cod_presi         =  a.cod_presi           and
										  b.cod_entidad       =  a.cod_entidad         and
										  b.cod_tipo_inst     =  a.cod_tipo_inst       and
										  b.cod_inst          =  a.cod_inst            and
										  b.cod_dep           =  a.cod_dep             and
										  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
										  b.cod_cargo         =  a.cod_cargo           and " . $sql . "  ORDER BY cedula_identidad, cod_dep, cod_tipo_nomina  ASC; ");


                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
            $this->set("dato_a", $dato_a);

        } else {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            $var_like = $var22;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));

            $Tfilas = $this->datos_personales_super_busqueda->findCount($sql_like);
            if($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int)ceil($Tfilas / 300);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->datos_personales_super_busqueda->findAll($sql_like, "cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido", "primer_nombre,primer_apellido ASC", 300, $pagina, null);
                $sql = "";
                foreach($datos_filas as $ve) {
                    /**/
                    if($sql == "") {
                        $sql .= "    a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "'  ";
                    }/**/
                    //$sql_in[]="'".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'";
                }//fin foreach
                $sql = "(" . $sql . ")";
                //$sql = " a.cedula_identidad in (".implode(',',$sql_in).")";

                $dato_a =   $this->datos_personales_super_busqueda->execute("
                                    SELECT
                                          a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.cod_tipo_nomina,
										  a.cod_cargo,
										  a.cod_ficha,
										  a.cedula_identidad,
										  a.condicion_actividad
								     FROM
								           cnmd06_fichas a,
								           cnmd05        b
								     WHERE
								     	  a.cod_presi         =  '" . $cod_presi . "'      and
										  a.cod_entidad       =  '" . $cod_entidad . "'    and
										  a.cod_tipo_inst     =  '" . $cod_tipo_inst . "'  and
										  a.cod_inst          =  '" . $cod_inst . "'       and
								          b.cod_presi         =  a.cod_presi           and
										  b.cod_entidad       =  a.cod_entidad         and
										  b.cod_tipo_inst     =  a.cod_tipo_inst       and
										  b.cod_inst          =  a.cod_inst            and
										  b.cod_dep           =  a.cod_dep             and
										  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
										  b.cod_cargo         =  a.cod_cargo           and  " . $sql . "   ORDER BY cedula_identidad, cod_dep, cod_tipo_nomina ASC;");


                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
            $this->set("dato_a", $dato_a);
        }//fin else



        $this->set("opcion", $var1);
    }//fin function




























    public function funcion($var1 = null, $var2 = null, $var3 = null)
    {

        $this->layout = "ajax";


    }//fin function






    public function datos($tipo = null, $pista = null)
    {
        $this->layout = "ajax";
        //echo $tipo;
        if($tipo != null && $pista != null) {
            if($tipo == 'cedula_identidad') {
                $datos = $this->cnmd06_datos_personales->findAll(" $tipo::text LIKE '$pista%'", $fields = 'cedula_identidad, primer_apellido, primer_nombre', $order = 'cedula_identidad ASC', $limit = 1000, $page = null, $recursive = null);
                $this->set('datos', $datos);
            } else {
                $datos = $this->cnmd06_datos_personales->findAll(" $tipo LIKE '$pista%'", $fields = 'cedula_identidad, primer_apellido, primer_nombre', $order = 'cedula_identidad ASC', $limit = 1000, $page = null, $recursive = null);
                $this->set('datos', $datos);
            }
        }

    }

    public function buscar_cedula()
    {
        $this->layout = "ajax";


    }


    public function lista_encontrados($pagina, $cc_cxargo, $cc_cxficha)
    {
        $this->layout = "ajax";
        $cond = "cedula_identidad=" . $pagina;
        $num = $this->cnmd06_datos_personales->findCount($cond);

        if($num == 1) {
            $this->Session->write('cedula_pestana_expediente', $pagina);
            $datacpcp01 = $this->cnmd06_datos_personales->findAll('cedula_identidad=' . $pagina);
            $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $pagina . "'");

            if($vec != 0) {
                $this->set('existe_imagen', true);
            } else {
                $this->set('existe_imagen', false);
            }

            $pais = $this->cugd01_republica->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'");

            $this->set('pais', $pais);
            $estados = $this->cugd01_estados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"]);
            $this->set('estados', $estados);
            $municipios = $this->cugd01_municipios->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"]);
            $this->set('municipios', $municipios);
            $parroquia = $this->cugd01_parroquias->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"]);
            $this->set('parroquia', $parroquia);
            $centros = $this->cugd01_centropoblados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_origen"]);
            $this->set('centros', $centros);

            $estados = $this->cugd01_estados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"]);
            $this->set('estados_actual', $estados);
            $municipios = $this->cugd01_municipios->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"]);
            $this->set('municipios_actual', $municipios);
            $parroquia = $this->cugd01_parroquias->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"]);
            $this->set('parroquia_actual', $parroquia);
            $centros = $this->cugd01_centropoblados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_habitacion"]);
            $this->set('centros_actual', $centros);

            $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
            $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

            $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
            $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);

            $deporte = $this->cnmd06_deportes->findAll();
            $this->set('deporte', $deporte);
            $religion = $this->cnmd06_religiones->findAll();
            $this->set('religion', $religion);
            $hobby = $this->cnmd06_hobby->findAll();
            $this->set('hobby', $hobby);

            $profesion = $this->cnmd06_profesiones->findAll();
            $this->set('profesion', $profesion);
            $especialidad = $this->cnmd06_especialidades->findAll("cod_profesion=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_profesion"]);
            $this->set('especialidad', $especialidad);
            $oficio = $this->cnmd06_oficio->findAll();
            $this->set('oficio', $oficio);
            $this->set('DATOS', $datacpcp01);

            $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
            //print_r($colores);
            $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

            $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
            //print_r($club);
            $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);


            $datos = $this->cnmd06_datos_personales->execute("

								SELECT


										  a.cedula_identidad,
										  a.primer_apellido,
										  a.segundo_apellido,
										  a.primer_nombre,
										  a.segundo_nombre,


										  b.cod_presi,
										  b.cod_entidad,
										  b.cod_tipo_inst,
										  b.cod_inst,
										  b.cod_dep,
										  (SELECT x.denominacion  FROM arrd05 x WHERE
												  x.cod_presi           =     b.cod_presi       and
												  x.cod_entidad         =     b.cod_entidad     and
												  x.cod_tipo_inst       =     b.cod_tipo_inst   and
												  x.cod_inst            =     b.cod_inst        and
												  x.cod_dep             =     b.cod_dep
										  ) as denominacion_dependencia,
										  b.cod_tipo_nomina,
										  (SELECT x.denominacion  FROM cnmd01 x WHERE
												  x.cod_presi           =     b.cod_presi       and
												  x.cod_entidad         =     b.cod_entidad     and
												  x.cod_tipo_inst       =     b.cod_tipo_inst   and
												  x.cod_inst            =     b.cod_inst        and
												  x.cod_dep             =     b.cod_dep         and
												  x.cod_tipo_nomina     =     b.cod_tipo_nomina
										  ) as denominacion_nomina,
										  b.cod_cargo,
										  b.cod_ficha,
										  b.fecha_ingreso,
										  b.forma_pago,
										  b.cod_entidad_bancaria,
										  b.cod_sucursal,
										  b.cuenta_bancaria,
										  b.condicion_actividad,
										  b.funciones_realizar,
										  b.responsabilidad_administrativa,
										  b.horas_laborar,
										  b.porcentaje_jub_pension,
										  b.fecha_terminacion_contrato,
										  b.fecha_retiro,
										  b.motivo_retiro,
										  b.paso,
										  b.tipo_contrato,
										  b.situacion,
										  b.nivel,
										  b.categoria,


										  c.cod_puesto,
										  (select devolver_denominacion_puesto(
								               (select xy.clasificacion_personal from cnmd01 xy where
								                  xy.cod_presi           =     c.cod_presi       and
												  xy.cod_entidad         =     c.cod_entidad     and
												  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
												  xy.cod_inst            =     c.cod_inst        and
												  xy.cod_dep             =     c.cod_dep         and
												  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
								               ), c.cod_puesto )
								          ) as demonimacion_puesto,
								          (select devolver_grado_puesto(
								               (select xy.clasificacion_personal from cnmd01 xy where
								                  xy.cod_presi           =     c.cod_presi       and
												  xy.cod_entidad         =     c.cod_entidad     and
												  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
												  xy.cod_inst            =     c.cod_inst        and
												  xy.cod_dep             =     c.cod_dep         and
												  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
								               ), c.cod_puesto )
								          ) as grado_puesto,
										  c.sueldo_basico,
										  c.compensaciones,
										  c.primas,
										  c.bonos,
										  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
										  c.cod_dir_superior,
										  c.cod_coordinacion,
										  c.cod_secretaria,
										  c.cod_direccion,
										  c.cod_division,
										  c.cod_departamento,
										  c.cod_oficina,
											  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
											  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
											  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
											  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
											  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
											  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
											  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
										  c.cod_estado,
										  c.cod_municipio,
										  c.cod_parroquia,
										  c.cod_centro,
									          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
											  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
											  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
											  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
											  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
										  c.condicion_actividad,
										  c.ano,
										  c.cod_sector,
										  c.cod_programa,
										  c.cod_sub_prog,
										  c.cod_proyecto,
										  c.cod_activ_obra,
										  c.cod_partida,
										  c.cod_generica,
										  c.cod_especifica,
										  c.cod_sub_espec,
										  c.cod_auxiliar,
										  c.cod_nivel_i,
										  c.cod_nivel_ii,
										  c.cod_ficha as cod_ficha_dos



								FROM


										 cnmd06_datos_personales         a,
										 cnmd06_fichas                   b,
										 cnmd05                          c


								WHERE
					                    a.cedula_identidad = '" . $pagina . "'          and
					                    b.cedula_identidad = a.cedula_identidad and
					                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
										b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
										b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
										b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
										b.cod_cargo        = '" . $cc_cxargo . "'   and
										b.cod_ficha        = '" . $cc_cxficha . "'  and
					                    c.cod_presi        = b.cod_presi        and
										c.cod_entidad      = b.cod_entidad      and
										c.cod_tipo_inst    = b.cod_tipo_inst    and
										c.cod_inst         = b.cod_inst         and
										c.cod_dep          = b.cod_dep          and
										c.cod_tipo_nomina  = b.cod_tipo_nomina  and
										c.cod_cargo        = b.cod_cargo


					  LIMIT 1 OFFSET 0 * 1

					;");
            // b.condicion_actividad  = 1              and

            $totalPages_Recordset1 =  count($datos);

            if($totalPages_Recordset1 == 0) {

                echo"<script>
					      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
					          </script>";
            } else {


                if(isset($datos[0][0]["cod_presi"])) {
                    $cod_presi_exp          =  $datos[0][0]["cod_presi"];
                    $cod_entidad_exp        =  $datos[0][0]["cod_entidad"];
                    $cod_tipo_inst_exp      =  $datos[0][0]["cod_tipo_inst"];
                    $cod_inst_exp           =  $datos[0][0]["cod_inst"];
                    $cod_dep_exp            =  $datos[0][0]["cod_dep"];
                    $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
                    $cod_cargo              =  $datos[0][0]["cod_cargo"];
                    $cod_ficha              =  $datos[0][0]["cod_ficha"];
                } else {

                    $cod_tipo_nomina        =  0;
                    $cod_cargo              =  0;
                    $cod_ficha              =  0;

                }//fin else

                $this->Session->write('cod_dep_expediente', $cod_dep_exp);
                $this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
                $this->Session->write('cod_cargo_expediente', $cod_cargo);
                $this->Session->write('cod_ficha_expediente', $cod_ficha);
                $this->Session->write('cod_ficha_expediente', $cod_ficha);

                echo"<script>
					      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
					          </script>";

            }//fin else

        } else {//echo "no hay dato";

            echo"<script>
				          	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
					          </script>";
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
            $this->preconsulta();
            $this->render("preconsulta");

            // }
        }//fin function consultar2



    }

    public function subir_imagen($var = null)
    {
        //$this->layout="img";
        if(isset($var)) {

            if($this->data['cnmp06_datos_personales']['foto']['size'] < 2004654) {
                if($this->data['cnmp06_datos_personales']['foto']['type'] == "image/jpeg") {
                    $fileDataMini = fread(fopen($this->redimensionar($this->data['cnmp06_datos_personales']['foto']['tmp_name'], 110, 0), "r"), $this->data['cnmp06_datos_personales']['foto']['size']);
                    $this->data['cnmp06_datos_personales']['foto']['cedula'] = $this->data['cnmp06_datos_personales']['cedula'];
                    $this->data['cnmp06_datos_personales']['foto']['foto'] = base64_encode($fileDataMini);
                    $this->cnmd06_imagenes->save($this->data['cnmp06_datos_personales']['foto']);
                    echo "La imagen se a cargado exitosamente...";
                    echo '<script type="text/javascript">' .
                            'window.close();' .
                            '</script>';
                } else {
                    echo "Por Favor Cargue una imagen que sea de tipo JPG";
                    echo "<br>Tipo Imagen:" . $this->data['cnmp06_datos_personales']['foto']['type'];
                    echo "<br>Tamaño: " . $this->data['cnmp06_datos_personales']['foto']['size'];
                }
            } else {
                echo "Disculpe, El archivo excede del tamaño permitido.";
            }

        }
    }//finn subir_imagen
    public function subir_imagen_2($var = null)
    {
        //$this->layout="";
        if(isset($var)) {

            if($this->data['cnmp06_datos_personales']['foto']['size'] < 2004654) {
                if($this->data['cnmp06_datos_personales']['foto']['type'] == "image/jpeg") {
                    $fileDataMini = fread(fopen($this->redimensionar($this->data['cnmp06_datos_personales']['foto']['tmp_name'], 110, 0), "r"), $this->data['cnmp06_datos_personales']['foto']['size']);
                    $this->data['cnmp06_datos_personales']['foto']['cedula_identidad'] = $this->data['cnmp06_datos_personales']['cedula'];
                    $this->data['cnmp06_datos_personales']['foto']['foto'] = base64_encode($fileDataMini);
                    $this->cnmd06_imagenes->save($this->data['cnmp06_datos_personales']['foto']);
                    echo "La imagen se a cargado exitosamente...";
                    echo '<script type="text/javascript">' .
                            'window.close();' .
                            '</script>';
                } else {
                    echo "Por Favor Cargue una imagen que sea de tipo JPG";
                    echo "<br>Tipo Imagen:" . $this->data['cnmp06_datos_personales']['foto']['type'];
                    echo "<br>Tamaño: " . $this->data['cnmp06_datos_personales']['foto']['size'];
                }
            } else {
                echo "Disculpe, El archivo excede del tamaño permitido.";
            }

        }
    }//finn subir_imagen

    public function busca_foto($id)
    {
        $this->layout = "ajax";
        $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $id . "'");
        if($vec != 0) {
            $this->set('existe_imagen', true);
        } else {
            $this->set('existe_imagen', false);
        }


        $pagina = 1;
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas == 0) {

            $this->index($id);
            $this->render("index");
        } else {


            $datos = $this->cnmd06_datos_personales->execute("

			SELECT


					  a.cedula_identidad,
					  a.primer_apellido,
					  a.segundo_apellido,
					  a.primer_nombre,
					  a.segundo_nombre,


					  b.cod_presi,
					  b.cod_entidad,
					  b.cod_tipo_inst,
					  b.cod_inst,
					  b.cod_dep,
					  (SELECT x.denominacion  FROM arrd05 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep
					  ) as denominacion_dependencia,
					  b.cod_tipo_nomina,
					  (SELECT x.denominacion  FROM cnmd01 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep         and
							  x.cod_tipo_nomina     =     b.cod_tipo_nomina
					  ) as denominacion_nomina,
					  b.cod_cargo,
					  b.cod_ficha,
					  b.fecha_ingreso,
					  b.forma_pago,
					  b.cod_entidad_bancaria,
					  b.cod_sucursal,
					  b.cuenta_bancaria,
					  b.condicion_actividad,
					  b.funciones_realizar,
					  b.responsabilidad_administrativa,
					  b.horas_laborar,
					  b.porcentaje_jub_pension,
					  b.fecha_terminacion_contrato,
					  b.fecha_retiro,
					  b.motivo_retiro,
					  b.paso,
					  b.tipo_contrato,
					  b.situacion,
					  b.nivel,
					  b.categoria,


					  c.cod_puesto,
					  (select devolver_denominacion_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as demonimacion_puesto,
			          (select devolver_grado_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as grado_puesto,
					  c.sueldo_basico,
					  c.compensaciones,
					  c.primas,
					  c.bonos,
					  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
					  c.cod_dir_superior,
					  c.cod_coordinacion,
					  c.cod_secretaria,
					  c.cod_direccion,
					  c.cod_division,
					  c.cod_departamento,
					  c.cod_oficina,
						  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro,
				          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
					  c.condicion_actividad,
					  c.ano,
					  c.cod_sector,
					  c.cod_programa,
					  c.cod_sub_prog,
					  c.cod_proyecto,
					  c.cod_activ_obra,
					  c.cod_partida,
					  c.cod_generica,
					  c.cod_especifica,
					  c.cod_sub_espec,
					  c.cod_auxiliar,
					  c.cod_nivel_i,
					  c.cod_nivel_ii,
					  c.cod_ficha



			FROM


					 cnmd06_datos_personales         a,
					 cnmd06_fichas                   b,
					 cnmd05                          c


			WHERE
                    a.cedula_identidad = '" . $id . "'          and
                    b.cedula_identidad = a.cedula_identidad and
                    b.condicion_actividad  = 1              and
                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
					b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
					b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
					b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
                    c.cod_presi        = b.cod_presi        and
					c.cod_entidad      = b.cod_entidad      and
					c.cod_tipo_inst    = b.cod_tipo_inst    and
					c.cod_inst         = b.cod_inst         and
					c.cod_dep          = b.cod_dep          and
					c.cod_tipo_nomina  = b.cod_tipo_nomina  and
					c.cod_cargo        = b.cod_cargo


  LIMIT 1 OFFSET 0 * 1

;");

            $totalPages_Recordset1 =  count($datos);

            if($totalPages_Recordset1 == 0) {

                echo"<script>
      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
          </script>";
            } else {


                if(isset($datos[0][0]["cod_presi"])) {
                    $cod_presi_exp          =  $datos[0][0]["cod_presi"];
                    $cod_entidad_exp        =  $datos[0][0]["cod_entidad"];
                    $cod_tipo_inst_exp      =  $datos[0][0]["cod_tipo_inst"];
                    $cod_inst_exp           =  $datos[0][0]["cod_inst"];
                    $cod_dep_exp            =  $datos[0][0]["cod_dep"];
                    $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
                    $cod_cargo              =  $datos[0][0]["cod_cargo"];
                    $cod_ficha              =  $datos[0][0]["cod_ficha"];
                } else {

                    $cod_tipo_nomina        =  0;
                    $cod_cargo              =  0;
                    $cod_ficha              =  0;

                }//fin else

                $this->Session->write('cod_dep_expediente', $cod_dep_exp);
                $this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
                $this->Session->write('cod_cargo_expediente', $cod_cargo);
                $this->Session->write('cod_ficha_expediente', $cod_ficha);

                echo"<script>
      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
          </script>";

            }//fin else

            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datacpcp01 = $this->cnmd06_datos_personales->findAll("cedula_identidad=" . $id, null, 'cedula_identidad ASC', 1, $pagina, null);
                /*$img=$this->cnmd06_imagenes->findCount("cedula=".$datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"]);
                if($img!=0){
                    $this->set("TieneFoto",true);
                }*/
                $this->Session->write('cedula_pestana_expediente', $id);
                $pais = $this->cugd01_republica->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'");
                $this->set('pais', $pais);
                $estados = $this->cugd01_estados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"]);
                $this->set('estados', $estados);
                $municipios = $this->cugd01_municipios->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"]);
                $this->set('municipios', $municipios);
                $parroquia = $this->cugd01_parroquias->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"]);
                $this->set('parroquia', $parroquia);
                $centros = $this->cugd01_centropoblados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_origen"]);
                $this->set('centros', $centros);

                $estados = $this->cugd01_estados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"]);
                $this->set('estados_actual', $estados);
                $municipios = $this->cugd01_municipios->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"]);
                $this->set('municipios_actual', $municipios);
                $parroquia = $this->cugd01_parroquias->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"]);
                $this->set('parroquia_actual', $parroquia);
                $centros = $this->cugd01_centropoblados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_habitacion"]);
                $this->set('centros_actual', $centros);

                $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
                $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

                $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
                $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);
                $deporte = $this->cnmd06_deportes->findAll();
                $this->set('deporte', $deporte);
                $religion = $this->cnmd06_religiones->findAll();
                $this->set('religion', $religion);
                $hobby = $this->cnmd06_hobby->findAll();
                $this->set('hobby', $hobby);

                $profesion = $this->cnmd06_profesiones->findAll();
                $this->set('profesion', $profesion);
                $especialidad = $this->cnmd06_especialidades->findAll("cod_profesion=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_profesion"]);
                $this->set('especialidad', $especialidad);
                $oficio = $this->cnmd06_oficio->findAll();
                $this->set('oficio', $oficio);
                $this->set('DATOS', $datacpcp01);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);



                $this->render("consulta_pestanas");

            }//fin else
        }//fin else

    }//fin if


    public function ver_foto($id)
    {
        $this->layout = "img";
        $vec = $this->cnmd06_imagenes->findByCedula($id);
        $dataimg = $vec["cumd06_imagenes"]["foto"];
        $this->set('img', $dataimg);
        //$this->render();
    }
    public function viewid($id)
    {
        $this->layout = "img";
        $vec = $this->cnmd06_imagenes->findByCedula($id);
        $dataimg = $vec["cnmd06_imagenes"]["foto"];
        $this->set('img', $dataimg);
    }






    public function cargar_foto_a()
    {

        $this->layout = "ajax";


    }//fin function





    public function cargar_foto_b()
    {

        $this->layout = "img";

        if(isset($this->data["cnmp06_datos_personales"]["buscar_a"])) {
            $dataimg = $this->data["cnmp06_datos_personales"]["buscar_a"];
            $this->set('img', $dataimg);
        } else {
            $this->set('img', "");
        }//fin else



    }//fin function






    /**
     * Funcion para redimensionar las imagenes.
     *
     */
    public function redimensionar($imagen, $largo, $mostrar = 1)
    {
        // $imagen	Ruta de la Imagen a Redimensioanr
        // $largo	Largo de la Redimension
        // $mostrar	1 Muestra la Imagen en el Nevegador
        // $mostrar	0 Guarda la Imagen

        // Si $mostrar es 0
        // Funcion devuelve ruta de la Imagen


        $anchura = $largo;
        // Altura Maxima de la Imagen
        $hmax = 400;
        $nombre = $imagen;
        $datos = getimagesize($nombre);



        if($datos[2] == 1) {
            $img = @imagecreatefromgif($nombre);
        }

        if($datos[2] == 2) {
            $img = @imagecreatefromjpeg($nombre);
        }

        if($datos[2] == 3) {
            $img = @imagecreatefrompng($nombre);
        }

        $ratio = ($datos[0] / $anchura);

        $altura = ($datos[1] / $ratio);

        if($altura > $hmax) {
            $anchura2 = $hmax * $anchura / $altura;
            $altura = $hmax;
            $anchura = $anchura2;
        }

        $thumb = imagecreatetruecolor($anchura, $altura);

        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);


        // Creamos la Imagen (un JPG)
        if ($mostrar == 0) {
            // Guardamos Imagen en Directorio
            imagejpeg($thumb, $imagen . '_copy', 100);
            imagedestroy($thumb);
            return ($imagen . '_copy');//devuelve ruta de la imagen creada

        } else {
            // Mostramos Imagen en el Navegador
            //header("Content-type: image/jpeg");
            return imagejpeg($thumb, '', 100);

        }
        imagedestroy($thumb);
    }//fin funcion redimensionar

}//fin de la clase controller
