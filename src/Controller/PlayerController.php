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

class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em): Response
    {
        $video_list = $videoRepository->findAll();

        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnail_file = $form->get('thumbnail')->getData();
            if ($thumbnail_file) {
                $thumbnail_file->move($this->getParameter('thumbnails_dir'), $thumbnail_file->getClientOriginalName());
                $video->setThumbnail($thumbnail_file->getClientOriginalName());
            }

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
    public function player(Request $request, VideoRepository $videoRepository): Response
    {
        $video_id = $request->query->get('id');
        $video = $videoRepository->find($video_id);

        $video_path = "https://onedrive.live.com/download?cid=29246095C87A94F7&resid=29246095C87A94F7%" . $video->getOnedriveId() . "&authkey" . $video->getOnedriveAuthkey();
        return $this->render('player/player.html.twig', [
            'video_path' => $video_path,
            'videos_dir' => $this->getParameter('videos_dir')
        ]);
    }
}
