<?php
header("Content-Type: application/json; charset=UTF-8");

include_once 'db.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "SELECT * FROM MEMBER";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();

    if($num > 0) {
        $data = array();
        $data["records"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $item = array(
                "ID" => $ID,
                "FullName" => $FullName,
                "Email" => $Email,
                "DateOfBirth" => $DateOfBirth,
                "Gender" => $Gender,
                "JoiningDate" => $JoiningDate,
                "MobileNumber" => $MobileNumber,
                "EmergencyNumber" => $EmergencyNumber,
                "Photo" => $Photo,
                "Profession" => $Profession,
                "Nationality" => $Nationality
            );
            array_push($data["records"], $item);
        }

        http_response_code(200);
        echo json_encode($data);
        die;
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No records found."));
        die;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $query = "INSERT INTO MEMBER (FullName, Email, Password, DateOfBirth, Gender, JoiningDate, MobileNumber, EmergencyNumber, Photo, Profession, Nationality) VALUES (:FullName, :Email, :Password, :DateOfBirth, :Gender, :JoiningDate, :MobileNumber, :EmergencyNumber, :Photo, :Profession, :Nationality)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':FullName', $input['FullName']);
    $stmt->bindParam(':Email', $input['Email']);
    $stmt->bindParam(':Password', password_hash($input['Password'], PASSWORD_BCRYPT)); // Encrypt the password
    $stmt->bindParam(':DateOfBirth', $input['DateOfBirth']);
    $stmt->bindParam(':Gender', $input['Gender']);
    $stmt->bindParam(':JoiningDate', $input['JoiningDate']);
    $stmt->bindParam(':MobileNumber', $input['MobileNumber']);
    $stmt->bindParam(':EmergencyNumber', $input['EmergencyNumber']);
    $stmt->bindParam(':Photo', $input['Photo']);
    $stmt->bindParam(':Profession', $input['Profession']);
    $stmt->bindParam(':Nationality', $input['Nationality']);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Record created."));
        die;
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create record."));
        die;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    $query = "UPDATE MEMBER SET FullName = :FullName, Email = :Email, DateOfBirth = :DateOfBirth, Gender = :Gender, JoiningDate = :JoiningDate, MobileNumber = :MobileNumber, EmergencyNumber = :EmergencyNumber, Photo = :Photo, Profession = :Profession, Nationality = :Nationality WHERE ID = :ID";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':FullName', $input['FullName']);
    $stmt->bindParam(':Email', $input['Email']);
    $stmt->bindParam(':DateOfBirth', $input['DateOfBirth']);
    $stmt->bindParam(':Gender', $input['Gender']);
    $stmt->bindParam(':JoiningDate', $input['JoiningDate']);
    $stmt->bindParam(':MobileNumber', $input['MobileNumber']);
    $stmt->bindParam(':EmergencyNumber', $input['EmergencyNumber']);
    $stmt->bindParam(':Photo', $input['Photo']);
    $stmt->bindParam(':Profession', $input['Profession']);
    $stmt->bindParam(':Nationality', $input['Nationality']);
    $stmt->bindParam(':ID', $input['ID']);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Record updated."));
        die;
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update record."));
        die;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);

    $query = "DELETE FROM MEMBER WHERE ID = :ID";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':ID', $input['ID']);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Record deleted."));
        die;
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete record."));
        die;
    }
}
?>
