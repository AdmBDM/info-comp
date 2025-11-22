<?php

use yii\db\Migration;

/**
 * Class m251121_142320_create_infocomp_entities_with_trigger
 */
class m251121_142320_create_infocomp_entities_with_trigger extends Migration
{
    public function safeUp()
    {
        // ----------------------------
        // Создание таблицы infocomp
        // ----------------------------
        if (!$this->db->schema->getTableSchema('infocomp', true)) {
            $this->createTable('infocomp', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'hostname' => $this->string()->notNull(),
                'source_type' => $this->string(50),
                'source_path' => $this->text(),
                'is_active' => $this->boolean()->notNull()->defaultValue(true),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);
        }

        // ----------------------------
        // Создание таблицы pages
        // ----------------------------
        if (!$this->db->schema->getTableSchema('pages', true)) {
            $this->createTable('pages', [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'type' => $this->string(50),
                'template' => $this->string(),
                'config_json' => $this->text(),
                'is_active' => $this->boolean()->notNull()->defaultValue(true),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);
        }

        // ----------------------------
        // Создание таблицы playlists
        // ----------------------------
        if (!$this->db->schema->getTableSchema('playlists', true)) {
            $this->createTable('playlists', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'description' => $this->text(),
                'is_active' => $this->boolean()->notNull()->defaultValue(true),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);
        }

        // ----------------------------
        // Создание таблицы displays (мульти-дисплей)
        // ----------------------------
        if (!$this->db->schema->getTableSchema('displays', true)) {
            $this->createTable('displays', [
                'id' => $this->primaryKey(),
                'infocomp_id' => $this->integer()->notNull(),
                'display_index' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'orientation' => "VARCHAR(20)", // enum будет создан ниже
                'config_json' => $this->json(),
                'playlist_id' => $this->integer(),
                'is_active' => $this->boolean()->notNull()->defaultValue(true),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);

            // FK
            $this->addForeignKey(
                'fk_displays_infocomp',
                'displays',
                'infocomp_id',
                'infocomp',
                'id',
                'CASCADE'
            );

            $this->addForeignKey(
                'fk_displays_playlist',
                'displays',
                'playlist_id',
                'playlists',
                'id',
                'SET NULL'
            );

            // Создание enum для orientation
            $this->execute("DO \$\$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'display_orientation_enum') THEN
                    CREATE TYPE display_orientation_enum AS ENUM ('Ландшафт', 'Портрет');
                END IF;
            END\$\$;");
            $this->execute("ALTER TABLE displays ALTER COLUMN orientation TYPE display_orientation_enum USING orientation::display_orientation_enum;");
        }

        // ----------------------------
        // Создание таблицы playlist_items
        // ----------------------------
        if (!$this->db->schema->getTableSchema('playlist_items', true)) {
            $this->createTable('playlist_items', [
                'id' => $this->primaryKey(),
                'playlist_id' => $this->integer()->notNull(),
                'page_id' => $this->integer()->notNull(),
                'duration' => $this->integer(),
                'order_index' => $this->integer(),
                'is_active' => $this->boolean()->notNull()->defaultValue(true),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);

            $this->addForeignKey(
                'fk_playlist_items_playlist',
                'playlist_items',
                'playlist_id',
                'playlists',
                'id',
                'CASCADE'
            );

            $this->addForeignKey(
                'fk_playlist_items_page',
                'playlist_items',
                'page_id',
                'pages',
                'id',
                'CASCADE'
            );
        }

        // ----------------------------
        // Добавление комментариев (отдельный блок)
        // ----------------------------
        // Таблицы
        $this->execute("COMMENT ON TABLE infocomp IS 'Инфо-компы / физические ПК';");
        $this->execute("COMMENT ON TABLE pages IS 'Страницы для отображения';");
        $this->execute("COMMENT ON TABLE playlists IS 'Плейлисты страниц';");
        $this->execute("COMMENT ON TABLE playlist_items IS 'Элементы плейлистов';");
        $this->execute("COMMENT ON TABLE displays IS 'Мониторы инфокомпов для мульти-дисплея';");

        // Поля (пример для infocomp, остальные аналогично)
        $this->execute("COMMENT ON COLUMN infocomp.id IS 'ID ПК';");
        $this->execute("COMMENT ON COLUMN infocomp.name IS 'Название ПК';");
        $this->execute("COMMENT ON COLUMN infocomp.hostname IS 'Имя хоста';");
        $this->execute("COMMENT ON COLUMN infocomp.source_type IS 'Тип источника';");
        $this->execute("COMMENT ON COLUMN infocomp.source_path IS 'Путь к источнику';");
        $this->execute("COMMENT ON COLUMN infocomp.is_active IS 'Активен / неактивен';");
        $this->execute("COMMENT ON COLUMN infocomp.created_at IS 'Дата создания';");
        $this->execute("COMMENT ON COLUMN infocomp.updated_at IS 'Дата последнего обновления';");

        // Можно добавить комментарии для остальных таблиц аналогично — при необходимости

        // ----------------------------
        // Создание функции триггера для updated_at
        // ----------------------------
        $this->execute("
            CREATE OR REPLACE FUNCTION update_updated_at_column()
            RETURNS TRIGGER AS \$\$
            BEGIN
               NEW.updated_at = NOW();
               RETURN NEW;
            END;
            \$\$ LANGUAGE 'plpgsql';
        ");

        // Привязка триггера к таблицам
        $tables = ['infocomp', 'pages', 'playlists', 'playlist_items', 'displays'];
        foreach ($tables as $table) {
            $this->execute("
                CREATE TRIGGER trg_update_updated_at_{$table}
                BEFORE UPDATE ON {$table}
                FOR EACH ROW
                EXECUTE PROCEDURE update_updated_at_column();
            ");
        }
    }

    public function safeDown()
    {
        $tables = ['playlist_items', 'displays', 'playlists', 'pages', 'infocomp'];
        foreach ($tables as $table) {
            $this->execute("DROP TRIGGER IF EXISTS trg_update_updated_at_{$table} ON {$table};");
        }
        $this->execute("DROP FUNCTION IF EXISTS update_updated_at_column();");

        foreach ($tables as $table) {
            $this->dropTable($table);
        }

        // Удаление enum
        $this->execute("DROP TYPE IF EXISTS display_orientation_enum;");
    }
}
