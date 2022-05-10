<?php
namespace Diagro\Token\Model;

use JsonSerializable;

class Company implements JsonSerializable
{

    /**
     * @var int The company ID
     */
    private int $id;

    /**
     * @var string The name of the company
     */
    private string $name;

    /**
     * @var string country iso code
     */
    private string $country;

    /**
     * @var string curreny iso code
     */
    private string $currency;


    public function __construct(int $id, string $name, string $country, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->currency = $currency;
    }


    /**
     * @return int
     */
    public function id() : int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function country() : string
    {
        return $this->country;
    }


    /**
     * @return string
     */
    public function currency() : string
    {
        return $this->currency;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return ['id' => $this->id(), 'name' => $this->name(), 'country' => $this->country(), 'currency' => $this->currency()];
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name();
    }


}