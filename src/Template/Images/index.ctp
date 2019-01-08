<?php if(!isset($images)): ?>
<div class="images index large-9 medium-8 columns content">
    <h3><?= __('写真一覧') ?></h3>
    <div id="aniimated-thumbnials">
        <?php foreach ($images as $image): ?>
            <a href="<?= $image->image_src?>">
                <img src="<?= $image->image_src ?>">
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
<?php else: ?>
    <h1>まだ写真がありません</h1>
<?php endif ?>
