# Flyp.me for Symfony

A Symfony Bundle for [Flyp.me](https://flyp.me/api) APIs

## Install

```
composer require paneedesign/flypme-bundle
```


## Usage

### Enable the bundle

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...

            new PaneeDesign\FlypMeBundle\PedFlypMeBundle(),
        ];

        // ...
    }

    // ...
}
```

### Create client

```php
$flypme = $this->get('ped_flyp_me.handler');
```

### Call APIs

#### Create

Create a new order

Last param could be "invoiced_amount" or "ordered_amount".

You can optionally specify destination and refund_address on the request.


```php
$flypme->orderNew("LTC", "ZEC", "0.02", "t1SBTywpsDMKndjogkXhZZSKdVbhadt3rVt", "LajyQBeZaBA1NkZDeY8YT5RYYVRkXMvb2T", "invoiced_amount");
```

result: 

```json
{
  "order": {
    "uuid": "1b5929e7-0e6c-44a6-a428-e4db856d880e",
    "destination": "t1SBTywpsDMKndjogkXhZZSKdVbhadt3rVt",
    "exchange_rate": "0.5403268038",
    "ordered_amount": "0.00980653",
    "invoiced_amount": "0.02",
    "charged_fee": "0.001",
    "from_currency": "LTC",
    "to_currency": "ZEC"
  },
  "expires": 1199
}
```


#### Update

Update an order

Last param could be "invoiced_amount" or "ordered_amount".

You can optionally specify destination and refund_address on the request.


```php
$flypme->orderUpdate("1b5929e7-0e6c-44a6-a428-e4db856d880e", "LTC", "ZEC", "0.03", "t1SBTywpsDMKndjogkXhZZSKdVbhadt3rVt", "LajyQBeZaBA1NkZDeY8YT5RYYVRkXMvb2T", "invoiced_amount");
```

result: 

```json
{
  "order": {
    "uuid": "1b5929e7-0e6c-44a6-a428-e4db856d880e",
    "destination": "t1SBTywpsDMKndjogkXhZZSKdVbhadt3rVt",
    "exchange_rate": "0.5292734791",
    "ordered_amount": "0.0148782",
    "invoiced_amount": "0.03",
    "charged_fee": "0.001",
    "from_currency": "LTC",
    "to_currency": "ZEC"
  },
  "expires": 1199
}
```

#### Accept

Accept an order

Accept an order by uuid


```php
$flypme->orderAccept("1b5929e7-0e6c-44a6-a428-e4db856d880e");
```

result: 

```json
{
  "order": {
    "uuid": "1b5929e7-0e6c-44a6-a428-e4db856d880e",
    "destination": "t1SBTywpsDMKndjogkXhZZSKdVbhadt3rVt",
    "exchange_rate": "0.5292734791",
    "ordered_amount": "0.0148782",
    "invoiced_amount": "0.03",
    "charged_fee": "0.001",
    "from_currency": "LTC",
    "to_currency": "ZEC"
  },
  "expires": 1053,
  "deposit_address": "MHoWWcJzNH4aWUKvrtMwpqMggRRBsvB7va"
}
```

#### Check

Check order status by uuid

Possible statuses are: DRAFT, WAITING_FOR_DEPOSIT, DEPOSIT_RECEIVED, DEPOSIT_CONFIRMED, EXECUTED, REFUNDED, CANCELED and EXPIRED

```php
$flypme->orderCheck("1b5929e7-0e6c-44a6-a428-e4db856d880e");
```

result: 

```json
{
    "status": "WAITING_FOR_DEPOSIT"
}
```

Result will also include 'txid' and 'txurl' when the order is EXECUTED:

```json
{
    "status": "EXECUTED",
    "txid": "XXXXX", 
    "txurl": "https://etherscan.io/tx/XXX"
}
```

#### Info

Get order full info

```php
$flypme->orderInfo("1b5929e7-0e6c-44a6-a428-e4db856d880e");
```

result: 

```json
{
  "order": {
    "uuid": "1b5929e7-0e6c-44a6-a428-e4db856d880e",
    "destination": "t1SBTywpsDMKndjogkXhZZSKdVbhadt3rVt",
    "exchange_rate": "0.5292734791",
    "ordered_amount": "0.0148782",
    "invoiced_amount": "0.03",
    "charged_fee": "0.001",
    "from_currency": "LTC",
    "to_currency": "ZEC"
  },
  "expires": 961,
  "status": "WAITING_FOR_DEPOSIT",
  "deposit_address": "MHoWWcJzNH4aWUKvrtMwpqMggRRBsvB7va"
}
```

Result will also include 'txid' and 'txurl' when the order is EXECUTED:

```json
{
  "order": {
    "uuid": "1b5929e7-0e6c-44a6-a428-e4db856d880e",
    (...)
  },
  (...)
  "deposit_address": "MHoWWcJzNH4aWUKvrtMwpqMggRRBsvB7va",
  "txid": "XXXXX",
  "txurl": "https://etherscan.io/tx/..."
}
```

#### Cancel order

Cancel a pending order

```php
$flypme->orderCancel("1b5929e7-0e6c-44a6-a428-e4db856d880e");
```

result: 

```json
{
    "result": "ok"
}
```

#### Query rates

Get exchange rates

```php
$flypme->dataExchangeRates();
```

result: 

```json
{
    "LTC-BTC": "0.0174777496",
    "BTC-LTC": "55.724760293",
    (...)
    "CREA-FYP": "2.06496",
    "FYP-CREA": "0.3423985733"
}
```

#### Query active currencies

Get available currencies information

A currency needs to have both exchange and send set to true to be enabled for the accountless exchange. Confirmation time is the expected time in minutes (approximate). Other parameters are self explanatory.

```php
$flypme->currencies();
```

result: 

```json
{
    "BTC": {
        "code": "BTC",
        "precision": 8,
        "display_precision": 4,
        "created_at": "2014-02-04T02:28:37.000Z",
        "updated_at": "2017-12-12T17:03:52.000Z",
        "name": "Bitcoin",
        "website": "https://bitcoin.org/",
        "confirmation_time": 20,
        "default": false,
        "charged_fee": "0.0008",
        "currency_type": "CRYPTO",
        "exchange": true,
        "send": true
    },
    (...)
    "ZEC": {
        "code": "ZEC",
        "precision": 8,
        "display_precision": 4,
        "created_at": "2017-02-24T11:41:27.000Z",
        "updated_at": "2017-12-12T14:44:02.000Z",
        "name": "Zcash",
        "website": "https://z.cash/",
        "confirmation_time": 5,
        "default": false,
        "charged_fee": "0.0008",
        "currency_type": "CRYPTO",
        "exchange": true,
        "send": true
    }
}
```

#### Query limits

Get max and min limits in $toCurrency. To get the limits in $fromCurrency you must calculate it using the exchange rate.

```php
// $flypme->orderLimits($fromCurrency, $toCurrency)
$flypme->orderLimits('BTC', 'ETH');
```

result: 

```json
{
  "min": "0.006",
  "max": "7.26915022"
}
```