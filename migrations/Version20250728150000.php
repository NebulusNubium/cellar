<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250728150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add rating table and relation to bottles';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, bottle_id INT NOT NULL, user_id INT NOT NULL, value INT NOT NULL, INDEX IDX_D889262B83917B06 (bottle_id), INDEX IDX_D889262BA76ED395 (user_id), UNIQUE INDEX rating_unique_user_bottle (bottle_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262B83917B06 FOREIGN KEY (bottle_id) REFERENCES bottles (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE rating');
    }
}

