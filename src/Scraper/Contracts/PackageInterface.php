<?php

namespace App\Scraper\Contracts;

interface PackageInterface
{
  public function getPackageWrapperSelector(): string;
  public function getHeaderSelector(): string;
  public function getNameSelector(): string;
  public function getDescSelector(): string;
  public function getPriceSelector(): string;
  public function getDataSelector(): string;

  public function extractor(string $what, string $where): ?string;
}
