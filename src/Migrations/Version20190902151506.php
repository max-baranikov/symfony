<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190902151506 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE book ADD file BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE book ADD cover BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE book DROP author_id');
        $this->addSql('ALTER TABLE book DROP book_filename');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD book_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE book DROP file');
        $this->addSql('ALTER TABLE book DROP cover');
    }
}
