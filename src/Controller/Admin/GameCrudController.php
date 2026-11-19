<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

final class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Гра')->setEntityLabelInPlural('Ігри');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Назва');
        yield AssociationField::new('category', 'Категорія');
        yield IntegerField::new('minPlayers', 'Мінімум гравців');
        yield IntegerField::new('maxPlayers', 'Максимум гравців');
        yield IntegerField::new('minutes', 'Тривалість (хв)');
        yield TextareaField::new('description', 'Опис');
    }
}
