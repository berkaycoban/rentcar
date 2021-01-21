<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121143417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, owner_id_id INT NOT NULL, brand VARCHAR(100) NOT NULL, model VARCHAR(255) NOT NULL, year INT NOT NULL, fuel VARCHAR(30) NOT NULL, gear VARCHAR(30) NOT NULL, airbag TINYINT(1) NOT NULL, luggage_volume INT DEFAULT NULL, seats INT DEFAULT NULL, air_conditioning TINYINT(1) NOT NULL, min_license_year INT NOT NULL, min_driver_age INT NOT NULL, km INT NOT NULL, daily_price INT NOT NULL, daily_max_km INT NOT NULL, img_src VARCHAR(255) DEFAULT NULL, img_alt VARCHAR(255) NOT NULL, class VARCHAR(40) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_773DE69D8FDDAB70 (owner_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D8FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES company (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE car');
    }
}
