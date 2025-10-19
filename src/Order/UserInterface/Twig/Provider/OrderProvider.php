<?php

declare(strict_types=1);

namespace App\Order\UserInterface\Twig\Provider;

use App\Common\Application\Query\QueryBus;
use App\Order\Application\UseCase\GetOrderById\GetOrderByIdQuery;
use App\Order\UserInterface\ApiPlatform\Resource\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class OrderProvider
{
    public function __construct(private readonly Environment $view, private readonly QueryBus $queryBus)
    {
    }

    #[Route(path: '/app/orders/{orderId}', name: 'app_order_provider')]
    public function __invoke(string $orderId): Response
    {
        $orderDTO = $this->queryBus->ask(new GetOrderByIdQuery($orderId));

        $response = $this->view->render('twig/order/show.html.twig', [
            'order' => OrderResource::fromOrderDTO($orderDTO),
        ]);

        return new Response($response);
    }
}
