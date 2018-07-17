<?php declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180716075731 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE kingdom_resource (id INT AUTO_INCREMENT NOT NULL, kingdom_id INT NOT NULL, resource_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_3792A6DD6976FEC0 (kingdom_id), INDEX IDX_3792A6DD89329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region_building (region_id INT NOT NULL, building_id INT NOT NULL, INDEX IDX_92A8652698260155 (region_id), INDEX IDX_92A865264D2A7E12 (building_id), PRIMARY KEY(region_id, building_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kingdom_building (id INT AUTO_INCREMENT NOT NULL, kingdom_id INT DEFAULT NULL, building_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_6A6C331F6976FEC0 (kingdom_id), INDEX IDX_6A6C331F4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, id_avatar VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kingdom (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, population INT NOT NULL, power INT NOT NULL, gold INT NOT NULL, INDEX IDX_256D961498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE market (id INT AUTO_INCREMENT NOT NULL, kingdom_resource_id INT DEFAULT NULL, quantity INT NOT NULL, price INT NOT NULL, INDEX IDX_6BAC85CB54C8A63 (kingdom_resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_resource (building_id INT NOT NULL, resource_id INT NOT NULL, is_production TINYINT(1) NOT NULL, is_required TINYINT(1) NOT NULL, INDEX IDX_671DF54D4D2A7E12 (building_id), INDEX IDX_671DF54D89329D25 (resource_id), PRIMARY KEY(building_id, resource_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, is_food TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, recipient_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, at_date DATETIME NOT NULL, INDEX IDX_B6BD307FE92F8F78 (recipient_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, avatar_id INT NOT NULL, kingdom_id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, action_points INT NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', date_registration DATETIME NOT NULL, last_connection DATETIME NOT NULL, UNIQUE INDEX UNIQ_98197A65F85E0677 (username), UNIQUE INDEX UNIQ_98197A655126AC48 (mail), INDEX IDX_98197A6586383B10 (avatar_id), UNIQUE INDEX UNIQ_98197A656976FEC0 (kingdom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE kingdom_resource ADD CONSTRAINT FK_3792A6DD6976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id)');
        $this->addSql('ALTER TABLE kingdom_resource ADD CONSTRAINT FK_3792A6DD89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE region_building ADD CONSTRAINT FK_92A8652698260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE region_building ADD CONSTRAINT FK_92A865264D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kingdom_building ADD CONSTRAINT FK_6A6C331F6976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id)');
        $this->addSql('ALTER TABLE kingdom_building ADD CONSTRAINT FK_6A6C331F4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE kingdom ADD CONSTRAINT FK_256D961498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE market ADD CONSTRAINT FK_6BAC85CB54C8A63 FOREIGN KEY (kingdom_resource_id) REFERENCES kingdom_resource (id)');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6586383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A656976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE market DROP FOREIGN KEY FK_6BAC85CB54C8A63');
        $this->addSql('ALTER TABLE region_building DROP FOREIGN KEY FK_92A8652698260155');
        $this->addSql('ALTER TABLE kingdom DROP FOREIGN KEY FK_256D961498260155');
        $this->addSql('ALTER TABLE region_building DROP FOREIGN KEY FK_92A865264D2A7E12');
        $this->addSql('ALTER TABLE kingdom_building DROP FOREIGN KEY FK_6A6C331F4D2A7E12');
        $this->addSql('ALTER TABLE building_resource DROP FOREIGN KEY FK_671DF54D4D2A7E12');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6586383B10');
        $this->addSql('ALTER TABLE kingdom_resource DROP FOREIGN KEY FK_3792A6DD6976FEC0');
        $this->addSql('ALTER TABLE kingdom_building DROP FOREIGN KEY FK_6A6C331F6976FEC0');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A656976FEC0');
        $this->addSql('ALTER TABLE kingdom_resource DROP FOREIGN KEY FK_3792A6DD89329D25');
        $this->addSql('ALTER TABLE building_resource DROP FOREIGN KEY FK_671DF54D89329D25');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('DROP TABLE kingdom_resource');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE region_building');
        $this->addSql('DROP TABLE kingdom_building');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE kingdom');
        $this->addSql('DROP TABLE market');
        $this->addSql('DROP TABLE building_resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE player');
    }
}
