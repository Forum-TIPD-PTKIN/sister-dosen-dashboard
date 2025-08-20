<?php
include "../../inc/config.php";


function get_data_sorting($datas,$parent=0,$depth=0) {
  $depth++;
  $data = array();
  foreach ($datas as $key => $value) {
    $data[] = array(
      'id' => $value['id'],
      'urutan_menu' => $key+1,
      'depth' => $depth-1,
      'parent' => $parent
    );

    if (isset($value['children'])) {
      $data = array_merge($data, get_data_sorting($value['children'],$value['id'],$depth));
    }
  }

  return $data;
}


    $data_sorting = $_POST['sorting'];

    $data_sort = get_data_sorting($data_sorting,0);

    foreach ($data_sort as $key) {
        $array_update_menu['urutan_menu'] = $key['urutan_menu'];
        $array_update_menu['parent'] = $key['parent'];
        $array_update_menu['depth'] = $key['depth'];
        $db->update("sys_menu",$array_update_menu,'id',$key['id']);
    }
?>