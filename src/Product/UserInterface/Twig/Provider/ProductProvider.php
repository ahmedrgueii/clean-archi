<?php

declare(strict_types=1);

namespace App\Product\UserInterface\Twig\Provider;

use App\Common\Application\Query\QueryBus;
use App\Product\Application\UseCase\GetProductById\GetProductByIdQuery;
use App\Product\UserInterface\ApiPlatform\Resource\ProductResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class ProductProvider
{
    public function __construct(private readonly Environment $view, private readonly QueryBus $queryBus)
    {
    }

    #[Route(path: '/app/products/{productId}', name: 'app_product_provider')]
    public function __invoke(string $productId): Response
    {
        $productDTO = $this->queryBus->ask(new GetProductByIdQuery($productId));

        $response = $this->view->render('twig/product/show.html.twig', [
            'product' => ProductResource::fromProductDTO($productDTO),
        ]);

        return new Response($response);
    }
}
