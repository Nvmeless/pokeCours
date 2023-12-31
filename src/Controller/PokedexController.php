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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $jsonPokedex = $serializer->serialize($pokedex, 'json', ["groups" => 'getAllPokedex']);
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
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ):JsonResponse
    {
        $pokedex = $serializer->deserialize($request->getContent(), Pokedex::class,'json');
        $errors = $validator->validate($pokedex);
        if(count($errors) > 0){
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST,[], true);
        }
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
        UrlGeneratorInterface $urlGenerator,
        PokedexRepository $repository
    ):JsonResponse
    {
        $updatedPokedex = $serializer->deserialize($request->getContent(),Pokedex::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $pokedex]);
        
        $content = $request->toArray();
        if(isset($content["idDevolution"])){
                $idDevolutions = $content["idDevolution"];
                foreach ($updatedPokedex->getDevolutionId() as $key => $devolutions_Id) {
                    $updatedPokedex->removeDevolutionId($devolutions_Id);
                }
                $relatedEntity = $repository->find($idDevolutions);
                $updatedPokedex->addDevolutionId($relatedEntity);
            }
        // $updatedPokedex->addDevolutionId($idDevolution);

        $entityManager->persist($updatedPokedex);
        $entityManager->flush();
        
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }

    #[Route("/api/pokedex/delete/{idPokedex}", name:"pokedex.delete", methods: ["DELETE"])]
    #[ParamConverter("pokedex", options:["id" => "idPokedex"])]
    public function forcDeleteFromPokedex(
        Pokedex $pokedex, 
        EntityManagerInterface $entityManager
        ): JsonResponse
    {
        $entityManager->remove($pokedex);
        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }



}
