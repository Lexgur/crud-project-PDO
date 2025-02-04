<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
</head>
<body class="screen">
<h1>Student List</h1>
<a href="index.php?action=create_student" target="_blank" rel="noopener"><button>ADD</button></a>
<table border="1">
    <thead>
    <tr>
        <th>Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>ID</th>
        <th>ACTIONS</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $student): ?>
        <tr>
            <!-- Accessing object properties with getter methods -->
            <td><?= htmlspecialchars($student->getFirstName()) ?></td>
            <td><?= htmlspecialchars($student->getLastName()) ?></td>
            <td><?= htmlspecialchars($student->getAge()) ?></td>
            <td><?= htmlspecialchars($student->getId()) ?></td>
            <td>
                <a href="index.php?action=delete_student&id=<?= htmlspecialchars($student->getId()) ?>" target="_blank" rel="noopener">
                    <button>DELETE</button>
                </a>
                <a href="index.php?action=update_student&id=<?= htmlspecialchars($student->getId()) ?>" target="_blank" rel="noopener">
                    <button>UPDATE</button>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
