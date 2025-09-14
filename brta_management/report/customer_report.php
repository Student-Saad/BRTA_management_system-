<?php
// কাস্টম রিপোর্ট দেখানোর কোড
echo "<h1>কাস্টম রিপোর্ট</h1>";

// ইউজারের ইনপুট থেকে রিপোর্ট ফিল্টার করা
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    
    echo "<p>তারিখ: $start_date থেকে $end_date পর্যন্ত রিপোর্ট দেখাচ্ছে</p>";

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

    // কাস্টম রিপোর্ট নিয়ে আসার SQL কোড
    $sql = "SELECT * FROM reports WHERE report_date BETWEEN '$start_date' AND '$end_date'";
    $result = $conn->query($sql);

    // যদি কাস্টম রিপোর্ট থাকে তবে তা প্রদর্শন করা
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Report ID: " . $row["report_id"]. " - Report Name: " . $row["report_name"]. " - Report Date: " . $row["report_date"]. "<br>";
        }
    } else {
        echo "কাস্টম রিপোর্ট পাওয়া যায়নি";
    }

    $conn->close();
} else {
    echo "<form method='get'>
            <label for='start_date'>শুরু তারিখ:</label>
            <input type='date' id='start_date' name='start_date' required>
            <label for='end_date'>শেষ তারিখ:</label>
            <input type='date' id='end_date' name='end_date' required>
            <input type='submit' value='রিপোর্ট দেখান'>
          </form>";
}
?>
