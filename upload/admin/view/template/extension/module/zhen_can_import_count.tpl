<?php echo $header; ?>
<?php echo $column_left;

function titleCount($count){
   if ($count == -1) {
      return '-';
      } elseif ($count == 1) {
      return 'Доступно';
      } elseif ($count == 2) {
          return 'Доступно (2-3 дня)';
      } if ($count == 3) {
          return 'Нет в наличии';
      } else {
          return 'Уточняйте';
      }
}
?>
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
        <h3 class="panel-title">Результаты предосмотра</h3>
      </div>
      <div class="panel-body">

        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-export" data-toggle="tab">Найденые</a></li>
          <li><a href="#tab-finded" data-toggle="tab">Пропущеные</a></li>
          <li><a href="#tab-nofinded" data-toggle="tab">Не найденые</a></li>
          <li><a href="#tab-settings" data-toggle="tab">Не верные данные</a></li>
          <li><a href="#tab-empty" data-toggle="tab">С пустыми ячейками</a></li>
        </ul>

        <div class="tab-content">

          <div class="tab-pane active" id="tab-export">
            <div class="btn-group dropup">
              <button type="button" class="btn btn-danger" style="margin-right: 25px; border-radius: 3px;" onclick="uploadData();">
                <span> Загрузить количество с прайса так как есть   <i class="fa fa-download fw" style="color: #ead801;"></i> </span>
              </button>
            </div>
            <form action="<?php echo $update; ?>" method="post" enctype="multipart/form-data" id="update">
              <input type="hidden"
                     id="expo"
                     name="mas"
                     value="<?php print base64_encode(serialize($mas)) ?>">
            </form>

                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Новое количество</th>
                    </tr> 
                  </thead>
                  <tbody>
                    <?php
                      $index=0;
                      foreach( $mas["finded"] as $item){
                        $index++;
                    ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['name'] ?></td>
                        <td><?= titleCount($item['new_count']) ?></td>
                      </tr>                                    
                    <?php
                      } 
                    ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-finded">
            <table class="table table-striped">
              <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Нозвание</th>
                <th scope="col">Новое количество</th>
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
                <td><?= titleCount($item['new_count']) ?></td>
              </tr>
              <?php
                      }
                    ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-nofinded">
            <div class="alert alert-success" id="success-alert">
              <button type="button" class="close" data-dismiss="alert">x</button>
              <strong> Елемент добавлен! </strong>
              Далее можете к елементу прописать соответствующую модель товара.
            </div>
            <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Нозвание</th>
                      <th scope="col">Добавить в ненайденые</th>
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
                        <td>
                          <div class="btn-group dropup">
                            <button type="button" class="btn btn-info insert" style="padding: 1px 6px;margin-right: 25px; border-radius: 3px;" data-toggle="modal" data-target="#myModal" data-item="<?= $item['name'] ?>">
                              <span> <i class="fa fa-plus fw" style="color: #ead801;"></i> </span>
                            </button>
                          </div>
                        </td>
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
                      <th scope="col">Новое количество</th>
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
                        <td><?= titleCount($item['new_count']) ?></td>
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
                      <th scope="col">Новое количество</th>
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
                        <td><?= titleCount($item['new_count']) ?></td>
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
<script type="text/javascript">
  $("#success-alert").hide();
  function showAlert() {
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
      $("#success-alert").slideUp(500);
    });
  };
  $('.insert').on('click', function() {
    var item =  $(this).data('item');
    var btn = $(this);
    $.ajax({
      url: 'index.php?route=extension/module/zhen_import/ajaxFuncToInsert&token=<?= $token ?>',
      type:'POST',
      data: { name: item },
      success: function(res) {
          btn.css('background-color', '#333');
          showAlert();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });

  function uploadData() {
    $('#update').submit();
  }
</script>