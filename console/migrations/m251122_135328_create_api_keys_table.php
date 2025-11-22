<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_keys}}`.
 */
class m251122_135328_create_api_keys_table extends Migration
{
    public function safeUp()
    {
        // проверяем, есть ли таблица
        if ($this->db->schema->getTableSchema('{{%api_keys}}', true) === null) {
            $this->createTable('{{%api_keys}}', [
                'id' => $this->primaryKey(),
                'infocomp_id' => $this->integer()->notNull(),
                'key' => $this->string(64)->notNull(),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            ]);

            // FK на infocomp
            $this->addForeignKey(
                'fk_api_keys_infocomp',
                '{{%api_keys}}',
                'infocomp_id',
                '{{%infocomp}}',
                'id',
                'CASCADE'
            );
        }

        // Комментарии отдельным блоком
        $this->execute("COMMENT ON TABLE {{%api_keys}} IS 'API ключи для устройств Infocomp';");
        $this->execute("COMMENT ON COLUMN {{%api_keys}}.infocomp_id IS 'ID ПК';");
        $this->execute("COMMENT ON COLUMN {{%api_keys}}.key IS 'API ключ';");
        $this->execute("COMMENT ON COLUMN {{%api_keys}}.created_at IS 'Дата создания ключа';");
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_api_keys_infocomp', '{{%api_keys}}');
        $this->dropTable('{{%api_keys}}');
    }
}
