<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203125007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init database';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE energy_consumption (id INT AUTO_INCREMENT NOT NULL, energy_type_id INT NOT NULL, region_id INT NOT NULL, measure_value DOUBLE PRECISION DEFAULT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_66CFF82380726647 (energy_type_id), INDEX IDX_66CFF82398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE energy_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, name_slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_locked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_84E131EDF2B4115 (name_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, name_slug VARCHAR(150) NOT NULL, code_insee INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_locked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F62F176DF2B4115 (name_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE energy_consumption ADD CONSTRAINT FK_66CFF82380726647 FOREIGN KEY (energy_type_id) REFERENCES energy_type (id)');
        $this->addSql('ALTER TABLE energy_consumption ADD CONSTRAINT FK_66CFF82398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE energy_consumption DROP FOREIGN KEY FK_66CFF82380726647');
        $this->addSql('ALTER TABLE energy_consumption DROP FOREIGN KEY FK_66CFF82398260155');
        $this->addSql('DROP TABLE energy_consumption');
        $this->addSql('DROP TABLE energy_type');
        $this->addSql('DROP TABLE region');
    }
}
