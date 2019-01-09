<?php if($images->count() > 0): ?>
<div class="images index large-9 medium-8 columns content">
    <h3><?= __('写真一覧') ?></h3>
    <div id="aniimated-thumbnials">
        <?php foreach ($images as $image): ?>
            <a href="<?= $image->image_src?>">
                <img src="<?= $image->image_src ?>">
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
    <h1>まだ写真がありません</h1>
<?php endif ?>

