<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    // Configura los campos que se mostrarán en la vista de CRUD
    public function configureFields(string $pageName): iterable
    {
        return [
            
            IdField::new('id')->hideOnForm(), // Este campo se oculta en el formulario
            TextField::new('email', 'Email'), // Campo para el email de la pregunta
            TextField::new('password', 'Contraseña')->setRequired(false), // Campo para la contraseña
            ChoiceField::new('roles', 'Roles') // Campo para los roles
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ])
                ->allowMultipleChoices() // Permite seleccionar múltiples roles
                ->setRequired(false), // No es obligatorio
                    
            
            
        ];
    }

    
    
}
