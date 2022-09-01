<?php

namespace tests\App\Entity;

use App\Entity\Package;
use PHPUnit\Framework\TestCase;
use App\Scraper\Wireless;

class PackageTest extends TestCase
{
  public function testPackageCreate()
  {
    $package = new Package();
    $package->setTier(array_key_first(Wireless::PACKAGES_NAMES));
    $this->assertEquals(array_key_first(Wireless::PACKAGES_NAMES), $package->getTier());

    // further tests
  }
}
