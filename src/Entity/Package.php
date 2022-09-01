<?php

declare(strict_types=1);

namespace App\Entity;

class Package implements \JsonSerializable
{
  /**
   * @var string|null
   */
  private ?string $option = null;

  /**
   * @var string
   */
  private string $tier;

  /**
   * @var string
   */
  private string $title;

  /**
   * @var string
   */
  private string $description;

  /**
   * @var string
   */
  private string $price;

  /**
   * @var float
   */
  private float $monthlyPrice;

  /**
   * @var ?float
   */
  private ?float $discount;

  /**
   * @return string|null
   */
  public function getOption(): ?string
  {
    return $this->option;
  }

  /**
   * @param string|null $option
   */
  public function setOption(?string $option): void
  {
    $this->option = $option;
  }

  /**
   * @return string
   */
  public function getTier(): string
  {
    return $this->tier;
  }

  /**
   * @param string $tier
   */
  public function setTier(string $tier): void
  {
    $this->tier = $tier;
  }

  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @param string $title
   */
  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * @param string $description
   */
  public function setDescription(string $description): void
  {
    $this->description = $description;
  }

  /**
   * @return string
   */
  public function getPrice(): string
  {
    return $this->price;
  }

  /**
   * @param string $price
   */
  public function setPrice(string $price): void
  {
    $this->price = $price;
  }

  /**
   * @return float
   */
  public function getMonthlyPrice(): float
  {
    return $this->monthlyPrice;
  }

  public function setMonthlyPrice(array $subIndicatorDivider = ['dividerFlag' => 'Annual', 'dividerValue' => 12], string $curency = '£'): void
  {
    if ('' !== $this->price) {
      $intPrice =  floatval(str_replace($curency, '', $this->price));
      if ($this->option == $subIndicatorDivider['dividerFlag'])
        $this->monthlyPrice = $intPrice / $subIndicatorDivider['dividerValue'];
      else $this->monthlyPrice = $intPrice;
    }
  }

  /**
   * @return float
   */
  public function getDiscount(): ?float
  {
    return $this->discount;
  }

  /**
   * @param ?float $discount
   */
  public function setDiscount(?float $discount): void
  {
    $this->discount = $discount;
  }

  /**
   * @param Package $monthlyPackage
   */
  public function calculateAnnualDiscount(Package $monthlyPackage): void
  {
    $monthlySaving = $monthlyPackage->getMonthlyPrice() - $this->monthlyPrice;
    $this->discount = round(12 * $monthlySaving, 1);
  }

  public function jsonSerialize()
  {
    return [
      'tier' => $this->getTier(),
      'option' => $this->getOption(),
      'title' => $this->getTitle(),
      'description' => $this->getDescription(),
      'price' => $this->getPrice(),
      'discount' => (null !== $this->getDiscount()) ? $this->getDiscount() : ''
    ];
  }
}
