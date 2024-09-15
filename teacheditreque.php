<?php

session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['teacher_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

// Check if service_request_id is set in the URL
if (!isset($_GET['service_request_id'])) {
    $_SESSION['error'] = 'ไม่มีข้อมูลคำขอ';
    header("Location: teachreque.php");
    exit();
}

$requestId = $_GET['service_request_id'];

// Fetch the request details
$sql = "SELECT * FROM service_request sr
        JOIN member m ON sr.member_id_request = m.member_id
        JOIN service_request_type srt ON sr.servicerequest_type_id = srt.servicerequest_type_id
        WHERE service_request_id = :service_request_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':service_request_id', $requestId);
$stmt->execute();
$requestDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$requestDetails) {
    $_SESSION['error'] = 'ไม่พบข้อมูลคำขอ';
    header("Location: teachreque.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $note = $_POST['note'];

    /// Check if status is "ไม่อนุมัติ" (2) and note is empty
    if ($status == '2' && empty($note)) {
        $_SESSION['error'] = 'กรุณาระบุเหตุผลในการปฏิเสธคำขอ';
        header("Location: teacheditreque.php?service_request_id=" . $requestId);
        exit();
    } elseif ($status == '1' && empty($note) && $requestDetails['service_request_location'] == 'online') {
        $_SESSION['error'] = 'กรุณาระบุลิงค์เพื่อให้บริการ';
        header("Location: teacheditreque.php?service_request_id=" . $requestId);
        exit();
    }

    $updateSql = "UPDATE service_request SET service_request_status = :status, note = :note WHERE service_request_id = :service_request_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':status', $status);
    $updateStmt->bindParam(':note', $note);
    $updateStmt->bindParam(':service_request_id', $requestId);
    $updateStmt->execute();

    // Redirect to the list page after successful update
    $_SESSION['success'] = 'อัพเดทข้อมูลเรียบร้อยแล้ว';
    header("Location: teachreque.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- <link rel="stylesheet" href="css/teditre.css"> -->
    <!-- <link rel="stylesheet" href="css/t-editreque.css"> -->
    <link rel="stylesheet" href="css/teac-editre.css">

    <title>Edit Service Request</title>
</head>

<body>

    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-teacher.php");
            ?>
        </nav>
    </header>
    <a href="javascript:void(0)" onclick="w3_open()" class="bi bi-list" id="MenuBtn"></a>

    <!-- ==== All Body Content ==== -->
    <main>
        <!-- logo heard -->
        <div id="logo-head">
            <div class="frame">
                <div class="logo">
                    <img src="images/psylogo.png" alt="">
                </div>
                <div class="namecenter">
                    <h1>ศูนย์ความเป็นเลิศทางจิตวิทยา<br></h1>
                    <h2>PSYCHOLOGY EXCELLENCE CENTER</h2>
                    <h3>คณะศึกษาศาสตร์ มหาวิทยาลัยมหาสารคาม</h3>
                </div>
            </div>
        </div>

        <div id="container">
            <h2>ข้อมูลของผู้ให้บริการ</h2>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
            <div class="info">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?service_request_id=' . $requestId; ?>" method="post">
                    <div class="details-group">
                        <label>ผู้ขอรับบริการ:</label>
                        <?php echo htmlspecialchars($requestDetails['member_name']) . " " . htmlspecialchars($requestDetails['member_lastname']); ?>
                    </div>
                    <div class="details-group">
                        <label>วันที่ :</label>
                        <?php echo htmlspecialchars($requestDetails['service_request_date']) .  "<label>". " เวลา : " . "</label> " . htmlspecialchars($requestDetails['service_request_time']); ?>
                    </div>
                    <div class="details-group">
                        <label>อายุ : </label>
                        <?php echo htmlspecialchars($requestDetails['member_age']) . "  ปี"; ?>
                    </div>
                    <div class="details-group">
                        <label>เพศ :</label>
                        <?php echo htmlspecialchars($requestDetails['member_gender']); ?>
                    </div>
                    <div class="details-group">
                        <label>อีเมล์ :</label>
                        <?php echo htmlspecialchars($requestDetails['member_email']); ?>
                    </div>
                    <div class="details-group">
                        <label>ประวัติการเข้ารับบริการ :</label>
                        <?php echo htmlspecialchars($requestDetails['service_request_history']); ?>
                    </div>
                    <div class="details-group">
                        <label>รายละเอียดเบื้องต้น :</label>
                        <?php echo htmlspecialchars($requestDetails['service_request_Basicdetails']); ?>
                    </div>
                    <div class="details-group">
                        <label>เรื่องที่ต้องการขอรับบริการ :</label>
                        <?php echo htmlspecialchars($requestDetails['servicerequest_type_name']); ?>
                    </div>
                    <div class="details-group">
                        <label>ผู้ให้การปรึกษา :</label>
                        <?php echo htmlspecialchars($requestDetails['service_request_gender']); ?>
                    </div>
                    <div class="details-group">
                        <label>สถานที่ให้การปรึกษา :</label>
                        <?php echo htmlspecialchars($requestDetails['service_request_location']); ?>
                    </div>
                    <div class="details-group">
                        <p>
                            <label for="status">สถานะ:</label>
                            <select id="status" name="status">
                                <option value="1" <?php echo $requestDetails['service_request_status'] == 1 ? 'selected' : ''; ?>>อนุมัติ</option>
                                <option value="2" <?php echo $requestDetails['service_request_status'] == 2 ? 'selected' : ''; ?>>ไม่อนุมัติ</option>
                            </select>
                        </p>
                    </div>
                    <div class="details-group">
                        <p>
                            <label for="note">*** หมายเหตุกรณีไม่อนุมัติคำขอเข้ารับบริการ ***</label><br>
                            <textarea type="text" name="note"><?php echo htmlspecialchars($requestDetails['note']); ?></textarea>
                        </p>
                        <input type="hidden" id="service_request_id" name="service_request_id" value="<?php echo htmlspecialchars($requestId); ?>">
                    </div>
                    <button type="submit">บันทึก</button>
                </form>
            </div>

        </div>

    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>