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
      
