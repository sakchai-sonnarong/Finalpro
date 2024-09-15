<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['teacher_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
    exit();
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

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="css/tsave.css"> -->
    <!-- <link rel="stylesheet" href="css/teachsave.css"> -->
    <link rel="stylesheet" href="css/tinfos.css">

    <title>Teacher Save Information</title>
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
            <h2>บันทึกข้อมูลการให้การปรึกษา</h2>

            <form action="teachsave_db.php" method="post">
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
                <div class="details">
                    <div class="details-group">
                        <label for="name">ชื่อ-นามสกุล :</label>
                        <?php
                        if (isset($_SESSION['teacher_login'])) {
                            $teacher_login = $_SESSION['teacher_login'];
                            $stmt = $conn->prepare("SELECT sr.service_request_id, m.member_id, m.member_name, m.member_lastname
                    FROM service_request sr
                    JOIN member m ON sr.member_id_request = m.member_id
                    WHERE sr.member_id_student = :student_id
                    AND (sr.service_request_data IS NULL OR sr.service_request_data = '')
                    AND (sr.service_value_data IS NULL OR sr.service_value_data = '')");
                            $stmt->bindParam(':student_id', $teacher_login);
                            $stmt->execute();
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        ?>
                        <select name="service_request_id" id="service_request_id">
                            <option selected disabled value="">เลือกผู้รับบริการ</option>
                            <?php if (isset($rows)) {
                                foreach ($rows as $row) { ?>
                                    <option value="<?php echo htmlspecialchars($row['service_request_id']); ?>">
                                        <?php echo htmlspecialchars("(" . $row['service_request_id'] . ") " . $row['member_name'] . " " . $row['member_lastname']); ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="details-group">
                        <label for="dataconsult">ข้อมูลการให้การปรึกษา :</label>
                        <textarea type="text" class="form-control" name="dataconsult" aria-describedby="dataconsult"></textarea>
                    </div>
                    <div class="details-group">
                        <label for="valueconsult">ผลการให้การปรึกษา :</label>
                        <textarea type="text" class="form-control" name="valueconsult" aria-describedby="valueconsult"></textarea>
                    </div>
                </div>
                <button type="submit" name="submit">บันทึก</button>
            </form>
            

        </div>

    </main>

    <script>
        function setRequestId(selectElement) {
            var requestId = selectElement.options[selectElement.selectedIndex].getAttribute('data-request-id');
            document.getElementById('service_request_id').value = requestId;
        }
    </script>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>