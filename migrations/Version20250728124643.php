<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728124643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, cellar_id INT NOT NULL, wine_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX IDX_B12D4A36D4A8C468 (cellar_id), INDEX IDX_B12D4A3628A2BD76 (wine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36D4A8C468 FOREIGN KEY (cellar_id) REFERENCES cellars (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3628A2BD76 FOREIGN KEY (wine_id) REFERENCES bottles (id)');
        $this->addSql('ALTER TABLE cellars DROP FOREIGN KEY FK_CF295FFDFACB6020');
        $this->addSql('DROP INDEX IDX_CF295FFDFACB6020 ON cellars');
        $this->addSql('ALTER TABLE cellars DROP stocks_iduseless');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36D4A8C468');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3628A2BD76');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('ALTER TABLE cellars ADD stocks_iduseless INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cellars ADD CONSTRAINT FK_CF295FFDFACB6020 FOREIGN KEY (stocks_iduseless) REFERENCES stocks (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CF295FFDFACB6020 ON cellars (stocks_iduseless)');
    }
}
