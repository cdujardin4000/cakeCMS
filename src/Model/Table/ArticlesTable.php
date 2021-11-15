<?php

namespace App\Model\Table;
use Cake\ORM\Table;
// the Text class
use Cake\Utility\Text;
// the EventInterface class
use Cake\Event\EventInterface;
// the Validator class
use Cake\Validation\Validator;

class ArticlesTable extends Table {

    public function initialize(array $config): void{
        // fonctions qui sont appellées lors de crétion d'un article
        $this->addBehavior('Timestamp');
    }

    public function beforeSave(EventInterface $event, $entity, $options){
        // fonction qui formate le titre pour faire le slug qui sert d'identifiant dans le barre d'adresse
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // trim slug to maximum length defined in schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    public function validationDefault(Validator $validator): Validator{
        // Ajout des restrictions pour la crétion d'un article
        $validator
            ->notEmptyString('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->notEmptyString('body')
            ->minLength('body', 10);

        return $validator;
    }
}
