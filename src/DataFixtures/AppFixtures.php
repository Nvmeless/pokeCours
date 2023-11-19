<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Pokedex;
use App\Entity\User;
use App\Entity\Pokemon;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
        /**
     * Password Hasher
     *
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    private Generator $faker;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->faker = Factory::create('fr_FR');
                $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $users = [];

        //Set Public User
        $publicUser = new User();
        $publicUser->setUsername("public");
        $publicUser->setRoles(["PUBLIC"]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $manager->persist($publicUser);
        $users[] = $publicUser;

        // Authentifiés
        for ($i = 0; $i < 5; $i++) {
            $userUser = new User();
            $password = $this->faker->password(2, 6);
            $userUser->setUsername($this->faker->userName() . "@". $password);
            $userUser->setRoles(["USER"]);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $manager->persist($userUser);
            $users[] = $userUser;

        }


        // Admins
        $adminUser = new User();
        $adminUser->setUsername("admin");
        $adminUser->setRoles(["ADMIN"]);
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, "password"));
        $manager->persist($adminUser);
        $users[] = $adminUser;

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
            //Set PV
            $pvMax = random_int($min,$max);
            $pvMin = random_int($min,$pvMax);
            $pokedex->setPvMin($pvMin);
            $pokedex->setPvMax($pvMax);
            
            $attackMax = random_int($min,$max);
            $attackMin = random_int($min,$attackMax);
            $pokedex->setAttackMin($attackMin);
            $pokedex->setAttackMax($attackMax);

            $defenseMax = random_int($min,$max);
            $defenseMin = random_int($min,$defenseMax);
            $pokedex->setDefenseMin($defenseMin);
            $pokedex->setDefenseMax($defenseMax);

            $speedMax = random_int($min,$max);
            $speedMin = random_int($min,$speedMax);
            $pokedex->setSpeedMin($speedMin);
            $pokedex->setSpeedMax($speedMax);

            $specialMax = random_int($min ,$max);
            $specialMin = random_int($min ,$specialMax);
            $pokedex->setSpecialMin($specialMin);
            $pokedex->setSpecialMax($specialMax);


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
        foreach($users as $user){
            $teamPokemons = [];
            for($i = 1; $i < 4;$i++){
                $pokemon = new Pokemon();
                $pokedexRef = $pokedexs[array_rand($pokedexs, 1)];
                $pokemon->setName($pokedexRef->getName());
                $pvMax =rand($pokedexRef->getPvMin(),$pokedexRef->getPvMax());
                $pokemon->setPvMax($pvMax);
                $pokemon->setPv(rand(0, $pvMax));
                $pokemon->setLevel(rand(0, 100));
                $pokemon->setMaster($user);
                $pokemon->setPokedex($pokedexRef);
                $teamPokemons[] = $pokemon;
            }
            $team = new Team();
            $team->setName("randomName");
            // $team->setMax(3);
            $team->setMaster($user);
            foreach($teamPokemons as $userPokemon){
                $team->addMonster($userPokemon);
            }
            $team->setStatus('online');
            
            array_push($pokemons, ...$teamPokemons);
            $manager->persist($team);
        }
        foreach ($pokemons as $pokemon) {
           $manager->persist($pokemon);
        }
        foreach ($users as $user) {


        }

      
        //POKEMON FIXTURES



   

        $manager->flush();
    }
}
