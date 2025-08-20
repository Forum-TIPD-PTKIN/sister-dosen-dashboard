<?php
include "../../../inc/config.php";
$prev_table = $_GET['prev_tb'];

$table = $db2->query("show table status where Name!=?",array('Name'=>$prev_table));

$prev = $db2->query("show columns from $prev_table");

 ?>
  <div class="body">
                      <div class="row form-group">
                          <div class="col-lg-1" style="width:110px;margin-left:0;padding-left:0">
                          <select class="form-control" name="join_cond[]" >
                            <option value="inner">INNER</option>
                            <option value="left">LEFT</option>
                            <option value="right">RIGHT</option>
                          </select>
                        </div><!-- /.col-lg-6 -->
                        <label class="control-label" style="float:left;width:20px;margin-left:-10px">JOIN</label>
                        <div class="col-lg-3">
                          <select class="form-control chzn-select" onchange="isi_kanan($(this),this.value,'<?=$prev_table;?>')" name="join_with[]" >
                          <?php 
                          echo "<option>Choose Join With Table</option>";
                          
                          foreach ($table as $tab) {
                            echo "<option value='$tab->Name'>$tab->Name</option>";
                          }?>
                          </select>
                        </div><!-- /.col-lg-6 -->
                        <label class="control-label" style="float:left;width:15px;margin-left:-10px">ON</label>
<div class="col-lg-3">
      <select class="form-control chzn-select select-on-<?=$prev_table;?>" name="join_on_first[]" >
                              <?php foreach ($prev as $cols) {
                            echo "<option value='$prev_table.$cols->Field'>$prev_table.$cols->Field</option>";
                          }
                          ?>
                          </select>
                        </div><!-- /.col-lg-6 -->
                   
                        <div id="isi_kanan_join"></div>
                      </div><!-- /.row -->
</div>
<script type="text/javascript">
        //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
</script>
