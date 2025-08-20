<?php
include "../../../inc/config.php";
?>
<style type="text/css">
tbody tr {
  cursor: move;
}
</style>
   <div class="col-lg-12">
   	<h3>MAPPING TABLE</h3>
                <div class="box">
                  <div id="defaultTable" class="body collapse in">
                    <table class="table responsive-table" >
                      <thead>

                        <tr style="background: rgb(60, 141, 188);color: #fff;">
                          <th>No</th>
                          <th>SOURCE FIELD</th>
                          <th>TARGET FIELD</th>
                          <th style="width: 70px;">Is Filter</th>
                          <th>Description</th>
                        </tr>
                      </thead>
                       <tbody id="input_sort" class="contain">      
<?php
$column = $db->query("show FULL columns from ".$_POST['target_table']);
foreach ($column as $col) {
  $col_target[$col->Field] = $col;
}

$no = 1;
//dump($_POST);
$alias = array_filter($_POST['alias1']);

foreach ($_POST['dipilih1'] as $key_col => $col_name) {
  $xpl_col = explode("#", $col_name);
  $is_filter_col = substr(strrchr($key_col, '.'), 1);
  ?>
   <tr>
  <td style="width:1px">
    <?=$no;?>
  </td>
    <td><?=$key_col;?> <?=$xpl_col['1'];?></td>
    <td>
    <select name="target_field[<?=$alias[$key_col];?>]" class="form-control chzn-select column-target">
      <option value="no_download_col">No Download</option>
      <?php
      foreach ($col_target as $target_col) {
        ?>
        <option value="<?=$target_col->Field;?>"><?=$target_col->Field;?> <?=$target_col->Type;?></option>
        <?php
      }
      ?>
     </select>
      <div id="required_content_<?=$col->Field;?>">

     </div>
     <div id="type_content_<?=$col->Field;?>">

     </div>

  </td>
  <td>
    <div class="checkbox checkbox-success" style="padding-left: 30px;">
    <input type="checkbox" class="is_filter flat-red" name="is_filter[<?=$is_filter_col;?>]" value="<?=$col->Field;?>">
    <label>&nbsp;</label>
    </div>
  </td>
  <td>

                          <input type="text" class="form-control description_target" name="description_target[<?=$key_col.".".$col->Field;?>]" required>
                       </td>
  </tr>
  <?php
  $no++;
}

?>


    </tbody>
 </table>



                  </div>
                </div>

<div style="margin-bottom:20px" id="isi_remote"></div>


              </div><!-- /.col-lg-6 -->
 <style type="text/css">
   .ui-highlight {
    border: 1px dashed #999;
    height: 0px;
    background: #fee;

 </style>
<script type="text/javascript">
  $('.column-target').change(function(){
    //$(this).closest('td').siblings().find('.description_target').val('sd');

    target = $(this);
      if ($(this).val()!='') {
           $.ajax({
              url: "<?=base_admin();?>modul/excel/downloader/get_comment.php",
              method : "post",
              data : {
                table_name : "<?=$_POST['target_table'];?>",
                col_name : $(this).val()
              },
              success:function(data){
                console.log(data);
                target.closest('td').siblings().find('.description_target').val(data);
              }
          });
        
      }
  });
        //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });

</script>
