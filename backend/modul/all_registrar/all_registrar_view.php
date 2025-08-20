<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        All Registrar
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>all-registrar">All Registrar</a></li>
                        <li class="active">All Registrar List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <?php
                                if ($db->userCan("insert")) {
                                      ?>
                                      <a href="<?=base_index();?>all-registrar/create" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                  }
                                ?>
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
                        <table id="dtb_all_registrar" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:0;' class='dt-center'>#</th>
                                  <th>Step User</th>
                                  <th>Email</th>
                                  <th>Jenis Seleksi</th>
                                  <th>Nomor Virtual</th>
                                  <th>npsn</th>
                                  <th>nama_sekolah</th>
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
  $del="";
 if ($db->userCan("update")) {
    $edit = "<a data-id='+data+' href=".base_index()."all-registrar/edit/'+data+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/all_registrar/all_registrar_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_all_registrar"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
      
      
      var dtb_all_registrar = $("#dtb_all_registrar").DataTable({
              
           'order' : [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [ 
              
            {
            "targets": [7],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<a href="<?=base_index();?>all-registrar/detail/'+data+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>';
               }
            },
      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
    
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/all_registrar/all_registrar_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

</script>
            