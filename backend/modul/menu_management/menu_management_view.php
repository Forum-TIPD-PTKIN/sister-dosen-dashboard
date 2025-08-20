<style type="text/css">
  .radio, .checkbox {
    margin-top: 2px; 
    margin-bottom: 2px; 
}
th {
  text-align: center;
}
td {
  vertical-align: middle;
}
.form-horizontal .checkbox {
  padding-top:2px;
}
</style>
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                       Group User Permission
            </h1>
                       <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>menu-management">Menu Management</a></li>
                        <li class="active">Group User Permission</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                  <h3 class="box-title">&nbsp;</h3>
                                </div><!-- /.box-header -->



                                <div class="box-body table-responsive">
<form method="get" class="form-horizontal" action="">
                      <div class="form-group">
                        <label for="Menu" class="control-label col-lg-2 ">Group Users</label>
                        <div class="col-lg-3" style="margin-bottom:5px">
                            <select name="user" id="id_po_select" data-placeholder="Pilih User" class="form-control select2" tabindex="2">
                        <option value="">Choose Group User</option>
                          <?php 

foreach ($db->query("select sys_group_users.id,level, sys_group_users.level_name from sys_group_users where level!='root' ") as $isi) {

                  if ($_GET['user']==$isi->level) {
                     echo "<option value='$isi->level' selected>$isi->level_name</option>";
                  } else {
                     echo "<option value='$isi->level'>$isi->level_name</option>";
                  }
                 
               } ?>

                  
                  </select>
                        </div>
                        <div class="col-lg-5">
                          <button class="btn btn-primary">Show Menu</button>
                        </div>
                      </div><!-- /.form-group -->
</form>
<?php if (isset($_GET['user'])) {
  
?>       
<h3>Check The Checkbox To Give Permission</h3>
<?php 

function buildMenuUser($url,$parent, $menu,$has_parent='N')
{
    $html = "";
    $tr_bg = "";
    $class_page = "";
    if (isset($menu['parents'][$parent]))
    {
      $no=1;
        foreach ($menu['parents'][$parent] as $itemId)
        {
          
            if(!isset($menu['parents'][$itemId]))
            {
                $type = 'main';
                $class_page = '';
                if ($has_parent=='N') {
                    $tr_bg = "style='background:#dfdfdf'";
                }
                if ($menu['items'][$itemId]['type_menu']=='page') {
                    $class_page = "class='mjs-nestedSortable-no-nesting'";
                    $type = 'page';
                } elseif ($menu['items'][$itemId]['type_menu']=='separator') {
                    $class_page = "class='mjs-nestedSortable-disabled'";
                } 
                $html .= "<tr $class_page data-type='$type' id='dataId_".$menu['items'][$itemId]['id']."' $tr_bg>";
                $html .= "<td class='dt-center'><span class='btn btn-xs btn-primary check-all' data-id='".$menu['items'][$itemId]['id']."'>Check All</span></td>";
                $html .= "<td style='padding-left:20px;vertical-align: middle;'>";
                $html.="";
                if($menu['items'][$itemId]['icon']!='') {
                    $html.="<i class='fa ".$menu['items'][$itemId]['icon']."'></i> ";
                } else {
                    $html.="<i class='fa fa-circle-o'></i> ";
                }
                $html.=ucwords($menu['items'][$itemId]['page_name'])."</td>";
                $html .= "<td class='dt-center'>
                          <div class='checkbox'>
                            <div class='checkbox checkbox-primary'>
                              <input class='styled styled-primary check-single check_".$menu['items'][$itemId]['id']."' type='checkbox' data-role='read_act' data-id='".$menu['items'][$itemId]['id']."' ".($menu['items'][$itemId]['read_act']=='Y'?'checked=""':'')."'>
                              <label for='checkbox2'>&nbsp;</label>
                            </div>
                          </div>
                        </td>";
                $html .= "<td class='dt-center'>
                          <div class='checkbox'>
                            <div class='checkbox checkbox-primary'>
                              <input class='styled styled-primary check-single check_".$menu['items'][$itemId]['id']."' type='checkbox' data-role='insert_act' data-id='".$menu['items'][$itemId]['id']."' ".($menu['items'][$itemId]['insert_act']=='Y'?'checked=""':'')."'>
                              <label for='checkbox2'>&nbsp;</label>
                            </div>
                          </div>
                        </td>";
                $html .= "<td class='dt-center'>
                          <div class='checkbox'>
                            <div class='checkbox checkbox-primary'>
                              <input class='styled styled-primary check-single check_".$menu['items'][$itemId]['id']."' type='checkbox' data-role='update_act' data-id='".$menu['items'][$itemId]['id']."' ".($menu['items'][$itemId]['update_act']=='Y'?'checked=""':'')."'>
                              <label for='checkbox2'>&nbsp;</label>
                            </div>
                          </div>
                        </td>";
                $html .= "<td class='dt-center'>
                          <div class='checkbox'>
                            <div class='checkbox checkbox-primary'>
                              <input class='styled styled-primary check-single check_".$menu['items'][$itemId]['id']."' type='checkbox' data-role='delete_act' data-id='".$menu['items'][$itemId]['id']."' ".($menu['items'][$itemId]['delete_act']=='Y'?'checked=""':'')."'>
                              <label for='checkbox2'>&nbsp;</label>
                            </div>
                          </div>
                        </td>";
                $html .= "<td class='dt-center'>
                          <div class='checkbox'>
                            <div class='checkbox checkbox-primary'>
                              <input class='styled styled-primary check-single check_".$menu['items'][$itemId]['id']."' type='checkbox' data-role='import_act' data-id='".$menu['items'][$itemId]['id']."' ".($menu['items'][$itemId]['import_act']=='Y'?'checked=""':'')."'>
                              <label for='checkbox2'>&nbsp;</label>
                            </div>
                          </div>
                        </td>";
                $html .= "</tr>";
            }

            if(isset($menu['parents'][$itemId]))
            {
                $type = 'main';
                $class_page = '';
                if ($menu['items'][$itemId]['type_menu']=='page') {
                    $class_page = "class='mjs-nestedSortable-no-nesting'";
                    $type = 'page';
                } elseif ($menu['items'][$itemId]['type_menu']=='separator') {
                    $class_page = "class='mjs-nestedSortable-disabled'";
                } 

                $html .= "<tr data-type='$type' $class_page id='dataId_".$menu['items'][$itemId]['id']."' style='background:#dfdfdf;'>";
                $html .= "<td colspan='2' style='vertical-align: middle;'>";
                if($menu['items'][$itemId]['icon']!='') {
                    $html.="<i class='fa ".$menu['items'][$itemId]['icon']."'></i> ";
                } else {
                    $html.="<i class='fa fa-circle-o'></i> ";
                }
                $html.= ucwords($menu['items'][$itemId]['page_name'])."</td>";
                $html .= "<td class='dt-center'>
                          <div class='checkbox'>
                            <div class='checkbox checkbox-primary'>
                              <input class='styled check-single styled-primary' type='checkbox' data-role='read_act' data-id='".$menu['items'][$itemId]['id']."' ".($menu['items'][$itemId]['read_act']=='Y'?'checked=""':'')."'>
                              <label for='checkbox2'>&nbsp;</label>
                            </div>
                          </div>
                        </td> <td colspan='4'></td>";
                $html .="<ul class='submenu-list'>";
                $html .=buildMenuUser($url,$itemId, $menu,'Y');
                $html .= "</ul></tr>";
                 $no++;
            }

        }

}
return $html;

}
?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>

<table id="dtb" class="table table-bordered">
                        <thead>
                        <tr>
                          <th style='width:10px'>#</th>
                          <th>Menu </th>
                          <th width="10%">View</th>
                           <th width="10%">Add</th>
                            <th width="10%">Edit</th>
                             <th width="10%">Delete</th>
                             <th width="10%">Import</th>
                         
                        </tr>
                      </thead>
<?php

$menu = array(
    'items' => array(),
    'parents' => array()
);
$group_level = $db->fetchSingleRow("sys_group_users","level",$_GET['user']);
if ($group_level) {
$and_root = "";
if ($group_level->level!='root') {
    $and_root = "and id!=144 and parent!=144";
}

$level = $group_level->level;
// Builds the array lists with data from the menu table
foreach ($db->query("select *,
(select sys_menu_role.read_act from sys_menu_role where sys_menu_role.id_menu=sys_menu.id and sys_menu_role.group_level='$level') as read_act,
(select sys_menu_role.insert_act from sys_menu_role where sys_menu_role.id_menu=sys_menu.id and sys_menu_role.group_level='$level') as insert_act,
(select sys_menu_role.update_act from sys_menu_role where sys_menu_role.id_menu=sys_menu.id and sys_menu_role.group_level='$level') as update_act,
(select sys_menu_role.delete_act from sys_menu_role where sys_menu_role.id_menu=sys_menu.id and sys_menu_role.group_level='$level') as delete_act,
(select sys_menu_role.import_act from sys_menu_role where sys_menu_role.id_menu=sys_menu.id and sys_menu_role.group_level='$level') as import_act 
 from sys_menu where tampil='Y' $and_root order by parent,urutan_menu asc ") as $items) {

  $items = $db->converObjToArray($items);

      // Creates entry into items array with current menu item id ie.
    $menu['items'][$items['id']] = $items;
    // Creates entry into parents array. Parents array contains a list of all items with children
    $menu['parents'][$items['parent']][] = $items['id'];
}
echo buildMenuUser(uri_segment(0),0, $menu);


}
echo "<input type='hidden' data-id='group_level' value='$level'>";  

}


?>
</table>

                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                </section><!-- /.content -->
  



<script type="text/javascript">
     
$(".check-all").on('click',function() { // bulk checked
        //var status = this.checked;
        //var val = $(this).val();
     var currentBtn = $(this);
     var checked        = true;
        id = currentBtn.attr('data-id');
        $('.check_'+id).each(function(e){
            checked = !$(this).prop('checked') ? false : checked;
        });
        var status = !checked;
        console.log(checked);
        $('.check_'+id).prop("checked",status);
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/menu_management/menu_management_action.php?act=mass',
            data: {id_menu:id,status:status,level:"<?=$level;?>"},
            success: function(result) {
               $('#loadnya').hide();
              $(".notif_top_up").fadeIn(1000);
              $(".notif_top_up").fadeOut(1000);
            },
        });
});

$(".check-single").on('click',function() { // bulk checked
        var status = this.checked;
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');
        role = currentBtn.attr('data-role');
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/menu_management/menu_management_action.php?act=single',
            data: {id_menu:id,role:role,status:status,level:"<?=$level;?>"},
            success: function(result) {
               $('#loadnya').hide();
              $(".notif_top_up").fadeIn(1000);
              $(".notif_top_up").fadeOut(1000);
               //console.log(result);
            },
        });
});


</script>

