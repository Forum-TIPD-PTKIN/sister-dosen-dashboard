<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Formulir</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>formulir">Formulir</a>
                        </li>
                        <li class="active"><?php echo $lang["edit"];?> Formulir</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title"><?php echo $lang["edit"];?> Formulir</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_formulir" method="post" class="form-horizontal" action="<?=base_admin();?>modul/formulir/formulir_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nama Peserta" class="control-label col-lg-2">Nama Peserta <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama" value="<?=$data_edit->nama;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                <div class="form-group">
                  <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin <span style="color:#FF0000">*</span></label>
                      <div class="col-lg-10">
                        
                <div class="radio radio-success radio-inline">
                  <input type="radio" name="jk"  id="radio1" value="L" <?=($data_edit->jk=="L")?"checked":"";?> required>
                    <label for="radio1" style="padding-left: 5px;">
                      Laki - Laki
                    </label>
                </div>
                
                <div class="radio radio-success radio-inline">
                  <input type="radio" name="jk"  id="radio2" value="P" <?=($data_edit->jk=="P")?"checked":"";?> required>
                    <label for="radio2" style="padding-left: 5px;">
                      Perempuan
                    </label>
                </div>
                
                      </div>
                </div><!-- /.form-group -->
                
              <div class="form-group">
                <label for="Nomor HP (whatsapp)" class="control-label col-lg-2">Nomor HP (whatsapp) <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="no_hp" value="<?=$data_edit->no_hp;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="NIK" class="control-label col-lg-2">NIK <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="nik" value="<?=$data_edit->nik;?>" class="form-control" required>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Tempat Lahir" class="control-label col-lg-2">Tempat Lahir <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="tmpt_lahir" value="<?=$data_edit->tmpt_lahir;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
              <label for="Tanggal Lahir " class="control-label col-lg-2">Tanggal Lahir  <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" value="<?=$data_edit->tgl_lahir;?>" name="tgl_lahir" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          
                            <input type="hidden" name="id" value="<?=$data_edit->mhs_id;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <a href="<?=base_index();?>formulir" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
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
    
    
    
        $("#modal_formulir").scroll(function(){
          $(".tgl_picker").datepicker("hide");
          $(".tgl_picker").blur();
        });
        $(".tgl_picker").datepicker({ 
        format: "yyyy-mm-dd",
        autoclose: true, 
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
    $("#edit_formulir").validate({
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
            
          nama: {
          required: true,
          //minlength: 2
          },
        
          jk: {
          required: true,
          //minlength: 2
          },
        
          no_hp: {
          required: true,
          //minlength: 2
          },
        
          nik: {
          required: true,
          //minlength: 2
          },
        
          tmpt_lahir: {
          required: true,
          //minlength: 2
          },
        
          tgl_lahir: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nama: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jk: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          no_hp: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nik: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tmpt_lahir: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl_lahir: {
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
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    window.history.back();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
