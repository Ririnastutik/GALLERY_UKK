<?php
session_start();
include 'db.php'; // Pastikan db.php terkoneksi dengan benar ke database

// Cek apakah user sudah login
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
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
                <li><a href="Keluar.php">Keluar</a></li>
            </ul>
        </div>

        <!-- content -->
        <div class="content">
            <div class="container">
                <h3>Tambah Data Foto</h3>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="adminid" value="<?php echo $_SESSION['a_global']->admin_id ?>">
                        <input type="text" name="namaadmin" class="input-control" value="<?php echo $_SESSION['a_global']->admin_name ?>" readonly="readonly">
                        <input type="text" name="nama" class="input-control" placeholder="Nama Foto" required>
                        <textarea class="input-control" name="deskripsi" placeholder="Deskripsi"></textarea><br />

                        <!-- Dropdown kategori -->
                        <select class="input-control" name="category" required>
                            <option value="">--Pilih Kategori--</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM tb_category");
                            while ($r = mysqli_fetch_array($kategori)) {
                                echo '<option value="'.$r['category_id'].'">'.$r['category_name'].'</option>';
                            }
                            ?>
                        </select>

                        <input type="file" name="gambar" class="input-control" required>
                        <select class="input-control" name="status" required>
                            <option value="">--Pilih--</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {
                        // Ambil data dari form
                        $ida       = $_POST['adminid'];
                        $user      = $_POST['namaadmin'];
                        $nama      = $_POST['nama'];
                        $deskripsi = $_POST['deskripsi'];
                        $status    = $_POST['status'];
                        $kategori  = $_POST['category'];

                        // Validasi input kategori
                        if (!isset($kategori) || $kategori == "") {
                            echo '<script>alert("Silakan pilih kategori")</script>';
                        } else {
                            // Cek apakah kategori ada di database
                            $check_category = mysqli_query($conn, "SELECT * FROM tb_category WHERE category_id='$kategori'");
                            if (mysqli_num_rows($check_category) == 0) {
                                echo '<script>alert("Kategori tidak ditemukan")</script>';
                            } else {
                                // Proses upload gambar
                                $filename = $_FILES['gambar']['name'];
                                $tmp_name = $_FILES['gambar']['tmp_name'];
                                $type1 = explode('.', $filename);
                                $type2 = strtolower(end($type1));

                                $newname = 'foto' . time() . '.' . $type2;
                                $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                                // Cek format file
                                if (!in_array($type2, $tipe_diizinkan)) {
                                    echo '<script>alert("Format file tidak diizinkan")</script>';
                                } else {
                                    // Pindahkan file ke folder foto
                                    if (move_uploaded_file($tmp_name, './foto/' . $newname)) {
                                        // Query insert ke tabel tb_image
                                        $query = "INSERT INTO tb_image (category_id, admin_id, admin_name, image_name, image_description, image, image_status, date_created) 
                                        VALUES ('$kategori', '$ida', '$user', '$nama', '$deskripsi', '$newname', '$status', CURRENT_TIMESTAMP)";

                                        $insert = mysqli_query($conn, $query);

                                        // Cek apakah insert berhasil
                                        if ($insert) {
                                            echo '<script>alert("Tambah Foto berhasil")</script>';
                                            echo '<script>window.location="data-image.php"</script>';
                                        } else {
                                            echo 'Gagal: ' . mysqli_error($conn);
                                        }
                                    } else {
                                        echo '<script>alert("Gagal meng-upload gambar")</script>';
                                    }
                                }
                            }
                        }
                    }
                    ?>
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
