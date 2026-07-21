<div class="table-filter">
      <div class="filter">
        <label for="search">جستجو</label>
        <input type="search" id="search" name="search" placeholder="جستجو" value="" class="form-control">
      </div>
      <div class="filter">
        <label for="status">وضعیت</label>
        <select name="status" id="status" class="form-control">
          <option value=""> - همه - </option>
          <option value="publish">درحال فروش</option>
          <option value="expire">توقف فروش</option>
          <option value="draft">پیش نویس</option>
          <option value="preslae">پیشفروش</option>
        </select>
      </div>
      <div class="filter filter-price">
        <label for="search">قیمت</label>
        <div>
          از
          <input type="search" name="price_from" placeholder="از" value="" class="form-control">
          تا
          <input type="search" name="price_to" placeholder="تا" value="" class="form-control">
        </div>
      </div>
      <div class="filter btn-filter">
        <button class="btn btn-primary ">
          فیلتر کردن
        </button>
      </div>
    </div><!--.table-filter-->