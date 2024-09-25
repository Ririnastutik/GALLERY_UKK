<?php
session_start();
include 'db.php'; // Pastikan ini terkoneksi dengan benar

// Cek apakah user sudah login
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $category_name = $_POST['category_name'];

    // Cek apakah nama kategori sudah diisi
    if (!empty($category_name)) {
        // Query insert ke tabel tb_category
        $insert = mysqli_query($conn, "INSERT INTO tb_category (category_name) VALUES ('$category_name')");

        // Cek apakah insert berhasil
        if ($insert) {
            echo '<script>alert("Kategori berhasil ditambahkan")</script>';
            echo '<script>window.location="kategori.php"</script>'; // Redirect setelah berhasil
        } else {
            echo 'Gagal: ' . mysqli_error($conn);
        }
    } else {
        echo '<script>alert("Nama kategori tidak boleh kosong!")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* Atur gaya dasar untuk body dan html */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Container utama untuk layout */
        .layout {
            display: flex;
            flex: 1; /* Mengisi ruang yang tersedia */
        }

        /* Gaya untuk sidebar */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            height: 100vh; /* Tinggi penuh */
            position: fixed; /* Agar tetap di tempat */
        }

        /* Gaya untuk menu */
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
        }

        /* Gaya untuk footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%; /* Pastikan lebar penuh */
        }

        header {
            background-color: #333;
            padding: 10px 0;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">GALERI FOTO</a></h1>
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
                <li><a href="kategori.php">Kategori</a></li>
                <li><a href="Keluar.php">Keluar</a></li>
            </ul>
        </div>

        <!-- content -->
        <div class="content">
            <div class="container">
                <h3>Tambah Kategori</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="text" name="category_name" class="input-control" placeholder="Nama Kategori" required>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>
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
</body>
</html>
