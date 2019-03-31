<?php


namespace App;


use mysql_xdevapi\Result;
use test\Mockery\ArgumentObjectTypeHint;

class CardSetType
{
    /**
     * @var array
     */
    private $cardSet;

    public function setCardSet(array $cardSet)
    {
        $this->cardSet = $cardSet;
    }

    public function is_Flush()
    {
        return count(array_unique(array_map(function (Card $card) {
                return $card->getColor();
            }, $this->cardSet))) == 1;
    }

    public function is_straight()
    {
        $cardsNumber = $this->extractNumber();

        if (max($cardsNumber) === 13) {
            foreach ($cardsNumber as $key => $card) {
                if ($card === 1) {
                    $cardsNumber[$key] = 14;
                    break;
                }
            }
        }

        return max($cardsNumber) - min($cardsNumber) === 4;
    }

    public function is_Four_of_a_Kind()
    {
        return $this->gavinSameOfAKind(4);
    }

    public function is_Three_of_a_Kind()
    {
        return $this->gavinSameOfAKind(3);
    }

    /**
     * @return array
     */
    public function extractNumber(): array
    {
        $cardsNumber = array_map(function (Card $card) {
            return $card->getNumber();
        }, $this->cardSet);
        return $cardsNumber;
    }

    /**
     * @param $number
     *
     * @return bool
     */
    protected function gavinSameOfAKind($number): bool
    {
        $cardsNumber = $this->extractNumber();

        $result = array_count_values($cardsNumber);

        return max(array_values($result)) == $number;
    }

    public function isTwoPair()
    {
        $cardsNumber = $this->extractNumber();

        $result = array_count_values($cardsNumber);

        $result = array_count_values($result);

        return isset($result[2]) && $result[2] == 2;
    }

}
