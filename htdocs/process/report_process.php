<?php
header('Content-Type: application/json'); 
include("../includes/db.php");
include("../includes/noti_functions.php");

if (!$conn) {
    echo json_encode(['message' => 'Database connection failed.']);
    http_response_code(500);
    exit();
}

$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if ($data === null || !is_array($data)) {
    echo json_encode(['message' => 'Invalid JSON input or no data received.']);
    http_response_code(400); 
    exit();
}
$postId = $data['postId'] ?? null;
$reasons = $data['reasons'] ?? [];
$note = $data['note'] ?? '';
$reportUserID = $_SESSION['userid'] ?? null; 

if ($reportUserID === null) {
    echo json_encode(['message' => 'User not logged in or session expired.']);
    http_response_code(401); 
    exit();
}

$reasons_for_main_report_field = json_encode($reasons);
$stmt_report = $conn->prepare("INSERT INTO report(reportuserID, postID, Message, status) VALUES (?, ?, ?, 0)");
if ($stmt_report === false) {
    echo json_encode(['message' => 'Failed to prepare report insertion statement: ' . $conn->error]);
    http_response_code(500);
    exit();
}
$stmt_report->bind_param("sis", $reportUserID, $postId, $note);

if ($stmt_report->execute()) {
    $reportID = $conn->insert_id;

    
    if (!empty($reasons)) {
        $sql_report_type = "INSERT INTO report_type(reportID, report_type) VALUES (?, ?)";
        $stmt_report_type = $conn->prepare($sql_report_type);

        if ($stmt_report_type === false) {

            error_log('Failed to prepare report_type insertion statement: ' . $conn->error);
        } else {
            foreach ($reasons as $reason) {
                
                $stmt_report_type->bind_param("is", $reportID, $reason);
                if (!$stmt_report_type->execute()) {
                    error_log('Failed to insert report_type: ' . $stmt_report_type->error);
                    
                }
            }
            $stmt_report_type->close();
        }
    }
    
    echo json_encode([
        'message' => 'Report submitted successfully!',
        'reportId' => $reportID 
    ]);
    http_response_code(201);
    send_noti_to_Admin($reportUserID,"Report",$postId);
} else {
   
    echo json_encode(['message' => 'Failed to insert main report: ' . $stmt_report->error]);
    http_response_code(500); 
}

$stmt_report->close(); 
$conn->close();
?>