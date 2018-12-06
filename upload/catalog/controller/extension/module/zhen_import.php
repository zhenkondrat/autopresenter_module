<?php

class ControllerExtensionModuleExample extends Controller {

  public function index () {
    $view = 'extension/module/example.tpl';
    // проверяем - существует ли кастомный шаблон в текущей теме
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $view)) {
      $view = $this->config->get('config_template') . '/template/' . $view;
    }

    return $this->load->view($view);
  }

}