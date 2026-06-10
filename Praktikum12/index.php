<?php
include_once "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ini PHP</title>
</head>

<body>
    <h1>HPH</h1>

    <fieldset>
        <form action="proses.php" method="POST">
            <legend>Isian Data</legend>
            <input type="text" name="fname" placeholder="first name">
            <input type="email" name="email" placeholder="email">
            <input type="submit" name="btnsubmit" value="Simpan">
        </form>
    </fieldset>

    <br>
    
    <?php 
        // Mengambil keyword dari URL (jika ada)
        $keyword = isset($_GET["keyword"]) ? trim($_GET["keyword"]) : "";
    ?>
    
    <form action="index.php" method="get">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
        <input type="submit" name="btnCari" value="Cari">
    </form>
    <br>
    
    <?php
    
    $msg = isset($_GET['msg']) ? trim($_GET['msg']) : "";
    if ($msg != "") {
        echo "<span>" . htmlspecialchars($msg) . "</span><br><br>";
    }

    
    try {
        if ($keyword != '') {

            $sql = "SELECT id, firstname, email FROM user WHERE firstname LIKE :keyword";
            $stmt = $conn->prepare($sql);
            
           
            $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
            
        } else {
            
            $sql = "SELECT id, firstname, email FROM user";
            $stmt = $conn->prepare($sql);
        }
        
       
        $stmt->execute();

        
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'><tr><th>ID</th><th>Firstname</th><th>Email</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['firstname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Data tidak ditemukan.";
        }
    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
    }
    

    $conn = null;
    ?>

</body>

</html>