<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220115162448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode_downloads (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', episode_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', podcast_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', occured_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episodes (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', podcast_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7DD55EDD786136AB (podcast_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcasts (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode_downloads ADD CONSTRAINT FK_F5B1328CC5B772 FOREIGN KEY (podcast_id) REFERENCES podcasts (id)');
        $this->addSql('ALTER TABLE episode_downloads ADD CONSTRAINT FK_F5B1328C444E6803 FOREIGN KEY (episode_id) REFERENCES episodes (id)');
        $this->addSql('ALTER TABLE episodes ADD CONSTRAINT FK_7DD55EDD786136AB FOREIGN KEY (podcast_id) REFERENCES podcasts (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episodes DROP FOREIGN KEY FK_7DD55EDD786136AB');
        $this->addSql('DROP TABLE episode_downloads');
        $this->addSql('DROP TABLE episodes');
        $this->addSql('DROP TABLE podcasts');
    }
}
