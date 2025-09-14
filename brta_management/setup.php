<?php
require_once 'config/database.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>BRTA Setup</title>
    <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
</head>
<body class='bg-gray-100'>
    <div class='min-h-screen flex items-center justify-center'>
        <div class='bg-white p-8 rounded-lg shadow-md max-w-md w-full'>
            <h1 class='text-2xl font-bold text-center mb-6'>BRTA Database Setup</h1>";

try {
    // Create database connection
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS brta_management");
    echo "<div class='text-green-600 mb-2'>✓ Database created successfully</div>";
    
    // Use the database
    $pdo->exec("USE brta_management");
    
    // Read and execute schema
    $schema = file_get_contents('database/schema.sql');
    $pdo->exec($schema);
    echo "<div class='text-green-600 mb-2'>✓ Database tables created successfully</div>";
    
    echo "<div class='mt-6 text-center'>
            <a href='index.php' class='bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600'>
                Go to BRTA System
            </a>
          </div>";
    
} catch (PDOException $e) {
    echo "<div class='text-red-600 mb-2'>✗ Error: " . $e->getMessage() . "</div>";
    echo "<div class='mt-4 text-sm text-gray-600'>
            <p>Please ensure:</p>
            <ul class='list-disc list-inside mt-2'>
                <li>MySQL is running</li>
                <li>Database credentials are correct in config/database.php</li>
                <li>You have permission to create databases</li>
            </ul>
          </div>";
}

echo "    </div>
    </div>
</body>
</html>";
?>