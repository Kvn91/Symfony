<?php
namespace Kevin\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kevin\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Liste des noms de catégorie à ajouter
        $namesList = array(
            'Kevin',
            'Arya',
            'Theon',
            'Hodor',
            'Cersei'
        );

        foreach ($namesList as $name) {
            $user = new User();
            $user->setUsername($name);
            $user->setPassword($name);

            $user->setSalt('');

            $user->setRoles(array('ROLE_USER'));

            $manager->persist($user);
        }
        $manager->flush();
    }
}