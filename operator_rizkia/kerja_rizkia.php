<?php

include '../config_rizkia/koneksi_rizkia.php';
session_start();
include '../config_rizkia/security_rizkia.php';

if(!isset($_SESSION['user_rizkia']) || $_SESSION['user_rizkia']['role_rizkia'] !== 'operator'){
    header("Location: ../auth_rizkia/login_rizkia.php");
    exit;
}

$id_operator_rizkia = (int)$_SESSION['user_rizkia']['id_rizkia'];

if(isset($_POST['selesai_rizkia']) && csrf_validate_rizkia($_POST['csrf_token_rizkia'] ?? '')){
    $id_jadwal = (int)($_POST['id'] ?? 0);

    if($id_jadwal > 0){
        $stmt_update = mysqli_prepare($conn_rizkia, "UPDATE scheduling_rizkia SET status_rizkia='Selesai' WHERE id_rizkia=? AND operator_id_rizkia=?");
        mysqli_stmt_bind_param($stmt_update, "ii", $id_jadwal, $id_operator_rizkia);
        mysqli_stmt_execute($stmt_update);
    }
}

$stmt_data = mysqli_prepare($conn_rizkia, "SELECT id_rizkia, waktu_mulai_rizkia, status_rizkia FROM scheduling_rizkia WHERE operator_id_rizkia=? ORDER BY waktu_mulai_rizkia DESC");
mysqli_stmt_bind_param($stmt_data, "i", $id_operator_rizkia);
mysqli_stmt_execute($stmt_data);
$data_rizkia = mysqli_stmt_get_result($stmt_data);

while($d = mysqli_fetch_assoc($data_rizkia)){
    echo "Jadwal: " . htmlspecialchars($d['waktu_mulai_rizkia']) . " (" . htmlspecialchars($d['status_rizkia']) . ")<br>";

    if($d['status_rizkia'] !== 'Selesai'){
        echo "<form method='POST'>";
        echo csrf_input_rizkia();
        echo "<input type='hidden' name='id' value='" . (int)$d['id_rizkia'] . "'>";
        echo "<button name='selesai_rizkia'>Selesai</button>";
        echo "</form>";
    }
}
?>
