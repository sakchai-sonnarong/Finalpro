<?php
session_start();
require_once 'config/conndb.php';

if (isset($_POST['view'])) {
    $member_id = $_POST['member_id'];
}
// ตรวจสอบว่ามีการส่งข้อมูล member_id มาหรือไม่
if (isset($_POST['member_id'])) {
    $member_id = $_POST['member_id'];
} elseif (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
} else {
    $_SESSION['error'] = 'ไม่พบข้อมูลผู้ใช้งาน';
    header('Location: aduser.php');
    exit();
}

$sql = "SELECT *
        FROM member m 
        JOIN member_type mt ON m.member_type_id = mt.member_type_id
        WHERE m.member_id = :memberid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':memberid', $member_id);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/ad_detailu.css">
    <title>detailuser</title>
</head>

<body>
    <!-- Header Logo -->
    <header>
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
    </header>

    <!--  Container -->
    <main>
        <div class="container">
            <div class="head">
                <a href="aduser.php" class="bi bi-arrow-left" id="BackBtn"></a>
                <h2>ข้อมูลของผู้ใช้งาน</h2>
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php } ?>
                <form action="edituser.php?member_id=<?php echo htmlspecialchars($member['member_id']); ?>" method="post">
                    <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['member_id']); ?>"> <label for="member_name">ชื่อ - นามสกุล</label>
                    <?php echo htmlspecialchars($member['member_name']) . " " . htmlspecialchars($member['member_lastname']); ?>
                    <br>
                    <label for="member_age">อายุ :</label>
                    <?php echo htmlspecialchars($member['member_age']); ?>
                    <br>
                    <label for="member_gender">เพศ :</label>
                    <?php echo htmlspecialchars($member['member_gender']); ?>
                    <br>
                    <label for="member_phone">เบอร์โทร :</label>
                    <?php echo htmlspecialchars($member['member_phone']); ?>
                    <br>
                    <label for="member_email">E-mail :</label>
                    <?php echo htmlspecialchars($member['member_email']); ?>
                    <br>
                    <label for="member_username">ชื่อผู้ใช้งาน :</label>
                    <?php echo htmlspecialchars($member['member_username']); ?>
                    <br>
                    <label for="member_type_name">ประเภทผู้ใช้งาน :</label>
                    <?php echo htmlspecialchars($member['member_type_name']); ?>
                    <button type="submit" name="submit" class="edit-user">แก้ไข</button>
                </form>
                <form action="cruduser_db.php" method="post">
                    <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['member_id']); ?>">
                    <button type="submit" name="delete" class="delete-user">ลบผู้ใช้งาน</button>
                </form>
                <br>

            </div>
        </div>
    </main>


</body>

</html>