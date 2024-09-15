<?php

session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

if (isset($_POST['edit'])) {
    $document_form_id = $_POST['document_form_id'];

    // ดึงข้อมูลห้องจากฐานข้อมูล
    $sql = $conn->prepare("SELECT * FROM document_form WHERE document_form_id = :document_form_id");
    $sql->bindParam(':document_form_id', $document_form_id);
    $sql->execute();
    $document = $sql->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/edocum-ad.css">

    <title>แก้ไขข้อมูลแบบฟอร์ม</title>
</head>

<body>
    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-admin.php");
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
            <h2>แก้ไขข้อมูลแบบฟอร์ม</h2>
            <div class="detail">
                <form action="cruddocument_db.php" method="POST">
                    <input type="hidden" name="document_form_id" value="<?php echo htmlspecialchars($document['document_form_id']); ?>">

                    <label for="document_form_data">ชื่อแบบฟอร์ม :</label>
                    <input type="text" id="document_form_data" name="document_form_data" value="<?php echo htmlspecialchars($document['document_form_data']); ?>" required><br><br>
            </div>
            <button type="submit" name="edit">บันทึก</button>
            </form>
        </div>
    </main>
    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>