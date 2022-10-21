<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\LeagueDto;
use App\Entity\League;
use App\Mailer\DefaultMailSender;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
#[Route('/', name: 'default')]
class Controller
{
    public const ADDED_MESSAGE = 'The following league was added to your favourites.';

    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private Validator $validator,
        private DefaultMailSender $mailSender
    ) {
    }

    #[Route('/getFavourite', name: 'get_all', methods: [Request::METHOD_GET])]
    public function getFavourite(): Response
    {
        return new Response(
            $this->serializer->serialize(
                $this->em->getRepository(League::class)->findAll(),
                'json',
                ['groups' => 'read_league']
            )
        );
    }

    #[Route('/addFavourite', name: 'add_favourite', methods: [Request::METHOD_POST])]
    public function addFavourite(Request $request): Response
    {
        $requestData = $request->toArray();
        $leagueDto = new LeagueDto($requestData);
        $this->validator->validate($leagueDto);
        $league = new League(
            $leagueDto->getIdRemote(),
            $leagueDto->getName(),
            $leagueDto->getType(),
            $leagueDto->getLogo(),
            $leagueDto->getCountry(),
            $leagueDto->getFlag()
        );

        $this->em->persist($league);

        $this->em->flush();

        $this->mailSender->send('userTestEmail@gmail.com', $league);

        return new Response($this->serializer->serialize($league, 'json', ['groups' => 'read_league']));
    }

    #[Route('/remove/{id}', name: 'remove_favourite', methods: [Request::METHOD_GET])]
    public function removeFavourite(string $id): Response
    {
        $league = $this->em->getRepository(League::class)->findOneBy(['idRemote' => $id]);

        if (isset($league)) {
            $this->em->remove($league);
            $this->em->flush();

            return new Response();
        }

        return new Response('Record not found!');
    }
}
