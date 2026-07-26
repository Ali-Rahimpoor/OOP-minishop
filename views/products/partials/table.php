<table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= sortLinks('title', 'محصول', $filters) ?></th>
          <th><?= sortLinks('price', 'قیمت', $filters) ?></th>
          <th><?= sortLinks('discount', 'تخفیف', $filters) ?></th>
          <th>موجودی</th>
          <th style="width: 110px;">وضعیت</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </thead>
      <tbody>      
        <?php foreach($products as $key => $product): ?>
          <?php include BASE_PATH . "/views/products/partials/table-row.php";  ?>
        <?php endforeach; ?>
        <!--<tr>
          <td colspan="6">
            نتیجه ای یافت نشد
          </td>
        </tr>-->

      </tbody>
    </table>