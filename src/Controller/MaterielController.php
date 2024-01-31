<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Materiel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Func;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
// use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\cache;
use Nelmio\apiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
    /**
     * Récupère tous les matériels en stock et les renvoie au format JSON.
     *
     * @Route("/api/materiel", name="materiel.list", methods={"GET"})
     *
     * @param MaterielRepository $materielRepository Le repository des matériels
     * @param SerializerInterface $serializer Le service de sérialisation
     * @param TagAwareCacheInterface $cache Le service de cache
     * @return JsonResponse La réponse JSON contenant les matériels en stock
     */
    #[Route('/api/materiel', name: 'materiel.list', methods: ['GET'])]
    public function getAllMateriel(MaterielRepository $materielRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $materiels = $materielRepository->findBy(['status' => 'En stock']);
        $context = SerializationContext::create()->setGroups(['get_materiel']);
        $jsonMateriels = $serializer->serialize($materiels, 'json', $context);
        $idCache = "getAllMateriel";
        $jsonMateriels = $cache->get($idCache, function (ItemInterface $item) use ($materielRepository, $serializer) {
            $item->tag("materielCache");
            echo 'mise en cache';
            $materielsList = $materielRepository->findBy(['status' => 'En stock']);
            $context = SerializationContext::create()->setGroups(['get_materiel']);
            return $serializer->serialize($materielsList, 'json', $context);
        });
        return new JsonResponse($jsonMateriels, Response::HTTP_OK, [], true);
    }

    /**
         * Retourne un materiel en fonction de son ID.
         *
         * @Route('/api/materiel/{id}', name='materiel.show', methods=['GET'])
         *
         * @param MaterielRepository $materielRepository le repository des materiels
         * @param SerializerInterface $serializer le service de sérialisation
         * @param int $id l'identifiant du materiel à récupérer
         * @return JsonResponse la réponse JSON contenant le materiel demandé
         */
        #[Route('/api/materiel/{id}', name: 'materiel.show', methods: ['GET'])]
        public function getMateriel(MaterielRepository $materielRepository, SerializerInterface $serializer, $id, TagAwareCacheInterface $cache): JsonResponse
        {
            $idCache = "getMateriel_" . $id;
            $cachedMateriel = $cache->get($idCache, function (ItemInterface $item) use ($materielRepository, $serializer, $id) {
                $item->tag("materielCache");
                $materiel = $materielRepository->find($id);
                $context = SerializationContext::create()->setGroups(['get_materiel']);
                return $serializer->serialize($materiel, 'json', $context);
            });

            return new JsonResponse($cachedMateriel, Response::HTTP_OK, [], true);
        }

    /**
     * Mettre à jour un matériel.
     *
     * @Route("api/materiel/{id}", name="materiel.update", methods={"PUT","PATCH"})
     *
     * @param TagAwareCacheInterface $cache Le cache utilisé pour invalider les tags.
     * @param MaterielRepository $materielRepository Le repository utilisé pour récupérer le matériel.
     * @param SerializerInterface $serializer Le serializer utilisé pour sérialiser le matériel en JSON.
     * @param int $id L'identifiant du matériel à mettre à jour.
     * @param Request $request La requête HTTP contenant les données du matériel à mettre à jour.
     * @param EntityManagerInterface $entityManager L'entity manager utilisé pour persister les modifications.
     * @return JsonResponse La réponse JSON contenant le matériel mis à jour.
     */
    #[Route('api/materiel/{id}', name: 'materiel.update', methods: ['PUT', 'PATCH'])]
    public function updateMateriel(TagAwareCacheInterface $cache, MaterielRepository $materielRepository, SerializerInterface $serializer, $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $data = json_decode($request->getContent(), true);
        empty($data['name']) ? true : $materiel->setName($data['name']);
        empty($data['available']) ? true : $materiel->setAvailable($data['available']);
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $cache->invalidateTags(["materielCache"]);
        $entityManager->persist($materiel);
        $entityManager->flush();
        $context = SerializationContext::create()->setGroups(['get_materiel']);
        $jsonMateriel = $serializer->serialize($materiel, 'json', $context);
        return new JsonResponse($jsonMateriel, Response::HTTP_OK, [], true);
    }

    /**
         * Supprime un matériel.
         *
         * @Route('api/materiel/{id}', name='materiel.delete', methods=['DELETE'])
         *
         * @param MaterielRepository $materielRepository Le repository de matériel
         * @param SerializerInterface $serializer Le service de sérialisation
         * @param int $id L'identifiant du matériel à supprimer
         * @param EntityManagerInterface $entityManager Le gestionnaire d'entités
         * @param TagAwareCacheInterface $cache Le cache utilisé pour invalider les tags
         * @return JsonResponse La réponse JSON contenant le matériel supprimé
         */
    #[Route('api/materiel/{id}', name: 'materiel.delete', methods: ['DELETE'])]
    public function deleteMateriel(MaterielRepository $materielRepository, SerializerInterface $serializer, $id, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $cache->invalidateTags(["materielCache"]);
        $entityManager->remove($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel, 'json');
        return new JsonResponse($jsonMateriel, Response::HTTP_OK, [], true);
    }
    
    /**
     * Supprime un matériel.
     *
     * @Route('api/materiel/delete/{id}', name='materiel.hide', methods=['PUT','PATCH'])
     *
     * @param MaterielRepository $materielRepository Le repository de matériel
     * @param SerializerInterface $serializer Le service de sérialisation
     * @param int $id L'identifiant du matériel à supprimer
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités
     * @param TagAwareCacheInterface $cache Le cache utilisé pour invalider les tags
     * @return JsonResponse La réponse JSON contenant le matériel supprimé
     */
    #[Route('api/materiel/delete/{id}', name: 'materiel.hide', methods: ['PUT', 'PATCH'])]
    public function softDelete(MaterielRepository $materielRepository,SerializerInterface $serializer, $id, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $materiel->setStatus("off");
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $cache->invalidateTags(["materielCache"]);
        $entityManager->persist($materiel);
        $entityManager->flush();
        $context = SerializationContext::create()->setGroups(['get_materiel']);
        $jsonMateriel = $serializer->serialize($materiel,'json', $context);
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    /**
     * Restaure un matériel.
     *
     * @Route('api/materiel/restore/{id}', name='materiel.restore', methods=['PUT','PATCH'])
     *
     * @param MaterielRepository $materielRepository Le repository de matériel
     * @param SerializerInterface $serializer Le service de sérialisation
     * @param int $id L'identifiant du matériel à supprimer
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités
     * @param TagAwareCacheInterface $cache Le cache utilisé pour invalider les tags
     * @return JsonResponse La réponse JSON contenant le matériel supprimé
     */
    #[Route('api/materiel/restore/{id}', name: 'materiel.restore', methods: ['PUT', 'PATCH'])]
    public function restore(MaterielRepository $materielRepository,SerializerInterface $serializer, $id, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache): JsonResponse
    {
        $materiel = $materielRepository->find($id);
        $materiel->setStatus("en stock");
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $cache->invalidateTags(["materielCache"]);
        $entityManager->persist($materiel);
        $entityManager->flush();
        $context = SerializationContext::create()->setGroups(['get_materiel']);
        $jsonMateriel = $serializer->serialize($materiel,'json', $context);
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }

    /**
     * Ajoute un matériel.
     *
     * @Route('api/materiel', name='materiel.create', methods={'POST'})
     *
     * @param SerializerInterface $serializer Le service de sérialisation
     * @param Request $request La requête HTTP contenant les données du matériel à ajouter
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités
     * @param TagAwareCacheInterface $cache Le cache utilisé pour invalider les tags
     * @return JsonResponse La réponse JSON contenant le matériel ajouté
     */
    #[Route('api/materiel', name: 'materiel.create', methods: ['POST'])]
    public function addMateriel(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache): JsonResponse
    {
        $materiel = new Materiel();
        $data = json_decode($request->getContent(),true);
        $materiel->setName($data['name']);
        $materiel->setType($data['type']);
        $materiel->setAvailable($data['available']);
        $materiel->setStatus("En stock");
        $materiel->setCreatedAt(new DateTimeImmutable());
        $materiel->setUpdatedAt(new DateTimeImmutable());
        $cache->invalidateTags(["materielCache"]);
        $entityManager->persist($materiel);
        $entityManager->flush();
        $jsonMateriel = $serializer->serialize($materiel,'json');
        return new JsonResponse($jsonMateriel,Response::HTTP_OK,[],true);
    }
}
