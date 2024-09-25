<?php
session_start();
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

include 'db.php';

// Ambil data admin
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

// Ambil kategori
$kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");

// Ambil foto terbaru
$foto = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC LIMIT 8");

// Validasi apakah query foto berhasil
if (!$foto) {
    echo "Error: " . mysqli_error($conn);
    exit; // Menghentikan eksekusi jika query gagal
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Atur gaya dasar untuk body dan html */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Atur header agar tetap di bagian atas dan tidak patah saat discroll */
        header {
            background-color: #333;
            padding: 10px 0;
            color: white;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 2; /* Pastikan header di atas sidebar */
        }

        /* Container utama untuk layout */
        .layout {
            display: flex;
            flex: 1;
            margin-top: 60px; /* Memberikan ruang di bawah header */
        }

        /* Gaya untuk sidebar */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            height: calc(100vh - 60px); /* Tinggi penuh dikurangi tinggi header */
            position: fixed; /* Tetap di tempat saat discroll */
            top: 80px; /* Mulai di bawah header */
            left: 0;
            z-index: 1; /* Sidebar di belakang header */
        }

        /* Gaya untuk menu di sidebar */
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0; /* Jarak vertikal antar item */
        }

        .sidebar ul li a {
            display: block; /* Membuat link menempati seluruh lebar item */
            padding: 10px; /* Padding untuk membuat area klik lebih besar */
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #575757; /* Warna latar belakang saat hover */
        }

        /* Gaya untuk konten */
        .content {
            margin-left: 250px; /* Memberikan jarak dari sidebar */
            padding: 20px;
            flex: 1; /* Mengisi sisa ruang */
            background-color: #f9f9f9;
            min-height: calc(100vh - 60px); /* Konten minimal setinggi layar minus header */
        }

        /* Gaya untuk foto grid */
        .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Grid responsif */
            gap: 20px;
        }

        .foto-grid .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .foto-grid .card:hover {
            transform: translateY(-5px); /* Efek hover untuk kartu foto */
        }

        .foto-grid img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Gaya untuk footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php" style="color: white; text-decoration: none;">GALERI FOTO</a></h1>
        </div>
    </header>

    <!-- layout -->
    <div class="layout">
        <!-- sidebar -->
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="data-image.php">Data Foto</a></li>
                <li><a href="Keluar.php">Keluar</a></li>
            </ul>
        </div>

        <!-- content -->
        <div class="content">
            <div class="container">
                <h3 class="mt-4">Foto Terbaru</h3>

                
                <div class="foto-grid">
                    <?php if (mysqli_num_rows($foto) > 0) {
                        while ($p = mysqli_fetch_assoc($foto)) { ?>
                            <div class="card">
                                <a href="detail-image.php?id=<?= $p['image_id'] ?>" class="text-decoration-none">
                                    <img src="foto/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['image_name']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars(substr($p['image_name'], 0, 30)) ?></h5>
                                        <p class="card-text">Nama User: <?= htmlspecialchars($p['admin_name']) ?></p>
                                        <p class="card-text"><?= htmlspecialchars($p['date_created']) ?></p>
                                    </div>
                                </a>
                            </div>
                    <?php }} else { ?>
                        <p>Foto tidak ada</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Galeri Foto.</small>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
