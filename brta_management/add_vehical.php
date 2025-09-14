<?php
// add_vehicle.php

include 'includes/header.php';
require_once 'config/database.php';

// Show all errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize messages
$message = '';
$error = '';

// Handle form submission
if (isset($_POST['submit'])) {
    try {
        $db = new Database();
        $conn = $db->connect();

        // Ensure PDO throws exceptions
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement
        $sql = "INSERT INTO vehicles (
                    registration_number, chassis_number, engine_number, vehicle_type,
                    brand, model, year_of_manufacture, color,
                    owner_name, owner_nid, owner_address, owner_phone,
                    registration_date, expiry_date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Date values
        $registration_date = date('Y-m-d');
        $expiry_date = date('Y-m-d', strtotime('+1 year'));

        // Execute query
        $stmt->execute([
            $_POST['registration_number'] ?? '',
            $_POST['chassis_number'] ?? '',
            $_POST['engine_number'] ?? '',
            $_POST['vehicle_type'] ?? '',
            $_POST['brand'] ?? '',
            $_POST['model'] ?? '',
            $_POST['year_of_manufacture'] ?? '',
            $_POST['color'] ?? '',
            $_POST['owner_name'] ?? '',
            $_POST['owner_nid'] ?? '',
            $_POST['owner_address'] ?? '',
            $_POST['owner_phone'] ?? '',
            $registration_date,
            $expiry_date
        ]);

        if ($stmt->rowCount() > 0) {
            $message = "✅ Vehicle registered successfully!";
        } else {
            $error = "❌ Failed to register vehicle!";
        }

    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}
?>

<!-- HTML Form UI -->
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-car text-4xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Vehicle Registration</h1>
            <p class="text-gray-600 mt-2">Register a new vehicle in the BRTA database</p>
        </div>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <!-- Row 1 -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label>Registration Number *</label>
                    <input type="text" name="registration_number" required placeholder="e.g., DHA-123456"
                           class="input-field">
                </div>
                <div>
                    <label>Vehicle Type *</label>
                    <select name="vehicle_type" required class="input-field">
                        <option value="">Select Vehicle Type</option>
                        <option value="car">Car</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="truck">Truck</option>
                        <option value="bus">Bus</option>
                        <option value="microbus">Microbus</option>
                        <option value="pickup">Pickup</option>
                    </select>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label>Chassis Number *</label>
                    <input type="text" name="chassis_number" required class="input-field">
                </div>
                <div>
                    <label>Engine Number *</label>
                    <input type="text" name="engine_number" required class="input-field">
                </div>
            </div>

            <!-- Row 3 -->
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label>Brand *</label>
                    <input type="text" name="brand" required placeholder="e.g., Toyota" class="input-field">
                </div>
                <div>
                    <label>Model *</label>
                    <input type="text" name="model" required placeholder="e.g., Corolla" class="input-field">
                </div>
                <div>
                    <label>Year of Manufacture *</label>
                    <input type="number" name="year_of_manufacture" min="1980" max="2025" required class="input-field">
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label>Color *</label>
                    <input type="text" name="color" required placeholder="e.g., White" class="input-field">
                </div>
                <div>
                    <label>Owner NID Number *</label>
                    <input type="text" name="owner_nid" required placeholder="17 digit NID number" class="input-field">
                </div>
            </div>

            <!-- Row 5 -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label>Owner Name *</label>
                    <input type="text" name="owner_name" required class="input-field">
                </div>
                <div>
                    <label>Owner Phone *</label>
                    <input type="tel" name="owner_phone" required placeholder="01XXXXXXXXX" class="input-field">
                </div>
            </div>

            <!-- Row 6 -->
            <div>
                <label>Owner Address *</label>
                <textarea name="owner_address" rows="3" required placeholder="Full address"
                          class="input-field"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6">
                <button type="submit" name="submit" value="1"
                        class="btn-primary">
                    <i class="fas fa-save"></i> <span>Register Vehicle</span>
                </button>
                <a href="index.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> <span>Back to Home</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Add CSS classes in your layout or CSS file -->
<style>
    .input-field {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        transition: border 0.2s;
    }
    .input-field:focus {
        border-color: #3b82f6;
        outline: none;
    }
    .btn-primary {
        background-color: #2563eb;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.3s;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
    }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.3s;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background-color: #4b5563;
    }
</style>

<?php include 'includes/footer.php'; ?>
