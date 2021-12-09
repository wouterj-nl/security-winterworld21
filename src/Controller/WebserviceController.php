<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class WebserviceController extends AbstractController
{
    #[Route('/webservice', name: 'webservice')]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return new Response('<message>missing credentials</message>', Response::HTTP_UNAUTHORIZED, ['Content-Type' => 'text/xml']);
        }

        return new Response('<message>Hi '.$user->getEmail().'</message>', 200, ['Content-Type' => 'text/xml']);
    }
}
