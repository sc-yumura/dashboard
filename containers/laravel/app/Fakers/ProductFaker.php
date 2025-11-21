<?php
namespace App\Fakers;

class ProductFaker extends \Faker\Provider\Base
{
  public function productName($nbWords = 5)
  {
    $sentence = $this->generator->sentence($nbWords);
    return substr($sentence, 0, strlen($sentence) - 1);
  }

  public function ISBN()
  {
    return $this->generator->ean13();
  }
}