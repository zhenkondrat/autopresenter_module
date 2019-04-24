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

    function table_exists($table)
    {
        $result =  $this->db->query("SHOW TABLES LIKE '{$table}'");
        if( $result->num_rows == 1 )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        $result->free();
    }

    function insert_to_table($name)
    {
        $str = $name;
        $charset = mb_detect_encoding($str);
        $str = iconv($charset, "UTF-8", $str);
        $name = $str;
        $result =  $this->db->query("INSERT ignore INTO `" .
                                     DB_PREFIX .
                                    "zhen_not_founded`(`importName`, `id`) VALUES ('" .
                                     $name .
                                    "' , (select count(x.id)+1 from oc_zhen_not_founded x)   )");

        if($this->db->countAffected()>0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        $result->free();
    }

    function create_nofinded_table(){
        $result =
            $this->db->query("CREATE TABLE " . DB_PREFIX . "zhen_not_founded ( " .
                "importName varchar(255), " .
                "existModel varchar(255), " .
                "id int, " .
                "PRIMARY KEY (importName) " .
                ");");
    }

    public function get_nofinded(){
        /* create table for not finded elements*/
        if(!$this->table_exists(DB_PREFIX . "zhen_not_founded"))
            $this->create_nofinded_table();

        return $this->db->query("select * from `" . DB_PREFIX . "zhen_not_founded`");
    }

    function update_to_table($id, $model)
    {
        $str = $model;
        $charset = mb_detect_encoding($str);
        $str = iconv($charset, "UTF-8", $str);
        $name = $str;
        $result =  $this->db->query("UPDATE `" .
            DB_PREFIX .
            "zhen_not_founded` SET `existModel`='" .
            $name .
            "' WHERE `id`=" . $id);

        console.log("in model");
    }

    /*
      Result description:
      0 - is not updated
      1 - is updated row
      2 - not find row with that name
    */
    public function updatePrice ($item, $default_filter){
        if($item["new_price"]==0)
            return 0;

        $name = preg_replace('/\s+/', '', $item["name"]);
        $name = mb_strtolower($name, 'UTF-8');

        $str = $name;
        $charset = mb_detect_encoding($str);
        $str = iconv($charset, "UTF-8", $str);
        $name = $str;
        $name = preg_replace('/"/', '&quot;', $name);

        $filter = $default_filter == true ? "%" . $this->parse_name($name) : "%" . $this->parse_name($name)  . "%" ;

        //try get one element
        $query = "SELECT * FROM " . DB_PREFIX . "product  WHERE " .
            DB_PREFIX . "product.product_id = " .
            " (select distinct product_id " .
            " from ( SELECT  REPLACE(LOWER(TRIM(sku)), ' ', '') as convertString, product_id FROM " . DB_PREFIX . "product WHERE sku <>  '') tempTable " .
            " where convertString like '" . $filter . "')";

        $res = $this->db->query($query);

        if($this->db->countAffected()==0) {
            //if not find element by name, trying find by model number
            $query = "SELECT p.* FROM " . DB_PREFIX . "product p  WHERE " .
                "p.model = " .
                " (select distinct existModel " .
                " from ( SELECT  REPLACE(LOWER(TRIM(importName)), ' ', '') as convertString, existModel FROM " . DB_PREFIX . "zhen_not_founded WHERE importName <>  '') tempTable " .
                " where convertString like '" . $filter . "')";

            $res = $this->db->query($query);

            if($this->db->countAffected()==0)
                return 7;
            else {
                //try updated element
                $this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . $item["new_price"] . "' WHERE " .
                    DB_PREFIX . "product.model = " .
                    " (select distinct existModel " .
                    " from ( SELECT  REPLACE(LOWER(TRIM(importName)), ' ', '') as convertString, existModel FROM " . DB_PREFIX . "zhen_not_founded WHERE importName <>  '') tempTable " .
                    " where convertString like '" . $filter . "')"
                );

                if($this->db->countAffected()!=0)
                    return 8;
                else
                    return 9;
            }
        }
        else {
            //try updated element
            $this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . $item["new_price"] . "' WHERE " .
                DB_PREFIX . "product.product_id = " .
                " (select distinct product_id " .
                " from ( SELECT  REPLACE(LOWER(TRIM(sku)), ' ', '') as convertString, product_id FROM " . DB_PREFIX . "product WHERE sku <>  '') tempTable " .
                " where convertString like '" . $filter . "')");

            if($this->db->countAffected()!=0)
                return 8;
            else
                return 9;
        }

    }

    public function tryUpdatePrice ($name, $price, $default_filter){
        if($price==0)
            return 0;

        $name = preg_replace('/\s+/', '', $name);
        $name = mb_strtolower($name, 'UTF-8');

        $str = $name;
        $charset = mb_detect_encoding($str);
        $str = iconv($charset, "UTF-8", $str);
        $name = $str;
        $name = preg_replace('/"/', '&quot;', $name);

        $filter = $default_filter == true ? "%" . $this->parse_name($name) : "%" . $this->parse_name($name)  . "%" ;

        //if dublicate is exist then return 0
        $this->db->query("select distinct product_id " .
            " from ( SELECT REPLACE(LOWER(TRIM(sku)), ' ', '') as convertString, product_id FROM " . DB_PREFIX . "product WHERE sku <>  '') tempTable " .
            " where convertString like '" . $filter . "'");

        if($this->db->countAffected()>1)
            return 0;

        //try get one element
        $query = "SELECT * FROM " . DB_PREFIX . "product  WHERE " .
            DB_PREFIX . "product.product_id = " .
            " (select distinct product_id " .
            " from ( SELECT  REPLACE(LOWER(TRIM(sku)), ' ', '') as convertString, product_id FROM " . DB_PREFIX . "product WHERE sku <>  '') tempTable " .
            " where convertString like '" . $filter . "')";

        $res = $this->db->query($query);

        if($this->db->countAffected()==0) {
            //if not find element by name, trying find by model number
            $query = "SELECT p.* FROM " . DB_PREFIX . "product p  WHERE " .
                "p.model = " .
                " (select distinct existModel " .
                " from ( SELECT  REPLACE(LOWER(TRIM(importName)), ' ', '') as convertString, existModel FROM " . DB_PREFIX . "zhen_not_founded WHERE importName <>  '') tempTable " .
                " where convertString like '" . $filter . "')";

                //            SELECT p.*
                //FROM `oc_product` p
                //Where p.model =
                //                (select distinct existModel
                // from
                // ( SELECT  REPLACE(LOWER(TRIM(importName)), ' ', '') as convertString, existModel
                //      FROM  `oc_zhen_not_founded`
                //      WHERE importName <>  '') tempTable
                //      where convertString like '%2-проводный электропривод 12В SL-2')

                $res = $this->db->query($query);

                if($this->db->countAffected()==0)
                    return 2;
                else {
//                    var_dump($res);
                    /*get price from element*/
                    return floatval($res->rows[0]["price"]);
                }
        }
        else {
            /*get price from element*/
            return floatval($res->rows[0]["price"]);
        }
    }

    public function parse_name($name){
        $row = $name;
        // if (stripos($row, ',') ){
        //   $lastComaIndex = strripos($row, ',');
        //   $row = substr($row, 0, $lastComaIndex);
        // }
        // $row=str_replace(',','',$row);
        while( stripos($row, '\'') )
            $row=str_replace('\'','',$row);

        return $row;
    }

    public function check($row, $func){
        if(isset($row['name']) && isset($row['old_price']))
            return true;
        else
            return false;
    }

    public function upload($arr, $setting) {
        /* result array */
        $mas = [];
        $mas["skiped"] = [];
        $mas["wrong_data"] = [];
        $mas["updated"] = [];
        $mas["finded"] = [];
        $mas["nofinded"] = [];
        $mas["empty_cell"] = [];

        /* create table for not finded elements*/
        if(!$this->table_exists(DB_PREFIX . "zhen_not_founded"))
           $this->create_nofinded_table();


        /* get needed update attribute */
        $func = 0;

            if($setting['usePrice'] == true && $setting['useCount'] == false)
                $func = 2;
            else
                if($setting['usePrice'] == false && $setting['useCount'] == true)
                    $func = 3;

        /* get all rows from input array(from file)*/
        foreach($arr as $item){ // $index = current($arr);
            $usd = floatval($item[1])/$setting['USD'];
            $row = ['name' => $item[0], 'old_price' => $usd, 'new_price' => $usd ];

            if( $this->check($row, $func) ){ //if set name and price and count
                $res = -1;
                /* select wanted action */
                switch($func){
                    case 2:
                        if(is_numeric($item[1]))
                            $res = $this->tryUpdatePrice( $item[0], $item[1]/$setting['USD'], $setting['useDefaultSearch'] );
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
                    case -1:
                        array_push($mas["empty_cell"], $row);
                        break;
                    case 0:
                        array_push($mas["skiped"], $row);
                        break;
                    case 1:
                        array_push($mas["updated"], $row);
                        break;
                    case 2:
                        array_push($mas["nofinded"], $row);
//                        $this->insert_to_table($row['name']);
                        break;
                    default: {
                        $row['old_price'] = $res;
//                        var_dump($res);
                        if ($row['old_price'] == $row['new_price'])
                            array_push($mas["skiped"], $row);
                        else
                            array_push($mas["finded"], $row);
                    }
                }
            }
            else
                array_push($mas["empty_cell"], $row);
        }

        return $mas;
    }

    public function update($arr) {
        /* result array */
        $mas = [];
        $mas["updated"] = [];
        $mas["noupdated"] = [];
        $mas["wrong_data"] = [];
        $mas["finded"] = $arr["finded"];

        foreach($mas["finded"] as $item) {
//            $tmp = $item["new_price"];
//            $item["new_price"] = $item["old_price"];
//            $item["old_price"] = $tmp;

            if(is_numeric($item["new_price"]))
                $res = $this->updatePrice( $item, true );//TODO changeble filter value
            else
                array_push($mas["wrong_data"], $item);


            switch($res){
                case 9:
                    array_push($mas["noupdated"], $item);
                    break;
                case 8:
                    array_push($mas["updated"], $item);
                    break;
                default: {
                    array_push($mas["wrong_data"], $item);
                }
            }
        }
//        var_dump($mas);
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