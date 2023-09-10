<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use App\Service\RoundRobinTournament;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentController extends AbstractController
{
    public function __construct(
        private readonly TournamentRepository $repository,
        private readonly RoundRobinTournament $robin,
    ) {
    }

    #[Route('/', name: 'tournament_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $tournaments = $this->repository->findAll();

        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournaments
        ]);
    }

    #[Route('/tournaments', name: 'tournament_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $data = new Tournament();
        $form = $this->createForm(TournamentType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->repository->add($data, true);

            return $this->redirectToRoute('tournament_index');
        }

        $tournaments = $this->repository->findAll();

        return $this->render('tournament/create.html.twig', [
            'form' => $form->createView(),
            'tournaments' => $tournaments
        ]);
    }

    #[Route('/{slug}-{id}', name: 'tournament_show', requirements: ['slug' => '.+', 'id' => '\d+'], methods: ['GET'])]
    public function show(Tournament $tournament, string $slug): Response
    {
        if ($tournament->getSlug() !== $slug) {
            return $this->redirectToRoute('tournament_show', [
                'id'   => $tournament->getId(),
                'slug' => $tournament->getSlug(),
            ]);
        }

        $teams = $tournament->getTeams();
        $teams = array_column($teams->toArray(), 'name');

        $rounds = $this->robin->schedule($teams);

        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
            'rounds' => $rounds,
        ]);
    }

    #[Route('/tournaments/{id}/delete', name: 'tournament_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Tournament $tournament): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournament->getId(), $request->request->get('_token'))) {
            $this->repository->remove($tournament, true);
        }

        return $this->redirectToRoute('tournament_index', [], Response::HTTP_SEE_OTHER);
    }
}