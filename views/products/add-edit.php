<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ثبت/ویرایش محصول</title>
    <link rel="stylesheet" href="https://dl.daneshjooyar.com/mvie/Moodi_Hamed/assets/css/font-yekanbakh-vf.css">
    <link rel="stylesheet" href="<?= site_url('public/assets/css/style.css')?>">
</head>
<body>
    <div class="box-container">
        <header>
            <div class="title">
                <h1>ثبت/ویرایش محصول</h1>
                <p>از این بخش میتوانید محصولات فعلی را ویرایش یا محصول جدید ثبت کنید</p>
            </div>
        </header>
         <?php if(1==2):  ?>                              
        <div class="message error">
            نام محصول تکراری می باشد
        </div>
        <div class="message success">
            ویرایش محصول با موفقیت انجام شد
        </div>
        <?php endif; ?>

        <form 
        action="<?php echo site_url('/products/store'); ?>" id="product-register"
        method = "POST"
        enctype="multipart/form-data"
        >
            <div class="form-right">
                <div class="form-group">
                    <label for="title">نام محصول</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="نام محصول">
                </div>
                <div class="form-group">
                    <label for="description">توضیحات</label>
                    <textarea rows="15" name="description" id="description" class="form-control" placeholder="توضیحات محصول"></textarea>
                </div>
            </div>
            <div class="form-side">
                <div class="form-group">
                    <label for="thumbnail">تصویر شاخص محصول</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png">
                </div>
                <img src="<?php echo site_url('/storage/uploads/no-product.jpg') ?>"
                     alt="" class="thumbnail-preview">
                <div class="form-group">
                    <label for="price">قیمت</label>
                    <input type="number" name="price" id="price" class="form-control" value="0" step="1">
                </div>
                <div class="form-group">
                    <label for="sale_price">قیمت فروش</label>
                    <input type="number" name="sale_price" id="sale_price" class="form-control" min="0" step="1" value="0">
                </div>
                <div class="form-group">
                    <label for="stock">موجودی انبار</label>
                    <input type="number" name="stock" id="stock" class="form-control" min="0" step="1" >
                </div>
                <div class="form-group">
                    <label for="status">وضعیت</label>
                    <select name="status" id="status" class="form-control">
                        <option value="publish">انتشار و فروش</option>
                        <option value="draft">پیش نویس</option>
                        <option value="presale">پیشفروش</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100">
                    ثبت محصول/ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
    <script src="<?php echo site_url('public/assets/js/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?php echo site_url('public/assets/js/script.js')?>"></script>
</body>
</html>