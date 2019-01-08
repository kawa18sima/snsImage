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
        $this->paginate = [
            'contain' => ['Users']
        ];
        $images = $this->paginate($this->Images);

        $this->set(compact('images'));
    }

    public function add()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $user = $this->Auth->user();
            $images = $this->Twitter->getTimeLineImages();
            foreach($images as $url => $time){
                $time = new Time($time);
                $time->timezone = 'Asia/Tokyo';
                $time->i18nFormat('yyyy-MM-dd HH:mm:ss');
                $data = [
                    'image_src' => $url,
                    'upload_time' => $time,
                    'user_id' => $user['id']
                ];
                $image = $this->Images->newEntity($data);
                if(isset($this->Images->find('all', $data)->first()['id'])){
                   $this->Images->save($image);
               }
            }
        }
        return $this->redirect(['action' => 'index']);
    }

}
