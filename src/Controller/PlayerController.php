<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    const COOKIE_HASH = "$2y$10$7ZyMPl905Ik/mGVxYu1V4OcLvrckzlHNEAQjLqeRrrg8/xATooCAu";
    const PASSWORD_HASH = "$2y$10$8MXHXSp3y9wCVNQYIIUdS.PXLKIGXgPU.Tk.f1slVMKF1bZovbQIu";

    const FLAGS = [
        'Florian',
        'Camille',
        'Voyage',
        'Adrénaline',
    ];

    #[Route('/', name: 'app_index')]
    public function index(VideoRepository $videoRepository): Response
    {
        $video_list = $videoRepository->findAllSorted();
        foreach (self::FLAGS as $id => $flag) {
            foreach ($video_list as $video) {
                if ($video->getFlag() && in_array($id, $video->getFlag())) {
                    $video_by_flag[$flag][] = $video;
                }
            }
        }

        $video_by_flag['Tout'] = $videoRepository->findAllSorted();

        return $this->render('player/index.html.twig', [
            'video_by_flag' => $video_by_flag
        ]);
    }

    #[Route('/player', name: 'app_player')]
    public function player(Request $request, VideoRepository $videoRepository): Response
    {
        $video_id = $request->query->get('id');
        $video = $videoRepository->find($video_id);

        $video_path = "https://onedrive.live.com/download?cid=29246095C87A94F7&resid=29246095C87A94F7%" . $video->getOnedriveId() . "&authkey=" . $video->getOnedriveAuthkey();
        return $this->render('player/player.html.twig', [
            'video_path' => $video_path,
            'video_title' => $video->getTitle(),
            'youtube_url' => $video->getYoutubeUrl()
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em): Response
    {
        if ($response = $this->check_cookie_password($request)) {
            return $response;
        }

        $update = false;
        $video = $request->request->all('video');

        if ($video && $video['id']) {
            $update = true;
            $video = $videoRepository->find($video['id']);
        } else {
            $video = new Video();
        }

        // BUG : csrf_protection -> Failed to start the session: already started by PHP
        // $form = $this->createForm(VideoType::class, $video);
        $form = $this->createForm(VideoType::class, $video, ['csrf_protection' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnail_file = $form->get('thumbnail')->getData();
            if ($thumbnail_file) {

                $filename = md5(uniqid($thumbnail_file->getClientOriginalName(), true)) . "_" . $thumbnail_file->getClientOriginalName();
                $info = getimagesize($thumbnail_file);

                switch ($info['mime']) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($thumbnail_file);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($thumbnail_file);
                }

                imagejpeg($image, $this->getParameter('thumbnails_dir') . $filename, 60);

                if ($update) {
                    $filesystem = new Filesystem();
                    $filesystem->remove($this->getParameter('thumbnails_dir') . $video->getThumbnail());
                }

                $video->setThumbnail($filename);
            }

            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }

        $video_list = $videoRepository->findAll();

        return $this->render('player/admin.html.twig', [
            'video_form' => $form->createView(),
            'video_list' => $video_list,
        ]);
    }

    #[Route('/video_info', name: 'app_video_info')]
    public function video_info(Request $request, VideoRepository $videoRepository): Response
    {
        $video_id = $request->query->get('id');
        $video = $videoRepository->find($video_id);

        return new JsonResponse([
            'title' => $video->getTitle(),
            'year' => $video->getYear(),
            'onedrive_id' => $video->getOnedriveId(),
            'onedrive_authkey' => $video->getOnedriveAuthkey(),
            'flag' => $video->getFlag(),
            'youtube_url' => $video->getYoutubeUrl(),
        ]);
    }

    #[Route("/password", name: "app_password", methods: ["GET", "POST"])]
    public function password(Request $request): Response
    {
        if (password_verify($request->cookies->get("password"), self::COOKIE_HASH)) {
            return $this->redirectToRoute("app_admin");
        }

        $password = $request->request->get("password");

        if (password_verify($password, self::PASSWORD_HASH)) {
            // Redirection
            $response = $this->redirectToRoute("app_admin");
            $response->headers->setCookie(Cookie::create("password", "valid"));
            return $response;
        }

        return $this->render("player/password.html.twig");
    }

    public function check_cookie_password(Request $request)
    {
        if (!password_verify($request->cookies->get("password"), self::COOKIE_HASH)) {
            return $this->redirectToRoute("app_index");
        }
    }
}
