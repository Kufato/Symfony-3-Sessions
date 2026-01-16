<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116144839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD updated_at DATETIME DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D896DBBDE ON post (updated_by_id)');
        $this->addSql('ALTER TABLE vote CHANGE value value INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A108564A76ED3954B89032C ON vote (user_id, post_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D896DBBDE');
        $this->addSql('DROP INDEX IDX_5A8A6C8D896DBBDE ON post');
        $this->addSql('ALTER TABLE post DROP updated_at, DROP updated_by_id');
        $this->addSql('DROP INDEX UNIQ_5A108564A76ED3954B89032C ON vote');
        $this->addSql('ALTER TABLE vote CHANGE value value SMALLINT NOT NULL');
    }
}
