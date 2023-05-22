<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517175623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD to_do_list_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_list (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25B3AB48EB ON task (to_do_list_id)');
        $this->addSql('ALTER TABLE to_do_list ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_do_list ADD CONSTRAINT FK_4A6048ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4A6048ECA76ED395 ON to_do_list (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B3AB48EB');
        $this->addSql('DROP INDEX IDX_527EDB25B3AB48EB ON task');
        $this->addSql('ALTER TABLE task DROP to_do_list_id');
        $this->addSql('ALTER TABLE to_do_list DROP FOREIGN KEY FK_4A6048ECA76ED395');
        $this->addSql('DROP INDEX IDX_4A6048ECA76ED395 ON to_do_list');
        $this->addSql('ALTER TABLE to_do_list DROP user_id');
    }
}
