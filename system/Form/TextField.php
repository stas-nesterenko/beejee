<?php


namespace BJ\Form;


class TextField extends AbstractField
{
    private $minLength;
    private $maxLength;
    private $regex;

    /**
     * устанавливает минимальную длину
     * @param $value
     * @return $this
     */
    public function setMinLength($value)
    {
        $this->minLength = $value;
        return $this;
    }

    /**
     * устанавливает максимальную длину
     * @param $value
     * @return $this
     */
    public function setMaxLength($value)
    {
        $this->maxLength = $value;
        return $this;
    }

    /**
     * устанавливает регулярное выражение для проверки
     * @param $value
     * @return $this
     */
    public function setRegex($value)
    {
        $this->regex = $value;
        return $this;
    }

    public function valid()
    {
        if (
            isset($this->minLength) &&
            mb_strlen($_POST[$this->name]) < $this->minLength
        ) {
            return false;
        }

        if (
            isset($this->maxLength) &&
            mb_strlen($_POST[$this->name]) > $this->maxLength
        ) {
            return false;
        }

        if (
            isset($this->regex) &&
            !preg_match($this->regex, $_POST[$this->name])
        ) {
            return false;
        }

        return true;
    }

    public function getError()
    {
        if (
            isset($this->minLength) &&
            mb_strlen($_POST[$this->name]) < $this->minLength
        ) {
            return 'слишком короткое значение';
        }

        if (
            isset($this->maxLength) &&
            mb_strlen($_POST[$this->name]) > $this->maxLength
        ) {
            return 'слишком длинное значение';
        }

        if (
            isset($this->regex) &&
            !preg_match($this->regex, $_POST[$this->name])
        ) {
            return 'значение введено с ошибкой';
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    function getValidationRule()
    {
        $rule = [];

        if (isset($this->minLength)) {
            $rule['minLength'] = [
                'value' => $this->minLength,
                'error' => 'слишком короткое значение'
            ];
        }

        if (isset($this->maxLength)) {
            $rule['maxLength'] = [
                'value' => $this->maxLength,
                'error' => 'слишком короткое значение'
            ];
        }
        
        return $rule;
    }
}
