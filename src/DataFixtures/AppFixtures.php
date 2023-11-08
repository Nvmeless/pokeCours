<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Pokedex;
use App\Entity\Pokemon;
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

        $pokedexNames = ["MecaZouzoumon","Shmiromon","Chaussure","Anemon","Veylsimon","Kiffounmon", "ZouzouMon", "Kripipomon", "Chimpokomon", "Ramon", "Croketasderamon"];


        for($i = 1; $i < 152;$i++){

            $pokedex = new Pokedex();
            // $pokedex->setName($this->faker->name());
            $pokedex->setName($pokedexNames[array_rand($pokedexNames)]);
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
        
        $pokemons = [];
        for($i = 1; $i < 500;$i++){
            $pokemon = new Pokemon();
            $pokedexRef = $pokedexs[array_rand($pokedexs, 1)];
            $pokemon->setName($pokedexRef->getName());
            $pvMax =rand($pokedexRef->getPvMin(),$pokedexRef->getPvMax());
            $pokemon->setPvMax($pvMax);
            $pokemon->setPv(rand(0, $pvMax));
            $pokemon->setLevel(rand(0, 100));
            
            $pokemon->setPokedex($pokedexRef);
            $pokemons[] = $pokemon;
        }

        foreach ($pokemons as $pokemon) {
           $manager->persist($pokemon);
        }
        //POKEMON FIXTURES



   

        $manager->flush();
    }
}
