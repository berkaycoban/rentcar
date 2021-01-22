<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122114158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, car_id_id INT NOT NULL, customer_id_id INT NOT NULL, pickup_date DATETIME NOT NULL, pickup_car_km INT NOT NULL, return_date DATETIME DEFAULT NULL, return_car_km INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_723705D1A0EF1B80 (car_id_id), INDEX IDX_723705D1B171EB6C (customer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A0EF1B80 FOREIGN KEY (car_id_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B171EB6C FOREIGN KEY (customer_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car ADD available TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE car DROP available');
    }
}
