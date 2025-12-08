<?php
include 'database.php';
$order_id = $_GET['id'];

// Get Customer Info
$order_info = $conn->query("SELECT o.order_date, c.name 
                            FROM orders o 
                            JOIN customers c ON o.customer_id = c.id 
                            WHERE o.id = $order_id")->fetch_assoc();

include 'header.php';
?>

<a href="view_orders.php" class="btn btn-blue" style="margin-bottom:15px;">← Back to List</a>

<div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
    <h3>Order #<?php echo $order_id; ?> Summary</h3>
    <p><strong>Customer:</strong> <?php echo $order_info['name']; ?></p>
    <p><strong>Date:</strong> <?php echo $order_info['order_date']; ?></p>
</div>

<table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Subtotal</th>
    </tr>
    <?php
    // JOIN: Link OrderItems to Products
    $sql = "SELECT oi.quantity, oi.price_at_purchase, p.name 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = $order_id";
    
    $result = $conn->query($sql);
    $grand_total = 0;

    while($row = $result->fetch_assoc()): 
        $subtotal = $row['quantity'] * $row['price_at_purchase'];
        $grand_total += $subtotal;
    ?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>₱<?php echo number_format($row['price_at_purchase'], 2); ?></td>
        <td>₱<?php echo number_format($subtotal, 2); ?></td>
    </tr>
    <?php endwhile; ?>
    
    <tr style="background-color: #e9ecef;">
        <td colspan="3" style="text-align: right; font-weight: bold;">Grand Total:</td>
        <td style="font-weight: bold; color: green;">₱<?php echo number_format($grand_total, 2); ?></td>
    </tr>
</table>

</div>
</body>
</html>