<?php
session_start();
$koneksi = new mysqli("localhost", "u637089379_lapordesa", "Lapordesa123", "u637089379_lapordesa");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

file_put_contents("log.txt", json_encode($_POST) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $username = strtolower($_POST['username'] ?? '');

    $password = $_POST['password'] ?? '';

    // Cek admin langsung
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id'] = 0; // Bisa 0 atau nilai unik
        $_SESSION['username'] = 'admin';
        $_SESSION['level'] = 'admin';
        $_SESSION['nama'] = 'Administrator';
        $_SESSION['email'] = 'admin@example.com';

        header("Location: admin/admin.php");
        exit;
    }

    // Cek ke database untuk user biasa
    $query = $koneksi->prepare("SELECT id, username, password, level, nama, email FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (hash('sha256', $password) === $user['password']) {
            // Set sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];

            if ($user['level'] === 'masyarakat') {
                // Kirim data ke sessionStorage via JavaScript
                echo "<script>
                    const userData = {
                        username: " . json_encode($user['username']) . ",
                        password: " . json_encode($user['password']) . ",
                        nama: " . json_encode($user['nama']) . ",
                        email: " . json_encode($user['email']) . ",
                        level: " . json_encode($user['level']) . "
                    };
                    sessionStorage.setItem('userData', JSON.stringify(userData));
                    window.location.href = 'user.php';
                </script>";
                exit;
            } else if ($user['level'] === 'sekdes' || $user['level'] === 'kades') {
    echo "<script>
        const userData = {
            username: " . json_encode($user['username']) . ",
            nama: " . json_encode($user['nama']) . ",
            email: " . json_encode($user['email']) . ",
            level: " . json_encode($user['level']) . "
        };
        sessionStorage.setItem('userData', JSON.stringify(userData));
        window.location.href = 'admin/index.php';
    </script>";
    exit;
}
 else {
                echo "Level user tidak dikenali.";
            }
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.history.back();</script>";
    }
}
?>