<?php 
include 'database.php'; 
include 'header.php'; 
?>

<h2>Product Management</h2>
<a href="add_product.php" class="btn btn-green" style="margin-bottom: 15px;">+ Add New Product</a>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // JOIN: Get Category Name alongside Product Data
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <td>₱<?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td class="action-links">
                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-blue">Edit</a>
                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-red" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div> <!-- Close Container -->
</body>
</html>