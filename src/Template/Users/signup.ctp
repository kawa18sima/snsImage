<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('ユーザ作成') ?></legend>
        <?php
            echo $this->Form->control('name', [
                'label' => '名前'
            ]);
            echo $this->Form->control('email', [
                'label' => 'メールアドレス'
            ]);
            echo $this->Form->control('password', [
                'label' => 'パスワード'
            ]);
            echo $this->Form->control('password_confirm',[
                'type' => 'password',
                'label' => 'パスワード（確認）',
                'required' => true
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('登録')) ?>
    <?= $this->Form->end() ?>
</div>
