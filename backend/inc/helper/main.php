<?php
function session_check()
{
  if (empty($_SESSION['login'])) {
    echo "die";
    exit();
  }
}

function session_check_end() {
    if (empty($_SESSION['login'])) {
    echo "<script>alert('Sessio Anda Telah Habis'); window.location = '".base_url()."';</script>";
    exit();
  }
}
/**
 * return $pengaturan
 *
 * @param  [type] $short_name short name column call
 * @return [type]             isi pengaturan
 */
function getPengaturan($short_name)
{
    global $db;
    $pengaturan = $db->fetchSingleRow('tb_master_pengaturan_umum', "short_name", $short_name);
    if ($pengaturan->isi_pengaturan!="") {
        return $pengaturan->isi_pengaturan;
    } else {
        return "";
    }
}
function fullName() {
    return $_SESSION['full_name'];
}
function dateTime() {
    return date('Y-m-d H:i:s');
}
function getUser($id) {
     global $db;
    return $db->fetchSingleRow("sys_users","id",$id);
}
function session_check_json()
{
 if (empty($_SESSION['login'])) {
    $json_response = array();
    $status['status'] = "die";
    array_push($json_response, $status);
    echo json_encode($json_response);
    exit();
  }
}

    //url parsing
    function parse_path()
    {
        $path = array();
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_path = explode('?', $_SERVER['REQUEST_URI']);
            
            $path['base']      = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
            $path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
            $path['call']      = mb_convert_encoding($path['call_utf8'], 'UTF-8', 'UTF-8');
            if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
                $path['call'] = '';
            }
            $path['call_parts'] = explode('/', $path['call']);
            
            
            if ($request_path[1] = '') {
                $path['query_utf8'] = urldecode($request_path[1]);
                $path['query']      = utf8_decode(urldecode($request_path[1]));
                $vars               = explode('&', $path['query']);
                foreach ($vars as $var) {
                    $t                         = explode('=', $var);
                    $path['query_vars'][$t[0]] = $t[1];
                }
            }
        }
        return $path;
    }

function uri_segment($segment)
{
    //url path
    $path = parse_path();
    if (isset($path['call_parts'][$segment])) {
        return $path['call_parts'][$segment];
    }
}

function title($segment) {
    //url path
    $path = parse_path();
    if (isset($path['call_parts'][$segment])) {
        if ($path['call_parts'][$segment]!="") {
          return getPengaturan('app_name'). ' - '.ucwords(str_replace("-", " ",$path['call_parts'][$segment]));
        } else {
          return getPengaturan('app_name');
        }
        
    }
}

/**
 * return $setting
 *
 * @param  [type] $short_name short name column call
 * @return [type]             setting data
 */
function getSetting($short_name)
{
    global $db;
    $setting = $db->fetchSingleRow("sys_setting", "short_name", $short_name);
    return $setting;
}

//submit form action json response 
function action_response($error_message,$custom_response=array()) {
    $json_response = array();
    if ($error_message=='') {
        $status['status'] = "good";
        if (!empty($custom_response)) {
       foreach ($custom_response as $key => $value) {
          $status[$key] = $value;
       }

      }

     } else {
        $status['status'] = "error";
        $status['error_message'] = $error_message;
     }
    array_push($json_response, $status);
    echo json_encode($json_response);
    exit();
}
//for admin only
function session_check_adm()
{
  if ($_SESSION['group_level']!='root') {
  exit();
  }
}
//redirection 
function redirect($var)
{
  header("location:".$var);
}

function dump($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

//root directory web
function base_url()
{
    $root = '';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $root = $protocol . $host;
    if (DIR_MAIN != '') {
        $root .= "/" . DIR_MAIN . "/";
    } else {
        $root .= "/";
    }
    return $root;
}

//root url api
function base_url_api()
{
    $root = '';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $root = $protocol . $host;
    $root .= "/" . DIR_API . "/";
    return $root;
}

//root url api
function base_url_front()
{
    $root = '';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $root = $protocol . $host;
    if (DIR_MAIN != '') {
        $root .= "/" . DIR_MAIN . "/" . DIR_FRONT . "/";
    } else {
        $root .= "/" . DIR_FRONT . "/";
    }
    return $root;
}

//base admin is url until admin dir, ex:http://localhost/backend/admina
function base_admin()
{
    $root = '';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $root = $protocol . $host;
    if (DIR_MAIN != '') {
        $root .= "/" . DIR_MAIN . "/" . DIR_ADMIN . "/";
    } else {
        $root .= "/" . DIR_ADMIN . "/";
    }
    return $root;
}

//base admin is url until index.php, ex:http://localhost/backend/admina/index.php
function base_index()
{
    $root = '';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $root = $protocol . $host;
    if (DIR_MAIN != '') {
        $root .= "/" . DIR_MAIN . "/" . DIR_ADMIN . "/";
    } else {
        $root .= "/" . DIR_ADMIN . "/";
    }
    return $root;
}


function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function validateDateTime($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
/**
 * return indonesian date format 
 * @param  text $date date text 2019-07-02
 * @return text       indonesian format 2 januari 2019
 */
function tgl_indo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
  $date = substr($date, 0,10);
  if (validateDate($date)) {
       // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
      $BulanIndo = array("Januari", "Februari", "Maret",
                 "April", "Mei", "Juni",
                 "Juli", "Agustus", "September",
                 "Oktober", "November", "Desember");
    
      $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
      $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
      $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
      
      $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
      return($result);
  } else {
    return '';
  }

}
//uang
function rupiah($angka){

  $hasil_rupiah = number_format($angka,0,',','.');
  return $hasil_rupiah;
}

function tgl_time($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
  if (validateDateTime($date)) {
       // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
      $BulanIndo = array("Januari", "Februari", "Maret",
                 "April", "Mei", "Juni",
                 "Juli", "Agustus", "September",
                 "Oktober", "November", "Desember");
    
      $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
      $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
      $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
      $time = substr($date, -8);
      
      $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun.' '.$time;
      return($result);
  } else {
    return '';
  }

}
function getBulan($bln){
  switch ($bln){
   case 1: 
   return "Januari";
   break;
   case 2:
       return "Februari";
   break;
     case 3:
   return "Maret";
   break;
   case 4:
   return "April";
   break;
   case 5:
   return "Mei";
   break;
     case 6:
   return "Juni";
   break;
   case 7:
   return "Juli";
   break;
     case 8:
   return "Agustus";
     break;
   case 9:
   return "September";
   break;
   case 10:
     return "Oktober";
   break;
     case 11:
     return "Nopember";
   break;
   case 12:
   return "Desember";
   break;
 }
} 
function getHariFromDate($date) {
  $date = substr($date, 0, 10);
  if (validateDate($date)) {
    $day = date('D', strtotime($date));
    $dayList = array(
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu'
    );
    $nama_hari = $dayList[$day];
  } else {
    $nama_hari = "";
  }

  return  $nama_hari;
}

/*function diff_array($array_first,$array_second) {
  if (count($array_first)>count($array_second)) {
  //delete 
  $data = array_map('unserialize', array_diff(array_map('sortAndSerialize', $array_first), array_map('sortAndSerialize', $array_second)));
  return  array('status' => 'del','data' => $data);
  } else {
  $data = array_map('unserialize', array_diff(array_map('sortAndSerialize', $array_second), array_map('sortAndSerialize', $array_first)));
  return  array('status' => 'add','data' => $data);
  }

}*/

  function selected($parentId,$menuData) {
    $html = '';
      if ($parentId!="") {
        if (isset($menuData['parents'][$parentId]))
        {
            foreach ($menuData['parents'][$parentId] as $itemId)
            {
              $html .= $menuData['items'][$itemId]['id']."#";
              $html .= selected($itemId, $menuData);
            }
        }
        //$html = array_filter($html);
      }

    
      return $html;
    }
    // Menu builder function, parentId 0 is the root
    function buildMenu($url, $parent, $menu)
    {
        $html = "";
        $active = '';
        if (isset($menu['parents'][$parent])) {
            foreach ($menu['parents'][$parent] as $itemId) {

                if (!isset($menu['parents'][$itemId])) {
                    if ($menu['items'][$itemId]['type_menu'] == 'separator') {
                        $html .= "<li class='header'>" . ucwords($menu['items'][$itemId]['page_name']) . "</li>";
                    } else {
                        $html .= "<li ";
                        $html .= ($url == $menu['items'][$itemId]['url']) ? 'class="active"' : '';
                        $html .= ">
                     <a href='" . base_admin() . $menu['items'][$itemId]['url'] . "'>";
                        if ($menu['items'][$itemId]['icon'] != '') {
                            $html .= "<i class='fa " . $menu['items'][$itemId]['icon'] . "'></i>";
                        } else {
                            $html .= "<i class='fa fa-square'></i>";
                        }
                        $html .= ucwords($menu['items'][$itemId]['page_name']) . "</a></li>";
                    }

                }

                if (isset($menu['parents'][$itemId])) {
                  if (isset($menu['url'][$url])) {
                    if(in_array($menu['url'][$url],array_filter(explode("#",selected($menu['items'][$itemId]['id'],$menu))))) {
                        $active = 'active';
                    }
                  }

                    $html .= "<li class='treeview $active";

                    $html .= "'><a href='#'>";
                    if ($menu['items'][$itemId]['icon'] != '') {
                        $html .= "<i class='fa " . $menu['items'][$itemId]['icon'] . "'></i>";
                    } else {
                        $html .= "<i class='fa fa-square'></i>";
                    }
                    $html .= "<span>" . ucwords($menu['items'][$itemId]['page_name']) . "</span>
                                    <i class='fa fa-angle-left pull-right'></i>
                                </a>";
                    $html .= "<ul class='treeview-menu'>";
                    $html .= buildMenu($url, $itemId, $menu);
                    $html .= "</ul></li>";
                }
                $active = "";
            }

        }
        return $html;
    }

    /**
     * create menu
     *
     * @param [string] $type_menu vertical or horizontal
     * @return void
     */
    function createMenu($type_menu)
    {
      global $db;
        // Select all entries from the menu table
        $result=$db->query(
            "select sys_menu.*,sys_menu_role.read_act,sys_menu_role.insert_act,url,type_menu,sys_menu_role.update_act,sys_menu_role.delete_act,sys_menu_role.group_level from sys_menu
        left join sys_menu_role on sys_menu.id=sys_menu_role.id_menu
        where sys_menu_role.group_level=? and sys_menu_role.read_act=? and tampil=? ORDER BY parent, urutan_menu asc",
            array(
            'sys_menu_role.group_level'=>$_SESSION['group_level'],
            'sys_menu_role.read_act'=>'Y',
            'tampil'=>'Y'
            )
        );


        // Create a multidimensional array to list items and parents
        $menu = array(
            'items' => array(),
            'parents' => array(),
            'url' => array()
        );
        // Builds the array lists with data from the menu table
        foreach ($result as $items) {

            $items = $db->convertObjToArray($items);

              // Creates entry into items array with current menu item id ie.
            $menu['items'][$items['id']] = $items;
            // Creates entry into parents array. Parents array contains a list of all items with children
            $menu['parents'][$items['parent']][] = $items['id'];
            if ($items['type_menu']=='page') {
              $menu['url'][$items['url']] = $items['id'];
          }
        }
        if ($type_menu=='vertical') {
          return buildMenu(uri_segment(0), 0, $menu);
        } else {
          return buildMenuHorizontal(uri_segment(0), 0, $menu);
        }
        
    }

 // Menu builder function, parentId 0 is the root
 function buildMenuHorizontal($url, $parent, $menu)
 {
     $active = '';
     $html = "";
     if (isset($menu['parents'][$parent])) {
         foreach ($menu['parents'][$parent] as $itemId) {

             if (!isset($menu['parents'][$itemId])) {
                 if ($menu['items'][$itemId]['type_menu'] != 'separator') {
                     $html .= "<li ";
                     $html .= ($url == $menu['items'][$itemId]['url']) ? 'class="active"' : '';
                     $html .= ">
                  <a href='" . base_admin() . $menu['items'][$itemId]['url'] . "'>";
                     if ($menu['items'][$itemId]['icon'] != '') {
                         $html .= "<i class='fa " . $menu['items'][$itemId]['icon'] . "'></i> ";
                     } else {
                         $html .= "<i class='fa fa-square'></i> ";
                     }
                     $html .= ucwords($menu['items'][$itemId]['page_name']) . "</a></li>";
                 }
             }

             if (isset($menu['parents'][$itemId])) {
                 if (isset($menu['url'][$url])) {
                     if(in_array($menu['url'][$url],array_filter(explode("#",selected($menu['items'][$itemId]['id'],$menu))))) {
                         $active = 'active';
                     }
                 }
                 $html .= "<li class='dropdown ".$active;
                 //(in_array( $this->terpilih($url, $menu['items'][$itemId]['id']);
                 $html .= "'><a href='#' class='dropdown-toggle' data-toggle='dropdown' data-hover='dropdown' data-submenu='' aria-expanded='false'>";
                 if ($menu['items'][$itemId]['icon'] != '') {
                     $html .= "<i class='fa " . $menu['items'][$itemId]['icon'] . "'></i>";
                 } else {
                     $html .= "<i class='fa fa-square'></i>";
                 }
                 $html .= "<span> " . ucwords($menu['items'][$itemId]['page_name']) . "</span>
                                 <i class='fa fa-angle-down'></i>
                             </a>";
                 $html .= "<ul class='dropdown-menu'>";
                 $html .= buildMenuHorizontalSubMenu($url, $itemId, $menu);
                 $html .= "</ul></li>";
             }
             $active = '';
         }

     }
     return $html;
 }

// Menu builder function, parentId 0 is the root
 function buildMenuHorizontalSubMenu($url, $parent, $menu)
 {
     $html = "";
     $active = '';
     if (isset($menu['parents'][$parent])) {
         foreach ($menu['parents'][$parent] as $itemId) {

             if (!isset($menu['parents'][$itemId])) {
                 if ($menu['items'][$itemId]['type_menu'] != 'separator') {
                     $html .= "<li ";
                     $html .= ($url == $menu['items'][$itemId]['url']) ? 'class="active"' : '';
                     $html .= ">
                  <a href='" . base_admin() . $menu['items'][$itemId]['url'] . "'>";
                     if ($menu['items'][$itemId]['icon'] != '') {
                         $html .= "<i class='fa " . $menu['items'][$itemId]['icon'] . "'></i> ";
                     } else {
                         $html .= "<i class='fa fa-square'></i> ";
                     }
                     $html .= ucwords($menu['items'][$itemId]['page_name']) . "</a></li>";
                 }
             }

             if (isset($menu['parents'][$itemId])) {
                 if (isset($menu['url'][$url])) {
                     if(in_array($menu['url'][$url],array_filter(explode("#",selected($menu['items'][$itemId]['id'],$menu))))) {
                         $active = 'active';
                     }
                 }
                 $html .= "<li class='dropdown-submenu $active'><a href='#'>";
                 if ($menu['items'][$itemId]['icon'] != '') {
                     $html .= "<i class='fa " . $menu['items'][$itemId]['icon'] . "'></i>";
                 } else {
                     $html .= "<i class='fa fa-square'></i>";
                 }
                 $html .= "<span> " . ucwords($menu['items'][$itemId]['page_name']) . "</span>
                             </a>";
                 $html .= "<ul class='dropdown-menu'>";
                 $html .= buildMenuHorizontal($url, $itemId, $menu);
                 $html .= "</ul></li>";
             }
             $active = '';
         }

     }
     return $html;
 }

function isAlumni() {
  if ($_SESSION['group_level']=='alumni') {
    return true;
  } else {
    return false;
  }
}

/**
 * Undocumented function
 *
 * @param [type] $type bucket type
 * @return void
 */
function upload_s3_crop($type,$file,$old_file="") {
  global $db;
    //get config
    $s3_data = $db->fetchSingleRow('s3_storage','type',$type);
    $bucket = $s3_data->bucket;

    $endpoint = $s3_data->url;
    
    $s3 = new Aws\S3\S3Client([
    
        "version" => "latest",
    
        "region" => "idn",
        'scheme' =>'http',
    
        "endpoint" => $endpoint,
    
        "use_path_style_endpoint" => true,
    
        "credentials" => [
    
            "key" => $s3_data->key,
    
            "secret" => $s3_data->secret
    
        ],
    
    ]);

  $filename = time().rand().".png";//rename nama file';
    
    $result = $s3->putObject([
    
        "Bucket" => $bucket,
    
        "Key" => $filename,
    
        "Body" => "this is the body!",
    
        // you can use relative
        // "SourceFile" => "./aws-sdk-php-v3-developer-guide.pdf",
    
        // or absolute path
        "SourceFile" => $file,
    
        "ContentType" => 'image/png',
        'ACL'    => 'public-read',
    
    ]);

    if ($old_file!="") {
      $results = $s3->deleteObject(array(
        'Bucket' => $bucket,
        'Key'    => $old_file
        )); 
    //  dump($results);
    }

    return $endpoint.'/'.$bucket.'/'.$filename;
}
/**
 * Undocumented function
 *
 * @param [type] $type bucket type
 * @return void
 */
function init_s3($type,$key,$secret,$endpoint) {
  $s3 = new Aws\S3\S3Client([
      "version" => "latest",
  
      "region" => "idn",
      'scheme' =>'http',
  
      "endpoint" => $endpoint,
  
      "use_path_style_endpoint" => true,
  
      "credentials" => [
  
          "key" => $key,
  
          "secret" => $secret
  
      ],
  
  ]);
  return $s3;
}
function delete_s3($type,$file_name) {
  global $db;
  //get config
  $s3 = $db->fetchSingleRow('s3_storage','type',$type);
  $bucket = $s3->bucket;
  $endpoint = $s3->url;
  $init = init_s3('file',$s3->key,$s3->secret,$endpoint);
  $results = $init->deleteObject(array(
    'Bucket' => $bucket,
    'Key'    => $file_name
    )); 
    return $results;
}
function upload_s3($type,$filename,$file,$file_type) {
  global $db;
    //get config
    $s3 = $db->fetchSingleRow('s3_storage','type',$type);
    $bucket = $s3->bucket;

    $endpoint = $s3->url;
    
    $s3 = new Aws\S3\S3Client([
    
        "version" => "latest",
    
        "region" => "idn",
        'scheme' =>'http',
    
        "endpoint" => $endpoint,
    
        "use_path_style_endpoint" => true,
    
        "credentials" => [
    
            "key" => $s3->key,
    
            "secret" => $s3->secret
    
        ],
    
    ]);
    
    $result = $s3->putObject([
    
        "Bucket" => $bucket,
    
        "Key" => $filename,
    
        "Body" => "this is the body!",
    
        // you can use relative
        // "SourceFile" => "./aws-sdk-php-v3-developer-guide.pdf",
    
        // or absolute path
        "SourceFile" => $file,
    
        "ContentType" => $file_type,
        'ACL'    => 'public-read',
    
    ]);

    return $result;

    //return $endpoint.'/'.$bucket.'/'.$filename;
}
function getSize($filesize) { 
 if(is_numeric($filesize)){
    $decr = 1024; $step = 0;
    $prefix = array('Byte','KB','MB','GB','TB','PB');
        
    while(($filesize / $decr) > 0.9){
        $filesize = $filesize / $decr;
        $step++;
    } 
    return round($filesize).' '.$prefix[$step];
    } else {

    return 'NaN';
    }
} 

/**
 * check jika kampus punya fakultas
 *
 * @return boolean
 */
function hasFakultas()
{
    global $db;
    $has_fakultas = getPengaturan('has_fakultas');
    if ($has_fakultas=='Y') {
        return true;
    } else {
        return false;
    }
}
/**
 * menampilkan looping select list prodi sesuai akses prodi yang diberikan
 * @return [type] select option list prodi
 */
function loopingProdi($filter_modul="",$id_fakultas="") {
  if ($filter_modul!="") {
    $selected_filter_value = getFilter(array($filter_modul => 'prodi'));
  }
  $and_id_fakultas = "";
  if ($id_fakultas!="" && $id_fakultas!="all") {
    $and_id_fakultas = "and id_fakultas='".$id_fakultas."'";
  }
  global $db;
  $akses_prodi = getAksesProdi();
  if ($akses_prodi) {
    $jurusan = $db->query("select * from view_prodi_jenjang where kode_jur in ($akses_prodi) $and_id_fakultas");
        //jika jurusan hanya punya 1 akses prodi, misal admin prodi
   if ($jurusan->rowCount()==1) {
      foreach ($jurusan as $dt) {
        echo "<option value='$dt->kode_jur' selected>".string_rapih($dt->nama_jurusan)."</option>";
      }
    } else {
      //jika group user punya akses ke semua prodi
      echo "<option value='all'>Semua</option>";
      foreach ($jurusan as $dt) {
        if ($filter_modul!="" && $selected_filter_value==$dt->kode_jur) {
          echo "<option value='$dt->kode_jur' selected>".string_rapih($dt->nama_jurusan)."</option>";
        } else {
          echo "<option value='$dt->kode_jur'>".string_rapih($dt->nama_jurusan)."</option>";
        }
        
      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }

}
/**
 * get filter session
 * @param  [type] $array_filter_name array filter modulname and filtername
 * @return string/boolean       value of filtername
 */
function getFilter($array_filter_name) {
    foreach ($array_filter_name as $key => $value) {
      $filter_modul = $key;
      $filter_name = $value;
    }
    if (isset($_SESSION[$filter_modul][$filter_name])) {
        return $_SESSION[$filter_modul][$filter_name];
    } else {
      return '';
    }
}
/**
 * return implode kode jur base on hak akses prodi
 * @return [type] list comma separated kode prodi
 */
function getAksesProdi() {
  global $db;
  $data_prodi = array();
  $kode_prodi = "";
  $get_akses_prodi = $db->fetchSingleRow("sys_group_users","level",$_SESSION['group_level']);
  if ($get_akses_prodi->akses_prodi!="") {
    $decode_prodi = json_decode($get_akses_prodi->akses_prodi);
    $kode_prodi = $decode_prodi->akses;
  }
  if ($kode_prodi!="") {
    return $kode_prodi;
  } else {
    return false;
  }
}
function string_rapih($word) {
  return preg_replace( '/[^[:print:]]/', '',str_replace("Dan", "dan", ucwords(strtolower($word))));
}
?>