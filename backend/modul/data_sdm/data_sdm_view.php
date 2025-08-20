<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Data Sdm
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>data-sdm">Data Sdm</a></li>
                        <li class="active">Data Sdm List</li>
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
                <h4><i class="icon fa fa-ban"></i> Error!</h4><span class="isi_error"></span>
              </div>
                <button class="btn btn-primary btn-flat down_feeder_portal"><i class="fa fa-cloud-download"></i> Download/Update SDM Dari Sister</button> 
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
                        <table id="dtb_data_sdm" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:0;' class='dt-center'>#</th>
                                  <th>Nama</th>
                                  <th>Nidn</th>
                                  <th>NIP</th>
                                  <th>NUPTK</th>
                                  <th>Status Aktif</th>
                                  <th>Status SDM</th>
                                  <th>Jenis SDM</th>
                                  <th>Created At</th>
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

    </section><!-- /.content -->

        <script type="text/javascript">
      
      
      var dtb_data_sdm = $("#dtb_data_sdm").DataTable({
              
           'order' : [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [ 
              
            {
            "targets": [9],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<a href="<?=base_index();?>data-sdm/detail/'+data+'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a>';
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
              url :'<?=base_admin();?>modul/data_sdm/data_sdm_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

/* $(".download-data").click(function(e) {
    e.preventDefault();
    $("#loadnya").show();
    $.ajax({
        url: '<?=base_admin();?>modul/data_sdm/data_sdm_download.php',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            $("#loadnya").hide();
            if (response.success) {
                alert('Data successfully downloaded.');
                dtb_data_sdm.ajax.reload();
            } else {
                alert('Error downloading data: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Download error:', error);
            alert('An error occurred while downloading data.');
        }
    });
}); */

$('.down_feeder_portal').on('click', function() {
  $("#loadnya").show();
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
              url: "<?=base_admin();?>/modul/data_sdm/get_jumlah.php",
              type : "post",
              dataType: 'json',
              'async':false,
              success: function(data) {

                console.log(data);
                 totaldata = data.jumlah;
                  total_data = parseInt(totaldata);
                  var bagi = Math.ceil(total_data/5);
                    
                    getDataDown(bagi,total_data,start_time);
              }
            });
 
}



   
    var counters = 0;
    var persen = 0;
    var progress_down=5;
    var last = "";
    
window.getDataDown=function(bagi,total_data,start_time)
{

  console.log(total_data);
    var start = start_time;
    if ((bagi*5)==progress_down) {
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
        url: "<?=base_admin();?>modul/data_sdm/stream.php",
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

              counters+=5;
              progress_down+=5;

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
            