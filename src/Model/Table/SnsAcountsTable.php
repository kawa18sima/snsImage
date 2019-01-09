<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SnsAcounts Model
 *
 * @property \App\Model\Table\AcountsTable|\Cake\ORM\Association\BelongsTo $Acounts
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SnsAcount get($primaryKey, $options = [])
 * @method \App\Model\Entity\SnsAcount newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SnsAcount[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SnsAcount|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SnsAcount|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SnsAcount patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SnsAcount[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SnsAcount findOrCreate($search, callable $callback = null, $options = [])
 */
class SnsAcountsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('sns_acounts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Acounts', [
            'foreignKey' => 'acount_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('sns')
            ->maxLength('sns', 255)
            ->requirePresence('sns', 'create')
            ->notEmpty('sns');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
