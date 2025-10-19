<?php

declare(strict_types=1);

namespace App\Order\UserInterface\Twig\Provider;

use App\Common\Application\Query\QueryBus;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\UseCase\SearchOrdersPaginated\SearchOrdersPaginatedQuery;
use App\Order\UserInterface\ApiPlatform\Resource\OrderResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class OrdersProvider
{
    public function __construct(private readonly Environment $view, private readonly QueryBus $queryBus)
    {
    }

    #[Route(path: '/app/orders', name: 'app_orders_provider')]
    public function __invoke(Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $itemsPerPage = (int) $request->get('itemsPerPage', 10);

        $orders = $this->queryBus->ask(new SearchOrdersPaginatedQuery($page, $itemsPerPage));

        $response = $this->view->render('twig/order/list.html.twig', [
            'orders' => $this->mapToResources($orders),
        ]);

        return new Response($response);
    }

    /**
     * @param OrderDTO[] $orders
     * @return OrderResource[]
     */
    private function mapToResources(array $orders): array
    {
        return array_map(static fn (OrderDTO $order): OrderResource => OrderResource::fromOrderDTO($order), $orders);
    }
}
