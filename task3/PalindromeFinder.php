<?php

class PalindromeFinder
{
    /**
     * @var string $originalString
     */
    private $originalString;
    /**
     * @var string $cleanedString
     */
    private $cleanedString;
    /**
     * @var string $formattedString
     */
    private $formattedString;
    /**
     * @var array $lengthTracker
     */
    private $lengthTracker = [];
    /**
     * PalindromeFinder constructor.
     *
     * @param string $s
     */
    public function __construct(string $s)
    {
        if (!strlen($s)) {
            throw new \LengthException("Введена пустая строка");
        }
        $this->originalString = $s;
    }

    /**
     * логика вывода
     * @return string
     */
    public function check()
    {
        $find = $this->find();
        return ($find == mb_strtolower($this->cleanedString)) ? $this->originalString : $find;
    }
    /**
     * Попытка реализации алгоритма Манакера
     * @return string
     */
    private function find()
    {
        return $this->cleanString()
            ->formatString()
            ->calculateLengths()
            ->getLongestSubstring();
    }
    /**
     * Удаляем лишние символы
     * @return $this
     */
    private function cleanString()
    {
        // \P{Xan} содержит все, что не является буквой или цифрой
        $this->cleanedString = preg_replace('~\P{Xan}++~u', '', $this->originalString);
        return $this;
    }
    /**
     * Разделяем строку на символы и оборачиваем разделителями, чтобы отрегулировать длину
     * @return $this
     */
    private function formatString()
    {
        $adjustedString = implode('#', preg_split('/(?<!^)(?!$)/u', mb_strtolower($this->cleanedString)));
        $this->formattedString = "^#" . $adjustedString . "#$";
        return $this;
    }
    /**
     * Подготовить список длин палиндромных подстрок
     * @return $this
     */
    private function calculateLengths()
    {
        $this->lengthTracker = array_fill(0, mb_strlen($this->formattedString), 0);
        $center = 0;
        $right = 0;
        for ($i = 1; $i < mb_strlen($this->formattedString) - 1; ++$i) {
            $mirror = 2 * $center - $i;
            if ($right > $i) {
                $this->lengthTracker[$i] = min($right - $i, $this->lengthTracker[$mirror]);
            }
            while(
                mb_substr($this->formattedString, $i + (1 + $this->lengthTracker[$i]), 1) ==
                mb_substr($this->formattedString, $i - (1 + $this->lengthTracker[$i]), 1)
            ) {
                $this->lengthTracker[$i]++;
            }
            if ($i + $this->lengthTracker[$i] > $right) {
                $center = $i;
                $right = $i + $this->lengthTracker[$i];
            }
        }
        return $this;
    }
    /**
     * Извлекает самую длинную палиндромную подстроку
     * @return string
     */
    private function getLongestSubstring()
    {
        $maxLength = max($this->lengthTracker);
        // If 2 items with equal length found return first occurrence
        $keys = array_keys($this->lengthTracker, $maxLength);
        $center = reset($keys);
        $substring = mb_substr($this->cleanedString, ($center - 1 - $maxLength) / 2, $maxLength);
        return mb_strtolower($substring);
    }
}