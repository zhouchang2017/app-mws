<?php

namespace App\Erp\Metrics;

class SqliteTrendDateExpression extends TrendDateExpression
{
    /**
     * Get the value of the expression.
     *
     * @return mixed
     */
    public function getValue()
    {
        $offset = $this->offset();

        if ($offset > 0) {
            $interval = '\'+'.$offset.' hour\'';
        } elseif ($offset === 0) {
            $interval = '\'+0 hour\'';
        } else {
            $interval = '\'-'.($offset * -1).' hour\'';
        }

        switch ($this->unit) {
            case 'month':
                return "strftime('%Y-%m', datetime({$this->wrap($this->column)}, {$interval}))";
            case 'week':
                return "strftime('%Y-%W', datetime({$this->wrap($this->column)}, {$interval}))";
            case 'day':
                return "strftime('%Y-%m-%d', datetime({$this->wrap($this->column)}, {$interval}))";
            case 'hour':
                return "strftime('%Y-%m-%d %H:00', datetime({$this->wrap($this->column)}, {$interval}))";
            case 'minute':
                return "strftime('%Y-%m-%d %H:%M:00', datetime({$this->wrap($this->column)}, {$interval}))";
        }
    }
}
