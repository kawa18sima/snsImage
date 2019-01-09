<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class TwitterController extends AppController
{
    public $components = ['Twitter'];
    

    public function beforeFilter(Event $event){
        $this->autoRender = false;
        $this->loadModel('SnsAcount');
        $current_user = $this->Auth->user();
    }
    
    public function callback(){
        
        if($this->Twitter->initializeOnCallback()){
            $acount = $this->SnsAcount->newEntity([
                'acount_id' => $this->Twitter->getUserId(),
                'sns' => 'twitter',
                'user_id' => $current_user['id']
            ]);
            $this->SnsAcount->save($acount);
            $this->Flash->success(__('認証に成功しました。'));
        }else{
            $this->Flash->error(__('認証に失敗しました。'));
        }
        return $this->redirect(['controller' => 'images', 'action' => 'index']);
    }

    public function login(){
        if ($this->request->is('get')){
            if($this->SnsAcount->find()->where(['user_id' => $current_user['id'], 'sns' => 'twitter'])->count() > 0){
                $this->Flash->error(__('すでにログインしています。'));
                return $this->redirect(['controller' => 'images', 'action' => 'index']);
            }else{
                return $this->redirect($this->Twitter->getAuthenticateUrl());
            }
        }
    }
}
