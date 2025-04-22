<?php 
require_once(__DIR__ . '/../includes/init.php');
$user_role = get_role();
$id_user = $_SESSION["user_id"];

if($user_role == 'user') {
    $errors = array();
    $sukses = false;
    $ada_error = false;
    $result = '';

    if(isset($_POST['submit'])):
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];

        if(!$nama) {
            $errors[] = 'Nama tidak boleh kosong';
        }        

        if(!$email) {
            $errors[] = 'Email tidak boleh kosong';
        }

        if(!$id_user) {
            $errors[] = 'Id User salah';
        }

        if($password && ($password != $password2)) {
            $errors[] = 'Password harus sama keduanya';
        }

        if(empty($errors)):
            $update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama', email = '$email' WHERE id_user = '$id_user'");

            if($password) {
                $pass = sha1($password);
                $update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama', password = '$pass', email = '$email' WHERE id_user = '$id_user'");
            }        
            if($update) {
                $sukses = true;
                $errors[] = 'Data berhasil diupdate';
            } else {
                $errors[] = 'Data gagal diupdate';
            }
        endif;

    endif;
    
    $data = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'");
    $cek = mysqli_num_rows($data);
    if($cek > 0) {
        $d = mysqli_fetch_array($data);
    } else {
        $errors[] = 'Data tidak ada';
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        /* Full height background image with transparency */
        body {
            background-image: url('../assets/gambar/makanan2.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow-y: auto; /* Enable vertical scroll */
            padding: 40px 0; /* Jarak atas dan bawah */
        }

        /* Overlay to make background transparent */
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
            backdrop-filter: blur(6px);
        }

        /* Centered profile edit card */
        .profile-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 25px;
            max-width: 400px;
            width: 100%;
            text-align: left;
            box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.3);
            z-index: 1;
            font-family: Arial, sans-serif;
            position: relative;
        }

        /* Title styling */
        .profile-card h1 {
            font-size: 20px;
            color: #0D4C3A;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Button Icon Kembali */
        .btn-back {
            position: absolute;
            top: 15px;
            left: 15px;
            color: #B68D6C;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-back:hover {
            color: #A07A5E;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            color: #333;
            background-color: #FAF3EF;
        }

        /* Button styles */
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            width: 48%;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: #FFF;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-update {
            background-color: #0D4C3A;
        }

        .btn-update:hover {
            background-color: #0A3D2E;
        }

        .btn-cancel {
            background-color: #B68D6C;
        }

        .btn-cancel:hover {
            background-color: #A07A5E;
        }

        /* Alert Box */
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #ffffff;
            background-color: #0D4C3A;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="background-overlay"></div> <!-- Transparent overlay for background -->

<div class="profile-card">
    <!-- Icon Kembali ke Dashboard -->
    <a href="dashboard-user.php" class="btn-back"><i class="fas fa-arrow-left"></i></a>

    <h1>Edit Profile</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="alert">
            <?php foreach ($errors as $error): ?>
                <?php echo $error; ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" readonly value="<?php echo $d['username']; ?>" class="form-control"/>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter new password" class="form-control"/>
        </div>

        <div class="form-group">
            <label>Ulangi Password</label>
            <input type="password" name="password2" placeholder="Repeat new password" class="form-control"/>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" required value="<?php echo $d['nama']; ?>" class="form-control"/>
        </div>

        <div class="form-group">
            <label>E-Mail</label>
            <input type="email" name="email" required value="<?php echo $d['email']; ?>" class="form-control"/>
        </div>

        <div class="btn-group">
            <button name="submit" value="submit" type="submit" class="btn btn-update">Update</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='dashboard-user.php'">Batal</button>
        </div>
    </form>
</div>

</body>
</html>

<?php
} else {
    header('Location: ../public/login.php');
}
?>
