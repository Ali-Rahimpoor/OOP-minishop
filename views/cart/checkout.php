<?php
/**
 * @var array $items    لیست آیتم‌های سبد خرید (اعتبارسنجی‌شده)
 * @var int   $subtotal جمع کل قیمت سبد (به ریال)
 */
?>
<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>نهایی کردن سفارش</title>
  <link rel="stylesheet" href="<?php echo 'http://localhost/oop-minishop/public/assets/css/style.css'; ?>">
  <style>
    /* ===== RESET & BASE ===== */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: 'Yekan', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        direction: rtl;
      }

      /* ===== CONTAINER ===== */
      .box-container {
        max-width: 900px;
        width: 100%;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        padding: 40px;
        transition: all 0.3s ease;
        animation: slideUp 0.6s ease-out;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* ===== HEADER ===== */
      header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #f0f2f5;
      }

      .title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
      }

      .title h1 {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .title a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        padding: 8px 20px;
        border: 2px solid #667eea;
        border-radius: 25px;
        transition: all 0.3s ease;
        font-size: 14px;
        background: transparent;
        -webkit-text-fill-color: #667eea;
      }

      .title a:hover {
        background: #667eea;
        color: #fff;
        -webkit-text-fill-color: #fff;
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
      }

      /* ===== HEADINGS ===== */
      h3 {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin: 30px 0 15px 0;
        padding-right: 15px;
        border-right: 4px solid #667eea;
        line-height: 1.4;
      }

      h3:first-of-type {
        margin-top: 0;
      }

      /* ===== TABLE ===== */
      .products-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 15px 0 20px 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      }

      .products-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
      }

      .products-table th {
        padding: 15px 20px;
        text-align: right;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.5px;
      }

      .products-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #f0f2f5;
        color: #2c3e50;
        font-size: 15px;
      }

      .products-table tbody tr {
        transition: background-color 0.3s ease;
      }

      .products-table tbody tr:hover {
        background-color: #f8f9ff;
      }

      .products-table tbody tr:last-child td {
        border-bottom: none;
      }

      .products-table tbody tr:nth-child(even) {
        background-color: #fafbfc;
      }

      .products-table tbody tr:nth-child(even):hover {
        background-color: #f8f9ff;
      }

      /* ===== TOTAL PRICE ===== */
      h3:last-of-type {
        font-size: 24px;
        color: #2c3e50;
        border-right-color: #f39c12;
        padding: 15px 15px;
        background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
        border-radius: 12px;
        margin-top: 5px;
        display: inline-block;
        border-right: 4px solid #f39c12;
      }

      /* ===== FORM ===== */
      form {
        margin-top: 25px;
        background: #fafbfc;
        padding: 30px;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
      }

      label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 14px;
      }

      input[type="text"],
      textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e4e8;
        border-radius: 10px;
        font-size: 15px;
        font-family: inherit;
        transition: all 0.3s ease;
        background: #fff;
        color: #2c3e50;
        margin-bottom: 18px;
        direction: rtl;
      }

      input[type="text"]:focus,
      textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
      }

      textarea {
        resize: vertical;
        min-height: 100px;
      }

      /* ===== BUTTON ===== */
      .btn {
        display: inline-block;
        padding: 14px 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
      }

      .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      }

      .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
      }

      /* ===== ALERT COMPONENT (if used) ===== */
      .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
        border-right: 4px solid;
      }

      .alert-success {
        background: #e8f5e9;
        border-color: #4caf50;
        color: #2e7d32;
      }

      .alert-danger {
        background: #ffebee;
        border-color: #f44336;
        color: #c62828;
      }

      .alert-warning {
        background: #fff3e0;
        border-color: #ff9800;
        color: #e65100;
      }

      /* ===== RESPONSIVE ===== */
      @media (max-width: 768px) {
        .box-container {
          padding: 20px;
          border-radius: 15px;
        }

        .title h1 {
          font-size: 22px;
        }

        .title a {
          font-size: 13px;
          padding: 6px 16px;
        }

        .products-table {
          font-size: 14px;
        }

        .products-table th,
        .products-table td {
          padding: 10px 12px;
        }

        h3 {
          font-size: 18px;
        }

        h3:last-of-type {
          font-size: 20px;
        }

        form {
          padding: 20px;
        }

        input[type="text"],
        textarea {
          font-size: 14px;
          padding: 10px 14px;
        }

        .btn {
          padding: 12px 30px;
          font-size: 15px;
        }
      }

      @media (max-width: 480px) {
        .box-container {
          padding: 15px;
        }

        .title {
          flex-direction: column;
          align-items: stretch;
          text-align: center;
        }

        .title h1 {
          font-size: 20px;
        }

        .title a {
          text-align: center;
        }

        .products-table {
          font-size: 13px;
          display: block;
          overflow-x: auto;
        }

        .products-table th,
        .products-table td {
          padding: 8px 10px;
          white-space: nowrap;
        }

        h3:last-of-type {
          font-size: 18px;
          display: block;
          text-align: center;
        }

        .btn {
          font-size: 14px;
          padding: 12px 20px;
        }
      }

      /* ===== SCROLLBAR ===== */
      ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
      }

      ::-webkit-scrollbar-track {
        background: #f0f2f5;
        border-radius: 10px;
      }

      ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
      }

      ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4296 100%);
      }
  </style>
</head>
<body>
  <div class="box-container">
    <header>
      <div class="title">
        <h1>نهایی کردن سفارش</h1>
        <a href="<?= site_url('cart'); ?>">بازگشت به سبد خرید</a>
      </div>
    </header>

    <?php require BASE_PATH . '/views/components/alert.php'; ?>

    <h3>خلاصه‌ی سفارش</h3>
    <table class="products-table">
      <thead>
        <tr>
          <th>محصول</th>
          <th>تعداد</th>
          <th>قیمت کل</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?php echo htmle($item->title); ?></td>
            <td><?php echo htmle((string) $item->quantity); ?></td>
            <td><?php echo number_format(toToman($item->quantity * $item->unit_price)); ?> تومان</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <h3>جمع کل: <?php echo number_format(toToman($subtotal)); ?> تومان</h3>

    <h3>اطلاعات گیرنده</h3>
    <form action="<?= site_url('cart/place-order'); ?>" method="post">
      <label for="receiver_name">نام گیرنده</label>
      <input type="text" name="receiver_name" id="receiver_name" required>

      <label for="receiver_mobile">شماره موبایل گیرنده</label>
      <input type="text" name="receiver_mobile" id="receiver_mobile" maxlength="11" required>

      <label for="address">آدرس کامل</label>
      <textarea name="address" id="address" rows="4" required></textarea>

      <button type="submit" class="btn btn-primary">ثبت نهایی سفارش</button>
    </form>

  </div><!--.box-container-->
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>