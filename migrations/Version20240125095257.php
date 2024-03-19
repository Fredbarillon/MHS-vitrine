<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125095257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boss (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3EFE663AE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(150) NOT NULL, last_name VARCHAR(150) NOT NULL, first_name VARCHAR(150) NOT NULL, telephone INT NOT NULL, address VARCHAR(255) NOT NULL, zip_code VARCHAR(5) NOT NULL, city VARCHAR(100) NOT NULL, buyer TINYINT(1) NOT NULL, seller TINYINT(1) NOT NULL, just_want_info TINYINT(1) NOT NULL, message LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_4C62E638E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_mobilhome (contact_id INT NOT NULL, mobilhome_id INT NOT NULL, INDEX IDX_139DA9EFE7A1254A (contact_id), INDEX IDX_139DA9EF193388D3 (mobilhome_id), PRIMARY KEY(contact_id, mobilhome_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, mobilhome_id INT DEFAULT NULL, contact_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, position INT DEFAULT NULL, INDEX IDX_C53D045F193388D3 (mobilhome_id), INDEX IDX_C53D045FE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mobilhome (id INT AUTO_INCREMENT NOT NULL, boss_id INT NOT NULL, length DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION DEFAULT NULL, brand VARCHAR(150) NOT NULL, model VARCHAR(150) NOT NULL, year INT NOT NULL, nb_bedroom INT NOT NULL, price INT NOT NULL, central_livingroom TINYINT(1) NOT NULL, oven TINYINT(1) NOT NULL, ac TINYINT(1) NOT NULL, double_glazing TINYINT(1) NOT NULL, stock INT DEFAULT 1 NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(1000) DEFAULT NULL, sales_arguments VARCHAR(255) DEFAULT NULL, INDEX IDX_7D6C8A94261FB672 (boss_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_mobilhome ADD CONSTRAINT FK_139DA9EFE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_mobilhome ADD CONSTRAINT FK_139DA9EF193388D3 FOREIGN KEY (mobilhome_id) REFERENCES mobilhome (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F193388D3 FOREIGN KEY (mobilhome_id) REFERENCES mobilhome (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE mobilhome ADD CONSTRAINT FK_7D6C8A94261FB672 FOREIGN KEY (boss_id) REFERENCES boss (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_mobilhome DROP FOREIGN KEY FK_139DA9EFE7A1254A');
        $this->addSql('ALTER TABLE contact_mobilhome DROP FOREIGN KEY FK_139DA9EF193388D3');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F193388D3');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FE7A1254A');
        $this->addSql('ALTER TABLE mobilhome DROP FOREIGN KEY FK_7D6C8A94261FB672');
        $this->addSql('DROP TABLE boss');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_mobilhome');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE mobilhome');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
