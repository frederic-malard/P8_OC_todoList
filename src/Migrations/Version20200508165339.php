<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use App\Repository\TaskRepository;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200508165339 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $manager = $this->container->get("doctrine.orm.entity_manager");

        $passwordEncoder = $this->container->get("security.password_encoder");

        $repository = $manager->getRepository(Task::class);

        $tasksWithNoUser = $repository->findBy(["user" => null]);

        $user = new User();

        $user->setUsername("anonyme");
        $user->setEmail("email@mail.fr");

        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                "AdminMdp"
            )
        );

        foreach ($tasksWithNoUser as $task) {
            $task->setUser($user);
        }

        $manager->persist($user);
        $manager->flush();

        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL');
    }
}
