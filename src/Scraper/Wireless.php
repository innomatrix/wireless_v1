<?php

namespace App\Scraper;

use App\Scraper\Contracts\WebsiteInterface;

class Wireless implements WebsiteInterface
{
    const SUBSCRIPTION_OPTION = [
        'Monthly' => 'Monthly',
        'Annual' => 'Annual'
    ];

    const PACKAGES_NAMES = [
        'Basic' => 'Basic',
        'Standard' => 'Standard',
        'Optimum' => 'Optimum'
    ];

    const DIVIDER =  [
        'dividerFlag' => 'Annual',
        'dividerValue' => 12
    ];

    const CURRECNY = '£';

    public function getUrl(): string
    {
        return 'https://wltest.dns-systems.net/';
    }

    public function getName(): string
    {
        return 'Wireless';
    }

    public function getWrapperSelector(): string
    {
        return '.pricing-table';
    }

    public function getPackageWrapperSelector(): string
    {
        return '.package';
    }

    public function getHeaderSelector(): string
    {
        return '.header h3';
    }

    public function getNameSelector(): string
    {
        return '.package-name';
    }

    public function getDescSelector(): string
    {
        return '.package-description';
    }

    public function getPriceSelector(): string
    {
        return '.package-price .price-big';
    }

    public function getDataSelector(): string
    {
        return '.package-data';
    }

    public function extractor(string $what, string $where): ?string
    {
        foreach (constant('self::' . $where) as $definition => $value) {
            if (preg_match('/' . $definition . '/i', $what))
                return $value;
        }

        return null;
    }
}
