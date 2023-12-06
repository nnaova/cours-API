<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Repository\MaterielRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class MaterielController extends AbstractController
{
    #[Route('/materiel', name: 'app_materiel')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new materielController!',
            'path' => 'src/Controller/MaterielController.php',
        ]);
    }
    #[Route('/api/materiel', name: 'app_materiel')]
    public function getAllMateriel(Request $request,MaterielRepository $materielRepository,SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface ,UrlGeneratorInterface $urlgenerator): JsonResponse
    {
        $materielentry = $serializer->deserialize($request->getContent(),Materiel::class,'json');
        $materielentry->setCreatedAt(new DateTimeImmutable());
        $materielentry->setUpdatedAt(new DateTimeImmutable());
        $entityManagerInterface->persist($materielentry);
        $entityManagerInterface->flush();
        dd("fin");
        $jsonMateriels = $serializer->serialize($materielentry,'json');
        $location = $urlgenerator->generate("materiel.get",["id" => $materielentry->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonMateriels,Response::HTTP_OK,[],true);
    }
}
