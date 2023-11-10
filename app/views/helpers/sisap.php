<?php

class SisapHelper extends Helper {

    var $helpers = array('Html', 'Javascript', 'Ajax', "Sisap2");

    function imagen_ventana($options = array(), $tipo = null, $title_aux = null, $url = null, $width_aux = "330px", $height_aux = "400px", $resizable_aux = false, $maximizable_aux = false, $minimizable_aux = false, $closable_aux = false) {


        if (!isset($options['id'])) {
            $options['id'] = "input" . intval(rand());
        }
        if (!isset($options['size'])) {
            $options['size'] = '3';
        }
        if (!isset($options['onKeyPress'])) {
            $options['onKeyPress'] = '';
        }
        if (!isset($options['onChange'])) {
            $options['onChange'] = '';
        }
        if (!isset($options['onFocus'])) {
            $options['onFocus'] = '';
        }
        if (!isset($options['onBlur'])) {
            $options['onBlur'] = '';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '30';
        }
        if (!isset($options['value'])) {
            $options['value'] = '';
        }
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['type'])) {
            $options['type'] = 'text';
        }
        if (!isset($options['autocomplete'])) {
            $options['autocomplete'] = 'off';
        }


        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin



        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin

        if ($tipo == 1) {/// IMAGEN DE BUSCAR
            $options['class'] = "buscar_input2";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['value'] = "";
            $options['type'] = "button";
            $options['title'] = '';
            //$options['style']='display:none;';
        } else if ($tipo == 2) {/// TIPO BUTTON
            $htmlOptions['class'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = '';
        } else if ($tipo == 3) {/// TIPO BUTTON NORMAL
            $htmlOptions['class'] = "buscar_input";
            $options['value'] = "";
            $options ['class'] = "buscar_input";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = '';
        } else if ($tipo == 4) {/// TIPO BUTTON
            $htmlOptions['class'] = "ver_input";
            $options ['class'] = "ver_input";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = '';
        } else if ($tipo == 5) {/// TIPO BUTTON
            $htmlOptions['class'] = "add_input";
            $options ['class'] = "add_input";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = '';
        } else if ($tipo == 6) {/// TIPO BUTTON
            $htmlOptions['class'] = "subir_imagen";
            $options ['class'] = "subir_imagen";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = '';
        } else if ($tipo == 7) {/// TIPO BUTTON
            $htmlOptions['class'] = "mini_lupa";
            $options ['class'] = "mini_lupa";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = '';
        } else if ($tipo == 8) {/// TIPO BUTTON
            $htmlOptions['class'] = "cambiar_clave";
            $options ['class'] = "cambiar_clave";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = 'Cambiar clave usuario actual';
        } else if ($tipo == 9) {/// TIPO BUTTON
            $htmlOptions['class'] = "estatus_usuarios";
            $options ['class'] = "estatus_usuarios";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = 'Estatus de los usuarios';
        } else if ($tipo == 10) {/// TIPO BUTTON
            $htmlOptions['class'] = "pdf_ventana";
            $options ['class'] = "pdf_ventana";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = 'Pdf';
        } else if ($tipo == 11) {/// TIPO BUTTON PDF
            $htmlOptions['class'] = "generar_input";
            $options ['class'] = "generar_input";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = 'Pdf';
        } else if ($tipo == 12) {/// TIPO BUTTON PDF
            $htmlOptions['class'] = "open_window_input";
            $options ['class'] = "open_window_input";
            $options['value'] = "";
            $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
            $options['type'] = "button";
            $options['title'] = 'Abrir ventana';
        }//fin else



        echo '<input onClick="' . $onClick . '" value="' . $options['value'] . '" type="' . $options['type'] . '"  style="' . $options ['style'] . '"  ' . $options['disabled'] . '  size="' . $options ['size'] . '"  id="' . $options ['id'] . '"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '" title="' . $options['title'] . '"   ' . $options['readonly'] . ' />';
    }

//fin function

    function input_buscar($name = null, $options = array(), $tipo = null, $title_aux = null, $url = null, $width_aux = "330px", $height_aux = "400px", $resizable_aux = false, $maximizable_aux = false, $minimizable_aux = false, $closable_aux = false) {


        if (!isset($options['id'])) {
            $options['id'] = "input" . intval(rand());
        }
        if (!isset($options['size'])) {
            $options['size'] = '15';
        }
        if (!isset($options['onKeyPress'])) {
            $options['onKeyPress'] = '';
        }
        if (!isset($options['onChange'])) {
            $options['onChange'] = '';
        }
        if (!isset($options['onFocus'])) {
            $options['onFocus'] = '';
        }
        if (!isset($options['onBlur'])) {
            $options['onBlur'] = '';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '15';
        }
        if (!isset($options['value'])) {
            $options['value'] = '';
        }
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['type'])) {
            $options['type'] = 'text';
        }
        if (!isset($options['autocomplete'])) {
            $options['autocomplete'] = 'off';
        }


        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin



        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin

        $options['class'] = "input_lupa";
        $onClick = "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', '$closable_aux')";
        $options['type'] = "text";
        $options['title'] = '';
        $options['readonly'] = 'readonly';

        $xname = explode('/', $name);
        $modelo = isset($xname[0]) ? $xname[0] : '';
        $nombre = isset($xname[1]) ? $xname[1] : '';
        $nombre_input = "data[$modelo][$nombre]";


        echo '<input onClick="' . $onClick . '" name="' . $nombre_input . '" value="' . $options['value'] . '" type="' . $options['type'] . '"  style="' . $options ['style'] . '"  ' . $options['disabled'] . '  size="' . $options ['size'] . '"  id="' . $options ['id'] . '"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '" title="' . $options['title'] . '"   ' . $options['readonly'] . ' />';
    }

//fin function input_buscar

    function selectTagRemote($fieldName, $optionElements, $extra_opciones = array(), $selected = null, $selectAttr = array(), $optionAttr = null, $showEmpty = true, $return = false) {


        $this->Html->setFormTag($fieldName);
        Helper::return_helpers();
        $var_encargada = 0;
        $ya = 0;
        $script = "";


        if ($this->Html->tagIsInvalid($this->Html->model, $this->Html->field)) {
            if (isset($selectAttr['class']) && trim($selectAttr['class']) != "") {
                $selectAttr['class'] .= ' form_error';
            } else {
                $selectAttr['class'] = 'form_error';
            }
        }
        if (!isset($selectAttr['id'])) {
            $selectAttr['id'] = $this->Html->model . Inflector::camelize($this->Html->field) . intval(rand());
            $options['id'] = $selectAttr['id'];
            $options['type'] = 'change';
        } else {
            $options['id'] = $selectAttr['id'];
            $options['type'] = 'change';
        }
        if (!isset($selectAttr['loading'])) {
            $selectAttr['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $selectAttr['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin
        if (!isset($selectAttr['complete'])) {
            $selectAttr['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $selectAttr['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!empty($selectAttr['funcion'])) {
            $options['funcion'] = $selectAttr['funcion'];
        } else {
            $options['funcion'] = '';
        }//fin if ="";


        $options['confirm'] = "";


        if (isset($selectAttr['readonly'])) {
            $selectAttr['onFocus'] = 'blur();';
        }

        if (!isset($selectAttr['onChange'])) {
            if (isset($selectAttr['onchange'])) {
                $selectAttr['onChange'] = $selectAttr['onchange'] == "vacio" ? "vacio" : $selectAttr['onchange'];
            } else {
                $selectAttr['onChange'] = "";
            }

            if ($selectAttr['onChange'] == "vacio") {
                $selectAttr['onChange'] = " " . "  ";
            } else {
                $selectAttr['onChange'] .= "      mascara_select(this.form,document.getElementById('" . $options['id'] . "').selectedIndex , '" . $options['id'] . "'); ";
            }
            //$selectAttr['onChange'] .= "      mascara_select(this.form,document.getElementById('".$options['id']."').selectedIndex , '".$options['id']."'); ";
        } else if ($selectAttr['onChange'] == "vacio" || (isset($selectAttr['onchange']) && $selectAttr['onchange'] == "vacio")) {
            $selectAttr['onChange'] = "";
            $selectAttr['onchange'] = "";
        } else if ($selectAttr['onChange'] != " mascara_select(this.form,this.form." . $options['id'] . ".selectedIndex , '" . $options['id'] . "');") {
            $selectAttr['onChange'] = " " . "  mascara_select(this.form,document.getElementById('" . $options['id'] . "').selectedIndex , '" . $options['id'] . "'); ";
        }//fin else
        if ($var_encargada == 0) {
            $selectAttr['onChange'] .= " link_javascript_visor('" . $selectAttr['id'] . "', '" . $this->Ajax->ajax_remote("change", $this->http_use) . "', '" . $options['funcion'] . "', '" . $this->http_use . "',  '" . $this->http_use_var . "', '" . $options['confirm'] . "' ";
        }
        if (isset($selectAttr['funcion'])) {
            $options['funcion'] = $selectAttr['funcion'];
        }
        for ($i = 1; $i <= 10; $i++) {
            if (isset($selectAttr['update' . $i])) {
                $htmlOptions['id'] = $selectAttr['id'];
                $options['update'] = $selectAttr['update' . $i];
                $options['url'] = $selectAttr['onchange' . $i];
                $es_slash = substr($options['url'], -1);
                $options['url'] = $es_slash == '/' ? substr($options['url'], 0, -1) : $options['url'];

                if ($var_encargada != 0) {
                    $script = $this->Javascript->event("'{$htmlOptions['id']}'", $this->Ajax->ajax_remote("change", $this->http_use), $this->Ajax->remoteFunction($options));
                } else {
                    $selectAttr['onChange'] .= ", '" . $options['url'] . "', '" . $options['update'] . "'  ";
                }//fin funtion
            }//FIN IF
        }//FIN FOR
        if ($var_encargada == 0) {
            $selectAttr['onChange'] .= ");  ";
        }
        if (isset($selectAttr['onchange'])) {
            $selectAttr['onchange'] = $selectAttr['onChange'];
        }

        $i = 0;
        if (!empty($extra_opciones)) {
            foreach ($extra_opciones as $name) {
                $i++;
                if (isset($extra_opciones['value' . $i]) && isset($extra_opciones['opcion' . $i])) {
                    $optionElements[$extra_opciones['value' . $i]] = $extra_opciones['opcion' . $i];
                }
            }
        }
        if (!is_array($optionElements) && $i != 0) {
            echo '<select></select>';
            return null;
        } else if (!is_array($optionElements)) {
            echo '<select></select>';
            return null;
        }
        if (!isset($selected)) {
            $selected = $this->Html->tagValue($fieldName);
        }
        if (isset($selectAttr) && array_key_exists("multiple", $selectAttr)) {
            $select[] = sprintf($this->Html->tags['selectmultiplestart'], $this->Html->model, $this->Html->field, $this->Html->parseHtmlOptions($selectAttr));
        } else {
            $select[] = sprintf($this->Html->tags['selectstart'], $this->Html->model, $this->Html->field, $this->Html->parseHtmlOptions($selectAttr));
        }
        if ($showEmpty == true) {
            $select[] = sprintf($this->Html->tags['selectempty'], $this->Html->parseHtmlOptions($optionAttr));
        }
        foreach ($optionElements as $name => $title) {
            $optionsHere = $optionAttr;
            if (($selected != null) && ($selected == $name)) {
                $optionsHere['selected'] = 'selected';
            } else if (is_array($selected) && in_array($name, $selected)) {
                $optionsHere['selected'] = 'selected';
            }
            $select[] = sprintf($this->Html->tags['selectoption'], trim($name), $this->Html->parseHtmlOptions($optionsHere), h($title));
        }
        $select[] = sprintf($this->Html->tags['selectend']);
        echo $this->output(implode("\n", $select), $return);



///////  IMPRIMIAR JAVASCRIPT
        echo $script;
////// FIN IMPRIMIAR JAVASCRIPT
    }

//fin function

    function submitTagRemote($title = 'Submit', $options = array(), $confirm = true) {
        Helper::return_helpers();
        $htmlOptions = $this->Ajax->__getHtmlOptions($options);
        $htmlOptions['value'] = $title;
        $options['type'] = 'click';
        $var_encargada = 0;
        $ya = 0;
        $script = "";
        $salir = "";

        if (!isset($htmlOptions['loading'])) {
            $htmlOptions['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $htmlOptions['loading'];
        }//fin
        if (!isset($htmlOptions['complete'])) {
            $htmlOptions['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $htmlOptions['complete'];
        }//fin

        if (!isset($htmlOptions['loading'])) {
            $htmlOptions['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $htmlOptions['loading'];
        }//fin
        if (!isset($htmlOptions['complete'])) {
            $htmlOptions['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $htmlOptions['complete'];
        }//fin
        if (!isset($options['with'])) {
            $options['with'] = 'Form.serialize(Event.element(event).form)';
            //$options['with'] = "form";
        }
        if (!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = 'submit_' . intval(rand());
            $options['id'] = $htmlOptions['id'];
        }

        if ($confirm == true && strtoupper($htmlOptions['value']) == 'ELIMINAR') {
            $options['confirm'] = "Esta seguro que desea eliminar este registro";
        } else {
            $options['confirm'] = "";
        }

        if (!empty($htmlOptions['funcion'])) {
            $options['funcion'] = $htmlOptions['funcion'];
        } else {
            $options['funcion'] = '';
        }//fin if

        if ($var_encargada == 0) {
            $htmlOptions['onclick'] = "javascript:link_javascript_visor_submit('" . $htmlOptions['id'] . "', '" . $this->Ajax->ajax_remote("click", $this->http_use) . "', '" . $options['funcion'] . "', '" . $this->http_use . "',  '" . $this->http_use_var . "', event, '" . $options['confirm'] . "' ";
        }

        for ($i = 1; $i <= 10; $i++) {
            if (isset($options['update' . $i])) {
                if ($confirm == true && strtoupper($htmlOptions['value']) == 'ELIMINAR' && $ya == 0) {
                    $options['confirm'] = "Estas seguro que desea eliminar este registro";
                    $ya++;
                } else {
                    $options['confirm'] = '';
                }
                $options['update'] = $options['update' . $i];
                $options['url'] = $options['url' . $i];

                if ($options['url'] == '/administradors/' || $options['url'] == '/administradors' || $options['url'] == 'administradors' || $options['url'] == '/administradors/vacio') {
                    $options['update'] = 'principal';
                    $options['url'] = '/modulos/vacio';
                }
                if (strtoupper($htmlOptions['value']) == 'SALIR') {
                    if ($options['url'] == "/root_panel/vacio" || $options['url'] == "/ccnp01_concejo_comunales_entrada/vacio" || $options['url'] == "/administradors/vacio" || $options['url'] == "/modulos/vacio" || $options['url'] == "/administradors/" || $options['url'] == "/modulos/vacio/" || $options['url'] == "/administradors/vacio" || $options['url'] == "/administradors/uso_general") {
                        $salir = true;
                    } else {
                        if ($salir != true) {
                            $salir = false;
                        }
                    }//fin else
                } else {
                    $salir = true;
                }//fin else
                if ($var_encargada != 0) {
                    $script .= $this->Javascript->event("'{$htmlOptions['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
                } else {
                    $htmlOptions['onclick'] .= ", '" . $options['url'] . "', '" . $options['update'] . "'  ";
                }//fin funtion
            }//FIN IF
        }//FIN FOR

        if ($var_encargada == 0) {
            $htmlOptions['onclick'] .= "); return false;";
        } else {
            $htmlOptions['onclick'] = '  return false; ';
        }


//echo $this->Html->submit($title, $htmlOptions);


        $this->combertir_submit($htmlOptions, $title, $salir);


///////  IMPRIMIAR JAVASCRIPT
        echo $script;
////// FIN IMPRIMIAR JAVASCRIPT
    }

//fin function

    function buttonTagRemote($fieldName, $htmlAttributes = array(), $options = array(), $return = false, $confirm = true) {
        Helper::return_helpers();
        $this->Html->setFormTag($fieldName);
        $var_encargada = 0;
        $ya = 0;
        $script = "";
        $options['type'] = 'click';
        $salir = "";


        if (!isset($htmlAttributes['value'])) {
            $htmlAttributes['value'] = $this->Html->tagValue($fieldName);
        }
        if (!isset($htmlAttributes['type'])) {
            $htmlAttributes['type'] = 'button';
        }
        if (!isset($htmlAttributes['src'])) {
            $htmlAttributes['src'] = '';
        }
        if (!isset($htmlAttributes['id'])) {
            $htmlAttributes['id'] = 'button' . intval(rand());
        }
        if (!isset($htmlAttributes['loading'])) {
            $htmlAttributes['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $htmlAttributes['loading'];
        }//fin
        if (!isset($htmlAttributes['complete'])) {
            $htmlAttributes['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $htmlAttributes['complete'];
        }//fin

        if (!isset($htmlAttributes['class'])) {
            $htmlAttributes['class'] = ' ';
        }//fin else
        if (!empty($htmlAttributes['funcion'])) {
            $options['funcion'] = $htmlAttributes['funcion'];
        } else {
            $options['funcion'] = '';
        }//fin if


        if ($confirm == true && strtoupper($htmlAttributes['value']) == 'ELIMINAR') {
            $options['confirm'] = "Esta seguro que desea eliminar este registro";
        } else {
            $options['confirm'] = "";
        }

        if ($var_encargada == 0) {
            $htmlAttributes['onclick'] = "javascript:link_javascript_visor('" . $htmlAttributes['id'] . "', '" . $this->Ajax->ajax_remote("click", $this->http_use) . "', '" . $options['funcion'] . "', '" . $this->http_use . "', '" . $this->http_use_var . "',  '" . $options['confirm'] . "' ";
        }

        for ($i = 1; $i <= 10; $i++) {
            if (isset($options['update' . $i])) {
                if ($confirm == true && strtoupper($htmlAttributes['value']) == 'ELIMINAR' && $ya == 0) {
                    $options['confirm'] = "Estas seguro que desea eliminar este registro";
                    $ya++;
                } else {
                    $options['confirm'] = '';
                }
                $options['update'] = $options['update' . $i];
                $options['url'] = $options['url' . $i];
                if ($options['url'] == '/administradors/' || $options['url'] == '/administradors' || $options['url'] == 'administradors' || $options['url'] == '/administradors/vacio') {
                    $options['update'] = 'principal';
                    $options['url'] = '/modulos/vacio';
                }
                if (strtoupper($htmlAttributes['value']) == 'SALIR') {
                    if ($options['url'] == "/root_panel/vacio" || $options['url'] == "/ccnp01_concejo_comunales_entrada/vacio" || $options['url'] == "/administradors/vacio" || $options['url'] == "/modulos/vacio" || $options['url'] == "/administradors/" || $options['url'] == "/modulos/vacio/" || $options['url'] == "/administradors/vacio" || $options['url'] == "/administradors/uso_general") {
                        $salir = true;
                    } else {
                        if ($salir != true) {
                            $salir = false;
                        }
                    }//fin else
                } else {
                    $salir = true;
                }//fin else
                if ($var_encargada != 0) {
                    $script .= $this->Javascript->event("'{$htmlAttributes['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
                } else {
                    $htmlAttributes['onclick'] .= ", '" . $options['url'] . "', '" . $options['update'] . "'  ";
                }//fin funtion
            }//FIN IF
        }//FIN FOR

        if ($var_encargada == 0) {
            $htmlAttributes['onclick'] .= "); ";
        }



//echo $this->output(sprintf($this->Html->tags['input'], $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);



        $this->combertir_button($htmlAttributes, $return, $salir);


///////  IMPRIMIR JAVASCRIPT
        echo $script;
////// FIN IMPRIMIR JAVASCRIPT
    }

//fin function

    function combertir_button($htmlAttributes = null, $return = null, $salir = true) {


        $ver = 0;

        if ($salir == false) {
            $htmlAttributes['value'] = "REGRESAR";
        }

        if (VERSION_BUTTON == 2) {
            $aux_txt = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = str_replace(" ", "", $htmlAttributes['value']);
        } else {
            $aux_txt = strtoupper($htmlAttributes['value']);
        }


        if (strtoupper($htmlAttributes['value']) == 'SIGUIENTE') {
            $htmlAttributes['class'] = "siguiente_input";
            //$htmlAttributes['onkeypress'] = "nav_next_prev(event);";
            $htmlAttributes['id'] = "bt_siguiente";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Siguiente";
        } else if (strtoupper($htmlAttributes['value']) == 'ANTERIOR') {
            $htmlAttributes['class'] = "anterior_input";
            //$htmlAttributes['onkeypress'] = "nav_next_prev(event);";
            $htmlAttributes['id'] = "bt_anterior";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Anterior";
        } else if (strtoupper($htmlAttributes['value']) == 'SALIR') {
            $htmlAttributes['class'] = "salir_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Salir";
        } else if (strtoupper($htmlAttributes['value']) == 'BUSCAR') {
            $htmlAttributes['class'] = "buscar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Buscar";
        } else if (strtoupper($htmlAttributes['value']) == 'GUARDAR') {
            $htmlAttributes['class'] = "guardar_input2";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Guardar";
        } else if (strtoupper($htmlAttributes['value']) == 'GRABAR') {
            $htmlAttributes['class'] = "guardar_input2";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Guardar";
        } else if (strtoupper($htmlAttributes['value']) == 'CONSULTAR') {
            $htmlAttributes['class'] = "consultar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Consultar";
        } else if (strtoupper($htmlAttributes['value']) == 'PRIMERO') {
            $htmlAttributes['class'] = "primero_input";
            $htmlAttributes['id'] = "bt_primero";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Primero";
        } else if (strtoupper($htmlAttributes['value']) == 'ÚLTIMO') {
            $htmlAttributes['class'] = "ultimo_input";
            $htmlAttributes['id'] = "bt_ultimo";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Último";
        } else if (strtoupper($htmlAttributes['value']) == 'ELIMINAR') {
            $htmlAttributes['class'] = "eliminar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Eliminar";
        } else if (strtoupper($htmlAttributes['value']) == 'MODIFICAR') {
            $htmlAttributes['class'] = "modificar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Modificar";
        } else if (strtoupper($htmlAttributes['value']) == 'EMITIRVALUACIóN') {
            $htmlAttributes['class'] = "generar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Emitir valuación";
        } else if (strtoupper($htmlAttributes['value']) == 'REGRESAR') {
            $htmlAttributes['class'] = "regresar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Regresar";
        } else if (strtoupper($htmlAttributes['value']) == 'CANCELAR') {
            $htmlAttributes['class'] = "regresar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Regresar";
        } else if (strtoupper($htmlAttributes['value']) == 'ANULAR') {
            $htmlAttributes['class'] = "eliminar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Anular";
        } else if (strtoupper($htmlAttributes['value']) == 'ANULACION') {
            $htmlAttributes['class'] = "eliminar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Anular";
        } else if (strtoupper($htmlAttributes['value']) == 'CONTINUAR') {
            $htmlAttributes['class'] = "continuar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Continuar";
        } else if (strtoupper($htmlAttributes['value']) == 'CONSULTA') {
            $htmlAttributes['class'] = "consultar_input";
            $aux_a = strtoupper($htmlAttributes['value']);
            $htmlAttributes['value'] = "";
            $htmlAttributes['title'] = "Consular";
        } else {
            $htmlAttributes['value'] = $aux_txt;
            $ver = 1;
            echo $this->output(sprintf($this->Html->tags['input'], $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
        }//fin else





        if ($ver != 1) {
            if (defined('VERSION_BUTTON')) {
                if (VERSION_BUTTON == 1) {
                    $htmlAttributes['class'] = "";
                    $htmlAttributes['value'] = $aux_txt;
                    echo $this->output(sprintf($this->Html->tags['input'], $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
                } else {
                    echo $this->output(sprintf($this->Html->tags['input'], $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
                }//fin
            } else {
                $htmlAttributes['class'] = "";
                $htmlAttributes['value'] = $aux_txt;
                echo $this->output(sprintf($this->Html->tags['input'], $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
            }//fin
        }//fin if
    }

//fin function

    function combertir_submit($htmlOptions = null, $title = null, $salir = true) {


        $ver = 0;

        if ($salir == false) {
            $htmlOptions['value'] = "REGRESAR";
        }

        if (VERSION_BUTTON == 2) {
            $aux_txt = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = str_replace(" ", "", $htmlOptions['value']);
        } else {
            $aux_txt = strtoupper($htmlOptions['value']);
        }





        if (strtoupper($htmlOptions['value']) == 'GUARDAR') {
            $htmlOptions['class'] = "guardar_input2";
            $id = '7';
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $title = "";
            $htmlOptions['title'] = 'Guardar';
            $title = "Guardar";
        } else if (strtoupper($htmlOptions['value']) == 'GRABAR') {
            $htmlOptions['class'] = "guardar_input2";
            $id = '7';
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "Grabar";
            $title = "Grabar";
        } else if (strtoupper($htmlOptions['value']) == 'SALIR') {
            $htmlOptions['class'] = "salir_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Salir';
            $title = "Salir";
        } else if (strtoupper($htmlOptions['value']) == 'BUSCAR') {
            $htmlOptions['class'] = "buscar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Buscar';
            $title = "Buscar";
        } else if (strtoupper($htmlOptions['value']) == 'CONSULTAR') {
            $htmlOptions['class'] = "consultar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Consultar';
            $title = "Consultar";
        } else if (strtoupper($htmlOptions['value']) == 'PRIMERO') {
            $htmlOptions['class'] = "primero_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Primero';
            $title = "Primero";
        } else if (strtoupper($htmlOptions['value']) == 'ÚLTIMO') {
            $htmlOptions['class'] = "primero_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Último';
            $title = "Último";
        } else if (strtoupper($htmlOptions['value']) == 'SIGUIENTE') {
            $htmlOptions['class'] = "siguiente_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Siguiente';
            $title = "Siguiente";
        } else if (strtoupper($htmlOptions['value']) == 'ANTERIOR') {
            $htmlOptions['class'] = "anterior_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Anterior';
            $title = "Anterior";
        } else if (strtoupper($htmlOptions['value']) == 'ELIMINAR') {
            $htmlOptions['class'] = "eliminar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = 'Eliminar';
            $title = "Eliminar";
        } else if (strtoupper($htmlOptions['value']) == 'MODIFICAR') {
            $htmlOptions['class'] = "modificar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Modificar";
            $title = "Modificar";
        } else if (strtoupper($htmlOptions['value']) == 'EMITIRVALUACIóN') {
            $htmlOptions['class'] = "generar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Emitir valuación";
            $title = "Emitir valuación";
        } else if (strtoupper($htmlOptions['value']) == 'REGRESAR') {
            $htmlOptions['class'] = "regresar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Regresar";
            $title = "Regresar";
        } else if (strtoupper($htmlOptions['value']) == 'CANCELAR') {
            $htmlOptions['class'] = "regresar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Regresar";
            $title = "Regresar";
        } else if (strtoupper($htmlOptions['value']) == 'ANULAR') {
            $htmlOptions['class'] = "eliminar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Anular";
            $title = "Anular";
        } else if (strtoupper($htmlOptions['value']) == 'ANULACION') {
            $htmlOptions['class'] = "eliminar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Anular";
            $title = "Anular";
        } else if (strtoupper($htmlOptions['value']) == 'CONTINUAR') {
            $htmlOptions['class'] = "continuar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Continuar";
            $title = "Continuar";
        } else if (strtoupper($htmlOptions['value']) == 'GENERAR') {
            $htmlOptions['class'] = "generar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Generar";
            $title = "Generar";
        } else if (strtoupper($htmlOptions['value']) == 'GENERARTXT') {
            $htmlOptions['class'] = "generar_input_txt";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Generar";
            $title = "Generar";
        } else if (strtoupper($htmlOptions['value']) == 'CONSULTA') {
            $htmlOptions['class'] = "consultar_input";
            $aux_a = strtoupper($htmlOptions['value']);
            $htmlOptions['value'] = "";
            $htmlOptions['title'] = "Consultar";
            $title = "Consulta";
        } else {
            $title = $aux_txt;
            $htmlOptions['value'] = $aux_txt;
            $ver = 1;
            $aux_a = strtoupper($htmlOptions['value']);
            echo $this->Html->submit($title, $htmlOptions, null, $aux_txt);
        }//fin else



        if ($ver != 1) {
            if (defined('VERSION_BUTTON')) {
                if (VERSION_BUTTON == 1) {
                    $htmlOptions['class'] = "";
                    $htmlOptions['value'] = $aux_txt;
                    $title = $aux_txt;
                    echo $this->Html->submit($title, $htmlOptions, null, $aux_txt);
                } else {
                    echo $this->Html->submit($title, $htmlOptions, null, '');
                }//fin
            } else {
                $htmlOptions['class'] = "";
                $htmlOptions['value'] = $aux_txt;
                $title = $aux_txt;
                echo $this->Html->submit($title, $htmlOptions, null, $aux_txt);
            }//fin
        }//fin if
    }

//fin function

    function mensajes_desactivar($mensaje, $opcion) {

        echo "<script type=text/javascript>";
        echo " nMiliSegundos=3000;";
        echo'window.setTimeout("desactiva_mensaje();", nMiliSegundos);';
        echo"</script>";
        echo "<div id='msj_" . $opcion . "'>" . $mensaje . "</div>";
    }

    function mensajes_correcto($mensaje) {

        echo "<script type=text/javascript>";
        echo "var mensaje = '" . $mensaje . "';";
        echo "fun_msj2(mensaje);";
        echo'if(document.getElementById("valida_codigo")){
			document.getElementById("valida_codigo").innerHTML = "";
		    document.getElementById("valida_codigo").style.display = "none";
			}';
        echo"</script>";
    }

    function mensajes_error($mensaje) {

        echo "<script type=text/javascript>";
        echo "var mensaje = '" . $mensaje . "';";
        echo "fun_msj(mensaje);";
        echo"</script>";
    }

    function linkTagRemote($title, $html_options = array(), $escapeTitle = true) {

        Helper::return_helpers();
        $href = '#';
        $var_encargada = 0;


        if (isset($html_options['href'])) {
            $href = $html_options['href'];
        }//fin

        if (!isset($html_options['onclick'])) {
            $html_options['onclick'] = " ";
        }


        if (!empty($html_options['fallback']) && isset($html_options['fallback'])) {
            $href = $html_options['fallback'];
        }

        if (!isset($html_options['id'])) {
            $html_options['id'] = 'linkTag' . intval(rand());
        }

        if (!isset($html_options['loading'])) {
            $html_options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $html_options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($html_options['complete'])) {
            $html_options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $html_options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin

        /*

          $options['type'] = 'click';
          echo $this->Html->link($title, $href, $html_options, null, $escapeTitle);
          for($i=1; $i<=10; $i++){
          if (isset($html_options['update'.$i])) {

          $options['update'] = $html_options['update'.$i];
          $options['url'] = $html_options['url'.$i];
          $script = $this->Javascript->event("'{$html_options['id']}'", "click", $this->Ajax->remoteFunction($options));
          echo $script;
          }//FIN IF
          }//fin for

         */

        if (!empty($html_options['funcion'])) {
            $options['funcion'] = $html_options['funcion'];
        } else {
            $options['funcion'] = '';
        }//fin if

        $options['confirm'] = "";


        if ($var_encargada == 0) {
            $html_options['onclick'] .= " link_javascript_visor('" . $html_options['id'] . "', '" . $this->Ajax->ajax_remote("click", $this->http_use) . "', '" . $options['funcion'] . "', '" . $this->http_use . "', '" . $this->http_use_var . "',  '" . $options['confirm'] . "' ";
        }

        for ($i = 1; $i <= 10; $i++) {
            if (isset($html_options['update' . $i])) {
                $options['update'] = $html_options['update' . $i];
                $options['url'] = $html_options['url' . $i];
                if ($var_encargada != 0) {
                    $script .= $this->Javascript->event("'{$html_options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
                } else {
                    $html_options['onclick'] .= ", '" . $options['url'] . "', '" . $options['update'] . "'  ";
                }//fin funtion
            }//FIN IF
        }//FIN FOR

        if ($var_encargada == 0) {
            $html_options['onclick'] .= "); ";
        }




        echo $this->Html->link($title, $href, $html_options, null, $escapeTitle);
    }

//fin function

    function linkTagRemote_imagen($title, $imagen, $html_options = array(), $disabled = false, $activa_ventana = null) {

        $href = '#';
        Helper::return_helpers();

        if (!empty($html_options['fallback']) && isset($html_options['fallback'])) {
            $href = $html_options['fallback'];
        }

        if (!isset($html_options['id'])) {
            $html_options['id'] = 'linkTag' . intval(rand());
        }

        if (!isset($html_options['loading'])) {
            $html_options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $html_options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($html_options['complete'])) {
            $html_options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $html_options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin

        $html_options['onclick'] = "return false;";
        $options['type'] = 'click';

        //echo $this->Html->link($title, $href, $html_options);

        if ($activa_ventana == true) {
            $link_ventana = "abre_ventana_porcentaje();";
        } else {
            $link_ventana = "";
        }

        if ($disabled == true) {

            echo'
						 <a href="#" id="' . $html_options['id'] . '" onclick="activa_link_correr_script(this.id); ' . $link_ventana . ' return false;"  style="display:block;">
					            <img src="' . $imagen . '" alt="' . $title . '" align="middle" name="image" border="0"/>
					     <span>' . $title . '</span></a>
					     ';

            echo'
							 <a href="#" id="' . $html_options['id'] . '_b" onclick="' . $link_ventana . 'return false;"  style="display:none;">
						            <img src="' . $imagen . '" alt="' . $title . '" align="middle" name="image" border="0"/>
						     <span>' . $title . '</span></a>
						';
        } else {

            echo'
						 <a href="#" id="' . $html_options['id'] . '" onclick="' . $link_ventana . '"  style="display:block;">
					            <img src="' . $imagen . '" alt="' . $title . '" align="middle" name="image" border="0"/>
					     <span>' . $title . '</span></a>
					    ';
        }//fin else

        for ($i = 1; $i <= 10; $i++) {

            if (isset($html_options['update' . $i])) {

                $options['update'] = $html_options['update' . $i];
                $options['url'] = $html_options['url' . $i];
                $script = $this->Javascript->event("'{$html_options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));

                echo $script;
            }//FIN IF
        }//fin for
    }

//fin function

    function tipoGasto() {

        return array('1' => 'Funcionamiento', '2' => 'Inversión', '3' => 'Situados a Entes', '4' => 'Transferencias');
    }

// FIN FUNCION tipoGasto

    function tipoPresupuesto($modalidad = 1, $extra_cod = array(), $extra_deno = array()) {

        switch ($modalidad) {
            case 1: $tipos_presupuesto = array('1' => 'Ordinario', '2' => 'Coordinado', '3' => 'Fci', '4' => 'Mpps', '5' => 'Ingresos Extraordinarios', '6' => 'Ingresos Propios', '7' => 'Laee', '8' => 'Fides');
                break;
            case 2: $tipos_presupuesto = array('0' => 'Todo', '1' => 'Ordinario', '2' => 'Coordinado', '3' => 'Fci', '4' => 'Mpps', '5' => 'Ingresos Extraordinarios', '6' => 'Ingresos Propios');
                break;
            case 3: $tipos_presupuesto = array('0' => 'Todo', '1' => 'Ordinario', '2' => 'Coordinado', '3' => 'Fci', '4' => 'Mpps', '5' => 'Ingresos Extraordinarios', '6' => 'Ingresos Propios', '7' => 'Laee', '8' => 'Fides');
                break;
            case 4: if (!empty($extra_cod) && !empty($extra_deno)) {
                    	if (is_array($extra_cod) && is_array($extra_deno)) {
                        	$tipos_presupuesto = array_combine($extra_cod, $extra_deno);
                    	}
                	} else {
                    	$tipos_presupuesto = array();
                	}
                break;
            case 5: $tipos_presupuesto = array('1' => 'Ordinario', '2' => 'Coordinado', '3' => 'Fci', '4' => 'Mpps', '5' => 'Ingresos Extraordinarios', '6' => 'Ingresos Propios');
                break;
            default: $tipos_presupuesto = array();
                break;
        }

        return $tipos_presupuesto;
    }

// FIN FUNCION tipoPresupuesto

    function vtipo_motivo_retiro() {
        $motivo_retiro = array('1' => 'Despido justificado', '2' => 'Despido injusticado', '3' => 'Retiro justificado', '4' => 'Renuncia', '5' => 'Jubilacion', '6' => 'Pensionado', '7' => 'Culminacion de contrato', '8' => 'Baja por propia solicitud', '9' => 'Fallecimiento', '10' => 'Baja por Expulsion', '11' => 'Remocion del cargo', '12' => 'Reduccion del Personal', '13' => 'Causa Ajena de la Voluntad de las Partes', '14' => 'Culminación de Comisión de Servicio', '15' => 'Rescisión del Contrato', '16' => 'Destitución');
        return $motivo_retiro;
    }

    /*

      function Tabla($entidad=null,$fecha=false,$titulo=null,$subtitulo=null,$anchoPX=null) {
      $url = $this->webroot . $this->themeWeb . IMAGES_URL;

      $img_top = '../webroot/img/fondos/top/'.date("d").'.jpg';
      if(file_exists($img_top)){
      $img_dia = date("d");
      }else{
      $img_dia = 'default';
      }

      $contenido='<style>
      #tabla_top{
      background-image:url(/img/fondos/top/'.$img_dia.'.jpg);
      background-repeat:no-repeat;
      -moz-border-radius: 15px;
      -webkit-border-radius: 15px;
      border-radius: 15px;
      }
      </style>';

      if(isset($_SESSION["concejo_comunal"])){
      $img_dependencia=$url.'logos_consejos/logo_'.$_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'';
      $img_dependencia_o='../webroot/img/logos_consejos/logo_'.$_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'';

      if(file_exists($img_dependencia_o.".gif") || file_exists($img_dependencia_o.".png")){
      $img_dependencia=file_exists($img_dependencia_o.".gif")==true?$img_dependencia.".gif":$img_dependencia.".png";
      $escudo='<img src="'.$img_dependencia.'" alt="logo_'.$_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'"/>';
      }else{
      $escudo='<img src="/img/logos_dependencias/logo_1_11_30_11_1.png" alt="logo"/>';
      }

      if(isset($entidad)){
      if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
      if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
      }else{
      if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
      if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
      }
      $fecha = $fecha == true ? date("d/m/Y") : "";
      $titulo = !empty($titulo) ? $titulo :"Error: debe pasar como parametro el titulo.";
      $subtitulo = isset($subtitulo) ? $subtitulo : "";
      $width = isset($anchoPX) ? $anchoPX : "100%";

      $contenido .='<table width="100%"  height="100" border="0" cellspacing="0" cellpadding="0" id="tabla_top">' .
      '<tr><td width="73" height="80">'.$escudo.'</td>' .
      '<td class="text14Tabla">&nbsp;</td>' .
      '<td align="right" valign="top" class="text14Tabla">'.$fecha.'&nbsp;&nbsp;&nbsp;</td>' .
      '</tr>' .
      '<tr>' .
      '<td colspan="3" height="28" align="center" class="text18Tabla">'.$this->cambiar(strtoupper($titulo)).'</td>' .
      '</tr>' .
      '<tr><td colspan="3" align="center" >'.$subtitulo.' &nbsp;</td></tr>'.
      '<tr><td colspan="3">&nbsp;&nbsp;&nbsp; '.$this->cambiar($dependencia).'</td></tr>' .
      '</table>';
      if(defined('VERSION')== true && VERSION == 2){
      $this->MarcoTabla_v2($contenido,$width);
      }else{
      $this->MarcoTabla_v1($contenido,$width);
      }


      }else{

      if(isset($_SESSION['SScodpresi']) && isset($_SESSION['SScodentidad'])){
      $img_escudo=$url.'escudo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'';
      //$img_dependencia=$url.'logos_dependencias/logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
      $img_dependencia=$url.'logos_dependencias/logo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
      $img_institucion=$url.'escudos/logo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'';
      $img_escudo_o='../webroot/img/escudos/escudo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'';
      //$img_dependencia_o='../webroot/img/logos_dependencias/logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
      $img_dependencia_o='../webroot/img/logos_dependencias/logo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';


      if($_SESSION['SScodpresi']==1 & $_SESSION['SScodentidad']==1 && $_SESSION['SScodtipoinst']==1 && $_SESSION['SScodinst']==1 && $_SESSION['SScoddep']==1){
      $l=$_SESSION['SScodpresi']."_".$_SESSION['SScodentidad']."_".$_SESSION['SScodtipoinst']."_".$_SESSION['SScodinst']."_".$_SESSION['SScoddep'];
      $escudo="<img src='".$url."/logos_dependencias/logo_".$l.".png'/>";
      }else{
      if(file_exists($img_dependencia_o.".gif") || file_exists($img_dependencia_o.".png")){
      $img_dependencia=file_exists($img_dependencia_o.".gif")==true?$img_dependencia.".gif":$img_dependencia.".png";
      $escudo='<img src="'.$img_dependencia.'" alt="logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'"/>';
      }else{
      if(file_exists($img_escudo_o.".gif") || file_exists($img_escudo_o.".png")){
      $img_dependencia=file_exists($img_escudo_o.".gif")==true?$img_institucion.".gif":$img_institucion.".png";
      $escudo='<img src="'.$img_dependencia.'" alt="logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'"/>';
      }else{
      $escudo='<img src="'.$url.'logos_dependencias/logo_jl.png"/>';
      }

      }
      }//fin else
      }else{
      $escudo='<img src="'.$url.'logos_dependencias/logo_jl.png"/>';
      }

      if(isset($entidad)){
      if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
      if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
      }else{
      if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
      if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
      }

      $fecha = $fecha == true ? date("d/m/Y") : "";
      $titulo = !empty($titulo) ? $titulo :"Error: debe pasar como parametro el titulo.";
      $subtitulo = isset($subtitulo) ? $subtitulo : "";
      $width = isset($anchoPX) ? $anchoPX : "100%";

      $contenido .='<table width="100%"  height="100" border="0" cellspacing="0" cellpadding="0" id="tabla_top">' .
      '<tr><td width="73" height="80">'.$escudo.'</td>' .
      '<td class="text14Tabla">&nbsp;</td>' .
      '<td align="right" valign="top" class="text14Tabla">'.$fecha.'&nbsp;&nbsp;&nbsp;</td>' .
      '</tr>' .
      '<tr>' .
      '<td colspan="3" height="28" align="center" class="text18Tabla">'.$this->cambiar(strtoupper($titulo)).'</td>' .
      '</tr>' .
      '<tr><td colspan="3" align="center" >'.$subtitulo.' &nbsp;</td></tr>'.
      '<tr><td colspan="3">&nbsp;&nbsp;&nbsp;'.$this->cambiar($dependencia).'</td></tr>' .
      '</table>';
      if(defined('VERSION')== true && VERSION == 2){
      $this->MarcoTabla_v2($contenido,$width);
      }else{
      $this->MarcoTabla_v1($contenido,$width);
      }

      }//fin else



      }//fin function Tabla


     */

    function Tabla($entidad = null, $fecha = false, $titulo = null, $subtitulo = null, $anchoPX = null) {
        $contenido = $this->Sisap2->top_tabla($entidad, $fecha, $titulo, $subtitulo, $anchoPX);
        $width = isset($anchoPX) ? $anchoPX : "100%";
        if (defined('VERSION_MARCO') == true && VERSION_MARCO == 2) {
            $this->MarcoTabla_v2($contenido, $width);
        } else if (defined('VERSION_MARCO') == true && VERSION_MARCO == 3) {
            $this->Sisap2->Tabla($entidad, $fecha, $titulo, $subtitulo, $anchoPX);
        } else {
            $this->MarcoTabla_v1($contenido, $width);
        }
    }
 function Tabla_modulos($entidad = null, $fecha = false, $titulo = null, $subtitulo = null, $anchoPX = null, $modulo = null) {
    $contenido = $this->Sisap2->top_tabla_modulos($entidad, $fecha, $titulo, $subtitulo, $anchoPX,$modulo);
    $width = isset($anchoPX) ? $anchoPX : "100%";
    if (defined('VERSION_MARCO') == true && VERSION_MARCO == 2) {
        $this->MarcoTabla_v2($contenido, $width);
    } else if (defined('VERSION_MARCO') == true && VERSION_MARCO == 3) {
        $this->Sisap2->Tabla_modulos($entidad, $fecha, $titulo, $subtitulo, $anchoPX, $modulo);
    } else {
        $this->MarcoTabla_v1($contenido, $width);
    }
}
    function cambiar($cadena) {
        $cadena = str_replace("&AACUTE;", "&Aacute;", $cadena);
        $cadena = str_replace("&EACUTE;", "&Eacute;", $cadena);
        $cadena = str_replace("&IACUTE;", "&Iacute;", $cadena);
        $cadena = str_replace("&OACUTE;", "&Oacute;", $cadena);
        $cadena = str_replace("&UACUTE;", "&Uacute;", $cadena);
        $cadena = str_replace("à", "&aacute;", $cadena);
        $cadena = str_replace("è", "&eacute;", $cadena);
        $cadena = str_replace("ì", "&iacute;", $cadena);
        $cadena = str_replace("ò", "&oacute;", $cadena);
        $cadena = str_replace("ù", "&uacute;", $cadena);
        $cadena = str_replace("À", "&Aacute;", $cadena);
        $cadena = str_replace("È", "&Eacute;", $cadena);
        $cadena = str_replace("Ì", "&Iacute;", $cadena);
        $cadena = str_replace("Ò", "&Oacute;", $cadena);
        $cadena = str_replace("Ù", "&Uacute;", $cadena);
        return $cadena;
    }



    function Formato1_cantidad($monto) {
        $monto = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $monto));
        $decimales = 2;

        if (substr($monto, -10, 1) == '.') {
            $sents = '.' . substr($monto, -9);
            $monto = substr($monto, 0, strlen($monto) - 10);
            $decimales = 9;
        } else if (substr($monto, -9, 1) == '.') {
            $sents = '.' . substr($monto, -8);
            $monto = substr($monto, 0, strlen($monto) - 9);
            $decimales = 8;
        } else if (substr($monto, -8, 1) == '.') {
            $sents = '.' . substr($monto, -7);
            $monto = substr($monto, 0, strlen($monto) - 8);
            $decimales = 7;
        } else if (substr($monto, -7, 1) == '.') {
            $sents = '.' . substr($monto, -6);
            $monto = substr($monto, 0, strlen($monto) - 7);
            $decimales = 6;
        } else if (substr($monto, -6, 1) == '.') {
            $sents = '.' . substr($monto, -5);
            $monto = substr($monto, 0, strlen($monto) - 6);
            $decimales = 5;
        } else if (substr($monto, -5, 1) == '.') {
            $sents = '.' . substr($monto, -4);
            $monto = substr($monto, 0, strlen($monto) - 5);
            $decimales = 4;
        } else if (substr($monto, -4, 1) == '.') {
            $sents = '.' . substr($monto, -3);
            $monto = substr($monto, 0, strlen($monto) - 4);
            $decimales = 3;
        } else if (substr($monto, -3, 1) == '.') {
            $sents = '.' . substr($monto, -2);
            $monto = substr($monto, 0, strlen($monto) - 3);
            $decimales = 2;
        } elseif (substr($monto, -2, 1) == '.') {
            $sents = '.' . substr($monto, -1);
            $monto = substr($monto, 0, strlen($monto) - 2);
            $decimales = 1;
        } else {
            $sents = '.00';
        }//fin else

        $monto = preg_replace("/[^0-9]/", "", $monto);
        return number_format($monto . $sents, $decimales, '.', '');
    }

//fin function

    function Formato2_cantidad($monto) {

        $decimales = 2;

        if (substr($monto, -10, 1) == '.') {
            $decimales = 9;
        } else if (substr($monto, -9, 1) == '.') {
            $decimales = 8;
        } else if (substr($monto, -8, 1) == '.') {
            $decimales = 7;
        } else if (substr($monto, -7, 1) == '.') {
            $decimales = 6;
        } else if (substr($monto, -6, 1) == '.') {
            $decimales = 5;
        } else if (substr($monto, -5, 1) == '.') {
            $decimales = 4;
        } else if (substr($monto, -4, 1) == '.') {
            $decimales = 3;
        } else if (substr($monto, -3, 1) == '.') {
            $decimales = 2;
        } elseif (substr($monto, -2, 1) == '.') {
            $decimales = 1;
        }//fin else
//$monto = preg_replace("/[^0-9]/", "", $monto);
        return number_format($monto, $decimales, ",", ".");
    }

//fin function

    function imputationBudget($name = null, $rows = array(), $options = array(), $type = null, $epath = array(), $width = "100%", $fondo = array(true, "")) {

        if (!isset($options['type'])) {
            $options['type'] = (int) $type;
        }
        if (!isset($options['class'])) {
            $options['class'] = "tablacompromiso tablacompromiso2";
        }
        if (!isset($options['class_tr'])) {
            $options['class_tr'] = "tr_negro";
        }
        if (!isset($options['border'])) {
            $options['border'] = "0";
        }
        if (!isset($options['cellspacing'])) {
            $options['cellspacing'] = "0";
        }
        if (!isset($options['cellpadding'])) {
            $options['cellpadding'] = "0";
        }
        if (!isset($options['align'])) {
            $options['align'] = "center";
        }
        if (!isset($options['title'])) {
            $options['title'] = "Imputaci&oacute;n Presupuestaria";
        }
        if (isset($options['subtitle']) && strlen($options['subtitle']) > 0) {
            $options['title'] .= "<BR>" . $options['subtitle'];
        }

        $n_name = explode('/', $name);
        $nmodel = isset($n_name[0]) ? $n_name[0] : 'modelo';
        $nfield = isset($n_name[1]) ? $n_name[1] : 'campo';
        $ninput = "data[$nmodel][$nfield]";

        if (!isset($epath['url'])) {
            $epath['url'] = 'modulos';
        } else {
            $epath['url'] = trim(str_replace("/", "", $epath['url']));
        }
        if (!isset($epath['action'])) {
            $epath['action'] = 'vacio';
        } else {
            $epath['action'] = trim(str_replace("/", "", $epath['action']));
        }
        if (isset($epath) && is_array($epath) && array_key_exists("parameters", $epath)) {
            $epath['parameters'] = implode("/", $epath['parameters']);
        } else {
            $epath['parameters'] = '';
        }
        if (!isset($epath['update'])) {
            $epath['update'] = 'principal';
        }
        if (!isset($epath['function'])) {
            $epath['function'] = '';
        } else {
            $epath['function'] = "'funcion' => '" . $epath['function'] . "'";
        }
        if (!isset($epath['id'])) {
            $epath['id'] = '';
        }
        if (!isset($epath['button'])) {
            $epath['button'] = '';
        }

        if ($epath['button'] != '')
            $epath['class'] = '';
        else
        if (!isset($epath['class'])) {
            $epath['class'] = 'agregar_imp_input';
        }

        if ($fondo[0] === true) {
            $url = $this->webroot . $this->themeWeb . IMAGES_URL;
            if (trim($fondo[1]) != "") {
                if (file_exists("../webroot" . $url . trim($fondo[1]))) {
                    $cap_fondo = "background=" . $url . trim($fondo[1]);
                } else {
                    $fondo[1] = "bg_pro_bar.jpg";
                    $cap_fondo = "background=" . $url . $fondo[1];
                }
            } else {
                $fondo[1] = "bg_pro_bar.jpg";
                $cap_fondo = "background=" . $url . $fondo[1];
            }
        } else {
            $cap_fondo = "";
        }

        if ($options['type'] <= 0 || $options['type'] > 4) {

            echo '<table ' . $cap_fondo . ' width="' . $width . '" border="' . $options['border'] . '" cellspacing="' . $options['cellspacing'] . '" cellpadding="' . $options['cellpadding'] . '" class="' . $options['class'] . '">
					<tr align="' . $options['align'] . '">
    					<td class="' . $options['class_tr'] . '" style="font-size:14pt;" width="100%">' . $options['title'] . '</td>
  					</tr>
  					<tr>
						<td align="' . $options['align'] . '" id="upd_' . $nfield . '_1" style="font-size:12pt;color:#940000;"><br/>Lo Siento, el proceso para la Imputaci&oacute;n Presupuestaria fue invocado incorrectamente, consulte al Administrador / Programador.<br/><br/></td>
  					</tr>
				</table>';

            $this->mensajes_error("La Modalidad para el Proceso de Imputaci&oacute;n Presupuestaria es incorrecta, consulte con un Administrador / Programador.");
        } else {


            switch ((int) $options['type']) {

                case 1:

                    echo '<table ' . $cap_fondo . ' width="' . $width . '" border="' . $options['border'] . '" cellspacing="' . $options['cellspacing'] . '" cellpadding="' . $options['cellpadding'] . '" class="' . $options['class'] . '">
					<tr class="' . $options['class_tr'] . '" align="' . $options['align'] . '">
    					<td width="5%">Dep</td>
    					<td width="15%">Proyecto<br>Acci&oacute;n<br>Centralizada</td>
    					<td width="15%">Acci&oacute;n<br>Espec&iacute;fica</td>
    					<td width="5%">Activ</td>
		    			<td width="5%">Part</td>
    					<td width="5%">Gen</td>
    					<td width="5%">Esp</td>
    					<td width="5%">Sub<br>Esp</td>
    					<td width="5%">Aux</td>
    					<td width="10%">Tipo<br>de<br>Gasto</td>
    					<td width="10%">Tipo<br>de<br>Recurso</td>
    					<td width="10%">Monto</td>
    					<td width="5%">---</td>
  					</tr>

  					<tr>
						<td align="' . $options['align'] . '" id="upd_' . $nfield . '_1"><input name="data["' . $nmodel . '"]["' . $nfield . '_1"]" value="" id="' . $nfield . '_1" maxlength="4" onKeyPress="return solonumeros_enteros(event);" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_2"><select id="' . $nfield . '_2" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_3"><select id="' . $nfield . '_3" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_4"><select id="' . $nfield . '_4" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_5"><select id="' . $nfield . '_5" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_6"><select id="' . $nfield . '_6" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_7"><select id="' . $nfield . '_7" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_8"><select id="' . $nfield . '_8" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_9"><select id="' . $nfield . '_9" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_10">' . $this->Html->selectTag($nmodel . '/' . $nfield . '_10', $this->tipoGasto(), null, array('id' => $nfield . '_10', 'class' => 'campoText', 'style' => 'border:1px solid cyan;'), null, true) . '</td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_11">' . $this->Html->selectTag($nmodel . '/' . $nfield . '_11', $this->tipoPresupuesto(1), null, array('id' => $nfield . '_11', 'class' => 'campoText', 'style' => 'border:1px solid cyan;'), null, true) . '</td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_12"><input name="data["' . $nmodel . '"]["' . $nfield . '_12"]" value="" id="' . $nfield . '_12" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_12\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_13">';
                    $url1 = '/' . $epath['url'] . '/' . $epath['action'] . '/' . $epath['parameters'];
                    echo $this->submitTagRemote($epath['button'], array($epath['function'], 'url1' => $url1, 'update1' => $epath['update'], 'id' => $epath['id'], 'class' => $epath['class'])) .
                    '</td>
  					</tr>';

                    if (!empty($rows)) {
                        $r = 1;
                        foreach ($rows as $drows) {
                            echo '<tr id="rowimp_' . $r . '">
						<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">';
                            echo $this->submitTagRemote('', array('funcion' => 'validar_monto', 'url1' => '/cfpp05/guardar', 'update1' => 'ListaGastos', 'id' => 'bt_guardar', 'class' => 'agregar_imp_input')) .
                            '</td>
  					</tr>';
                            $r++;
                        }// FIN FOREACH
                    } else {
                        echo '<tr align="' . $options['align'] . '">';
                        for ($j = 0; $j < 13; $j++) {
                            echo '<td>--</td>';
                        }
                        echo '</tr>';
                    }

                    echo '<tr id="TOTAL_IMP">
						<td align="right" colspan="11" style="font-size:10pt;font-weight:bold;">TOTAL ' . MONEDA1 . '.</td>
						<td align="left" colspan="2" style="font-size:10pt;font-weight:bold;">&nbsp;</td>
					  </tr>
					  <tr id="TOTAL_DISTRIBUCION_IMP">
						<td align="right" colspan="11" style="font-size:10pt;font-weight:bold;">TOTAL DISTRIBUCI&Oacute;N ' . MONEDA1 . '.</td>
						<td align="left" colspan="2" style="font-size:10pt;font-weight:bold;">&nbsp;</td>
  					  </tr>';

                    break;




                case 2:

                    echo '<table ' . $cap_fondo . ' width="' . $width . '" border="' . $options['border'] . '" cellspacing="' . $options['cellspacing'] . '" cellpadding="' . $options['cellpadding'] . '" class="' . $options['class'] . '">
					<tr class="' . $options['class_tr'] . '" align="' . $options['align'] . '">
    					<td width="5%">A&ntilde;o</td>
    					<td width="15%">Proyecto<br>Acci&oacute;n<br>Centralizada</td>
    					<td width="15%">Acci&oacute;n<br>Espec&iacute;fica</td>
    					<td width="5%">Activ</td>
		    			<td width="5%">Part</td>
    					<td width="5%">Gen</td>
    					<td width="5%">Esp</td>
    					<td width="5%">Sub<br>Esp</td>
    					<td width="5%">Aux</td>
    					<td width="10%">Disponibilidad<br>Anual</td>
    					<td width="10%">Disponibilidad<br>del Mes</td>
    					<td width="10%">Monto</td>
    					<td width="5%">---</td>
  					</tr>

  					<tr>
						<td align="' . $options['align'] . '" id="upd_' . $nfield . '_1"><input name="data["' . $nmodel . '"]["' . $nfield . '_1"]" value="" id="' . $nfield . '_1" maxlength="4" onKeyPress="return solonumeros_enteros(event);" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_2"><select id="' . $nfield . '_2" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_3"><select id="' . $nfield . '_3" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_4"><select id="' . $nfield . '_4" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_5"><select id="' . $nfield . '_5" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_6"><select id="' . $nfield . '_6" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_7"><select id="' . $nfield . '_7" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_8"><select id="' . $nfield . '_8" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_9"><select id="' . $nfield . '_9" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_10"><input name="data["' . $nmodel . '"]["' . $nfield . '_10"]" value="" id="' . $nfield . '_10" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_10\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_11"><input name="data["' . $nmodel . '"]["' . $nfield . '_11"]" value="" id="' . $nfield . '_11" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_11\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_12"><input name="data["' . $nmodel . '"]["' . $nfield . '_12"]" value="" id="' . $nfield . '_12" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_12\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_13">';
                    $url1 = '/' . $epath['url'] . '/' . $epath['action'] . '/' . $epath['parameters'];
                    echo $this->submitTagRemote($epath['button'], array($epath['function'], 'url1' => $url1, 'update1' => $epath['update'], 'id' => $epath['id'], 'class' => $epath['class'])) .
                    '</td>
  					</tr>';

                    if (!empty($rows)) {
                        $r = 1;
                        foreach ($rows as $drows) {
                            echo '<tr id="rowimp_' . $r . '">
						<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">';
                            echo $this->submitTagRemote('', array('funcion' => 'validar_monto', 'url1' => '/cfpp05/guardar', 'update1' => 'ListaGastos', 'id' => 'bt_guardar', 'class' => 'agregar_imp_input')) .
                            '</td>
  					</tr>';
                            $r++;
                        }// FIN FOREACH
                    } else {
                        echo '<tr align="' . $options['align'] . '">';
                        for ($j = 0; $j < 13; $j++) {
                            echo '<td>--</td>';
                        }
                        echo '</tr>';
                    }

                    echo '<tr id="TOTAL_IMP">
						<td align="right" colspan="11" style="font-size:10pt;font-weight:bold;">TOTAL ' . MONEDA1 . '.</td>
						<td align="left" colspan="2" style="font-size:10pt;font-weight:bold;">&nbsp;</td>
					  </tr>';

                    break;




                case 3:

                    echo '<table ' . $cap_fondo . ' width="' . $width . '" border="' . $options['border'] . '" cellspacing="' . $options['cellspacing'] . '" cellpadding="' . $options['cellpadding'] . '" class="' . $options['class'] . '">
					<tr class="' . $options['class_tr'] . '" align="' . $options['align'] . '">
    					<td width="5%">A&ntilde;o</td>
    					<td width="18%">Proyecto<br>Acci&oacute;n<br>Centralizada</td>
    					<td width="18%">Acci&oacute;n<br>Espec&iacute;fica</td>
    					<td width="5%">Activ</td>
		    			<td width="5%">Part</td>
    					<td width="5%">Gen</td>
    					<td width="5%">Esp</td>
    					<td width="5%">Sub<br>Esp</td>
    					<td width="5%">Aux</td>
    					<td width="10%">Disponibilidad</td>
    					<td width="14%">Monto</td>
    					<td width="5%">---</td>
  					</tr>

  					<tr>
						<td align="' . $options['align'] . '" id="upd_' . $nfield . '_1"><input name="data["' . $nmodel . '"]["' . $nfield . '_1"]" value="" id="' . $nfield . '_1" maxlength="4" onKeyPress="return solonumeros_enteros(event);" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_2"><select id="' . $nfield . '_2" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_3"><select id="' . $nfield . '_3" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_4"><select id="' . $nfield . '_4" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_5"><select id="' . $nfield . '_5" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_6"><select id="' . $nfield . '_6" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_7"><select id="' . $nfield . '_7" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_8"><select id="' . $nfield . '_8" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_9"><select id="' . $nfield . '_9" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_10"><input name="data["' . $nmodel . '"]["' . $nfield . '_10"]" value="" id="' . $nfield . '_10" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_10\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_11"><input name="data["' . $nmodel . '"]["' . $nfield . '_11"]" value="" id="' . $nfield . '_11" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_11\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_12">';
                    $url1 = '/' . $epath['url'] . '/' . $epath['action'] . '/' . $epath['parameters'];
                    echo $this->submitTagRemote($epath['button'], array($epath['function'], 'url1' => $url1, 'update1' => $epath['update'], 'id' => $epath['id'], 'class' => $epath['class'])) .
                    '</td>
  					</tr>';

                    if (!empty($rows)) {
                        $r = 1;
                        foreach ($rows as $drows) {
                            echo '<tr id="rowimp_' . $r . '">
						<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">';
                            echo $this->submitTagRemote('', array('funcion' => 'validar_monto', 'url1' => '/cfpp05/guardar', 'update1' => 'ListaGastos', 'id' => 'bt_guardar', 'class' => 'agregar_imp_input')) .
                            '</td>
  					</tr>';
                            $r++;
                        }// FIN FOREACH
                    } else {
                        echo '<tr align="' . $options['align'] . '">';
                        for ($j = 0; $j < 12; $j++) {
                            echo '<td>--</td>';
                        }
                        echo '</tr>';
                    }

                    echo '<tr id="TOTAL_IMP">
						<td align="right" colspan="10" style="font-size:10pt;font-weight:bold;">TOTAL ' . MONEDA1 . '.</td>
						<td align="left" colspan="2" style="font-size:10pt;font-weight:bold;">&nbsp;</td>
					  </tr>';

                    break;




                case 4:

                    echo '<table ' . $cap_fondo . ' width="' . $width . '" border="' . $options['border'] . '" cellspacing="' . $options['cellspacing'] . '" cellpadding="' . $options['cellpadding'] . '" class="' . $options['class'] . '">
					<tr class="' . $options['class_tr'] . '" align="' . $options['align'] . '">
    					<td width="5%">A&ntilde;o</td>
    					<td width="20%">Proyecto<br>Acci&oacute;n<br>Centralizada</td>
    					<td width="20%">Acci&oacute;n<br>Espec&iacute;fica</td>
    					<td width="6%">Activ</td>
		    			<td width="6%">Part</td>
    					<td width="6%">Gen</td>
    					<td width="6%">Esp</td>
    					<td width="6%">Sub<br>Esp</td>
    					<td width="6%">Aux</td>
    					<td width="14%">Monto</td>
    					<td width="5%">---</td>
  					</tr>

  					<tr>
						<td align="' . $options['align'] . '" id="upd_' . $nfield . '_1"><input name="data["' . $nmodel . '"]["' . $nfield . '_1"]" value="" id="' . $nfield . '_1" maxlength="4" onKeyPress="return solonumeros_enteros(event);" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_2"><select id="' . $nfield . '_2" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_3"><select id="' . $nfield . '_3" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_4"><select id="' . $nfield . '_4" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_5"><select id="' . $nfield . '_5" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_6"><select id="' . $nfield . '_6" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_7"><select id="' . $nfield . '_7" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_8"><select id="' . $nfield . '_8" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_9"><select id="' . $nfield . '_9" class="campoText" style="border:1px solid cyan;"></select></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_10"><input name="data["' . $nmodel . '"]["' . $nfield . '_10"]" value="" id="' . $nfield . '_10" onKeyPress="return solonumeros_con_punto(event);" onChange="moneda(\"' . $nfield . '_10\");" style="text-align:center;border:1px solid cyan;" class="campoText"></td>
    					<td align="' . $options['align'] . '" id="upd_' . $nfield . '_11">';
                    $url1 = '/' . $epath['url'] . '/' . $epath['action'] . '/' . $epath['parameters'];
                    echo $this->submitTagRemote($epath['button'], array($epath['function'], 'url1' => $url1, 'update1' => $epath['update'], 'id' => $epath['id'], 'class' => $epath['class'])) .
                    '</td>
  					</tr>';

                    if (!empty($rows)) {
                        $r = 1;
                        foreach ($rows as $drows) {
                            echo '<tr id="rowimp_' . $r . '">
						<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">' . $drows[$nmodel][''] . '</td>
    					<td align="' . $options['align'] . '">';
                            echo $this->submitTagRemote('', array('funcion' => 'validar_monto', 'url1' => '/cfpp05/guardar', 'update1' => 'ListaGastos', 'id' => 'bt_guardar', 'class' => 'agregar_imp_input')) .
                            '</td>
  					</tr>';
                            $r++;
                        }// FIN FOREACH
                    } else {
                        echo '<tr align="' . $options['align'] . '">';
                        for ($j = 0; $j < 11; $j++) {
                            echo '<td>--</td>';
                        }
                        echo '</tr>';
                    }

                    echo '<tr id="TOTAL_IMP">
						<td align="right" colspan="9" style="font-size:10pt;font-weight:bold;">TOTAL ' . MONEDA1 . '.</td>
						<td align="left" colspan="2" style="font-size:10pt;font-weight:bold;">&nbsp;</td>
					  </tr>';

                    break;




                default:

                    $this->mensajes_error("La Modalidad para el Proceso de Imputaci&oacute;n Presupuestaria es incorrecta, consulte con un Administrador / Programador.");

                    echo '<table ' . $cap_fondo . ' width="' . $width . '" border="' . $options['border'] . '" cellspacing="' . $options['cellspacing'] . '" cellpadding="' . $options['cellpadding'] . '" class="' . $options['class'] . '">
					<tr align="' . $options['align'] . '">
    					<td class="' . $options['class_tr'] . '" style="font-size:14pt;" width="100%">' . $options['title'] . '</td>
  					</tr>
  					<tr>
						<td align="' . $options['align'] . '" id="upd_' . $nfield . '_1" style="font-size:12pt;color:#940000;"><br/>Lo Siento, el proceso para la Imputaci&oacute;n Presupuestaria fue invocado incorrectamente, consulte al Administrador / Programador.<br/><br/></td>
  					</tr>';

                    break;
            } // FIN SWITCH

            echo '</table>';
        }
    }

// FIN FUNCTION IMPUTATIONBUDGET

    function MarcoTabla_v2($content, $width) {
        $ancho_td_central = $width - (28 * 2);
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        $tablaGeneral = '<table width="' . $width . '" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  style="width:28px;height:23px;background-image:url(' . $url . 'tabla_r2_c2.png);">&nbsp;</td>
    <td  style="background-image:url(' . $url . 'tabla_r2_c4.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="' . $ancho_td_central . '" height="23" border="0" alt="" /></td>
    <td  style="width:28px;height:23px;background-image:url(' . $url . 'tabla_r2_c6.png);">&nbsp;</td>
  </tr>
  <tr>
    <td style="width:28px;background-image:url(' . $url . 'tabla_r4_c2.png); background-repeat:repeat-y"><img src="' . $url . 'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
    <td bgcolor="#AAD6EA">' . $content . '</td>
    <td style="width:28px;background-image:url(' . $url . 'tabla_r4_c6.png); background-repeat:repeat-y"><img src="' . $url . 'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
  </tr>
  <tr>
    <td width="28" height="25"><img name="tabla_r6_c2" src="' . $url . 'tabla_r6_c2.png" width="28" height="25" border="0" id="tabla_r6_c2" alt="" /></td>
    <td  style="background-image:url(' . $url . 'tabla_r6_c4.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="25" border="0" alt="" /></td>
    <td width="28" height="25"><img name="tabla_r6_c6" src="' . $url . 'tabla_r6_c6.png" width="28" height="25" border="0" id="tabla_r6_c6" alt="" /></td>
  </tr>
</table>';
        echo $tablaGeneral;
    }

//fin marcoTabla

    function MarcoTabla_v1($content, $width) {
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        $tablaGeneral = '<table width="' . $width . '" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="15" height="11"><img name="tabla_r1_c1" src="' . $url . 'tabla_r1_c1.png" width="15" height="11" border="0" id="tabla_r1_c1" alt="." /></td>
		    <td style="background-image:url(' . $url . 'tabla_r1_c3.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="11" border="0" alt="" /></td>
		    <td width="19" height="11"><img name="tabla_r1_c5" src="' . $url . 'tabla_r1_c5.png" width="19" height="11" border="0" id="tabla_r1_c5" alt="." /></td>
		  </tr>
		  <tr>
		    <td  style="background-image:url(' . $url . 'tabla_r3_c1.png); background-repeat:repeat-y">&nbsp;</td>
		    <td bgcolor="#0488B4">' . $content . '</td>
		    <td style="background-image:url(' . $url . 'tabla_r3_c5.png); background-repeat:repeat-y">&nbsp;</td>
		  </tr>
		  <tr>
		    <td><img name="tabla_r7_c1" src="' . $url . 'tabla_r7_c1.png" width="15" height="19" border="0" id="tabla_r7_c1" alt="." /></td>
		    <td  style="background-image:url(' . $url . 'tabla_r6_c3.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="17" border="0" alt="" /></td>
		    <td><img name="tabla_r6_c5" src="' . $url . 'tabla_r6_c5.png" width="19" height="19" border="0" id="tabla_r6_c5" alt="." /></td>
		  </tr>
		</table>';
        echo $tablaGeneral;
    }

//fin marcoTabla

    function OpenTable($width) {
        if (defined('VERSION_MARCO') == true && VERSION_MARCO == 2) {
            $this->OpenTable_v2($width);
        } else if (defined('VERSION_MARCO') == true && VERSION_MARCO == 3) {
            //$this->OpenTable_v2($width);
        } else {
            $this->OpenTable_v1($width);
        }
    }

//

    function CloseTable() {
        if (defined('VERSION_MARCO') == true && VERSION_MARCO == 2) {
            $this->CloseTable_v2();
        } else if (defined('VERSION_MARCO') == true && VERSION_MARCO == 3) {
            echo '</div></fieldset>';
        } else {
            $this->CloseTable_v1();
        }
    }

//

    function OpenTable_v2($width) {
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        $AbreTabla = '<table width="' . $width . '" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="28" height="23"><img name="tabla_r2_c2" src="' . $url . 'tabla_r2_c2.png" width="28" height="23" border="0" id="tabla_r2_c2" alt="" /></td>
    <td style="background-image:url(' . $url . 'tabla_r2_c4.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="23" border="0" alt="" /></td>
    <td width="28" height="23"><img name="tabla_r2_c6" src="' . $url . 'tabla_r2_c6.png" width="28" height="23" border="0" id="tabla_r2_c6" alt="" /></td>
  </tr>
  <tr>
    <td  style="background-image:url(' . $url . 'tabla_r4_c2.png); background-repeat:repeat-y"><img src="' . $url . 'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
    <td bgcolor="#AAD6EA" valign="top" align="center" style="font-family: arial,sans-serif;font-size: 10px;text-transform: uppercase;">';
        echo $AbreTabla;
    }

//fin OpenTable

    function CloseTable_v2() {
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        $cerrarTabla = '</td>
    <td style="background-image:url(' . $url . 'tabla_r4_c6.png); background-repeat:repeat-y"><img src="' . $url . 'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
  </tr>
  <tr>
    <td width="28" height="25"><img name="tabla_r6_c2" src="' . $url . 'tabla_r6_c2.png" width="28" height="25" border="0" id="tabla_r6_c2" alt="" /></td>
    <td  style="background-image:url(' . $url . 'tabla_r6_c4.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="25" border="0" alt="" /></td>
    <td width="28" height="25"><img name="tabla_r6_c6" src="' . $url . 'tabla_r6_c6.png" width="28" height="25" border="0" id="tabla_r6_c6" alt="" /></td>
  </tr>
</table>';
        echo $cerrarTabla;
    }

//fin CloseTable

    function OpenTable_v1($width) {
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        $AbreTabla = '<table width="' . $width . '" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="15" height="11"><img name="tabla_r1_c1" src="' . $url . 'tabla_r1_c1.png" width="15" height="11" border="0" id="tabla_r1_c1" alt="." /></td>
		    <td style="background-image:url(' . $url . 'tabla_r1_c3.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="11" border="0" alt="" /></td>
		    <td width="19" height="11"><img name="tabla_r1_c5" src="' . $url . 'tabla_r1_c5.png" width="19" height="11" border="0" id="tabla_r1_c5" alt="." /></td>
		  </tr>
		  <tr>
		    <td  style="background-image:url(' . $url . 'tabla_r3_c1.png); background-repeat:repeat-y">&nbsp;</td>
		    <td bgcolor="#0488B4" valign="top" align="center">';
        echo $AbreTabla;
    }

//fin OpenTable

    function CloseTable_v1() {
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        $cerrarTabla = '</td>
		    <td style="background-image:url(' . $url . 'tabla_r3_c5.png); background-repeat:repeat-y">&nbsp;</td>
		  </tr>
		  <tr>
		    <td><img name="tabla_r7_c1" src="' . $url . 'tabla_r7_c1.png" width="15" height="19" border="0" id="tabla_r7_c1" alt="." /></td>
		    <td  style="background-image:url(' . $url . 'tabla_r6_c3.png); background-repeat:repeat-x"><img src="' . $url . 'spacer.gif" width="1" height="17" border="0" alt="" /></td>
		    <td><img name="tabla_r6_c5" src="' . $url . 'tabla_r6_c5.png" width="19" height="19" border="0" id="tabla_r6_c5" alt="." /></td>
		  </tr>
		</table>';
        echo $cerrarTabla;
    }

//fin CloseTable

    function TablaMsj($capa1 = null, $capa2 = null) {
        $url = $this->webroot . $this->themeWeb . IMAGES_URL;
        if (isset($capa1)) {
            $o = '<div id="' . $capa1 . '"></div>';
        } else {
            $o = '';
        }
        if (isset($capa2)) {
            $oo = '<div id="' . $capa2 . '"></div>';
        } else {
            $oo = '';
        }
        $contenido = '<table width="750"  border="0" cellpadding="0" cellspacing="0"  style="margin-top:10px;">
<tr><td width="1"><img src="' . $url . 'blank.gif" width="1" height="30"></td><td><div id="msj_cancelar" style="display:none;"></div>
			<div id="msj_aceptar" style="display:none;"></div>' . $o . $oo . '</td></tr>
</table>';

        echo $contenido;
    }

//fin tablamsj

    function CssSelect($width = null) {
        if (isset($width)) {
            $contentCSS = '<style type="text/css">';
            $contentCSS.='#select_1, #select_2, #select_3, #select_4, #select_5, #select_6, #select_7, #select_8, #select_9, #select_10, #select_11, #vacio{
	                   width:' . $width . 'px;}';
            $contentCSS.='</style>';
        } else {
            $contentCSS = '<style type="text/css">';
            $contentCSS.='#select_1, #select_2, #select_3, #select_4, #select_5, #select_6, #select_7, #select_8, #select_9, #select_10, #select_11, #vacio{
	                   width:80px;}';
            $contentCSS.='</style>';
        }
        echo $contentCSS;
    }

//fin cssselect
    /**
     * Esta funcion te permite imprimir un select vacio con su id.
     *
     * Asi.  &lt;select id="vacio"&gt;&lt;/select&gt;
     *
     */

    function SelectVacio($nombre = null, $id = null) {
        if ($nombre != null && $id != null) {
            echo '<select id="' . $id . '" name="' . $nombre . '"></select>';
        } else if ($nombre != null) {
            echo '<select id="vacio" name="' . $nombre . '"></select>';
        } else {
            echo '<select id="vacio"></select>';
        }
    }

//fin selectVacio


    /**
     * Esta funcion te permite Manipular el array de la consulta y le agrega el cero.
     * para dar formatos asi  01,02,03....
     * a la funcion se le pasa el nombre de la variable que se le va hacer el set y el valor,
     * en este caso seria un array   AddCero('ejemploVar',$vector, $extra=null)
     *
     */

    /**
     * Esta funcion permite convertir o dar formato al monto.
     * Ejemplo: si ingresan 145.000,00 lo lleva a 145000.00
     * con ese formtato puedes dividir el monto y no tendra problema alguno.
     * luego para llevarlo al formato original se utiliza la funcion Formato2
     *
     */
    function Formato1($monto) {
        $aux = $monto . '';
        for ($i = 0; $i < strlen($aux); $i++) {
            if ($aux[$i] == ',') {
                if (isset($aux[$i + 3])) {
                    if ($aux[$i + 3] == '5') {
                        $monto += 0.001;
                        break;
                    }
                }
            }
        }//fin
        $monto = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $monto));
        if (substr($monto, -3, 1) == '.') {
            $sents = '.' . substr($monto, -2);
            $monto = substr($monto, 0, strlen($monto) - 3);
        } elseif (substr($monto, -2, 1) == '.') {
            $sents = '.' . substr($monto, -1);
            $monto = substr($monto, 0, strlen($monto) - 2);
        } else {
            $sents = '.00';
        }
        $monto = preg_replace("/[^0-9]/", "", $monto);
        $var = number_format($monto . $sents, 2, '.', '');
        return $var;
    }


    function formato_precio2($monto){
        $monto= str_replace(',', '', $monto);
        $monto=number_format($monto,2,",",".");
        return $monto;
    }

    function Formato2($monto) {

        // $monto =  sprintf("%01.4f",$monto);
        $aux = $monto . '';
        $monto = sprintf("%01.3f", $monto);
        for ($i = 0; $i < strlen($aux); $i++) {
            if ($aux[$i] == '.') {
                if (isset($aux[$i + 3])) {
                    if ($aux[$i + 3] == '5') {
                        $monto += 0.001;
                        break;
                    }
                }
            }
        }//fin for



        $var = number_format($monto, 2, ",", ".");
        return $var;
    }

//fin function

    /**
     * Esta funcion permite convertir o dar formato al monto.
     * Ejemplo: si ingresan 145000.00 lo lleva a 145.000,00
     *
     */
    function AddCero($vector = object, $extra = null) {
        if ($extra == null) {
            foreach ($vector as $x) {
                if ($x < 10) {
                    $Var[$x] = "0" . $x;
                } else {
                    $Var[$x] = $x;
                }
            }//fin each
        } else {
            foreach ($vector as $x) {
                if ($x < 10) {
                    $Var[$x] = $extra . ".0" . $x;
                } else {
                    $Var[$x] = $extra . "." . $x;
                }
            }//fin each
        }
        return $Var;
    }

//fin AddCero


function AddCero2($numero=null,$extra=null){

   	  if($extra==null){
   	  	  if($numero<10){
        	   $numero="0".$numero;
        	}else{
	           $numero;
        	}
   	  }else{
        	if($numero<10){
        	   $numero=$extra.".".$numero;
        	}else{
	           $numero=$extra.".".$numero;
        	}

   	  }
	    return $numero;
   }//fin AddCero2

    function AddCero3($numero, $extra = null) {
        if ($numero < 10) {
            $numero = '00' . $numero;
        }
        if ($numero >= 10 && $numero <= 99) {
            $numero = '0' . $numero;
        }
        return $numero;
    }

//fin AddCero3

    function AddCero4($numero, $extra = null) {
        if ($numero < 10) {
            $numero = '000' . $numero;
        }
        if ($numero >=10 && $numero <= 99) {
            $numero = '00' . $numero;
        }
        if ($numero > 99 && $numero <= 999) {
            $numero = '0' . $numero;
        }

        return $numero;
    }

//fin AddCero4
//esta funcion agrega cero a una sola variable
    function add_c_c($var) {
        if ($var <= 9 && strlen($var) == 1) {
            $codigo = '0' . $var;
        } else {
            $codigo = $var;
        }
        return $codigo;
    }

//fin AddCero
    /**
     * la
     */

    function radioTagRemote($name = null, $campos = null, $options = array(), $inbetween = null, $selecion = null) {

        $this->Html->setFormTag($name);
        $aux = '';

        $options['type'] = 'click';
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['id'])) {
            $options['id'] = 'radio';
            $aux = 'radio';
        } else {
            $aux = $options['id'];
        }
        if (!isset($options['onKeyPress'])) {
            $options['onKeyPress'] = '';
        }
        if (!isset($options['onChange'])) {
            $options['onChange'] = '';
        }
        if (!isset($options['onFocus'])) {
            $options['onFocus'] = '';
        }
        if (!isset($options['onBlur'])) {
            $options['onBlur'] = '';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '30';
        }
        if (!isset($options['value'])) {
            $options['value'] = '';
        }
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['onClick'])) {
            $options['onClick'] = '';
        }


        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        foreach ($campos as $optValue => $optTitle) {
            $options['id'] = $aux . '_' . $optValue;
            if ($selecion != $optValue) {
                $read = "";
            } else {
                $read = "checked";
            }
            echo'<input type="radio" name="data[' . $this->Html->model . '][' . $this->Html->field . ']"  onKeyPress="' . $options ['onKeyPress'] . '"   onFocus="' . $options ['onFocus'] . '"  onBlur="' . $options ['onBlur'] . '"  onClick="' . $options ['onClick'] . '"    class="' . $options ['class'] . '"    style="' . $options ['style'] . '"    ' . $options['disabled'] . '   id="' . $options['id'] . '" value="' . $optValue . '"  ' . $read . '><label  style="' . $options ['style'] . '"  for="' . $options['id'] . '" >' . $optTitle . '</label> ' . $inbetween . '';
            Helper::return_helpers();
            for ($i = 1; $i <= 10; $i++) {
                if (isset($options['update' . $i])) {
                    $options['update'] = $options['update' . $i];
                    $options['url'] = $options['url' . $i] . '/' . $optValue . '';
                    $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
                    echo $script;
                }//FIN IF
            }//FIN FOR
        }//fin for
    }

//fin funcion

    function radio_consolidado($name = null, $options = array(), $inbetween = null, $texto = false, $extra = '', $tipo_consolidado = null) {


       $this->Html->setFormTag($name);
        $aux = '';
        $campos = array('1' => 'Instituci&oacute;n', '2' => 'Dependencia');
        $selecion = 1;

        if($extra != ''){
            $campos['3'] = $extra;
        }
        $options['type'] = 'click';
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['id'])) {
            $options['id'] = 'radio';
            $aux = 'radio';
        } else {
            $aux = $options['id'];
        }
        if (!isset($options['onKeyPress'])) {
            $options['onKeyPress'] = '';
        }
        if (!isset($options['onChange'])) {
            $options['onChange'] = '';
        }
        if (!isset($options['onFocus'])) {
            $options['onFocus'] = '';
        }
        if (!isset($options['onBlur'])) {
            $options['onBlur'] = '';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '30';
        }
        if (!isset($options['value'])) {
            $options['value'] = '';
        }
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['onClick'])) {
            $options['onClick'] = '';
        }


        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin



        if ($tipo_consolidado == null) {
            $_SESSION['cod_dep_reporte_consolidado'] = $_SESSION['SScoddep'];
        } else {
            $_SESSION['cod_dep_reporte_consolidado'] = $_SESSION['SScoddeporig'];
        }



        $_SESSION['consolidado_select_ventas_dependencia'] = 1;

        if ($_SESSION['SScoddep'] == 1 && $_SESSION["SScoddeporig"] == 1) {
            $_SESSION['cod_dep_reporte_consolidado'] = 1;

            echo "<table border='0'>";

            if ($texto == true) {

                echo "<tr>";
                echo "<td align='center'>";
                echo "<b>CONSOLIDADO POR</b>";
                echo "</td>";
                echo "</tr>";
            }//fin if


            echo "<tr>";
            echo "<td align='center'>";

            foreach ($campos as $optValue => $optTitle) {
                $contar = 0;
                $options['id'] = $aux . '_' . $optValue;
                if ($selecion != $optValue) {
                    $read = "";
                } else {
                    $read = "checked";
                }
                echo'<input type="radio" name="data[' . $this->Html->model . '][' . $this->Html->field . ']"  onKeyPress="' . $options ['onKeyPress'] . '"   onFocus="' . $options ['onFocus'] . '"  onBlur="' . $options ['onBlur'] . '"  onClick="' . $options ['onClick'] . '"    class="' . $options ['class'] . '"    style="' . $options ['style'] . '"    ' . $options['disabled'] . '   id="' . $options['id'] . '" value="' . $optValue . '"  ' . $read . '><label for="' . $options['id'] . '">' . $optTitle . '</label> ' . $inbetween . '';
                Helper::return_helpers();
                for ($i = 1; $i <= 10; $i++) {
                    if (isset($options['update' . $i])) {
                        $contar++;
                        $options['update'] = $options['update' . $i];
                        $options['url'] = $options['url' . $i] . '/' . $optValue . '';
                        $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
                        echo $script;
                    }//FIN IF
                }//FIN FOR


                $options['update'] = "capa_carga_sesion";
                
                if($_SESSION['tipo_reporte'] == 'reporte_balance_ejecucion'){
                    $options['url'] = "/select_ventas_dependencia/index" . '/' . $optValue . '/'.$_SESSION['tipo_reporte'];
                }else{
                    $options['url'] = "/select_ventas_dependencia/index" . '/' . $optValue . '';
                }

                
                $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
                echo $script;
            }//fin for

              
            echo "<br><br>";
            echo "<div id='capa_carga_sesion'></div>";

            echo "</td>";
            echo "</tr>";
            echo "</table>";
        }//fin if
    }

//fin funcion

    function wFile_helper_sisap($name = null, $msg = null, $mode = 'w', $filename = null, $leerarchivo = false) {
        if (!class_exists('File')) {
            uses('file');
        }
        if ($msg != null) {

            if ($filename == null) {
                $filename = "../webroot/descargas/" . $name;
            } else {
                $filename .= "/" . $name;
            }

            $file = new File($filename);

            if ($leerarchivo == true) {
                if (file_exists($filename)) {
                    $file_leer_candena = $file->read() . " \n ";
                } else {
                    $file_leer_candena = "";
                }
            } else {
                $file_leer_candena = "";
            }

            return $file->write($file_leer_candena . "" . $msg, $mode);
        } else {
            return false;
        }
    }

//fin function

    function submit_ajax_pdf($url = null, $update = 'funcion_capa_pdf_ajax_1', $tipo = 1, $funcion = null) {

        if ($tipo == 1) {
            echo $this->submitTagRemote('generar', array("funcion" => "abre_ventana_pdf", 'url1' => $url, 'update1' => $update, 'id' => 'submit_pdf'));
        } else if ($tipo == 2) {
            echo $this->submitTagRemote('guardar', array("funcion" => $funcion, 'url1' => $url, 'update1' => $update, 'id' => 'submit_pdf'));
        }
    }

//fin funtion

    function ventana_info_contabilidad($dia = null, $mes = null, $año = null, $numero = null) {



        $options['type'] = 'click';

        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }

        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin

        $options['id'] = $dia . "_" . $mes . "_" . $año . "_" . $numero . "_" . rand();

        $url = "/ventana_info_contabilidad/index/" . $dia . "/" . $mes . "/" . $año . "/" . $numero . "/";
        $width_aux = "750px";
        $height_aux = "400px";
        $title_aux = "Asiento Contable";
        $resizable_aux = false;
        $maximizable_aux = false;
        $minimizable_aux = false;
        $closable_aux = false;

        if ($numero != '') {
            echo " <input type=\"text\" id='" . $options['id'] . "' onClick=\"codigo_ventana('" . $url . "', '" . $width_aux . "', '" . $height_aux . "', '" . $title_aux . "', '" . $resizable_aux . "', '" . $maximizable_aux . "', '" . $minimizable_aux . "', '" . $closable_aux . "')\"    class='" . $options ['class'] . "'    style='text-align:center;width:100%;cursor:pointer;' onMouseOver=\"new Effect.Highlight(this);\"   value='" . $numero . "'  readonly=\"readonly\">";
        } else {
            echo " <input type=\"text\" id=''     class='" . $options ['class'] . "'    style='text-align:center;width:100%;'   value='" . $numero . "'  readonly=\"readonly\">";
        }
//echo " <input type=\"text\" id='".$options['id']."' onClick=\"codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')\"    class='".$options ['class']."'    style='text-align:center;width:100%;cursor:pointer;' onMouseOver=\"new Effect.Highlight(this);\"   value='".$numero."'  readonly=\"readonly\">";
        echo "";
    }

//fin funcion

    function radio_nivel_consulta($lista_ano = array(), $seleccion_ano = null, $vector_presi = array(), $cod_presi_seleccion = null, $opcion = array()) {


        $aux = 'radio_nivel_consulta';
        $campos = array('1' => 'República', '2' => 'Estados', '3' => 'Tipo de Instituciones', '4' => 'Institución');
        $options['type'] = 'click';

        if (!isset($opcion["url"])) {
            $opcion["url"] = "";
        }
        if (!isset($opcion["update"])) {
            $opcion["update"] = "";
        }

        echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='tablacompromiso tablacompromiso2'>";
        echo "		<tr>
			<td width='20%' align='right' class='fila_titulos'>Año:</td>
			<td>";
        $this->selectTagRemote('datos/ano_consolidado', $lista_ano, array('value1' => 'todo', 'opcion1' => 'Todos'), $seleccion_ano, array("onchange1" => $opcion["url"], "update1" => $opcion["update"], 'id' => 'ano_estimacion', 'style' => 'width:80px', 'maxlength' => '8', true), null, true);
        echo "     </td>
		    </tr>";


        echo "		<tr>
			<td width='20%' align='right' class='fila_titulos'>Nivel de consulta:</td>
			<td align='left'>
";


        foreach ($campos as $optValue => $optTitle) {
            $contar = 0;
            $inbetween = "<br>";
            $options['id'] = $aux . '_' . $optValue;
            $read = "";

            if ($optValue != 1) {
                $read = "";
            } else {
                $read = "checked";
            }

            if ($optValue == "2") {
                $inbetween = "";
            }


            echo'<input type="radio" name="data[datos][radio_nivel_consulta]"  id="' . $options['id'] . '" value="' . $optValue . '"  ' . $read . '  ><label for="' . $options['id'] . '">' . $optTitle . '</label> ' . $inbetween . '';
            Helper::return_helpers();

            $options['update'] = "capa_carga_sesion";
            $options['url'] = "/select_nivel_consulta/index/" . $optValue . '';
            $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
            echo $script;
        }//fin for

        echo "

           </td>
		</tr>
	</table>

<br><br>

	<div id='capa_carga_sesion'>

	 <table width='25%' border='0' cellpadding='0' cellspacing='0' class='tablacompromiso tablacompromiso2' align='left'>
	  <tr>
	     <td align='center' class='fila_titulos'>República</td>
	  </tr>
	 <tr>
	     <td width='25%' id='n_select_1'>";
        $this->selectTagRemote('datos/cod_presi', $vector_presi, null, $cod_presi_seleccion, array('id' => 'select_1', 'onChange' => 'vacio', 'style' => 'width:100%', true), null, true);
        echo "   </td>
	 </tr>

</table>

<br><br><br>


	</div>

<br><br>

";
    }

//fin funcion

    function radio_nivel_consulta_alcaldia($lista_ano = array(), $seleccion_ano = null, $vector_presi = array(), $cod_presi_seleccion = null, $opcion = array()) {


        $aux = 'radio_nivel_consulta';
        $campos = array('1' => 'República', '2' => 'Estados', '3' => 'Tipo de Instituciones', '4' => 'Institución');
        $options['type'] = 'click';

        if (!isset($opcion["url"])) {
            $opcion["url"] = "";
        }
        if (!isset($opcion["update"])) {
            $opcion["update"] = "";
        }

        echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='tablacompromiso tablacompromiso2'>";
        echo "		<tr>
			<td width='20%' align='right' class='fila_titulos'>Año:</td>
			<td>";
        $this->selectTagRemote('datos/ano_consolidado', $lista_ano, array('value1' => 'todo', 'opcion1' => 'Todos'), $seleccion_ano, array("onchange1" => $opcion["url"], "update1" => $opcion["update"], 'id' => 'ano_estimacion', 'style' => 'width:80px', 'maxlength' => '8', true), null, true);
        echo "     </td>
		    </tr>";


        echo "		<tr>
			<td width='20%' align='right' class='fila_titulos'>Nivel de consulta:</td>
			<td align='left'>
";


        foreach ($campos as $optValue => $optTitle) {
            $contar = 0;
            $inbetween = "<br>";
            $options['id'] = $aux . '_' . $optValue;
            $read = "";

            if ($optValue != 1) {
                $read = "";
            } else {
                $read = "checked";
            }

            if ($optValue == "2") {
                $inbetween = "";
            }


            echo'<input type="radio" name="data[datos][radio_nivel_consulta]"  id="' . $options['id'] . '" value="' . $optValue . '"  ' . $read . '  ><label for="' . $options['id'] . '">' . $optTitle . '</label> ' . $inbetween . '';
            Helper::return_helpers();

            $options['update'] = "capa_carga_sesion";
            $options['url'] = "/select_nivel_consulta_alcaldia/index/" . $optValue . '';
            $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
            echo $script;
        }//fin for

        echo "

           </td>
		</tr>
	</table>

<br><br>

	<div id='capa_carga_sesion'>

	 <table width='25%' border='0' cellpadding='0' cellspacing='0' class='tablacompromiso tablacompromiso2' align='left'>
	  <tr>
	     <td align='center' class='fila_titulos'>República</td>
	  </tr>
	 <tr>
	     <td width='25%' id='n_select_1'>";
        $this->selectTagRemote('datos/cod_presi', $vector_presi, null, $cod_presi_seleccion, array('id' => 'select_1', 'onChange' => 'vacio', 'style' => 'width:100%', true), null, true);
        echo "   </td>
	 </tr>

</table>

<br><br><br>


	</div>

<br><br>

";
    }

//fin funcion

    function CssSelect_global($width = null) {
        if (isset($width)) {
            $contentCSS = '<style type="text/css">';
            $contentCSS.='select{width:' . $width . 'px;}';
            $contentCSS.='</style>';
        } else {
            $contentCSS = '<style type="text/css">';
            $contentCSS.='select{ width:80px;}';
            $contentCSS.='</style>';
        }
        echo $contentCSS;
    }

//fin cssselect

    function inputValidaCodigo($name = null, $tabla = null, $codigos = array(), $options = array(), $num = false) {


        $this->Html->setFormTag($name);


        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $html_options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $html_options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        $url = '/administradors/valida_codigo/' . $tabla;

        $codigos_aux = $codigos;

        foreach ($codigos as $cod_campo => $cod_valor) {
            if ($cod_campo != "") {
                $url .= "/" . $cod_campo;
            }
            if ($cod_valor != "") {
                $url .= "/" . $cod_valor;
            }
        }

        if ($num == false) {
            $retur = 'return solonumeros(event);';
        } else {
            $retur = '';
            $options['maxlength'] = '';
        }

        $options['id'] = 'valida';
        if (!isset($options['size'])) {
            $options['size'] = '3';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '2';
        }

        echo '<input value="" type="text" size="' . $options ['size'] . '" name="data[' . $this->Html->model . '][' . $this->Html->field . ']" id="' . $options ['id'] . '" onKeyPress="' . $retur . '"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '" style="' . $options['style'] . '" ' . $options['readonly'] . ' />';
        echo '<div id="aux" style="display:none;">
						<input type="hidden" name="existe" value="nada"  id="existe"/>
						<input type="hidden" name="aux_codigo" value=""  id="aux_codigo"/>
						<input type="hidden" name="aux_existe" value=""  id="aux_existe"/>
     </div>';


        $options['update'] = 'valida_codigo';
        $options['url'] = $url;
        $optValue = $options['id'];



        /* $options['type'] = 'KeyPress';
          echo  $this->Javascript->event("'{$optValue}'", "KeyPress", $this->Ajax->remoteFunction($options));
          $options['type'] = 'Keyup';
          echo  $this->Javascript->event("'{$optValue}'", "keyup", $this->Ajax->remoteFunction($options));
          $options['type'] = 'keydown';
          echo  $this->Javascript->event("'{$optValue}'", "keydown", $this->Ajax->remoteFunction($options)); */

        $options['type'] = 'focus';
        echo $this->Javascript->event("'{$optValue}'", "focus", $this->Ajax->remoteFunction($options));
        $options['type'] = 'blur';
        echo $this->Javascript->event("'{$optValue}'", "blur", $this->Ajax->remoteFunction($options));
    }

//fin function

    function inputValidaCodigo2($name = null, $tabla = null, $codigos = array(), $options = array(), $num = false) {


        $this->Html->setFormTag($name);

        $url = '/administradors/valida_codigo2/' . $tabla;

        $codigos_aux = $codigos;





        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin





        foreach ($codigos as $cod_campo => $cod_valor) {
            if ($cod_campo != "") {
                $url .= "/" . $cod_campo;
            }
            if ($cod_valor != "") {
                $url .= "/" . $cod_valor;
            }
        }

        if ($num == false) {
            $retur = 'return solonumeros(event);';
        } else {
            $retur = '';
            $options['maxlength'] = '';
        }

        $options['id'] = 'valida';
        if (!isset($options['size'])) {
            $options['size'] = '3';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '';
        }

        echo '<input value="" type="text" size="' . $options ['size'] . '" name="data[' . $this->Html->model . '][' . $this->Html->field . ']" id="' . $options ['id'] . '" onKeyPress="' . $retur . '"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '"  ' . $options['readonly'] . ' />';
        echo '<div id="aux" style="display:none;">
						<input type="hidden" name="existe" value="nada"  id="existe"/>
						<input type="hidden" name="aux_codigo" value=""  id="aux_codigo"/>
						<input type="hidden" name="aux_existe" value=""  id="aux_existe"/>
     </div>';
        $options['update'] = 'valida_codigo';
        $options['url'] = $url;
        $optValue = $options['id'];


        /* $options['type'] = 'KeyPress';
          echo  $this->Javascript->event("'{$optValue}'", "KeyPress", $this->Ajax->remoteFunction($options));
          $options['type'] = 'Keyup';
          echo  $this->Javascript->event("'{$optValue}'", "keyup", $this->Ajax->remoteFunction($options));
          $options['type'] = 'keydown';
          echo  $this->Javascript->event("'{$optValue}'", "keydown", $this->Ajax->remoteFunction($options)); */

        $options['type'] = 'focus';
        echo $this->Javascript->event("'{$optValue}'", "focus", $this->Ajax->remoteFunction($options));
        $options['type'] = 'blur';
        echo $this->Javascript->event("'{$optValue}'", "blur", $this->Ajax->remoteFunction($options));
    }

//fin function
//INPUT_TAG_REMOTE

    function inputTagRemote($name = null, $options = array()) {

        Helper::return_helpers();
        $this->Html->setFormTag($name);


        if (!isset($options['id'])) {
            $options['id'] = $name;
        }
        if (!isset($options['size'])) {
            $options['size'] = '3';
        }
        if (!isset($options['onKeyPress'])) {
            $options['onKeyPress'] = '';
        }
        if (!isset($options['onChange'])) {
            $options['onChange'] = '';
        }
        if (!isset($options['onFocus'])) {
            $options['onFocus'] = '';
        }
        if (!isset($options['onBlur'])) {
            $options['onBlur'] = '';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['style'])) {
            $options['style'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '30';
        }
        if (!isset($options['value'])) {
            $options['value'] = '';
        }
        if (!isset($options['disabled'])) {
            $options['disabled'] = '';
        }
        if (!isset($options['type'])) {
            $options['type'] = 'text';
        }
        if (!isset($options['autocomplete'])) {
            $options['autocomplete'] = 'off';
        }


        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin



        echo '<input value="' . $options['value'] . '" type="' . $options['type'] . '"  style="' . $options ['style'] . '"  ' . $options['disabled'] . '  size="' . $options ['size'] . '" name="data[' . $this->Html->model . '][' . $this->Html->field . ']" id="' . $options ['id'] . '" onKeyPress="' . $options['onKeyPress'] . '"  onBlur="' . $options['onBlur'] . '"  onFocus="' . $options['onFocus'] . '" onChange="' . $options ['onChange'] . '"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '" autocomplete="' . $options['autocomplete'] . '"  ' . $options['readonly'] . ' />';


        echo '<div id="aux" style="display:none;">
						<input type="hidden" name="existe" value="nada"  id="existe"/>
						<input type="hidden" name="aux_codigo" value=""  id="aux_codigo"/>
						<input type="hidden" name="aux_existe" value=""  id="aux_existe"/>
     </div>';



        if (isset($options['update'])) {
            $options['update'] = $options['update'];
            $options['url'] = $options['url'];
            $optValue = $options['id'];
            $options['type'] = 'change';
            echo $this->Javascript->event("'{$optValue}'", $this->Ajax->ajax_remote("change", $this->http_use), $this->Ajax->remoteFunction($options));
        }//fin if




        for ($i = 1; $i <= 10; $i++) {
            if (isset($options['update' . $i])) {
                $options['update'] = $options['update' . $i];
                $options['url'] = $options['url' . $i];
                $options['type'] = 'change';

                $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("change", $this->http_use), $this->Ajax->remoteFunction($options));
                echo $script;
            }//FIN IF
        }//fin for
    }

//fin function

    function inputValidaPuesto($name = null, $options = array()) {


        $this->Html->setFormTag($name);
        if (isset($options['value'])) {
            $val = $options['value'][0] . $options['value'][1] . $options['value'][2];
            $url = '/administradors/valida_puesto/' . $val;
        } else {
            $url = '/administradors/valida_puesto';
        }




        if (!isset($options['loading'])) {
            $options['loading'] = "Element.show('mini_loading');";
            $options['loading'] = $options['loading'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin


        if (!isset($options['complete'])) {
            $html_options['complete'] = "Element.hide('mini_loading');";
            $options['complete'] = $html_options['complete'];
            //htmlOptions['onclick'] = 'return false; ';
        }//fin



        $options['id'] = 'valida';
        $optValue = $options['id'];
        if (!isset($options['size'])) {
            $options['size'] = '3';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '2';
        }


        echo '<input value="" type="text" size="' . $options ['size'] . '" name="data[' . $this->Html->model . '][' . $this->Html->field . ']" id="' . $options ['id'] . '" onKeyPress="return solonumeros(event);"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '"  ' . $options['readonly'] . ' autocomplete="off" />';
        echo '<div id="aux" style="display:none;">
						<input type="hidden" name="existe" value="nada"  id="existe"/>
						<input type="hidden" name="aux_codigo" value=""  id="aux_codigo"/>
						<input type="hidden" name="aux_existe" value=""  id="aux_existe"/>
     </div>';


        for ($i = 1; $i <= 10; $i++) {
            if (isset($options['update' . $i])) {

                $options['update'] = $options['update' . $i];
                $options['url'] = $options['url' . $i];
                $options['type'] = 'keydown';
                echo $this->Javascript->event("'{$optValue}'", "keydown", $this->Ajax->remoteFunction($options));
                $options['type'] = 'KeyPress';
                echo $this->Javascript->event("'{$optValue}'", "KeyPress", $this->Ajax->remoteFunction($options));
                $options['type'] = 'Keyup';
                echo $this->Javascript->event("'{$optValue}'", "keyup", $this->Ajax->remoteFunction($options));
            }
        }

        $options['update'] = 'valida_codigo';
        $options['url'] = $url;


        $options['type'] = 'KeyPress';
        echo $this->Javascript->event("'{$optValue}'", "KeyPress", $this->Ajax->remoteFunction($options));
        $options['type'] = 'Keyup';
        echo $this->Javascript->event("'{$optValue}'", "keyup", $this->Ajax->remoteFunction($options));
        /* $options['type'] = 'keydown';
          echo  $this->Javascript->event("'{$optValue}'", "keydown", $this->Ajax->remoteFunction($options)); */
    }

//fin function

    function inputValidaClase($name = null, $options = array()) {

        Helper::return_helpers();
        $this->Html->setFormTag($name);
        if (isset($options['value'])) {
            $val = $options['value'][0] . $options['value'][1] . $options['value'][2];
            $url = '/administradors/valida_clase/' . $val;
        } else {
            $url = '/administradors/valida_clase';
        }



        if (isset($options['changue'])) {
            $options['update'] = $options['update_c'];
            $options['url'] = $options['changue'];
            $optValue = $options['id'];
            $options['type'] = 'change';
            echo $this->Javascript->event("'{$optValue}'", $this->Ajax->ajax_remote("change", $this->http_use), $this->Ajax->remoteFunction($options));
        }//fin if



        $options['id'] = 'valida';
        $optValue = $options['id'];
        if (!isset($options['size'])) {
            $options['size'] = '5';
        }
        if (!isset($options['readonly'])) {
            $options['readonly'] = '';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        if (!isset($options['maxlength'])) {
            $options['maxlength'] = '2';
        }


        echo '<input value="" type="text" size="' . $options ['size'] . '" style="text-align:center;" name="data[' . $this->Html->model . '][' . $this->Html->field . ']" id="' . $options ['id'] . '" onKeyPress="return solonumeros(event);"  maxlength="' . $options['maxlength'] . '" class="' . $options['class'] . '"  ' . $options['readonly'] . '  autocomplete="off" />';
        echo '<div id="aux" style="display:none;">
						<input type="hidden" name="existe" value="nada"  id="existe"/>
						<input type="hidden" name="aux_codigo" value=""  id="aux_codigo"/>
						<input type="hidden" name="aux_existe" value=""  id="aux_existe"/>
     </div>';

        for ($i = 1; $i <= 10; $i++) {
            if (isset($options['update' . $i])) {

                $options['update'] = $options['update' . $i];
                $options['url'] = $options['url' . $i];
                //$options['type'] = 'keydown';
                //echo  $this->Javascript->event("'{$optValue}'", "keydown", $this->Ajax->remoteFunction($options));
                //$options['type'] = 'KeyPress';
                //	echo  $this->Javascript->event("'{$optValue}'", "KeyPress", $this->Ajax->remoteFunction($options));
                $options['type'] = 'Keyup';
                echo $this->Javascript->event("'{$optValue}'", "keyup", $this->Ajax->remoteFunction($options));
            }
        }

        /*
          $options['update'] = 'valida_codigo';
          $options['url'] = $url;


          $options['type'] = 'KeyPress';
          echo  $this->Javascript->event("'{$optValue}'", "KeyPress", $this->Ajax->remoteFunction($options));
          $options['type'] = 'Keyup';
          echo  $this->Javascript->event("'{$optValue}'", "keyup", $this->Ajax->remoteFunction($options));
          $options['type'] = 'keydown';
          echo  $this->Javascript->event("'{$optValue}'", "keydown", $this->Ajax->remoteFunction($options)); */
    }

//fin function

    function mensajes_codigo_valida($mensaje, $activa) {

        echo "<script type=text/javascript>";
        echo "var mensaje = '" . $mensaje . "';";
        echo "fun_msj3(mensaje, " . $activa . ");";
        echo"</script>";
    }

    function ant_sig($op) {
        if ($op) {
            $opcion1 = " ";
        } else {
            $opcion1 = 'disabled';
        }
        return $opcion1;
    }

//fin VarNav

    function Formato2_3($monto) {
        //str_replace('.', ',', $monto);
        //$monto = preg_replace("/[^0-9\.]/", "", $monto);
        //$monto=number_format($monto, 3,".","");

        $ultimo = strlen($monto);
        $dato = substr($monto, $ultimo - 1, 1);

        if ($dato == 0) {
            return number_format($monto, 2, ',', '.');
        } else {
            return number_format($monto, 3, ',', '.');
        }
    }

    function Formato($price) {
        $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
        if (substr($price, -3, 1) == '.') {
            $sents = '.' . substr($price, -2);
            $price = substr($price, 0, strlen($price) - 3);
        } elseif (substr($price, -2, 1) == '.') {
            $sents = '.' . substr($price, -1);
            $price = substr($price, 0, strlen($price) - 2);
        } else {
            $sents = '.00';
        }
        $price = preg_replace("/[^0-9]/", "", $price);
        return number_format($price . $sents, 2, '.', '');
    }

    function Formato_6($price) {
        $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
        if (substr($price, -7, 1) == '.') {
            $sents = '.' . substr($price, -6);
            $price = substr($price, 0, strlen($price) - 7);
        } elseif (substr($price, -6, 1) == '.') {
            $sents = '.' . substr($price, -1);
            $price = substr($price, 0, strlen($price) - 6);
        } else {
            $sents = '.000000';
        }

        if ($sents == ".000000") {
            echo $price;
        } else {
            $price = preg_replace("/[^0-9]/", "", $price);
            $var = number_format($price . $sents, 6, '.', '');
            $var = str_replace('.', ',', $var);
            return $var;
        }//fin else
    }

//fin function

    function Formato_6_out($price) {
        $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
        if (substr($price, -7, 1) == '.') {
            $sents = '.' . substr($price, -6);
            $price = substr($price, 0, strlen($price) - 7);
        } elseif (substr($price, -6, 1) == '.') {
            $sents = '.' . substr($price, -1);
            $price = substr($price, 0, strlen($price) - 6);
        } else {
            $sents = '.000000';
        }

        if ($sents == ".000000") {
            return $price;
        } else {
            $price = preg_replace("/[^0-9]/", "", $price);
            $var = number_format($price . $sents, 6, '.', '');
            $var = str_replace('.', ',', $var);
            return $var;
        }//fin else
    }

//fin function

    function Formato_3_out($price) {
        $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
        if (substr($price, -4, 1) == '.') {
            $sents = '.' . substr($price, -3);
            $price = substr($price, 0, strlen($price) - 4);
        } elseif (substr($price, -3, 1) == '.') {
            $sents = '.' . substr($price, -1);
            $price = substr($price, 0, strlen($price) - 3);
        } else {
            $sents = '.000';
        }

        if ($sents == ".000") {
            return number_format($price, 3, ',', '.');
        } else {
            $price = preg_replace("/[^0-9]/", "", $price);
            return number_format($price . $sents, 3, ',', '.');
        }//fin else
    }

//fin function

    function Formato_3_in($monto) {
        $monto = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $monto));
        if (substr($monto, -4, 1) == '.') {
            $sents = '.' . substr($monto, -3);
            $monto = substr($monto, 0, strlen($monto) - 4);
        } elseif (substr($monto, -3, 1) == '.') {
            $sents = '.' . substr($monto, -1);
            $monto = substr($monto, 0, strlen($monto) - 4);
        } else {
            $sents = '.000';
        }
        $monto = preg_replace("/[^0-9]/", "", $monto);
        return number_format($monto . $sents, 3, '.', '');
    }

    function Formato_6input($price) {
        $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
        if (substr($price, -7, 1) == '.') {
            $sents = '.' . substr($price, -6);
            $price = substr($price, 0, strlen($price) - 7);
        } elseif (substr($price, -6, 1) == '.') {
            $sents = '.' . substr($price, -1);
            $price = substr($price, 0, strlen($price) - 6);
        } else {
            $sents = '.000000';
        }

        if ($sents == ".000000") {
            return $price;
        } else {
            $price = preg_replace("/[^0-9]/", "", $price);
            $var = number_format($price . $sents, 6, '.', '');
            $var = str_replace('.', ',', $var);
            return $var;
        }//fin else
    }

//fin function

    function cambia_fecha($var = null) {


        $fecha = $var;
        $mes = '';
        $year = '';
        if ($fecha != '') {
            $year = $fecha[0] . $fecha[1] . $fecha[2] . $fecha[3];
            $mes = $fecha[5] . $fecha[6];
            $dia = $fecha[8] . $fecha[9];
            $var = $dia . '/' . $mes . '/' . $year;



            if ($var == "01/01/1900") {
                $var = "00/00/000";
            }

            return $var;
        }
    }

//fin function

    function mascara_dos($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '0' . $var1;
                }break;
            case '2';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function mascara_tres($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '00' . $var1;
                }break;
            case '2';
                {
                    $var1 = '0' . $var1;
                }break;
            case '3';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function mascara_cuatro($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '000' . $var1;
                }break;
            case '2';
                {
                    $var1 = '00' . $var1;
                }break;
            case '3';
                {
                    $var1 = '0' . $var1;
                }break;
            case '4';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function mascara_cinco($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '0000' . $var1;
                }break;
            case '2';
                {
                    $var1 = '000' . $var1;
                }break;
            case '3';
                {
                    $var1 = '00' . $var1;
                }break;
            case '4';
                {
                    $var1 = '0' . $var1;
                }break;
            case '5';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function mascara_seis($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '00000' . $var1;
                }break;
            case '2';
                {
                    $var1 = '0000' . $var1;
                }break;
            case '3';
                {
                    $var1 = '000' . $var1;
                }break;
            case '4';
                {
                    $var1 = '00' . $var1;
                }break;
            case '5';
                {
                    $var1 = '0' . $var1;
                }break;
            case '6';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function mascara_siete($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '000000' . $var1;
                }break;
            case '2';
                {
                    $var1 = '00000' . $var1;
                }break;
            case '3';
                {
                    $var1 = '0000' . $var1;
                }break;
            case '4';
                {
                    $var1 = '000' . $var1;
                }break;
            case '5';
                {
                    $var1 = '00' . $var1;
                }break;
            case '6';
                {
                    $var1 = '0' . $var1;
                }break;
            case '7';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function mascara_ocho($var1) {

        $var = strlen($var1);
        switch ($var) {
            case '1';
                {
                    $var1 = '0000000' . $var1;
                }break;
            case '2';
                {
                    $var1 = '000000' . $var1;
                }break;
            case '3';
                {
                    $var1 = '00000' . $var1;
                }break;
            case '4';
                {
                    $var1 = '0000' . $var1;
                }break;
            case '5';
                {
                    $var1 = '000' . $var1;
                }break;
            case '6';
                {
                    $var1 = '00' . $var1;
                }break;
            case '7';
                {
                    $var1 = '0' . $var1;
                }break;
            case '8';
                {
                    $var1 = '' . $var1;
                }break;
        }//fin

        return $var1;
    }

//fin funtion

    function Formato_redondear_input($price = null) {

        $sents = "";
        $price2 = "";
        $op = 0;

        $porcentaje = "";

        if ($price != null && $price != 0) {

            for ($i = 0; $i <= strlen($price); $i++) {
                if ($price[$i] == ".") {
                    $op = $i;
                    break;
                } else {
                    if ($price[$i] != "%" && $price[$i] != " ") {
                        $price2 .= $price[$i];
                    } else {
                        $porcentaje = 'si';
                    }
                }
            }//fin for
            for ($j = $op; $j < strlen($price); $j++) {
                if ($price[$j] != "%" && $price[$j] != " ") {
                    $sents .= $price[$j];
                } else {
                    $porcentaje = 'si';
                }
            }


            if ($sents == ".0" || $sents == ".00" || $sents == ".000" || $sents == ".0000" || $sents == ".00000" || $sents == ".000000") {

                if ($porcentaje == "si") {
                    $price2 = $price2 . " " . "%";
                }


                return $price2;
            } else {

                if ($porcentaje == "si") {
                    $sents = $sents . " " . "%";
                } else {
                    $price2 = $price2;
                }

               if (substr($sents,2,5)=="00000"){
               $sents=substr($sents,0,2);}else
               if (substr($sents,3,4)=="0000"){
               $sents=substr($sents,0,3);}else
               if (substr($sents,4,3)=="000"){
               $sents=substr($sents,0,4);}else
               if (substr($sents,5,2)=="00"){
               $sents=substr($sents,0,5);}

                return $price2 . $sents;
            }//fin else
        } else {

            return $price;
        }//fin else
    }

//fin function

    function Formato_redondear($price) {

        $sents = "";
        $price2 = "";
        $op = 0;


        if ($price != null && $price != 0) {

            for ($i = 0; $i <= strlen($price); $i++) {
                if ($price[$i] == ".") {
                    $op = $i;
                    break;
                } else {
                    $price2 .= $price[$i];
                }
            }//fin for
            for ($j = $op; $j < strlen($price); $j++) {
                $sents .= $price[$j];
            }


            if ($sents == ".0" || $sents == ".00" || $sents == ".000" || $sents == ".0000" || $sents == ".00000" || $sents == ".000000") {
                echo $price2;
            } else {

                echo $price2 . $sents;
            }//fin else
        }//fin
    }

//fin function

    function zero($x = null) {
        if ($x != null) {
            if ($x < 10) {
                $x = "0" . $x;
            } else if ($x >= 10 && $x <= 99) {
                $x = $x;
            }
        }
        return $x;
    }

    function zeros($x = null) {
        if ($x != null) {
            if ($x < 10) {
                $x = "000" . $x;
            } else if ($x >= 10 && $x <= 99) {
                $x = "00" . $x;
            } else if ($x >= 100 && $x <= 999) {
                $x = "0" . $x;
            }
        }
        return $x;
    }

    function Porcentaje($monto = null) {
        return $monto . " %";
    }

    function dia($tagName, $value = null, $selected = null, $selectAttr = null, $optionAttr = null, $showEmpty = true) {
        if (empty($selected) && $this->Html->tagValue($tagName)) {
            $selected = date('d', strtotime($this->Html->tagValue($tagName)));
        }
        $dayValue = empty($selected) ? ($showEmpty == true ? NULL : date('d')) : $selected;
        $days = array('01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31');
        $option = $this->Html->selectTag($tagName . "", $days, $dayValue, $selectAttr, $optionAttr, $showEmpty);
        return $option;
    }

    //* Returns a SELECT element for years
    function year($tagName, $value = null, $minYear = null, $maxYear = null, $selected = null, $selectAttr = null, $optionAttr = null, $showEmpty = true) {
        if (empty($selected) && ($this->Html->tagValue($tagName))) {
            $selected = date('Y', strtotime($this->Html->tagValue($tagName)));
        }

        $yearValue = empty($selected) ? ($showEmpty ? NULL : date('Y')) : $selected;
        $currentYear = date('Y');
        $maxYear = is_null($maxYear) ? $currentYear + 11 : $maxYear + 1;
        $minYear = is_null($minYear) ? $currentYear - 60 : $minYear;

        if ($minYear > $maxYear) {
            $tmpYear = $minYear;
            $minYear = $maxYear;
            $maxYear = $tmpYear;
        }

        $minYear = $currentYear < $minYear ? $currentYear : $minYear;
        $maxYear = $currentYear > $maxYear ? $currentYear : $maxYear;

        for ($yearCounter = $minYear; $yearCounter < $maxYear; $yearCounter++) {
            $years[$yearCounter] = $yearCounter;
        }

        return $this->Html->selectTag($tagName . "", $years, $yearValue, $selectAttr, $optionAttr, $showEmpty);
    }

//
    function mes($tagName, $value = null, $selected = null, $selectAttr = null, $optionAttr = null, $showEmpty = true) {
        if (empty($selected) && ($this->Html->tagValue($tagName))) {
            $selected = date('m', strtotime($this->Html->tagValue($tagName)));
        }
        $monthValue = empty($selected) ? ($showEmpty ? NULL : date('m')) : $selected;
        $months = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Deciembre');

        return $this->Html->selectTag($tagName . "", $months, $monthValue, $selectAttr, $optionAttr, $showEmpty);
    }

    /**
     * Funcion para redimensionar las imagenes.
     *
     */
    function redimensionar($imagen, $largo, $mostrar = 1) {
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



        if ($datos[2] == 1) {
            $img = @imagecreatefromgif($nombre);
        }

        if ($datos[2] == 2) {
            $img = @imagecreatefromjpeg($nombre);
        }

        if ($datos[2] == 3) {
            $img = @imagecreatefrompng($nombre);
        }

        $ratio = ($datos[0] / $anchura);

        $altura = ($datos[1] / $ratio);

        if ($altura > $hmax) {
            $anchura2 = $hmax * $anchura / $altura;
            $altura = $hmax;
            $anchura = $anchura2;
        }

        $thumb = imagecreatetruecolor($anchura, $altura);

        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);


        // Creamos la Imagen (un JPG)
        if ($mostrar == 0) {
            // Guardamos Imagen en Directorio
            imagejpeg($thumb, $imagen . '_mini.jpg', 100);
            imagedestroy($thumb);
            return ($imagen . '_mini.jpg'); //devuelve ruta de la imagen creada
        } else {
            // Mostramos Imagen en el Navegador
            //header("Content-type: image/jpeg");
            return imagejpeg($thumb, '', 100);
        }
        imagedestroy($thumb);
    }

//fin funcion redimensionar
    /**
     * funcion cambia formato fecha.
     * $fecha= Fecha a cambiar
     * $tipo_return= formato como quieres que la devuelva
     * ej. ("02/08/1984","A-M-D") retorna => 1984-02-08
     *     ("1984-02-08","D-M-A") retorna => 02/08/1984
     */

    function Cfecha($fecha, $tipo_return) {
        if ($tipo_return == "A-M-D") {
            $paso = explode('/', $fecha);
            $fecha_aux[] = $paso[2];
            $fecha_aux[] = $paso[1];
            $fecha_aux[] = $paso[0];
            $fecha_return = implode('-', $fecha_aux);
        } else if ($tipo_return == "D/M/A") {
            $paso = explode('-', $fecha);
            $fecha_aux[] = $paso[2];
            $fecha_aux[] = $paso[1];
            $fecha_aux[] = $paso[0];
            $fecha_return = implode('/', $fecha_aux);
        }
        return $fecha_return;
    }




    function fecha_alfabetico($var=null, $var1=null) {

        $fecha = $var;
        $mes = '';
        $year = '';
        if ($fecha != '') {
            $year = $fecha[0] . $fecha[1] . $fecha[2] . $fecha[3];
            $mes  = $fecha[5] . $fecha[6];
            $dia  = $fecha[8] . $fecha[9];
        }//fin

        switch ($mes) {
            case"1": {
                    $var = "Enero     ";
                }break;
            case"2": {
                    $var = "Febrero   ";
                }break;
            case"3": {
                    $var = "Marzo     ";
                }break;
            case"4": {
                    $var = "Abril     ";
                }break;
            case"5": {
                    $var = "Mayo      ";
                }break;
            case"6": {
                    $var = "Junio     ";
                }break;
            case"7": {
                    $var = "Julio     ";
                }break;
            case"8": {
                    $var = "Agosto    ";
                }break;
            case"9": {
                    $var = "Septiembre";
                }break;
            case"10": {
                    $var = "Octubre   ";
                }break;
            case"11": {
                    $var = "Noviembre ";
                }break;
            case"12": {
                    $var = "Diciembre ";
                }break;
        }//fin

                                    $var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';// POR DEFECTO
   if ($var1=='0003' || $var1==3){  $var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//INDUSTRIAL (BANCO 0003)
   if ($var1=='0007' || $var1==7){  $var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BANFOANDES
   if ($var1=='0102' || $var1==102){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//VENEZUELA
   if ($var1=='0134' || $var1==134){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BANESCO
   if ($var1=='0163' || $var1==163){$var_aux = $dia . ' DE ' . $var . ' '. substr($year,2,2).'   ';}//TESORO
   if ($var1=='0191' || $var1==191){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BNC
   if ($var1=='0105' || $var1==105){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//MERCANTIL
   if ($var1=='0108' || $var1==108){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//PROVINCIAL
   if ($var1=='0114' || $var1==114){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BANCARIBE
   if ($var1=='0116' || $var1==116){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BOD
   if ($var1=='0125' || $var1==125){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//INDUSTRIAL (BANCO 0125)
   if ($var1=='0128' || $var1==128){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//CARONI
   if ($var1=='0147' || $var1==147){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BANORTE
   if ($var1=='0149' || $var1==149){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//PUEBLO SOBERANO
   if ($var1=='0151' || $var1==151){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//FONDOCOMUN
   if ($var1=='0156' || $var1==156){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//100% BANCO
   if ($var1=='0166' || $var1==166){$var_aux = $dia . ' DE ' . $var . ' '. $year.'   ';}//BANCO AGRICOLA VENEZUELA
   if ($var1=='0175' || $var1==175){$var_aux = $dia . ' DE ' . $var . ' '. substr($year,2,2).'   ';}//BICENTENARIO

   if ($var1==null){$var_aux = $dia . ' DE ' . $var . ' '. $year.' ';}// SIN ENTIDAD BANCARIA

        return $var_aux;
    }

//fin function




    function fecha_alfabetico_cheque($var=null) {



        $fecha = $var;
        $mes = '';
        $year = '';
        if ($fecha != '') {
            $year = $fecha[0] . $fecha[1] . $fecha[2] . $fecha[3];
            $mes = $fecha[5] . $fecha[6];
            $dia = $fecha[8] . $fecha[9];
        }//fin



        switch ($mes) {
            case"1": {
                    $var = "Enero     ";
                }break;
            case"2": {
                    $var = "Febrero   ";
                }break;
            case"3": {
                    $var = "Marzo     ";
                }break;
            case"4": {
                    $var = "Abril     ";
                }break;
            case"5": {
                    $var = "Mayo      ";
                }break;
            case"6": {
                    $var = "Junio     ";
                }break;
            case"7": {
                    $var = "Julio     ";
                }break;
            case"8": {
                    $var = "Agosto    ";
                }break;
            case"9": {
                    $var = "Septiembre";
                }break;
            case"10": {
                    $var = "Octubre   ";
                }break;
            case"11": {
                    $var = "Noviembre ";
                }break;
            case"12": {
                    $var = "Diciembre ";
                }break;
        }//fin

        $var_aux = $dia . ' DE ' . $var . '    ' . $year;


        return $var_aux;
    }

//fin function

    function unidad($numuero) {
        switch ($numuero) {
            case 9: {
                    $numu = "NUEVE";
                    break;
                }
            case 8: {
                    $numu = "OCHO";
                    break;
                }
            case 7: {
                    $numu = "SIETE";
                    break;
                }
            case 6: {
                    $numu = "SEIS";
                    break;
                }
            case 5: {
                    $numu = "CINCO";
                    break;
                }
            case 4: {
                    $numu = "CUATRO";
                    break;
                }
            case 3: {
                    $numu = "TRES";
                    break;
                }
            case 2: {
                    $numu = "DOS";
                    break;
                }
            case 1: {
                    $numu = "UN";
                    break;
                }
            case 0: {
                    $numu = "";
                    break;
                }
        }
        return $numu;
    }

// Función que permite específicar las letras correspondientes a las decenas de la cifra a convertir
    function decena($numdero) {
        if ($numdero >= 90 && $numdero <= 99) {
            $numd = "NOVENTA ";
            if ($numdero > 90)
                $numd = $numd . "Y " . ($this->unidad($numdero - 90));
        }
        else if ($numdero >= 80 && $numdero <= 89) {
            $numd = "OCHENTA ";
            if ($numdero > 80)
                $numd = $numd . "Y " . ($this->unidad($numdero - 80));
        }
        else if ($numdero >= 70 && $numdero <= 79) {
            $numd = "SETENTA ";
            if ($numdero > 70)
                $numd = $numd . "Y " . ($this->unidad($numdero - 70));
        }
        else if ($numdero >= 60 && $numdero <= 69) {
            $numd = "SESENTA ";
            if ($numdero > 60)
                $numd = $numd . "Y " . ($this->unidad($numdero - 60));
        }
        else if ($numdero >= 50 && $numdero <= 59) {
            $numd = "CINCUENTA ";
            if ($numdero > 50)
                $numd = $numd . "Y " . ($this->unidad($numdero - 50));
        }
        else if ($numdero >= 40 && $numdero <= 49) {
            $numd = "CUARENTA ";
            if ($numdero > 40)
                $numd = $numd . "Y " . ($this->unidad($numdero - 40));
        }
        else if ($numdero >= 30 && $numdero <= 39) {
            $numd = "TREINTA ";
            if ($numdero > 30)
                $numd = $numd . "Y " . ($this->unidad($numdero - 30));
        }
        else if ($numdero >= 20 && $numdero <= 29) {
            if ($numdero == 20)
                $numd = "VEINTE ";
            else
                $numd = "VEINTI" . ($this->unidad($numdero - 20));
        }
        else if ($numdero >= 10 && $numdero <= 19) {
            switch ($numdero) {
                case 10: {
                        $numd = "DIEZ ";
                        break;
                    }
                case 11: {
                        $numd = "ONCE ";
                        break;
                    }
                case 12: {
                        $numd = "DOCE ";
                        break;
                    }
                case 13: {
                        $numd = "TRECE ";
                        break;
                    }
                case 14: {
                        $numd = "CATORCE ";
                        break;
                    }
                case 15: {
                        $numd = "QUINCE ";
                        break;
                    }
                case 16: {
                        $numd = "DIECISEIS ";
                        break;
                    }
                case 17: {
                        $numd = "DIECISIETE ";
                        break;
                    }
                case 18: {
                        $numd = "DIECIOCHO ";
                        break;
                    }
                case 19: {
                        $numd = "DIECINUEVE ";
                        break;
                    }
            }
        }
        else
            $numd = $this->unidad($numdero);
        return $numd;
    }

// Función que permite específicar las letras correspondientes a las centenas de la cifra a convertir
    function centena($numc) {
        if ($numc >= 100) {
            if ($numc >= 900 && $numc <= 999) {
                $numce = "NOVECIENTOS ";
                if ($numc > 900)
                    $numce = $numce . ($this->decena($numc - 900));
            }
            else if ($numc >= 800 && $numc <= 899) {
                $numce = "OCHOCIENTOS ";
                if ($numc > 800)
                    $numce = $numce . ($this->decena($numc - 800));
            }
            else if ($numc >= 700 && $numc <= 799) {
                $numce = "SETECIENTOS ";
                if ($numc > 700)
                    $numce = $numce . ($this->decena($numc - 700));
            }
            else if ($numc >= 600 && $numc <= 699) {
                $numce = "SEISCIENTOS ";
                if ($numc > 600)
                    $numce = $numce . ($this->decena($numc - 600));
            }
            else if ($numc >= 500 && $numc <= 599) {
                $numce = "QUINIENTOS ";
                if ($numc > 500)
                    $numce = $numce . ($this->decena($numc - 500));
            }
            else if ($numc >= 400 && $numc <= 499) {
                $numce = "CUATROCIENTOS ";
                if ($numc > 400)
                    $numce = $numce . ($this->decena($numc - 400));
            }
            else if ($numc >= 300 && $numc <= 399) {
                $numce = "TRESCIENTOS ";
                if ($numc > 300)
                    $numce = $numce . ($this->decena($numc - 300));
            }
            else if ($numc >= 200 && $numc <= 299) {
                $numce = "DOSCIENTOS ";
                if ($numc > 200)
                    $numce = $numce . ($this->decena($numc - 200));
            }
            else if ($numc >= 100 && $numc <= 199) {
                if ($numc == 100)
                    $numce = "CIEN ";
                else
                    $numce = "CIENTO " . ($this->decena($numc - 100));
            }
        }
        else
            $numce = $this->decena($numc);
        return $numce;
    }

// Función que permite específicar las letras correspondientes a las unidades de mil de la cifra a convertir
    function miles($nummero) {
        if ($nummero >= 1000 && $nummero < 2000) {
            $numm = "MIL " . ($this->centena($nummero % 1000));
        }
        if ($nummero >= 2000 && $nummero < 10000) {
            $numm = $this->unidad(Floor($nummero / 1000)) . " MIL " . ($this->centena($nummero % 1000));
        }
        if ($nummero < 1000)
            $numm = $this->centena($nummero);
        return $numm;
    }

// Función que permite específicar las letras correspondientes a las decenas de mil de la cifra a convertir
    function decmiles($numdmero) {
        if ($numdmero == 10000)
            $numde = "DIEZ MIL";
        if ($numdmero > 10000 && $numdmero < 20000) {
            $numde = $this->decena(Floor($numdmero / 1000)) . "MIL " . ($this->centena($numdmero % 1000));
        }
        if ($numdmero >= 20000 && $numdmero < 100000) {
            $numde = $this->decena(Floor($numdmero / 1000)) . " MIL " . ($this->miles($numdmero % 1000));
        }
        if ($numdmero < 10000)
            $numde = $this->miles($numdmero);
        return $numde;
    }

// Función que permite específicar las letras correspondientes a las centenas de mil de la cifra a convertir
    function cienmiles($numcmero) {
        if ($numcmero == 100000)
            $num_letracm = "CIEN MIL";
        if ($numcmero >= 100000 && $numcmero < 1000000) {
            $num_letracm = $this->centena(Floor($numcmero / 1000)) . " MIL " . ($this->centena($numcmero % 1000));
        }
        if ($numcmero < 100000)
            $num_letracm = $this->decmiles($numcmero);
        return $num_letracm;
    }

// Función que permite específicar las letras correspondientes a las unidades de millón de la cifra a convertir
    function millon($nummiero) {
        if ($nummiero >= 1000000 && $nummiero < 2000000) {
            $num_letramm = "UN MILLON " . ($this->cienmiles($nummiero % 1000000));
        }
        if ($nummiero >= 2000000 && $nummiero < 10000000) {
            $num_letramm = $this->unidad(Floor($nummiero / 1000000)) . " MILLONES " . ($this->cienmiles($nummiero % 1000000));
        }
        if ($nummiero < 1000000)
            $num_letramm = $this->cienmiles($nummiero);
        return $num_letramm;
    }

// Función que permite específicar las letras correspondientes a las decenas de millón de la cifra a convertir
    function decmillon($numerodm) {
        if ($numerodm == 10000000)
            $num_letradmm = "DIEZ MILLONES";
        if ($numerodm > 10000000 && $numerodm < 20000000) {
            $num_letradmm = $this->decena(Floor($numerodm / 1000000)) . "MILLONES " . ($this->cienmiles($numerodm % 1000000));
        }
        if ($numerodm >= 20000000 && $numerodm < 100000000) {
            $num_letradmm = $this->decena(Floor($numerodm / 1000000)) . " MILLONES " . ($this->millon($numerodm % 1000000));
        }
        if ($numerodm < 10000000)
            $num_letradmm = $this->millon($numerodm);
        return $num_letradmm;
    }

// Función que permite específicar las letras correspondientes a las centenas de millón de la cifra a convertir
    function cienmillon($numcmeros) {
        if ($numcmeros == 100000000)
            $num_letracms = "CIEN MILLONES";
        if ($numcmeros >= 100000000 && $numcmeros < 1000000000) {
            $num_letracms = $this->centena(Floor($numcmeros / 1000000)) . " MILLONES " . ($this->millon($numcmeros % 1000000));
        }
        if ($numcmeros < 100000000)
            $num_letracms = $this->decmillon($numcmeros);
        return $num_letracms;
    }

// Función que permite específicar las letras correspondientes a las unidades del millardo de la cifra a convertir
    function milmillon($nummierod) {
        if ($nummierod >= 1000000000 && $nummierod < 2000000000) {
            //se trae el resto de la cantidad en letras
            $string_aux=$this->cienmillon($nummierod % 1000000000);
            if(strpos($string_aux, 'MILLON') === false ){
                $num_letrammd="MIL MILLONES ".$string_aux;
            }else{
                $num_letrammd="MIL ".$string_aux;
            }
        }

        if ($nummierod >= 2000000000 && $nummierod < 10000000000) {

            $string_aux=$this->cienmillon($nummierod % 1000000000);
            if(strpos($string_aux, 'MILLON') === false ){
                $num_letrammd= $this->unidad(Floor($nummierod / 1000000000)) . " MIL MILLONES ".$string_aux;
            }else{
                $num_letrammd=$this->unidad(Floor($nummierod / 1000000000)) . " MIL ".$string_aux;
            }
        }

        if ($nummierod < 1000000000){
            $num_letrammd = $this->cienmillon($nummierod);
        }
        
        return $num_letrammd;
    }

// Funci&oacute;n que permite la conversi&oacute;n de n&uacute;meros a letras
    function convertir($numero) {
        $cantidad = explode(".", $numero);
        $numf = $this->milmillon($cantidad[0]);
        if (!isset($cantidad[1])) {
            $cantidad[1] = 0;
        }
        $n = $this->decena($cantidad[1]);
        if ($cantidad[0] == 0) {
            return $n . " CENTIMOS";
        } else if ($cantidad[0] == 1) {
            if ($cantidad[1] != "") {
                $n = $cantidad[1] == 0 ? "CERO" : $n;
                return $numf . " BOLIVAR CON " . $n . " CENTIMOS";
            } else {
                return $numf . " BOLIVAR ";
            }
        } else {
            if ($cantidad[1] != "") {
                $n = $cantidad[1] == 0 ? "CERO" : $n;
                return $numf . " BOLIVARES CON " . $n . " CENTIMOS";
            } else {
                return $numf . " BOLIVARES ";
            }
        }
    }

    function FormatoJ1($monto) {
        $aux = $monto . '';
        for ($i = 0; $i < strlen($aux); $i++) {
            if ($aux[$i] == ',') {
                if (isset($aux[$i + 3])) {
                    if ($aux[$i + 3] == '5') {
                        $monto += 0.001;
                        break;
                    }
                }
            }
        }//fin
        $monto = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $monto));
        if (substr($monto, -3, 1) == '.') {
            $sents = '.' . substr($monto, -2);
            $monto = substr($monto, 0, strlen($monto) - 3);
        } elseif (substr($monto, -2, 1) == '.') {
            $sents = '.' . substr($monto, -1);
            $monto = substr($monto, 0, strlen($monto) - 2);
        } else {
            $sents = '.00';
        }
        $monto = preg_replace("/[^0-9]/", "", $monto);
        $var = number_format($monto . $sents, 2, '.', '');
        return $var;
    }

    function FormatoDEC2($monto) {
        $var = $this->FormatoJ1($this->Formato2($monto));
        return $var;
    }

    function get_porcentaje($total, $base) {
        $porcentaje = ($base * 100) / $total;

        return round($porcentaje, 1);
    }

    function input_fecha($modelo = null, $name = null, $id = null, $value = null, $options = array()) {

        /* echo "<div class=\"div_fecha\">" .
          "<input type=\"text\" name=\"data[".$modelo."][".$name."]\" id=\"".$id."\" value=\"".$value."\" style=\"border-width:0px; width: 77%;margin:0px 0px 0px 0px; font-size:12px;text-align:center;\" readonly=\"true\">" .
          "<img style=\"cursor:pointer;\" onClick=\"displayCalendar(document.getElementById('$id'),'dd/mm/yyyy',this)\" src=\"/img/calendar.gif\" align=\"absmiddle\"/></div>";
         */
        echo "<input type=\"text\" name=\"data[" . $modelo . "][" . $name . "]\" id=\"" . $id . "\" value=\"" . $value . "\" class=\"input_fecha\" onClick=\"displayCalendar(document.getElementById('$id'),'dd/mm/yyyy',this)\"  readonly=\"true\">";



        for ($i = 1; $i <= 10; $i++) {
            if (isset($options['update' . $i])) {
                $options['update'] = $options['update' . $i];
                $options['url'] = $options['url' . $i];

                echo "<input type='hidden' value='" . $options['url'] . "'    id='url_" . $i . $id . "'      />";
                echo "<input type='hidden' value='" . $options['update'] . "' id='update_" . $i . $id . "'   />";
            }//FIN IF
        }//FIN FOR
    }

//fin function

    function link_paginacion($url, $id_update, $total_paginas, $pagina_actual) {
        for ($i = 1; $i <= $total_paginas; $i++) {
            $link_url = $url . '/' . $i;
            if ($i == $pagina_actual) {
                echo '<span class="link_paginacion_activo">' . $i . '</span>';
            } else {
                echo '<a class="link_paginacion" href="#pagina_' . $i . '" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $i . '</a>';
            }
            if ($i == 10) {
                echo'<br><br>';
            } else if ($i == 20) {
                echo'<br><br>';
            } else if ($i == 30) {
                echo'<br><br>';
            } else if ($i == 40) {
                echo'<br><br>';
            } else if ($i == 50) {
                echo'<br><br>';
            } else if ($i == 60) {
                echo'<br><br>';
            } else if ($i == 70) {
                echo'<br><br>';
            } else if ($i == 80) {
                echo'<br><br>';
            } else if ($i == 90) {
                echo'<br><br>';
            } else if ($i == 100) {
                echo'<br><br>';
            } else if ($i == 110) {
                echo'<br><br>';
            } else if ($i == 120) {
                echo'<br><br>';
            } else if ($i == 130) {
                echo'<br><br>';
            } else if ($i == 140) {
                echo'<br><br>';
            } else if ($i == 150) {
                echo'<br><br>';
            }
        }//fin for
    }

    function link_paginacion_ventanas($url = null, $id_update = null, $total_paginas = null, $pagina_actual = null, $anterior = null, $siguiente = null, $ultimo = null) {
       echo" <table class='adminlist' width='100%'>
		<tr>
				<th colspan='3'  width='100%' height='22'>
						<span class='pagenav'>";
        if ($pagina_actual > 1) {
            $text = "<< Primero";
            $link_url = $url . "/1";
            echo '<a  href="#" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $text . '</a>';
        }
        echo" 					</span>

						<span class='pagenav'>";
        if ($pagina_actual > 1) {
            $text = "< Anterior";
            $link_url = $url . "/" . $anterior . "";
            echo '<a  href="#" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $text . '</a>';
        }
        echo" 					</span>";


        if ($total_paginas <= 10) {
            if ($total_paginas != 1) {
                for ($i = 1; $i <= $total_paginas; $i++) {
                    $link_url = $url . '/' . $i;
                    if ($i == $pagina_actual) {
                        echo ' <span class="link_paginacion_ventana_activo">' . $i . '</span> ';
                    } else {
                        echo ' <a href="#pagina_' . $i . '" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $i . '</a> ';
                    }
                }//fin for
            }//fin if
        } else {



            if ($pagina_actual >= 5) {
                $a = $pagina_actual - 4;
            } else {
                $a = 1;
            }
            if ($pagina_actual >= 6) {
                $b = $pagina_actual + 5;
            } else {
                $b = 10;
            }

            for ($i = $a; $i <= $b; $i++) {
                $link_url = $url . '/' . $i;
                if ($i <= $total_paginas) {
                    if ($i == $pagina_actual) {
                        echo ' <span class="link_paginacion_ventana_activo">' . $i . '</span> ';
                    } else {
                        echo ' <a href="#pagina_' . $i . '" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $i . '</a> ';
                    }//fin if
                }//fin if
            }//fin for
        }//fin else




        echo"					<span class='pagenav'>";
        if ($pagina_actual < $total_paginas) {
            $text = "Siguiente >";
            $link_url = $url . "/" . $siguiente . "";
            echo '<a  href="#" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $text . '</a>';
        }
        echo" 					</span>

						<span class='pagenav'>";
        if ($pagina_actual < $total_paginas) {
            $text = "Último >>";
            $link_url = $url . "/" . $ultimo . "";
            echo '<a  href="#" onclick="ver_documento(\'' . $link_url . '\',\'' . $id_update . '\');">' . $text . '</a>';
        }
        echo" 					</span>
				</th>
		</tr>
</table>";
    }

//FIN FUNCTION


    function agregar_imagen_inst($opcion, $identificacion, $id_capa_cargar) {
        echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/cnmp06_constancia_firmante/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/add/1", "400px", "110px");
    }

    function modificar_imagen_inst($opcion, $identificacion, $id_capa_cargar) {
        echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/cnmp06_constancia_firmante/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/add/1", "400px", "110px");
    }

    function ver_real_imagen_inst($identificador, $opcion) {
        echo '<img src="/cnmp06_constancia_firmante/ver_miniatura/' . $identificador . '/' . $opcion . '/' . intval(rand()) . '" border="0" height="250" width="98%" title="HACER CLICK PARA VER LA IMAGEN" style="cursor:pointer;" onclick="' . $this->onclick_ventana('/cnmp06_constancia_firmante/ver_img_grande_croquis/' . $identificador . '/' . $opcion, 'Imagen', '550px', '400px', true, true, true) . '" />';
    }

    function ver_imagen_min_inst($identificador, $opcion) {
        echo '<img src="/cnmp06_constancia_firmante/ver_full/' . $identificador . '/' . $opcion . '/' . intval(rand()) . '" border="0" height="146" width="110" />';
    }

    function agregar_imagen_croquis($opcion, $identificacion, $id_capa_cargar) {
        echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/cugp10_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/add/1", "400px", "110px");
    }

    function modificar_imagen_croquis($opcion, $identificacion, $id_capa_cargar) {
        echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/cugp10_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/modificar/1", "400px", "110px");
    }

    function ver_real_imagen_croquis($identificador, $opcion) {
        echo '<img src="/cugp10_imagenes/ver_miniatura/' . $identificador . '/' . $opcion . '/' . intval(rand()) . '" border="0" height="850" title="HACER CLICK PARA VER LA IMAGEN" width="100%" style="cursor:pointer;" onclick="' . $this->onclick_ventana('/cugp10_imagenes/ver_img_grande_croquis/' . $identificador . '/' . $opcion, 'Imagen Croquis', '550px', '400px', true, true, true) . '"/>';
    }

    function agregar_imagen($opcion, $identificacion, $id_capa_cargar, $value) {
        if($value){
            echo $this->imagen_ventana($value, 6, "Cargar Imagen", "/cugp10_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/add", "400px", "110px");
        }else{
            echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/cugp10_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/add", "400px", "110px");
        }
    }

    function modificar_imagen($opcion, $identificacion, $id_capa_cargar, $value) {
        if($value){
            echo $this->imagen_ventana($value, 6, "Cargar Imagen", "/cugp10_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/modificar", "400px", "110px");
        }else{
            echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/cugp10_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/modificar", "400px", "110px");
        }
    }

    function agregar_imagen_consejo($opcion, $identificacion, $id_capa_cargar) {
        echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/ccnp00_imagenes/index/formulario/" . $opcion . "/" . $identificacion . "/" . $id_capa_cargar . "/add", "400px", "110px");
    }

    function modificar_imagen_consejo($opcion, $identificacion, $id_capa_cargar) {
        echo $this->imagen_ventana(array("value" => ".."), 6, "Cargar Imagen", "/ccnp00_imagenes/index/formulario/$opcion/$identificacion/$id_capa_cargar/modificar/", "400px", "110px");
    }

    function ver_miniatura_consejo($opcion, $identificador) {
        //echo '<img src="/img/ver_foto_grande.png" border="0" height="75" title="HACER CLICK PARA VER LA IMAGEN" width="75" style="cursor:pointer;" onclick="'.$this->onclick_ventana('/cugp10_imagenes/ver_imagen_grande/'.$identificador.'/'.$opcion,'Imagen','550px','400px').'"/>';
        echo '<img src="/ccnp00_imagenes/ver_miniatura/' . $identificador . '/' . $opcion . '/' . intval(rand()) . '" border="0" height="146" title="HACER CLICK PARA VER LA IMAGEN" width="110" style="cursor:pointer;" onclick="' . $this->onclick_ventana('/ccnp00_imagenes/ver_imagen_grande/' . $identificador . '/' . $opcion, 'Imagen', '550px', '400px') . '"/>';
    }

    function onclick_ventana($url = null, $title_aux, $width_aux = "330px", $height_aux = "400px", $resizable_aux = false, $maximizable_aux = false, $minimizable_aux = false, $closable_aux = true) {
        return "codigo_ventana('$url', '$width_aux', '$height_aux', '$title_aux', '$resizable_aux', '$maximizable_aux', '$minimizable_aux', $closable_aux);";
    }

    function ver_miniatura_imagen_vg($identificador, $opcion) {
        //echo '<img src="/img/ver_foto_grande.png" border="0" height="75" title="HACER CLICK PARA VER LA IMAGEN" width="75" style="cursor:pointer;" onclick="'.$this->onclick_ventana('/cugp10_imagenes/ver_imagen_grande/'.$identificador.'/'.$opcion,'Imagen','550px','400px').'"/>';
        echo '<img src="/cugp10_imagenes/ver_miniatura/' . $identificador . '/' . $opcion . '/' . intval(rand()) . '" border="0" height="146" title="HACER CLICK PARA VER LA IMAGEN" width="110" style="cursor:pointer;" onclick="' . $this->onclick_ventana('/cugp10_imagenes/ver_imagen_grande/' . $identificador . '/' . $opcion, 'Imagen', '550px', '400px') . '"/>';
    }

    function ver_miniatura_imagen($identificador, $opcion) {
        echo '<img src="/cugp10_imagenes/ver/' . $identificador . '/' . $opcion . '/' . intval(rand()) . '" border="0" height="146"  width="110"/>';
    }

    function ver_video_ayuda($url, $ancho = 425, $alto = 344) {
        //echo '<img src="/cugp10_imagenes/ver/'.$identificador.'/'.$opcion.'/'.intval(rand()).'" border="0" height="146" title="HACER CLICK PARA AGRANDAR LA IMAGEN" width="110" style="cursor:pointer;" onclick="'.$this->onclick_ventana('/cugp10_imagenes/ver_imagen_grande/'.$identificador.'/'.$opcion,'Imagen','550px','400px').'"/>';
        echo $this->Html->image('help-browser.png', array('border' => 0, 'width' => 32, 'height' => 32, 'title' => 'AYUDA', 'style' => 'cursor:pointer;', "onclick" => $this->onclick_ventana('/cugp10_videos/index/' . $url . '/' . $ancho . '/' . $alto, 'Ayuda', $ancho + (100) . 'px', $alto + (100) . 'px')));
    }

    function ver_video_ayuda_popup($url, $ancho = 425, $alto = 344, $tipo = 1, $texto = null) {
        $url = "/cugp10_videos/index/" . $url . "/" . $ancho . "/" . $alto;
        $ancho = $ancho + 2;
        $alto = $alto + 2;
        //echo $this->Html->image('help-browser.png',array('border'=>0,'width'=>32,'height'=>32,'title'=>'AYUDA','style'=>'cursor:pointer;',"onclick"=>"ventana_popup('".$url."','AYUDA',".$ancho.",".$alto.");"));
        if ($tipo != 1 && isset($texto) && $texto != null) {
            //echo $this->Html->image('help-browser.png',array('border'=>0,'width'=>32,'height'=>32,'title'=>'AYUDA','style'=>'cursor:pointer;',"onclick"=>"ventana_popup('".$url."','AYUDA',".$ancho.",".$alto.");"));
            echo "<a href=\"javascript:ventanaSecundaria('" . $url . "');\">";
            echo "" . $texto;
            echo "</a>";
        } else {
            echo "<a href=\"javascript:ventana_popup('" . $url . "'," . $ancho . "," . $alto . ");\">";
            echo $this->Html->image('help-browser.png', array('border' => 0, 'width' => 32, 'height' => 32, 'title' => 'AYUDA'));
            echo "</a>";
        }
    }

    function ver_manual($url, $ancho = 750, $alto = 500, $tipo = 1, $texto = null) {
        $url = "/cugp10_manuales/ver_manual/" . $url;
        $ancho = $ancho + 2;
        $alto = $alto + 2;
        echo "<input type='button' id='ver_manual' value='.' class='ayuda_input' onClick=\"javascript:ventana_popup_video_ayuda('" . $url . "', 'alberto', '0','0','0','0','0','0','0','" . $ancho . "','" . $alto . "','200','100','1');\">";
        //echo "<a href='#' class='ayuda_input_1' onClick=\"javascript:ventana_popup_video_ayuda('".$url."', 'alberto', '0','0','0','0','0','0','0','".$ancho."','".$alto."','200','100','1');\">";
        //echo $this->Html->image('help-browser.png',array('border'=>0,'width'=>32,'height'=>32,'title'=>'VER AYUDA'));
        //echo "</a>";
    }

    function frecuencia($id) {
        switch ($id) {
            case 1: return '1era Semana';
                break;
            case 2: return '2da Semana';
                break;
            case 3: return '3era Semana';
                break;
            case 4: return '4ta Semana';
                break;
            case 5: return '5ta Semana';
                break;
            case 6: return 'Todas las semanas';
                break;
            case 7: return '1era Quincena';
                break;
            case 8: return '2da Quincena';
                break;
            case 9: return 'Ambas Quincenas';
                break;
            case 10: return 'Pago Unico';
                break;
            case 11: return 'Suspendido';
                break;
            default: return '<br/>';
                break;
        }
    }

    /**
     * Nombre: function restriccion_programas_claves();
     * Descripcion: funcion para crear una miniventana con el formulario pidiendo el Usuario y el Password para entrar a algun programa.
     *
     * Variables:
     *
     * @ formName: Nombre del formulario, es asi como se va a recibir el - this->data(); - en el controlador.
     * @ rutaController: Url o Programa que recibira los datos del formulario.
     * @ capaActualizar: capa o elemento el cual va a ser actualizado. Por defecto va a ser (principal)
     *
     */
    function restriccion_programas_claves($formName = null, $rutaController = null, $capaActualizar = 'principal') {

        if ($formName == null || $rutaController == null) {
            echo '<center>
					<form name="data[' . $formName . ']" method="POST" id="form1">
					<table id="capa_programas_resringidos">
					<tr>
					<td align="center">
						<table align="center" width="235" align="center" border="0" cellspacing="0">
						         <tr>
					    			 <td style="font-family:verdana; font-size:9pt; font-weight:bold; color:#F1F1F1;">ATENCI&Oacute;N: Se deben pasar los dos parametros a la funcion, tanto el nombre del formulario, como el nombre del controlador.</td>
						  		  </tr>
						</table>
					</td>
					</tr>
					</table>
					</form>
				 </center>';

            echo $this->buttonTagRemote('/buttom/', array('type' => 'button', 'value' => '   Salir   '), array('url1' => '/modulos/vacio', 'update1' => 'principal'));
        } else {

            echo '<center>
				   <form name="data[' . $formName . ']" method="POST" id="form1">
					<table id="capa_programas_resringidos">
					<tr>
					<td>
						       <table width="350" align="center" border="0" cellspacing="0">
						         <tr>
					    			 <td colspan="2"><br></td>
						  		  </tr>
					    		  <tr>
					    			 <td width="150" align="right" style="font-family:verdana; font-size:9pt; font-weight:bold; color:#F1F1F1;">Login:&nbsp;&nbsp;&nbsp;</td>
						    		 <td width="200"><input type="text" name="data[' . $formName . '][login]" id="login" class="input_user_prog_restric" /></td>
						  		  </tr>
					    		  <tr>
					    			 <td width="150" align="right" style="font-family:verdana; font-size:9pt; font-weight:bold; color:#F1F1F1;">Contraseña:&nbsp;&nbsp;&nbsp;</td>
						    		 <td width="200"><input type="password" name="data[' . $formName . '][password]" id="password" class="input_pass_prog_restric" /></td>
						  		  </tr>
					    		  <tr>
					    			 <td colspan="2">&nbsp;</td>
						  		  </tr>
					    		  <tr>
					    			 <td colspan="2" align="center">';

            echo $this->submitTagRemote('    Entrar    ', array('url1' => '/' . $rutaController, 'update1' => $capaActualizar, 'disabled' => 'enable'));
            echo $this->buttonTagRemote('/' . $formName . '/', array('type' => 'button', 'value' => '   Salir   '), array('url1' => '/modulos/vacio', 'update1' => 'principal'));

            echo '					</td>
						  		  </tr>
						  		  <tr>
					    			 <td colspan="2"><br></td>
						  		  </tr>
					    		</table>
					</td>
					</tr>
					</table>
					</form>
				</center>

				<script>
						document.getElementById("login").focus();
				</script>';
        }
    }


function validar_cclave($var_cc=null) {
 	if($var_cc != null){

	/**
		$hash = password_hash($var_cc);
		// $hash = crypt($var_cc);
		// $hash = password_hash($var_cc, $algorithm, $options); cost=>10

    if (!password_verify($var_cc, $hash)) {
        //if (password_needs_rehash($hash)) {
            //$hash = password_hash($var_cc);
        //}

      $error_clave[0] = "No se pudo verificar la clave... Intente con otra!";
      $error_clave[1] = false;
    }else
    */

	if(isset($_SESSION["nom_usuario"])){
		$username = $_SESSION["nom_usuario"];
	}else{
		$username = "";
	}

	if($var_cc == $username){
      $error_clave[0] = "La clave debe de ser diferente al nombre de usuario (Login)";
      $error_clave[1] = false;

	}else if(strlen($var_cc) < 6){
      $error_clave[0] = "La clave debe tener al menos 6 caracteres";
      $error_clave[1] = false;
   }

   else if(strlen($var_cc) > 25){
      $error_clave[0] = "La clave no puede tener m&aacute;s de 25 caracteres";
      $error_clave[1] = false;
   }

   else if (!preg_match('`[A-Za-z]`',$var_cc)){
      $error_clave[0] = "La clave debe tener al menos una letra (a-z)";
      $error_clave[1] = false;
   }

   else if (!preg_match('`[0-9]`',$var_cc)){
      $error_clave[0] = "La clave debe tener al menos un d&iacute;gito (0-9)";
      $error_clave[1] = false;
   }

	else if(!preg_match('`[!@#)$%&*<,>;:(-._]`', $var_cc)) {
      $error_clave[0] = "La clave es incorrecta debe contener una combinaci&oacute;n entre letras(a-z), n&uacute;meros(0-9) y s&iacute;mbolos especiales como: <br><span style='color:#840000;font-size:14px;'>".'! @ # $ _ % & * ( ) < > . , ; : -'."</span>";
      $error_clave[1] = false;
    }

   else{
   		$error_clave[0] = "";
   		$error_clave[1] = true;
   }
	}
	return $error_clave;
}


}

//FIN CLASS
?>
