<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722115312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cellar_bottles (cellars_id INT NOT NULL, bottles_id INT NOT NULL, INDEX IDX_B4F4E6DEA4CCFC8B (cellars_id), INDEX IDX_B4F4E6DE4B11BD50 (bottles_id), PRIMARY KEY(cellars_id, bottles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cellar_bottles ADD CONSTRAINT FK_B4F4E6DEA4CCFC8B FOREIGN KEY (cellars_id) REFERENCES cellars (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cellar_bottles ADD CONSTRAINT FK_B4F4E6DE4B11BD50 FOREIGN KEY (bottles_id) REFERENCES bottles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cellars_bottles DROP FOREIGN KEY FK_8EFE91954B11BD50');
        $this->addSql('ALTER TABLE cellars_bottles DROP FOREIGN KEY FK_8EFE9195A4CCFC8B');
        $this->addSql('DROP TABLE cellars_bottles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cellars_bottles (cellars_id INT NOT NULL, bottles_id INT NOT NULL, INDEX IDX_8EFE9195A4CCFC8B (cellars_id), INDEX IDX_8EFE91954B11BD50 (bottles_id), PRIMARY KEY(cellars_id, bottles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cellars_bottles ADD CONSTRAINT FK_8EFE91954B11BD50 FOREIGN KEY (bottles_id) REFERENCES bottles (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cellars_bottles ADD CONSTRAINT FK_8EFE9195A4CCFC8B FOREIGN KEY (cellars_id) REFERENCES cellars (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cellar_bottles DROP FOREIGN KEY FK_B4F4E6DEA4CCFC8B');
        $this->addSql('ALTER TABLE cellar_bottles DROP FOREIGN KEY FK_B4F4E6DE4B11BD50');
        $this->addSql('DROP TABLE cellar_bottles');
    }
}
