<?php
$setting = $db->fetchSingleRow("tb_pengaturan_api", "id", 1);
if (!$setting) {
    // Inisialisasi jika belum ada
    $db->query("INSERT INTO tb_pengaturan_api (id, api_base_url, api_sandbox_url, role, mode, token_lifetime) VALUES (1, 'https://sister-api.kemdiktisaintek.go.id/ws.php/1.0', 'https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0', 'Sister-WS Consumer Basic', 'production', 3600)");
    $setting = $db->fetchSingleRow("tb_pengaturan_api", "id", 1);
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Pengaturan API SISTER
        <small>Konfigurasi Web Service Kemdiktisaintek</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Pengaturan API SISTER</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cogs"></i> Parameter Koneksi API SISTER</h3>
                </div><!-- /.box-header -->
                
                <form id="form_pengaturan_api" method="post" action="<?=base_admin();?>modul/pengaturan_api/pengaturan_api_action.php?act=save" class="form-horizontal">
                    <div class="box-body">
                        <div id="alert_box" style="display:none;" class="alert alert-dismissable">
                            <button type="button" class="close" onclick="$('#alert_box').hide();">&times;</button>
                            <span id="alert_msg"></span>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mode API <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="mode" value="production" <?=($setting->mode !== 'sandbox') ? 'checked' : '';?>> 
                                    <span class="label label-success">Production</span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="mode" value="sandbox" <?=($setting->mode === 'sandbox') ? 'checked' : '';?>> 
                                    <span class="label label-warning">Sandbox / Testing</span>
                                </label>
                                <p class="help-block text-muted">Pilih mode environment yang ingin digunakan.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="api_base_url" class="col-sm-3 control-label">Production Base URL <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="api_base_url" name="api_base_url" value="<?=htmlspecialchars($setting->api_base_url);?>" required placeholder="https://sister-api.kemdiktisaintek.go.id/ws.php/1.0">
                                <p class="help-block text-muted">Contoh: https://sister-api.kemdiktisaintek.go.id/ws.php/1.0</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="api_sandbox_url" class="col-sm-3 control-label">Sandbox URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="api_sandbox_url" name="api_sandbox_url" value="<?=htmlspecialchars($setting->api_sandbox_url);?>" placeholder="https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0">
                                <p class="help-block text-muted">URL endpoint untuk mode sandbox.</p>
                            </div>
                        </div>

                        <hr style="margin: 15px 0;">

                        <div class="form-group">
                            <label for="user_id" class="col-sm-3 control-label">ID Pengguna (User ID) <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="user_id" name="user_id" value="<?=htmlspecialchars($setting->user_id);?>" required placeholder="UUID / ID Pengguna dari SISTER">
                                <p class="help-block text-muted">ID Pengguna (id_pengguna) yang didaftarkan pada Manajemen Hak Akses SISTER.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-sm-3 control-label">Username SISTER <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="username" name="username" value="<?=htmlspecialchars($setting->username);?>" required placeholder="Username API SISTER">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-3 control-label">Password SISTER</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" value="<?=htmlspecialchars($setting->password);?>" placeholder="Kosongkan jika tidak ingin mengubah">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="toggle_password"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                                <p class="help-block text-muted">Password akun API SISTER.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role" class="col-sm-3 control-label">Role Pengguna</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="role" name="role" value="<?=htmlspecialchars($setting->role ?: 'Sister-WS Consumer Basic');?>" placeholder="Sister-WS Consumer Basic">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="token_lifetime" class="col-sm-3 control-label">Token Lifetime (Detik)</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" id="token_lifetime" name="token_lifetime" value="<?=$setting->token_lifetime ?: 3600;?>">
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="btn_save"><i class="fa fa-save"></i> Simpan Pengaturan</button>
                        <button type="button" class="btn btn-info pull-right" id="btn_test_conn"><i class="fa fa-refresh"></i> Uji Koneksi API SISTER</button>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Petunjuk & Status</h3>
                </div>
                <div class="box-body">
                    <p>Halaman ini digunakan untuk mengonfigurasi koneksi langsung ke Web Service <strong>SISTER Kemdiktisaintek</strong> secara dinamis tanpa perlu mengubah file konfigurasi di server.</p>
                    
                    <div class="callout callout-info" style="margin-bottom: 15px;">
                        <h4>Terakhir Diperbarui:</h4>
                        <p><strong><?=$setting->updated_at ?: '-';?></strong></p>
                    </div>

                    <p><strong>Langkah Uji Koneksi:</strong></p>
                    <ol style="padding-left: 20px;">
                        <li>Pastikan User ID, Username, dan Password terisi sesuai kredensial resmi SISTER PT Anda.</li>
                        <li>Klik <strong>Simpan Pengaturan</strong>.</li>
                        <li>Klik <strong>Uji Koneksi API SISTER</strong> untuk memverifikasi autentikasi token <code>/authorize</code>.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Toggle password view
    $('#toggle_password').click(function() {
        var input = $('#password');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Submit form simpan
    $('#form_pengaturan_api').on('submit', function(e) {
        e.preventDefault();
        var btn = $('#btn_save');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Pengaturan');
                var alertBox = $('#alert_box');
                alertBox.removeClass('alert-danger alert-success').show();
                if (res.status === 'success') {
                    alertBox.addClass('alert-success');
                    $('#alert_msg').html('<strong>Berhasil!</strong> ' + res.message);
                } else {
                    alertBox.addClass('alert-danger');
                    $('#alert_msg').html('<strong>Gagal!</strong> ' + res.message);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Pengaturan');
                $('#alert_box').removeClass('alert-success').addClass('alert-danger').show();
                $('#alert_msg').html('<strong>Error!</strong> Terjadi kesalahan server (' + xhr.status + ').');
            }
        });
    });

    // Tombol Uji Koneksi
    $('#btn_test_conn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menguji koneksi...');
        
        $.ajax({
            url: '<?=base_admin();?>modul/pengaturan_api/pengaturan_api_action.php?act=test',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Uji Koneksi API SISTER');
                var alertBox = $('#alert_box');
                alertBox.removeClass('alert-danger alert-success').show();
                if (res.status === 'success') {
                    alertBox.addClass('alert-success');
                    $('#alert_msg').html('<strong>Koneksi Berhasil!</strong> ' + res.message + '<br><small>Token: ' + res.token_preview + '</small>');
                } else {
                    alertBox.addClass('alert-danger');
                    $('#alert_msg').html('<strong>Koneksi Gagal!</strong> ' + res.message);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Uji Koneksi API SISTER');
                $('#alert_box').removeClass('alert-success').addClass('alert-danger').show();
                $('#alert_msg').html('<strong>Error!</strong> Gagal melakukan request uji koneksi (' + xhr.status + ').');
            }
        });
    });
});
</script>
