# Kali-Server

Kali-Server provides a RESTful API centralizing SKU allocation and management for web-applications.

- [kali-client](https://github.com/1001Pharmacies/kali-client) The official *Symfony bundle* kali client
- [API doc](resources/doc/api/v1.0/index.md)
- [Commands](resources/doc/api/v1.0/commands.md)

## Summary

- [Install](resources/doc/api/install.md)
- [Generate a Client](resources/doc/api/usage.md)
- [Root Endpoint](#root-endpoint)
- [HTTP Verbs](#http-verbs)
- [Authentication](#authentication)
    -    [Request token](#request-token)
        -    [Returned JSON](#returned-json)
    -    [Use token to fetch API](#request-token)
        -    [Request route](#request-route)
        -    [Headers parameters](#headers-parameters)
        -    [CLI example](#cli-example)

## Overview

## Root Endpoint

### API 

```ShellSession
"https://{projectDomain}/api"
```

### API Doc 

```ShellSession
"https://{projectDomain}/doc"
```

## HTTP Verbs

Where possible, API strives to use appropriate HTTP verbs for each action.

| Verb     | Description                           |
| :------- | :------------------------------------ |
| GET      | Used for retrieving sku resources.    |
| POST     | Used for creating sku resources.      |
| PUT      | Used for updating sku resources.      |
| DELETE   | Used for deleting sku resources.      |

## Authentication

Kali server use a strict Oauth2 'client_credentials' authentication.
You have to generate a client to recieve a public and secret key to request a valid token to fetch the API.
This token expires after 1 hour by default, so you'll have to request another one.

### Request Token

```HTTP
GET https://{projectDomain}/oauth/v2/token?client_id={your public key}&client_secret={your secret key}&grant_type=client_credentials
```

#### Returned JSON

```JSON
{
    "access_token": "{your access token}",
    "expires_in": 3600,
    "token_type": "bearer",
    "scope": null
}
```

### Use Token to fetch API

#### Request Route

```HTTP
POST https://{projectDomain}/api
```

#### Headers parameters

| Name           | Type   | Description                                   |
| :------------- | :----- | :-------------------------------------------- |
| Authorization  | string | 'Bearer {the access token }'                  |

#### CLI example

```ShellSession
curl -H "Authorization: Bearer {access_token}" https://{projectDomain}/api
```
