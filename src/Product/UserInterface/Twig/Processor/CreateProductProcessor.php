<?php

declare(strict_types=1);

namespace App\Product\UserInterface\Twig\Processor;

use App\Common\Application\Command\CommandBus;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Application\UseCase\CreateProduct\CreateProductCommand;
use App\Product\Infrastructure\Symfony\Form\ProductFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class CreateProductProcessor
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly Environment $view,
        private readonly FormFactoryInterface $formFactory,
        private readonly RouterInterface $router,
    ) {
    }

    #[Route(path: '/app/products/create', name: 'app_product_processor_create', methods: ['GET', 'POST'], stateless: false)]
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ProductFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productDTO = $this->createProduct($form->getData());

            return new RedirectResponse(
                $this->router->generate('app_product_provider', ['productId' => $productDTO->id])
            );
        }

        $response = $this->view->render('twig/product/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($response);
    }

    /**
     * @param array{name:string,description:?string,priceAmount:int,priceCurrency:string,stock:int} $data
     */
    private function createProduct(array $data): ProductDTO
    {
        return $this->commandBus->dispatch(
            new CreateProductCommand(
                name: (string) $data['name'],
                description: $data['description'],
                priceAmount: (int) $data['priceAmount'],
                priceCurrency: (string) $data['priceCurrency'],
                initialStock: (int) $data['stock'],
            )
        );
    }
}
