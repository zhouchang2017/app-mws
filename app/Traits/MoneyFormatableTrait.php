<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/18
 * Time: 11:00 AM
 */

namespace App\Traits;


use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

trait MoneyFormatableTrait
{
    protected $currency = 'USD';

    public $priceFields = ['price'];


    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return MoneyFormatableTrait
     */
    public function setCurrency(string $currency = null)
    {
        $this->currency = $currency ?? config('dealpaw.currency');
        return $this;
    }

    private function displayCurrencyUsing($value)
    {
        $money = new Money($value, new Currency($this->getCurrency()));
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        return $moneyFormatter->format($money);
    }

    private function displayCurrencyByTextUsing($value)
    {
        $money = new Money($value, new Currency('USD'));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    private function saveCurrencyUsing($value)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $money = $moneyParser->parse($value, $this->getCurrency());

        return $money->getAmount();
    }

    public function hasSetMoneyMutator($key)
    {
        return in_array($key,$this->priceFields);
    }

    public function setAttribute($key, $value)
    {
        if ($this->hasSetMoneyMutator($key)) {
            return $this->attributes[$key] = $this->saveCurrencyUsing($value === 0 ? '0.00' : (string)$value);
        }
        return parent::setAttribute($key, $value);
    }

    public function hasGetMutator($key)
    {
        if ($this->hasSetMoneyMutator($key)) {
            return true;
        }
        return parent::hasGetMutator($key);
    }

    protected function mutateAttribute($key, $value)
    {
        if ($this->hasSetMoneyMutator($key)) {
            return $this->displayCurrencyUsing($value);
        }
        return parent::mutateAttribute($key, $value);
    }

}