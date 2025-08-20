<?php
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

$url = "http://pengembangan.online/download/get_tes.php";
//param here
$param = array();


if ($_POST['nm_agama']!='all') {
//param here
$param = array(
    "id_agama" => $_POST["id_agama"],
	"nm_agama" => $_POST["nm_agama"]
);
    $and_id_agama = "and id_agama=?";
	$and_nm_agama = "and nm_agama=?";
}


if (isset($_GET["ask"])=="jumlah") {
    $url_jumlah = array(
        "ask" => "jumlah"
    );
    $jumlah_tes = post_data($url,$param,$url_jumlah);
    $results = json_decode($jumlah_tes);
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

                    $check = $db->checkExist("bulan_aktif",array("nim" => $key->nim));
                 if ($check==true) {
                        $update_tes[] = array(
                           	"id_jurusan" => $key->id_agama,
							"tgl_mulai" => $key->nm_agama
                        );
                        $nipd[] = $key->nim;
                  } else {
                       $insert_tes[] = array(
                            "id_jurusan" => $key->id_agama,
							"tgl_mulai" => $key->nm_agama
                        );
                  }      
              }

            if (!empty($update_tes)) {
                updateMulti("tes",$update_tes,"nim",$nipd);
            }

            if (!empty($insert_tes)) {
              $db->insertMulti("bulan_aktif",$insert_tes);
            }

    if ($_POST["last"]=="yes") {
        //echo "<pre>";

    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
            <font color=\"#3c763d\">".$_POST["total_data"]." Data tes berhasil di Unduh</font><br />";
            $msg .= "</div>
            </div>";

            $jumlah["last_notif"] = $msg;
    }



    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}
?>
