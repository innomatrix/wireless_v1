<?php

namespace App\Scraper\Contracts;

interface WebsiteInterface extends PackageInterface
{
  public function getUrl(): string;
  public function getName(): string;
}

