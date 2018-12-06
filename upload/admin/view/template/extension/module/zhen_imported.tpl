<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-example" class="btn btn-primary">
          <i class="fa fa-save"></i>
        </button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div> 
  <div class="container-fluid">
   <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Результаты импортирования</h3>
      </div>
      <div class="panel-body">

        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-export" data-toggle="tab">Оновленые</a></li>
          <li><a href="#tab-import" data-toggle="tab">Не оновленые</a></li>
          <li><a href="#tab-finded" data-toggle="tab">Не найденые</a></li>
          <li><a href="#tab-settings" data-toggle="tab">Не верные данные</a></li>          
          <li><a href="#tab-empty" data-toggle="tab">С пустыми ячейками</a></li>
        </ul>

        <div class="tab-content">

          <div class="tab-pane active" id="tab-export">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Новая цена</th>
                      <th scope="col">Количество</th>
                    </tr> 
                  </thead>
                  <tbody>
                    <?php
                      $index=0;
                      foreach( $mas["updated"] as $item){
                        $index++;
                    ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['count'] ?></td>
                      </tr>                                    
                    <?php
                      } 
                    ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-import">
            <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Новая цена</th>
                      <th scope="col">Количество</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $index=0;
                      foreach( $mas["skiped"] as $item){
                        $index++;
                    ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['count'] ?></td>
                      </tr>                                    
                    <?php
                      } 
                    ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-finded">
            <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Новая цена</th>
                      <th scope="col">Количество</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $index=0;
                      foreach( $mas["nofinded"] as $item){
                        $index++;
                    ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['count'] ?></td>
                      </tr>                                    
                    <?php
                      } 
                    ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-settings">
            <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Новая цена</th>
                      <th scope="col">Количество</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $index=0;
                      foreach( $mas["wrong_data"] as $item){
                        $index++;
                    ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['count'] ?></td>
                      </tr>                                    
                    <?php
                      } 
                    ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-empty">
            <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Новая цена</th>
                      <th scope="col">Количество</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $index=0;
                      foreach( $mas["empty_cell"] as $item){
                        $index++;
                    ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['count'] ?></td>
                      </tr>                                    
                    <?php
                      } 
                    ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
