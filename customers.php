<?php
include 'database.php';

if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $conn->query("INSERT INTO customers (name, phone) VALUES ('$name', '$phone')");
    header("Location: customers.php");
}

include 'header.php';
?>

<h2>Customer List</h2>

<form method="POST" style="margin-bottom: 20px;">
    <div style="display: flex; gap: 10px;">
        <input type="text" name="name" placeholder="Customer Name" required>
        <input type="text" name="phone" placeholder="Phone Number">
        <button type="submit" name="add_customer" class="btn btn-green" style="height: 40px; margin-top: 5px;">Add</button>
    </div>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM customers");
    while($row = $res->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['phone']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</div>
</body>
</html>