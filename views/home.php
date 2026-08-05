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
    if($_GET['action']==='register'){
      echo "<span class='alert'> ثبت نام با موفقیت انجام شد✅</span>";
    }
    if($_GET['action']==='login'){
      echo "<span class='alert'> ورود با موفقیت انجام شد✅</span>";
    }
    if($_GET['action']==='logout'){
      echo "<span class='alert'>خروج با موفقیت انجام شد ✅</span>";
    }
  } ?>
  <div class="box-container">
    <header>
      <div class="title">
        <?php if(is_login()):?>
          <h1>خوش آمدی <?= getUername(); ?></h1>
        <?php else:?>
          <h1>لیست محصولات</h1>
        <?php endif; ?>
        <a href="<?= site_url('register'); ?>">ثبت نام</a>
        <?php  if(is_login()): ?> 
        <form action="<?= site_url('logout') ?>" method="post">
            <button class="btn" type="submit">خروج</button>
        </form>
        <form action='<?= site_url('cart') ?>' method="get">
          <button class="btn" type="submit">سبد خرید</button>
        </form>
        <?php endif;?>

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