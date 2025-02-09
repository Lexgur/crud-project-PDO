<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>View Students</title>
</head>
<body class="screen">
<h1>Student List</h1>
<button class="add-btn"><a href="/index.php?action=create_student" target="_blank" rel="noopener">ADD</a></button>
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
            <td><?=$student->getFirstName()?></td>
            <td><?=$student->getLastName()?></td>
            <td><?=$student->getAge()?></td>
            <td><?=$student->getId()?></td>
            <td>
                    <button class = "dlt-btn"><a href="/index.php?action=delete_student&id=<?=$student->getId()?>" target="_blank" rel="noopener">DELETE </a></button>

                    <button class = "upd-btn"><a href="/index.php?action=update_student&id=<?=$student->getId()?>" target="_blank" rel="noopener">UPDATE</a></button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
