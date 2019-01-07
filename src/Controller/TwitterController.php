<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class TwitterController extends AppController
{
    public $components = ['Twitter'];
    

    public function beforeFilter(Event $event){
        $this->autoRender = false;
    }
    public function callback(){
        if($this->Twitter->initializeOnCallback()){
            $this->Flash->success(__('認証に成功しました。'));
        }else{
            $this->Flash->error(__('認証に失敗しました。'));
        }
        return $this->redirect(['controller' => 'users', 'action' => 'index']);
    }

    public function login(){
        if ($this->request->is('get')){
            if($this->Twitter->isAuthorized()){
                $this->Flash->error(__('すでにログインしています。'));
                return $this->redirect(['controller' => 'users', 'action' => 'index']);
            }else{
                return $this->redirect($this->Twitter->getAuthenticateUrl());
            }
        }
    }

    public function logout(){    
        if ($this->request->is('get')){
            $this->Twitter->clearSessionData();
            $this->Flash->success(__('認証を解除しました。'));
        }
        return $this->redirect(['controller' => 'users', 'action' => 'index']);
    }
}
