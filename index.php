<?php
require_once 'db.php';
$pdo = getDB();

// Ambil data gabungan dari tabel kiroku dan jugyoin
$sql = "SELECT kiroku.*, jugyoin.name, jugyoin.hourly_rate 
        FROM kiroku 
        JOIN jugyoin ON kiroku.jugyoin_id = jugyoin.id 
        ORDER BY kiroku.start_work DESC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>全記録一覧 - 勤怠管理システム</title>
    <style>
        /* TEMA ORANYE SESUAI TUGAS USER 2 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff7e6; /* Oranye sangat muda */
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.2);
            border-top: 8px solid #ff9800; /* Aksen Oranye Tua */
        }
        h1 {
            color: #e65100;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #ff9800;
            color: white;
            padding: 12px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ffe0b2;
            text-align: center;
        }
        tr:hover {
            background-color: #fff3e0;
        }
        .salary {
            font-weight: bold;
            color: #ef6c00;
        }
        .nav-links {
            margin-bottom: 20px;
            text-align: center;
        }
        .nav-links a {
            text-decoration: none;
            color: #ff9800;
            margin: 0 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>全記録一覧 (Attendance List)</h1>

    <div class="nav-links">
        <a href="in.php">出勤入力 (Clock-In)</a> | 
        <a href="out.php">退勤入力 (Clock-Out)</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>従業員名 (Nama)</th>
                <th>出勤時刻 (Start)</th>
                <th>退勤時刻 (End)</th>
                <th>時給 (Rate)</th>
                <th>給料 (Salary)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['start_work'] ?></td>
                <td><?= $row['end_work'] ?: '<span style="color:gray;">勤務中...</span>' ?></td>
                <td>¥<?= number_format($row['hourly_rate']) ?></td>
                <td class="salary">
                    <?php
                    if ($row['end_work']) {
                        $start = strtotime($row['start_work']);
                        $end = strtotime($row['end_work']);
                        // Hitung selisih jam (detik / 3600)
                        $hours = ($end - $start) / 3600;
                        $total_pay = $hours * $row['hourly_rate'];
                        echo "¥" . number_format(floor($total_pay));
                    } else {
                        echo "-";
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
