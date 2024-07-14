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

// Create classes table if not exists
$sql = "CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(255) NOT NULL,
    teacher_id INT,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . htmlspecialchars($conn->error));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_name = $conn->real_escape_string($_POST['class_name']);
    $teacher_id = (int)$_POST['teacher_id'];

    if (!empty($_POST['id'])) {
        // Update existing class
        $id = (int)$_POST['id'];
        $sql = $conn->prepare("UPDATE classes SET class_name = ?, teacher_id = ? WHERE id = ?");
        $sql->bind_param("sii", $class_name, $teacher_id, $id);
    } else {
        // Add new class
        $sql = $conn->prepare("INSERT INTO classes (class_name, teacher_id) VALUES (?, ?)");
        $sql->bind_param("si", $class_name, $teacher_id);
    }

    if ($sql->execute() === TRUE) {
        header("Location: classes.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($sql->error);
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = $conn->prepare("DELETE FROM classes WHERE id = ?");
    $sql->bind_param("i", $id);

    if ($sql->execute() === TRUE) {
        header("Location: classes.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($sql->error);
    }
}

// Fetch classes
$result = $conn->query("SELECT classes.id, classes.class_name, teachers.name as teacher_name FROM classes LEFT JOIN teachers ON classes.teacher_id = teachers.id");

// Fetch teachers for the dropdown
$teachers = $conn->query("SELECT id, name FROM teachers");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes</title>
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
    <h1 class="text-4xl font-bold text-center text-blue-800">Classes</h1>
    
    <!-- Add/Edit Class Form -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <form method="POST" action="classes.php">
            <input type="hidden" name="id" id="classId">
            <div class="mb-4">
                <label for="class_name" class="block text-gray-700 font-bold mb-2">Class Name:</label>
                <input type="text" name="class_name" id="class_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="teacher_id" class="block text-gray-700 font-bold mb-2">Teacher:</label>
                <select name="teacher_id" id="teacher_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select a teacher</option>
                    <?php while($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 focus:outline-none focus:shadow-outline">Save</button>
            </div>
        </form>
    </div>

    <!-- Class List -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-blue-800">Class List</h2>
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Class Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teacher</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['class_name']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="edit_classes.php?id=<?php echo $row['id']; ?>"  class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                        <a href="classes.php?delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function editClass(id, className, teacherId) {
    document.getElementById('classId').value = id;
    document.getElementById('class_name').value = className;
    document.getElementById('teacher_id').value = teacherId;
}
</script>

</body>
</html>