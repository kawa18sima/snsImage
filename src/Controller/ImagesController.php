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

        $this->set(compact('images'));
    }

    public function add()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $user = $this->Auth->user();
            $images = $this->Twitter->getTimeLineImages();
            foreach($images as $url => $time){
                if($this->Images->find()->where([
                    'image_src' => $url,
                    'user_id' => $user['id']
                ])->count() == 0){
                $time = new Time($time);
                $time->timezone = 'Asia/Tokyo';
                $time->i18nFormat('yyyy-MM-dd HH:mm:ss');
                $image = $this->Images->newEntity([
                    'image_src' => $url,
                    'upload_time' => $time,
                    'user_id' => $user['id']
                ]);
                $this->Images->save($image);
            }
            }
        }
        return $this->redirect(['action' => 'index']);
    }

}
