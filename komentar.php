<?php
error_reporting(E_ALL);
include 'db.php';

// Ambil data admin (user)
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

// Ambil data produk berdasarkan ID dari URL
$produk = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_id = '" . $_GET['id'] . "' ");
$p = mysqli_fetch_object($produk);

// Ambil komentar yang sudah ada untuk gambar ini
$comments = mysqli_query($conn, "SELECT c.comment_text, c.date_created, a.admin_name 
                                  FROM tb_comments c 
                                  JOIN tb_admin a ON c.admin_id = a.admin_id 
                                  WHERE c.image_id = '" . $_GET['id'] . "' 
                                  ORDER BY c.date_created DESC");

// Proses penambahan komentar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $image_id = $_POST['image_id'];
    $admin_id = $_POST['admin_id'];
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment']);

    // Masukkan komentar ke database
    $insert_comment = mysqli_query($conn, "INSERT INTO tb_comments (image_id, admin_id, comment_text) 
                                           VALUES ('$image_id', '$admin_id', '$comment_text')");

    if ($insert_comment) {
        // Update jumlah komentar pada gambar di tabel tb_image
        mysqli_query($conn, "UPDATE tb_image SET comments = comments + 1 WHERE image_id = '$image_id'");

        // Redirect ke halaman yang sama untuk memperbarui tampilan
        header("Location: detail-image.php?id=$image_id");
        exit();
    } else {
        echo "<script>alert('Gagal menambahkan komentar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Gambar</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .comment-box {
            margin-top: 20px;
        }
        .comment {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Detail Gambar</h3>
        <div class="box">
            <div class="image-section">
                <img src="foto/<?php echo $p->image ?>" width="100%" alt="<?php echo $p->image_name ?>" /> 
            </div>
            <div class="details-section">
                <h3><?php echo $p->image_name ?><br />Kategori: <?php echo $p->category_name ?></h3>
                <h4>Nama User: <?php echo $p->admin_name ?><br />
                    Upload Pada Tanggal: <?php echo $p->date_created ?>
                </h4>
                <p>Deskripsi:<br />
                    <?php echo $p->image_description ?>
                </p>

                <!-- Bagian komentar -->
                <div class="comment-box">
                    <h4>Komentar</h4>
                    <!-- Form untuk menambahkan komentar -->
                    <form method="POST" action="">
                        <textarea name="comment" required placeholder="Tulis komentar..."></textarea>
                        <input type="hidden" name="image_id" value="<?php echo $p->image_id; ?>" />
                        <input type="hidden" name="admin_id" value="<?php echo $p->admin_id; ?>" />
                        <button type="submit">Kirim</button>
                    </form>

                    <!-- Menampilkan komentar yang sudah ada -->
                    <?php while ($comment = mysqli_fetch_object($comments)) { ?>
                        <div class="comment">
                            <strong><?php echo $comment->admin_name; ?></strong> <em><?php echo $comment->date_created; ?></em>
                            <p><?php echo $comment->comment_text; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>
