<?php
include '../config_rizkia/koneksi_rizkia.php';
session_start();

/* ROLE CHECK ADMIN */
if(!isset($_SESSION['user_rizkia']['role_rizkia']) || $_SESSION['user_rizkia']['role_rizkia'] != 'admin'){
    die("Akses ditolak. Hanya Admin.");
}

/* TAMBAH */
if(isset($_POST['tambah_rizkia'])){
    $nama = mysqli_real_escape_string($conn_rizkia, $_POST['nama_rizkia']);
    $jumlah = mysqli_real_escape_string($conn_rizkia, $_POST['jumlah_rizkia']);
    $deadline = mysqli_real_escape_string($conn_rizkia, $_POST['deadline_rizkia']);

    mysqli_query($conn_rizkia,"INSERT INTO jobs_rizkia 
    (nama_job_rizkia, jumlah_rizkia, deadline_rizkia, status_rizkia) 
    VALUES('$nama','$jumlah','$deadline','Menunggu')");
}

/* UPDATE */
if(isset($_POST['update_rizkia'])){
    $id = mysqli_real_escape_string($conn_rizkia, $_POST['id']);
    $nama = mysqli_real_escape_string($conn_rizkia, $_POST['nama_rizkia']);
    $jumlah = mysqli_real_escape_string($conn_rizkia, $_POST['jumlah_rizkia']);
    $deadline = mysqli_real_escape_string($conn_rizkia, $_POST['deadline_rizkia']);

    mysqli_query($conn_rizkia,"UPDATE jobs_rizkia SET
    nama_job_rizkia='$nama',
    jumlah_rizkia='$jumlah',
    deadline_rizkia='$deadline'
    WHERE id_rizkia='$id'");
}

/* HAPUS */
if(isset($_POST['hapus_rizkia'])){
    $id = mysqli_real_escape_string($conn_rizkia, $_POST['id']);
    mysqli_query($conn_rizkia,"DELETE FROM jobs_rizkia WHERE id_rizkia='$id'");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Jobs - MachinaFlow</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#eef2f7;
    color:#2c3e50;
}

/* HEADER */
.header{
    height:80px;
    margin-left:220px;
    background:linear-gradient(135deg,#1f2d3a,#2c3e50);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:bold;
    letter-spacing:1px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
}

/* SIDEBAR */
.sidebar{
    width:220px;
    height:100vh;
    background:linear-gradient(180deg,#2c3e50,#34495e);
    position:fixed;
    top:0;
    left:0;
    display:flex;
    flex-direction:column;
    box-shadow:4px 0 15px rgba(0,0,0,0.08);
}

.sidebar .logo{
    padding:24px 20px;
    font-size:22px;
    font-weight:bold;
    color:white;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,0.08);
    letter-spacing:1px;
}

.menu{
    flex:1;
    padding:18px 12px;
    overflow-y:auto;
}

.sidebar a{
    display:block;
    color:#ecf0f1;
    text-decoration:none;
    padding:12px 14px;
    margin-bottom:8px;
    border-radius:10px;
    transition:0.3s;
    font-size:14px;
    font-weight:bold;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.08);
    transform:translateX(4px);
}

.sidebar a.active{
    background:rgba(255,255,255,0.12);
}

/* LOGOUT */
.logout{
    margin:12px;
    background:#e74c3c !important;
    text-align:center;
    border-radius:10px;
}

.logout:hover{
    background:#c0392b !important;
    transform:none !important;
}

/* CONTENT */
.content{
    margin-left:220px;
    padding:25px;
}

/* CARD */
.card{
    background:white;
    border-radius:16px;
    padding:22px;
    margin-bottom:22px;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
    animation:fadeIn 0.5s ease;
}

.card h3{
    margin-bottom:18px;
    font-size:20px;
    color:#2c3e50;
}

/* FORM */
label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:bold;
    color:#2c3e50;
}

input{
    width:100%;
    padding:11px 12px;
    margin-bottom:14px;
    border:1px solid #dcdde1;
    border-radius:10px;
    outline:none;
    transition:0.3s;
    background:#fbfcfe;
    font-size:14px;
}

input:focus{
    border-color:#3498db;
    box-shadow:0 0 8px rgba(52,152,219,0.15);
    background:white;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    border-radius:12px;
    overflow:hidden;
}

th{
    background:#2c3e50;
    color:white;
    padding:14px;
    font-size:14px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ecf0f1;
    font-size:14px;
}

tr:hover{
    background:#f8fafc;
}

/* BUTTON */
button{
    padding:8px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:bold;
    transition:0.3s;
}

.btn-edit{
    background:#3498db;
    color:white;
}
.btn-edit:hover{
    background:#2980b9;
}

.btn-hapus{
    background:#e74c3c;
    color:white;
}
.btn-hapus:hover{
    background:#c0392b;
}

.btn-update{
    background:#2c3e50;
    color:white;
}
.btn-update:hover{
    background:#1f2d3a;
}

.btn-batal{
    background:#7f8c8d;
    color:white;
}
.btn-batal:hover{
    background:#636e72;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-box{
    background:white;
    width:380px;
    padding:25px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    animation:fadeInScale 0.3s ease;
}

.modal-box h3{
    margin-bottom:16px;
    color:#2c3e50;
}

/* ANIMATION */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(12px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes fadeInScale{
    from{
        opacity:0;
        transform:scale(0.95);
    }
    to{
        opacity:1;
        transform:scale(1);
    }
}
</style>
</head>

<body>

<div class="sidebar">
    <div class="logo">MachinaFlow</div>

    <div class="menu">
        <a href="dashboard_rizkia.php">Dashboard</a>
        <a href="jobs_rizkia.php" class="active">Jobs</a>
        <a href="scheduling_rizkia.php">Scheduling</a>
        <a href="mesin_rizkia.php">Mesin</a>
        <a href="users_rizkia.php">Users</a>
        <a href="laporan_rizkia.php">Laporan</a>
        <a href="monitoring_rizkia.php">Monitoring</a>
    </div>

    <a class="logout" href="../auth_rizkia/logout_rizkia.php">Logout</a>
</div>

<div class="header">Jobs Management</div>

<div class="content">

<div class="card">
<h3>Tambah Job</h3>

<form method="POST">
    <input type="text" name="nama_rizkia" placeholder="Nama Job" required>
    <input type="number" name="jumlah_rizkia" placeholder="Jumlah" required>
    <label>Deadline</label>
    <input type="date" name="deadline_rizkia" required>
    <button class="btn-update" name="tambah_rizkia">Tambah</button>
</form>
</div>

<div class="card">
<h3>Data Jobs</h3>

<table>
<tr>
<th>Nama</th>
<th>Jumlah</th>
<th>Deadline</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn_rizkia,"SELECT * FROM jobs_rizkia");
while($d=mysqli_fetch_array($data)){
?>
<tr>
<td><?= $d['nama_job_rizkia'] ?></td>
<td><?= $d['jumlah_rizkia'] ?></td>
<td><?= $d['deadline_rizkia'] ?></td>
<td><?= $d['status_rizkia'] ?></td>
<td>
    <button class="btn-edit" onclick="openEdit(
    '<?= $d['id_rizkia'] ?>',
    '<?= $d['nama_job_rizkia'] ?>',
    '<?= $d['jumlah_rizkia'] ?>',
    '<?= $d['deadline_rizkia'] ?>'
    )">Edit</button>

    <form method="POST" style="display:inline">
        <input type="hidden" name="id" value="<?= $d['id_rizkia'] ?>">
        <button class="btn-hapus" name="hapus_rizkia">Hapus</button>
    </form>
</td>
</tr>
<?php } ?>

</table>
</div>

</div>

<div id="modal" class="modal">
<div class="modal-box">

<h3>Edit Job</h3>

<form method="POST">
    <input type="hidden" name="id" id="id">
    <input type="text" name="nama_rizkia" id="nama" required>
    <input type="number" name="jumlah_rizkia" id="jumlah" required>
    <input type="date" name="deadline_rizkia" id="deadline" required>

    <button class="btn-update" name="update_rizkia">Update</button>
    <button type="button" class="btn-batal" onclick="closeModal()">Batal</button>
</form>

</div>
</div>

<script>
function openEdit(id,nama,jumlah,deadline){
    document.getElementById('modal').style.display='flex';
    document.getElementById('id').value=id;
    document.getElementById('nama').value=nama;
    document.getElementById('jumlah').value=jumlah;
    document.getElementById('deadline').value=deadline;
}

function closeModal(){
    document.getElementById('modal').style.display='none';
}
</script>

</body>
</html>