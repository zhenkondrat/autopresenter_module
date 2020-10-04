<?php

class ControllerExtensionModuleZhenImport extends Controller {

  public function index () {
    $view = 'extension/module/zhen_import.tpl';
    // check template in theme
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $view)) {
      $view = $this->config->get('config_template') . '/template/' . $view;
    }

    return $this->load->view($view);
  }

  //Discription
  //for use this controller in other controller need (load):
  //$data['zhen_import'] = $this->load->controller('extension/module/zhen_import');
  //and echo in other view:  echo $zhen_import;


    /*
     * for autopresenter need update controller
     * \catalog\controller\product\product.php
     *
     * 284 row
     *      // if ($product_info['quantity'] <= 0) {
			// 	$data['stock'] = $product_info['stock_status'];
			// } elseif ($this->config->get('config_stock_display')) {
			// 	$data['stock'] = $product_info['quantity'];
			// } else {
			// 	$data['stock'] = $this->language->get('text_instock');
			// }
			/*
			  101 - present product UPDATE `oc_product` SET `quantity`=101 WHERE `quantity`=1
		      102 - present product(2-3 day) UPDATE `oc_product` SET `quantity`=102 WHERE `quantity`=2
		      103 - absent product UPDATE `oc_product` SET `quantity`=103 WHERE `quantity`=3
		      100 - need specify
		     */
        /*
         *  need next code:
            if ($product_info['quantity'] == 101) {
            $data['stock'] = 'В наличии';
            } elseif ($product_info['quantity'] == 102) {
            $data['stock'] = 'В наличии (2-3 дня)';
            } if ($product_info['quantity'] == 99) {
                $data['stock'] = 'Отсуцтвует';
            } if ($product_info['quantity'] == 100) {
                $data['stock'] = 'Уточняйте';
            }
         */
}