<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/restaurant', name: 'app_api_restaurant_')]

final class RestaurantController extends AbstractController
{
    #[Route(name: 'new', methods:'POST')]
   public function new(): Response
   {

   }

   #[Route('/show', name: 'show', methods:'GET')]
   public function show(): Response
   {
    return $this->json(['message'=> 'Restaurant de ma BDD']);
   }

   #[Route('/', name: 'edit', methods: 'PUT')]
   public function edit(): Response
   {

   }

   #[Route('/', name: 'delete', methods: 'DELETE')]
   public function delete(): Response
   {

   }
}