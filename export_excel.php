<?php
session_start();

include 'config/db.php';

$filename = "Laporan_SPK_BEM.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");

echo '

<html>
<head>
<meta charset="UTF-8">

<style>

body{
    font-family: Times New Roman, serif;
    font-size:18px;
}

table{
    border-collapse:collapse;
    margin-bottom:15px;
}

th{
    border:1px solid #000;
    padding:5px;
    text-align:center;
    font-weight:bold;
    background:#D9E2F3;
    vertical-align:top;
}

td{
    border:1px solid #000;
    padding:4px;
    vertical-align:top;
}

.judul{
    font-size:14px;
    font-weight:bold;
    text-align:center;
    border:none;
}

.sub{
    border:none;
    text-align:center;
}

.section{
    background:#B4C7E7;
    font-weight:bold;
    padding:6px;
}

.center{
    text-align:center;
}

.bold{
    font-weight:bold;
}

.none{
    border:none;
}

.wrap{
    white-space:normal;
    line-height:1.4;
    vertical-align:top;
}

.kandidat{
    background:#EDEDED;
    text-align:center;
    font-weight:bold;
    vertical-align:middle;
}

</style>

</head>

<body>

';


/* =====================================================
JUDUL
===================================================== */

echo '

<table width="1200">

<tr>
<td colspan="8" class="judul">
SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN KETUA BEM
</td>
</tr>

<tr>
<td colspan="8" class="sub">
Periode '.date('Y').' | Dicetak : '.date('d-m-Y H:i:s').'
</td>
</tr>

</table>

';


/* =====================================================
A. KRITERIA
===================================================== */

echo '

<table width="450">

<tr>
<td colspan="3" class="section">
A. KRITERIA DAN BOBOT
</td>
</tr>

<tr>
<th width="35">No</th>
<th width="280">Kriteria</th>
<th width="120">Bobot</th>
</tr>

';

$q = mysqli_query($conn,"SELECT * FROM kriteria");

$no = 1;

while($d=mysqli_fetch_assoc($q)){

echo '

<tr>
<td class="center">'.$no++.'</td>
<td>'.$d['nama'].'</td>
<td class="center">'.($d['bobot']*100).'%</td>
</tr>

';

}

echo '</table>';

echo '

<tr>
<td><br></td>
</tr>

';


echo '</table>';


/* =====================================================
B. DATA KANDIDAT
===================================================== */

echo '

<table width="1350">

<tr>
<td colspan="8" class="section">
B. DATA KANDIDAT
</td>
</tr>

<tr>

<th width="35">No</th>
<th width="170">Nama</th>
<th width="55">IPK</th>
<th width="430">Visi Misi</th>
<th width="160">Kepemimpinan</th>
<th width="160">Pengalaman</th>
<th width="160">Komunikasi</th>
<th width="160">Integritas</th>

</tr>

';

$q = mysqli_query($conn,"
SELECT *
FROM kandidat
ORDER BY nomor_urut
");

while($k=mysqli_fetch_assoc($q)){

echo '

<tr>

<td class="center">
'.$k['nomor_urut'].'
</td>

<td class="bold">
'.$k['nama'].'
</td>

<td class="center">
'.$k['ipk'].'
</td>

<td class="wrap">
'.$k['visi_misi'].'
</td>

<td class="wrap">
'.$k['kepemimpinan'].'
</td>

<td class="wrap">
'.$k['pengalaman'].'
</td>

<td class="wrap">
'.$k['komunikasi'].'
</td>

<td class="wrap">
'.$k['integritas'].'
</td>

</tr>

';

}

echo '</table>';

echo '

<tr>
<td><br></td>
</tr>

';


/* =====================================================
C. HASIL VOTING
===================================================== */

echo '

<table width="650">

<tr>
<td colspan="4" class="section">
C. HASIL VOTING
</td>
</tr>

<tr>
<th width="35">No</th>
<th width="280">Nama Kandidat</th>
<th width="140">Jumlah</th>
<th width="140">Persentase</th>
</tr>

';

$total = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(DISTINCT id_mahasiswa) total
FROM penilaian
")
)['total'];

$q = mysqli_query($conn,"
SELECT *
FROM kandidat
ORDER BY nomor_urut
");

while($k=mysqli_fetch_assoc($q)){

$jml = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(DISTINCT id_mahasiswa) total
FROM penilaian
WHERE id_kandidat='$k[id]'
")
)['total'];

$persen = $total > 0
? round(($jml/$total)*100,2)
: 0;

echo '

<tr>

<td class="center">
'.$k['nomor_urut'].'
</td>

<td class="bold">
'.$k['nama'].'
</td>

<td class="center">
'.$jml.'
</td>

<td class="center">
'.$persen.'%
</td>

</tr>

';

}

echo '</table>';

echo '

<tr>
<td><br></td>
</tr>

';


/* =====================================================
D. DATA PEMILIH
===================================================== */

echo '

<table width="700">

<tr>
<td colspan="3" class="section">
D. DATA MAHASISWA YANG SUDAH MEMILIH
</td>
</tr>

';

$kandidat = mysqli_query($conn,"
SELECT *
FROM kandidat
ORDER BY nomor_urut
");

while($k=mysqli_fetch_assoc($kandidat)){

echo '

<tr>
<td colspan="3" align="center" class="kandidat">
Kandidat '.$k['nomor_urut'].' - '.$k['nama'].'
</td>
</tr>

<tr>
<th width="35">No</th>
<th width="150">NIM</th>
<th width="400">Nama Mahasiswa</th>
</tr>

';

$pemilih = mysqli_query($conn,"
SELECT DISTINCT u.nim,u.nama
FROM penilaian p
JOIN users u ON p.id_mahasiswa=u.id
WHERE p.id_kandidat='$k[id]'
");

$no = 1;

if(mysqli_num_rows($pemilih)>0){

while($p=mysqli_fetch_assoc($pemilih)){

echo '

<tr>

<td class="center">
'.$no++.'
</td>

<td class="center">
'.$p['nim'].'
</td>

<td>
'.$p['nama'].'
</td>

</tr>

';

}

}else{

echo '

<tr>
<td colspan="3" class="center">
Belum ada pemilih
</td>
</tr>

';

}

echo '

<tr>
<td colspan="3" class="center bold">
Total Pemilih : '.($no-1).'
</td>
</tr>

<tr>
<td colspan="3" class="none"></td>
</tr>

';

}

echo '</table>';

echo '

<tr>
<td><br></td>
</tr>

';


/* =====================================================
E. HASIL AHP
===================================================== */

echo '

<table width="650">

<tr>
<td colspan="4" class="section">
E. HASIL AKHIR AHP
</td>
</tr>

<tr>
<th width="35">No</th>
<th width="280">Nama Kandidat</th>
<th width="140">Skor</th>
<th width="160">Keterangan</th>
</tr>

';

$q = mysqli_query($conn,"
SELECT *
FROM kandidat
ORDER BY nomor_urut
");

while($k=mysqli_fetch_assoc($q)){

echo '

<tr>

<td class="center">
'.$k['nomor_urut'].'
</td>

<td class="bold">
'.$k['nama'].'
</td>

<td class="center">
0
</td>

<td class="center">
Baik
</td>

</tr>

';

}

echo '</table>';

echo '

<tr>
<td><br></td>
</tr>

';


/* =====================================================
F. PARTISIPASI
===================================================== */

$total_mhs = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='mahasiswa'
")
)['total'];

$sudah = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='mahasiswa'
AND has_voted=1
")
)['total'];

$belum = $total_mhs - $sudah;

echo '

<table width="550">

<tr>
<td colspan="3" class="section">
F. PARTISIPASI MAHASISWA
</td>
</tr>

<tr>
<th width="280">Keterangan</th>
<th width="120">Jumlah</th>
<th width="120">Persentase</th>
</tr>

<tr>
<td>Total Mahasiswa</td>
<td class="center">'.$total_mhs.'</td>
<td class="center">100%</td>
</tr>

<tr>
<td>Sudah Memilih</td>
<td class="center">'.$sudah.'</td>
<td class="center">'.round(($sudah/$total_mhs)*100,2).'%</td>
</tr>

<tr>
<td>Belum Memilih</td>
<td class="center">'.$belum.'</td>
<td class="center">'.round(($belum/$total_mhs)*100,2).'%</td>
</tr>

</table>

';

?>