<?php
/**
 * @var array $items    لیست آیتم‌های سبد خرید (stdClass با id, product_id, quantity, unit_price, title, thumbnail)
 * @var int   $subtotal جمع کل قیمت سبد (به ریال)
 */
?>
<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>سبد خرید</title>
  <link rel="stylesheet" href="<?php echo 'http://localhost/oop-minishop/public/assets/css/style.css'; ?>">
  <style>
         /* --------------------------------------
         Root Variables & Reset
      -------------------------------------- */
      :root {
      --primary-color: #2c3e50;
      --secondary-color: #3498db;
      --accent-color: #e74c3c;
      --success-color: #27ae60;
      --warning-color: #f39c12;
      --light-bg: #f8f9fa;
      --border-color: #e0e0e0;
      --text-color: #333;
      --text-light: #777;
      --white: #fff;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --radius: 8px;
      --transition: all 0.3s ease;
      --font-family: 'Tahoma', 'Vazir', 'Segoe UI', sans-serif;
      }

      * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      }

      body {
      font-family: var(--font-family);
      background: #f0f2f5;
      color: var(--text-color);
      direction: rtl;
      text-align: right;
      padding: 40px 20px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      }

      /* --------------------------------------
         Main Container
      -------------------------------------- */
      .box-container {
      max-width: 1100px;
      width: 100%;
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 30px 35px 40px;
      transition: var(--transition);
      }

      /* --------------------------------------
         Header
      -------------------------------------- */
      header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
      border-bottom: 2px solid var(--border-color);
      padding-bottom: 18px;
      margin-bottom: 25px;
      }

      .title {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
      }

      .title h1 {
      font-size: 26px;
      font-weight: 700;
      color: var(--primary-color);
      letter-spacing: -0.5px;
      }

      .title h1::before {
      content: "🛒 ";
      font-size: 26px;
      }

      .title a {
      display: inline-block;
      background: var(--secondary-color);
      color: var(--white);
      padding: 8px 18px;
      border-radius: 30px;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      transition: var(--transition);
      }

      .title a:hover {
      background: #217dbb;
      transform: translateX(-3px);
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
      }

      /* --------------------------------------
         Alert Component
      -------------------------------------- */
      .alert {
      padding: 14px 20px;
      border-radius: var(--radius);
      margin-bottom: 20px;
      font-size: 15px;
      font-weight: 500;
      border-right: 5px solid transparent;
      }

      .alert-success {
      background: #d4edda;
      border-right-color: var(--success-color);
      color: #155724;
      }

      .alert-danger {
      background: #f8d7da;
      border-right-color: var(--accent-color);
      color: #721c24;
      }

      .alert-warning {
      background: #fff3cd;
      border-right-color: var(--warning-color);
      color: #856404;
      }

      .alert-info {
      background: #d1ecf1;
      border-right-color: var(--secondary-color);
      color: #0c5460;
      }

      /* --------------------------------------
         Empty Cart Message
      -------------------------------------- */
      .box-container > p:first-of-type {
      text-align: center;
      font-size: 20px;
      color: var(--text-light);
      padding: 60px 20px;
      background: var(--light-bg);
      border-radius: var(--radius);
      border: 2px dashed var(--border-color);
      }

      /* --------------------------------------
         Products Table
      -------------------------------------- */
      .products-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px;
      font-size: 15px;
      }

      .products-table thead {
      background: var(--primary-color);
      color: var(--white);
      }

      .products-table th {
      padding: 14px 12px;
      text-align: right;
      font-weight: 600;
      font-size: 14px;
      letter-spacing: 0.3px;
      }

      .products-table th:last-child {
      text-align: center;
      }

      .products-table td {
      padding: 16px 12px;
      border-bottom: 1px solid var(--border-color);
      vertical-align: middle;
      background: var(--white);
      }

      .products-table tbody tr {
      transition: var(--transition);
      }

      .products-table tbody tr:hover {
      background: #fafbfc;
      }

      .products-table tbody tr:last-child td {
      border-bottom: none;
      }

      /* ---- Product column (flex row) ---- */
      .table-flex-col {
      display: flex;
      align-items: center;
      gap: 14px;
      }

      .product-thumbnail {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
      background: var(--light-bg);
      flex-shrink: 0;
      transition: var(--transition);
      }

      .product-thumbnail:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
      }

      .product-title {
      font-weight: 500;
      color: var(--primary-color);
      line-height: 1.4;
      margin: 0;
      font-size: 15px;
      word-break: break-word;
      }

      /* ---- Price columns ---- */
      .products-table td:nth-child(2),
      .products-table td:nth-child(4) {
      font-weight: 500;
      color: var(--text-color);
      white-space: nowrap;
      }

      .products-table td:nth-child(4) {
      color: var(--primary-color);
      font-weight: 600;
      }

      /* ---- Quantity Form ---- */
      .cart-quantity-form {
      display: flex;
      align-items: center;
      gap: 8px;
      direction: ltr;
      }

      .cart-quantity-form input[type="number"] {
      width: 70px;
      padding: 6px 8px;
      border: 2px solid var(--border-color);
      border-radius: var(--radius);
      font-size: 14px;
      font-weight: 500;
      text-align: center;
      transition: var(--transition);
      background: var(--white);
      color: var(--text-color);
      font-family: var(--font-family);
      }

      .cart-quantity-form input[type="number"]:focus {
      outline: none;
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
      }

      .cart-quantity-form input[type="number"]::-webkit-inner-spin-button,
      .cart-quantity-form input[type="number"]::-webkit-outer-spin-button {
      opacity: 1;
      height: 30px;
      }

      .cart-quantity-form button[type="submit"] {
      background: var(--secondary-color);
      color: var(--white);
      border: none;
      padding: 6px 14px;
      border-radius: var(--radius);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      font-family: var(--font-family);
      white-space: nowrap;
      }

      .cart-quantity-form button[type="submit"]:hover {
      background: #217dbb;
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
      }

      .cart-quantity-form button[type="submit"]:active {
      transform: translateY(0);
      }

      /* ---- Delete Button ---- */
      .btn-icon.delete-product {
      background: var(--accent-color);
      color: var(--white);
      border: none;
      padding: 6px 16px;
      border-radius: var(--radius);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      font-family: var(--font-family);
      white-space: nowrap;
      }

      .btn-icon.delete-product:hover {
      background: #c0392b;
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
      }

      .btn-icon.delete-product:active {
      transform: translateY(0);
      }

      /* ---- Actions column ---- */
      .products-table td:last-child {
      text-align: center;
      min-width: 80px;
      }

      .products-table td:last-child form {
      display: inline-block;
      }

      /* --------------------------------------
         Cart Summary
      -------------------------------------- */
      .cart-summary {
      display: flex;
      justify-content: flex-end;
      margin-top: 30px;
      padding-top: 22px;
      border-top: 2px solid var(--border-color);
      }

      .cart-summary h3 {
      font-size: 22px;
      font-weight: 700;
      color: var(--primary-color);
      background: var(--light-bg);
      padding: 12px 28px;
      border-radius: var(--radius);
      border-right: 5px solid var(--secondary-color);
      letter-spacing: 0.5px;
      }

      .cart-summary h3::after {
      content: " 💰";
      font-size: 20px;
      }

      /* --------------------------------------
         Responsive Design
      -------------------------------------- */

      /* Tablets & small laptops */
      @media (max-width: 992px) {
      .box-container {
         padding: 20px 18px;
      }

      .products-table {
         font-size: 14px;
      }

      .products-table th,
      .products-table td {
         padding: 12px 8px;
      }

      .product-thumbnail {
         width: 55px;
         height: 55px;
      }

      .title h1 {
         font-size: 22px;
      }

      .cart-quantity-form input[type="number"] {
         width: 60px;
         font-size: 13px;
      }
      }

      /* Mobile devices */
      @media (max-width: 768px) {
      body {
         padding: 15px 8px;
      }

      .box-container {
         padding: 16px 12px;
      }

      header {
         flex-direction: column;
         align-items: stretch;
         gap: 10px;
      }

      .title {
         flex-direction: column;
         align-items: stretch;
         gap: 10px;
      }

      .title h1 {
         font-size: 20px;
         text-align: center;
      }

      .title a {
         text-align: center;
         padding: 10px;
         font-size: 15px;
      }

      /* ---- Make table scrollable horizontally ---- */
      .products-table {
         display: block;
         overflow-x: auto;
         -webkit-overflow-scrolling: touch;
         font-size: 13px;
      }

      .products-table thead,
      .products-table tbody,
      .products-table tr {
         display: table;
         width: 100%;
         min-width: 650px;
      }

      .products-table th,
      .products-table td {
         padding: 10px 6px;
      }

      .product-thumbnail {
         width: 45px;
         height: 45px;
      }

      .table-flex-col {
         gap: 8px;
      }

      .product-title {
         font-size: 13px;
         max-width: 120px;
         overflow: hidden;
         text-overflow: ellipsis;
      }

      .cart-quantity-form {
         flex-direction: column;
         gap: 5px;
      }

      .cart-quantity-form input[type="number"] {
         width: 55px;
         padding: 4px 6px;
         font-size: 12px;
      }

      .cart-quantity-form button[type="submit"],
      .btn-icon.delete-product {
         padding: 4px 10px;
         font-size: 11px;
      }

      .cart-summary {
         justify-content: center;
         margin-top: 20px;
      }

      .cart-summary h3 {
         font-size: 18px;
         padding: 10px 20px;
         text-align: center;
         width: 100%;
      }

      .box-container > p:first-of-type {
         font-size: 17px;
         padding: 40px 15px;
      }
      }

      /* Very small screens */
      @media (max-width: 480px) {
      .box-container {
         padding: 12px 8px;
      }

      .title h1 {
         font-size: 18px;
      }

      .products-table th,
      .products-table td {
         padding: 8px 4px;
         font-size: 12px;
      }

      .product-thumbnail {
         width: 38px;
         height: 38px;
      }

      .product-title {
         font-size: 12px;
         max-width: 80px;
      }

      .cart-summary h3 {
         font-size: 16px;
         padding: 8px 14px;
      }

      .cart-quantity-form input[type="number"] {
         width: 45px;
         font-size: 11px;
      }

      .cart-quantity-form button[type="submit"],
      .btn-icon.delete-product {
         font-size: 10px;
         padding: 3px 8px;
      }
      }

      /* --------------------------------------
         Additional Enhancements
      -------------------------------------- */

      /* Smooth loading state for buttons */
      button[type="submit"] {
      position: relative;
      }

      button[type="submit"]:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      }

      /* Quantity input - hide arrows on Firefox */
      .cart-quantity-form input[type="number"] {
      -moz-appearance: textfield;
      }

      /* Focus visible for accessibility */
      button:focus-visible,
      input:focus-visible {
      outline: 2px solid var(--secondary-color);
      outline-offset: 2px;
      }

      /* Print styles */
      @media print {
      .box-container {
         box-shadow: none;
         border: 1px solid #ddd;
      }
      
      .cart-quantity-form button,
      .btn-icon.delete-product {
         display: none;
      }
      
      .products-table tbody tr:hover {
         background: var(--white);
      }
      }
  </style>
</head>
<body>
  <div class="box-container">
    <header>
      <div class="title">
        <h1>سبد خرید</h1>
        <a href="<?= site_url(''); ?>">بازگشت به فروشگاه</a>
      </div>
    </header>

    <?php require BASE_PATH . '/views/components/alert.php'; ?>

    <?php if (empty($items)): ?>

      <p>سبد خرید شما خالی است.</p>

    <?php else: ?>

      <table class="products-table">
        <thead>
          <tr>
            <th>محصول</th>
            <th>قیمت واحد</th>
            <th>تعداد</th>
            <th>قیمت کل</th>
            <th>عملیات</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td>
                <div class="table-flex-col">
                  <img class="product-thumbnail" src="<?php echo product_img_src($item->thumbnail); ?>" alt="">
                  <p class="product-title"><?php echo htmle($item->title); ?></p>
                </div>
              </td>
              <td><?php echo number_format(toToman($item->unit_price)); ?> تومان</td>
              <td>
                <form action="<?= site_url('cart/update/' . $item->id); ?>" method="post" class="cart-quantity-form">
                  <input type="number" name="quantity" min="1" value="<?php echo htmle((string) $item->quantity); ?>">
                  <button type="submit">ثبت</button>
                </form>
              </td>
              <td><?php echo number_format(toToman($item->quantity * $item->unit_price)); ?> تومان</td>
              <td>
                <form action="<?= site_url('cart/remove/' . $item->id); ?>" method="post">
                  <button type="submit" class="btn-icon delete-product">حذف</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="cart-summary">
        <h3>جمع کل: <?php echo number_format(toToman($subtotal)); ?> تومان</h3>
        <form action="<?= site_url('cart/checkout'); ?>" method="post">
          <button type="submit" class="btn btn-primary">نهایی کردن سفارش</button>
        </form>
      </div>

    <?php endif; ?>

  </div><!--.box-container-->
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>