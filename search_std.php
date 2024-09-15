<?php
require_once 'config/conndb.php';

// รับค่าจากการร้องขอ
$q = $_GET['q'] ?? '';

// ตรวจสอบค่าที่ได้รับ
if ($q) {
    try {
        // เตรียมคำสั่ง SQL เพื่อค้นหานิสิต
        $stmt = $conn->prepare("SELECT member_id, member_name, member_lastname FROM member WHERE member_name LIKE :query AND member_type_id = 1");
        $stmt->execute([':query' => "%$q%"]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // สร้างผลลัพธ์ในรูปแบบที่ Select2 ต้องการ
        $results = array_map(function($student) {
            return [
                'id' => $student['member_id'],
                'text' => $student['member_name'] . ' ' . $student['member_lastname']
            ];
        }, $students);

        // ส่งผลลัพธ์ในรูปแบบ JSON
        echo json_encode($results);
    } catch (PDOException $e) {
        // จัดการข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // กรณีที่ไม่มีค่าค้นหา
    echo json_encode([]);
}
?>
