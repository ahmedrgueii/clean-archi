<?php

namespace App\User\UserInterface\Twig\Provider;

use App\Common\Application\Query\QueryBus;
use App\User\Application\DTO\UserDTO;
use App\User\Application\UseCase\GetUserById\GetUserByIdQuery;
use App\User\Domain\Exception\UserNotFound;
use App\User\UserInterface\ApiPlatform\Resource\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class UserProvider
{
    public function __construct(
        private readonly Environment $view,
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route(path: '/app/users/{userId}', name: 'app_user_provider')]
    public function __invoke(string $userId): Response
    {
        try {
            $userDTO = $this->getUserById($userId);
        } catch (UserNotFound $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        $response = $this->view->render('twig/user/show.html.twig', [
            'user' => UserResource::fromUserDTO($userDTO),
        ]);

        return new Response($response);
    }

    /**
     * @throws UserNotFound
     */
    private function getUserById(string $userId): UserDTO
    {
        return $this->queryBus->ask(new GetUserByIdQuery($userId));
    }
}