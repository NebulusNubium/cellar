<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721145156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bottles (id INT AUTO_INCREMENT NOT NULL, regions_id INT NOT NULL, countries_id INT NOT NULL, name VARCHAR(255) NOT NULL, year INT NOT NULL, grapes VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A3C3D9FCE83E5F (regions_id), INDEX IDX_A3C3D9AEBAE514 (countries_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bottles_cellars (bottles_id INT NOT NULL, cellars_id INT NOT NULL, INDEX IDX_C04E35E14B11BD50 (bottles_id), INDEX IDX_C04E35E1A4CCFC8B (cellars_id), PRIMARY KEY(bottles_id, cellars_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cellars (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_CF295FFDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bottles ADD CONSTRAINT FK_A3C3D9FCE83E5F FOREIGN KEY (regions_id) REFERENCES regions (id)');
        $this->addSql('ALTER TABLE bottles ADD CONSTRAINT FK_A3C3D9AEBAE514 FOREIGN KEY (countries_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE bottles_cellars ADD CONSTRAINT FK_C04E35E14B11BD50 FOREIGN KEY (bottles_id) REFERENCES bottles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bottles_cellars ADD CONSTRAINT FK_C04E35E1A4CCFC8B FOREIGN KEY (cellars_id) REFERENCES cellars (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cellars ADD CONSTRAINT FK_CF295FFDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bottles DROP FOREIGN KEY FK_A3C3D9FCE83E5F');
        $this->addSql('ALTER TABLE bottles DROP FOREIGN KEY FK_A3C3D9AEBAE514');
        $this->addSql('ALTER TABLE bottles_cellars DROP FOREIGN KEY FK_C04E35E14B11BD50');
        $this->addSql('ALTER TABLE bottles_cellars DROP FOREIGN KEY FK_C04E35E1A4CCFC8B');
        $this->addSql('ALTER TABLE cellars DROP FOREIGN KEY FK_CF295FFDA76ED395');
        $this->addSql('DROP TABLE bottles');
        $this->addSql('DROP TABLE bottles_cellars');
        $this->addSql('DROP TABLE cellars');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE regions');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
