<?php
session_start();
include "../../inc/config.php";
?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_referensi_data" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/referensi_data/referensi_data_action.php?act=in">
                      
              <div class="form-group">
                <label for="reference_type" class="control-label col-lg-2">reference_type <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="reference_type" placeholder="reference_type" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="reference_id" class="control-label col-lg-2">reference_id <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="reference_id" placeholder="reference_id" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="data" class="control-label col-lg-2">data <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="data" placeholder="data" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">nama <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama" placeholder="nama" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                      

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
    
     
    
    
    $("#input_referensi_data").validate({
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
            
          reference_type: {
          required: true,
          //minlength: 2
          },
        
          reference_id: {
          required: true,
          //minlength: 2
          },
        
          data: {
          required: true,
          //minlength: 2
          },
        
          nama: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          reference_type: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          reference_id: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          data: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama: {
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
                            $('#modal_referensi_data').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_referensi_data.draw();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
