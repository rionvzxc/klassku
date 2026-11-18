<?php function TemplatePagination($pagination) {
    $page = $pagination["page"];
    $lastPage = $pagination["last_page"];

    if ($lastPage <= 1) {
        return;
    }
    $pageUrl = fn($target) => htmlspecialchars("?".http_build_query(
        array_merge($pagination["filters"], ["page" => $target])
    ));
    $firstVisible = max(1, min($page - 2, $lastPage - 4));
    $lastVisible = min($lastPage, $firstVisible + 4);
    $pageNumbers = array_unique(array_merge([1], range($firstVisible, $lastVisible), [$lastPage]));
    $previousNumber = 0;
?>
<nav class="pagination" xflex="row" vstyle="gp-10 al-i5 pd-10 bg-1" aria-label="Halaman hasil">
    <div class="pagination-controls" xflex="row" vstyle="gp-5 al-i5">
        <?php if ($page > 1): ?>
            <a xbutton href="<?= $pageUrl($page - 1) ?>" rel="prev">Sebelumnya</a>
        <?php else: ?>
            <button type="button" disabled>Sebelumnya</button>
        <?php endif; ?>
        <?php foreach ($pageNumbers as $number): ?>
            <?php if ($number > $previousNumber + 1): ?>
                <span aria-hidden="true">…</span>
            <?php endif; ?>
            <?php if ($number === $page): ?>
                <span xbutton="bt-g" aria-current="page" aria-label="Halaman <?= $number ?>"><?= $number ?></span>
            <?php else: ?>
                <a xbutton href="<?= $pageUrl($number) ?>" aria-label="Halaman <?= $number ?>"><?= $number ?></a>
            <?php endif; ?>
            <?php $previousNumber = $number; ?>
        <?php endforeach; ?>
        <?php if ($page < $lastPage): ?>
            <a xbutton href="<?= $pageUrl($page + 1) ?>" rel="next">Berikutnya</a>
        <?php else: ?>
            <button type="button" disabled>Berikutnya</button>
        <?php endif; ?>
    </div>
    <div xline-vr aria-hidden="true"></div>
    <span>Halaman <?= $page ?> dari <?= $lastPage ?></span>
</nav>
<?php } ?>
