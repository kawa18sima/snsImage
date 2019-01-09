<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class TwitterController extends AppController
{
    public $components = ['Twitter'];
    

    public function beforeFilter(Event $event){
        $this->autoRender = false;
        $this->loadModel('SnsAcounts');
    }
    
    public function callback(){
        
        if($this->Twitter->initializeOnCallback()){
            $acount = $this->SnsAcounts->newEntity([
                'acount_id' => $this->Twitter->getUserId(),
                'sns' => 'twitter',
                'user_id' => $this->Auth->user()['id']
            ]);
            $this->SnsAcounts->save($acount);
            $this->Flash->success(__('認証に成功しました。'));
        }else{
            $this->Flash->error(__('認証に失敗しました。'));
        }
        return $this->redirect(['controller' => 'images', 'action' => 'index']);
    }

    public function login(){
        if ($this->request->is('get')){
            if($this->SnsAcounts->find()->where(['user_id' => $this->Auth->user()['id'], 'sns' => 'twitter'])->count() > 0){
                $this->Flash->error(__('すでにログインしています。'));
                return $this->redirect(['controller' => 'images', 'action' => 'index']);
            }else{
                return $this->redirect($this->Twitter->getAuthenticateUrl());
            }
        }
    }
}
