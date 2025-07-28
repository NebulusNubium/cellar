<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728073658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, stock_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks_bottles (stocks_id INT NOT NULL, bottles_id INT NOT NULL, INDEX IDX_5FE80751FACB6020 (stocks_id), INDEX IDX_5FE807514B11BD50 (bottles_id), PRIMARY KEY(stocks_id, bottles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stocks_bottles ADD CONSTRAINT FK_5FE80751FACB6020 FOREIGN KEY (stocks_id) REFERENCES stocks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stocks_bottles ADD CONSTRAINT FK_5FE807514B11BD50 FOREIGN KEY (bottles_id) REFERENCES bottles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cellars ADD stocks_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cellars ADD CONSTRAINT FK_CF295FFDFACB6020 FOREIGN KEY (stocks_id) REFERENCES stocks (id)');
        $this->addSql('CREATE INDEX IDX_CF295FFDFACB6020 ON cellars (stocks_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cellars DROP FOREIGN KEY FK_CF295FFDFACB6020');
        $this->addSql('ALTER TABLE stocks_bottles DROP FOREIGN KEY FK_5FE80751FACB6020');
        $this->addSql('ALTER TABLE stocks_bottles DROP FOREIGN KEY FK_5FE807514B11BD50');
        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP TABLE stocks_bottles');
        $this->addSql('DROP INDEX IDX_CF295FFDFACB6020 ON cellars');
        $this->addSql('ALTER TABLE cellars DROP stocks_id');
    }
}
