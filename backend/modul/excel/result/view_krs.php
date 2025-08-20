
<!-- button 1. place inside box header -->

<button class="btn btn-primary btn-flat download-data-krs"><i class="fa fa-cloud-download"></i> Download Data</button>


<!-- button 2. place inside box body -->
<!--progress block -->
<div class="row" id="show_progress" style="display: none">
    <div class="col-md-11">
        <div class="progress">
            <div class="progress-bar progress-bar-striped" id="progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 1%"> 1% </div>
        </div>
    </div>
    <div class="col-md-1" id="message"> <span class="current-count">1</span>/<span class="total-count">13</span> </div>
</div>
<div class="alert alert-danger alert-dismissible" id="ada_error" style="display: none">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4>
    <i class="icon fa fa-ban"></i> Error!
  </h4>
  <span class="isi_error"></span>
</div>
<!--end progress block -->


<!--start filter block -->
<div id="download-filter-krs" style="display:none">
  <form class="form-horizontal">
    
        <div class="form-group">
          <label for="Jurusan" class="control-label col-lg-1" style="text-align:right;">id_semester</label>
          <div class="col-lg-3">
            <select id="id_semester_krs" name="id_semester_krs" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2">
              <option value="all">Semua</option> <?php
                  foreach ($db->query("select * from tabe_name") as $isi) {
                      echo "<option value="$isi->kode_jur">$isi->nama_jur</option>";
                   } ?>
            </select>
          </div>
        </div>
    
    <!-- /.form-group -->
    <div class="form-group">
      <label for="Jurusan" class="control-label col-lg-1">&nbsp;</label>
      <div class="col-lg-3">
        <span class="btn btn-success btn-flat download-data-krs-now">
          <i class="fa fa-cloud-download"></i> Download Data </span>
      </div>
    </div>
  </form>
</div>
<!--end filter block -->


//place this in js script
function millisToMinutesAndSeconds(s) {
  var ms = s % 1000;
  s = (s - ms) / 1000;
  var secs = s % 60;
  s = (s - secs) / 60;
  var mins = s % 60;
  var hrs = (s - mins) / 60;

  return (hrs < 1 ? "" : hrs+" Jam : ") + (mins < 1 ? "" : mins+" Menit : ") + secs + " detik";
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


$(".download-data-krs").on("click", function() {
  if ($("#download-filter-krs").is(":visible")){
    $("#download-filter-krs").hide();
  } else {
    $("#download-filter-krs").show();
  }
});

$(".download-data-krs-now").on("click", function() {
    total_down_krs();
});


function total_down_krs() {
          $("#show_progress").show();
  var totaldata;
  var start_time = new Date().getTime();
  var datas = {
        kode_jur : $("#kode_jur_krs").val()
    }
     $.ajax({
      url: "<?=base_admin();?>modul/krs/stream_krs.php?ask=jumlah",
      type : "post",
      dataType: "json",
      "async":false,
      data : datas,
      success: function(data) {
          totaldata = data.jumlah;
          total_data = parseInt(totaldata);
          var bagi = Math.ceil(total_data/50);
          getDataDownkrs(bagi,total_data,start_time);
      }
    });

    return totaldata;
}


    var counters = 0;
    var persen = 0;
    var progress_down=50;
    var last = "";
    
window.getDataDownkrs=function(bagi,total_data,start_time)
{

  console.log(total_data);
    var start = start_time;
    if ((bagi*50)==progress_down) {
      data = {
          offset : counters,
          total_data : total_data,
          kode_jur : $("#kode_jur_krs").val(),
          last : "yes"
          }
    } else {
      data = {
            offset : counters,
            total_data : total_data,
            kode_jur : $("#kode_jur_krs").val(),
            last : "no"
          }
    }




    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/krs/stream_krs.php",
        //async:false,
        data : data,
        type : "post",
        dataType: "json",
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
              
              counters+=50;
              progress_down+=50;
            
              //console.log(data[index].offset);
               if (counters < total_data) {
                  getDataDownkrs(bagi,total_data,start);
                } else {
                 $("#loadnya").hide();
                  var end_time = new Date().getTime();
                  waktu = "Total Waktu Upload : "+millisToMinutesAndSeconds(end_time-start);
                  alert("Download Data krs Selesai");
                  $("#isi_informasi_download").html(data[index].last_notif.concat(waktu));
                  $("#informasi_download").modal("show");
                  //console.log("done");
                } 
              });

        },
      error: function (xhr, ajaxOptions, thrownError) {
        alert("oops ada error");
        $("#loadnya").hide();
         $("#ada_error").show();
        $(".isi_error").html(xhr.responseText);
        
        }

    });
}
