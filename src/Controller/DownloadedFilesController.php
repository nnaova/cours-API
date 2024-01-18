<?php

namespace App\Controller;

use App\Entity\DownloadedFiles;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DownloadedFilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DownloadedFilesController extends AbstractController
{
    #[Route('/', name: 'app.index')]
    public function index(): void
    {

        
    }
     #[Route('/api/file', name: 'files.create', methods:["POST"])]
    public function createFile(Request $request, DownloadedFilesRepository $repository, SerializerInterface $serializer, 
    EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $newFile = new DownloadedFiles();

        $file = $request->files->get("file");
        
        $newFile->setFile($file);
        $newFile->setStatus("En stock");
        $entityManager->persist($newFile);
        $entityManager->flush();

        $realname = $newFile->getRealname();
        $realpath = $newFile->getRealpath();
        $slug = $newFile->getSlug();
        $jsonPicture= [
            "id"=>$newFile->getId(),
            "name"=>$newFile->getName(),
            "realname"=>$realname,
            "realpath"=>$realpath,
            "mimetype"=>$newFile->getMimeType(),
            "slug"=>$slug,
            "status"=>$newFile->getStatus(),
        ];
        $location = $urlGenerator->generate("app.index",[], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonPicture, Response::HTTP_CREATED,["Location"=> $location. $realpath . "/".$slug]);
    }
    #[Route('/api/file', name: 'files.list', methods:["GET"])]
    public function listFiles(DownloadedFilesRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $files = $repository->findBy(["status" => "En stock"]);
        $jsonFiles = $serializer->serialize($files, "json");
        return new JsonResponse($jsonFiles, Response::HTTP_OK, [], true);
    }
    #[Route('/api/file/{id}', name: 'files.hide', methods:["PUT"])]
    public function hideFile(Request $request, DownloadedFilesRepository $repository, SerializerInterface $serializer, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $file = $repository->find($id);
        $file->setStatus("Hors stock");
        $entityManager->persist($file);
        $entityManager->flush();
        $jsonFile = $serializer->serialize($file, "json");
        return new JsonResponse($jsonFile, Response::HTTP_OK, [], true);
    }
    #[Route('/api/file/{id}', name: 'files.delete', methods:["DELETE"])]
    public function deleteFile(Request $request, DownloadedFilesRepository $repository, SerializerInterface $serializer, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $file = $repository->find($id);
        $entityManager->remove($file);
        $entityManager->flush();
        $jsonFile = $serializer->serialize($file, "json");
        return new JsonResponse($jsonFile, Response::HTTP_OK, [], true);
    }
    #[Route('api/file/restore/{id}', name: 'files.restore', methods:["PUT"])]
    public function restoreFile(Request $request, DownloadedFilesRepository $repository, SerializerInterface $serializer, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $file = $repository->find($id);
        $file->setStatus("En stock");
        $entityManager->persist($file);
        $entityManager->flush();
        $jsonFile = $serializer->serialize($file, "json");
        return new JsonResponse($jsonFile, Response::HTTP_OK, [], true);
    }
}