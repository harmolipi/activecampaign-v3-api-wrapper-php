<?php

namespace Harmolipi\ActiveCampaign\models;

class Deal implements \JsonSerializable
{
  private $title;
  private $value;
  private $currency;
  private $owner;
  private $stage;
  private $group;
  private $contact;
  private $account;
  private $fields;
  private $description;
  private $percentage;
  private $status;

  /**
   * Deal constructor.
   *
   * @param string $title
   * @param int $value
   * @param string $currency
   * @param string $owner
   * @param string $stage
   * @param string $group
   * @param string $contact
   * @param string $account
   * @param array $fields
   * @param string $description
   * @param int $percentage
   * @param string $status
   */
  public function __construct(string $title, int $value, string $currency, string $owner, string $stage = '', string $group = '', string $contact = '', string $account = '', array $fields = array(), string $description = '', int $percentage = null, string $status = '')
  {
    // Throw an error if neither the stage nor group is set:
    if (!$stage && !$group) {
      throw new \Exception('Either the stage or group must be set');
    }

    $this->title = $title;
    $this->value = $value;
    $this->currency = $currency;
    $this->owner = $owner;

    if ($stage) {
      $this->stage = $stage;
    }

    if ($group) {
      $this->group = $group;
    }

    if ($contact) {
      $this->contact = $contact;
    }

    if ($account) {
      $this->account = $account;
    }

    if ($fields) {
      $this->fields = $fields;
    }

    if ($description) {
      $this->description = $description;
    }

    if ($percentage) {
      $this->percentage = $percentage;
    }

    if ($status) {
      $this->status = $status;
    }
  }

  /**
   * Set the group of the deal
   *
   * @param string $group
   * @return $this
   */
  public function setGroup(string $group): self
  {
    $this->group = $group;
    return $this;
  }

  /**
   * Set the stage of the deal
   *
   * @param string $stage
   * @return $this
   */
  public function setStage(string $stage): self
  {
    $this->stage = $stage;
    return $this;
  }

  /**
   * Set the contact of the deal
   *
   * @param string $contact
   * @return $this
   */
  public function setContact(string $contact): self
  {
    $this->contact = $contact;
    return $this;
  }

  /**
   * Set the account of the deal
   *
   * @param string $account
   * @return $this
   */
  public function setAccount(string $account): self
  {
    $this->account = $account;
    return $this;
  }

  /**
   * Set the fields of the deal
   *
   * @param array $fields
   * @return $this
   */
  public function setFields(array $fields): self
  {
    $this->fields = $fields;
    return $this;
  }

  /**
   * Set the description of the deal
   *
   * @param string $description
   * @return $this
   */
  public function setDescription(string $description): self
  {
    $this->description = $description;
    return $this;
  }

  /**
   * Set the percentage of the deal
   *
   * @param int $percentage
   * @return $this
   */
  public function setPercentage(int $percentage): self
  {
    $this->percentage = $percentage;
    return $this;
  }

  /**
   * Set the status of the deal
   *
   * @param string $status
   * @return $this
   */
  public function setStatus(string $status): self
  {
    $this->status = $status;
    return $this;
  }

  // Create a deal from an array:
  /**
   * Create a deal from an array
   *
   * @param array $array
   * @return Deal
   */
  public static function fromArray(array $array): self
  {
    $deal = new self($array['title'], $array['value'], $array['currency'], $array['owner']);

    if (isset($array['stage'])) {
      $deal->setStage($array['stage']);
    }

    if (isset($array['group'])) {
      $deal->setGroup($array['group']);
    }

    if (isset($array['contact'])) {
      $deal->setContact($array['contact']);
    }

    if (isset($array['account'])) {
      $deal->setAccount($array['account']);
    }

    if (isset($array['fields'])) {
      $deal->setFields($array['fields']);
    }

    if (isset($array['description'])) {
      $deal->setDescription($array['description']);
    }

    if (isset($array['percentage'])) {
      $deal->setPercentage($array['percentage']);
    }

    if (isset($array['status'])) {
      $deal->setStatus($array['status']);
    }

    return $deal;
  }

  /**
   * Specify data which should be serialized to JSON
   *
   * @return mixed data which can be serialized by json_encode,
   * which is a value of any type other than a resource.
   */
  public function jsonSerialize(): mixed
  {
    return [
      'deal' => [
        'title' => $this->title,
        'value' => $this->value,
        'currency' => $this->currency,
        'owner' => $this->owner,
        'stage' => $this->stage,
        'group' => $this->group,
        'contact' => $this->contact,
        'account' => $this->account,
        'fields' => $this->fields,
        'description' => $this->description,
        'percentage' => $this->percentage,
        'status' => $this->status
      ],
    ];
  }

  /**
   * Convert the deal to an array
   *
   * @return array
   */
  public function toArray(): array
  {
    return [
      'title' => $this->title,
      'value' => $this->value,
      'currency' => $this->currency,
      'owner' => $this->owner,
      'stage' => $this->stage,
      'group' => $this->group,
      'contact' => $this->contact,
      'account' => $this->account,
      'fields' => $this->fields,
      'description' => $this->description,
      'percentage' => $this->percentage,
      'status' => $this->status
    ];
  }
}
