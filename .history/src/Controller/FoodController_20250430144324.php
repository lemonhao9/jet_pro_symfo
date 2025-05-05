<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/food', name: 'app_api_food_')]
final class FoodController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private FoodRepository $repository)
    {

    }

    #[Route(name:'new',methods: 'POST')]
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
