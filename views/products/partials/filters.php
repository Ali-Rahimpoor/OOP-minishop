<form method="get" action="<?= site_url('') ?>" class="table-filter">
      <div class="filter">
        <label for="search">جستجو</label>
        <input type="search" id="search" name="search" placeholder="جستجو" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" class="form-control">
      </div>
      <div class="filter">
        <label for="status">وضعیت</label>
        <select name="status" id="status" class="form-control">
          <option value=""> - همه - </option>
          <?php foreach(['publish'=>'درحال فروش','expire'=>'توقف فروش','draft'=>'پیش نویس','presale'=>'پیشفروش'] as $value => $label): ?>
            <option value="<?= $value ?>" <?=( $filters['status'] ?? '') === $value ? 'selected' : '' ?>>
              <?= $label ?>
            </option>
            <?php endforeach;  ?>
        </select>
      </div>
      <div class="filter filter-price">
        <label for="search">قیمت</label>
        <div>
          از
          <input type="search" name="price_from" placeholder="از"    
          value="<?= htmlspecialchars($filters['price_from'] ?? '') ?>" 
          class="form-control">
          تا
          <input type="search" name="price_to" placeholder="تا" value="<?= htmlspecialchars($filters['price_to'] ?? '') ?>" 
           class="form-control">
        </div>
      </div>
      <div class="filter btn-filter">
        <button type="submit" class="btn btn-primary ">
          فیلتر کردن
        </button>
      </div>
</form><!--.table-filter-->