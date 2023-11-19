<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\BattleTeam;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FightController extends AbstractController
{
    #[Route('/fight', name: 'app_fight')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FightController.php',
        ]);
    }



        /**
     * creer un combat
     * 
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/fight', name: 'battle.create.random', methods: ["POST"])]
    public function createRandomFight(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator,
        UserRepository $userRepository
    ):JsonResponse
    {
        $battle = new Battle();
        $attack = $this->getUser();
        $defense = $this->getUser();

        
        //Search for opponent
        $users = $userRepository->findAll();
        //Check if its different user id
        //Check if user has monsters
        if(!($attack->getPokemon()->count() < 1 && $attack->getTeams()->count() < 1)){
            //Check if opponent is fightable
            while($attack->getId() === $defense->getId() || ($defense->getPokemon()->count() < 1 && $defense->getTeams()->count() < 1)){

                shuffle($users);
                $defense = $users[array_rand($users,1)];
                
            }
            
            //create defense battle team
            $attackBattleTeam = new BattleTeam();
            $attackBattleTeam->setUser($attack);
            $attackTeams = $attack->getTeams();
            $attackBattleTeam->setName('attack');
            $attackTeamList = [];
            foreach($attackTeams as $attackTeam){
                $attackTeamList[] = $attackTeam;
            }
            $attackBattleTeam->setTeams($attackTeamList[0]);
            $attackBattleTeam->setStatus('active');
            
            
            $defenseBattleTeam = new BattleTeam();
            $defenseBattleTeam->setUser($defense);
            $defenseTeams = $defense->getTeams();
            $defenseBattleTeam->setName('defense');
            $defenseTeamList = [];
            foreach($defenseTeams as $defenseTeam){
                $defenseTeamList[] = $defenseTeam;
            }
            $defenseBattleTeam->setTeams($defenseTeamList[0]);
            
            $defenseBattleTeam->setStatus('active');
            $attackBattleTeam->setBattle($battle);
            $defenseBattleTeam->setBattle($battle);
            $battle->addBattleTeam($defenseBattleTeam);
            $battle->addBattleTeam($attackBattleTeam);
            
            
            
            $BattleErrors = $validator->validate($battle);
            $DefenseErrors = $validator->validate($defenseBattleTeam);
            $AttackErrors = $validator->validate($attackBattleTeam);
            if(count($BattleErrors) > 0 || count($DefenseErrors) > 0 || count($AttackErrors) > 0){
                return new JsonResponse($serializer->serialize([$BattleErrors,$DefenseErrors,$AttackErrors], 'json'), Response::HTTP_BAD_REQUEST,[], true);
            }
            $entityManager->persist($battle);
            $entityManager->persist($defenseBattleTeam);
            $entityManager->persist($attackBattleTeam);
            $battle->setStatus("active");

            
      
            $entityManager->flush();



        }

// dd($battle->getBattleTeam());
        $jsonBattle = $serializer->serialize($battle, 'json', ["groups" => "getBattleInfos"]);
        
        // $jsonPokedex = $serializer->serialize($pokedex, 'json', ["groups" => "getAllPokedex"]);
        // $location = $urlGenerator->generate("pokedex.get", ["idPokedex" => $pokedex->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        // return new JsonResponse($jsonPokedex, Response::HTTP_CREATED,["Location" => $location], true);
        return new JsonResponse($jsonBattle, Response::HTTP_CREATED,[], true);
    }
    
}
