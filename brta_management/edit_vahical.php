<?php
include 'includes/header.php';
require_once 'config/database.php';

$message = '';
$error = '';
$vehicle = null;

// Get vehicle ID
$vehicle_id = $_GET['id'] ?? null;

if (!$vehicle_id) {
    header('Location: view_vehicles.php');
    exit;
}

// Fetch vehicle details
try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->execute([$vehicle_id]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vehicle) {
        header('Location: view_vehicles.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Error fetching vehicle: " . $e->getMessage();
}

// Handle form submission
if ($_POST['submit'] ?? false) {
    try {
        $sql = "UPDATE vehicles SET registration_number=?, chassis_number=?, engine_number=?, vehicle_type=?, brand=?, model=?, year_of_manufacture=?, color=?, owner_name=?, owner_nid=?, owner_address=?, owner_phone=?, status=?, updated_at=NOW() WHERE id=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $_POST['registration_number'],
            $_POST['chassis_number'],
            $_POST['engine_number'],
            $_POST['vehicle_type'],
            $_POST['brand'],
            $_POST['model'],
            $_POST['year_of_manufacture'],
            $_POST['color'],
            $_POST['owner_name'],
            $_POST['owner_nid'],
            $_POST['owner_address'],
            $_POST['owner_phone'],
            $_POST['status'],
            $vehicle_id
        ]);
        
        $message = "Vehicle updated successfully!";
        
        // Refresh vehicle data
        $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicle_id]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        $error = "Error updating vehicle: " . $e->getMessage();
    }
}
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-edit text-4xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Edit Vehicle Registration</h1>
            <p class="text-gray-600 mt-2">Update vehicle information</p>
        </div>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i><?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($vehicle): ?>
            <form method="POST" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number *</label>
                        <input type="text" name="registration_number" value="<?php echo htmlspecialchars($vehicle['registration_number']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type *</label>
                        <select name="vehicle_type" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="car" <?php echo $vehicle['vehicle_type'] === 'car' ? 'selected' : ''; ?>>Car</option>
                            <option value="motorcycle" <?php echo $vehicle['vehicle_type'] === 'motorcycle' ? 'selected' : ''; ?>>Motorcycle</option>
                            <option value="truck" <?php echo $vehicle['vehicle_type'] === 'truck' ? 'selected' : ''; ?>>Truck</option>
                            <option value="bus" <?php echo $vehicle['vehicle_type'] === 'bus' ? 'selected' : ''; ?>>Bus</option>
                            <option value="microbus" <?php echo $vehicle['vehicle_type'] === 'microbus' ? 'selected' : ''; ?>>Microbus</option>
                            <option value="pickup" <?php echo $vehicle['vehicle_type'] === 'pickup' ? 'selected' : ''; ?>>Pickup</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chassis Number *</label>
                        <input type="text" name="chassis_number" value="<?php echo htmlspecialchars($vehicle['chassis_number']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Engine Number *</label>
                        <input type="text" name="engine_number" value="<?php echo htmlspecialchars($vehicle['engine_number']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand *</label>
                        <input type="text" name="brand" value="<?php echo htmlspecialchars($vehicle['brand']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                        <input type="text" name="model" value="<?php echo htmlspecialchars($vehicle['model']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year of Manufacture *</label>
                        <input type="number" name="year_of_manufacture" value="<?php echo htmlspecialchars($vehicle['year_of_manufacture']); ?>" required min="1980" max="2025"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color *</label>
                        <input type="text" name="color" value="<?php echo htmlspecialchars($vehicle['color']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Owner NID *</label>
                        <input type="text" name="owner_nid" value="<?php echo htmlspecialchars($vehicle['owner_nid']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" <?php echo $vehicle['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="suspended" <?php echo $vehicle['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                            <option value="expired" <?php echo $vehicle['status'] === 'expired' ? 'selected' : ''; ?>>Expired</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Owner Name *</label>
                        <input type="text" name="owner_name" value="<?php echo htmlspecialchars($vehicle['owner_name']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Owner Phone *</label>
                        <input type="tel" name="owner_phone" value="<?php echo htmlspecialchars($vehicle['owner_phone']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Owner Address *</label>
                    <textarea name="owner_address" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($vehicle['owner_address']); ?></textarea>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" name="submit" 
                            class="btn-primary text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-save"></i>
                        <span>Update Vehicle</span>
                    </button>
                    <a href="view_vehicles.php" 
                       class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:bg-gray-600 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Vehicles</span>
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>