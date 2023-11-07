<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use  Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Component\Validator\Constraints\Url;

class PictureController extends AbstractController
{
    #[Route('/', name: 'app_picture')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PictureController.php',
        ]);
    }
    #[Route('/api/pictures/{idPicture}', name:'pictures.get', methods:['GET'])]
    public function getPicture(
        int $idPicture,
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager, 
        PictureRepository $repository, 
        UrlGeneratorInterface $urlGenerator
        ): JsonResponse
        {
            $picture = $repository->find($idPicture);

            $location = $picture->getPublicpath() . '/' . $picture->getRealpath();
            $location = $urlGenerator->generate('app_picture', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $location = $location . str_replace('/public/', "", $picture->getPublicpath()). "/" . $picture->getRealpath();
            return $picture ? 
            new JsonResponse($serializer->serialize($picture,"json"), Response::HTTP_OK, ["Location" => $location], true) :
            new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

    #[Route('/api/picture', name:'pictures.create', methods: ['POST'])]
    public function createPicture(        
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
        ): JsonResponse
        {
            $picture = new Picture;
            $file = $request->files->get('file');
            $picture->setFile($file);
            $picture->setMime($file->getClientMimeType());
            $picture->setRealname($file->getClientOriginalName());
            $picture->setPublicpath('/public/medias/pictures');
            $picture->setUploadDate(new \DateTime());
            
            $entityManager->persist($picture);
            $entityManager->flush();

            $jsonPicture = $serializer->serialize($picture,'json');
            $location = $urlGenerator->generate('pictures.get',['idPicture' => $picture->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            return new JsonResponse($jsonPicture, Response::HTTP_CREATED, ['Location' => $location], true);
        }
}
