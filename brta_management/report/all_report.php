<?php
// সব রিপোর্ট দেখানোর কোড
echo "<h1>সব রিপোর্ট</h1>";

// এখানে ডাটাবেস থেকে সব রিপোর্ট ফেচ করতে পারেন
// উদাহরণস্বরূপ: 

// ডাটাবেস সংযোগ কোড (MySQL এর উদাহরণ)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brta_database";

// ডাটাবেস সংযোগ স্থাপন
$conn = new mysqli($servername, $username, $password, $dbname);

// সংযোগ চেক করা
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// সব রিপোর্ট নিয়ে আসার SQL কোড
$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

// যদি রিপোর্ট থাকে তবে তা প্রদর্শন করা
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Report ID: " . $row["report_id"]. " - Report Name: " . $row["report_name"]. "<br>";
    }
} else {
    echo "কোনো রিপোর্ট পাওয়া যায়নি";
}

$conn->close();
?>
