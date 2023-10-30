<?php

namespace App\User\UserInterface\Vue;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class EntryPoint
{
    #[Route(path: '/vue/{vueRouting}', name: 'app_vue_entry_point', defaults: ['vueRouting' => null])]
    public function __invoke(Environment $view): Response
    {
        return new Response($view->render('vue/entry.html.twig'));
    }
}
