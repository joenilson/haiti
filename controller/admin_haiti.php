<?php
/*
 * Copyright (C) 2016 Joe Nilson <joenilson at gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_model('impuesto.php');
require_model('pais.php');
require_model('divisa.php');
require_model('cuenta_especial.php');
require_once 'plugins/haiti/vendor/php-i18n/i18n.class.php';
/**
 * Description of admin_haiti
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class admin_haiti extends fs_controller {
    public $conf_divisa;
    public $conf_impuestos;
    public $conf_pais;
    public $conf_regional;
    public $impuestos_haiti;
    public $variables;
    public $i18n;
    public $pagetitle;
    public function __construct() {
        parent::__construct(__CLASS__, 'Haití', 'admin', TRUE, TRUE, FALSE);
    }
    
    protected function private_core() {
        $this->language();
        $this->share_extensions();
        $impuesto_empresa = new impuesto();
        $this->variables = array();
        $this->variables['zona_horaria'] = "America/Port-au-Prince";
        $this->variables['nf0'] = "2";
        $this->variables['nf0_art'] = "4";
        $this->variables['nf1'] = ".";
        $this->variables['nf2'] = ",";
        $this->variables['pos_divisa'] = "left";
        $this->variables['factura'] = "facture";
        $this->variables['facturas'] = "factures";
        $this->variables['factura_simplificada'] = "facture simplifiée";
        $this->variables['factura_rectificativa'] = "facture rectificative";
        $this->variables['albaran'] = "récépissé";
        $this->variables['albaranes'] = "bordereaux d emballage";
        $this->variables['pedido'] = "ordre";
        $this->variables['pedidos'] = "ordres";
        $this->variables['presupuesto'] = "budget";
        $this->variables['presupuestos'] = "budgets";
        $this->variables['provincia'] = "province";
        $this->variables['apartado'] = "PO";
        $this->variables['cifnif'] = "Carte d identité/NIF";
        $this->variables['iva'] = "TCA";
        $this->variables['numero2'] = "Numéro Auxiliaire";
        $this->variables['serie'] = "série";
        $this->variables['series'] = "série";
        
        $this->impuestos_haiti = array(
            array('codigo' => 'TCA10', 'descripcion' => 'TCA 10%', 'porcentaje' => 10, 'recargo' => 0, 'subcuenta_compras' => '02010602', 'subcuenta_ventas' => '02010602'),
            array('codigo' => 'EXEMPT', 'descripcion' => 'EXENTO', 'porcentaje' => 0, 'recargo' => 0, 'subcuenta_compras' => '02010602', 'subcuenta_ventas' => '02010602')
        );
        
        if (isset($_GET['opcion'])) {
            if ($_GET['opcion'] == 'moneda') {
                $this->moneda();
            } else if ($_GET['opcion'] == 'impuestos') {
                $this->impuestos();
            } else if ($_GET['opcion'] == 'pais') {
                $this->pais();
            } else if ($_GET['opcion'] == 'configuracion_regional') {
                $this->configuracion_regional();
            }
        }
        $this->conf_divisa = ($this->empresa->coddivisa == 'HTG') ? TRUE : FALSE;
        $this->conf_pais = ($this->empresa->codpais == 'HTI') ? TRUE : FALSE;
        $this->conf_regional = ($GLOBALS['config2']['iva'] == 'TCA') ? TRUE : FALSE;
        $this->conf_impuestos = ($impuesto_empresa->get_by_iva(10)) ? TRUE : FALSE;        
        
    }
    
    private function language($lang=false){
        if($lang){
            $this->i18n = new i18n('plugins/haiti/lang/lang_'.$lang.'.ini', FS_TMP_NAME.'langcache/', $lang);
        }else{
            $this->i18n = new i18n('plugins/haiti/lang/lang_{LANGUAGE}.ini', FS_TMP_NAME.'langcache/', 'es');
        }
        $this->i18n->init();
    }
    
    public function impuestos() {
        $tratamiento = false;
        $impuestos = new impuesto();
        //Eliminamos los Impuestos que no son de HAITI
        $lista_impuestos =array();
        foreach ($this->impuestos_haiti as $imp) {
            $lista_impuestos[]=$imp['porcentaje'];
        }
        
        foreach ($impuestos->all() as $imp) {
            if(!in_array($imp->iva, $lista_impuestos)){
                $imp->delete();
            }
        }
        
        //Agregamos los Impuestos de RD
        foreach ($this->impuestos_haiti as $imp) {
            if(!$impuestos->get_by_iva($imp['porcentaje'])){
                $imp0 = new impuesto();
                $imp0->codimpuesto = $imp['codigo'];
                $imp0->descripcion = $imp['descripcion'];
                $imp0->iva = $imp['porcentaje'];
                $imp0->recargo = $imp['recargo'];
                $imp0->codsubcuentasop = $imp['subcuenta_compras'];
                $imp0->codsubcuentarep = $imp['subcuenta_ventas'];
                if($imp0->save()){
                    $tratamiento = true;
                }
            }
        }
        
        //Corregimos la información de las Cuentas especiales con los nombres correctos
        $cuentas_especiales_rd['IVAACR']='Cuentas acreedoras de TCA en la regularización';
        $cuentas_especiales_rd['IVASOP']='Cuentas de TCA Compras';
        $cuentas_especiales_rd['IVARXP']='Cuentas de TCA exportaciones';
        $cuentas_especiales_rd['IVASIM']='Cuentas de TCA importaciones';
        $cuentas_especiales_rd['IVAREX']='Cuentas de TCA para clientes exentos';
        $cuentas_especiales_rd['IVAREP']='Cuentas de TCA Ventas';
        $cuentas_especiales = new cuenta_especial();
        foreach($cuentas_especiales_rd as $id=>$desc){
            $linea = $cuentas_especiales->get($id);
            if($linea->descripcion!==$desc){
                $linea->descripcion = $desc;
                $linea->save();
            }
        }
        
        if($tratamiento){
            $this->new_message('Información de impuestos actualizada correctamente');
        }else{
            $this->new_message('No se modificaron datos de impuestos previamente tratados.');
        }
    }
    
    public function moneda() {
        $tratamiento = false;
        //Validamos si existe la moneda HTG
        $div0 = new divisa();
        $divisa = $div0->get('HTG');
        if (!$divisa) {
            $div0->coddivisa = 'HTG';
            $div0->codiso = '332';
            $div0->descripcion = 'GOURDE';
            $div0->simbolo = 'HTG';
            $div0->tasaconv = 60.06;
            $div0->tasaconv_compra = 60.06;
            $div0->save();
            $tratamiento = true;
        }
        //Validamos si existe la moneda USD
        //por temas de operaciones en dolares
        $divisa = $div0->get('USD');
        if (!$divisa) {
            $div0->coddivisa = 'USD';
            $div0->codiso = '840';
            $div0->descripcion = 'DÓLARES EE.UU.';
            $div0->simbolo = '$';
            $div0->tasaconv = 57.53;
            $div0->tasaconv_compra = 57.53;
            $div0->save();
            $tratamiento = true;
        }
        
        if($tratamiento){
            $this->new_message('Datos de moneda HTG y USD actualizados correctamente.');
        }
        
        if ($this->empresa->coddivisa != 'HTG') {
            //Elegimos la divisa para la empresa como DOP si no esta generada
            $this->empresa->coddivisa = 'HTG';
            if ($this->empresa->save()) {
                $this->new_message('Datos de moneda para la empresa guardados correctamente.');
            }
        }
    }
    
    public function pais() {
        $pais0 = new pais();
        $pais = $pais0->get('HTI');
        if (!$pais) {
            $pais0->codpais = 'HTI';
            $pais0->codiso = 'HT';
            $pais0->nombre = 'Haití';
            $pais0->save();
        }

        $pais = $pais0->get('USA');
        if (!$pais) {
            $pais0->codpais = 'USA';
            $pais0->codiso = 'US';
            $pais0->nombre = 'Estados Unidos';
            $pais0->save();
        }

        $this->empresa->codpais = 'HTI';
        if ($this->empresa->save()) {
            $this->new_message('Datos guardados correctamente.');
        }
    }
    
    public function configuracion_regional() {
        //Configuramos la información básica para config2.ini
        $guardar = FALSE;
        foreach ($GLOBALS['config2'] as $i => $value) {
            if (isset($this->variables[$i])) {
                $GLOBALS['config2'][$i] = htmlspecialchars($this->variables[$i]);
                $guardar = TRUE;
            }
        }

        if ($guardar) {
            $file = fopen('tmp/' . FS_TMP_NAME . 'config2.ini', 'w');
            if ($file) {
                foreach ($GLOBALS['config2'] as $i => $value) {
                    if (is_numeric($value)) {
                        fwrite($file, $i . " = " . $value . ";\n");
                    } else {
                        fwrite($file, $i . " = '" . $value . "';\n");
                    }
                }
                fclose($file);
            }
            $this->new_message('Datos de configuracion regional guardados correctamente.');
        }
    }
    
    private function share_extensions(){
        $fsext = new fs_extension();
        $fsext->name = 'pcn';
        $fsext->from = __CLASS__;
        $fsext->to = 'contabilidad_ejercicio';
        $fsext->type = 'fuente';
        $fsext->text = 'Plan Comptable Nationale Haitien';
        $fsext->params = FS_PATH.'plugins/haiti/extras/haiti.xml';
        $fsext->save();
    }
}
