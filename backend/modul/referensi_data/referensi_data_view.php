<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Referensi Data
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>referensi-data">Referensi Data</a></li>
                        <li class="active">Referensi Data List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                                      <div class="row" id="show_progress" style="display: none">
               <div class="col-md-11">
                        <div class="progress">
                          <div class="progress-bar progress-bar-striped" id="progressbar" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                            10%
                          </div>

                        </div></div><div class='col-md-1' id="message"><span class='current-count'>1</span>/<span class="total-count">13</span></div></div>
                      <div class="alert alert-danger alert-dismissible" id="ada_error" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error! Laporkan Bug dan kendala di https://github.com/Forum-TIPD-PTKIN/sister-dosen-dashboard </h4><span class="isi_error"></span>
              </div>

                                 
                <button class="btn btn-primary btn-flat down_feeder_portal"><i class="fa fa-cloud-download"></i> Download Update Dari Sister</button> 
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
                        <table id="dtb_referensi_data" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:0;' class='dt-center'>#</th>
                                  <th>Reference Type</th>
                                  <th>Reference ID</th>
                                  <th>Nama Data</th>
                                  <th>Updated At</th>
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
<div class="modal modal-warning" id="informasi_upload" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <h4 class="modal-title">Informasi</h4> </div> <div class="modal-body"> <p id="isi_informasi_upload">

</p> </div> <div class="modal-footer"> <button type="button" id="ok_info_download" class="btn btn-outline pull-left">Close</button> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div><!-- /.modal -->
<div class="modal modal-warning" id="informasi" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <h4 class="modal-title">Informasi</h4> </div> <div class="modal-body
 <?php
  $edit ="";
  $del="";
 if ($db->userCan("update")) {
    $edit = "<a data-id='+data+'  class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/referensi_data/referensi_data_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_referensi_data"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    <div class="modal" id="modal_referensi_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Referensi Data</h4> </div> <div class="modal-body" id="isi_referensi_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">
      
      $("#add_referensi_data").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/referensi_data/referensi_data_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_referensi_data").html(data);
              }
          });

      $('#modal_referensi_data').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Referensi Data");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/referensi_data/referensi_data_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_referensi_data").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_referensi_data').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_referensi_data = $("#dtb_referensi_data").DataTable({
              
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
                return '<a href="<?=base_index();?>referensi-data/detail/'+data+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a> <?=$edit;?> <?=$del;?>';
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
              url :'<?=base_admin();?>modul/referensi_data/referensi_data_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('.down_feeder_portal').on('click', function() {
  total_down();

});


function millisToMinutesAndSeconds(s) {
  var ms = s % 1000;
  s = (s - ms) / 1000;
  var secs = s % 60;
  s = (s - secs) / 60;
  var mins = s % 60;
  var hrs = (s - mins) / 60;

  return (hrs < 1 ? '' : hrs+' Jam : ') + (mins < 1 ? '' : mins+' Menit : ') + secs + ' detik';
}

 function proses(percent){
    if(percent>10){
      
      $("#progressbar").css("width",percent+"%");
      $("#progressbar").html(percent+"%");
      } else {
        $("#progressbar").css("width",10+"%");
        $("#progressbar").html(10+"%");
      }
    } 


function total_down() {
          $("#show_progress").show();
          $("#down_kelas").attr("disabled", true);
          var start_time = new Date().getTime();
          var totaldata;

         
             $.ajax({
              //url: "http://localhost/datamahasiswa/get_jumlah.php",
              url: "<?=base_admin();?>/modul/referensi_data/get_jumlah.php",
              type : "post",
              dataType: 'json',
              'async':false,
              success: function(data) {

                console.log(data);
                 totaldata = data.jumlah;
                  total_data = parseInt(totaldata);
                  var bagi = Math.ceil(total_data/1);
                    
                    getDataDown(bagi,total_data,start_time);
              }
            });
 
}



   
    var counters = 0;
    var persen = 0;
    var progress_down=1;
    var last = "";
    
window.getDataDown=function(bagi,total_data,start_time)
{

  console.log(total_data);
    var start = start_time;
    if ((bagi*1)==progress_down) {
      data = {
          offset : counters,
          total_data : total_data,
          last : 'yes'
          }
    } else {
      data = {
            offset : counters,
            total_data : total_data,
            last : 'no'
          }
    }




    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/referensi_data/stream.php",
        //async:false,
        data : data,
        type : "post",
        dataType: 'json',
        success:function(data){
          $.each(data, function(index) {
            persen = ((progress_down/total_data)*100).toFixed(1);
            if (persen>100) {
              persen=100+ "%";
              progress_down = total_data;
            } else {
              persen=persen+ "%";
              progress_down = progress_down;
            }

            //data_rec.push(data[index].data_rec);
           
            $(".current-count").html(progress_down);
            $(".total-count").html(total_data);
            persen = parseInt(persen);
            proses(persen);
              
              counters+=1;
              progress_down+=1;
            
              //console.log(data[index].offset);
               if (counters < total_data) {
                  getDataDown(bagi,total_data,start);
                } else {
                 $("#loadnya").hide();
                  var end_time = new Date().getTime();
                  waktu = "Total Waktu Download : "+millisToMinutesAndSeconds(end_time-start);
                  alert('Download Data Dari Portal Selesai');
                  $("#isi_informasi_upload").html(data[index].last_notif.concat(waktu));
                  $('#informasi_upload').modal('show');
                  //console.log('done');
                } 
              });

        },
      error: function (xhr, ajaxOptions, thrownError) {
        alert('oops ada error');
        $("#loadnya").hide();
         $("#ada_error").show();
        $(".isi_error").html(xhr.responseText);
        
        }

    });
}
 $("#ok_info_download").click(function(){
 location.reload();
 });
</script>
            
