<?php
         include 'db.php';

         $db_name = "school_management";
         $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
         if (!$conn->query($sql)) {
            die("Connection Failed" . $conn->connect_error);
         }
         
// Select the database
$conn->select_db("school_management");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-yellow-50">



<nav class="bg-blue-600 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="#" class="text-white text-2xl font-bold">School Management System</a>
        <ul class="flex space-x-4">
            <li><a href="index.php" class="text-white">Home</a></li>
            <li><a href="students.php" class="text-white">Students</a></li>
            <li><a href="teachers.php" class="text-white">Teachers</a></li>
            <li><a href="classes.php" class="text-white">Classes</a></li>
            <li><a href="about.php" class="text-white">About</a></li>
        </ul>
    </div>
</nav>

<!-- Hero Section Start -->
<div class="bg-blue-200 py-20">
    <div class="container mx-auto text-center">
        <h1 class="text-5xl font-bold text-blue-800">Welcome to the School Management System</h1>
        <p class="mt-4 text-xl text-gray-700">Streamline your school’s operations with our efficient and secure system.</p>
        <a href="about.php" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700">Learn More</a>
    </div>
</div>
<!-- Hero Section End -->

<!-- <div class="container mx-auto mt-8">
    <h1 class="text-4xl font-bold text-center text-blue-800">Welcome to the School Management System</h1>
    <p class="mt-4 text-center text-gray-700">Manage all your school data efficiently and securely.</p>
</div> -->

<!-- Image Section Start -->
<div class="container mx-auto my-12 px-4">
    <div class="relative">
        <img src="images/school.png" alt="High Quality School Image" class="w-full h-auto rounded-lg shadow-lg">
        <div class="absolute inset-0 bg-blue-800 bg-opacity-50 flex items-center justify-center rounded-lg">
            <h2 class="text-white text-4xl font-bold">Empowering Education</h2>
        </div>
    </div>
</div>
<!-- Image Section End -->
 <?php require_once 'footer.php'; ?>
</body>
</html>