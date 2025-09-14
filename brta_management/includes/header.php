<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BRTA Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
  <!-- Navbar -->
  <nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <a href="index.php" class="text-2xl font-bold text-blue-600">BRTA</a>

        <!-- Menu -->
        <div class="hidden md:flex items-center space-x-6">
          <a href="index.php" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>

          <!-- Issue License Dropdown (updated) -->
          <div class="relative group">
            <button class="flex items-center text-gray-700 hover:text-blue-600 font-medium">
              Issue License <i class="fas fa-chevron-down ml-2 text-sm"></i>
            </button>
            <!-- Dropdown -->
            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 w-48">
              <a href="add_driver.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Add Driver</a>
            </div>
          </div>

          <!-- Manage Drivers Dropdown (updated) -->
          <div class="relative group">
            <button class="flex items-center text-gray-700 hover:text-blue-600 font-medium">
              Manage Drivers <i class="fas fa-chevron-down ml-2 text-sm"></i>
            </button>
            <!-- Dropdown -->
            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 w-48">
              <a href="view_drivers.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Drivers</a>
            </div>
          </div>

          <a href="search.php" class="text-gray-700 hover:text-blue-600 font-medium">Search</a>
        </div>
      </div>
    </div>
  </nav>
</body>
</html>
