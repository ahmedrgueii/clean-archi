<?php

declare(strict_types=1);

namespace App\Product\UserInterface\Twig\Provider;

use App\Common\Application\Query\QueryBus;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Application\UseCase\SearchProductsPaginated\SearchProductsPaginatedQuery;
use App\Product\UserInterface\ApiPlatform\Resource\ProductResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class ProductsProvider
{
    public function __construct(private readonly Environment $view, private readonly QueryBus $queryBus)
    {
    }

    #[Route(path: '/app/products', name: 'app_products_provider')]
    public function __invoke(Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $itemsPerPage = (int) $request->get('itemsPerPage', 10);

        $products = $this->queryBus->ask(new SearchProductsPaginatedQuery($page, $itemsPerPage));

        $response = $this->view->render('twig/product/list.html.twig', [
            'products' => $this->mapToResources($products),
        ]);

        return new Response($response);
    }

    /**
     * @param ProductDTO[] $products
     * @return ProductResource[]
     */
    private function mapToResources(array $products): array
    {
        return array_map(static fn (ProductDTO $product): ProductResource => ProductResource::fromProductDTO($product), $products);
    }
}
