<?php

namespace BJ;

abstract class AbstractModel
{
    protected $table;
    protected $attributes;
    protected $fillable;

    public $db;

    public function __construct()
    {
        $this->table = $this->getTableName();
        $this->fillable = $this->getFillable();
        $this->db = \DB::table($this->table);
    }

    /**
     * возвращает имя таблицы
     * @return string
     */
    abstract protected function getTableName();

    /**
     * возвращает массив с именами полей доступными для записи
     * @return array
     */
    abstract protected function getFillable();

    /**
     * заполняет поля модели из массива значений
     * @param array $attributes
     */
    public function fillFromArray($attributes)
    {
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                if (in_array($key, $this->fillable)) {
                    $this->attributes[$key] = $value;
                }
            }
        }
    }

    /**
     * сохраняет модель в базе данных
     * @return bool|integer
     */
    public function save()
    {
        if (!empty($this->attributes)) {
            return $this->db->insert($this->attributes);
        } else {
            return false;
        }
    }

    /**
     *  обновляет модель по ид
     * @param $id
     */
    public function update($id)
    {
        $this->db->where('id', $id)->update($this->attributes);
    }
}
