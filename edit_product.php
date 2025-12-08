<?php
include 'database.php';

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $cat_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $conn->query("UPDATE products SET name='$name', category_id='$cat_id', price='$price', stock='$stock' WHERE id=$id");
    header("Location: index.php");
}

include 'header.php';
?>

<h2>Edit Product</h2>
<form method="POST">
    <label>Product Name</label>
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required>

    <label>Category</label>
    <select name="category_id">
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()) {
            $selected = ($c['id'] == $product['category_id']) ? 'selected' : '';
            echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
        }
        ?>
    </select>

    <label>Price</label>
    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>">

    <label>Stock</label>
    <input type="number" name="stock" value="<?php echo $product['stock']; ?>">

    <button type="submit" name="update_product" class="btn btn-blue">Update Product</button>
</form>

</div>
</body>
</html>