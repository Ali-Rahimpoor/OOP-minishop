<?php
/**
 * IF EDIT => @var Product $product
 */
$is_edit = isset($product);
$status = $_POST['status'] ?? ($is_edit ? $product->status : "draft");
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $is_edit ? 'ویرایش' : 'ثبت'; ?> محصول</title>    
    <link rel="stylesheet" href="<?= site_url('public/assets/css/style.css') ?>">
</head>
<body>
<div class="box-container">
    <div>
        <a href="<?= site_url('/'); ?>">برگشت</a>
        <span>
        <?= isset($_GET['action']) && $_GET['action']=='edited'? "محصول با موفقیت ادیت شد ✅" : ""; ?>
        </span>
    </div>
    <header>
        <div class="title">
            <h1><?= $is_edit ? 'ویرایش' : 'ثبت'; ?> محصول</h1>
            <p>از این بخش میتوانید محصولات فعلی را ویرایش یا محصول جدید ثبت کنید</p>
        </div>
    </header>

    <form
        action="<?= $is_edit ? site_url('products/' . $product->id) : site_url('products/store'); ?>"
        id="product-register"
        method="POST"
        enctype="multipart/form-data"
    >
        <div class="form-right">
            <div class="form-group">
                <label for="title">نام محصول</label>
                <input
                    value="<?= $is_edit ? htmlspecialchars($product->title) : ''; ?>"
                    type="text"
                    name="title"
                    id="title"
                    class="form-control"
                    placeholder="نام محصول"
                >
            </div>

            <div class="form-group">
                <label for="description">توضیحات</label>
                <textarea
                    rows="15"
                    name="description"
                    id="description"
                    class="form-control"
                    placeholder="توضیحات محصول"
                ><?= $is_edit ? htmlspecialchars($product->description) : ''; ?></textarea>
            </div>
        </div>

        <div class="form-side">
            <div class="form-group">
                <label for="thumbnail">
                    <?= $is_edit ? 'تغییر تصویر محصول' : 'تصویر شاخص محصول'; ?>
                </label>

                <input
                    type="file"
                    id="thumbnail"
                    name="thumbnail"
                    accept="image/jpeg,image/png"
                    <?= !$is_edit ? 'required' : ''; ?>
                >
            </div>

            <?php
            $thumbnail = $is_edit && !empty($product->thumbnail)
                ? site_url('storage/uploads/' . $product->thumbnail)
                : site_url('storage/uploads/no-product.jpg');
            ?>

            <img
                src="<?= $thumbnail; ?>"
                alt="تصویر محصول"
                class="thumbnail-preview"
            >

            <div class="form-group">
                <label for="price">قیمت</label>
                <input
                    type="number"
                    name="price"
                    id="price"
                    class="form-control"
                    min="0"
                    step="1"
                    value="<?= $is_edit ? $product->price : 0; ?>"
                >
            </div>

            <div class="form-group">
                <label for="sale_price">قیمت فروش</label>
                <input
                    type="number"
                    name="sale_price"
                    id="sale_price"
                    class="form-control"
                    min="0"
                    step="1"
                    value="<?= $is_edit ? $product->sale_price : 0; ?>"
                >
            </div>

            <div class="form-group">
                <label for="stock">موجودی انبار</label>
                <input
                    type="number"
                    name="stock"
                    id="stock"
                    class="form-control"
                    min="0"
                    step="1"
                    value="<?= $is_edit ? $product->stock : 0; ?>"
                >
            </div>

            <div class="form-group">
                <label for="status">وضعیت</label>
                <select name="status" id="status" class="form-control">
                    <option value="publish" <?= $status === 'publish' ? 'selected' : ''; ?>>
                        انتشار و فروش
                    </option>

                    <option value="draft" <?= $status === 'draft' ? 'selected' : ''; ?>>
                        پیش نویس
                    </option>

                    <option value="presale" <?= $status === 'presale' ? 'selected' : ''; ?>>
                        پیش فروش
                    </option>
                 </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <?= $is_edit ? 'ذخیره تغییرات' : 'ثبت محصول'; ?>
            </button>
        </div>
    </form>
</div>

<script src="<?= site_url('public/assets/js/jquery-3.7.1.min.js') ?>"></script>
<script src="<?= site_url('public/assets/js/script.js') ?>"></script>
</body>
</html>