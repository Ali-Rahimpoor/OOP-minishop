<div class="result">
    کل نتایج: <?= number_format($total) ?> | صفحه <?= $page ?> از <?= $lastPage ?>
</div>

<div class="pagination">

    <?php if ($page > 1): ?>
        <a href="<?= htmlspecialchars(pageLink($page - 1, $filters)) ?>" class="prev">قبلی</a>
    <?php endif; ?>

    <?php
    // یه بازه‌ی ساده: صفحه‌ی فعلی، دو تا قبل و دو تا بعدش
    $start = max(1, $page - 2);
    $end   = min($lastPage, $page + 2);
    ?>

    <?php if ($start > 1): ?>
        <a href="<?= htmlspecialchars(pageLink(1, $filters)) ?>">1</a>
        <?php if ($start > 2): ?>
            <span>...</span>
        <?php endif; ?>
    <?php endif; ?>

    <?php for ($i = $start; $i <= $end; $i++): ?>
        <?php if ($i === $page): ?>
            <span><?= $i ?></span>
        <?php else: ?>
            <a href="<?= htmlspecialchars(pageLink($i, $filters)) ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($end < $lastPage): ?>
        <?php if ($end < $lastPage - 1): ?>
            <span>...</span>
        <?php endif; ?>
        <a href="<?= htmlspecialchars(pageLink($lastPage, $filters)) ?>"><?= $lastPage ?></a>
    <?php endif; ?>

    <?php if ($page < $lastPage): ?>
        <a href="<?= htmlspecialchars(pageLink($page + 1, $filters)) ?>" class="next">بعدی</a>
    <?php endif; ?>

</div><!--.pagination-->