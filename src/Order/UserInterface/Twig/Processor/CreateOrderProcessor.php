<?php

declare(strict_types=1);

namespace App\Order\UserInterface\Twig\Processor;

use App\Common\Application\Command\CommandBus;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\UseCase\PlaceOrder\PlaceOrderCommand;
use App\Order\Infrastructure\Symfony\Form\OrderFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class CreateOrderProcessor
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly Environment $view,
        private readonly FormFactoryInterface $formFactory,
        private readonly RouterInterface $router,
    ) {
    }

    #[Route(path: '/app/orders/create', name: 'app_order_processor_create', methods: ['GET', 'POST'], stateless: false)]
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(OrderFormType::class, ['items' => [['productId' => '', 'quantity' => 1]]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderDTO = $this->placeOrder($form->getData());

            return new RedirectResponse(
                $this->router->generate('app_order_provider', ['orderId' => $orderDTO->id])
            );
        }

        $response = $this->view->render('twig/order/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($response);
    }

    /**
     * @param array{items: array<int, array{productId: string, quantity: int}>} $data
     */
    private function placeOrder(array $data): OrderDTO
    {
        $items = $data['items'];
        if ($items instanceof \Traversable) {
            $items = iterator_to_array($items, false);
        }

        $normalizedItems = array_values(array_filter(
            array_map(
                static fn (array $item): array => [
                    'productId' => (string) ($item['productId'] ?? ''),
                    'quantity' => (int) ($item['quantity'] ?? 0),
                ],
                $items
            ),
            static fn (array $item): bool => '' !== trim($item['productId']) && $item['quantity'] > 0
        ));

        return $this->commandBus->dispatch(
            new PlaceOrderCommand(items: $normalizedItems)
        );
    }
}
