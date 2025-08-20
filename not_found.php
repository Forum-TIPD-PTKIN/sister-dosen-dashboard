<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="<?=base_url();?>site/assets/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?=base_url();?>site/logo_kerinci.png" sizes="16x16" />
    <title>404 Not Found - <?=getPengaturan('nama_kampus');?></title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #f8fafc; }
        .notfound-container { min-height: 80vh; display: flex; align-items: center; justify-content: center; }
        .notfound-card { max-width: 500px; margin: auto; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); background: #fff; padding: 2.5rem 2rem; text-align: center; }
        .notfound-card img { width: 80px; margin-bottom: 1rem; }
        .notfound-card h1 { font-size: 2.5rem; font-weight: 700; color: #e74c3c; }
        .notfound-card p { font-size: 1.1rem; color: #555; margin-bottom: 2rem; }
        .notfound-card .btn { font-weight: 500; padding: 0.75rem 2rem; }
    </style>
</head>
<body>
    <div class="notfound-container">
        <div class="notfound-card">
            <img src="<?=base_url();?>site/assets/img/404.svg" alt="404 Not Found" onerror="this.style.display='none'">
            <h1>404</h1>
            <p class="lead">Maaf, halaman yang Anda cari tidak ditemukan.<br>Silakan kembali ke halaman utama.</p>
            <a href="<?=base_url();?>" class="btn button-pmb_primary-outline"><i class="fa fa-home me-2"></i>Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>