# autopresenter_module
import prices and export data from db

For adding module to left menu.
-Add code to controller:

`$data['zhenkondrat'] = $this->url->link('extension/module/zhen_import',  'token=' . $this->session->data['token'], true);`	
 
after:
```
// Menu
$data['menus'][] = array(
	'id'       => 'menu-dashboard',
...
);
```
In `admin\controller\common\column_left.php` file.
      
-Add code to view:	

`<li><a href="<?php echo $zhenkondrat; ?>"><i class="fa fa-car fw"></i><span>АП</span></a></li>`
     
inside:
```   
<ul id="menu">
    <?php foreach ($menus as $menu) { ?>
      ...
    <?php } ?>
    <!-- zhenkondrat -->
    <li><a href="<?php echo $zhenkondrat; ?>"><i class="fa fa-car fw"></i><span>АП</span></a></li>
  </ul>
```
In   `admin\view\template\common\column_left.tpl` file.
      

On frontend in `catalog\controller\product` file change:
```
    if ($product_info['quantity'] <= 0) {
        $data['stock'] = $product_info['stock_status'];
    } elseif ($this->config->get('config_stock_display')) {
        $data['stock'] = $product_info['quantity'];
    } else {
        $data['stock'] = $this->language->get('text_instock');
    }
```
to:

```
    if ($product_info['quantity'] == 1) {
        $data['stock'] = 'Доступно';
        } elseif ($product_info['quantity'] == 2) {
            $data['stock'] = 'Доступно (2-3 дня)';
        } if ($product_info['quantity'] == 3) {
            $data['stock'] = 'Нет в наличии';
        } if ($product_info['quantity'] == 100) {
            $data['stock'] = 'Уточняйте';
        } else {
            $data['stock'] = 'Уточняйте';
        }

```
