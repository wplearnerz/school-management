<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Check if ID is set and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid teacher ID.");
}

$id = (int)$_GET['id'];

// Fetch teacher data
$sql = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows != 1) {
    die("Teacher not found.");
}

$teacher = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $email = $conn->real_escape_string($_POST['email']);

    // Update teacher
    $sql = $conn->prepare("UPDATE teachers SET name = ?, subject = ?, email = ? WHERE id = ?");
    $sql->bind_param("sssi", $name, $subject, $email, $id);

    if ($sql->execute() === TRUE) {
        header("Location: teachers.php");
        exit();
    } else {
        echo "Error updating record: " . htmlspecialchars($sql->error);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
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

<div class="container mx-auto mt-8">
    <h1 class="text-4xl font-bold text-center text-blue-800">Edit Teacher</h1>
    
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <form method="POST" action="edit_teacher.php?id=<?php echo htmlspecialchars($id); ?>">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($teacher['name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-gray-700 font-bold mb-2">Subject:</label>
                <input type="text" name="subject" id="subject" value="<?php echo htmlspecialchars($teacher['subject']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 focus:outline-none focus:shadow-outline">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>