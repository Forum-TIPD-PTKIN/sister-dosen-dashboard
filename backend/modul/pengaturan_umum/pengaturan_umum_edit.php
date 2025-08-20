<?php
session_start();
require "../../inc/config.php";
$data_edit = $db->fetchSingleRow("tb_master_pengaturan_umum", "id_pengaturan", $_POST['id_data']);
?>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_pengaturan_umum" method="post" class="form-horizontal" action="<?php echo base_admin();?>modul/pengaturan_umum/pengaturan_umum_action.php?act=up">

              <div class="form-group">
                <label for="Nama Pengaturan" class="control-label col-lg-2">Nama Pengaturan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama_pengaturan" value="<?php echo $data_edit->nama_pengaturan;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

                <?php
                if ($data_edit->type_pengaturan=='image') {
                    ?>
  <input type="hidden" name="type_pengaturan" value="image">
                             <div class="form-group">
                        <label class="control-label col-lg-2">Image </label>
                        <div class="col-lg-10">
              <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                             <img src="<?php echo $data_edit->isi_pengaturan?>" class="myImage">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                            <div>
                              <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span> <span class="fileinput-exists">Change</span>
                                <input type="file" name="gambar" accept="image/*">
                              </span>
                              <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.form-group -->
                    <?php
                } elseif ($data_edit->type_pengaturan=='radio') {
                  ?>
                      <input type="hidden" name="type_pengaturan" value="radio">
                <div class="form-group">
                  <label for="isi_pengaturan" class="control-label col-lg-2"><?php echo $data_edit->nama_pengaturan?></label>
                      <div class="col-lg-10">
                        
                <div class="radio radio-success radio-inline">
                  <input type="radio" name="isi_pengaturan"  id="radio1" value="Y" <?=($data_edit->isi_pengaturan=="Y")?"checked":"";?> >
                    <label for="radio1" style="padding-left: 5px;">
                      Ya
                    </label>
                </div>
                
                <div class="radio radio-success radio-inline">
                  <input type="radio" name="isi_pengaturan"  id="radio2" value="N" <?=($data_edit->isi_pengaturan=="N")?"checked":"";?> >
                    <label for="radio2" style="padding-left: 5px;">
                      Tidak
                    </label>
                </div>
                
                      </div>
                </div><!-- /.form-group -->
                  <?php
                } elseif ($data_edit->type_pengaturan=='sosmed') {
                    ?>
                    <input type="hidden" name="type_pengaturan" value="sosmed">
                    <div class="form-group">
                      <label for="isi_pengaturan" class="control-label col-lg-2"><?php echo $data_edit->nama_pengaturan?></label>
                          <div class="col-lg-10">
                            <?php
                            $data_sosmed = json_decode($data_edit->isi_pengaturan);
                            foreach ($data_sosmed as $name_sosmed => $url_sosmed) {
                                ?>
                            <div class="row" style="margin-bottom:5px">
                                 <div class="col-lg-3">
                                <input type="text" name="nama_sosmed[]" value="<?=$name_sosmed;?>" class="form-control" readonly> 
                                </div>
                                 <div class="col-lg-9">
                                <input type="text" name="url_sosmed[]" value="<?=$url_sosmed;?>" placeholder="Masukan URL Sosmed <?=$name_sosmed;?>" class="form-control"> 
                                </div>
                            </div>
                                <?php
                            }
                            ?>
                    </div><!-- /.form-group -->
                </div>
                <?php
                } elseif ($data_edit->type_pengaturan=='link') {
                    ?>
                    <input type="hidden" name="type_pengaturan" value="link">
                    <div class="form-group">
                      <label for="isi_pengaturan" class="control-label col-lg-2"><?php echo $data_edit->nama_pengaturan?></label>
                          <div class="col-lg-10">
                            <?php
                            if ($data_edit->isi_pengaturan!="") {
                            $data_sosmed = json_decode($data_edit->isi_pengaturan,true);
                            foreach ($data_sosmed as $name_link => $url) {
                                ?>
                            <div class="row" style="margin-bottom:5px">
                                 <div class="col-lg-4">
                                <input type="text" name="nama_link[]" value="<?=$name_link;?>" placeholder="Label URL" class="form-control"> 
                                </div>
                                 <div class="col-lg-7">
                                <input type="text" name="url[]" value="<?=$url;?>" placeholder="Alamat URL" class="form-control"> 
                                </div>
                            </div>
                                <?php
                            }
                        } else {
                                ?>
                            <div class="row" style="margin-bottom:5px">
                                 <div class="col-lg-3">
                                <input type="text" name="nama_link[]" placeholder="Label URL" class="form-control"> 
                                </div>
                                 <div class="col-lg-9">
                                <input type="text" name="url[]" placeholder="Alamat URL" class="form-control"> 
                                </div>
                            </div>
                                <?php
                        }
                        ?>
                    </div><!-- /.form-group -->
                </div>
                <?php
                }
                else {
                    ?>
    <input type="hidden" name="type_pengaturan" value="text">
          <div class="form-group">
              <label for="Isi Pengaturan" class="control-label col-lg-2">Isi Pengaturan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" class="form-control isi_pengaturan" name="isi_pengaturan" required value="<?php echo $data_edit->isi_pengaturan;?>">
              </div>
          </div><!-- /.form-group -->

                    <?php
                }

                ?>
              <input type="hidden" name="id" value="<?php echo $data_edit->id_pengaturan;?>">
              <input type="hidden" name="short_name" value="<?php echo $data_edit->short_name;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">

    $(document).ready(function() {


    $("#edit_pengaturan_umum").validate({
        ignore : [],
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
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },

        rules: {
          isi_pengaturan:{
              required:true
          },
          nama_pengaturan: {
          required: true,
          //minlength: 2
          }

        },
         messages: {

          nama_pengaturan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          isi_pengaturan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

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
                            $('#modal_pengaturan_umum').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_pengaturan_umum.draw(false);
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
