<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Web Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            background: #a9a9a9; /* Warna abu-abu tua */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .box-login {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 400px; /* Memperbesar lebar form */
            text-align: center;
        }

        .box-login h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 28px; /* Ukuran teks judul diperbesar */
        }

        .input-control {
            width: 100%;
            padding: 15px; /* Memperbesar padding pada input */
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 18px; /* Memperbesar ukuran font input */
            transition: border-color 0.3s;
        }

        .input-control:focus {
            border-color: #666; /* Warna fokus lebih gelap */
            outline: none;
        }

        .btn {
            background-color: #666; /* Warna tombol abu-abu */
            color: white;
            padding: 15px; /* Memperbesar padding tombol */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 18px; /* Memperbesar ukuran font tombol */
        }

        .btn:hover {
            background-color: #444; /* Warna tombol saat hover */
        }

        p {
            margin-top: 20px;
            color: #555;
            font-size: 16px; /* Memperbesar ukuran teks di bawah */
        }

        a {
            color: #666; /* Warna tautan */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="box-login">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="Username" class="input-control" required>
            <input type="password" name="pass" placeholder="Password" class="input-control" required>
            <input type="submit" name="submit" value="Login" class="btn">
        </form>
        <?php
        if (isset($_POST['submit'])) {
            session_start();
            include 'db.php';

            $user = mysqli_real_escape_string($conn, $_POST['user']);
            $pass = mysqli_real_escape_string($conn, $_POST['pass']);

            $cek = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '".$user."' AND password = '".$pass."'");
            if (mysqli_num_rows($cek) > 0) {
                $d = mysqli_fetch_object($cek);
                $_SESSION['status_login'] = true;
                $_SESSION['a_global'] = $d;
                $_SESSION['id'] = $d->admin_id;
                echo '<script>window.location="dashboard.php"</script>';
            } else {
                echo '<script>alert("Username atau password anda salah")</script>';
            }
        }
        ?><br />
        <p>Belum punya akun? daftar <a href="registrasi.php">DISINI</a></p>
        <p>atau klik <a href="index.php">Kembali</a></p>
    </div>
</body>
</html>
