<?php
session_start();
include 'db.php';
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

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .btn {
            background-color: #333;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #575757;
        }
    </style>
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php" style="color: white; text-decoration: none;"> GALERI FOTO</a></h1>
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
                <h3>Data Galeri Foto</h3>
                <div class="box">
                    <p>
                        <a href="tambah-image.php" class="btn">Tambah Data</a>
                        <a href="kategori.php" class="btn">Tambah Kategori</a>
                    </p>
                    
                    <table class="table">
                        <thead>
                            <tr>
                               <th width="60px">No</th>
                               <th>Kategori</th>
                               <th>Nama User</th>
                               <th>Nama Foto</th>
                               <th>Deskripsi</th>
                               <th>Gambar</th>
                               <th>Status</th>
                               <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
$no = 1;
$user = $_SESSION['a_global']->admin_id;

// Query untuk mengambil data dari tb_image dengan join ke tb_category
$foto = mysqli_query($conn, "SELECT tb_image.*, tb_category.category_name FROM tb_image 
                              LEFT JOIN tb_category ON tb_image.category_id = tb_category.category_id
                              WHERE tb_image.admin_id = '$user'");

if (mysqli_num_rows($foto) > 0) {
    while ($row = mysqli_fetch_array($foto)) {
        ?>
        <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo isset($row['category_name']) ? $row['category_name'] : 'Tidak ada kategori'; ?></td>
            <td><?php echo isset($row['admin_name']) ? $row['admin_name'] : 'Tidak ada nama user'; ?></td>
            <td><?php echo isset($row['image_name']) ? $row['image_name'] : 'Tidak ada nama foto'; ?></td>
            <td><?php echo isset($row['image_description']) ? $row['image_description'] : 'Tidak ada deskripsi'; ?></td>
            <td>
                <a href="foto/<?php echo $row['image'] ?>" target="_blank">
                    <img src="foto/<?php echo $row['image'] ?>" width="50px">
                </a>
            </td>
            <td><?php echo ($row['image_status'] == 0) ? 'Tidak Aktif' : 'Aktif'; ?></td>
            <td>
                <a href="edit-image.php?id=<?php echo $row['image_id'] ?>" class="btn">Edit</a> 
                <a href="proses-hapus.php?idp=<?php echo $row['image_id'] ?>" onclick="return confirm('Yakin Ingin Hapus ?')" class="btn">Hapus</a>
            </td>
        </tr>
        <?php 
    }
} else {
    ?>
    <tr>
        <td colspan="8">Tidak ada data</td>
    </tr>
    <?php 
}
?>

                        </tbody>
                    </table>
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
