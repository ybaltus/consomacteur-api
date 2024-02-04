<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240204120444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add OpenDataRaw table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE open_data_raw (id INT AUTO_INCREMENT NOT NULL, code_insee INT NOT NULL, region VARCHAR(150) NOT NULL, measure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', consum_electric INT NOT NULL, consum_thermic INT NOT NULL, consum_nuclear INT NOT NULL, consum_wind INT NOT NULL, consum_solar INT NOT NULL, consum_hydraulic INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE open_data_raw');
    }
}
