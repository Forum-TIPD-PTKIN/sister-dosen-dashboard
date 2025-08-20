               <!-- Content Header (Page header) -->
               <section class="content-header">
                    <h1>
                        Hak Akses Prodi
            </h1>
                       <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>hak-akses-prodi">Akses Prodi</a></li>
                        <li class="active">Hak Akses Prodi List</li>
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

<form method="get" class="form-horizontal" action="">
                      <div class="form-group">
                        <label for="Menu" class="control-label col-lg-2">Group Pengguna</label>
                        <div class="col-lg-4">
                            <select name="user" id="show_group" data-placeholder="Pilih User" class="form-control chzn-select" tabindex="2">
                        <option value="">Pilih Group Pengguna</option>
                          <?php 

foreach ($db->query("select * from  sys_group_users where id!=1") as $isi) {
                  if (intval($_GET['user'])==$isi->id) {
                     echo "<option value='$isi->id' selected>$isi->level_name</option>";
                  } else {
                     echo "<option value='$isi->id'>$isi->level_name</option>";
                  }
                 
               } ?>

                  
                  </select>
                        </div>
                      </div><!-- /.form-group -->

</form>

                                <div class="box-body table-responsive">
          
<?php if (isset($_GET['user'])) {
  
?>       
<h3>Checklist Untuk memberikan Hak Akses</h3>
<table id="dtb_prodi" class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <tr>
                        <th style="width:20px">No</th>
                          <th>Program Studi </th>
                          <th><div class="checkbox">
              <div class="checkbox checkbox-primary">
                        <input class="styled styled-primary" type="checkbox" id="bulk_check">
                        <label for="checkbox2">
                           Akses
                        </label>
                    </div>
                          </div></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
      $data_prodi = array();
      $dtb=$db->fetchSingleRow("sys_group_users","id",$_GET['user']);
      $id_group = "1";
      $id_group = $dtb->id;
      if ($dtb->akses_prodi!="") {
      $decode_prodi = json_decode($dtb->akses_prodi);
      $akses_p = $decode_prodi->akses;
      
          $data_prodi = explode(",", $decode_prodi->akses);
      }
      $jurusan = $db->query("select kode_jur,concat(tb_ref_dikti_jenjang_pendidikan.nm_jenj_didik,' ',tb_master_jurusan.nama_jur) as jurusan from tb_master_jurusan
inner join tb_ref_dikti_jenjang_pendidikan on tb_master_jurusan.id_jenj_didik=tb_ref_dikti_jenjang_pendidikan.id_jenj_didik order by nama_jur asc");

      $i=1;
      foreach ($jurusan as $isi) {
        ?><tr id="line_<?=$dtb->id;?>">
        <td>
        <?=$i;?></td>
        <td><?=$isi->jurusan;?></td>
        <td>
              <div class="checkbox">
              <div class="checkbox checkbox-primary">
                        <input class="styled styled-primary selected_check" type="checkbox" value="<?=$isi->kode_jur;?>" onclick="change_act(<?=$dtb->id;?>,this)" <?=(in_array($isi->kode_jur,$data_prodi))?'checked=""':'';?>>
                        <label for="checkbox2">
                            &nbsp;
                        </label>
                    </div>
                          </div>
        </td>
      
        </tr>
        <?php
        $i++;
      }
      ?>
                   </tbody>
                    </table>


                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                </section><!-- /.content -->
  



<script type="text/javascript">
$("#bulk_check").on('click',function() { // bulk checked
          var status = this.checked;
          $(".selected_check").each( function() {
            $(this).prop("checked",status);
          });

    var ids = [];
    $('.selected_check').each(function(){
      if($(this).is(':checked')) {
        ids.push($(this).val());
      }
    });
    var ids_string = ids.toString();
    $.ajax({
        type: "POST",
        url: "<?=base_admin();?>modul/hak_akses_prodi/hak_akses_prodi_action.php",
        data: {id_group :<?=$id_group;?>, data_ids:ids_string},
        success: function(result) {
              console.log(result);
        },
        //async:false
      });

});


function change_act() {
    var ids = [];
    $('.selected_check').each(function(){
      if($(this).is(':checked')) {
        ids.push($(this).val());
      }
    });
    var ids_string = ids.toString();
    $.ajax({
        type: "POST",
        url: "<?=base_admin();?>modul/hak_akses_prodi/hak_akses_prodi_action.php",
        data: {id_group :<?=$id_group;?>,data_ids:ids_string},
        success: function(result) {
              console.log(result);
        },
        //async:false
      });


}
</script>
<?php 

}  

?>
<script type="text/javascript">
$("#dtb_prodi").DataTable({
    "paging": false,
});
$("#show_group").change(function(){
    if ($(this).val()!='') {
          $(location).attr('href', "<?=base_index();?>hak-akses-prodi?user="+$(this).val());
    }

});
  </script>

                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                </section><!-- /.content -->