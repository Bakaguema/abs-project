<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SessionController
{
    #[Route('/save-number', name: 'save_number', methods: ['GET', 'POST'])]
    public function saveNumber(Request $request, SessionInterface $session)
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['number']) ) {
            $number = $data['number'];
            $session->set('totalMessage', $number);
            return new JsonResponse('Number saved to session.');
        }
        
        
        return new JsonResponse('No number saved to session.');
    }
}