<?php
include 'db.php';
// Check if ID is set and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid class ID.");
}

$id = (int)$_GET['id'];

// Fetch class data
$sql = $conn->prepare("SELECT * FROM classes WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows != 1) {
    die("Class not found.");
}

$class = $result->fetch_assoc();

// Fetch teachers for the dropdown
$teachers = $conn->query("SELECT id, name FROM teachers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_name = $conn->real_escape_string($_POST['class_name']);
    $teacher_id = (int)$_POST['teacher_id'];

    // Update class
    $sql = $conn->prepare("UPDATE classes SET class_name = ?, teacher_id = ? WHERE id = ?");
    $sql->bind_param("sii", $class_name, $teacher_id, $id);

    if ($sql->execute() === TRUE) {
        header("Location: classes.php");
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
    <title>Edit Class</title>
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
    <h1 class="text-4xl font-bold text-center text-blue-800">Edit Class</h1>
    
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <form method="POST" action="edit_classes.php?id=<?php echo htmlspecialchars($id); ?>">
            <div class="mb-4">
                <label for="class_name" class="block text-gray-700 font-bold mb-2">Class Name:</label>
                <input type="text" name="class_name" id="class_name" value="<?php echo htmlspecialchars($class['class_name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="teacher_id" class="block text-gray-700 font-bold mb-2">Teacher:</label>
                <select name="teacher_id" id="teacher_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select a teacher</option>
                    <?php while($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['id']; ?>" <?php if ($teacher['id'] == $class['teacher_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($teacher['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
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