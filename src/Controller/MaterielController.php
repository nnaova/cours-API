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
    public function getAllMateriel(MaterielRepository $materielRepository,SerializerInterface $serializer): JsonResponse
    {
        $materiels = $materielRepository->findBy(['status' => 'En stock']);
        $jsonMateriels = $serializer->serialize($materiels,'json', ['groups' => 'get_materiel']);
        return new JsonResponse($jsonMateriels,Response::HTTP_OK,[],true);
    }

    #[Route('/api/materiel/{id}', name: 'app_materiel_id')]
    public function getMateriel(MaterielRepository $materielRepository,SerializerInterface $serializer, $id): JsonResponse
    {
        $materiel = $materielRepository->findBy(['status' => 'En stock']);
        $materiel = $materielRepository->find($id);
        $jsonMateriel = $serializer->serialize($materiel,'json',['groups' => 'get_materiel']);
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    #[Route('api/materiel/update/{id}', name: 'app_materiel_update', methods: ['PUT','PATCH'])]
    public function updateMateriel(MaterielRepository $materielRepository,SerializerInterface $serializer, $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $data = json_decode($request->getContent(),true);
        empty($data['name']) ? true : $materiel->setName($data['name']);
        empty($data['available']) ? true : $materiel->setAvailable($data['available']);
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $entityManager->persist($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel,'json', ['groups' => 'get_materiel']);
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    #[Route('api/materiel/harddelete/{id}', name: 'app_materiel_delete', methods: ['DELETE'])]
    public function deleteMateriel(MaterielRepository $materielRepository,SerializerInterface $serializer, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $entityManager->remove($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel,'json');
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    #[Route('api/materiel/delete/{id}', name: 'app_materiel_soft_delete')]
    public function softDelete(MaterielRepository $materielRepository,SerializerInterface $serializer, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $materiel->setStatus("off");
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $entityManager->persist($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel,'json', ['groups' => 'delete_materiel']);
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    #[Route('api/materiel/restore/{id}', name: 'app_materiel_restore')]
    public function restore(MaterielRepository $materielRepository,SerializerInterface $serializer, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $materiel->setStatus("en stock");
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $entityManager->persist($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel,'json', ['groups' => 'delete_materiel']);
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    #[Route('api/materiel/add', name: 'app_materiel_add', methods: ['POST'])]
    public function addMateriel(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $materiel = new Materiel();
        $data = json_decode($request->getContent(),true);
        $materiel->setName($data['name']);
        $materiel->setType($data['type']);
        $materiel->setAvailable($data['available']);
        $materiel->setStatus("En stock");
        $materiel->setCreatedAt(new DateTimeImmutable());
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $entityManager->persist($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel,'json');
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }
}
