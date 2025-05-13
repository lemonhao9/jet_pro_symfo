<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/category', name: 'app_api_category_')]

final class CategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private CategoryRepository $repository)
    {

    }
    #[Route(name: 'new', methods:'POST')]
#[OA\Post(
    path: "/api/category",
    summary: "Créer une catégorie",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "Données de la catégorie à créer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 201,
            description: "Catégorie créée avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom de la catégorie"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Catégorie non créée"
        )
    ]
)]
    public function new(): Response
    {
         $category = new Category();
         $category->setTitle(title:'Entrée');
         $category->setCreatedAt(new \DateTimeImmutable());
 
         $this->manager->persist($category);
         $this->manager->flush();
         //A stocker en base
 
         return $this->json(
             ['message'=>"Category ressource created with {$category->getId()} id"],
             Response::HTTP_CREATED,
         );
    }
 
    #[Route('/{id}', name: 'show', methods:'GET')]
   #[OA\Get(
    path: "/api/category/{id}",
    summary: "Afficher un restaurant par ID",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID de la catégorie à afficher",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Catégorie trouvée avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom du restaurant"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Catégorie non trouvé"
        )
    ]
)]
    public function show(int $id): Response
    {
        $category = $this->repository->findOneBy(['id' => $id]);
 
     if (!$category) {
         throw $this->createNotFoundException("No Category found for {$id} id");
     }
 
     return $this->json(
         ['message' => "A Category was found : {$category->getName()} for {$category->getId()} id"]
     );
 }
 
 #[Route('/{id}', name: 'edit', methods: 'PUT')]
   #[OA\Put(
    path: "/api/category/{id}",
    summary: "Modifier une catégorie par ID",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID de la catégorie à modifier",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "Catégorie modifiée avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nouveau nom du restaurant"),
                    new OA\Property(property: "updatedAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Catégorie non trouvé"
        )
    ]
)]
 public function edit(int $id): Response
 {
    $category = $this->repository->findOneBy(['id' => $id]);
 
     if (!$category) {
         throw $this->createNotFoundException("No Restaurant found for {$id} id");
     }
 
     $category->setTitle('Category title updated');
     $this->manager->flush();
 
     return $this->redirectToRoute('app_api_restaurant_show', ['id' => $category->getId()]);
 }
 
 #[Route('/{id}', name: 'delete', methods: 'DELETE')]
   #[OA\Delete(
    path: "/api/category/{id}",
    summary: "Supprimer une catégorie par ID",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID de la catégorie à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Catégorie supprimé avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom du restaurant"),
                    new OA\Property(property: "updatedAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Catégorie non supprimé"
        )
    ]
)]
 public function delete(int $id): Response
 {
    $category = $this->repository->findOneBy(['id' => $id]);
     if (!$category) {
         throw $this->createNotFoundException("No Category found for {$id} id");
     }
 
     $this->manager->remove($category);
     $this->manager->flush();
 
     return $this->json(['message' => "Category resource deleted"], Response::HTTP_NO_CONTENT);
 }
 }