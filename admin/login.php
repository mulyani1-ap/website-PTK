<?php
session_start();
include "../config/database.php";

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username == 'admin1' && $password == 'admin')
    {
        //buat data pelengkap session secara manual biar web ga crash
        $_SESSION['admin'] = [
            'id' => 1,
            'username' => 'admin1',
            'nama' => 'Administrator'
        ];

        header("Location: dashboard.php");
        exit;
    }
    else 
    {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Admin PTK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{ background:#f5f7fb; display:flex; justify-content:center; align-items:center; height:100vh; }
        .card{ width:400px; border:none; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,.1); }
    </style>
</head>
<body>
    <div class="card p-4">
        <h3 class="text-center mb-4">Login Admin PTK</h3>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button name="login" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>
</body>
</html>