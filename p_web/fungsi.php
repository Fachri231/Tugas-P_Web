<?php
$db = mysqli_connect('localhost', 'root', '', 'p_web');

function query($query) {
    global $db;

    $rows = [];
    $result = mysqli_query($db, $query);
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data) {
    global $db;

    $nama = htmlspecialchars($data['nama']);
    $alamat = htmlspecialchars($data['alamat']);
    $pekerjaan = htmlspecialchars($data['pekerjaan']);

    $query = "INSERT INTO karyawan 
                VALUES ('', '$nama', '$alamat', '$pekerjaan')";
    
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function edit($data) {
    global $db;

    $id = $data['id'];
    $nama = htmlspecialchars($data['nama']);
    $alamat = htmlspecialchars($data['alamat']);
    $pekerjaan = htmlspecialchars($data['pekerjaan']);

    $query = "UPDATE karyawan 
                SET
                    nama = '$nama',
                    alamat = '$alamat',
                    pekerjaan = '$pekerjaan'
                    WHERE id = $id
                    ";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function hapus($id) {
    global $db;

    mysqli_query($db, "DELETE FROM karyawan WHERE id=$id");

    return mysqli_affected_rows($db);
}

