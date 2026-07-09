<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Nama file database dibedakan untuk Bahasa Inggris
$db_file = 'database_inggris.sqlite';
$db = new PDO('sqlite:' . $db_file);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Pembuatan tabel dengan kolom `kategori`
$query = "CREATE TABLE IF NOT EXISTS riwayat (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama TEXT,
    tanggal TEXT,
    kategori TEXT,
    benar INTEGER,
    salah INTEGER,
    rataWaktu TEXT,
    totalWaktu TEXT
)";
$db->exec($query);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    $data = json_decode($inputJSON, TRUE);

    if ($data) {
        $stmt = $db->prepare("INSERT INTO riwayat (nama, tanggal, kategori, benar, salah, rataWaktu, totalWaktu) VALUES (:nama, :tanggal, :kategori, :benar, :salah, :rataWaktu, :totalWaktu)");
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':tanggal', $data['tanggal']);
        $stmt->bindParam(':kategori', $data['kategori']);
        $stmt->bindParam(':benar', $data['benar']);
        $stmt->bindParam(':salah', $data['salah']);
        $stmt->bindParam(':rataWaktu', $data['rataWaktu']);
        $stmt->bindParam(':totalWaktu', $data['totalWaktu']);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
    }
} elseif ($action === 'get') {
    $stmt = $db->query("SELECT * FROM riwayat ORDER BY id DESC");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} elseif ($action === 'clear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->exec("DELETE FROM riwayat");
    echo json_encode(['status' => 'success', 'message' => 'Database dibersihkan']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali']);
}
?>