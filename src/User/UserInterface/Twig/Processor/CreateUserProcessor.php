<?php

declare(strict_types=1);

namespace App\User\UserInterface\Twig\Processor;

use App\Common\Application\Command\CommandBus;
use App\Common\Domain\Exception\InvalidFormat;
use App\User\Application\DTO\UserDTO;
use App\User\Application\UseCase\CreateUser\CreateUserCommand;
use App\User\Domain\Exception\EmailAlreadyUsed;
use App\User\Infrastructure\Symfony\Form\UserFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class CreateUserProcessor
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly Environment $view,
        private readonly FormFactoryInterface $formFactory,
        private readonly RouterInterface $router,
    ) {
    }

    #[Route(path: '/app/users/create', name: 'app_user_processor_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userDTO = $this->createUserAndReturnUserDTO($form->getData());
            } catch (EmailAlreadyUsed|InvalidFormat $exception) {
                throw new BadRequestException($exception->getMessage());
            }

            return new RedirectResponse(
                $this->router->generate('app_user_provider', ['userId' => $userDTO->id])
            );
        }

        $response = $this->view->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($response);
    }

    /**
     * @throws EmailAlreadyUsed|InvalidFormat
     */
    private function createUserAndReturnUserDTO(array $userResource): UserDTO
    {
        return $this->commandBus
            ->dispatch(
                new CreateUserCommand(
                    firstName: $userResource['firstName'],
                    lastName: $userResource['lastName'],
                    email: $userResource['email'],
                )
            );
    }
}
