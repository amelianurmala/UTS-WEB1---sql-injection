<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "latihan_sql");

$pesan = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // QUERY YANG VULNERABLE (sengaja tidak aman)
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["pesan"] = "Login BERHASIL! Selamat datang, " . $username;
        $_SESSION["status"] = "berhasil";
    } else {
        $_SESSION["pesan"] = "Login GAGAL! Username atau password salah.";
        $_SESSION["status"] = "gagal";
    }
    $_SESSION["last_username"] = $username;
    $_SESSION["last_password"] = $password;
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}

$last_username = $_SESSION["last_username"] ?? "";
$last_password = $_SESSION["last_password"] ?? "";
if (isset($_SESSION["last_username"])) unset($_SESSION["last_username"]);
if (isset($_SESSION["last_password"])) unset($_SESSION["last_password"]);

if (isset($_SESSION["pesan"])) {
    $pesan = $_SESSION["pesan"];
    $status = $_SESSION["status"];
    unset($_SESSION["pesan"]);
    unset($_SESSION["status"]);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Versi Rentan (SQL Injection Demo)</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #1a1a2e;
            transition: border-color 0.2s;
            outline: none;
        }

        input:focus {
            border-color: #e74c3c;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 6px;
        }

        button:hover { background: #c0392b; }

        .alert {
            margin-top: 18px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert.berhasil {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.gagal {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }


    </style>
</head>
<body>
    <div class="container">
       
        <h2>Halaman Login</h2>
        

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" value="<?= htmlspecialchars($last_username) ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" value="<?= htmlspecialchars($last_password) ?>" required>
            </div>
            <button type="submit">Masuk</button>
        </form>

        <?php if ($pesan): ?>
            <div class="alert <?= $status ?>">
                <?= $status === 'berhasil' ? '✅' : '❌' ?> <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>


    </div>
</body>
</html>