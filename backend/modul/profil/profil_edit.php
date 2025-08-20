<?php $data_edit=$db->fetchSingleRow('sys_users','id',$_SESSION['id_user']);
$readonly = "";

?>
   <div style="display: none" class="modal" id="uploadimageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title judul">Crop</h4> </div> <div class="modal-body" id="isi_rumpun_dosen">
                <div class="row">
            <div class="col-md-12 text-center">
              <div id="image_demo"></div>
              <p><button class="btn btn-success crop_image">Crop & Upload Image</button></p>
            </div>
          </div>
        </div>
         </div> 
       </div><!-- /.modal-content --> 
       </div><!-- /.modal-dialog --> 
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                     <?=$lang["edit_profile_button"];?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>profil"><?=$lang["profile_label"];?></a></li>
                        <li class="active"><?=$lang["edit_profile_button"];?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
                                <div class="box-header">
                                </div><!-- /.box-header -->

                  <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                     <form id="edit_profile" method="post" class="form-horizontal" action="<?=base_admin();?>modul/profil/profil_action.php?act=up">
                      <div class="form-group">
                        <label for="First Name" class="control-label col-lg-2">Username</label>
                        <div class="col-lg-10">
                          <input type="text" value="<?=$data_edit->username;?>" class="form-control" readonly> 
                        </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="First Name" class="control-label col-lg-2"><?=$lang["full_name"];?></label>
                        <div class="col-lg-10">
                          <input type="text" id="full_name" name="full_name" value="<?=$data_edit->full_name;?>" <?=$readonly;?> class="form-control" required readonly> 
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Email" class="control-label col-lg-2">Email</label>
                        <div class="col-lg-10">
                          <input type="text" id="email" name="email" value="<?=$data_edit->email;?>" class="form-control" required> 
                        </div>
                      </div><!-- /.form-group -->

<input type="hidden" name="isi_gambar" class="isi_gambar">

      <div class="form-group">
                        <label for="nama_foto" class="control-label col-lg-2"><?=$lang["photo"];?></label>
                        <div class="col-lg-10">
                          <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px;">
                             <img src="<?=$data_edit->foto_user?>">
                            </div>
                                <div class="fileinput-preview fileinput-exists thumbnail tesd" style="max-width: 200px; max-height: 150px;"></div>
                            <div>
                              <span class="btn btn-default btn-file"><span class="fileinput-new"><?=$lang["select_image"];?></span> <span class="fileinput-exists"><?=$lang["select_image"];?></span>
                                <input type="file"  id="upload_image"  accept="image/*">
                              </span>
                              <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?=$lang["remove_photo"];?></a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.form-group -->


                     

                      <input type="hidden" name="id" value="<?=$data_edit->id;?>">
                      <input type="hidden" name="old_file" value="<?=$data_edit->foto_user;?>">
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                           <a href="<?=base_index();?>profil" class="btn btn-default"><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
                           <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                        </div>
                      </div><!-- /.form-group -->
                    </form>
                    
                   
          
                  </div>
                  </div>
              </div>
</div>
                  
                </section><!-- /.content -->
  

 <script type="text/javascript" src="<?=base_admin();?>assets/plugins/croppie/croppie.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/croppie/croppie.css">       
 <script type="text/javascript">
 $(document).ready(function(){
  
  $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'square' //circle
    },
    boundary:{
      width:200,
      height:200
    }
  });

  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $('.tesd').html('<img src="'+response+'" style="max-height:150px">');
      $('.isi_gambar').val(response);

       $('#uploadimageModal').modal('hide');
/*      $.ajax({
        url:"upload.php",
        type: "POST",
        data:{"image": response},
        success:function(data)
        {
          $('#uploadimageModal').modal('hide');
          $('#uploaded_image').html(data);
        }
      });*/
    })
  });

});     

    $(document).ready(function() {
    

    
    $("#edit_profile").validate({
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
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          full_name: {
          required: true,
          //minlength: 2
          },
        
          email: {
          required: true,
          //minlength: 2
          }
        
        },
         messages: {
            
          full_name: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          email: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
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
                                    window.location='<?=base_index();?>profil';
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
