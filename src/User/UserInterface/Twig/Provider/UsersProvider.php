<?php

namespace App\User\UserInterface\Twig\Provider;

use App\Common\Application\Query\QueryBus;
use App\User\Application\DTO\UserDTO;
use App\User\Application\UseCase\SearchUsersPaginated\SearchUsersPaginatedQuery;
use App\User\UserInterface\ApiPlatform\Resource\UserResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class UsersProvider
{
    public function __construct(
        private readonly Environment $view,
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route(path: '/app/users', name: 'app_users_provider')]
    public function __invoke(Request $request): Response
    {
        $page = $request->get('page', 1);
        $itemsPerPage = $request->get('itemsPerPage', 10);

        $users = $this->getUsersDTOs($page, $itemsPerPage);

        $response = $this->view->render('twig/user/list.html.twig', [
            'users' => $this->mapUserDTOsToUsersResources($users),
        ]);

        return new Response($response);
    }

    /**
     * @return UserDTO[]
     */
    private function getUsersDTOs(int $page, int $itemsPerPage): array
    {
        return $this->queryBus->ask(new SearchUsersPaginatedQuery($page, $itemsPerPage));
    }

    /**
     * @return UserResource[]
     */
    private function mapUserDTOsToUsersResources(array $usersDTOs): array
    {
        $resources = [];
        foreach ($usersDTOs as $userDTO) {
            $resources[] = UserResource::fromUserDTO($userDTO);
        }

        return $resources;
    }
}