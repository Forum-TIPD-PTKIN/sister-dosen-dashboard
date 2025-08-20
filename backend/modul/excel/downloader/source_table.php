<?php
include "../../../inc/config.php";
?>
<style type="text/css">
tbody tr {
  cursor: move;
}

</style>
   <div class="col-lg-12">
                      <div class="form-group">
                        <label class="control-label col-lg-1">Target Table</label>
                        <div class="col-lg-5">
                          <select id="local_table" data-placeholder="Pilih Table" name="target_table" class="form-control chzn-select" tabindex="2" required>
                            <option value="">Select Table</option>
                            <?php foreach ($db->query('show table status') as $tb) {
                              echo "<option value='$tb->Name'>$tb->Name</option>";
                            }
                            ?>
                          </select>
                        </div>
                        <div class="col-lg-2" style="padding-left:0">
                          <span class="btn btn-success refresh-table" data-toggle="tooltip" data-title="Refresh Table List"><i class="fa fa-refresh"></i></span>
                          <span class="btn btn-primary load-column" data-toggle="tooltip" data-title="Show Selected Column"><i class="fa fa-refresh"></i> Load Column</span>
                        </div>
                      </div>
<div id="isi_mapping"></div>
<script type="text/javascript">
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
   $("#input_sort").sortable({
           cancel: ".primary,select,input,checkbox,.bootstrap-switch",
            axis: "y",
             connectWith: '.input_sort',
           cursorAt: { top: 0, left: 0 },
           cursor: "move",
           helper: fixWidthHelper,
           tolerance: "pointer",
           placeholder: "ui-highlight"
        });
   function fixWidthHelper(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
}
    $( "#input_sort" ).disableSelection();


$('.refresh-table').click(function(){
           $.ajax({
              url: "<?=base_admin();?>modul/excel/downloader/target_table.php",
              method : "post",
              success:function(data){
              //  alert(data);
                $("#local_table").html(data);
                $("#local_table").trigger("chosen:updated");
              }
          });
});
$(".load-column").click(function(){
      var checkboxValues = [];
      $('.dipilih1:checkbox:checked').map(function() {
                  checkboxValues.push($(this).val());
      });
      var values = $("#input_downloader").serialize();
      //console.log(checkboxValues);
      if (checkboxValues!='') {
           $.ajax({
              url: "<?=base_admin();?>modul/excel/downloader/mapping.php",
              method : "post",
               data : values,
/*              data : {
                table_name : $("#local_table").val(),
                colname : checkboxValues,
                post : values
              },*/
              success:function(data){
              //  alert(data);
                $("#isi_mapping").html(data);
              }
          });
      } else {
        alert('select some column please');
      }

});
</script>
