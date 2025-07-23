<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723115809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_bottles (user_id INT NOT NULL, bottles_id INT NOT NULL, INDEX IDX_58AEDDD3A76ED395 (user_id), INDEX IDX_58AEDDD34B11BD50 (bottles_id), PRIMARY KEY(user_id, bottles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_bottles ADD CONSTRAINT FK_58AEDDD3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_bottles ADD CONSTRAINT FK_58AEDDD34B11BD50 FOREIGN KEY (bottles_id) REFERENCES bottles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_bottles DROP FOREIGN KEY FK_58AEDDD3A76ED395');
        $this->addSql('ALTER TABLE user_bottles DROP FOREIGN KEY FK_58AEDDD34B11BD50');
        $this->addSql('DROP TABLE user_bottles');
    }
}
