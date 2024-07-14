<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50">

<nav class="bg-blue-600 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-white text-2xl font-bold">School Management System</a>
        <ul class="flex space-x-4">
            <li><a href="index.php" class="text-white">Home</a></li>
            <li><a href="students.php" class="text-white">Students</a></li>
            <li><a href="teachers.php" class="text-white">Teachers</a></li>
            <li><a href="classes.php" class="text-white">Classes</a></li>
            <li><a href="about.php" class="text-white">About</a></li>
        </ul>
    </div>
</nav>

<div class="container mx-auto mt-8 mb-28">
    <h1 class="text-4xl font-bold text-center text-blue-800">About Us</h1>
    
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Welcome to Our School Management System</h2>
        <p class="mb-4">
            Our School Management System is designed to facilitate the management of student, teacher, and class information efficiently.
            We aim to enhance the educational experience by providing a robust platform for administration, record-keeping, and communication.
        </p>
        <h3 class="text-xl font-semibold mb-2">Features:</h3>
        <ul class="list-disc list-inside mb-4">
            <li>Manage student records with ease</li>
            <li>Organize teacher information</li>
            <li>Class management for effective learning</li>
            <li>Secure login for users</li>
            <li>User-friendly interface with Tailwind CSS</li>
        </ul>
        <h3 class="text-xl font-semibold mb-2">Contact Us:</h3>
        <p>If you have any questions or feedback, feel free to reach out to us at <a href="mailto:muhammadazhar271@gmail.com" class="text-blue-600">muhammadazhar271@gmail.com</a>.</p>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>