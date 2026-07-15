<?php
session_start();
require "../../inc/config.php";
// Include the SDK using the composer autoloader
require '../../inc/lib/vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
session_check_json();
switch ($_GET["act"]) {
    case 'up_extend':
        //current data
        $data = $db->fetchSingleRow("tb_master_pengaturan_umum","id_pengaturan",$_POST['id']);
        if ($data->isi_extend!='') {
            $current_data = json_decode($data->isi_extend);
            foreach ($current_data as $dt) {
                $array_data[] = $dt->attr_name;
            }
        }

    $data = array();

          if(isset($_POST["isi_pengaturan"])=="on")
          {
            $data["isi_pengaturan"] = "Y";
          } else {
            $data["isi_pengaturan"] = "N";
          }
        if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            if (in_array(str_replace(" ", "_", strtolower($data_attr['attr_name'])), $array_data)) {
                $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_name']));
            } else {
                $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_label']));
            }
            $data_attributes[] = $data_attr;
          }
          $data['isi_extend'] = json_encode($data_attributes);
        }
    $up = $db->update("tb_master_pengaturan_umum", $data, "id_pengaturan", $_POST["id"]);
    action_response($db->getErrorMessage());
        break;
case "in":

    //removing first and last php tag from tinymce
    $str = preg_replace(array('/^\<p\>/','/\<\/p\>$/'), "", $_POST["isi_pengaturan"]);



    $data = array(
    "nama_pengaturan" => $_POST["nama_pengaturan"],
    "isi_pengaturan" => $str,
    );




    $in = $db->insert("tb_master_pengaturan_umum", $data);


    action_response($db->getErrorMessage());
    break;
case "delete":



    $db->delete("tb_master_pengaturan_umum", "id_pengaturan", $_POST["id"]);
    action_response($db->getErrorMessage());
    break;
case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
            $db->delete("tb_master_pengaturan_umum", "id_pengaturan", $id);
        }
    }
    action_response($db->getErrorMessage());
    break;
case "up":

    //get profil
    $auth = $db->fetchSingleRow("s3_storage","type",'profil');

    if ($_POST['type_pengaturan']=='image') {
        if(isset($_FILES["gambar"]["name"])) {
            $bucket = $auth->bucket;

            $endpoint = $auth->url;
            
            $s3 = new Aws\S3\S3Client([
            
                "version" => "latest",
            
                "region" => "idn",
                'scheme' =>'http',
            
                "endpoint" => $endpoint,
            
                "use_path_style_endpoint" => true,
            
                "credentials" => [
            
                    "key" => $auth->key,
            
                    "secret" => $auth->secret,
            
                ],
            
            ]);
        
        $filename = $_FILES["gambar"]["name"];
        $filename = preg_replace("#[^a-z.0-9]#i", "", $filename); 
        $ex = explode(".", $filename); // split filename
        $fileExt = end($ex); // ekstensi akhir
        $filename = time().rand().".".$fileExt;//rename nama file';
            
            $result = $s3->putObject([
            
                "Bucket" => $bucket,
            
                "Key" => $filename,
            
                "Body" => "this is the body!",
            
                // you can use relative
                // "SourceFile" => "./aws-sdk-php-v3-developer-guide.pdf",
            
                // or absolute path
                "SourceFile" => $_FILES["gambar"]["tmp_name"],
            
                "ContentType" => $_FILES["gambar"]["type"],
                'ACL'    => 'public-read',
            
            ]);
    
            $data = array(
            "nama_pengaturan" => $_POST["nama_pengaturan"],
            "isi_pengaturan" => $endpoint.'/'.$bucket.'/'.$filename
            );
        }

    } elseif ($_POST['short_name']=='sem_prodi') {
        if ($_POST['isi_pengaturan']=='N') {
            $db->update('sys_menu',array('hide' => 'Y'),'nav_act','setting_semester_prodi');
        } else {
            $db->update('sys_menu',array('hide' => 'N'),'nav_act','setting_semester_prodi');
        }

    $data = array(
        "nama_pengaturan" => $_POST["nama_pengaturan"],
        "isi_pengaturan" => $_POST["isi_pengaturan"],
        );
    }  elseif ($_POST['short_name']=='has_fakultas') {
        if ($_POST['isi_pengaturan']=='N') {
            $db->update('sys_menu',array('hide' => 'Y'),'nav_act','fakultas');
        } else {
            $db->update('sys_menu',array('hide' => 'N'),'nav_act','fakultas');
        }

    $data = array(
        "nama_pengaturan" => $_POST["nama_pengaturan"],
        "isi_pengaturan" => $_POST["isi_pengaturan"],
        );
    } elseif ($_POST['type_pengaturan']=='sosmed') {

        foreach ($_POST['nama_sosmed'] as $key => $sosmed) {
                $array_sosmed[$sosmed] = $_POST['url_sosmed'][$key];
        }

        $data = array(
            "isi_pengaturan" => json_encode($array_sosmed),
        );
    } elseif ($_POST['type_pengaturan']=='link') {

        foreach ($_POST['nama_link'] as $key => $sosmed) {
                if ( $_POST['url'][$key]!="") {
                    $array_ulink[$sosmed] = $_POST['url'][$key];
                }
                
        }

        $data = array(
            "isi_pengaturan" => json_encode($array_ulink),
        );
    } else {


        $data = array(
        "nama_pengaturan" => $_POST["nama_pengaturan"],
        "isi_pengaturan" => $_POST["isi_pengaturan"],
        );
    }


    $up = $db->update("tb_master_pengaturan_umum", $data, "id_pengaturan", $_POST["id"]);

    action_response($db->getErrorMessage());
    break;
default:
    // code...
    break;
}

?>
