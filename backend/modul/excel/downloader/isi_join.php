<?php
include "../../../inc/config.php";
$prev_table = $_GET['prev_tb'];

$table = $db2->query("show table status where Name!=?",array('Name'=>$prev_table));

$prev = $db2->query("show columns from $prev_table");

 ?>
  <div class="body">
                      <div class="row form-group">
                          <div class="col-lg-1" style="width:130px;margin-left:0;">
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
                          echo "<option>Choose Join With Table </option>";
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
  //val is selected table join
  function isi_kanan(thisObj,val,prev_tb)
  {
      old_data = thisObj.attr('orig');
      thisObj.attr('orig',thisObj.val());
      console.log(old_data);
      if (old_data!=thisObj.val()) {
        //del_join(old_data);
        $('.embed_'+old_data).remove();
      }

      if (old_data === undefined) {
        $('#isi_kanan_join').attr('id','isi_kanan_join_'+val);
        $.ajax({
          //?tb="+tb+"&col="+col
          url: "<?=base_admin();?>modul/excel/downloader/isi_kanan_join.php?tb="+val+"&prev="+prev_tb,
          success:function(data){
            $("#isi_kanan_join_"+val).html(data);
            //$("#isi_kanan_join_"+val).trigger("chosen:updated");
            }
          });
      } else {
        $("#isi_kanan_join_"+old_data).attr('id','isi_kanan_join_'+val);
        $.ajax({
          //?tb="+tb+"&col="+col
          url: "<?=base_admin();?>modul/excel/downloader/isi_kanan_join.php?tb="+val+"&prev="+prev_tb,
          success:function(data){
            $("#isi_kanan_join_"+val).html(data);
            //$("#isi_kanan_join_"+val).trigger("chosen:updated");
            }
          });
        $.ajax({
          //?tb="+tb+"&col="+col
          url: "<?=base_admin();?>modul/excel/downloader/select_on_value.php?tb="+val+"&prev="+prev_tb,
          success:function(data){
            $(".select-on-"+old_data).html(data);
            $(".select-on-"+old_data).trigger("chosen:updated");
            $(".select-on-"+old_data).removeClass("select-on-"+old_data).addClass("select-on-"+val);
            }
          });
      }


     $.ajax({
      //?tb="+tb+"&col="+col
      url: "<?=base_admin();?>modul/excel/downloader/embed_list.php?tb="+val,
      success:function(data){
        $('#list_table > tbody:last-child').append(data);
       // $("#embed").append(data);
        }
      });
  }
  function add_join_with(prev)
  {
     $.ajax({

      //?tb="+tb+"&col="+col
      url: "<?=base_admin();?>modul/excel/downloader/isi_join_with.php?prev_tb="+prev,
      success:function(data){

        $("#isi_join").append(data);
        }
      });
  }
</script>
