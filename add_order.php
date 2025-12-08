<?php
include 'database.php';

if (isset($_POST['submit_order'])) {
    $customer_id = $_POST['customer_id'];
    $quantities = $_POST['qty']; // Array of [product_id => quantity]

    // 1. Create the Main Order
    $conn->query("INSERT INTO orders (customer_id) VALUES ('$customer_id')");
    $order_id = $conn->insert_id;

    // 2. Loop through items and add to order_items table
    foreach ($quantities as $product_id => $qty) {
        if ($qty > 0) {
            // Get current price
            $prod = $conn->query("SELECT price FROM products WHERE id=$product_id")->fetch_assoc();
            $price = $prod['price'];

            // Add item
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                          VALUES ('$order_id', '$product_id', '$qty', '$price')");

            // Decrease Stock
            $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$product_id");
        }
    }
    echo "<script>alert('Order Placed Successfully!'); window.location='view_orders.php';</script>";
}

include 'header.php';
?>

<h2>Create New Order</h2>
<form method="POST">
    <label>Select Customer:</label>
    <select name="customer_id">
        <?php
        $res = $conn->query("SELECT * FROM customers");
        while ($r = $res->fetch_assoc()) {
            echo "<option value='{$r['id']}'>{$r['name']}</option>";
        }
        ?>
    </select>
    
    <h3>Order Items</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Stock Left</th>
            <th>Quantity</th>
        </tr>
        <?php
        $products = $conn->query("SELECT * FROM products");
        while ($p = $products->fetch_assoc()): 
        ?>
        <tr>
            <td><?php echo $p['name']; ?></td>
            <td>₱<?php echo $p['price']; ?></td>
            <td><?php echo $p['stock']; ?></td>
            <td>
                <input type="number" name="qty[<?php echo $p['id']; ?>]" value="0" min="0" max="<?php echo $p['stock']; ?>" style="width: 80px; margin:0;">
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <div style="margin-top: 20px;">
        <button type="submit" name="submit_order" class="btn btn-green">Place Order</button>
    </div>
</form>

</div>
</body>
</html>