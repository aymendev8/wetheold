<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Articles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for($k = 0 ; $k < 10; $k++ ){
            $user = new User();
            $user->setFullname($this->faker->name())
                ->setPseudo($this->faker->userName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('Password');
            $manager->persist($user);
        }

    for ($i = 0; $i < 10; $i++) {
        $article = new Articles();
        $article->setname($this->faker->sentence())
            ->setmarque($this->faker->word())
            ->setprix(mt_rand(100, 1000))
            ->setdescription($this->faker->paragraph());
        $manager->persist($article);
    }

        $manager->flush();
    }
}
