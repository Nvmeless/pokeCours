<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProfileController.php',
        ]);
    }

        /**
     * Renvoie mon pokemon par id
     * 
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/profile', name: 'profile.get', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getUserProfile(
        SerializerInterface $serializer
    ):JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $profile = $this->getUser();
        $jsonUser = $serializer->serialize($profile, 'json', ['groups' => 'getProfile']);
        return new JsonResponse($jsonUser, Response::HTTP_OK,[], true);
    }

}
