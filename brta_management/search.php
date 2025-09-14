<?php
include 'includes/header.php';
require_once 'config/database.php';

$results = [];
$search_query = $_GET['query'] ?? '';

if ($search_query) {
    try {
        $db = new Database();
        $conn = $db->connect();

        // শুধু drivers সার্চ
        $sql = "SELECT 'driver' as type, id, license_number as identifier, 
                       full_name as name, 
                       status, created_at 
                FROM drivers 
                WHERE license_number LIKE ? 
                   OR full_name LIKE ? 
                   OR nid_number LIKE ? 
                   OR phone LIKE ? 
                   OR email LIKE ?";
        $stmt = $conn->prepare($sql);
        $search_term = "%$search_query%";
        $stmt->execute([$search_term, $search_term, $search_term, $search_term, $search_term]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $error = "Search error: " . $e->getMessage();
    }
}
?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-search text-4xl text-purple-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Search Drivers</h1>
            <p class="text-gray-600 mt-2">Find drivers across the database</p>
        </div>

        <!-- Search Form -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="query" value="<?php echo htmlspecialchars($search_query); ?>" 
                           placeholder="Search by license number, name, NID, phone, email..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                </div>
                
                <button type="submit" 
                        class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold flex items-center justify-center space-x-2 hover:transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
            </form>
        </div>

        <!-- Search Results -->
        <?php if ($search_query): ?>
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    Search Results for "<?php echo htmlspecialchars($search_query); ?>" 
                    <span class="text-sm font-normal text-gray-600">(<?php echo count($results); ?> found)</span>
                </h2>
            </div>

            <?php if (empty($results)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Results Found</h3>
                    <p class="text-gray-500">Try searching with different keywords or check spelling</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($results as $result): ?>
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <i class="fas fa-id-card text-green-600"></i>
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">DRIVER</span>
                                        
                                        <?php 
                                        $status = $result['status'];
                                        $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' 
                                                        : ($status === 'expired' ? 'bg-red-100 text-red-800' 
                                                        : 'bg-yellow-100 text-yellow-800');
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $statusClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($status)); ?>
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        <?php echo htmlspecialchars($result['identifier']); ?> - <?php echo htmlspecialchars($result['name']); ?>
                                    </h3>
                                    
                                    <p class="text-sm text-gray-500">
                                        Added: <?php echo date('d M Y', strtotime($result['created_at'])); ?>
                                    </p>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="edit_driver.php?id=<?php echo $result['id']; ?>" 
                                       class="bg-blue-500 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-600 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="generate_pdf.php?type=driver&id=<?php echo $result['id']; ?>" 
                                       class="bg-red-500 text-white px-3 py-2 rounded-md text-sm hover:bg-red-600 transition-colors duration-200">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Search Drivers</h3>
                <p class="text-gray-500">Enter keywords above to find drivers</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
