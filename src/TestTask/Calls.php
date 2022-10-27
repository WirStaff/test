<?php

namespace TestTask;

use Exception;

class Calls extends CallsReport
{
    /**
     * @throws Exception
     */
    public function fillCallDtoSpl(string $json): CallDtoSpl
    {
        $data = file_get_contents($json);

        if (!$data) {
            throw new Exception('Файл ' . $json . ' не найден');
        }

        $data = json_decode($data);

        $callDtoSql = new CallDtoSpl;

        array_map(function ($el) use ($callDtoSql) {
            $callDtoSql->setItem(
                (new CallDto())
                    ->setStartDateTime($el->start_date_time)
                    ->setDuration((int)$el->duration_seconds)
            );
        }, $data);

        return $callDtoSql;
    }

    protected function getOverLoadCalls(CallDtoSpl $dto): array
    {
        $items = [];

        foreach ($dto->getItem() as $el) {
            if ($el->getDuration() > self::$maxCallPerOneSecond) {
                $items[strtotime($el->getStartDateTime())] = $el->getDuration();
            }
        }

        return $items;
    }

    /**
     * @throws Exception
     */
    protected function getMaxCallPerMinutes(CallDtoSpl $dto): MaxCallPerMinutesDtoSpl
    {
        $items = $dto->getItem();

        usort($items, function ($l, $r) {
            return $l->getStartDateTime() > $r->getStartDateTime();
        });

        $maxCallPerMinutesDtoSql = new MaxCallPerMinutesDtoSpl;

        foreach ($items as $el) {
            $maxCallPerMinutesDtoSql->setItem(
                (new MaxCallPerMinutesDto())
                    ->setDateTime(new \DateTime($el->getStartDateTime()))
                    ->setCallsCount($el->getDuration())
            );
        }

        return $maxCallPerMinutesDtoSql;
    }
}