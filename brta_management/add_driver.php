<?php
include 'includes/header.php';
require_once 'config/database.php';

$message = '';
$error = '';

if (isset($_POST['submit'])) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $sql = "INSERT INTO drivers 
        (license_number, full_name, father_name, mother_name, nid_number, date_of_birth, blood_group, address, phone, email, license_type, vehicle_class, issue_date, expiry_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $issue_date = date('Y-m-d');
        $expiry_date = date('Y-m-d', strtotime('+5 years'));

        $stmt->execute([
            $_POST['license_number'] ?? '',
            $_POST['full_name'] ?? '',
            $_POST['father_name'] ?? '',
            $_POST['mother_name'] ?? '',
            $_POST['nid_number'] ?? '',
            $_POST['date_of_birth'] ?? '',
            $_POST['blood_group'] ?? '',
            $_POST['address'] ?? '',
            $_POST['phone'] ?? '',
            $_POST['email'] ?? '',
            $_POST['license_type'] ?? '',
            $_POST['vehicle_class'] ?? '',
            $issue_date,
            $expiry_date
        ]);

        if ($stmt->rowCount() > 0) {
            $message = "Driver license issued successfully!";
        } else {
            $error = "Failed to insert data!";
        }

    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>


<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-id-card text-4xl text-green-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Driver License Registration</h1>
            <p class="text-gray-600 mt-2">Issue a new driving license</p>
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

        <form method="POST" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                    <input type="text" name="license_number" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g., DL-123456789">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Type *</label>
                    <select name="license_type" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select License Type</option>
                        <option value="professional">Professional</option>
                        <option value="non-professional">Non-Professional</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="full_name" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name *</label>
                    <input type="text" name="father_name" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Name *</label>
                    <input type="text" name="mother_name" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NID Number *</label>
                    <input type="text" name="nid_number" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="17 digit NID">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                    <input type="date" name="date_of_birth" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group *</label>
                    <select name="blood_group" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" name="phone" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="01XXXXXXXXX">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="example@email.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Class *</label>
                <input type="text" name="vehicle_class" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="e.g., A, B, C, D, E">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <textarea name="address" required rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="Complete address with district and postal code"></textarea>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" name="submit" 
                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-id-card"></i>
                    <span>Issue License</span>
                </button>
                <a href="index.php" 
                   class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 hover:bg-gray-600 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>