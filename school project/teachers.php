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

// Create teachers table if not exists
$sql = "CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . htmlspecialchars($conn->error));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $email = $conn->real_escape_string($_POST['email']);

    if (!empty($_POST['id'])) {
        // Update existing teacher
        $id = (int)$_POST['id'];
        $sql = $conn->prepare("UPDATE teachers SET name = ?, subject = ?, email = ? WHERE id = ?");
        $sql->bind_param("sssi", $name, $subject, $email, $id);
    } else {
        // Add new teacher
        $sql = $conn->prepare("INSERT INTO teachers (name, subject, email) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $name, $subject, $email);
    }

    if ($sql->execute() === TRUE) {
        header("Location: teachers.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($sql->error);
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $sql->bind_param("i", $id);

    if ($sql->execute() === TRUE) {
        header("Location: teachers.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($sql->error);
    }
}

// Fetch teachers
$result = $conn->query("SELECT * FROM teachers");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>
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
    <h1 class="text-4xl font-bold text-center text-blue-800">Teachers</h1>
    
    <!-- Add/Edit Teacher Form -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <form method="POST" action="teachers.php">
            <input type="hidden" name="id" id="teacherId">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-gray-700 font-bold mb-2">Subject:</label>
                <input type="text" name="subject" id="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 focus:outline-none focus:shadow-outline">Save</button>
            </div>
        </form>
    </div>

    <!-- Teacher List -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-blue-800">Teacher List</h2>
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="edit_teacher.php?id=<?php echo $row['id']; ?>"  class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                        <a href="teachers.php?delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function editTeacher(id, name, subject, email) {
    document.getElementById('teacherId').value = id;
    document.getElementById('name').value = name;
    document.getElementById('subject').value = subject;
    document.getElementById('email').value = email;
}
</script>

</body>
</html>