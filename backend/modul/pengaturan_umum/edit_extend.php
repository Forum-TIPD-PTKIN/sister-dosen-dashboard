<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Pengaturan Umum</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>pengaturan-pendaftaran">Pengaturan Umum</a>
                        </li>
                        <li class="active"><?php echo $lang["edit"];?> Pengaturan Umum</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title"><?php echo $lang["edit"];?> Pengaturan Umum</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_pengaturan_pendaftaran" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pengaturan_umum/pengaturan_umum_action.php?act=up_extend">

                              <div class="form-group">
                <label for="Ada Penguji" class="control-label col-lg-2"><?php echo $data_edit->nama_pengaturan?></label>
                <div class="col-lg-1">
                <?php if ($data_edit->isi_pengaturan=="Y") {
                ?>
                  <input name="isi_pengaturan" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" checked id="isi_pengaturan">
                <?php
              } else {
                ?>
                  <input name="isi_pengaturan" data-on-text="Ya" data-off-text="Tidak" class="make-switch" type="checkbox" id="isi_pengaturan">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->


         <div class="form-group show-form">
           <div class="col-lg-2" style="text-align: right">
             <span class="btn btn-success add-attr"><i class="fa fa-plus"></i> Tambah Form Isian</span>
           </div>

           <div class="col-lg-8 show-select" style="display: none">
            <select class="form-control select-type">
             <option value="">Pilih Jenis Form</option>
             <option value="text">Text Singkat</option>
             <option value="paragraph">Paragraph</option>
             <option value="number">Number</option>
             <option value="date">Date</option>
             <option value="dropdown">Drop-down</option>
             <option value="multiple_choice">Pilihan Ganda</option>
            </select>
           </div>
         </div><!-- /.form-group -->

         <div class="isi_embed">
          <?php

    $data_array_label = array(
      'attr_type' => 'Jenis Isian',
      'attr_label' => 'Label Isian',
      'required' => 'Wajb disi',
      'dropdown_data' => 'Data Pilihan',
      'multiple_choice_data' => 'Data Pilihan'
    );
$data_json = array(
      'dropdown' =>
        array(
          "attr_type"=>"dropdown",
          "attr_name"=>"peran_peneliti",
          "attr_label"=>"Peran Penelitian/Penulisan",
          "required"=>"checked",
          "dropdown_data" => ""
        ),
      'multiple_choice' =>
        array(
          "attr_type"=>"multiple_choice",
          "attr_name"=>"peran_peneliti",
          "attr_label"=>"Apakah Bumi Bulat atau Datar",
          "required"=>"checked",
          "multiple_choice_data" => ""
        ),
     'number' =>
        array(
          "attr_type"=>"number",
          "attr_name"=>"sks_mk",
          "attr_label"=>"SKS Matakuliah",
          "data-rule-number"=>"true",
          "required"=>"checked",
        ),
      'text' =>
      array(
        "attr_type"=>"text",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "required"=>"checked"

      ),
      'date' =>
      array(
        "attr_type"=>"date",
        "attr_name"=>"tanggal_masuk",
        "attr_label"=>"Tanggal Masuk",
        "required"=>"checked"
      ),
      'paragraph' =>
      array(
        "attr_type"=>"paragraph",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "required"=>"checked"

      ),
      'textareamce' =>
            array(
        "attr_type"=>"textareamce",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "data-rule-minlength"=>"2",
        "data-msg-minlength"=>"At least two chars",
        "data-rule-maxlength"=>"4",
        "data-msg-maxlength"=>"At most fours chars",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi"

      ),
      'image' =>
            array(
        "attr_type"=>"image",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi",
        "allowed_type"=>"png|jpg|jpeg|gif|bmp"

      ),
      'file' =>
            array(
        "attr_type"=>"file",
        "attr_name"=>"sks_mk",
        "attr_label"=>"SKS Matakuliah",
        "required"=>"true",
        "data-msg-required"=>"Nama Wajib diisi",
        "allowed_type"=>"pdf|docx|xlsx"

      )
    );

  function get_type_name($arr) {
    $hasil = array();
    //$hasil[$arr['attr_type']] = $arr['attr_type'];
    $hasil[$arr['attr_name']] = $arr;
    return $hasil;
  }

  function get_edit_attr($arr) {
    $hasil = array();
    //$hasil[$arr['attr_type']] = $arr['attr_type'];
    $hasil[$arr['attr_name']] = $arr;
    return $hasil;
  }
          if ($data_edit->isi_pengaturan=='Y') {
            if ($data_edit->isi_extend!='') {
            $attr = $data_edit->isi_extend;
            $edit_attr = "";
            $readonly = "";
              $decode = json_decode($attr);
              $itterate = 1;



                $all_type = array_map('get_type_name', $db->converObjToArray($decode));

                //echo "<pre>";


              foreach ($all_type as $key_parent => $type_attr) {
                 echo '<div class="group-json" style="border: 1px solid #3c8cbc;border-radius: 10px;margin-bottom: 10px;"><div class="form-group move-field"><div class="col-lg-10 text-center" style="padding:5px;cursor: move;"><i class="fa fa-align-justify"></i></div></div>';

                 foreach ($data_json[$type_attr[key($type_attr)]['attr_type']] as $key => $value) {

                        if (in_array($key, array_keys($type_attr[key($type_attr)]))) {
                          $value =  $type_attr[key($type_attr)][$key];
                        }
                        //$value =  $type_attr[key($type_attr)][$key];

                        if ($key=='attr_type') {
                        ?>
                            <div class="form-group" style="display:none">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                <div class="col-lg-5">
                                  <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control" readonly>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif ($key=='required') {
                        if ($value=='on') {
                          $value = "checked";
                        } else {
                          $value = "";
                        }
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                <div class="col-lg-5">
                                  <input name="field[<?=$itterate;?>][<?=$key;?>]" data-on-text="Yes" data-off-text="No" class="make-switch" type="checkbox" <?=$value;?>>
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      } elseif ($key=='dropdown_data' || $key=='multiple_choice_data') {
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$data_array_label[$key];?></label>
                                 <div class="col-lg-9">
                        <?php


                        if (count($value)>0) {
                          foreach ($value['value'] as $index_data => $val_data) {
                            if ($index_data==0) {
                              $show = "style='display: none'";
                            } else {
                              $show = "";
                            }
                          ?>
                                    <div class="row row-clone">
                                        <!-- <div class="col-lg-2" style="padding-top:2px">
                                            <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Value" class="form-control value-data" value="<?=$val_data;?>"> </div> -->
                                        <div class="col-lg-10" style="padding-top:2px">
                                            <input type="text" name="field[<?=$itterate;?>][<?=$key;?>][value][]" placeholder="Opsi" class="form-control label-data" value="<?=$val_data;?>" required> </div>
                                       <div class="col-lg-2" style="padding-left:0;padding-top:2px"><span class="btn btn-success add-clone"><i class="fa fa-plus"></i></span> <span class="btn btn-danger remove-clone" <?=$show;?>><i class="fa fa-trash"></i></span></div>
                                    </div>
                          <?php
                          }
                        }
                        ?>
                         </div>
                            </div><!-- /.form-group -->
                          <?php
                      }  elseif($key=='attr_name') {
                          ?>
                              <div class="form-group" style="display:none">
                                  <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key;?></label>
                                  <div class="col-lg-5">
                                    <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
                                  </div>
                              </div><!-- /.form-group -->
                          <?php
                        }  else {
                         if (in_array($key, array_keys($data_array_label))) {
                            $key_label = $data_array_label[$key];
                          } else {
                            $key_label = $key;
                          }
                        ?>
                            <div class="form-group">
                                <label for="Jumlah Pembimbing" class="control-label col-lg-3"><?=$key_label;?></label>
                                <div class="col-lg-5">
                                  <input type="text" name="field[<?=$itterate;?>][<?=$key;?>]" value="<?=$value;?>" class="form-control">
                                </div>
                            </div><!-- /.form-group -->
                        <?php
                      }
                  }
                  echo '<div class="form-group"><div class="control-label col-lg-3"><span class="btn btn-danger hapus-group"><i class="fa fa-trash"></i></span></div></div><hr></div>';
                   $itterate++;
              }

          }
            }
            
?>
           
         </div>
                                         <input type="hidden" name="id" value="<?php echo $data_edit->id_pengaturan;?>">
              <input type="hidden" name="short_name" value="<?php echo $data_edit->short_name;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <a href="<?=base_index();?>pengaturan-pendaftaran" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
                                <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {

$('#isi_pengaturan').on('switchChange.bootstrapSwitch', function (event, state) {
   if (state==true) {
    $(".show-form").show();
    $(".isi_embed").show();
    } else {
    $(".show-form").hide();
    $(".isi_embed").hide();
    }
});

/*   $(".group-json").sortable({
           cancel: ".primary,select,input,checkbox",
           revert: 100,
           placeholder: "dashed-placeholder"
        });*/

    $( ".isi_embed" ).sortable({
      connectWith: ".isi_embed",
      handle: ".move-field",
         revert : 100,
      cancel: ".primary,select,input,checkbox",
      //placeholder: "portlet-placeholder ui-corner-all"
    });


$(document).on('click','.hapus-group',function() {
  $(this).parent().parent().parent().remove();
});


$("#for_jurusan").change(function(){
  if (this.value=='all') {
    $('#for_jurusan option').prop('selected', true);
    $("#for_jurusan option[value='all']").prop("selected", false);
    $("#for_jurusan").trigger("chosen:updated");
  }
});

$('.add-attr').click(function(){
    $('.show-select').toggle();
});

$('.select-type').change(function(){
tipe = this.value;
if (this.value!='') {
 $('.show-select').hide();
 $('.select-type option:first').prop('selected',true);
 itterate = $('.group-json').length;
 $.ajax({
 url : "<?=base_admin();?>modul/pengaturan_pendaftaran/get_form.php",
   type : "post",
   data : {tipe:tipe,itterate:itterate},
   success : function(data) {
     $('.isi_embed').append(data);
      $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });

   }
 });
}
});

        $(document).on('click','.add-clone',function() {
            var cloned = $(this).parent().parent().last().clone().insertAfter( $(this).parent().parent());

            //var cloned = $(".row-clone:last").clone(true);
            cloned.find('.value-data').val('');
            cloned.find('.label-data').val('');
             //cloned.find('.btn-info').removeClass('btn-info').addClass('btn-danger');
             //cloned.find('.fa-plus').removeClass('fa-plus').addClass('fa-minus');
            cloned.find('.remove-clone').show();

/*           var $newdiv = $(".row-clone:last").clone(true);
            $newdiv.find('input').each(function() {
                var $this = $(this);
               $this.attr('id', $this.attr('id').replace(/_(\d+)_/, function($0, $1) {
                    return '_' + (+$1 + 1) + '_';
                }));
                $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function($0, $1) {
                    return '[' + (+$1 + 1) + ']';
                }));
                $this.val('');
            });
            $newdiv.insertAfter('.row-clone:last');*/

        });
        $(document).on('click','.remove-clone',function() {
            var jml_element = $('.row-clone').length;
            if (jml_element>1) {
              $(this).parent().parent().remove();
            }
        });


      $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });
        
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
        
    
    $("#edit_pengaturan_pendaftaran").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
   errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("select2")) {
               element.parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }  else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },

        

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    window.location="<?=base_admin();?>pengaturan-umum"
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
