<?php
include 'db.php';

// Ambil data admin
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

// Ambil kategori
$kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");

// Ambil foto terbaru
$foto = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Full height layout dengan flexbox */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Main container flex */
        .container-fluid {
            flex: 1; /* Mengisi ruang antara header dan footer */
        }

        /* Styling Sidebar agar tetap di posisi fixed dan memenuhi tinggi halaman */
        .nav-container {
            height: 100vh; /* Memenuhi tinggi viewport dari atas sampai bawah */
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed; /* Sidebar tetap di tempat meskipun discroll */
            top: 0;
            left: 0;
            width: 16.66667%; /* Mengatur lebar sidebar agar tetap sesuai grid Bootstrap (col-md-2) */
            z-index: 1000; /* Pastikan sidebar tetap di atas konten lain */
            overflow-y: auto; /* Memungkinkan scroll jika konten sidebar melebihi tinggi viewport */
        }

        .nav-link {
            color: white;
            margin-bottom: 10px;
        }

        .nav-link:hover {
            background-color: #495057;
            color: white;
            border-radius: 4px;
        }

        /* Menambahkan padding pada konten utama agar tidak tertutup sidebar */
        .col-md-10 {
            margin-left: 16.66667%; /* Menyesuaikan margin konten sesuai dengan lebar sidebar (col-md-2) */
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Styling Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%; /* Penuhi lebar penuh */
            position: relative; /* Posisi relative agar tetap di bawah */
            bottom: 0;
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigasi Vertikal di Samping Kiri -->
            <div class="col-md-2 nav-container">
                <h2><a href="index.php" class="text-white text-decoration-none">GALERI FOTO</a></h2>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="galeri.php">Beranda</a>
                    <a class="nav-link text-white" href="registrasi.php">Registrasi</a>
                    <a class="nav-link text-white" href="login.php">Login</a>
                </nav>
            </div>

            <!-- Konten Utama -->
            <div class="col-md-10">
                <!-- Search -->
                <div class="search py-3 bg-light">
                    <div class="container">
                        <form action="galeri.php" class="form-inline">
                            <input type="text" name="search" class="form-control mr-2" placeholder="Cari Foto">
                            <button type="submit" name="cari" class="btn btn-primary">Cari Foto</button>
                        </form>
                    </div>
                </div>

                <!-- Foto Terbaru -->
                <section class="py-5">
                    <div class="container">
                        <h3>Foto Terbaru</h3>
                        <div class="row">
                            <?php if (mysqli_num_rows($foto) > 0) {
                                while ($p = mysqli_fetch_assoc($foto)) { ?>
                                    <div class="col-md-3 mb-4">
                                        <a href="detail-image.php?id=<?= $p['image_id'] ?>" class="text-decoration-none">
                                            <div class="card">
                                                <img src="foto/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['image_name']) ?>">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= htmlspecialchars(substr($p['image_name'], 0, 30)) ?></h5>
                                                    <p class="card-text">Nama User: <?= htmlspecialchars($p['admin_name']) ?></p>
                                                    <p class="card-text"><?= htmlspecialchars($p['date_created']) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                            <?php }} else { ?>
                                <p>Foto tidak ada</p>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center">
        <div class="container-fluid">
            <small>&copy; 2024 Web Galeri Foto</small>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
