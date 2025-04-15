<?php
header('Content-Type: application/json');

include('Connect.php');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data && isset($data["Volt"])) {
    $volt = $data["Volt"];
    $current = $data["Current"];
    $freq = $data["Freq"];
    $pfaktor = $data["Pfaktor"];
    $activeEnergy = $data["ActiveEnergy"];
    $reactiveEnergy = $data["ReactiveEnergy"];
    $activePower = $data["ActivePower"];
    $apparentPower = $data["ApparentPower"];
    $apparentEnergy = $data["ApparentEnergy"];
    $reactivePower = $data["ReactivePower"];

    $sql = "INSERT INTO datanode1 (
                Volt, Current, Freq, Pfaktor,
                ActiveEnergy, ReactiveEnergy,
                ActivePower, ApparentPower,
                ApparentEnergy, ReactivePower
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "dddddddddd",
        $volt,
        $current,
        $freq,
        $pfaktor,
        $activeEnergy,
        $reactiveEnergy,
        $activePower,
        $apparentPower,
        $apparentEnergy,
        $reactivePower
    );


    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Format JSON tidak valid atau data tidak lengkap"]);
}

$conn->close();
