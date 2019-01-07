<?php
namespace App\Controller;

use App\Controller\AppController;

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
        if ($this->request->is('post')) {
            $user = $this->Auth->user();
            $images = $this->Twitter->getTimeLineImages();
            foreach($images as $url => $time){
                $image = $this->Images->newEntity([
                    'image_src' => $url,
                    'created' => new DateTime($time),
                    'user_id' => $user->id
                ]);
                $this->Images->save($image);
            }
        }
        return $this->redirect(['aciton' => 'index']);
        
    }
}
