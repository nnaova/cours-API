<?php

namespace App\Controller;

use App\Entity\DownloadFiles;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DownloadFilesRepositories;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DownloadFilesController extends AbstractController
{
    #[Route('/download/files', name: 'app_download_files')]
    public function index(): void
    {

    }
    #[Route('/api/file', name: 'files.create', methods: ['POST'])]
    public function createfiles(Request $request, DownloadFilesRepositories $repository, SerializerInterface $serializer, 
    EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $newFile = new DownloadFiles();

        $file=$request->files->get('file');
        $entityManager->persist($newFile);
        $entityManager->flush();

        $realname = $newFile->getRealname();
        $realpath = $newFile->getRealpath();
        $slug = $newFile->getSlug();
        
        $jsonpicture = [
            'name' => $file->getname(),
            'realname' => $realname,
            'realpath' => $realpath,
            'slug' => $slug,
            'mime' => $file->getMimeType(),
        ];

        return new JsonResponse($jsonpicture, Response::HTTP_CREATED, [
            'Location' => $urlGenerator->generate('files.show', ['id' => $newFile->getId()])
        ]);
    }
}
