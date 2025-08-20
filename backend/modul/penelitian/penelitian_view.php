<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Penelitian
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>penelitian">Penelitian</a></li>
                        <li class="active">Penelitian List</li>
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
                                      <a href="<?=base_index();?>penelitian/create" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
                        <table id="dtb_penelitian" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:13px;' class='dt-center'><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '> <input type='checkbox' class='group-checkable bulk-check'> <span></span></label></th>
                                  <th>Nama Dosen</th>
                                  <th>Judul</th>
                                  <th>Tahun</th>
                                  <th>Lama Kegiatan</th>
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
    $edit = "<a data-id='+data+' href=".base_index()."penelitian/edit/'+data+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/penelitian/penelitian_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_penelitian"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
      
      
      var dtb_penelitian = $("#dtb_penelitian").DataTable({
              
           'order' : [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [ 
              
            {
            "targets": [5],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<a href="<?=base_index();?>penelitian/detail/'+data+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>';
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
              url :'<?=base_admin();?>modul/penelitian/penelitian_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });


$('.bulk-check').on('click',function() { // bulk checked
      var status = this.checked;
      if (status) {
        select_deselect('select');
      } else {
        select_deselect('unselect');
      }
      $('.check-selected').each( function() {
        $(this).prop('checked',status);
      });
      check_selected();
});



  $(document).on('click', '#dtb_penelitian tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          check_selected();
      }
  });

  function check_selected() {
      var table_select = $('#dtb_penelitian tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb_notif').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      if (array_data_delete.length>0) {
        $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
        $('#bulk_delete').show();
      } else {
        $('.selected-data').text('');
        $('.bulk-check').prop('checked',false);
        $('#bulk_delete').hide();
      }
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_penelitian tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_penelitian tbody tr').removeClass('DTTT_selected selected')
      }
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_penelitian );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#modal-confirm-delete').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            error: function(data ) { 
                $('#loadnya').hide();
                console.log(data); 
               $('.isi_warning_delete').html(data.responseText);
               $('.error_data_delete').fadeIn();
               $('html, body').animate({
                  scrollTop: ($('.error_data_delete').first().offset().top)
              },500);
            },
            url: '<?=base_admin();?>modul/penelitian/penelitian_action.php?act=del_massal',
            data: {data_ids:all_ids},
               success: function(responseText) {
                  $('#loadnya').hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $('#informasi').modal('show');
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                               $('.selected-data').text('');
                               $('.bulk-check').prop('checked',false);
                               $('#bulk_delete').hide();
                               $('#loadnya').hide();
                               $(anSelected).remove();
                               dtb_penelitian.draw();
                          }
                    });
                }
            //async:false
        });

        $('#modal-confirm-delete').modal('hide');

    });

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }
  
</script>
            