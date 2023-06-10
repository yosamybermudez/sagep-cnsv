<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205022310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE encuentro_acuerdo_persona (encuentro_acuerdo_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_D9EE36ABAB8A4C (encuentro_acuerdo_id), INDEX IDX_D9EE36F5F88DB9 (persona_id), PRIMARY KEY(encuentro_acuerdo_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, nombres VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, carne_identidad VARCHAR(11) NOT NULL, cargo VARCHAR(11) NOT NULL, sexo VARCHAR(10) DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL, nivel_educacional VARCHAR(255) DEFAULT NULL, telefono VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_51E5B69B9B9F254F (carne_identidad), UNIQUE INDEX UNIQ_51E5B69B3BEE5771 (cargo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE encuentro_acuerdo_persona ADD CONSTRAINT FK_D9EE36ABAB8A4C FOREIGN KEY (encuentro_acuerdo_id) REFERENCES encuentro_acuerdo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE encuentro_acuerdo_persona ADD CONSTRAINT FK_D9EE36F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE almacen_producto CHANGE u_create u_create INT DEFAULT NULL, CHANGE u_update u_update INT DEFAULT NULL, CHANGE unidad_medida unidad_medida VARCHAR(255) DEFAULT NULL, CHANGE codigo codigo VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL, CHANGE f_create f_create DATETIME DEFAULT NULL, CHANGE f_update f_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cambiar_contrasena_peticion CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cargo CHANGE rol_sistema_id rol_sistema_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comentario CHANGE encuentro_id encuentro_id INT DEFAULT NULL, CHANGE comentario_padre_id comentario_padre_id INT DEFAULT NULL, CHANGE encuentro_acuerdo_id encuentro_acuerdo_id INT DEFAULT NULL, CHANGE solicitud_materiales_id solicitud_materiales_id INT DEFAULT NULL, CHANGE fecha_creado fecha_creado DATETIME DEFAULT NULL, CHANGE fecha_modificado fecha_modificado DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE encuentro CHANGE fecha_evento fecha_evento DATETIME DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL, CHANGE nombre nombre VARCHAR(255) DEFAULT NULL, CHANGE ref_evento ref_evento VARCHAR(255) DEFAULT NULL, CHANGE estado estado VARCHAR(255) DEFAULT NULL, CHANGE invitados invitados LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE motivo_cancelacion motivo_cancelacion VARCHAR(255) DEFAULT NULL, CHANGE hora hora TIME DEFAULT NULL, CHANGE lugar lugar VARCHAR(255) DEFAULT NULL, CHANGE dirige_encuentro dirige_encuentro VARCHAR(255) DEFAULT NULL, CHANGE hora_fin hora_fin TIME DEFAULT NULL, CHANGE cantidad_trabajadores cantidad_trabajadores INT DEFAULT NULL, CHANGE documentos_anexos documentos_anexos VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE encuentro_acuerdo CHANGE no_acuerdo no_acuerdo VARCHAR(255) DEFAULT NULL, CHANGE periodicidad periodicidad VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE encuentro_acuerdo_trazabilidad CHANGE encuentro_modificador_id encuentro_modificador_id INT DEFAULT NULL, CHANGE fecha_modificacion fecha_modificacion DATETIME DEFAULT NULL, CHANGE fecha_revision fecha_revision DATETIME DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE encuentro_tipo CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE file_documento CHANGE encuentro_id encuentro_id INT DEFAULT NULL, CHANGE solicitud_materiales_id solicitud_materiales_id INT DEFAULT NULL, CHANGE encuentro_acuerdo_id encuentro_acuerdo_id INT DEFAULT NULL, CHANGE fecha_subido fecha_subido DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE municipio CHANGE provincia_id provincia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE provincia CHANGE abreviatura abreviatura VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE sistema_modulo CHANGE enlace enlace VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sistema_permiso CHANGE permiso_agregar permiso_agregar TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE sistema_ruta CHANGE entidad_id entidad_id INT DEFAULT NULL, CHANGE enlace enlace VARCHAR(255) DEFAULT NULL, CHANGE parametros parametros VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud_materiales CHANGE nro_orden nro_orden VARCHAR(255) DEFAULT NULL, CHANGE nro_lote nro_lote VARCHAR(255) DEFAULT NULL, CHANGE producto producto VARCHAR(255) DEFAULT NULL, CHANGE otros otros VARCHAR(255) DEFAULT NULL, CHANGE solicitado_por_fecha solicitado_por_fecha DATETIME DEFAULT NULL, CHANGE recibido_por_cargo recibido_por_cargo VARCHAR(255) DEFAULT NULL, CHANGE recibido_por_nombre_completo recibido_por_nombre_completo VARCHAR(255) DEFAULT NULL, CHANGE recibido_por_fecha recibido_por_fecha DATETIME DEFAULT NULL, CHANGE nro_solicitud nro_solicitud VARCHAR(255) DEFAULT NULL, CHANGE vale_entrega vale_entrega VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud_materiales_productos CHANGE cantidad cantidad VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trabajador CHANGE cargo_id cargo_id INT DEFAULT NULL, CHANGE municipio_id municipio_id INT DEFAULT NULL, CHANGE no_expediente no_expediente VARCHAR(255) DEFAULT NULL, CHANGE direccion_carne direccion_carne VARCHAR(255) DEFAULT NULL, CHANGE direccion_residencia direccion_residencia VARCHAR(255) DEFAULT NULL, CHANGE sexo sexo VARCHAR(10) DEFAULT NULL, CHANGE foto foto VARCHAR(255) DEFAULT NULL, CHANGE nivel_educacional nivel_educacional VARCHAR(255) DEFAULT NULL, CHANGE fecha_alta fecha_alta DATE DEFAULT NULL, CHANGE fecha_baja fecha_baja DATE DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE trabajador_asociado_id trabajador_asociado_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE encuentro_acuerdo_persona DROP FOREIGN KEY FK_D9EE36F5F88DB9');
        $this->addSql('DROP TABLE encuentro_acuerdo_persona');
        $this->addSql('DROP TABLE persona');
        $this->addSql('ALTER TABLE almacen_producto CHANGE u_create u_create INT DEFAULT NULL, CHANGE u_update u_update INT DEFAULT NULL, CHANGE unidad_medida unidad_medida VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE codigo codigo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE f_create f_create DATETIME DEFAULT \'NULL\', CHANGE f_update f_update DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cambiar_contrasena_peticion CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cargo CHANGE rol_sistema_id rol_sistema_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comentario CHANGE encuentro_id encuentro_id INT DEFAULT NULL, CHANGE comentario_padre_id comentario_padre_id INT DEFAULT NULL, CHANGE encuentro_acuerdo_id encuentro_acuerdo_id INT DEFAULT NULL, CHANGE solicitud_materiales_id solicitud_materiales_id INT DEFAULT NULL, CHANGE fecha_creado fecha_creado DATETIME DEFAULT \'NULL\', CHANGE fecha_modificado fecha_modificado DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE encuentro CHANGE fecha_evento fecha_evento DATETIME DEFAULT \'NULL\', CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ref_evento ref_evento VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE estado estado VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE invitados invitados LONGTEXT CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE motivo_cancelacion motivo_cancelacion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE hora hora TIME DEFAULT \'NULL\', CHANGE lugar lugar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE dirige_encuentro dirige_encuentro VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE hora_fin hora_fin TIME DEFAULT \'NULL\', CHANGE cantidad_trabajadores cantidad_trabajadores INT DEFAULT NULL, CHANGE documentos_anexos documentos_anexos VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE encuentro_acuerdo CHANGE no_acuerdo no_acuerdo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE periodicidad periodicidad VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE encuentro_acuerdo_trazabilidad CHANGE encuentro_modificador_id encuentro_modificador_id INT DEFAULT NULL, CHANGE fecha_modificacion fecha_modificacion DATETIME DEFAULT \'NULL\', CHANGE fecha_revision fecha_revision DATETIME DEFAULT \'NULL\', CHANGE observaciones observaciones VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE encuentro_tipo CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE file_documento CHANGE encuentro_id encuentro_id INT DEFAULT NULL, CHANGE solicitud_materiales_id solicitud_materiales_id INT DEFAULT NULL, CHANGE encuentro_acuerdo_id encuentro_acuerdo_id INT DEFAULT NULL, CHANGE fecha_subido fecha_subido DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE municipio CHANGE provincia_id provincia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE provincia CHANGE abreviatura abreviatura VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sistema_modulo CHANGE enlace enlace VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sistema_permiso CHANGE permiso_agregar permiso_agregar TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sistema_ruta CHANGE entidad_id entidad_id INT DEFAULT NULL, CHANGE enlace enlace VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE parametros parametros VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE solicitud_materiales CHANGE nro_orden nro_orden VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nro_lote nro_lote VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE producto producto VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE otros otros VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE solicitado_por_fecha solicitado_por_fecha DATETIME DEFAULT \'NULL\', CHANGE recibido_por_cargo recibido_por_cargo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE recibido_por_nombre_completo recibido_por_nombre_completo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE recibido_por_fecha recibido_por_fecha DATETIME DEFAULT \'NULL\', CHANGE nro_solicitud nro_solicitud VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE vale_entrega vale_entrega VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE solicitud_materiales_productos CHANGE cantidad cantidad VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE trabajador CHANGE cargo_id cargo_id INT DEFAULT NULL, CHANGE municipio_id municipio_id INT DEFAULT NULL, CHANGE no_expediente no_expediente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion_carne direccion_carne VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion_residencia direccion_residencia VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE sexo sexo VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE foto foto VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nivel_educacional nivel_educacional VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_alta fecha_alta DATE DEFAULT \'NULL\', CHANGE fecha_baja fecha_baja DATE DEFAULT \'NULL\', CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE usuario CHANGE trabajador_asociado_id trabajador_asociado_id INT DEFAULT NULL');
    }
}
