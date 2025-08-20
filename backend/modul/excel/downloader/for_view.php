<?php
include "../../../inc/config.php";
?>

   <div class="col-lg-12">
    <h3>VIEW DATA</h3>
               
                 <div id="isi_join"></div>
  <span class='btn btn-primary btn-flat' onClick="add_join()" style="margin-bottom: 10px;"><i class='fa  fa-plus-square'></i> Add Join</span>
                <div class="box">
                  <div id="defaultTable" class="body collapse in">
                    <table class="table responsive-table" id="list_table">
                      <thead>
                        <tr style="background: rgb(60, 141, 188);color:#fff;cursor:auto">
                          <th>&nbsp;</th>
                          <th>FIELD</th>
                          <th>ALIAS</th>
                        </tr>
                        <tr style="background: rgb(0, 166, 90);color: #fff;cursor:auto">
                      <th colspan="3">Table Name :  <?=$_GET['tb'];?></th>
                      </tr>
                      </thead>
                        <tbody id="list_table_sort" class="list_contain">
<?php
$column = $db2->query("show columns from ".$_GET['tb']);
foreach ($column as $col) {
	?>
<tr>
<td style="width:1px">
  <div class="checkbox checkbox-success" style="padding-top:0">
<?php
if ($col->Key=='PRI') {
?>
 <input type="checkbox" class="dipilih1" id="checked1_<?=$_GET['tb'].'_'.$col->Field;?>" name="dipilih1[<?=$_GET['tb'];?>.<?=$col->Field;?>]" value="<?=$col->Field;?>#<?=$col->Type;?>" onClick="copy_to1('<?=$_GET['tb']?>','<?=$col->Field?>','pr')" class="flat-red">
 <?php
} else {
?>
 <input type="checkbox" class="dipilih1" id="checked1_<?=$_GET['tb'].'_'.$col->Field;?>" name="dipilih1[<?=$_GET['tb'];?>.<?=$col->Field;?>]" value="<?=$col->Field;?>#<?=$col->Type;?>" onClick="copy_to1('<?=$_GET['tb']?>','<?=$col->Field?>','')" class="flat-red">
 <?php
}
?>

                     <label>&nbsp;</label>
                    </div>

</td>
<td>
<?=$col->Field;?>

</td>
                          <td>
<input type="text" class="form-control" id="label1_<?=$_GET['tb'].'_'.$col->Field;?>" name="alias1[<?=$_GET['tb'];?>.<?=$col->Field;?>]"></td>

                       
                        </tr>




	<?php
}

?>
   </tbody>
 </table>
                  </div>
                </div>
                <div id="embed"></div>
 <div class="form-group">
                 <label class="control-label col-lg-2" style="text-align: left">Query
                 <span class="btn btn-info load-query" style="float: right;">Load Query</span>
               </label>
                 <div class="col-lg-7">
                   <textarea class="form-control query-data" rows="4" name="query_data" required=""></textarea>
                 </div>
                 <div class="col-lg-3">
                   <span class="btn btn-primary run-query"><i class="fa fa-play"></i> Run Query</span>
                 </div>
</div>
 <div class="form-group">
                 <label class="control-label col-lg-2">Count Query
               </label>
                 <div class="col-lg-7">
                   <textarea class="form-control count-query-data" rows="4" name="count_query_data" required=""></textarea>
                 </div>
</div>

 <div class="form-group">
                 <label class="control-label col-lg-2">Limit
               </label>
                 <div class="col-lg-1">
                   <input type="number" class="form-control limit" name="limit" value="50">
                 </div>
</div>

              </div><!-- /.col-lg-6 -->
              <div id="isi_select">
                
              </div>
 <div class="form-group">
                 <label class="control-label col-lg-2">Remote Url/Dir Location
               </label>
                 <div class="col-lg-5">
                   <input type="text" class="form-control url_location" name="url_location" required>
                 </div>
</div>
 <style type="text/css">
   .ui-high {
    border: 1px dashed #999;
    height: 10px;
    background: #fee;


}
 </style>
<script type="text/javascript">
$("#token_type").change(function(){
    if (this.value=='temporary') {
        $("#expire_in").show();
    } else {
        $("#expire_in").hide();
    }
});
                      $.each($('.make-switch'), function () {
        $(this).bootstrapSwitch({
            onText: $(this).data('onText'),
            offText: $(this).data('offText'),
            onColor: $(this).data('onColor'),
            offColor: $(this).data('offColor'),
            size: $(this).data('size'),
            labelText: $(this).data('labelText')
        });
    });

 $("#list_table_sort").sortable({
           cancel: ".primary,select,input,checkbox",
            axis: "y",
           cursorAt: { top: 0, left: 0 },
           cursor: "move",
           helper: fixWidthHelper,
           containment: 'parent',
           tolerance: "pointer",
           placeholder: "ui-high"
        });
   function fixWidthHelper(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
}
    $( "#list_table_sort" ).disableSelection();

$(".load-query").click(function(){

            var values = $("#input_downloader").serialize();
              $.ajax({
              type : "post",
              url : "<?=base_admin();?>modul/excel/downloader/load_query.php",
              data : values,
                dataType: 'json',
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  console.log(responseText);
                      $.each(responseText, function(index) {
                        $('.query-data').html(responseText[index].query);
                        $('.count-query-data').html(responseText[index].count_query);
                          console.log(responseText[index].status);
                    });
                }

          });
});

$(".run-query").click(function(){
            var values = $("#input_downloader").serialize();
              $.ajax({
              type : "post",
              url : "<?=base_admin();?>modul/excel/downloader/get_query.php",
              data : {query : $(".query-data").val(),limit : $(".limit").val()},
              success: function(data) {
                  $("#isi_select").html(data);
              }

          });
});
</script>
