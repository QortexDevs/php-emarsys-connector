<?php

namespace Qortex\Emarsys\Services;

use GuzzleHttp\Client;

class Connector
{
    private string $username;
    private string $secret;
    private string $apiUrl;
    private string $nonce;

    public function __construct($username, $secret, $apiUrl = null)
    {
        $this->username = $username;
        $this->secret = $secret;
        $this->apiUrl = $apiUrl ?? 'https://api.emarsys.net/api/v2/';
    }

    private function prepareRequest($endpoint)
    {
        $timestamp = gmdate('c');
        $this->nonce = md5(uniqid());
        $client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                #X-WSSE header description https://dev.emarsys.com/v2/before-you-start/authentication
                'X-WSSE' => 'UsernameToken Username="' . $this->username .
                    '", PasswordDigest="' . $this->getPasswordDigest($timestamp) .
                    '", Nonce="' . $this->nonce .
                    '", Created="' . $timestamp . '"',
                'Content-type' => 'application/json;charset=utf-8',
            ]
        ]);
        return $client;
    }

    private function sendPostRequest($endpoint, $payload)
    {
        $client = $this->prepareRequest($endpoint);
        $response = $client->request('POST', $endpoint . '/', ['json' => $payload]);
        return json_decode($response->getBody()->getContents());
    }

    private function sendGetRequest($endpoint, $query = [])
    {
        $client = $this->prepareRequest($endpoint);
        $response = $client->request('GET', $endpoint . '/', ['query' => $query]);
        return json_decode($response->getBody()->getContents());
    }

    private function getPasswordDigest($timestamp)
    {
        #Password digest algorithm description https://dev.emarsys.com/v2/first-steps/configure-authentication
        return base64_encode(sha1($this->nonce . $timestamp . $this->secret, false));
    }

    public function queryContacts(string $key, string $value)
    {
        return $this->sendGetRequest('contact/query', [
            $key => $value,
            'return' => $key
        ]);
    }

    public function createContact(string $key, array $properties)
    {
        return $this->sendPostRequest('contact', [
            'key_id' => $key,
            'contacts' => [
                $properties
            ],
        ]);
    }

    public function deleteContact(string $key, string $value)
    {
        return $this->sendPostRequest('contact/delete', [
            'key_id' => $key,
            $key => $value,
        ]);
    }

    public function removeContactFromContactListById(int $listId, int $contactId)
    {
        return $this->sendPostRequest('contactlist/' . $listId . '/delete', [
            'key_id' => 'id',
            'external_ids' => [
                $contactId
            ],
        ]);
    }

    public function addContactToContactListById(int $listId, int $contactId)
    {
        return $this->sendPostRequest('contactlist/' . $listId . '/add', [
            'key_id' => 'id',
            'external_ids' => [
                $contactId
            ],
        ]);
    }

    public function countContactsInAContactList(int $listId)
    {
        return $this->sendGetRequest('contactlist/' . $listId . '/count');
    }
}
