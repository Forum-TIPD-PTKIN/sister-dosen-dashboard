<?php
$get_template ='<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

$json_response = array();
$param = array();

'.$param_init.'
'.$filter_var.'
if (isset($_GET["ask"])=="jumlah") {
    $total_'.$service_name.' = $db->fetchCustomSingle("'.$count_query.'",$param);
    if ($total_'.$service_name.'->jumlah>0) {
       $json_response[\'jumlah\'] = $total_'.$service_name.'->jumlah;
    } else {
      $json_response[\'jumlah\'] = 0;
    }
} else {
	$limit = '.$limit.';
	$offset = $_GET["offset"];
	$data_'.$service_name.' = $db->query("'.$query.'",$param);
	foreach ($data_'.$service_name.' as $key) {
	    $data_rec = array(
	        '.$final_data_array_content.'
	    );
	    array_push($json_response, $data_rec);
	}
}

echo json_encode($json_response);';

$view_template = '
<!-- button 1. place inside box header -->
'.$button_filter_or_not.'

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

'.$filter_block.'

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
    $("#progressbar").css("width",percent+"%");
    $("#progressbar").html(percent+"%");
} 

'.$download_filter_or_not.'

function total_down_'.$service_name.'() {
    $("#show_progress").show();
    var totaldata;
    var start_time = new Date().getTime();
        '.$filter_download_count.'
     $.ajax({
      url: "<?=base_admin();?>modul/'.$service_name.'/stream_'.$service_name.'.php?ask=jumlah",
      type : "post",
      dataType: "json",
      "async":false,
      data : datas,
      success: function(data) {
          totaldata = data.jumlah;
          total_data = parseInt(totaldata);
          var bagi = Math.ceil(total_data/'.$limit.');
          getDataDown'.$service_name.'(bagi,total_data,start_time);
      }
    });

    return totaldata;
}


    var counters = 0;
    var persen = 0;
    var progress_down='.$limit.';
    var last = "";
    
window.getDataDown'.$service_name.'=function(bagi,total_data,start_time)
{

    console.log(total_data);
    var start = start_time;
    if ((bagi*'.$limit.')==progress_down) {
        data = {
            offset : counters,
            total_data : total_data,
            '.$filter_download.',
            last : "yes"
        }
    } else {
            data = {
            offset : counters,
            total_data : total_data,
            '.$filter_download.',
            last : "no"
        }
    }




    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/'.$service_name.'/stream_'.$service_name.'.php",
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
              
              counters+='.$limit.';
              progress_down+='.$limit.';
            
              //console.log(data[index].offset);
               if (counters < total_data) {
                  getDataDown'.$service_name.'(bagi,total_data,start);
                } else {
                 $("#loadnya").hide();
                  var end_time = new Date().getTime();
                  waktu = "Total Waktu Upload : "+millisToMinutesAndSeconds(end_time-start);
                  alert("Download Data '.$service_name.' Selesai");
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
';
$stream_template = '<?php
include "../../inc/config.php";
function post_data($url,$post=array(),$get=array()) 
{ 
   $ch = curl_init(); 
   $postvars = "";
   $get_vars = "";
   if (!empty($post)) {
        $array_post_var = array();
        foreach($post as $key=>$value) {
            $array_post_var[] = $key . "=" . $value;
        }
        $postvars = implode("&", $array_post_var);
   }
   if (!empty($get)) {
        $array_get_var = array();
        foreach($get as $key=>$value) {
            $array_get_var[] = $key . "=" . $value;
        }
        $get_vars = implode("&", $array_get_var);
        curl_setopt ($ch, CURLOPT_URL, $url."?".$get_vars); 
   } else {
        curl_setopt ($ch, CURLOPT_URL, $url); 
   }

   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
   if (!empty($post)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); 
   }
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
   $result = curl_exec($ch); 
   //dump($result);


   //$http_respond = trim( strip_tags( $result ) );
   $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

   curl_close($ch); 
   return $result;
}
/**
 * [trimmer trim for import excel
 *
 * @param  [type] $excel column value
 * @return [type]  trimmed value
 */
function trimmer($value)
{
    $result = preg_replace("/[^[:print:]]/", "", filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
    return addslashes(trim($result));
}

$json_response = array();
$msg = "";
$values = "";

$url = "'.$url_location.'/get_'.$service_name.'.php";
//param here
$param = array();

'.$filter_var.'

if (isset($_GET["ask"])=="jumlah") {
    $url_jumlah = array(
        "ask" => "jumlah"
    );
    $jumlah_'.$service_name.' = post_data($url,$param,$url_jumlah);
    $results = json_decode($jumlah_'.$service_name.');
    if ($results->jumlah>0) {
       $json_response["jumlah"] = $results->jumlah;
    } else {
      $json_response["jumlah"] = 0;
    }
    echo json_encode($json_response);
} else {
    if ($_POST["total_data"]<1) {
        $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">Tidak ada data berhasil di Upload</font><br />
            </div>";

        $jumlah["last_notif"] = $msg;
        array_push($json_response, $jumlah);

        echo json_encode($json_response);
        exit();
    }

    $offset = $_POST["offset"];

    $jumlah["offset"] = $offset;

    $par_get = array(
        "offset" => $offset
    );


    $data_rec = array();
            $data = post_data($url,$param,$par_get);
            $datas = json_decode($data);

            foreach ($datas as $key) {

                    $check = $db->checkExist("'.$target_table.'",array("nim" => $key->nim));
                 if ($check==true) {
                        $update_'.$service_name.'[] = array(
                           	'.$loop_data.'
                        );
                        $nipd[] = $key->nim;
                  } else {
                       $insert_'.$service_name.'[] = array(
                            '.$loop_data.'
                        );
                  }      
              }

            if (!empty($update_'.$service_name.')) {
                updateMulti("'.$service_name.'",$update_'.$service_name.',"nim",$nipd);
            }

            if (!empty($insert_'.$service_name.')) {
              $db->insertMulti("'.$target_table.'",$insert_'.$service_name.');
            }

    if ($_POST["last"]=="yes") {
        //echo "<pre>";

    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">".$_POST["total_data"]." Data '.$service_name.' berhasil di Unduh</font><br />";
            $msg .= "</div>
            </div>";

            $jumlah["last_notif"] = $msg;
    }



    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}
?>
';
