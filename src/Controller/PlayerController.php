<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController {
    #[Route('/', name: 'app_index')]
    public function index(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em): Response {
        $video_list = $videoRepository->findAll();

        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $video_file = $form->get('video')->getData();
            $thumbnail_file = $form->get('thumbnail')->getData();

            //$filename = $image->getClientOriginalName();
            //$uniqueFilename = md5(uniqid()) . "-" . $filename;

            $video_file->move($this->getParameter('videos_dir'), $video_file->getClientOriginalName());
            $thumbnail_file->move($this->getParameter('thumbnails_dir'), $thumbnail_file->getClientOriginalName());

            $video->setPath($video_file->getClientOriginalName());
            $video->setThumbnail($thumbnail_file->getClientOriginalName());

            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('app_index');
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
