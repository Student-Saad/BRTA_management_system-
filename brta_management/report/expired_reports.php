<?php
// মেয়াদোত্তীর্ণ লাইসেন্স দেখানোর কোড
echo "<h1>মেয়াদোত্তীর্ণ লাইসেন্স</h1>";

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

// মেয়াদোত্তীর্ণ লাইসেন্স নিয়ে আসার SQL কোড
$sql = "SELECT * FROM licenses WHERE expiry_date < CURDATE()";
$result = $conn->query($sql);

// যদি মেয়াদোত্তীর্ণ লাইসেন্স থাকে তবে তা প্রদর্শন করা
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "License ID: " . $row["license_id"]. " - License Name: " . $row["license_name"]. " - Expiry Date: " . $row["expiry_date"]. "<br>";
    }
} else {
    echo "কোনো মেয়াদোত্তীর্ণ লাইসেন্স পাওয়া যায়নি";
}

$conn->close();
?>
