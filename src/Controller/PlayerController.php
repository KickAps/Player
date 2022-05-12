<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController {
    #[Route('/', name: 'app_index')]
    public function index(Request $request, VideoRepository $videoRepository): Response {
        $video_list = $videoRepository->findAll();

        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
//            $images = $form->get('images')->getData();
//
//            $this->addImagesToProject($images, $project);
//
//            // Associate the project to the current user
//            $this->getUser()->addProject($project);
//
//            $this->em->persist($project);
//            $this->em->flush();
//
//            // Flash message
//            $this->addFlash('success', 'Projet créé avec succès !');
//
//            // Redirection to the show page
//            return $this->redirectToRoute('app_project_show', [
//                'externalId' => $this->getUser()->getExternalId(),
//                'id' => $project->getId()
//            ]);
        }

        return $this->render('player/index.html.twig', [
            'video_list' => $video_list,
            'video_form' => $form->createView()
        ]);
    }

    #[Route('/player', name: 'app_player')]
    public function player(Request $request): Response {
        $path = $request->query->get('path');
        return $this->render('player/player.html.twig', [
            'video_path' => $path,
        ]);
    }
}
