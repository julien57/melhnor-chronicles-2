<?php

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Init database tables
 */
class Version20180413121849 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region_building (region_id INT NOT NULL, building_id INT NOT NULL, INDEX IDX_92A8652698260155 (region_id), INDEX IDX_92A865264D2A7E12 (building_id), PRIMARY KEY(region_id, building_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kingdom_building (id INT AUTO_INCREMENT NOT NULL, kingdom_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_6A6C331F6976FEC0 (kingdom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_kingdom_building (building_id INT NOT NULL, kingdom_building_id INT NOT NULL, INDEX IDX_53004DD04D2A7E12 (building_id), INDEX IDX_53004DD0C1546954 (kingdom_building_id), PRIMARY KEY(building_id, kingdom_building_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_resource (building_id INT NOT NULL, resource_id INT NOT NULL, INDEX IDX_671DF54D4D2A7E12 (building_id), INDEX IDX_671DF54D89329D25 (resource_id), PRIMARY KEY(building_id, resource_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, id_avatar INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kingdom (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, population INT NOT NULL, power INT NOT NULL, gold INT NOT NULL, INDEX IDX_256D961498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, avatar_id INT NOT NULL, kingdom_id INT NOT NULL, name VARCHAR(255) NOT NULL, pass VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, action_points INT NOT NULL, date_registration DATETIME NOT NULL, last_connection DATETIME NOT NULL, UNIQUE INDEX UNIQ_98197A655E237E06 (name), INDEX IDX_98197A6586383B10 (avatar_id), UNIQUE INDEX UNIQ_98197A656976FEC0 (kingdom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE region_building ADD CONSTRAINT FK_92A8652698260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE region_building ADD CONSTRAINT FK_92A865264D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kingdom_building ADD CONSTRAINT FK_6A6C331F6976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id)');
        $this->addSql('ALTER TABLE building_kingdom_building ADD CONSTRAINT FK_53004DD04D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE building_kingdom_building ADD CONSTRAINT FK_53004DD0C1546954 FOREIGN KEY (kingdom_building_id) REFERENCES kingdom_building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE building_resource ADD CONSTRAINT FK_671DF54D89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kingdom ADD CONSTRAINT FK_256D961498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6586383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A656976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE region_building DROP FOREIGN KEY FK_92A8652698260155');
        $this->addSql('ALTER TABLE kingdom DROP FOREIGN KEY FK_256D961498260155');
        $this->addSql('ALTER TABLE building_kingdom_building DROP FOREIGN KEY FK_53004DD0C1546954');
        $this->addSql('ALTER TABLE region_building DROP FOREIGN KEY FK_92A865264D2A7E12');
        $this->addSql('ALTER TABLE building_kingdom_building DROP FOREIGN KEY FK_53004DD04D2A7E12');
        $this->addSql('ALTER TABLE building_resource DROP FOREIGN KEY FK_671DF54D4D2A7E12');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6586383B10');
        $this->addSql('ALTER TABLE kingdom_building DROP FOREIGN KEY FK_6A6C331F6976FEC0');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A656976FEC0');
        $this->addSql('ALTER TABLE building_resource DROP FOREIGN KEY FK_671DF54D89329D25');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE region_building');
        $this->addSql('DROP TABLE kingdom_building');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE building_kingdom_building');
        $this->addSql('DROP TABLE building_resource');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE kingdom');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE player');
    }
}
