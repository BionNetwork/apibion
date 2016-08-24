<?php

namespace BiBundle\Service\Utils;


use BiBundle\Exception;

class PeriodConverter
{
    const WEEK = "week";
    const MONTH = "month";
    const QUARTER = "quarter";
    const THIS_WEEK = "thisweek";


    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $finishDate;

    public function __construct($period)
    {
        if (!in_array($period, [self::WEEK, self::MONTH, self::QUARTER, self::THIS_WEEK])) {
            throw new Exception("Period marker not found!", 404);
        }

        switch ($period) {
            case self::WEEK:
                $this->fillByWeek();
                break;
            case self::MONTH:
                $this->fillByMonth();
                break;
            case self::QUARTER:
                $this->fillByQuarter();
                break;
            case self::THIS_WEEK:
                $this->fillByThisWeek();
                break;
            default:
                throw new Exception("Period marker not found!", 404);
        }
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     *  Неделя (с 00 часов понедельника до 23.59 воскресения недели, предшествующей текущей)
     */
    protected function fillByWeek()
    {
        $currentDate = new \DateTime();
        $weekForStartDate = $currentDate->modify('-1 week');
        $weekForFinishDate = clone($weekForStartDate);
        $weekForStartDate->modify('monday this week');
        $this->startDate = $weekForStartDate;
        $weekForFinishDate->modify('monday next week');
        $weekForFinishDate->modify('-1 second');
        $this->finishDate = $weekForFinishDate;
    }

    /**
     * Текущая неделя
     */
    protected function fillByThisWeek(){
        $weekForStartDate = new \DateTime();
        $weekForFinishDate = new \DateTime();
        $weekForStartDate->modify('monday this week');
        $weekForFinishDate->modify('sunday this week');
        $this->startDate = $weekForStartDate;
        $this->finishDate = $weekForFinishDate;
    }

    /**
     * Месяц (с 00 часов 1 числа до текущей даты текущего месяца)
     */
    protected function fillByMonth()
    {
        $monthForStartDate = new \DateTime();
        $monthForFinishDate = new \DateTime();
        $monthForStartDate->modify('first day of this month');
        $this->startDate = new \DateTime($monthForStartDate->format('Y-m-d 00:00:00'));
        $this->finishDate = $monthForFinishDate;
    }

    /**
     *  Квартал (с 00 1 дня до текущей даты текущего квартала)
     */
    protected function fillByQuarter()
    {
        $currentDate = new \DateTime();
        $currentQuarterNumber = intval((($currentDate->format('m') - 1) / 3) + 1);
        $firstQuarterMonthNumber = $currentQuarterNumber * 3 - 2;
        $firstQuarterMonthNumberString = $firstQuarterMonthNumber >= 10 ? $firstQuarterMonthNumber : '0' . $firstQuarterMonthNumber;
        $this->startDate = new \DateTime($currentDate->format('Y') . '-' . $firstQuarterMonthNumberString . '-01');
        $this->finishDate = $currentDate;
    }

}