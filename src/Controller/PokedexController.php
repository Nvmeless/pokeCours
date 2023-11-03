<?php

namespace App\Controller;

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

class PokedexController extends AbstractController
{
    #[Route('/pokedex', name: 'app_pokedex')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PokedexController.php',
        ]);
    }

    /**
     * Renvoie mes pokemons
     * 
     * @param PokedexRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/pokedex', name: 'pokedex.getAll', methods: ['GET'])]
    public function getPokedex(
        PokedexRepository $repository,
        SerializerInterface $serializer
    ):JsonResponse
    {
        $pokedex = $repository->findAll();
        $jsonPokedex = $serializer->serialize($pokedex, 'json', ['groups' => 'getAllPokedex']);
        return new JsonResponse($jsonPokedex, Response::HTTP_OK,[], true);
    }

    /**
     * Renvoie mon pokemon par id
     * 
     * @param Pokedex $pokedex
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/pokedex/{idPokedex}', name: 'pokedex.get', methods: ['GET'])]
    #[ParamConverter("pokedex", options:["id" => "idPokedex"])]
    public function getPokedexById(
        Pokedex $pokedex,
        SerializerInterface $serializer
    ):JsonResponse
    {
        // $request = Request::createFromGlobals();
        // $paramId = $request->query->get('id');
        // $parameters = 
        // $pokedex = $repository->find($paramId);
        $jsonPokedex = $serializer->serialize($pokedex, 'json');
        return new JsonResponse($jsonPokedex, Response::HTTP_OK,[], true);
    }

        /**
     * creer un Pokemon
     * 
     * @param Pokedex $pokedex
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/pokedex', name: 'pokedex.create', methods: ["POST"])]
    public function createPokedexEntry(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ):JsonResponse
    {
        $pokedex = $serializer->deserialize($request->getContent(), Pokedex::class,'json');
        $entityManager->persist($pokedex);
        $entityManager->flush();
        $jsonPokedex = $serializer->serialize($pokedex, 'json', ["groups" => "getAllPokedex"]);
        $location = $urlGenerator->generate("pokedex.get", ["idPokedex" => $pokedex->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonPokedex, Response::HTTP_CREATED,["Location" => $location], true);
    }


    #[Route("/api/pokedex/{idPokedex}", name:"pokedex.update", methods: ["PUT"])]
    #[ParamConverter("pokedex", options:["id" => "idPokedex"])]
    public function updatePokedex(
        Pokedex $pokedex,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ):JsonResponse
    {
        $updatedPokedex = $serializer->deserialize($request->getContent(),Pokedex::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $pokedex]);
        
        $content = $request->toArray();
        $idDevolution = $content["idDevolution"] ?? -1;
        
        $updatedPokedex->addDevolutionId($idDevolution);

        $entityManager->persist($updatedPokedex);
        $entityManager->flush();
        
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }
}
