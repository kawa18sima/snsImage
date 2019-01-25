<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Images Controller
 *
 * @property \App\Model\Table\ImagesTable $Images
 *
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImagesController extends AppController
{
    public $components = ['Twitter'];

    public function index()
    {
        $current_user = $this->Auth->user();
        $images = $this->Images->find()->where(['user_id' => $current_user['id']])->order(['upload_time' => 'DESC']);
        $this->loadModel('SnsAcounts');
        $sns = $this->SnsAcounts->find()->where(['user_id' => $current_user['id'], 'sns' => 'twitter'])->first();


        $this->set(compact('images', 'sns'));
    }

    public function add($sync_id)
    {
        $this->autoRender = false;
        $this->loadModel('SnsAcounts');
        if ($this->request->is('get')) {
            $sns = $this->SnsAcounts->find()->where(['sync' => $sync_id]);
            if($sns->count() == 0){
                return $this->redirect(['action' => 'index']);
            }
            $sns = $sns->first();
            $images = $this->Twitter->getTimeLineImages($sns);
            foreach($images as $url => $time){
                if($this->Images->find()->where([
                    'image_src' => $url,
                    'user_id' => $sns['user_id']
                ])->count() == 0){
                    $time = new Time($time);
                    $time->timezone = 'Asia/Tokyo';
                    $time->i18nFormat('yyyy-MM-dd HH:mm:ss');
                    $image = $this->Images->newEntity([
                        'image_src' => $url,
                        'upload_time' => $time,
                        'user_id' => $sns['user_id']
                    ]);
                    $this->Images->save($image);
                }
            }

            $sns['sync'] = null;
            $this->SnsAcounts->save($sns);
        }
        return $this->redirect(['action' => 'index']);
    }

    public function sync(){
        $this->autoRender = false;
        $this->loadModel('SnsAcounts');
        if($this->request->is('post')){
            $current_user = $this->Auth->user();
            $sns_acounts = $this->SnsAcounts->find()->where(['user_id' => $current_user['id']]);
            foreach($sns_acounts as $sns_acount){
                if($sns_acount['sns'] == 'twitter'){
                    $sns_acount['sync'] = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
                    while(!($this->SnsAcounts->save($sns_acount))){
                        $sns_acount['sync'] = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
                    }
                }
            }
        }
        return $this->redirect(['action' => 'index']);
    }

}
