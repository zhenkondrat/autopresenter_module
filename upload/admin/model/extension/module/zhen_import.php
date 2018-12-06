<?php

class ModelExtensionModuleZhenImport extends Model{

  public function getAll () {
    $comments = array();
    $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "comments (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(255) NOT NULL,
      `image` VARCHAR(255) NOT NULL,
      `text` TEXT NOT NULL,
      PRIMARY KEY(`id`)
    )");
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "comments ORDER BY id");
    foreach ($query->rows as $result) {
      $comments[] = $result;
    } 
    return $comments;
  }

  /*
    Result description:
    0 - is not updated
    1 - is updated row
    2 - not find row with that name
  */
  public function updatePrice ($name, $price, $default_filter){
     $filter = $default_filter == true ? "%" . $this->parse_name($name) : "%" . $this->parse_name($name)  . "%" ;

    $this->db->query("SELECT * FROM " . DB_PREFIX . "product  WHERE " . 
      DB_PREFIX . "product.product_id  IN ( SELECT product_id FROM " . DB_PREFIX . "product_description  WHERE name like '" . $filter . "')");
    if($this->db->countAffected()==0)
      return 2;

    $this->db->query("UPDATE " . DB_PREFIX . "product SET price = '".$price."' WHERE " . 
      DB_PREFIX . "product.product_id  IN ( SELECT product_id FROM " . DB_PREFIX . "product_description  WHERE name like '" . $filter . "')");
    if($this->db->countAffected()!=0)
       return 1;

    return 0;
  }
  /*
    quantity id description:
      1 - present product
      2 - present product(2-3 day)
      3 - absent product
      100 - need specify
  */
  public function updateCount ($name, $count, $default_filter){
    $filter = $default_filter == true ? "%" . $this->parse_name($name) : "%" . $this->parse_name($name)  . "%" ;

    $this->db->query("SELECT * FROM " . DB_PREFIX . "product  WHERE " . 
      DB_PREFIX . "product.product_id  IN ( SELECT product_id FROM " . DB_PREFIX . "product_description  WHERE name like '" . $filter . "')");
    if($this->db->countAffected()==0)
      return 2;
    //if( strpos( $haystack, $needle ) !== false)
    $data = 100;
    if(    (strpos( $count, "-" ) !== false) 
        || (strpos( $count, "Нет" ) !== false) 
        || (strpos( $count, "нет" ) !== false) 
        || (strpos( $count, "НЕТ" ) !== false)
        || (strpos( $count, "Y" ) !== false)
        || (strpos( $count, "y" ) !== false)
    ) 
       $data = 3; // is absent
    else 
       $data = 1;// is present

    $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '".$data."' WHERE " . 
    DB_PREFIX . "product.product_id  IN ( SELECT product_id FROM " . DB_PREFIX . "product_description  WHERE name like '" . $filter . "')");
    if($this->db->countAffected()!=0)
       return 1;

    return 0;
  }

  public function updateCount1C ($name, $count, $default_filter){
     $filter = $default_filter == true ? "%" . $this->parse_name($name) : "%" . $this->parse_name($name)  . "%" ;

    $select = $this->db->query("SELECT * FROM " . DB_PREFIX . "product  WHERE " . 
      DB_PREFIX . "product.product_id  IN ( SELECT product_id FROM " . DB_PREFIX . "product_description  WHERE name like '" . $filter . "')");
    if($this->db->countAffected()==0)
      return 2;
    //if( strpos( $haystack, $needle ) !== false)
    $data = 100;
    if(    (strpos( $count, "-" ) !== false) 
        || (strpos( $count, "Нет" ) !== false) 
        || (strpos( $count, "нет" ) !== false) 
        || (strpos( $count, "НЕТ" ) !== false)
        || (strpos( $count, "Y" ) !== false)
        || (strpos( $count, "y" ) !== false)
        || ( $count == "0" )
    ) 
       $data = 3; // is absent
    else 
       $data = 1;// is present

    $quantity = 0;   
    foreach ($select->rows as $result) {
        $quantity = $result['quantity'];
    }
    if ( $quantity==1 && $data == 3)
        $data = 2; // 2-3 days

    $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '".$data."' WHERE " . 
    DB_PREFIX . "product.product_id  IN ( SELECT product_id FROM " . DB_PREFIX . "product_description  WHERE name like '" . $filter . "')");
    if($this->db->countAffected()!=0)
       return 1;

    return 0;
  }

  public function parse_name($name){
    $row = $name;
    if (stripos($row, ',') ){
      $lastComaIndex = strripos($row, ',');
      $row = substr($row, 0, $lastComaIndex);
    }
    // $row=str_replace(',','',$row);
    while( stripos($row, '\'') )
      $row=str_replace('\'','',$row);

    return $row;
  }

  public function updatePriceAndCount ($name, $price, $count, $default_filter){
    $p = $this->updatePrice($name, $price, $default_filter);
    $c = $this->updateCount($name, $count, $default_filter);

   switch ($p + $c) {
    case 1:
       return 1;
       break;
    case 2:
       return 1;
       break;
    case 3:
       return 1;
       break;
    case 4:
       return 2;
       break;     
    default:
       return 0;
       break;
   }
  }

  public function check($row, $func){
    switch($func){
      case 1: 
        if(isset($row['name']) && isset($row['price']) && isset($row['count']))
          return true; 
        break;
      case 2:
        if(isset($row['name']) && isset($row['price']))
          return true;      
        break;
      case 3: 
        if(isset($row['name']) && isset($row['count']))
          return true;     
        break;
      default:
        return true;
    }
    return false;
  }

  public function upload($arr, $setting) {
    /* result array */
    $mas = [];    
    $mas["skiped"] = [];
    $mas["wrong_data"] = [];
    $mas["updated"] = [];
    $mas["nofinded"] = [];
    $mas["empty_cell"] = [];
    /* get needed update attribute */ 
    $func = 0;
    if($setting['usePrice'] == true && $setting['useCount'] == true)
      $func = 1;
    else
      if($setting['usePrice'] == true && $setting['useCount'] == false)
        $func = 2;
      else
        if($setting['usePrice'] == false && $setting['useCount'] == true)
          $func = 3;

    /* get all rows from input array(from file)*/
    foreach($arr as $item){ // $index = current($arr);
      $row = ['name' => $item[0], 'price' => $item[1], 'count' => $item[2] ];
      if( $this->check($row, $func) ){ //if set name and price and count
          $res = -1;    
          /* select wanted action */
          switch($func){
            case 1: 
              if(is_numeric($item[1]))
                $res = $this->updatePriceAndCount( $item[0], $item[1]/$setting['USD'], $item[2], $setting['useDefaultSearch'] ); 
              else
                array_push($mas["wrong_data"], $row);  
              break;
            case 2:
              if(is_numeric($item[1])) 
                $res = $this->updatePrice( $item[0], $item[1]/$setting['USD'], $setting['useDefaultSearch'] );
              else
                array_push($mas["wrong_data"], $row);      
              break;
            case 3: 
                if( $setting['update1C'] == true )
                  $res = $this->updateCount1C( $item[0], $item[2], $setting['useDefaultSearch'] ); 
                else 
                  $res = $this->updateCount( $item[0], $item[2], $setting['useDefaultSearch'] );    
              break;
          }

          /*push at log_arr for result*/
          switch($res){
            case 0: 
              array_push($mas["skiped"], $row);  
              break;
            case 1: 
              array_push($mas["updated"], $row);  
              break;
            case 2: 
              array_push($mas["nofinded"], $row);  
              break;
            default:
              array_push($mas["empty_cell"], $row);  
          }       
      }
      else
        array_push($mas["empty_cell"], $row);  
    }

    return $mas;
  }

  public function download(){      
    $select = $this->db->query("SELECT pd.name, p.price, p.quantity "
                              ." FROM " . DB_PREFIX . "product p, " . DB_PREFIX . "product_description pd "
                              ." WHERE p.product_id  = pd.product_id ");

    $arr = [];   
    foreach ($select->rows as $result) {
        $row = ['name' => $result['name'], 'price' => $result['price'], 'count' => $result['quantity'] ];
        array_push($arr, $row);
    }

    return $arr;
  }

}