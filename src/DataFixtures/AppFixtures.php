<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Pokedex;
use Faker\Factory;
use Faker\Generator;
class AppFixtures extends Fixture
{
    private Generator $faker;
    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        $min = 0;
        $max = 255;
        // $manager->persist($product);
        $pokedexs = [];
        for($i = 1; $i < 152;$i++){

            $pokedex = new Pokedex();
            $pokedex->setName($this->faker->name());
            $pokedex->setPvMin(random_int($min,$max));
            $pokedex->setPvMax(random_int($min,$max));
            $pokedex->setAttackMax(random_int($min,$max));
            $pokedex->setAttackMin(random_int($min,$max));
            $pokedex->setDefenseMin(random_int($min,$max));
            $pokedex->setDefenseMax(random_int($min,$max));
            $pokedex->setSpeedMax(random_int($min,$max));
            $pokedex->setSpeedMin(random_int($min,$max));
            $pokedex->setSpecialMax(random_int($min,$max));
            $pokedex->setSpecialMin(random_int($min,$max));
            $pokedex->setPokemonIdFirst(random_int(1,151));
            $pokedex->setPokemonIdSecond(random_int(1,151));
            $pokedexs[] = $pokedex;
        }
        foreach($pokedexs as $pokedex){
            if(random_int(0,1)){
                $pokedex->addDevolutionId($pokedexs[array_rand($pokedexs, 1)]);
            }
            $manager->persist($pokedex);
        }
        
        $manager->flush();
    }
}
