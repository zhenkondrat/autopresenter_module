# autopresenter_module
import prices and export data from db


for adding module to left menu:
 - add code to controller: 		
			$data['zhenkondrat'] = $this->url->link('extension/module/zhen_import',  'token=' . $this->session->data['token'], true);	
      
      after:
      // Menu
			$data['menus'][] = array(
				'id'       => 'menu-dashboard',
        ...
			);
      
      In admin\controller\common\column_left.php file.
    
    - add code to view: 	
      <li><a href="<?php echo $zhenkondrat; ?>"><i class="fa fa-car fw"></i><span>АП</span></a></li>
     
     after:
      <ul id="menu">
    <?php foreach ($menus as $menu) { ?>
      ...
    <?php } ?>
    <!-- zhenkondrat -->
    <li><a href="<?php echo $zhenkondrat; ?>"><i class="fa fa-car fw"></i><span>АП</span></a></li>
  </ul>
      
     In   admin\view\template\common\column_left.tpl file.
      
