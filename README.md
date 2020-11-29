# Provides connection to Emarsys Omnichannel Customer Engagement Platform API v2

## Install

``` sh
$ composer require qortex/php-emarsys-connector
```

## Use

First, obtain credentials for [Emarsys API User](https://help.emarsys.com/hc/en-us/articles/115004740329-your-account-security-settings#api-users).

Then, pass these credentials as username and secret to `EmarsysConnector` constructor:
``` php
use Qortex\Emarsys\Connector as EmarsysConnector;

$emarsysConnector = new EmarsysConnector($username, $secret);
```
Last, use any of the following `EmarsysConnector` methods to communicate with Emarsys Omnichannel Customer Engagement Platform:

``` php
function queryContacts(string $key, string $value)
```
Queries all contacts matching `$key` with the `$value` in Emarsys contacts database, regardless the contact lists.

Emarsys API [List Contact Data](https://dev.emarsys.com/v2/contacts/list-contact-data)

``` php
function createContact(string $key, array $properties)
```
Creates a contact in Emarsys contacts database and populates its properties with `$properties` . `$key` is used to provide uniqueness of the contact.

Emarsys API [Create Contacts](https://dev.emarsys.com/v2/contacts/create-contacts)

``` php
function deleteContact(string $key, string $value)
```
Deletes a contact with `$key` equals `$value` from Emarsys contacts database.

Emarsys API [Delete a Contact](https://dev.emarsys.com/v2/contacts/delete-contact)

``` php
function addContactToContactListById(int $listId, int $contactId)
```

Adds a contact defined by `$contactId` to a contact list defined by `$listId`.

Emarsys API [Add Contacts to a Contact List](https://dev.emarsys.com/v2/contact-lists/add-contacts-to-a-contact-list)

``` php
function removeContactFromContactListById(int $listId, int $contactId)
```
Removes a contact defined by `$contactId` from a contact list defined by `$listId`. 

Emarsys API [Remove Contacts from a Contact List](https://dev.emarsys.com/v2/contact-lists/remove-contacts-from-a-contact-list)

``` php
function countContactsInAContactList(int $listId)
```
Counts contacts in a contact list defined by `$listId`

Emarsys API [Count Contacts in a Contact List](https://dev.emarsys.com/v2/contact-lists/count-contacts-in-a-contact-list)
