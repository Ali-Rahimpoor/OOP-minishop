<?php
/**
 * @var array $products
 */
?>
<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>لیست محصولات</title>  
  <link rel="stylesheet" href="<?php echo   'http://localhost/oop-minishop/public/assets/css/style.css' ;?>">
</head>
<body>
  <?php if(isset($_GET['action'])){
    if($_GET['action']==='deleted'){
      echo "<span class='alert'> محصول با موفقیت حذف شد✅</span>";
    }
  } ?>
  <div class="box-container">
    <header>
      <div class="title">
        <h1>لیست محصولات</h1>
        <?php if(is_admin()): ?>
          <form action="<?= site_url('logout') ?>" method="post">
            <button type="submit">خروج</button>
          </form>
        <?php endif; ?>
        <p>از این بخش میتوانید محصولات فعلی را ویرایش یا محصول جدید ثبت کنید</p>
      </div>
      <div class="table-button">
        <a href="<?php echo site_url('product'); ?>" class="btn btn-secondary">
          + ثبت محصول جدید
        </a>
      </div>
    </header>
    <?php require BASE_PATH . '/views/products/partials/filters.php'; ?>
    <?php require BASE_PATH . '/views/products/partials/table.php'; ?>

    <div class="table-footer">
     <?php require BASE_PATH . "/views/components/paginate.php" ?>
    </div><!--.table-footer-->

  </div><!--.table-container-->
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>