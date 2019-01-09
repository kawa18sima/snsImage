<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{
    public $components = ['Twitter'];

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['login','signup']);
    }

    public function view($id = null)
    {
        if($id != $this->Auth->user()['id']){
            $this->Flash->error(__('権限がありません'));
            return $this->redirect(['controller' => 'images', 'actions' => 'index']);
        }
        $this->loadModel('SnsAcounts');
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        $twitter_login = $this->SnsAcounts->find()->where(['user_id' => $id])->first();

        $this->set(compact('user', 'twitter_login'));
    }

    public function signup()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('登録に成功しました。'));

                return $this->redirect(['action' => 'login']);
            }else{
                $errors = $user->errors();
                foreach ($errors as $key => $error) {
                    foreach ($error as $error_messages) {
                        $this->Flash->error($error_messages, ['key' => $key]);
                    }
                    unset($key, $error_messages);
                }
            }
            $this->Flash->error(__('登録できませんでした。以下の内容に従い修正してください。'));
        }
        $this->set(compact('user'));
    }

    public function edit($id = null)
    {
        if($id != $this->Auth->user()['id']){
            $this->Flash->error(__('権限がありません'));
            return $this->redirect(['controller' => 'images', 'actions' => 'index']);
        }
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('変更に成功しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('変更にできませんでした。以下の内容に従い修正してください。'));
        }
        $this->set(compact('user'));
    }

    public function login(){
        if ($this->request->is('post')){
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $this->Flash->success(__('ログインしました'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('メールアドレスもしくはパスワードが間違っています'));
        }
    }

    public function logout(){
        $this->Flash->success(__('ログアウトしました。'));
        $this->Twitter->clearSessionData();
        return $this->redirect($this->Auth->logout());
    }
}
