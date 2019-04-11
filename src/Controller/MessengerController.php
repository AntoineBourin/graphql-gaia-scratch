<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MessengerController extends AbstractController
{
    public function sendMessageAction(Request $request, User $user, MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $data = [
            'message' => $request->request->get('message'),
            'from' => $user,
        ];

        $topic = [sprintf("http://gaia.com/user/%d", $user->getId())];
        $update = new Update($topic, $serializer->serialize($data, 'json', ['groups' => 'public']));
        $bus->dispatch($update);

        return new JsonResponse(null, 202);
    }
}
