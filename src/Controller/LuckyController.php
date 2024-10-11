<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky/number', name: 'app_lucky')]
    public function number(): Response
    {
        $number = random_int(0, 100);
        return $this->render('lucky/index.html.twig', [
            'number' => $number,
        ]);
    }

    #[Route('/lucky/number/{max}', name: 'app_lucky_number')]
    public function numberMax(
        int $max,
        #[Autowire(service: 'monolog.logger.request')]
        LoggerInterface $logger
    ): Response
    {
        $number = random_int(0, $max);
        $logger->info("Lucky number $number");

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}
