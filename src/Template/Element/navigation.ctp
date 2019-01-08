<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li><?= $this->Form->postLink(__('写真の同期'), ['controller' => 'images' ,'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('ユーザー編集'), ['controller' => 'users' ,'action' => 'view', $user['id']]) ?></li>
    </ul>
</nav>
