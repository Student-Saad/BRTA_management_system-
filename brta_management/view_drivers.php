<?php
include 'includes/header.php';
require_once 'config/database.php';

$message = '';
$error = '';

// Handle delete operation
if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $stmt = $conn->prepare("DELETE FROM drivers WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        
        $message = "Driver record deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting driver: " . $e->getMessage();
    }
}

// Fetch all drivers
try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->query("SELECT * FROM drivers ORDER BY created_at DESC");
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching drivers: " . $e->getMessage();
    $drivers = [];
}
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                    <i class="fas fa-users text-green-600"></i>
                    <span>Driver Records</span>
                </h1>
                <p class="text-gray-600 mt-2">Manage all licensed drivers</p>
            </div>
            <div class="flex space-x-3">
                <a href="add_driver.php" 
                   class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Add Driver</span>
                </a>
                <a href="generate_pdf.php?type=drivers" 
                   class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:bg-red-700 transition-all duration-300">
                    <i class="fas fa-file-pdf"></i>
                    <span>PDF Report</span>
                </a>
            </div>
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

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">License No.</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Driver Details</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Contact</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">License Info</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($drivers)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                <br>No drivers found. <a href="add_driver.php" class="text-green-600 hover:text-green-800">Add the first driver</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($drivers as $driver): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($driver['license_number']); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars($driver['nid_number']); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800"><?php echo htmlspecialchars($driver['full_name']); ?></div>
                                    <div class="text-sm text-gray-600">DoB: <?php echo date('d M Y', strtotime($driver['date_of_birth'])); ?></div>
                                    <div class="text-sm text-gray-600">Blood: <?php echo htmlspecialchars($driver['blood_group']); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-800"><?php echo htmlspecialchars($driver['phone']); ?></div>
                                    <?php if ($driver['email']): ?>
                                        <div class="text-sm text-gray-600"><?php echo htmlspecialchars($driver['email']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-800">Type: <?php echo ucfirst(htmlspecialchars($driver['license_type'])); ?></div>
                                    <div class="text-sm text-gray-600">Class: <?php echo htmlspecialchars($driver['vehicle_class']); ?></div>
                                    <div class="text-sm text-gray-600">Expires: <?php echo date('d M Y', strtotime($driver['expiry_date'])); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <?php 
                                    $status = $driver['status'];
                                    $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' : ($status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $statusClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($status)); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex space-x-2">
                                        <a href="edit_driver.php?id=<?php echo $driver['id']; ?>" 
                                           class="bg-blue-500 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-600 transition-colors duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="generate_pdf.php?type=driver&id=<?php echo $driver['id']; ?>" 
                                           class="bg-red-500 text-white px-3 py-2 rounded-md text-sm hover:bg-red-600 transition-colors duration-200">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="?action=delete&id=<?php echo $driver['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this driver record?')"
                                           class="bg-red-600 text-white px-3 py-2 rounded-md text-sm hover:bg-red-700 transition-colors duration-200">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($drivers)): ?>
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing <?php echo count($drivers); ?> driver(s)
                </div>
                <div class="flex space-x-2">
                    <a href="add_driver.php" 
                       class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition-colors duration-200">
                        Add New Driver
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>