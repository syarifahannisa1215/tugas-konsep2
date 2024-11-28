<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = substr(preg_replace("/[^0-9]/", '', trim($_POST["phone"])), 0, 13);

    // Validasi input
    if (empty($name) || strlen($name) < 3) {
        die("Nama harus diisi dan minimal 3 karakter.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email tidak valid. Harap periksa kembali.");
    }
    if (strlen($phone) < 10 || strlen($phone) > 13) {
        die("Nomor telepon harus terdiri dari 10-13 digit.");
    }

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "tugas_konsep");

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    echo "Koneksi berhasil!<br>";

    // Query menggunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO pendaftaran_kelas (name, email, phone) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $phone);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman lain jika berhasil
        header("Location: index.html");
        exit();
    } else {
        echo "Error saat menyimpan data: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengguna</title>
    <style>
        /* Gaya CSS untuk form */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }
        body {
            background-color: #f3ece4;
            color: #5c4033;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            width: 100%;
            padding: 10px;
            background-color: #8b5a2b;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #a07855;
        }
        form label {
            font-weight: bold;
            color: #8b5a2b;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <label for="name">Nama:</label> 
        <input type="text" name="name" id="name" required>
        <label for="email">Email:</label> 
        <input type="email" name="email" id="email" required>
        <label for="phone">Telepon:</label> 
        <input type="text" name="phone" id="phone" required>
        <button type="submit">Kirim</button>
    </form>
</body>
</html>
