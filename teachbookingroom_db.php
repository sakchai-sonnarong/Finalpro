<?php 
session_start();
require_once 'config/conndb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomName = $_POST['room'];
    $date = $_POST['date'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $details = $_POST['details'];
    $documentFormId1 = $_POST['document_form_id1'] ?? null; // Use null if not set
    $number1 = $_POST['number1'] ?? null;
    $documentFormId2 = $_POST['document_form_id2'] ?? null;
    $number2 = $_POST['number2'] ?? null;
    $documentFormId3 = $_POST['document_form_id3'] ?? null;
    $number3 = $_POST['number3'] ?? null;

    if (isset($_SESSION['teacher_login'])) {
        $user_id = $_SESSION['teacher_login'];
    }else{
        echo "User not logged in";
        exit;
    }

    // ตรวจสอบว่า room_name นี้อยู่ในตาราง room และดึง room_id
    $room_check_sql = "SELECT room_id FROM room WHERE room_name = ?";
    $room_stmt = $conn->prepare($room_check_sql);
    $room_stmt->execute([$roomName]);

    if ($room_stmt->rowCount() === 0) {
        die('Room name does not exist');
    }

    $room = $room_stmt->fetch(PDO::FETCH_ASSOC);
    $roomId = $room['room_id'];

    // Check if the slot is available
    $check_sql = "SELECT * FROM booking_room WHERE room_id = ? AND booking_date = ? AND booking_time_in = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->execute([$roomId, $date, $startTime]);

    if ($stmt->rowCount() > 0) {
        echo "Slot not available";
        exit;
    }

    // Insert into booking_room
    $sql = "INSERT INTO booking_room (booking_date, booking_time_in, booking_time_out, booking_details, room_id, member_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'จองแล้ว')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$date, $startTime, $endTime, $details, $roomId, $user_id]);

    if ($stmt->rowCount() > 0) {
        // Get the last inserted booking ID
        $bookingId = $conn->lastInsertId();
        
        // Prepare the SQL for inserting document forms
        $doc_sql = "INSERT INTO booking_and_document (booking_id, document_form_id, document_form_number) VALUES (?, ?, ?)";
        $doc_stmt = $conn->prepare($doc_sql);
        
        if ($doc_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
        }
        
        // Insert document forms if they are not empty
        if (!empty($documentFormId1) && !empty($number1)) {
            $doc_stmt->execute([$bookingId, $documentFormId1, $number1]);
        }
        if (!empty($documentFormId2) && !empty($number2)) {
            $doc_stmt->execute([$bookingId, $documentFormId2, $number2]);
        }
        if (!empty($documentFormId3) && !empty($number3)) {
            $doc_stmt->execute([$bookingId, $documentFormId3, $number3]);
        }

        // Prepare the data for JavaScript alert
        $bookingData = [
            'roomName' => $roomName,
            'date' => $date,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'details' => $details,
            'documentFormId1' => $documentFormId1,
            'number1' => $number1,
            'documentFormId2' => $documentFormId2,
            'number2' => $number2,
            'documentFormId3' => $documentFormId3,
            'number3' => $number3
        ];
        $bookingDataJson = json_encode($bookingData);

        echo "<script>
                alert('Booking successful.\\n' +
                    'Room Name: {$roomName}\\n' +
                    'Date: {$date}\\n' +
                    'Start Time: {$startTime}\\n' +
                    'End Time: {$endTime}\\n' +
                    'Details: {$details}');
                window.location.href = 'teachshowbooking.php';
            </script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    $conn = null;
}
?>
