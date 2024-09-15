<?php
session_start();
require_once 'config/conndb.php';
if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
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

    <link rel="stylesheet" href="css/user.css">

    <title>User Page</title>

</head>

<body>
    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-user.php");
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

        <div id="hello">
            <div class="welcome">
                <h1>Hello<span style='font-size:50px;'>&#128075;</span></h1>
                <h2>Welcome to Psychology Excellence Center</h2>
                <h2>Faculty of Education, Mahasarakham University</h2>
            </div>

            <div class="open">
                <p>เปิดให้บริการวันจันทร์ - วันศุกร์ เวลา 09:00-12:00 น. และ 13:00-16:00 น.</p>
            </div>
        </div>

        <div id="news">
            <i class="bi bi-megaphone"></i>
            <span class="text">ข่าวสาร/สื่อที่น่าสนใจ</span>
        </div>

        <div id="boxes">
            <div class="box1">
                <a href="https://www.facebook.com/PSYCHOLOGYEXCELLENCECENTER.MSU/photos/a.368343809919305/4768785629875079/?_rdc=1&_rdr">
                    <img src="images/sleep.jpg">
                    <p>เคล็ดลับง่าย ๆ เพื่อการนอนที่<br>ถูกหลักอนามัย</p>
                </a>
            </div>
            <div class="box2">
                <a href="https://www.facebook.com/PSYCHOLOGYEXCELLENCECENTER.MSU/photos/a.368343809919305/4815219838564991/?_rdc=1&_rdr">
                    <img src="images/toxic.jpg">
                    <p>รู้จัก Toxic People อย่าให้คนเป็นพิษทำร้ายชีวิตคุณ</p>
                </a>
            </div>
            <div class="box3">
                <a href="https://www.facebook.com/PSYCHOLOGYEXCELLENCECENTER.MSU/photos/a.368343809919305/4829331630487145/?_rdc=1&_rdr">
                    <img src="images/gad.jpg">
                    <p>GAD (Generalized Anxiety Disorder) หรือภาวะวิตกกังวลทั่วไป!</p>
                </a>
            </div>
            <div class="box4">
                <a href="https://pec.msu.ac.th/2022/02/01/%e0%b8%81%e0%b8%b2%e0%b8%a3%e0%b8%94%e0%b8%b9%e0%b9%81%e0%b8%a5%e0%b8%95%e0%b8%99%e0%b9%80%e0%b8%ad%e0%b8%87-self-care-%e0%b9%81%e0%b8%a5%e0%b8%b0%e0%b9%81%e0%b8%99%e0%b8%a7%e0%b8%97%e0%b8%b2/">
                    <img src="images/self.jpg">
                    <p>การดูแลตนเอง (SELF-CARE)และแนวทางเบื้องต้นในการสร้างสุขภาวะที่ดี </p>
                </a>
            </div>
            <div class="box5">
                <a href="https://pec.msu.ac.th/2023/03/01/%e0%b8%8a%e0%b8%b5%e0%b8%a7%e0%b8%b4%e0%b8%95%e0%b8%97%e0%b8%b5%e0%b9%88%e0%b8%94%e0%b8%b5-the-good-life/">
                    <img src="images/smile.jpg">
                    <p>ชีวิตที่ดี The Good Life</p>
                </a>
            </div>
            <div class="box6">
                <a href="https://pec.msu.ac.th/2022/12/01/%e0%b8%a7%e0%b8%b4%e0%b8%98%e0%b8%b5%e0%b8%88%e0%b8%b1%e0%b8%94%e0%b8%81%e0%b8%b2%e0%b8%a3%e0%b8%84%e0%b8%a7%e0%b8%b2%e0%b8%a1%e0%b8%81%e0%b8%b1%e0%b8%87%e0%b8%a7%e0%b8%a5/">
                    <img src="images/managing.jpg">
                    <p>20 กลวิธีในการจัดการความวิตกกังวล</p>
                </a>
            </div>
            <div class="box7">
                <a href="https://pec.msu.ac.th/2022/03/09/%e0%b8%a3%e0%b8%b1%e0%b8%9a%e0%b8%a1%e0%b8%b7%e0%b8%ad%e0%b8%ad%e0%b8%a2%e0%b9%88%e0%b8%b2%e0%b8%87%e0%b9%84%e0%b8%a3%e0%b9%80%e0%b8%a1%e0%b8%b7%e0%b9%88%e0%b8%ad%e0%b9%80%e0%b8%a3%e0%b8%b2%e0%b8%96/">
                    <img src="images/body.jpg">
                    <p>รับมืออย่างไรเมื่อเราถูก Body Shaming</p>
                </a>
            </div>
            <div class="box8">
                <a href="https://www.facebook.com/PSYCHOLOGYEXCELLENCECENTER.MSU/photos/a.368343809919305/4847920118628296/?_rdc=1&_rdr">
                    <img src="images/pet.jpg">
                    <p>สัตว์เลี้ยงบำบัด (pet therapy)</p>
                </a>
            </div>
            <div class="box9">
                <a href="https://www.camri.go.th/th/home/infographic/infographic-973">
                    <img src="images/happy.jpg">
                    <p>ความสุข สร้างได้ด้วย 9 วิธี สร้างสุขให้ตนเอง</p>
                </a>
            </div>
            <div class="box10">
                <a href="https://www.camri.go.th/th/home/infographic/infographic-1229">
                    <img src="images/heart.png">
                    <p>4 องค์ประกอบ สร้างความเข้มแข็งทางใจ</p>
                </a>
            </div>
        </div>

        <div id="footer">
            <div class="footer-conten">
                <div class="contact">
                    <h5>Contact</h5>
                    <li><a href="https://www.facebook.com/PSYCHOLOGYEXCELLENCECENTER.MSU">
                            <i class="bi bi-facebook">
                                <p> : ศูนย์ความเป็นเลิศทางจิตวิทยา <br>คณะศึกษาศาสตร์ มมส</p>
                            </i>
                        </a></li>
                    <li><a href="https://line.me/R/ti/p/%40056oqvxn">
                            <i class="bi bi-line">
                                <p> : PEC-MSU</p>
                            </i>
                        </a></li>
                    <li><a href="#">
                            <i class="bi bi-telephone-fill">
                                <p> : 043-754321-40 ต่อ 6261 </p>
                            </i>
                        </a></li>
                    <li><a href="#">
                            <i class="bi bi-envelope-at-fill">
                                <p> : pec@msu.ac.th</p>
                            </i>
                        </a></li>
                    <li><a href="https://maps.app.goo.gl/byeh7hbL7d3jpq3s6">
                            <i class="bi bi-geo-alt-fill">
                                <p> : ชั้น 6 อาคารวิทยพัฒนา คณะศึกษาศาสตร์ มหาวิทยาลัยมหาสารคาม (วิทยาเขตในเมือง) <br>
                                    ถนนนครสวรรค์ ตำบลตลาด อำเภอเมืองมหาสารคาม จังหวัดมหาสารคาม 44000</p>
                            </i>
                        </a></li>
                </div>

                <div class="web-msu">
                    <h5>หน่วยงานภายใน</h5>
                    <li><a href="https://www.msu.ac.th/">
                            <p>มหาวิทยาลัยมหาสารคาม</p>
                        </a></li>
                    <li><a href="https://www.msu.ac.th/%E0%B8%84%E0%B8%93%E0%B8%B0-%E0%B8%A7%E0%B8%97%E0%B8%A2%E0%B8%B2%E0%B8%A5%E0%B8%A2/%E0%B8%84%E0%B8%93%E0%B8%B0%E0%B8%A8%E0%B8%B6%E0%B8%81%E0%B8%A9%E0%B8%B2%E0%B8%A8%E0%B8%B2%E0%B8%AA%E0%B8%95%E0%B8%A3%E0%B9%8C/">
                            <p>คณะศึกษาศาสตร์ มหาวิทยาลัยมหาสารคาม</p>
                        </a></li>
                    <li><a href="https://acad.msu.ac.th/th/">
                            <p>กองบริการการศึกษา</p>
                        </a></li>
                    <li><a href="https://sa.msu.ac.th/">
                            <p>กองกิจการนิสิต</p>
                        </a></li>
                </div>
            </div>

        </div>

    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>

</body>

</html>