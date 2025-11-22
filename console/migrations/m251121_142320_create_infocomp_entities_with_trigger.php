<?php

use yii\db\Migration;

/**
 * Class m251121_142320_create_infocomp_entities_with_trigger
 */
class m251121_142320_create_infocomp_entities_with_trigger extends Migration
{
    /**
     * @return void
     */
    public function safeUp(): void
    {
        // ----------------------------
        // Создание таблиц
        // ----------------------------
        if (!$this->db->schema->getTableSchema('infocomp', true)) {
            $this->createTable('infocomp', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'hostname' => $this->string()->notNull(),
                'source_type' => $this->string(50),
                'source_path' => $this->text(),
                'playlist_id' => $this->integer(),
                'status' => $this->string(50),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);
        }

        if (!$this->db->schema->getTableSchema('pages', true)) {
            $this->createTable('pages', [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'type' => $this->string(50),
                'template' => $this->string(),
                'config_json' => $this->text(),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);
        }

        if (!$this->db->schema->getTableSchema('playlists', true)) {
            $this->createTable('playlists', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'description' => $this->text(),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);
        }

        if (!$this->db->schema->getTableSchema('playlist_items', true)) {
            $this->createTable('playlist_items', [
                'id' => $this->primaryKey(),
                'playlist_id' => $this->integer()->notNull(),
                'page_id' => $this->integer()->notNull(),
                'duration' => $this->integer(),
                'order_index' => $this->integer(),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            ]);

            // Внешние ключи
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
        $this->execute("COMMENT ON TABLE infocomp IS 'Инфо-компы / дисплеи';");
        $this->execute("COMMENT ON TABLE pages IS 'Страницы для отображения на дисплеях';");
        $this->execute("COMMENT ON TABLE playlists IS 'Плейлисты, содержащие последовательность страниц';");
        $this->execute("COMMENT ON TABLE playlist_items IS 'Элементы плейлиста: связь плейлист-страница';");

        // Поля infocomp
        $this->execute("COMMENT ON COLUMN infocomp.id IS 'ID дисплея';");
        $this->execute("COMMENT ON COLUMN infocomp.name IS 'Название дисплея';");
        $this->execute("COMMENT ON COLUMN infocomp.hostname IS 'Имя хоста / идентификатор компьютера';");
        $this->execute("COMMENT ON COLUMN infocomp.source_type IS 'Тип источника (xml/db)';");
        $this->execute("COMMENT ON COLUMN infocomp.source_path IS 'Путь к источнику данных';");
        $this->execute("COMMENT ON COLUMN infocomp.playlist_id IS 'Активный плейлист';");
        $this->execute("COMMENT ON COLUMN infocomp.status IS 'Статус (active/disabled)';");
        $this->execute("COMMENT ON COLUMN infocomp.created_at IS 'Дата создания';");
        $this->execute("COMMENT ON COLUMN infocomp.updated_at IS 'Дата последнего обновления';");

        // Поля pages
        $this->execute("COMMENT ON COLUMN pages.id IS 'ID страницы';");
        $this->execute("COMMENT ON COLUMN pages.title IS 'Название страницы';");
        $this->execute("COMMENT ON COLUMN pages.type IS 'Тип страницы';");
        $this->execute("COMMENT ON COLUMN pages.template IS 'Шаблон страницы';");
        $this->execute("COMMENT ON COLUMN pages.config_json IS 'JSON-конфигурация';");
        $this->execute("COMMENT ON COLUMN pages.created_at IS 'Дата создания';");
        $this->execute("COMMENT ON COLUMN pages.updated_at IS 'Дата последнего обновления';");

        // Поля playlists
        $this->execute("COMMENT ON COLUMN playlists.id IS 'ID плейлиста';");
        $this->execute("COMMENT ON COLUMN playlists.name IS 'Название плейлиста';");
        $this->execute("COMMENT ON COLUMN playlists.description IS 'Описание плейлиста';");
        $this->execute("COMMENT ON COLUMN playlists.created_at IS 'Дата создания';");
        $this->execute("COMMENT ON COLUMN playlists.updated_at IS 'Дата последнего обновления';");

        // Поля playlist_items
        $this->execute("COMMENT ON COLUMN playlist_items.id IS 'ID элемента плейлиста';");
        $this->execute("COMMENT ON COLUMN playlist_items.playlist_id IS 'Ссылка на плейлист';");
        $this->execute("COMMENT ON COLUMN playlist_items.page_id IS 'Ссылка на страницу';");
        $this->execute("COMMENT ON COLUMN playlist_items.duration IS 'Время показа (секунды)';");
        $this->execute("COMMENT ON COLUMN playlist_items.order_index IS 'Порядок в плейлисте';");
        $this->execute("COMMENT ON COLUMN playlist_items.created_at IS 'Дата создания';");
        $this->execute("COMMENT ON COLUMN playlist_items.updated_at IS 'Дата последнего обновления';");

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
        $tables = ['infocomp', 'pages', 'playlists', 'playlist_items'];
        foreach ($tables as $table) {
            $this->execute("
                CREATE TRIGGER trg_update_updated_at_{$table}
                BEFORE UPDATE ON {$table}
                FOR EACH ROW
                EXECUTE PROCEDURE update_updated_at_column();
            ");
        }
    }

    /**
     * @return void
     */
    public function safeDown(): void
    {
        $tables = ['playlist_items', 'playlists', 'pages', 'infocomp'];
        foreach ($tables as $table) {
            $this->execute("DROP TRIGGER IF EXISTS trg_update_updated_at_{$table} ON {$table};");
        }
        $this->execute("DROP FUNCTION IF EXISTS update_updated_at_column();");

        foreach ($tables as $table) {
            $this->dropTable($table);
        }
    }
}
