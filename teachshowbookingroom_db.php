<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['teacher_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
}

if (isset($_POST['search'])) {
    $dateroom = $_POST['date'];
    $room = $_POST['selectroom'];

     // ตรวจสอบว่ามีการเลือกห้องหรือไม่
    if (empty($room)) {
        $_SESSION['error'] = 'กรุณาเลือกห้อง';
        header('Location: teachshowbooking.php');
        exit;
    }

    if (!empty($room)) {
        $statusroom = $conn->prepare("SELECT br.*, r.room_name, r.room_status, u.member_name, u.member_lastname
            FROM booking_room br 
            JOIN room r ON br.room_id = r.room_id 
            JOIN member u ON br.member_id = u.member_id 
            WHERE br.booking_date = ? AND r.room_name = ?");
        $statusroom->execute([$dateroom, $room]);
    } else {
        $statusroom = $conn->prepare("SELECT br.*, r.room_name, r.room_status, u.member_name, u.member_lastname
            FROM booking_room br 
            JOIN room r ON br.room_id = r.room_id 
            JOIN member u ON br.member_id = u.member_id 
            WHERE br.booking_date = ?");
        $statusroom->execute([$dateroom]);
    }
} else {
    $statusroom = $conn->prepare("SELECT br.*, r.room_name, r.room_status, u.member_name, u.member_lastname
        FROM booking_room br 
        JOIN room r ON br.room_id = r.room_id 
        JOIN member u ON br.member_id = u.member_id 
        WHERE br.booking_date = ?");
    $statusroom->execute([$dateroom]);
}

$result = $statusroom->fetchAll(PDO::FETCH_ASSOC);

// ดึงสถานะของห้อง
$selectedRoomQuery = $conn->prepare("SELECT room_status FROM room WHERE room_name = :room_name");
$selectedRoomQuery->bindParam(':room_name', $room);
$selectedRoomQuery->execute();
$roomStatusResult = $selectedRoomQuery->fetch(PDO::FETCH_ASSOC);
$roomStatus = $roomStatusResult['room_status'] ?? 1; // ถ้าไม่พบห้อง ให้ถือว่าพร้อมใช้งาน (ค่าเริ่มต้นคือ 1)

// สร้างช่วงเวลา 1 ชั่วโมง
$timeSlots = [];
$start = strtotime("08:00"); // เวลาที่เริ่มต้น
$end = strtotime("19:00"); // เวลาที่สิ้นสุด

for ($time = $start; $time < $end; $time += 3600) {
    $timeSlots[date("H:i", $time)] = "ว่าง"; // กำหนดสถานะเริ่มต้นเป็น "ว่าง"
}


// อัพเดตสถานะตามข้อมูลการจอง
// $roomStatus = 1;
foreach ($result as $row) {

    if ($row["room_status"] == 0) {
        $roomStatus = 0;
    }

    $bookingStart = $row["booking_time_in"];
    $bookingEnd = $row["booking_time_out"];

    // แปลงเวลาจองเป็น timestamps
    $startBooking = strtotime($bookingStart);
    $endBooking = strtotime($bookingEnd);

    // อัพเดตสถานะในช่วงเวลาที่จอง
    for ($time = $startBooking; $time < $endBooking; $time += 3600) {
        $timeSlot = date("H:i", $time);
        if (isset($timeSlots[$timeSlot])) {
            $timeSlots[$timeSlot] = "จองแล้ว"; // อัพเดตสถานะตามการจอง
        }
    }
}
header('Location: teachshowbooking.php?result=' . urlencode(json_encode($result)) . '&timeSlots=' . urlencode(json_encode($timeSlots)) . '&room=' . urlencode($room) . '&date=' . urlencode($dateroom) . '&roomStatus=' . urlencode($roomStatus));
exit;
