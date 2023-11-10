<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <div id='firmas2'>

            <input type="hidden" name="data[reporte][cod_tipo_documento1]" value="<?= $tipo_documento1 ?>" maxlength="5" id="cod_tipo_documento1" class="inputtext" />
            <table width="100%" cellspacing="0" cellpadding="0" id="grid" class="tablacompromiso tablacompromiso3">
                <tr class="tr_negro"><td colspan="2" class="td5" height="22">Formato 2: 'Concurso Cerrado, Concurso Abierto'</td></tr>
            </table>
            <table width="100%" border="0" cellspacing="0" class="tablacompromiso tablacompromiso2">
                <tr class="tr_negro">
                    <td width="5%" align="right">Firma</td>
                    <td width="5%" align="center" style="border-right:#000000 0px solid; border-top:#000000 0px solid;border-left:#000000 0px solid;border-bottom:#000000 1px solid;">
                        <?php echo $ajax->link($html->image('broom.png', array('width' => '18', 'height' => '18', 'border' => 0)), '/reporte/borrar_firmas/5', array('update' => 'limpiar99_firmas', 'title' => 'Limpiar Campos Firmas', 'style' => 'display:none;', 'id' => 'link_limpiaf'), "¿Desea limpiar los campos de los datos firmantes.?", false, true); ?>
                    </td>
                    <td width="24%" align="center">Responsabilidad</td>
                    <td width="30%" align="center">Nombre del Funcionario</td>
                    <td width="24%" align="center">Cargo</td>
                    <td width="12%" align="center">C&eacute;dula</td>
                </tr>

                <tr>
                    <td align="center" colspan="2" style='text-shadow: 0.06em 0.05em cyan;'><b>Primera</b></td>
                    <td><input type="text" name="data[reporte][responsa_primera_firma1]" value="<?= $responsa_primera_firma1 ?>" maxlength="100" id="responsa_primera_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][funcionario_primera_firma1]" value="<?= $funcionario_primera_firma1 ?>" maxlength="100" id="funcionario_primera_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cargo_primera_firma1]" value="<?= $cargo_primera_firma1 ?>" maxlength="100" id="cargo_primera_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cedula_primera_firma1]" value="<?= $cedula_primera_firma1 ?>" maxlength="10" id="cedula_primera_firma1" style="text-align:center;" class="inputtext" <?= $b_readonly ?> onKeyPress="return solonumeros_enteros(event);" /></td>
                </tr>

                <tr>
                    <td align="center" colspan="2" style='text-shadow: 0.06em 0.05em cyan;'><b>Segunda</b></td>
                    <td><input type="text" name="data[reporte][responsa_segunda_firma1]" value="<?= $responsa_segunda_firma1 ?>" maxlength="100" id="responsa_segunda_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][funcionario_segunda_firma1]" value="<?= $funcionario_segunda_firma1 ?>" maxlength="100" id="funcionario_segunda_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cargo_segunda_firma1]" value="<?= $cargo_segunda_firma1 ?>" maxlength="100" id="cargo_segunda_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cedula_segunda_firma1]" value="<?= $cedula_segunda_firma1 ?>" maxlength="10" id="cedula_segunda_firma1" style="text-align:center;" class="inputtext" <?= $b_readonly ?> onKeyPress="return solonumeros_enteros(event);" /></td>
                </tr>

                <tr>
                    <td align="center" colspan="2" style='text-shadow: 0.06em 0.05em cyan;'><b>Tercera</b></td>
                    <td><input type="text" name="data[reporte][responsa_tercera_firma1]" value="<?= $responsa_tercera_firma1 ?>" maxlength="100" id="responsa_tercera_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][funcionario_tercera_firma1]" value="<?= $funcionario_tercera_firma1 ?>" maxlength="100" id="funcionario_tercera_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cargo_tercera_firma1]" value="<?= $cargo_tercera_firma1 ?>" maxlength="100" id="cargo_tercera_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cedula_tercera_firma1]" value="<?= $cedula_tercera_firma1 ?>" maxlength="10" id="cedula_tercera_firma1" style="text-align:center;" class="inputtext" <?= $b_readonly ?> onKeyPress="return solonumeros_enteros(event);" /></td>
                </tr>

                <tr>
                    <td align="center" colspan="2" style='text-shadow: 0.06em 0.05em cyan;'><b>Cuarta</b></td>
                    <td><input type="text" name="data[reporte][responsa_cuarta_firma1]" value="<?= $responsa_cuarta_firma1 ?>" maxlength="100" id="responsa_cuarta_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][funcionario_cuarta_firma1]" value="<?= $funcionario_cuarta_firma1 ?>" maxlength="100" id="funcionario_cuarta_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cargo_cuarta_firma1]" value="<?= $cargo_cuarta_firma1 ?>" maxlength="100" id="cargo_cuarta_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cedula_cuarta_firma1]" value="<?= $cedula_cuarta_firma1 ?>" maxlength="10" id="cedula_cuarta_firma1" style="text-align:center;" class="inputtext" <?= $b_readonly ?> onKeyPress="return solonumeros_enteros(event);" /></td>
                </tr>

                <tr>
                    <td align="center" colspan="2" style='text-shadow: 0.06em 0.05em cyan;'><b>Quinta</b></td>
                    <td><input type="text" name="data[reporte][responsa_quinta_firma1]" value="<?= $responsa_quinta_firma1 ?>" maxlength="100" id="responsa_quinta_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][funcionario_quinta_firma1]" value="<?= $funcionario_quinta_firma1 ?>" maxlength="100" id="funcionario_quinta_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cargo_quinta_firma1]" value="<?= $cargo_quinta_firma1 ?>" maxlength="100" id="cargo_quinta_firma1" class="inputtext" <?= $b_readonly ?> /></td>
                    <td><input type="text" name="data[reporte][cedula_quinta_firma1]" value="<?= $cedula_quinta_firma1 ?>" maxlength="10" id="cedula_quinta_firma1" style="text-align:center;" class="inputtext" <?= $b_readonly ?> onKeyPress="return solonumeros_enteros(event);" /></td>
                </tr>
            </table>
            <br />

            <div id="limpiar100_firmas"></div>
            <div id="save100_firmas">
                <?php if (isset($firma_existe) && $firma_existe == 'no') { ?>
                    <?= $sisap->submitTagRemote('Guardar Firmas', array('funcion' => 'valida_firmas99_reportes', 'url1' => '/reporte/guardar_editar_firmas1/si/8', 'update1' => 'save100_firmas', 'id' => 'b_guardar_firmas', 'disabled' => 'enable')); ?>
                <?php } else if (isset($firma_existe) && $firma_existe == 'si') { ?>
                    <?= $sisap->submitTagRemote('Modificar Firmas', array('url1' => '/reporte/guardar_editar_firmas1/no/8', 'update1' => 'save100_firmas', 'id' => 'b_guardar_firmas', 'disabled' => 'enable')); ?>
                <?php } ?>
            </div>
            <br /><br />
        </div>

    </body>
</html>
