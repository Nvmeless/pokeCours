<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use  Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
use App\Entity\Pokedex;
use App\Repository\PokedexRepository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PokemonController extends AbstractController
{
    #[Route('/pokemon', name: 'app_pokemon')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PokemonController.php',
        ]);
    }



    private function setStats(Pokemon $pokemon, Pokedex $pokedex, $statName){
        // Set function name
        $statNameMin = "get" .$statName . "Min"; 
        $statNameMax = "get" .$statName . "Max"; 
        $statNamesetMax = "set" .$statName . "Max"; 
        $statNameSet = "set" .$statName; 
        //set Stats
        $statMin = $pokedex->$statNameMin();
        $statMax = $pokedex->$statNameMax();
        // Randomize stat between min and max
        $stat = rand($statMin, $statMax);
        //set to entity
        $pokemon->$statNamesetMax($stat);
        $pokemon->$statNameSet($stat);
        return ['statMinName' => $statNameMin,"min" => $statMin];
    }

    /**
     * Renvoie mon pokemon par id
     * 
     * @param Pokemon $pokemon
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/pokemon/{idPokemon}', name: 'pokemon.get', methods: ['GET'])]
    #[ParamConverter("pokemon", options:["id" => "idPokemon"])]
    public function getPokedexById(
        Pokemon $pokemon,
        SerializerInterface $serializer
    ):JsonResponse
    {
        
        $jsonPokemon = $serializer->serialize($pokemon, 'json', ["groups" => 'getAllPokemon']);
        return new JsonResponse($jsonPokemon, Response::HTTP_OK,[], true);
    }



            /**
     * creer un Pokemon
     * 
     * @param Pokedex $pokedex
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/pokemon/catch', name: 'pokemon.create', methods: ["POST"])]
    public function createPokedexEntry(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator,
        PokedexRepository $pokeRepository
    ):JsonResponse
    {
        // $pokedex = $serializer->deserialize($request->getContent(), Pokedex::class,'json');

        
        
        //Catch rate
        if(rand(1,100) %2=== 0) {
            $pokemon = new Pokemon();
            
            //Get pokemon id
            // $count = $pokeRepository->getPokedexCount();
            $pokedex = $pokeRepository->findAll();
            
            shuffle($pokedex);
            $pokedexRef = $pokedex[array_rand($pokedex, 1)];

            $pokemon->setName($pokedexRef->getName());
            $pokemon->setPokedex($pokedexRef);

            $stats = $this->setStats($pokemon, $pokedexRef, 'Pv');
            $pokemon->setLevel(rand(1, 100));
            //Validate errors
            $errors = $validator->validate($pokemon);
            //Errors handler
            if(count($errors) > 0){
                    return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST,[], true);
            }
            //Persist and flush entity
            $entityManager->persist($pokemon);
            $entityManager->flush();

            //Serialize to get datas created
            $jsonPokemon = $serializer->serialize($pokemon, 'json', ["groups" => "getAllPokedex"]);
            //Generate Url
            $location = $urlGenerator->generate("pokemon.get", ["idPokemon" => $pokemon->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            // Return Json Response
            return new JsonResponse($jsonPokemon, Response::HTTP_CREATED,["Location" => $location], true);
        }
        
          
            return new JsonResponse(['message' =>"not catched"], Response::HTTP_OK,[], false);
        
  
    }

}
