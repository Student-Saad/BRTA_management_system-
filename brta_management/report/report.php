<?php
require_once 'config/database.php';

// Simple PDF generation function
function generatePDF($content, $filename) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    // Basic PDF structure
    $pdf_content = "%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj

2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj

3 0 obj
<<
/Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Resources <<
/Font <<
/F1 4 0 R
>>
>>
/Contents 5 0 R
>>
endobj

4 0 obj
<<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
>>
endobj

5 0 obj
<<
/Length " . (strlen($content) + 100) . "
>>
stream
BT
/F1 12 Tf
50 750 Td
($content) Tj
ET
endstream
endobj

xref
0 6
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000284 00000 n 
0000000348 00000 n 
trailer
<<
/Size 6
/Root 1 0 R
>>
startxref
" . (400 + strlen($content)) . "
%%EOF";
    
    echo $pdf_content;
    exit;
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? null;

try {
    $db = new Database();
    $conn = $db->connect();
    
    if ($type === 'vehicle' && $id) {
        $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vehicle) {
            $content = "BRTA VEHICLE REGISTRATION CERTIFICATE\\n\\n";
            $content .= "Registration Number: " . $vehicle['registration_number'] . "\\n";
            $content .= "Vehicle Type: " . ucfirst($vehicle['vehicle_type']) . "\\n";
            $content .= "Brand/Model: " . $vehicle['brand'] . " " . $vehicle['model'] . "\\n";
            $content .= "Year: " . $vehicle['year_of_manufacture'] . "\\n";
            $content .= "Color: " . $vehicle['color'] . "\\n";
            $content .= "Chassis Number: " . $vehicle['chassis_number'] . "\\n";
            $content .= "Engine Number: " . $vehicle['engine_number'] . "\\n";
            $content .= "Owner Name: " . $vehicle['owner_name'] . "\\n";
            $content .= "Owner NID: " . $vehicle['owner_nid'] . "\\n";
            $content .= "Registration Date: " . $vehicle['registration_date'] . "\\n";
            $content .= "Expiry Date: " . $vehicle['expiry_date'] . "\\n";
            $content .= "Status: " . ucfirst($vehicle['status']) . "\\n";
            
            generatePDF($content, "vehicle_" . $vehicle['registration_number'] . ".pdf");
        }
    } elseif ($type === 'driver' && $id) {
        $stmt = $conn->prepare("SELECT * FROM drivers WHERE id = ?");
        $stmt->execute([$id]);
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($driver) {
            $content = "BRTA DRIVING LICENSE CERTIFICATE\\n\\n";
            $content .= "License Number: " . $driver['license_number'] . "\\n";
            $content .= "Full Name: " . $driver['full_name'] . "\\n";
            $content .= "Father's Name: " . $driver['father_name'] . "\\n";
            $content .= "Mother's Name: " . $driver['mother_name'] . "\\n";
            $content .= "NID Number: " . $driver['nid_number'] . "\\n";
            $content .= "Date of Birth: " . $driver['date_of_birth'] . "\\n";
            $content .= "Blood Group: " . $driver['blood_group'] . "\\n";
            $content .= "License Type: " . ucfirst($driver['license_type']) . "\\n";
            $content .= "Vehicle Class: " . $driver['vehicle_class'] . "\\n";
            $content .= "Issue Date: " . $driver['issue_date'] . "\\n";
            $content .= "Expiry Date: " . $driver['expiry_date'] . "\\n";
            $content .= "Status: " . ucfirst($driver['status']) . "\\n";
            
            generatePDF($content, "license_" . $driver['license_number'] . ".pdf");
        }
    } elseif ($type === 'vehicles') {
        $stmt = $conn->query("SELECT * FROM vehicles ORDER BY registration_number");
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = "BRTA VEHICLE RECORDS REPORT\\n\\n";
        $content .= "Generated on: " . date('Y-m-d H:i:s') . "\\n\\n";
        
        foreach ($vehicles as $vehicle) {
            $content .= "Registration: " . $vehicle['registration_number'] . " | ";
            $content .= "Vehicle: " . $vehicle['brand'] . " " . $vehicle['model'] . " | ";
            $content .= "Owner: " . $vehicle['owner_name'] . " | ";
            $content .= "Status: " . ucfirst($vehicle['status']) . "\\n";
        }
        
        generatePDF($content, "brta_vehicles_report.pdf");
    } elseif ($type === 'drivers') {
        $stmt = $conn->query("SELECT * FROM drivers ORDER BY license_number");
        $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = "BRTA DRIVER RECORDS REPORT\\n\\n";
        $content .= "Generated on: " . date('Y-m-d H:i:s') . "\\n\\n";
        
        foreach ($drivers as $driver) {
            $content .= "License: " . $driver['license_number'] . " | ";
            $content .= "Name: " . $driver['full_name'] . " | ";
            $content .= "Type: " . ucfirst($driver['license_type']) . " | ";
            $content .= "Status: " . ucfirst($driver['status']) . "\\n";
        }
        
        generatePDF($content, "brta_drivers_report.pdf");
    }
    
} catch (PDOException $e) {
    echo "Error generating PDF: " . $e->getMessage();
}

// Redirect back if no valid action
header('Location: index.php');
exit;
?>