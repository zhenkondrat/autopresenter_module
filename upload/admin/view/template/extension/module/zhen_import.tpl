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
          <h3 class="panel-title">Действия</h3>
        </div>
        <div class="panel-body">

          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-export" data-toggle="tab">Импорт: розница</a></li>
            <li><a href="#tab-nonfounded" data-toggle="tab">Не найденые позиции</a></li>
          </ul>

          <div class="tab-content">

            <div class="tab-pane active" id="tab-export">
                <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="import" class="form-horizontal">
                  <div class="row">
                      <div class="col-md-6">
                        <div class="list-group">
                          <div class="list-group-item active">
                            <h4 class="list-group-item-heading">Главные данные <i class="fa fa-table fw" style="color: #ead801;"></i></h4>
                            <p class="list-group-item-text">...</p>
                          </div>
                          <div class="list-group-item">
                            <h4 class="list-group-item-heading">Номер колонки с названием</h4>
                            <input type="text" name="colname" value="2" class="form-control" style="width: auto;display: inline-block;" />
                          </div>
                          <div class="list-group-item">
                            <h4 class="list-group-item-heading">Номер колонки с ценой</h4>
                            <input type="text" name="colprice" value="5" class="form-control" style="width: auto;display: inline-block;"  />
                            <input type="radio" name="groupUpdate" value="isprice" checked/> (оновить цену)
                          </div>
                          <div class="list-group-item">
                            <h4 class="list-group-item-heading">Номер колонки с количеством</h4>
                            <input type="text" name="colcount" value="6" class="form-control" style="width: auto;display: inline-block;" />
                            <input type="radio" name="groupUpdate" value="iscount"/> (оновить количество)
                          </div>
                          <div class="list-group-item">
                            <h4 class="list-group-item-heading">Bибирите файл для импорта</h4>
                            <div class="alert alert-info" role="alert">
                              <input type="file" name="upload" id="upload" class="form-control btn" style="display: inline-block;"/>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="list-group">
                          <div class="list-group-item active">
                            <h4 class="list-group-item-heading">Надстройки <i class="fa fa-cog fw" style="color: #ead801;"></i> </h4>
                            <p class="list-group-item-text">...</p>
                          </div>
                          <div class="list-group-item">
                            <h4 class="list-group-item-heading">Курс доллара <i class="fa fa-dollar fw" style="color: #1e91cf;"></i></h4>
                            <div class="btn-group dropup">
                              <input type="text" name="colusd" value="<?php echo $usd; ?>" class="form-control" style="width: auto;display: inline-block;" />
                              <input type="checkbox" name="isusd" value="isusd" checked /> (делить на курс)
                            </div>
                          </div>
                          <div class="list-group-item">
                            <h4 class="list-group-item-heading">Поиск наименований в БД <i class="fa fa-search fw"  style="color: #1e91cf;"></i></h4>
                            <div class="btn-group dropup">
                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="radio1" name="groupRadios" value="r1" checked>
                                <label class="custom-control-label" for="radio1">Когда строка <span style="color: #aa3333;">закачивается</span> на искомое</label>
                              </div>
                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="radio2" name="groupRadios" value="r2">
                                <label class="custom-control-label" for="radio2">Когда искомое <span style="color: #aa3333;">в середине</span> строки</label>
                              </div>
                            </div>
                          </div>
                           <div class="list-group-item">
                            <h4 class="list-group-item-heading">Експорт данных c БД </h4>
                              <a onclick="exportData();" class="btn btn-primary"><span>Експорт <i class="fa fa-upload fw"  style="color: #ead801;"></i></span></a>
                          </div>
                        </div>
                        <div class="alert alert-danger" role="alert">
                          <strong>Импортирование</strong> данных с <strong>1С</strong> по количеству остатков делать после Загрузки прайсов, что бы перезаписать количество <i class="fa fa-exclamation-triangle fw" style="color: #aa3333;"></i>
                        </div>
                      </div>
                  </div>
                  <div class="btn-group dropup">
                      <a onclick="uploadData();" class="btn btn-primary" style="margin-right: 25px;    border-radius: 3px;">
                        <span>Импорт данных <i class="fa fa-download fw"  style="color: #ead801;"></i></span>
                      </a>
                      <div class="alert alert-warning" role="alert" style=" margin-top: -3px; ">
                          <input type="checkbox" name="is1c" value="is1c" /> (отметить когда обновлять количество с 1С) <i class="fa fa-check-circle fw" style="color: #f38733;"></i>
                      </div>
                 </div>
                </form>
            </div>
                <form action="<?php echo $export; ?>" method="post" enctype="multipart/form-data" id="export">
                  <input type="hidden" name="export" value="teru">
                </form>


            <div class="tab-pane" id="tab-nonfounded">
              <div class="alert alert-success" id="success-alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong> Значение модели успешно измененно. </strong>
              </div>
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ненайденый елемент</th>
                    <th scope="col">Модель сущеcтвуещего елемента</th>
                    <th scope="col">Действия над елементами</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                        $index=0;
                        $new_mas = get_object_vars($mas)["rows"];

                        foreach($new_mas as $item){
                          $index++;
                      ?>
                      <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><?= $item['importName'] ?></td>
                        <td><span id="model<?= $item['id'] ?>"><?php if($item['existModel']!=NULL) echo $item['existModel']; ?></span></td>
                        <td>
                          <div class="btn-group dropup">
                            <button type="button" class="btn btn-success setdata" style="padding: 1px 6px; margin-right: 25px; border-radius: 3px;" data-toggle="modal" data-target="#myModal" data-id="<?= $item['id'] ?>" data-importname="<?= $item['importName'] ?>" data-existmodel="<?= $item['existModel'] ?>">
                              <span> <i class="fa fa-pencil fw" style="color: #ead801;"></i> </span>
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
          </div>
      </div>
    </div>
    </div>

      <!-- Modal -->
      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Изминение записи #<span id="modalspan"></span></h4>
            </div>
            <div class="modal-body">
              <div class="list-group-item">
                <h4 class="list-group-item-heading">Название не найденого</h4>
                <input type="text" name="importName" value="" class="form-control" style="width: 100%; display: inline-block;" />
              </div>
              <div class="list-group-item">
                <h4 class="list-group-item-heading">Модель товара для связки</h4>
                <input type="text" name="existModel" value="" class="form-control" style="width: auto;display: inline-block;" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="zhen" data-dismiss="modal">Изменить значение</button>
            </div>
          </div>

        </div>
      </div>
</div>
<script type="text/javascript"><!--

  $("#success-alert").hide();
  function showAlert() {
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
      $("#success-alert").slideUp(500);
    });
  };

  $('.setdata').on('click', function() {
    var id = $(this).data('id');
    var importname = $(this).data('importname');
    var existmodel = $(this).data('existmodel');
    $("#modalspan").text(id);
    $("input[name='importName']").val(importname);
    $("input[name='existModel']").val(existmodel);
  });

  $('#zhen').on('click', function() {
    var id = $("#modalspan").text();
    var existmodel = $("input[name='existModel']").val();
    $.ajax({
      url: 'index.php?route=extension/module/zhen_import/ajaxFuncToUpdate&token=<?= $token ?>&id=' + id + '&model=' + existmodel,
      type:'POST',
      success: function(htmlText) {
        $("#model"+id).text(existmodel);
        showAlert();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });

function checkFileSize(id) {
  // See also http://stackoverflow.com/questions/3717793/javascript-file-upload-size-validation for details
  var input, file, file_size;

  if (!window.FileReader) {
    // The file API isn't yet supported on user's browser
    return true;
  }

  input = document.getElementById(id);
  if (!input) {
    // couldn't find the file input element
    return true;
  }
  else if (!input.files) {
    // browser doesn't seem to support the `files` property of file inputs
    return true;
  }
  else if (!input.files[0]) {
    // no file has been selected for the upload
    alert( "<?php echo $error_select_file; ?>" );
    return false;
  }
  else {
    file = input.files[0];
    file_size = file.size;
    <?php if (!empty($post_max_size)) { ?>
    // check against PHP's post_max_size
    post_max_size = <?php echo $post_max_size; ?>;
    if (file_size > post_max_size) {
      alert( "<?php echo $error_post_max_size; ?>" );
      return false;
    }
    <?php } ?>
    <?php if (!empty($upload_max_filesize)) { ?>
    // check against PHP's upload_max_filesize
    upload_max_filesize = <?php echo $upload_max_filesize; ?>;
    if (file_size > upload_max_filesize) {
      alert( "<?php echo $error_upload_max_filesize; ?>" );
      return false;
    }
    <?php } ?>
    return true;
  }
}

function uploadData() {
  if (checkFileSize('upload')) {
    $('#import').submit();
  }
}

function exportData() {
     $('#export').submit();
}

function isNumber(txt){ 
  var regExp=/^[\d]{1,}$/;
  return regExp.test(txt); 
}


//--></script>
<?php echo $footer; ?>
