<?php
if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?php echo htmle($_SESSION['success']); ?>
  </div>
  <?php unset($_SESSION['success']);
endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?php echo htmle($_SESSION['error']); ?>
  </div>
  <?php unset($_SESSION['error']);
endif; ?>

<?php if (isset($_SESSION['cart_errors']) && is_array($_SESSION['cart_errors'])): ?>
  <div class="alert alert-danger">
    <ul>
      <?php foreach ($_SESSION['cart_errors'] as $cartError): ?>
        <li><?php echo htmle($cartError); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php unset($_SESSION['cart_errors']);
endif; ?>