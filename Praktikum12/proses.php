<?php
include_once "koneksi.php";
$firstname = FILTER_INPUT(INPUT_GET, "fname");
$email = FILTER_INPUT(INPUT_GET, "email");
$btnSubmit = FILTER_INPUT(INPUT_GET, "btnsubmit");

if ($btnSubmit) {
    try {
        $sql = "INSERT INTO user (firstname, email) VALUES (:fname, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'fname' => $firstname,
            'email' => $email
        ]);
        $msg = "New record created successfully";
    } catch (PDOException $e) {
        $msg = $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    header("location:index.php?msg=" . $msg);
    exit;
}
?>