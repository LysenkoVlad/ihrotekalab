<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
final class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_game_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Ігротека');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Ігри', 'fa fa-dice', Game::class);
        yield MenuItem::linkToCrud('Категорії', 'fa fa-tags', Category::class);
        yield MenuItem::linkToRoute('До каталогу', 'fa fa-book', 'catalog');
        yield MenuItem::linkToLogout('Вийти', 'fa fa-sign-out');
    }
}
