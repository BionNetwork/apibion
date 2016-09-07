<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160907122527 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE activation (id SERIAL NOT NULL, card_id INT NOT NULL, user_id INT NOT NULL, activation_status_id INT NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C6860774ACC9A20 ON activation (card_id)');
        $this->addSql('CREATE INDEX IDX_1C686077A76ED395 ON activation (user_id)');
        $this->addSql('CREATE INDEX IDX_1C686077CDEDF351 ON activation (activation_status_id)');
        $this->addSql('CREATE INDEX activation_create_on_idx ON activation (created_on)');
        $this->addSql('CREATE TABLE activation_status (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX activation_status_code_idx ON activation_status (code)');
        $this->addSql('CREATE TABLE argument (id SERIAL NOT NULL, card_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, dimension VARCHAR(255) DEFAULT NULL, datatype VARCHAR(255) DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D113B0A4ACC9A20 ON argument (card_id)');
        $this->addSql('CREATE INDEX argument_create_on_idx ON argument (created_on)');
        $this->addSql('CREATE TABLE card (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(2048) DEFAULT NULL, description_long VARCHAR(8192) DEFAULT NULL, rating NUMERIC(10, 0) NOT NULL, author VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, carousel VARCHAR(4096) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, price NUMERIC(10, 0) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX card_type_idx ON card (type)');
        $this->addSql('CREATE INDEX card_create_on_idx ON card (created_on)');
        $this->addSql('CREATE TABLE card_category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE card_representation (id SERIAL NOT NULL, activation_id INT DEFAULT NULL, card_id INT DEFAULT NULL, representation_id INT DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_65CC2309116B3934 ON card_representation (activation_id)');
        $this->addSql('CREATE INDEX IDX_65CC23094ACC9A20 ON card_representation (card_id)');
        $this->addSql('CREATE INDEX IDX_65CC230946CE82F4 ON card_representation (representation_id)');
        $this->addSql('CREATE INDEX card_representation_create_on_idx ON card_representation (created_on)');
        $this->addSql('CREATE TABLE dashboard (id SERIAL NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX dashboard_user_idx ON dashboard (user_id)');
        $this->addSql('CREATE INDEX dashboard_create_on_idx ON dashboard (created_on)');
        $this->addSql('CREATE TABLE dashboard_activation (id SERIAL NOT NULL, dashboard_id INT NOT NULL, activation_id INT NOT NULL, user_id INT NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_38943489B9D04D2B ON dashboard_activation (dashboard_id)');
        $this->addSql('CREATE INDEX IDX_38943489116B3934 ON dashboard_activation (activation_id)');
        $this->addSql('CREATE INDEX IDX_38943489A76ED395 ON dashboard_activation (user_id)');
        $this->addSql('CREATE INDEX dashboard_activation_create_on_idx ON dashboard_activation (created_on)');
        $this->addSql('CREATE TABLE images (id SERIAL NOT NULL, created_by INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(1024) NOT NULL, size INT DEFAULT NULL, mime VARCHAR(255) DEFAULT NULL, crop_data TEXT DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, queued BOOLEAN DEFAULT \'false\' NOT NULL, published BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E01FBE6ADE12AB56 ON images (created_by)');
        $this->addSql('CREATE INDEX images_name__idx ON images (name)');
        $this->addSql('CREATE INDEX images_path__idx ON images (path)');
        $this->addSql('CREATE INDEX images_created_on__idx ON images (created_on)');
        $this->addSql('CREATE TABLE image_sizes (id SERIAL NOT NULL, image_id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A1DB03C03DA5256D ON image_sizes (image_id)');
        $this->addSql('CREATE INDEX image_size_name__idx ON image_sizes (name)');
        $this->addSql('CREATE INDEX image_size_sizes__idx ON image_sizes (width, height)');
        $this->addSql('CREATE INDEX image_size_created_on__idx ON image_sizes (created_on)');
        $this->addSql('CREATE TABLE purchase (id SERIAL NOT NULL, user_id INT DEFAULT NULL, card_id INT DEFAULT NULL, price NUMERIC(10, 0) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6117D13BA76ED395 ON purchase (user_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B4ACC9A20 ON purchase (card_id)');
        $this->addSql('CREATE INDEX purchase_create_on_idx ON purchase (created_on)');
        $this->addSql('CREATE TABLE representation (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX representation_create_on_idx ON representation (created_on)');
        $this->addSql('CREATE TABLE resource (id SERIAL NOT NULL, activation_id INT DEFAULT NULL, user_id INT NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, remote_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC91F416116B3934 ON resource (activation_id)');
        $this->addSql('CREATE INDEX IDX_BC91F416A76ED395 ON resource (user_id)');
        $this->addSql('CREATE INDEX resource_create_on_idx ON resource (created_on)');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, status_id INT NOT NULL, role_id INT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, middlename VARCHAR(255) DEFAULT NULL, login VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, birth_date DATE DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, avatar_small VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, last_login_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, mail_notification BOOLEAN DEFAULT NULL, must_change_passwd BOOLEAN DEFAULT NULL, passwd_changed_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, is_blocked BOOLEAN DEFAULT \'false\' NOT NULL, is_deleted BOOLEAN DEFAULT \'false\' NOT NULL, verify_email_uuid VARCHAR(50) DEFAULT NULL, is_superuser BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX role_id__idx ON users (role_id)');
        $this->addSql('CREATE INDEX status_id__idx ON users (status_id)');
        $this->addSql('CREATE INDEX login_credenitials__idx ON users (login, password)');
        $this->addSql('CREATE INDEX phone__idx ON users (phone)');
        $this->addSql('CREATE UNIQUE INDEX email__idx ON users (email)');
        $this->addSql('CREATE TABLE user_contacts (id SERIAL NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, is_default BOOLEAN DEFAULT \'false\' NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D3CDF173A76ED395 ON user_contacts (user_id)');
        $this->addSql('CREATE INDEX user_contacts_valuetype__idx ON user_contacts (type, value)');
        $this->addSql('CREATE INDEX user_contacts_create_on__idx ON user_contacts (created_on)');
        $this->addSql('CREATE TABLE user_roles (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_statuses (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_statuses_code__idx ON user_statuses (code)');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT FK_1C6860774ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT FK_1C686077A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT FK_1C686077CDEDF351 FOREIGN KEY (activation_status_id) REFERENCES activation_status (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument ADD CONSTRAINT FK_D113B0A4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT FK_65CC2309116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT FK_65CC23094ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT FK_65CC230946CE82F4 FOREIGN KEY (representation_id) REFERENCES representation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dashboard ADD CONSTRAINT FK_5C94FFF8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dashboard_activation ADD CONSTRAINT FK_38943489B9D04D2B FOREIGN KEY (dashboard_id) REFERENCES dashboard (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dashboard_activation ADD CONSTRAINT FK_38943489116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dashboard_activation ADD CONSTRAINT FK_38943489A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6ADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image_sizes ADD CONSTRAINT FK_A1DB03C03DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E96BF700BD FOREIGN KEY (status_id) REFERENCES user_statuses (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES user_roles (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_contacts ADD CONSTRAINT FK_D3CDF173A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE card_representation DROP CONSTRAINT FK_65CC2309116B3934');
        $this->addSql('ALTER TABLE dashboard_activation DROP CONSTRAINT FK_38943489116B3934');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F416116B3934');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT FK_1C686077CDEDF351');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT FK_1C6860774ACC9A20');
        $this->addSql('ALTER TABLE argument DROP CONSTRAINT FK_D113B0A4ACC9A20');
        $this->addSql('ALTER TABLE card_representation DROP CONSTRAINT FK_65CC23094ACC9A20');
        $this->addSql('ALTER TABLE purchase DROP CONSTRAINT FK_6117D13B4ACC9A20');
        $this->addSql('ALTER TABLE dashboard_activation DROP CONSTRAINT FK_38943489B9D04D2B');
        $this->addSql('ALTER TABLE image_sizes DROP CONSTRAINT FK_A1DB03C03DA5256D');
        $this->addSql('ALTER TABLE card_representation DROP CONSTRAINT FK_65CC230946CE82F4');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT FK_1C686077A76ED395');
        $this->addSql('ALTER TABLE dashboard DROP CONSTRAINT FK_5C94FFF8A76ED395');
        $this->addSql('ALTER TABLE dashboard_activation DROP CONSTRAINT FK_38943489A76ED395');
        $this->addSql('ALTER TABLE images DROP CONSTRAINT FK_E01FBE6ADE12AB56');
        $this->addSql('ALTER TABLE purchase DROP CONSTRAINT FK_6117D13BA76ED395');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F416A76ED395');
        $this->addSql('ALTER TABLE user_contacts DROP CONSTRAINT FK_D3CDF173A76ED395');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E96BF700BD');
        $this->addSql('DROP TABLE activation');
        $this->addSql('DROP TABLE activation_status');
        $this->addSql('DROP TABLE argument');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_category');
        $this->addSql('DROP TABLE card_representation');
        $this->addSql('DROP TABLE dashboard');
        $this->addSql('DROP TABLE dashboard_activation');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE image_sizes');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE representation');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_contacts');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE user_statuses');
    }
}
