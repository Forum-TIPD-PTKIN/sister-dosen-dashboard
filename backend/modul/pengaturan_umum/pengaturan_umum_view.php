<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pengaturan Umum
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?php echo base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?php echo base_index();?>pengaturan-umum">Pengaturan Umum</a></li>
                        <li class="active">Pengaturan Umum List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <!-- <?php
                                if ($db->userCan("insert")) {
                                    ?>
                                      <a id="add_pengaturan_umum" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                }
                                ?> -->
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 10px">
                                   <button id="bulk_delete" style="display: none;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_pengaturan_umum" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>

                                  <th>Nama Pengaturan</th>
                                  <th>Isi Pengaturan</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
    <?php
    $edit ="";
    $edit_extend="";
    if ($db->userCan("update")) {
        $edit = "<a data-id='+data+'  class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
    $edit_extend = "<a data-id='+data+' href=".base_index()."pengaturan-umum/edit/'+data+' class=\"btn btn-primary btn-sm \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
    }

    ?>

    <div class="modal" id="modal_pengaturan_umum" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pengaturan Umum</h4> </div> <div class="modal-body" id="isi_pengaturan_umum"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    </section><!-- /.content -->

        <script type="text/javascript">

      $("#add_pengaturan_umum").click(function() {
          $.ajax({
              url : "<?php echo base_admin();?>modul/pengaturan_umum/pengaturan_umum_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pengaturan_umum").html(data);
              }
          });

      $('#modal_pengaturan_umum').modal({ keyboard: false,backdrop:'static',show:true });

    });


    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Pengaturan Umum");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?php echo base_admin();?>modul/pengaturan_umum/pengaturan_umum_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pengaturan_umum").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pengaturan_umum').modal({ keyboard: false,backdrop:'static' });

    });

      var dtb_pengaturan_umum = $("#dtb_pengaturan_umum").DataTable({


           'bProcessing': true,
            'bServerSide': true,
             "order": [],

         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [

            {
            "targets": [2],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                if (full[2]==19 || full[2]==20) {
                    return '<?php echo $edit_extend;?>';
                } else {
                    return '<?php echo $edit;?>';
                }
                
               }
            },


             ],

            'ajax':{
              url :'<?php echo base_admin();?>modul/pengaturan_umum/pengaturan_umum_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

</script>
