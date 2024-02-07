<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207113742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE electric (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', measure_value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eolien (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', measure_value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hydraulic (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', measure_value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nuclear (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', measure_value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solar (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', measure_value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thermic (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', measure_value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE electric');
        $this->addSql('DROP TABLE eolien');
        $this->addSql('DROP TABLE hydraulic');
        $this->addSql('DROP TABLE nuclear');
        $this->addSql('DROP TABLE solar');
        $this->addSql('DROP TABLE thermic');
    }
}
