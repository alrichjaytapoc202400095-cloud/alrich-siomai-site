<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Siomai Shop Store System</title>
  <style>
    :root{
      --primary:#d32f2f; --accent:#fbc02d; --dark:#212121; --light:#f5f5f5; --success:#388e3c;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,Segoe UI,Helvetica,Arial,sans-serif;background:var(--light);display:flex;min-height:100vh}
    .sidebar{width:260px;background:var(--dark);color:#fff;display:flex;flex-direction:column}
    .brand{padding:22px;text-align:center;font-weight:700;background:var(--primary);font-size:20px}
    .brand span{color:var(--accent)}
    .menu{list-style:none;margin:0;padding:0}
    .menu button{width:100%;padding:14px 18px;border:none;background:none;color:#ddd;text-align:left;cursor:pointer;font-size:15px;border-bottom:1px solid #2a2a2a}
    .menu button:hover,.menu button.active{background:#333;color:var(--accent);border-left:4px solid var(--accent)}
    .main{flex:1;padding:26px;overflow:auto}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
    .page{display:none;animation:f 240ms}
    .page.active{display:block}
    @keyframes f{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px}
    .item-card{background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);cursor:pointer;padding:12px;text-align:center}
    /* Updated Image Height for better visibility */
    .item-card img{width:100%;height:150px;object-fit:cover;border-radius:6px} 
    .stock-badge{position:absolute;right:12px;top:12px;padding:4px 8px;border-radius:12px;font-size:12px;color:#fff;background:rgba(0,0,0,0.5)}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:8px;overflow:hidden}
    th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #eee}
    th{background:#333;color:#fff}
    .btn{padding:8px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600}
    .btn-blue{background:#1976d2;color:#fff}
    .btn-red{background:var(--primary);color:#fff}
    .btn-green{background:var(--success);color:#fff}
    .btn-remove{background:#ff5252;color:#fff;border-radius:50%;width:28px;height:28px;border:none}
    input,select{padding:10px;border:1px solid #ddd;border-radius:6px;width:100%;margin-bottom:10px}
    .pos-container{display:flex;gap:18px}
    .product-area{flex:2}
    .cart-area{flex:1;background:#fff;padding:16px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.04)}
    .cart-item{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0f0}
    .total-section{margin-top:16px;border-top:2px solid #eee;padding-top:10px;display:flex;justify-content:space-between;font-weight:700}
    /* modal */
    .modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:50}
    .modal-content{background:#fff;padding:18px;border-radius:10px;width:420px;max-width:95%;position:relative}
    .close-modal{position:absolute;right:12px;top:10px;font-size:22px;cursor:pointer;color:#888}
    .table-thumb{width:50px;height:50px;border-radius:6px;object-fit:cover;border:1px solid #eee}
    .muted{color:#777}
    @media(max-width:900px){.pos-container{flex-direction:column}.sidebar{display:none}}
  </style>
</head>
<body>

  <div class="sidebar">
    <div class="brand">SIOMAI <span>SHOP</span></div>
    <ul class="menu">
      <li><button id="btn-pos" onclick="showPage('pos')" class="active">💰 Point of Sale</button></li>
      <li><button id="btn-products" onclick="showPage('products')">📦 Products & Stock</button></li>
      <li><button id="btn-categories" onclick="showPage('categories')">🗂 Categories</button></li>
      <li><button id="btn-orders" onclick="showPage('orders')">📜 Sales History</button></li>
      <li><button id="btn-customers" onclick="showPage('customers')">👥 Customers</button></li>
    </ul>
  </div>

  <div class="main">
    <!-- POS -->
    <div id="pos" class="page active">
      <div class="header">
        <h2>New Order</h2>
        <select id="pos-customer" style="width:220px">
          <option>Walk-in Customer</option>
        </select>
      </div>

      <div class="pos-container">
        <div class="product-area">
          <div class="grid" id="pos-grid"></div>
        </div>

        <div class="cart-area">
          <h3>Current Order</h3>
          <div id="cart-items"><p class="muted" style="text-align:center">No items selected</p></div>
          <div class="total-section"><span>Total:</span><span id="cart-total">₱0.00</span></div>
          <button class="btn btn-green" style="width:100%;margin-top:14px;padding:12px" onclick="checkout()">CONFIRM PAYMENT</button>
        </div>
      </div>
    </div>

    <!-- PRODUCTS -->
    <div id="products" class="page">
      <div class="header">
        <h2>Product Inventory</h2>
        <div>
          <button class="btn btn-blue" onclick="openAddProduct()">+ Add Product</button>
        </div>
      </div>

      <table>
        <thead><tr><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead>
        <tbody id="inventory-table"></tbody>
      </table>
    </div>

    <!-- CATEGORIES -->
    <div id="categories" class="page">
      <h2>Manage Categories</h2>
      <div style="display:flex;gap:8px;margin-bottom:12px;">
        <input id="new-category" placeholder="Add new category..." />
        <button class="btn btn-green" onclick="addCategory()">Add</button>
      </div>

      <table>
        <thead><tr><th>Category</th><th>Actions</th></tr></thead>
        <tbody id="category-table"></tbody>
      </table>
    </div>

    <!-- ORDERS -->
    <div id="orders" class="page">
      <h2>Sales History</h2>
      <table>
        <thead><tr><th>ID</th><th>Customer</th><th>Date</th><th>Items</th><th>Total</th><th>Action</th></tr></thead>
        <tbody id="order-history-table"></tbody>
      </table>
    </div>

    <div id="customers" class="page">
      <h2>Customers</h2>
      <p class="muted">Walk-in</p>
    </div>
  </div>

  <!-- ADD/EDIT PRODUCT MODAL -->
  <div id="productModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeProductModal()">&times;</span>
      <h3 id="productModalTitle">Add New Product</h3>

      <input id="p-id" type="hidden" />
      <label>Product Name</label>
      <input id="p-name" placeholder="e.g. Pork Siomai (4pcs)"/>

      <label>Category</label>
      <select id="p-cat"></select>

      <label>Price (₱)</label>
      <input id="p-price" type="number" min="0" step="0.01"/>

      <label>Stock</label>
      <input id="p-stock" type="number" min="0" step="1"/>

      <label>Image URL</label>
      <input id="p-img" placeholder="optional image link"/>

      <button class="btn btn-green" style="width:100%;margin-top:6px" onclick="saveProduct()">SAVE PRODUCT</button>
    </div>
  </div>

  <!-- ORDER DETAILS MODAL -->
  <div id="orderModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeOrderModal()">&times;</span>
      <h3>Order Details</h3>
      <div id="order-details"></div>
    </div>
  </div>

<script>
/* ---------- Data (initial) ---------- */
let categories = ["Steamed","Fried","Drinks","Extras","Rice Meal"];
let products = [
  // PORK SIOMAI - Matches your "Platter" picture
  {id:1,name:"Pork Siomai (Platter)",cat:"Steamed",price:35,stock:50,img:"https://images.summitmedia-digital.com/spotph/images/2021/01/22/ambers-siomai-1611299958.jpg"},
  
  // BEEF SIOMAI - Matches your "3 pieces" picture
  {id:2,name:"Beef Siomai (4pcs)",cat:"Steamed",price:40,stock:8,img:"https://panlasangpinoy.com/wp-content/uploads/2018/12/Chicken-Siomai.jpg"},
  
  // FRIED SIOMAI - Matches your "Bamboo Steamer" picture
  {id:3,name:"Fried Siomai",cat:"Fried",price:35,stock:30,img:"https://images.summitmedia-digital.com/yummyph/images/2016/09/06/fried-siomai.jpg"},
  
  // GULAMAN DRINK - Matches your "Cup" picture
  {id:4,name:"Black Gulaman",cat:"Drinks",price:15,stock:100,img:"https://philippinesfoodrecipes.eu/wp-content/uploads/2016/06/sago-at-gulaman.jpg"},
];
let cart = [];
let orders = [];

/* ---------- Navigation ---------- */
function showPage(pageId){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.getElementById(pageId).classList.add('active');
  document.querySelectorAll('.menu button').forEach(b=>b.classList.remove('active'));
  document.getElementById('btn-'+pageId).classList.add('active');
  if(pageId==='pos') renderPOS();
  if(pageId==='products') renderInventory();
  if(pageId==='categories') renderCategories();
  if(pageId==='orders') renderOrders();
}

/* ---------- POS ---------- */
function renderPOS(){
  const grid = document.getElementById('pos-grid'); grid.innerHTML='';
  products.forEach(p=>{
    let badge = p.stock < 10 ? `<span class="stock-badge" style="background:var(--primary)">Low: ${p.stock}</span>` : `<span class="stock-badge">Stock: ${p.stock}</span>`;
    let img = p.img || 'https://via.placeholder.com/150?text=No+Image';
    grid.innerHTML += `
      <div class="item-card" onclick="addToCart(${p.id})" title="Add to cart">
        ${badge}
        <img src="${img}" alt="${p.name}" onerror="this.src='https://via.placeholder.com/150?text=No+Image'"/>
        <div style="padding-top:8px">
          <strong>${p.name}</strong><div class="muted">₱${p.price.toFixed(2)}</div>
        </div>
      </div>`;
  });
  updateCartUI();
}

function addToCart(id){
  const product = products.find(p=>p.id===id);
  if(!product || product.stock<=0) return alert('Out of stock!');
  const existing = cart.find(i=>i.id===id);
  if(existing){
    if(existing.qty < product.stock) existing.qty++;
    else return alert('Not enough stock!');
  } else {
    cart.push({...product,qty:1});
  }
  updateCartUI();
}

function removeFromCart(id){
  const idx = cart.findIndex(i=>i.id===id);
  if(idx>-1) cart.splice(idx,1);
  updateCartUI();
}

function updateCartUI(){
  const container = document.getElementById('cart-items'), totalEl = document.getElementById('cart-total');
  if(cart.length===0){ container.innerHTML = `<p class="muted" style="text-align:center">No items selected</p>`; totalEl.innerText='₱0.00'; return; }
  container.innerHTML='';
  let total=0;
  cart.forEach(item=>{
    let subtotal = item.price * item.qty; total += subtotal;
    container.innerHTML += `<div class="cart-item"><div><strong>${item.name}</strong><br><small>₱${item.price.toFixed(2)} x ${item.qty}</small></div><div style="display:flex;align-items:center"><strong>₱${subtotal.toFixed(2)}</strong><button class="btn-remove" onclick="removeFromCart(${item.id})">×</button></div></div>`;
  });
  totalEl.innerText = '₱' + total.toFixed(2);
}

function checkout(){
  if(cart.length===0) return alert('Cart is empty!');
  // Decrease stock
  cart.forEach(ci=>{ const p = products.find(x=>x.id===ci.id); if(p) p.stock -= ci.qty; });
  const orderId = 'ORD' + (Math.floor(Math.random()*9000)+1000);
  const date = new Date().toLocaleString();
  const total = cart.reduce((s,i)=>s + i.qty*i.price,0);
  const items = cart.map(i=>({id:i.id,name:i.name,qty:i.qty,price:i.price}));
  orders.unshift({id:orderId,customer:document.getElementById('pos-customer').value,date,items,total});
  // insert to history table
  renderOrders();
  alert('✅ Order Placed Successfully!');
  cart=[]; updateCartUI(); renderPOS(); renderInventory();
}

/* ---------- Inventory (Products) ---------- */
function renderInventory(){
  const tbody = document.getElementById('inventory-table'); tbody.innerHTML='';
  products.forEach(p=>{
    let img = p.img || 'https://via.placeholder.com/50';
    tbody.innerHTML += `
      <tr>
        <td><img src="${img}" class="table-thumb" onerror="this.src='https://via.placeholder.com/50'"></td>
        <td>${p.name}</td>
        <td>${p.cat}</td>
        <td>₱${p.price.toFixed(2)}</td>
        <td style="font-weight:700;color:${p.stock<10?'red':'green'}">${p.stock}</td>
        <td>
          <button class="btn btn-blue" onclick="openEdit(${p.id})">Edit</button>
          <button class="btn btn-red" onclick="deleteProduct(${p.id})">Delete</button>
        </td>
      </tr>`;
  });
}

/* Add / Edit product modal */
function openAddProduct(){
  document.getElementById('productModalTitle').innerText='Add New Product';
  document.getElementById('p-id').value='';
  document.getElementById('p-name').value='';
  document.getElementById('p-price').value='';
  document.getElementById('p-stock').value='';
  document.getElementById('p-img').value='';
  fillCategorySelect();
  document.getElementById('productModal').style.display='flex';
}
function openEdit(id){
  const p = products.find(x=>x.id===id);
  if(!p) return alert('Product not found');
  document.getElementById('productModalTitle').innerText='Edit Product';
  document.getElementById('p-id').value=p.id;
  document.getElementById('p-name').value=p.name;
  document.getElementById('p-price').value=p.price;
  document.getElementById('p-stock').value=p.stock;
  document.getElementById('p-img').value=p.img||'';
  fillCategorySelect(p.cat);
  document.getElementById('productModal').style.display='flex';
}
function closeProductModal(){ document.getElementById('productModal').style.display='none'; }

function fillCategorySelect(selected){
  const sel = document.getElementById('p-cat'); sel.innerHTML='';
  categories.forEach(c=>{ sel.innerHTML += `<option ${selected===c?'selected':''}>${c}</option>`; });
}
function saveProduct(){
  const id = document.getElementById('p-id').value;
  const name = document.getElementById('p-name').value.trim();
  const cat = document.getElementById('p-cat').value;
  const price = parseFloat(document.getElementById('p-price').value);
  const stock = parseInt(document.getElementById('p-stock').value);
  const img = document.getElementById('p-img').value || '';

  if(!name || isNaN(price) || isNaN(stock)) return alert('Please fill required fields!');
  if(id){
    const p = products.find(x=>x.id==id);
    p.name=name; p.cat=cat; p.price=price; p.stock=stock; p.img=img;
    alert('Product updated');
  } else {
    const newId = products.length? (products[products.length-1].id + 1) : 1;
    products.push({id:newId,name,cat,price,stock,img});
    alert('Product added');
  }
  closeProductModal(); renderInventory(); renderPOS();
}

function deleteProduct(id){
  if(!confirm('Delete this product permanently?')) return;
  products = products.filter(x=>x.id!==id);
  renderInventory(); renderPOS();
}

/* ---------- Categories ---------- */
function renderCategories(){
  const tbody = document.getElementById('category-table'); tbody.innerHTML='';
  categories.forEach((c,i)=>{
    tbody.innerHTML += `<tr><td>${c}</td><td><button class="btn btn-blue" onclick="renameCategory(${i})">Edit</button> <button class="btn btn-red" onclick="deleteCategory(${i})">Delete</button></td></tr>`;
  });
}
function addCategory(){
  const val = (document.getElementById('new-category').value || '').trim();
  if(!val) return alert('Enter category name');
  categories.push(val); document.getElementById('new-category').value=''; renderCategories(); fillCategorySelect();
}
function renameCategory(i){
  const newName = prompt('Rename category:', categories[i]);
  if(newName) { categories[i] = newName.trim(); renderCategories(); fillCategorySelect(); }
}
function deleteCategory(i){
  if(!confirm('Delete category?')) return;
  const cat = categories[i];
  categories.splice(i,1); renderCategories(); fillCategorySelect();
}

/* ---------- Orders ---------- */
function renderOrders(){
  const tbody = document.getElementById('order-history-table'); tbody.innerHTML='';
  orders.forEach(o=>{
    const itemsSummary = o.items.map(it=>`${it.qty}x ${it.name}`).join(', ');
    tbody.innerHTML += `<tr><td>${o.id}</td><td>${o.customer}</td><td>${o.date}</td><td>${itemsSummary}</td><td>₱${o.total.toFixed(2)}</td><td><button class="btn btn-blue" onclick="viewOrder('${o.id}')">View</button></td></tr>`;
  });
}
function viewOrder(id){
  const o = orders.find(x=>x.id===id);
  if(!o) return;
  let html='';
  o.items.forEach(it=> html += `<div><strong>${it.qty}× ${it.name}</strong> — ₱${(it.qty*it.price).toFixed(2)}</div>`);
  html += `<hr><div style="font-weight:700">Total: ₱${o.total.toFixed(2)}</div>`;
  document.getElementById('order-details').innerHTML = html;
  document.getElementById('orderModal').style.display='flex';
}
function closeOrderModal(){ document.getElementById('orderModal').style.display='none'; }

/* ---------- Init ---------- */
renderPOS(); renderInventory(); renderCategories(); renderOrders();
</script>
</body>
</html>


