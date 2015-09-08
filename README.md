# 1001Pharmacies Symfony REST Edition

1001Pharmacies Symfony REST Edition provides a RESTful API base project for 1001Phamarcies applications with authentication and versionning.

## Summary

- [Install](Resources/doc/api/install.md)
- [Generate a Client](Resources/doc/api/usage.md)
- [Overview](#overview)
    -    [Headers](#headers)
    -    [Version](#version)
    -    [Schema](#schema)
- [Root Endpoint](#root-endpoint)
- [HTTP Verbs](#http-verbs)
- [Authentication](#authentication)
    -    [Request token](#request-token)
        -    [Returned JSON](#returned-json)
    -    [Use token to fetch API](#request-token)
        -    [Request route](#request-route)
        -    [Headers parameters](#headers-parameters)
        -    [CLI example](#cli-example)
- [Hypermedia](#hypermedia)
    -    [Pagination](#pagination)
    -    [Links summary](#links-summary)

## Overview

### Headers

`Content-Type` header parameter **MUST** be set to `application/json` for all requests with JSON sent data.

### Version

By default, all requests receive the latest version of the API. We encourage you to explicitly set this version via the Accept header.

```HTTP
Accept: application/vnd.1001pharmacies.api+{format};version=latest
```

You can specify which version of the API to use via the Accept header too.

```HTTP
Accept: application/vnd.1001pharmacies.api+{format};version=1.0
```

### Schema

All API access are over HTTPS, and can be accessed from the ```https://{projectDomain}/api```. All data is sent and received as JSON (or XML soon).

You can specify the format in the Accept Header

JSON :

```HTTP
Accept: application/vnd.1001pharmacies.api+json;
```

Example :

```ShellSession
$ curl -i https://{projectDomain}/api -H "Accept: application/vnd.1001pharmacies.api+json;version=latest"
```

```HTTP
HTTP/1.1 401 Unauthorized
Date: Wed, 18 Feb 2015 16:28:05 GMT
Server: Apache
Cache-Control: no-cache
Content-Length: 37
Connection: close
Content-Type: application/vnd.1001pharmacies.api+json
```

Blank fields are omitted.

All timestamps are returned in ISO 8601 format:

```
YYYY-MM-DDTHH:MM:SS
```

## Root Endpoint

### API 

```ShellSession
"https://{projectDomain}/api"
```

### DEMO (only available on dev environment)

```ShellSession
"https://{projectDomain}/app_dev.php/demo"
```

## HTTP Verbs

Where possible, API strives to use appropriate HTTP verbs for each action.

| Verb     | Description                           |
| :------- | :------------------------------------ |
| GET      | Used for retrieving resources.        |
| POST     | Used for updating resources.          |
| PUT      | Used for adding resource.             |
| PATCH    | Used for patching resources.          |
| DELETE   | Used for deleting resources.          |

## Authentication

1001Pharmarcies Symfony REST Project use a strict Oauth2 'client_credentials' authentication.
You have to generate a client to recieve a public and secret key to request a valid token to fetch the API.
This token expires after 1 hour by default, so you'll have to request another one.

### Request Token

```HTTP
GET https://{projectDomain}/oauth/v2/token?client_id={your public key}&client_secret={your secret key}&grant_type=client_credential
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
POST https://{projectDomain}/demo
```

#### Headers parameters

| Name           | Type   | Description                                   |
| :------------- | :----- | :-------------------------------------------- |
| Authorization  | string | 'Bearer {the access token }'                  |
| Accept         | string | 'application/vnd.1001pharmacies.api+{format}' |

#### CLI example

```ShellSession
curl -H "Authorization: Bearer {access_token}" -H "Accept application/vnd.1001pharmacies.api+{format}" https://{projectDomain}/demo
```

## Hypermedia

All resources may have one or more ```*_links``` properties linking to other resources. These are meant to provide explicit URLs so that proper API clients don’t need to construct URLs on their own. It is highly recommended that API clients use these. Doing so will make future upgrades of the API easier for developers. All URLs are expected to be proper RFC 6570 URI templates.

**Hateoas** leverages the Serializer library to provide a nice way to build HATEOAS REST web services. HATEOAS stands for **Hypermedia as the Engine of Application State**, and adds **hypermedia links** to your **representations** (i.e. your API responses). HATEOAS is about the discoverability of actions on a resource.

For instance, let's say you have a User API which returns a representation of a single demo as follow:

```JSON
{
    "name": "Parc Expo : Villepinte", 
    "secret": "KpiIJDady7ql85x9qv8PVcKo8cd0aDthDZp35qZlocyLbnXFvAnWTC0mLDPbyiNn90jKSKavPEhlQYFrpyvo2w==", 
    "version": "1.1",
}
```

In order to tell your API consumers how to retrieve the data for this specific demo, you have to add your very first link to this representation, let's call it self as it is the URI for this particular demo:

```JSON
{
    "name": "Parc Expo : Villepinte", 
    "secret": "KpiIJDady7ql85x9qv8PVcKo8cd0aDthDZp35qZlocyLbnXFvAnWTC0mLDPbyiNn90jKSKavPEhlQYFrpyvo2w==", 
    "version": "1.1",
    "_links": {
        "self": {
            "href": "https://{project-url}/demo/centers/0"
        }
    }
}
```

### Pagination

Requests that return multiple items will be paginated to 10 items by default. You can specify further pages with the ```?page``` parameter. For some resources, you can also set a custom page size up to 100 with the ```?perPage``` parameter. Note that for technical reasons not all endpoints respect the ```?perPage``` parameter, see events for example.

```ShellSession
$ curl "https://{project-url}/demo/centers.json?page=2&perPage=10"
```

```JSON
{
    "_embedded": {
        "items": [
            {
                "_links": {
                    "self": {
                        "href": "https://{project-url}/demo/centers/0"
                    }
                }, 
                "name": "Parc Expo : Villepinte", 
                "secret": "KpiIJDady7ql85x9qv8PVcKo8cd0aDthDZp35qZlocyLbnXFvAnWTC0mLDPbyiNn90jKSKavPEhlQYFrpyvo2w==", 
                "version": "1.1"
            }, 
            {
                "_links": {
                    "self": {
                        "href": "https://{project-url}/demo/centers/1"
                    }
                }, 
                "name": "Parc Expo : Versailles", 
                "secret": "Y/2pwN9MsKQ85lKzgX1qjVWqugLqtDv6z/xhodl35+pRCn1MqRFj/HmaqjSXkKUKWrpTmZG7ZEeIJGNUUI9luw==", 
                "version": "1.1"
            }, 
            ...
        ]
    }, 
    "_links": {
        "first": {
            "href": "https://{project-url}/demo/centers?page=1&perPage=10"
        }, 
        "last": {
            "href": "https://{project-url}/demo/centers?page=1&perPage=10"
        }, 
        "self": {
            "href": "https://{project-url}/demo/centers?page=1&perPage=10"
        }
    }, 
    "limit": 10, 
    "page": 1, 
    "pages": 1, 
    "total": 4
}

```

Note that page numbering is 1-based and that omitting the ```?page``` parameter will return the first page.

### Links summary

The pagination info is included in the Link header. It is important to follow these Link header values instead of constructing your own URLs. In some instances, such as in the Commits API.

| Name       |    Description                                              |
| :--------- | :---------------------------------------------------------- |
| self       |    Shows the URL of the object itself.                      |
| uri        |    Shows the URL (external) of the object.                  |
| next       |    Shows the URL of the immediate next page of results.     |
| last       |    Shows the URL of the last page of results.               |
| first      |    Shows the URL of the first page of results.              |
| prev       |    Shows the URL of the immediate previous page of results. |
| \_embedded |    Shows the URL of the immediate previous page of results. |