<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['login','signup']);
    }

    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    public function signup()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

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
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
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
        return $this->redirect($this->Auth->logout());
    }
}
