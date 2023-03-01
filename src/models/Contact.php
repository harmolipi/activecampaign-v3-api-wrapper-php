<?php

namespace Harmolipi\ActiveCampaign\models;

class Contact implements \JsonSerializable
{
  private $email;
  private $firstName;
  private $lastName;
  private $phone;
  private $fieldValues;
  private $orgid;

  /**
   * Contact constructor.
   * @param string $email
   * @param string $firstName
   * @param string $lastName
   * @param string $phone
   * @param array $fieldValues
   * @param string $orgid
   */
  public function __construct(string $email, string $firstName = '', string $lastName = '', string $phone = '', array $fieldValues = array(), string $orgid = '')
  {
    $this->email = $email;

    if ($firstName) {
      $this->firstName = $firstName;
    } else {
      $this->firstName = '';
    }

    if ($lastName) {
      $this->lastName = $lastName;
    } else {
      $this->lastName = '';
    }

    if ($phone) {
      $this->phone = $phone;
    } else {
      $this->phone = '';
    }

    if ($fieldValues) {
      $this->fieldValues = $fieldValues;
    } else {
      $this->fieldValues = array();
    }

    if ($orgid) {
      $this->orgid = $orgid;
    } else {
      $this->orgid = '';
    }
  }

  /**
   * Set the first name of the contact
   *
   * @param string $firstName
   * @return $this
   */
  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;
    return $this;
  }

  /**
   * Set the last name of the contact
   *
   * @param string $lastName
   * @return $this
   */
  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;
    return $this;
  }

  /**
   * Set the phone number of the contact
   *
   * @param string $phone
   * @return $this
   */
  public function setPhone(string $phone): self
  {
    $this->phone = $phone;
    return $this;
  }

  /**
   * Set the field values of the contact
   *
   * @param array $fieldValues
   * @return $this
   */
  public function setFieldValues(array $fieldValues): self
  {
    $this->fieldValues = $fieldValues;
    return $this;
  }

  /**
   * Set the orgid of the contact
   *
   * @param string $orgid
   * @return $this
   */
  public function setOrgid(string $orgid): self
  {
    $this->orgid = $orgid;
    return $this;
  }

  /**
   * Create a contact from an array
   *
   * @param array $array
   * @return Contact
   */
  public static function fromArray(array $array): self
  {
    $contact = new self($array['email']);

    if (isset($array['first_name'])) {
      $contact->setFirstName($array['first_name']);
    }

    if (isset($array['last_name'])) {
      $contact->setLastName($array['last_name']);
    }

    if (isset($array['phone'])) {
      $contact->setPhone($array['phone']);
    }

    if (isset($array['fieldValues'])) {
      $contact->setFieldValues($array['fieldValues']);
    }

    if (isset($array['orgid'])) {
      $contact->setOrgid($array['orgid']);
    }

    return $contact;
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
      'contact' => [
        'email' => $this->email,
        'first_name' => $this->firstName,
        'last_name' => $this->lastName,
        'phone' => $this->phone,
        'fieldValues' => $this->fieldValues,
        'orgid' => $this->orgid
      ]
    ];
  }

  /**
   * Convert the contact to an array
   *
   * @return array
   */
  public function toArray(): array
  {
    return [
      'email' => $this->email,
      'first_name' => $this->firstName,
      'last_name' => $this->lastName,
      'phone' => $this->phone,
      'fieldValues' => $this->fieldValues,
      'orgid' => $this->orgid
    ];
  }
}
