<?php

namespace Harmolipi\ActiveCampaign;

use Exception;
use Harmolipi\ActiveCampaign\models\Contact;

/**
 * Wrapper class for the ActiveCampaign API.
 */
class ActiveCampaignAPI
{
  private $api_base;
  private $api_key;
  public $connection_id;

  /**
   * Constructor.
   * 
   * @param string $api_url The API endpoint URL.
   * @param string $api_key The API key.
   * @param string $connection_id The connection ID to use for the API requests.
   */
  public function __construct($api_url, $api_key, $connection_id = null)
  {
    $this->api_base = $api_url;
    $this->api_key = $api_key;
    $this->connection_id = $connection_id ?? null;
  }

  public function setConnectionId($connection_id)
  {
    $this->connection_id = $connection_id;
  }

  /**
   * Make a request to the API.
   * 
   * @param string $url The API endpoint URL.
   * @param array $params Optional parameters to include in the request.
   * @param string $method The HTTP method to use (default: GET).
   * @param array $data The data to send in the request body (for PUT and POST requests).
   * @return mixed The API response.
   */
  private function makeRequest($endpoint, $params = array(), $method = 'GET', $data = array())
  {
    $params['api_key'] = $this->api_key;

    $curl = curl_init();

    $url = $this->api_base . $endpoint;
    $full_url = $url . '?' . http_build_query($params);

    curl_setopt_array($curl, array(
      CURLOPT_URL => $full_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      )
    ));

    if ($method == 'POST' || $method == 'PUT') {
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($response === false || !empty($response['errors'])) {
      $error = curl_error($curl);
      curl_close($curl);
      throw new Exception("cURL error: $error");
    }

    $result = json_decode($response, true);

    if ($http_code < 200 || $http_code >= 300 || isset($result['status']) && $result['status'] == 'error') {
      $error_message = isset($result['error']) ? $result['error'] : 'Unknown error occurred';
      curl_close($curl);
      throw new Exception("HTTP error $http_code: $error_message");
    }

    curl_close($curl);

    return $result;
  }

  public function test()
  {
    return 'test';
  }

  /* ======================== Contacts ======================== */

  /**
   * Get a list of contacts.
   * 
   * @param array $params Optional parameters to filter and sort the list of contacts.
   * @return array An array of contacts.
   */
  public function getContacts($params = array())
  {
    $endpoint =  '/api/3/contacts';

    $response = $this->makeRequest($endpoint, $params);

    return $response['contacts'];
  }

  /**
   * Get a contact by ID.
   * 
   * @param int $id The ID of the contact to retrieve.
   * @return array The contact data.
   */
  public function getContact($id)
  {
    $endpoint =  '/api/3/contacts/' . $id;

    $response = $this->makeRequest($endpoint);

    return $response['contact'];
  }

  /**
   * Create a contact.
   * 
   * @param array $data The data to create the contact with.
   * @return array The created contact data.
   */
  public function createContact(Contact $data): Contact
  {
    $endpoint =  '/api/3/contacts';

    $response = $this->makeRequest($endpoint, array(), 'POST', $data);

    // Create a new contact from the response
    $contact = Contact::fromArray($response['contact']);

    return $contact;
  }

  /**
   * Update a contact.
   * 
   * @param int $id The ID of the contact to update.
   * @param array $data The data to update the contact with.
   * @return array The updated contact data.
   */
  public function updateContact($id, $data)
  {
    $endpoint =  '/api/3/contacts/' . $id;

    $response = $this->makeRequest($endpoint, array(), 'PUT', $data);

    return $response['contact'];
  }

  /* ======================== Account ======================== */

  /**
   * Get a list of accounts.
   * 
   * @param array $params Optional parameters to filter and sort the list of accounts.
   * @return array An array of accounts.
   */
  public function getAccounts($params = array())
  {
    $endpoint =  '/api/3/accounts';

    $response = $this->makeRequest($endpoint, $params);

    return $response['accounts'];
  }

  /**
   * Get an account by ID.
   * 
   * @param int $id The ID of the account to retrieve.
   * @return array The account data.
   */
  public function getAccount($id)
  {
    $endpoint =  '/api/3/accounts/' . $id;

    $response = $this->makeRequest($endpoint);

    return $response['account'];
  }

  /**
   * Create an account.
   * 
   * @param array $data The data to create the account with.
   * @return array The created account data.
   */
  public function createAccount($data)
  {
    $endpoint =  '/api/3/accounts';

    $response = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $response['account'];
  }

  /**
   * Update an account.
   * 
   * @param int $id The ID of the account to update.
   * @param array $data The data to update the account with.
   * @return array The updated account data.
   */
  public function updateAccount($id, $data)
  {
    $endpoint =  '/api/3/accounts/' . $id;

    $response = $this->makeRequest($endpoint, array(), 'PUT', $data);

    return $response['account'];
  }

  /**
   * Create a new contact-account association.
   * 
   * @param array $data The data to create the association with.
   * @return array The created association data.
   */
  public function createContactAccountAssociation($data)
  {
    $endpoint =  '/api/3/accountContacts';

    $response = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $response['accountContact'];
  }

  /**
   * Get a list of account custom fields.
   * 
   * @param array $params Optional parameters to limit the list of account custom fields.
   * @return array An array of account custom fields.
   */
  public function getAccountCustomFields($params = array())
  {
    $endpoint =  '/api/3/accountCustomFieldMeta';

    $response = $this->makeRequest($endpoint, $params);

    return $response['accountCustomFieldMeta'];
  }

  /* ======================== Custom Fields ======================== */

  /**
   * Get a list of custom fields.
   * 
   * @param array $params Optional parameters to filter and sort the list of custom fields.
   * @return array An array of custom fields.
   */
  public function getCustomFields($params = array())
  {
    $endpoint =  '/api/3/fields';

    $response = $this->makeRequest($endpoint, $params);

    return $response['fields'];
  }

  /* ======================== Tags ======================== */

  /**
   * Get a list of tags.
   * 
   * @param array $params Optional parameters to filter and sort the list of tags.
   * @return array An array of tags.
   */
  public function getTags($params = array())
  {
    $endpoint =  '/api/3/tags';

    $response = $this->makeRequest($endpoint, $params);

    return $response['tags'];
  }

  /* ======================== Contact Tags ======================== */

  /**
   * Add a tag to a contact.
   * 
   * @param int $contact_id The ID of the contact to add the tag to.
   * @param int $tag_id The ID of the tag to add to the contact.
   * @return array The updated contact data.
   */
  public function addTagToContact(int $contact_id, int $tag_id)
  {
    $endpoint =  '/api/3/contactTags/';

    $data = [
      'contactTag' => [
        'contact' => $contact_id,
        'tag' => $tag_id,
      ],
    ];

    $response = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $response['contactTag'];
  }

  /* ======================== Deals ======================== */

  /**
   * Get a list of deals.
   * 
   * @param array $params Optional parameters to filter and sort the list of deals.
   * @return array An array of deals.
   */

  public function getDeals($params = array())
  {
    $endpoint =  '/api/3/deals';

    $response = $this->makeRequest($endpoint, $params);

    return $response['deals'];
  }

  /**
   * Get a deal by ID.
   * 
   * @param int $id The ID of the deal to retrieve.
   * @return array The deal data.
   */
  public function getDeal($id)
  {
    $endpoint =  '/api/3/deals/' . $id;

    $response = $this->makeRequest($endpoint);

    return $response['deal'];
  }

  // Get a deal's custom fields:
  /**
   * Get a deal's custom fields.
   * 
   * @param int $id The ID of the deal to retrieve the custom fields for.
   * @return array The deal's custom fields.
   */
  public function getDealCustomFieldData($id)
  {
    $endpoint =  '/api/3/deals/' . $id . '/dealCustomFieldData';

    $response = $this->makeRequest($endpoint);

    return $response['dealCustomFieldData'];
  }

  /**
   * Get a list of custom fields.
   * 
   * @param array $params Optional parameters to filter and sort the list of custom fields.
   * @return array An array of custom fields.
   */
  public function getDealCustomFields($params = array())
  {
    $endpoint =  '/api/3/dealCustomFieldMeta';

    $response = $this->makeRequest($endpoint, $params);

    return $response['dealCustomFieldMeta'];
  }

  /**
   * Create a deal.
   * 
   * @param array $data The data to create the deal with.
   * @return array The created deal data.
   */
  public function createDeal($data)
  {
    $endpoint =  '/api/3/deals';

    $response = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $response['deal'];
  }

  /* ======================== E-commerce Connections ======================== */

  /**
   * Get a list of connections.
   * 
   * @param array $params Optional parameters to filter and sort the list of connections.
   * @return array An array of connections.
   */
  public function getConnections(array $params = array())
  {
    $endpoint =  '/api/3/connections';

    $response = $this->makeRequest($endpoint, $params);

    return $response['connections'];
  }

  /**
   * Get a connection by ID.
   * 
   * @param int $id The ID of the connection to retrieve.
   * @return array The connection data.
   */
  public function getConnection(int $id)
  {
    $endpoint =  '/api/3/connections/' . $id;

    $response = $this->makeRequest($endpoint);

    return $response['connection'];
  }

  /**
   * Create a new connection.
   *
   * @param array $params An array of parameters to create the new connection.
   * @return array The created connection.
   */
  public function createConnection(array $data)
  {
    $endpoint = '/api/3/connections';

    $response = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $response['connection'];
  }

  /**
   * Update a connection.
   * 
   * @param int $id The ID of the connection to update.
   * @param array $data The data to update the connection with.
   * @return array The updated connection data.
   */
  public function updateConnection(int $id, array $data)
  {
    $endpoint =  '/api/3/connections/' . $id;

    $response = $this->makeRequest($endpoint, array(), 'PUT', $data);

    return $response['connection'];
  }

  /**
   * Get a list of customers from a connection.
   * 
   * @param int $id The ID of the connection to retrieve customers from.
   * @param array $params Optional parameters to filter and sort the list of customers.
   * @return array An array of customers.
   */
  public function getConnectionCustomers(int $id = null, array $params = array())
  {
    if (is_null($id)) {
      $id = $this->connection_id;
    }

    $endpoint =  '/api/3/connections/' . $id . '/customers';

    $response = $this->makeRequest($endpoint, $params);

    return $response;
  }

  /* ======================== E-commerce Customers ======================== */

  public function getCustomers($params = array())
  {
    $endpoint =  '/api/3/ecomCustomers';

    $response = $this->makeRequest($endpoint, $params);

    return $response['ecomCustomers'];
  }

  public function createCustomer($data)
  {
    $endpoint = '/api/3/ecomCustomers';

    $result = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $result['ecomCustomer'];
  }

  /* ======================== E-commerce Orders ======================== */

  public function getOrders($params = array())
  {
    $endpoint =  '/api/3/ecomOrders';

    $response = $this->makeRequest($endpoint, $params);

    return $response['ecomOrders'];
  }

  public function createOrder($data)
  {
    $endpoint = '/api/3/ecomOrders';

    $result = $this->makeRequest($endpoint, array(), 'POST', $data);

    return $result;
  }
}
