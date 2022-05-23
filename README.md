# Swatch Group sellout API v1: PHP SDK

## Introduction

This PHP SDK is for using the official Swatch Group sellout API v1.

You can use it to activate warranty cards. In the further you can use this SKD to do more with the API.

The Swatch Group sellout API is ready for the productive mode mid-February 2022.

**At the moment you can use the interface only for the brand Tissot!**

## Implementation

Example implementation:

```php
<?php

require ('swatchgroup/Sellout/SwatchGroupSelloutClient.php');

use swatchgroup\Sellout\SwatchGroupSelloutClient;

$client = new SwatchGroupSelloutClient(
    'c138f36c-e759-4c13-b7eb-5b2d197125c7',
    '3934b741-ae22-4279-9bd4-5003f86ae793',
    false
);
```

Constructor options:

Option | Description | Example
--- | --- | ---
Client id | Your Client id uuid | c138f36c-e759-4c13-b7eb-5b2d197125c7
Client secret | Your Client secret uuid | 3934b741-ae22-4279-9bd4-5003f86ae793
Is productive mode | You are in productive mode? | True or False

### List of operations

---

#### $client->getAccessToken(): object

##### Params:

You don't need any params for this function.

##### Return:

```php
//Example:
    {
      ["access_token"] => "DXjWpLW0XFFSoA8XXJ4r94Qlx3piKx1I"
      ["token_type"] => "Bearer"
      ["expires_in"] => 3600
    }
```
---

#### $client->sellout(string $salesTransactionId, string $salesDate, string $brandCode, string $posId, string $posIdType, string $posCountry, string $articleCode, string $serialNumber, bool $isSellout): object

##### Params:

Type | Param | Description | Example
--- | --- | --- | ---
string | $salesTransactionId | Sales transaction id is a unique id to identify transaction in log. | `"Sales transaction id"`
string | $salesDate | Datetime of the sale. | `"2022-02-17T09:00:00"`
string | $brandCode | The brand code of sellout. In the example is the brand code from Tissot. | `"TIS"`
string | $posId | POSId is the point of sale Id (where we have the sell-out). You can find this ID in backend of the Swatch Group. | `"123456"`
string | $posIdType | POSIdType is used to identify if POSId is a Retailer Id or Tissot Id. | Tissot Id = `"Internal"` or Retailer Id = `"Partner"`
string | $posCountry | POSCountry code. | `"DEU"`
string | $articleCode | Article code of watch. | `"T0914204705701"`
string | $serialNumber | Serial number of the sale watch. | `"Serial number from watch"`
bool | $isSellout | Is that a sellout or not?  | `true` or `false`

##### Return:

```php
//Example
    {
      "output": {
        "Success": true
      }
    }
```

### Support

---

Do you have any question or have ideas to make this project better?

Then write me an email: tom.gottschlich@uhrenlounge.de
