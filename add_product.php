<?php
include 'database.php';

if (isset($_POST['save_product'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sidi", $name, $category_id, $price, $stock);
    $stmt->execute();
    header("Location: index.php");
}

include 'header.php';
?>

<h2>Add New Product</h2>
<form method="POST">
    <label>Product Name</label>
    <input type="text" name="name" required>

    <label>Category</label>
    <select name="category_id">
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()) {
            echo "<option value='{$c['id']}'>{$c['name']}</option>";
        }
        ?>
    </select>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required>

    <label>Initial Stock</label>
    <input type="number" name="stock" required>

    <button type="submit" name="save_product" class="btn btn-green">Save Product</button>
    <a href="index.php" class="btn btn-red">Cancel</a>
</form>

</div>
</body>
</html>