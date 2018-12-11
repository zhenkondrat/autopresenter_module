<?php

class ControllerExtensionModuleZhenImport extends Controller {
  // index - выполняется по умолчанию если в url не указан конкретный action!
  public function index () {
    // массив переменных для представления
    $data = array();
    // загружаем языковой пакет
    $this->load->language('extension/module/zhen_import');
    // устанавливаем заголовок окна
    $this->document->setTitle($this->language->get('heading_title'));

    // загружаем модель setting
    $this->load->model('setting/setting');

    $this->load->model('extension/module/zhen_import');
    //$this->model_extension_module_zhen_import->upload("s");
 
    // если от пользователя пришёл POST запрос
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      // заполняем настройки модуля из него
      $this->model_setting_setting->editSetting('zhen_import', $this->request->post);
      // и редиректим пользователя к списку модулей
      $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
    }

    // устанавливаем переменные представления
    $data['heading_title'] = $this->language->get('heading_title');
    // эти переводы уже подгружены Opencart из ru-ru.php:
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['entry_status'] = $this->language->get('entry_status');

    $data['action'] = $this->url->link('extension/module/zhen_import', 'token=' . $this->session->data['token'], true);
   
    $data['usd'] = 25;    

    $data['import'] = $this->url->link('extension/module/zhen_import/upload', 'token=' . $this->session->data['token'], $this->ssl);
    $data['export'] = $this->url->link('extension/module/zhen_import/download', 'token=' . $this->session->data['token'], $this->ssl);

    $data['post_max_size'] = $this->return_bytes( ini_get('post_max_size') );
    $data['upload_max_filesize'] = $this->return_bytes( ini_get('upload_max_filesize') );
    // добавляем кусочки шаблона в качестве переменных
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    // выводим результат пользователю
    $this->response->setOutput($this->load->view('extension/module/zhen_import.tpl', $data));
  }

  public function upload() {
    // загружаем модель setting
    $this->load->model('setting/setting');
    $this->load->model('extension/module/zhen_import');

    //change max_execution_time prolonging time to request
    set_time_limit(1000);
    
    chdir( DIR_SYSTEM.'PHPExcel' );
      require_once( 'Classes/PHPExcel.php' );
    $inputFileName = $this->request->files['upload']['tmp_name'];
    //  Read your Excel workbook
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }
    //  Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();

    /*init var*/
    $colname=0;
    $colprice=0;
    $colcount=0;
    $colusd=0;
    $isusd=false;
    $isprice=false;
    $iscount=false;
    $filter=false;
    $is1c=false;
    /*set data*/
    if (isset( $this->request->post['colname'] ) && ($this->request->post['colname']!='')) {
          $colname = $this->request->post['colname'];
        }
    if (isset( $this->request->post['colprice'] ) && ($this->request->post['colprice']!='')) {
          $colprice = $this->request->post['colprice'];
        }
    if (isset( $this->request->post['colcount'] ) && ($this->request->post['colcount']!='')) {
          $colcount = $this->request->post['colcount'];
        }
    if (isset( $this->request->post['colusd'] ) && ($this->request->post['colusd']!='')) {
          $colusd = $this->request->post['colusd'];
        }
    if (isset( $this->request->post['isusd'] ) && ($this->request->post['isusd']!='')) {
      $isusd = true;
    }
    if (isset( $this->request->post['isprice'] ) && ($this->request->post['isprice']!='')) {
      $isprice = true;
    }
    if (isset( $this->request->post['iscount'] ) && ($this->request->post['iscount']!='')) {
      $iscount = true;
    }
    if (isset( $this->request->post['is1c'] ) && ($this->request->post['is1c']!='')) {
      $is1c = true;
    }
    if (isset( $this->request->post['groupRadios'] ) && ($this->request->post['groupRadios']!='')) {
      if($this->request->post['groupRadios']=='r1')
        $filter = true;
      else
        if($this->request->post['groupRadios']=='r2')
          $filter = false;
    }

    /*read data from file to array*/
    $rowData=[];
    $needRow=[];
    for ($row = 1; $row <= $highestRow; $row++){ 
        $rowData[$row] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                        NULL,
                                        TRUE,
                                        FALSE);

        $needRow[$row][0] =  $rowData[$row][0][$colname-1];
        $needRow[$row][1] =  $rowData[$row][0][$colprice-1];
        $needRow[$row][2] =  $rowData[$row][0][$colcount-1];
    }

    /*seting array*/
    $setting_arr=[];
    $setting_arr['USD'] = $isusd ? $colusd : 1;
    $setting_arr['usePrice'] = $isprice;
    $setting_arr['useCount'] = $iscount;
    $setting_arr['useDefaultSearch'] = $filter;
    $setting_arr['update1C'] = $is1c;

    /* get result from model method*/
    $mas=null;
    $mas=$this->model_extension_module_zhen_import->upload($needRow, $setting_arr);

    /*response customizing*/
    $this->write_log(var_export($mas, true));
    // загружаем языковой пакет
    $this->load->language('extension/module/zhen_import');
    // устанавливаем заголовок окна
    $this->document->setTitle($this->language->get('heading_title'));
    $data['heading_title'] = $this->language->get('heading_title');
    // добавляем кусочки шаблона в качестве переменных
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    /*response array*/
    $data['mas'] = $mas;    
    /*send data to view*/
    $this->response->setOutput($this->load->view('extension/module/zhen_imported.tpl', $data));
  }
  
  public function download() {
    // загружаем модель setting
    $this->load->model('setting/setting');
    $this->load->model('extension/module/zhen_import');
    //  Excel 
    chdir( DIR_SYSTEM.'PHPExcel' );
      require_once( 'Classes/PHPExcel.php' );
    //  Read your Excel workbook
    try {
        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("ThinkPHP")
                ->setLastModifiedBy("zhenkondrat")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test doc for Office 2007 XLSX, generated by PHPExcel.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getActiveSheet()->setTitle('Export autopresenter');

    } catch(Exception $e) {
        die('Error creating file : '.$e->getMessage());
    }
    
    /* get result from model method*/
    $mas=null;
    $mas = $this->model_extension_module_zhen_import->download();

    //setCellValueByColumnAndRow($column, $row, $value)
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, "№");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 1, "Название");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 1, "Цена");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 1, "Количество");

    $i=0;
    foreach ($mas as $item) {
      $i++;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i+1, $i);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $i+1, $item['name']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $i+1, $item['price']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $i+1, $this->getCountDiscription($item['count']) );
    }

    // If you want to output e.g. a PDF file, simply do:
    //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
    //$objWriter->save('ExportData.xlsx');
    // Write file to the browser
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Export.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');

  }

  protected function return_bytes($val)  {
    $val = trim($val);
  
    switch (strtolower(substr($val, -1)))
    {
      case 'm': $val = (int)substr($val, 0, -1) * 1048576; break;
      case 'k': $val = (int)substr($val, 0, -1) * 1024; break;
      case 'g': $val = (int)substr($val, 0, -1) * 1073741824; break;
      case 'b':
        switch (strtolower(substr($val, -2, 1)))
        {
          case 'm': $val = (int)substr($val, 0, -2) * 1048576; break;
          case 'k': $val = (int)substr($val, 0, -2) * 1024; break;
          case 'g': $val = (int)substr($val, 0, -2) * 1073741824; break;
          default : break;
        } break;
      default: break;
    }
    return $val;
  }

  public function write_log($log_msg)    {
    $log_filename = DIR_APPLICATION . "log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('Y-m-d_H-i-s', time()) . '.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
  }

  public function getCountDiscription($count_id)    {
      switch($count_id){
        case 1: 
          return "В наличии"; 
          break;
        case 2: 
          return "В наличии (2-3 дня)";
          break;
        case 3: 
          return "Нет в наличии";
          break;
        default:
          return "Наличие уточняйте";
      }   
  }

}