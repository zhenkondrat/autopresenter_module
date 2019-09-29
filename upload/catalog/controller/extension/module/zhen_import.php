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

}