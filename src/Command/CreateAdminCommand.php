<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin', description: 'Створити адміністратора ігротеки.')]
final class CreateAdminCommand
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $users,
        private UserPasswordHasherInterface $hasher,
    ) {}

    public function __invoke(#[Argument] string $email, SymfonyStyle $io): int
    {
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 180) {
            $io->error('Введіть дійсну адресу електронної пошти.');
            return Command::INVALID;
        }
        if ($this->users->findOneBy(['email' => $email])) {
            $io->error('Ця адреса вже зареєстрована.');
            return Command::FAILURE;
        }

        $password = $io->askHidden('Пароль (щонайменше 8 символів)');
        if (!is_string($password) || mb_strlen($password) < 8) {
            $io->error('Пароль надто короткий.');
            return Command::INVALID;
        }
        $user = (new User())->setEmail($email)->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $this->em->persist($user);
        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException) {
            $io->error('Ця адреса вже зареєстрована.');
            return Command::FAILURE;
        }
        $io->success('Адміністратора створено.');
        return Command::SUCCESS;
    }
}
