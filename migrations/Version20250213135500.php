<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213135500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, company_name VARCHAR(255) DEFAULT NULL, type_of_activity VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_position VARCHAR(255) DEFAULT NULL, contact_number VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_C7440455A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, job_type_id INT NOT NULL, category_id INT NOT NULL, client_id INT NOT NULL, reference VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, is_active TINYINT(1) NOT NULL, job_title VARCHAR(255) NOT NULL, job_location VARCHAR(255) DEFAULT NULL, closing_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', salary INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_288A3A4E5FA33B08 (job_type_id), INDEX IDX_288A3A4E12469DE2 (category_id), INDEX IDX_288A3A4E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E5FA33B08 FOREIGN KEY (job_type_id) REFERENCES job_offer_type (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E5FA33B08');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E12469DE2');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E19EB6921');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE job_offer');
    }
}
