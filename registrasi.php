<?php
    include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto - Registrasi</title>
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

        .box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Gaya untuk input dan button */
        .input-control {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .input-control:focus {
            border-color: #666; /* Warna fokus lebih gelap */
            outline: none;
        }

        .btn {
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .btn:hover {
            background-color: #444; /* Warna tombol saat hover */
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
            <h1><a href="dashboard.php" style="color: white; text-decoration: none;"> GALERI FOTO</a></h1>
        </div>
    </header>

    <!-- layout -->
    <div class="layout">
        <!-- sidebar -->
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="galeri.php">Dashboard</a></li>
                <li><a href="registrasi.php">Registrasi</a></li>
                <li><a href="login.php">Login</a></li>
    
            </ul>
        </div>

        <!-- content -->
        <div class="content">
            <div class="container">
                <h3>Registrasi Akun</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="text" name="nama" placeholder="Nama User" class="input-control" required pettern >
                        <input type="text" name="user" placeholder="Username" class="input-control" required>
                        <input type="password" name="pass" placeholder="Password" class="input-control" required>
                        <input type="text" name="tlp" placeholder="Nomor Telepon" class="input-control" required>
                        <input type="email" name="email" placeholder="E-mail" class="input-control" required>
                        <input type="text" name="almt" placeholder="Alamat" class="input-control" required>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>

                    <?php
    include 'db.php';

    if (isset($_POST['submit'])) {
        $nama = ucwords($_POST['nama']); 
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $telpon = $_POST['tlp'];
        $mail = $_POST['email'];
        $alamat = ucwords($_POST['almt']);

        // Pengecekan apakah username sudah ada
        $check_username = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '".$username."'");

        if (mysqli_num_rows($check_username) > 0) {
            // Jika username sudah ada, tampilkan pesan error
            echo '<script>alert("Username sudah terdaftar, silakan gunakan username lain.")</script>';
        } else {
            // Jika username belum ada, lakukan proses insert
            $insert = mysqli_query($conn, "INSERT INTO tb_admin VALUES (
                                null,
                                '".$nama."',
                                '".$username."',
                                '".$password."',
                                '".$telpon."',
                                '".$mail."',
                                '".$alamat."')"
            );

            if ($insert) {
                echo '<script>alert("Registrasi berhasil")</script>';
                echo '<script>window.location="login.php"</script>';
            } else {
                echo 'gagal '.mysqli_error($conn);
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
