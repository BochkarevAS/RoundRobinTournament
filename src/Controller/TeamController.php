<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    public function __construct(
        private readonly TeamRepository $repository
    ) {
    }

    #[Route('/teams', name: 'team_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $data = new Team();
        $form = $this->createForm(TeamType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->repository->add($data, true);

            return $this->redirectToRoute('team_index');
        }

        $teams = $this->repository->findAll();

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'form'  => $form->createView(),
        ]);
    }

    #[Route('/teams/{id}/delete', name: 'team_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Team $team): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $this->repository->remove($team, true);
        }

        return $this->redirectToRoute('team_index', [], Response::HTTP_SEE_OTHER);
    }
}