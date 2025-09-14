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
        
        $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        
        $message = "Vehicle deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting vehicle: " . $e->getMessage();
    }
}

// Fetch all vehicles
try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->query("SELECT * FROM vehicles ORDER BY created_at DESC");
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching vehicles: " . $e->getMessage();
    $vehicles = [];
}
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                    <i class="fas fa-car text-blue-600"></i>
                    <span>Vehicle Records</span>
                </h1>
                <p class="text-gray-600 mt-2">Manage all registered vehicles</p>
            </div>
            <div class="flex space-x-3">
                <a href="add_vehicle.php" 
                   class="btn-primary text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Add Vehicle</span>
                </a>
                <a href="generate_pdf.php?type=vehicles" 
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
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Registration No.</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Vehicle Details</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Owner</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Expiry Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vehicles)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-car text-4xl mb-4 text-gray-300"></i>
                                <br>No vehicles found. <a href="add_vehicle.php" class="text-blue-600 hover:text-blue-800">Add the first vehicle</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($vehicle['registration_number']); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo ucfirst(htmlspecialchars($vehicle['vehicle_type'])); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800"><?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars($vehicle['year_of_manufacture'] . ' - ' . $vehicle['color']); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800"><?php echo htmlspecialchars($vehicle['owner_name']); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars($vehicle['owner_phone']); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <?php 
                                    $status = $vehicle['status'];
                                    $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' : ($status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $statusClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($status)); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-800"><?php echo date('d M Y', strtotime($vehicle['expiry_date'])); ?></div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex space-x-2">
                                        <a href="edit_vehicle.php?id=<?php echo $vehicle['id']; ?>" 
                                           class="bg-blue-500 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-600 transition-colors duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="generate_pdf.php?type=vehicle&id=<?php echo $vehicle['id']; ?>" 
                                           class="bg-red-500 text-white px-3 py-2 rounded-md text-sm hover:bg-red-600 transition-colors duration-200">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="?action=delete&id=<?php echo $vehicle['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this vehicle?')"
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

        <?php if (!empty($vehicles)): ?>
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing <?php echo count($vehicles); ?> vehicle(s)
                </div>
                <div class="flex space-x-2">
                    <a href="add_vehicle.php" 
                       class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors duration-200">
                        Add New Vehicle
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>