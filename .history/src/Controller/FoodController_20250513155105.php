<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/food', name: 'app_api_food_')]
final class FoodController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private FoodRepository $repository)
    {

    }

    #[Route(name:'new',methods: 'POST')]
 #[OA\Post(
    path: "/api/food",
    summary: "Créer un plat",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "Données du plat à créer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 201,
            description: "Plat créé avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom du plat"),
                    new OA\Property(property: "price", type: "integer", example: "15"),
                    new OA\Property(property: "description", type: "string", example: "Description du plat"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Plat non créé"
        )
    ]
)]
    public function new():Response
    {
        $food = new Food();
        $food->setTitle(title:'Tomate Mozza');
        $food->setDescription(description:'Plat de Tomates, et de Mozzarela');
        $food->setPrice(15);
        $food->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($food);
        $this->manager->flush();

        return $this->json(
            ['message'=> "Food ressource created with {$food->getId()} id"],
            Response::HTTP_CREATED,
        );
    }

    #[Route('/{id}', name:'show',methods: 'GET')]
   #[OA\Get(
    path: "/api/food/{id}",
    summary: "Afficher un plat par ID",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID du plat à afficher",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Plat trouvé avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom du plat"),
                    new OA\Property(property: "price", type: "integer", example: "15"),
                    new OA\Property(property: "description", type: "string", example: "Description du plat"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Plat non trouvé"
        )
    ]
)]
    public function show(int $id):Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw $this->createNotFoundException("No Restaurant found for {$id} id");
        }
    
        return $this->json(
            ['message' => "A food was found : {$food->getName()} for {$food->getId()} id"]
        );
    }

    #[Route('/{id}', name:'edit',methods: 'PUT')]
   #[OA\Put(
    path: "/api/food/{id}",
    summary: "Modifier un plat par ID",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID du plat à modifier",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "Plat modifié avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom du plat"),
                    new OA\Property(property: "price", type: "integer", example: "15"),
                    new OA\Property(property: "description", type: "string", example: "Description du plat"),
                    new OA\Property(property: "updatedAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Restaurant non trouvé"
        )
    ]
)]
    public function edit(int $id):Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

    if (!$food) {
        throw $this->createNotFoundException("No food found for {$id} id");
    }

    $food->setName('Food name updated');
    $this->manager->flush();

    return $this->redirectToRoute('app_api_food_show', ['id' => $food->getId()]);
    }

    #[Route('/{id}', name:'delete',methods: 'DELETE')]
   #[OA\Delete(
    path: "/api/food/{id}",
    summary: "Supprimer un plat par ID",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID du plat à supprimer",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Plat supprimé avec succès",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Nom du plat"),
                    new OA\Property(property: "price", type: "integer", example: "15"),
                    new OA\Property(property: "description", type: "string", example: "Description du plat"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time")
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Restaurant non trouvé"
        )
    ]
)]
    public function delete(int $id):Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);
        if (!$food) {
            throw $this->createNotFoundException("No food found for {$id} id");
        }
    
        $this->manager->remove($food);
        $this->manager->flush();

        return $this->json(['message' => "Food resource deleted"], Response::HTTP_NO_CONTENT);
    }
}
