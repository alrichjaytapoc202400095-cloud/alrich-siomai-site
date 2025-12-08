<?php
include 'database.php';

if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    header("Location: manage_categories.php");
}

include 'header.php';
?>

<h2>Manage Categories</h2>

<form method="POST" style="background:#f9f9f9; padding:15px; border-radius:5px;">
    <label>New Category Name:</label>
    <input type="text" name="name" placeholder="e.g., Platters" required>
    <button type="submit" name="add_category" class="btn btn-green">Add Category</button>
</form>

<h3>Existing Categories</h3>
<ul>
    <?php
    $result = $conn->query("SELECT * FROM categories");
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['name']}</li>";
    }
    ?>
</ul>

</div>
</body>
</html>