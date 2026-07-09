<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:database_mlbb.sqlite');
$db->exec("CREATE TABLE IF NOT EXISTS riwayat (id INTEGER PRIMARY KEY AUTOINCREMENT, nama TEXT, tanggal TEXT, kategori TEXT, benar INTEGER, salah INTEGER, rataWaktu TEXT, totalWaktu TEXT)");

$action = $_GET['action'] ?? '';

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("INSERT INTO riwayat (nama, tanggal, kategori, benar, salah, rataWaktu, totalWaktu) VALUES (:n, :t, :k, :b, :s, :rw, :tw)");
    $stmt->execute([':n' => $data['nama'], ':t' => $data['tanggal'], ':k' => $data['kategori'], ':b' => $data['benar'], ':s' => $data['salah'], ':rw' => $data['rataWaktu'], ':tw' => $data['totalWaktu']]);
    echo json_encode(['status' => 'success']);
} elseif ($action === 'get') {
    echo json_encode($db->query("SELECT * FROM riwayat ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC));
} elseif ($action === 'clear') {
    $db->exec("DELETE FROM riwayat");
    echo json_encode(['status' => 'success']);
}
?>