<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('ユーザ編集'), ['action' => 'edit', $user->id]) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('名前') ?></th>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('メールアドレス') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('パスワード') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('ツイッター認証') ?></th>
            <td>
                <?php if(!$twitter_login): ?>
                    <?= $this->Html->link(__('ツイッターでログインする'), ['controller' => 'twitter' ,'action' => 'login']) ?>
                <?php else: ?>
                    <?= $this->Html->link(__('認証を解除する'), ['controller' => 'twitter' ,'action' => 'logout']) ?>
                <?php endif ?>
            </td>
        </tr>
    </table>
</div>
