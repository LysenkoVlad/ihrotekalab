<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed-catalog', description: 'Додати відсутні демонстраційні ігри.')]
final class SeedCatalogCommand
{
    private const SHELVES = [
        'Стратегії' => [
            ['Портові крани', 2, 4, 45, 'Розподіліть вантажі між причалами до вечірнього припливу. Виграє порт, який проведе найбільше суден без черги.'],
            ['Мідні сади', 2, 5, 60, 'Прокладайте канали між терасами й обирайте, які сади полити першими. Кожна нова гребля змінює план сусідів.'],
        ],
        'Кооперативні' => [
            ['Останній маяк', 1, 4, 35, 'Команда доглядачів збирає лінзу маяка до приходу туману. На кожному ході вирішіть разом, кому дістати деталь, а кому підтримувати вогонь.'],
            ['Лісова пошта', 2, 4, 25, 'Доставте листи до лісових станцій, поки стежки не замело. Для короткого маршруту доведеться обмінюватися картами.'],
        ],
        'Абстрактні' => [
            ['Скляні орбіти', 2, 2, 20, 'Обертайте кільця зоряної карти, щоб зібрати власне сузір’я. Кожен рух пересуває і ваші фішки, і фішки суперника.'],
            ['Тіні мостів', 2, 3, 30, 'З’єднуйте острови тонкими дерев’яними мостами. Враховуйте їхні тіні, які закривають клітинки для наступного ходу.'],
        ],
    ];

    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(OutputInterface $output): int
    {
        $added = 0;
        foreach (self::SHELVES as $name => $rows) {
            $category = $this->em->getRepository(Category::class)->findOneBy(['name' => $name]);
            if (!$category) {
                $category = new Category();
                $category->setName($name);
                $this->em->persist($category);
            }
            foreach ($rows as [$title, $min, $max, $minutes, $description]) {
                if ($this->em->getRepository(Game::class)->findOneBy(['title' => $title])) {
                    continue;
                }
                $game = new Game();
                $game->setCategory($category);
                $game->setTitle($title);
                $game->setMinPlayers($min);
                $game->setMaxPlayers($max);
                $game->setMinutes($minutes);
                $game->setDescription($description);
                $this->em->persist($game);
                ++$added;
            }
        }
        $this->em->flush();
        $output->writeln("Додано ігор: $added");

        return Command::SUCCESS;
    }
}
