<?php
include 'includes/header.php';
require_once 'config/database.php';

$message = '';
$error = '';
$driver = null;

// Get driver ID
$driver_id = $_GET['id'] ?? null;

if (!$driver_id) {
    header('Location: view_drivers.php');
    exit;
}

// Fetch driver details
try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM drivers WHERE id = ?");
    $stmt->execute([$driver_id]);
    $driver = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$driver) {
        header('Location: view_drivers.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Error fetching driver: " . $e->getMessage();
}

// Handle form submission
if ($_POST['submit'] ?? false) {
    try {
        $sql = "UPDATE drivers SET license_number=?, full_name=?, father_name=?, mother_name=?, nid_number=?, date_of_birth=?, blood_group=?, address=?, phone=?, email=?, license_type=?, vehicle_class=?, status=?, updated_at=NOW() WHERE id=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $_POST['license_number'],
            $_POST['full_name'],
            $_POST['father_name'],
            $_POST['mother_name'],
            $_POST['nid_number'],
            $_POST['date_of_birth'],
            $_POST['blood_group'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['license_type'],
            $_POST['vehicle_class'],
            $_POST['status'],
            $driver_id
        ]);
        
        $message = "Driver information updated successfully!";
        
        // Refresh driver data
        $stmt = $conn->prepare("SELECT * FROM drivers WHERE id = ?");
        $stmt->execute([$driver_id]);
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        $error = "Error updating driver: " . $e->getMessage();
    }
}
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-user-edit text-4xl text-green-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Edit Driver License</h1>
            <p class="text-gray-600 mt-2">Update driver information</p>
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

        <?php if ($driver): ?>
            <form method="POST" class="space-y-6">
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                        <input type="text" name="license_number" value="<?php echo htmlspecialchars($driver['license_number']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Type *</label>
                        <select name="license_type" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="professional" <?php echo $driver['license_type'] === 'professional' ? 'selected' : ''; ?>>Professional</option>
                            <option value="non-professional" <?php echo $driver['license_type'] === 'non-professional' ? 'selected' : ''; ?>>Non-Professional</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="active" <?php echo $driver['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="suspended" <?php echo $driver['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                            <option value="expired" <?php echo $driver['status'] === 'expired' ? 'selected' : ''; ?>>Expired</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($driver['full_name']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name *</label>
                        <input type="text" name="father_name" value="<?php echo htmlspecialchars($driver['father_name']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Name *</label>
                        <input type="text" name="mother_name" value="<?php echo htmlspecialchars($driver['mother_name']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NID Number *</label>
                        <input type="text" name="nid_number" value="<?php echo htmlspecialchars($driver['nid_number']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($driver['date_of_birth']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group *</label>
                        <select name="blood_group" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <?php 
                            $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                            foreach ($blood_groups as $bg): ?>
                                <option value="<?php echo $bg; ?>" <?php echo $driver['blood_group'] === $bg ? 'selected' : ''; ?>>
                                    <?php echo $bg; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Class *</label>
                        <input type="text" name="vehicle_class" value="<?php echo htmlspecialchars($driver['vehicle_class']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($driver['phone']); ?>" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($driver['email'] ?? ''); ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <textarea name="address" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"><?php echo htmlspecialchars($driver['address']); ?></textarea>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" name="submit" 
                            class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-save"></i>
                        <span>Update Driver</span>
                    </button>
                    <a href="view_drivers.php" 
                       class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:bg-gray-600 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Drivers</span>
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>