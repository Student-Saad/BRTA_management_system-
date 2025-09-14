<?php include 'includes/header.php'; ?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-green-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="mb-8">
                <i class="fas fa-shield-alt text-6xl mb-6 opacity-90"></i>
            </div>
            <h1 class="text-5xl font-bold mb-6">BRTA Management System</h1>
            <p class="text-xl mb-8 opacity-90">Bangladesh Road Transport Authority - Digital License Management</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="add_driver.php" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold flex items-center justify-center space-x-2 hover:bg-gray-100 hover:transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-id-card"></i>
                    <span>Issue License</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">System Features</h2>
                <p class="text-gray-600">Comprehensive license management solutions</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Driver License -->
                <div class="card-hover bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-id-card text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Driver Licensing</h3>
                    <p class="text-gray-600 mb-4">Issue and manage driving licenses with complete records</p>
                    <a href="view_drivers.php" class="text-green-600 hover:text-green-800 font-medium">
                        Manage Drivers <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Search System -->
                <div class="card-hover bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Advanced Search</h3>
                    <p class="text-gray-600 mb-4">Powerful search across all records and entities</p>
                    <a href="search.php" class="text-purple-600 hover:text-purple-800 font-medium">
                        Search Records <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Reports -->
                <div class="card-hover bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-pdf text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">PDF Reports</h3>
                    <p class="text-gray-600 mb-4">Generate and download comprehensive PDF reports</p>
                    <a href="reports.php" class="text-orange-600 hover:text-orange-800 font-medium">
                        Generate Reports <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">System Overview</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                require_once 'config/database.php';
                $db = new Database();
                $conn = $db->connect();
                
                // Get driver count
                $driver_stmt = $conn->query("SELECT COUNT(*) as count FROM drivers");
                $driver_count = $driver_stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
                
                // Get expired licenses
                $expired_stmt = $conn->query("SELECT COUNT(*) as count FROM drivers WHERE status = 'expired' OR expiry_date < CURDATE()");
                $expired_count = $expired_stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
                ?>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2"><?php echo number_format($driver_count); ?></div>
                    <div class="text-gray-600">Licensed Drivers</div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="text-3xl font-bold text-red-600 mb-2"><?php echo number_format($expired_count); ?></div>
                    <div class="text-gray-600">Expired Licenses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Quick Actions</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <a href="add_driver.php" class="card-hover block bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-xl shadow-md">
                    <i class="fas fa-user-plus text-4xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Issue Driver License</h3>
                    <p class="opacity-90">Issue new driving license with verification</p>
                </a>
                
                <a href="search.php" class="card-hover block bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-xl shadow-md">
                    <i class="fas fa-search text-4xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Search Records</h3>
                    <p class="opacity-90">Find drivers across all records</p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
