<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406123219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE athlete ADD country_id INT NOT NULL, ADD discipline_id INT NOT NULL');
        $this->addSql('ALTER TABLE athlete ADD CONSTRAINT FK_C03B8321F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE athlete ADD CONSTRAINT FK_C03B8321A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('CREATE INDEX IDX_C03B8321F92F3E70 ON athlete (country_id)');
        $this->addSql('CREATE INDEX IDX_C03B8321A5522701 ON athlete (discipline_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE athlete DROP FOREIGN KEY FK_C03B8321F92F3E70');
        $this->addSql('ALTER TABLE athlete DROP FOREIGN KEY FK_C03B8321A5522701');
        $this->addSql('DROP INDEX IDX_C03B8321F92F3E70 ON athlete');
        $this->addSql('DROP INDEX IDX_C03B8321A5522701 ON athlete');
        $this->addSql('ALTER TABLE athlete DROP country_id, DROP discipline_id');
    }
}
