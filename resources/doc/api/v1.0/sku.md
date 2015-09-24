# Kali API - v1.0 - Sku

---

## /api/ ##

### `POST` /api/ ###

_Creates a new sku from the submitted data._

#### Parameters ####

sku:

  * type: object (SkuType)
  * required: true

sku[project]:

  * type: string
  * required: true
  * description: Name of the application

sku[type]:

  * type: string
  * required: true
  * description: Type of the entity to be skued

sku[id]:

  * type: string
  * required: true
  * description: Id of the entity to be skued

sku[permalink]:

  * type: string
  * required: false
  * description: Permanent link of the entity.


## /api/{sku} ##

### `DELETE` /api/{sku} ###

_Removes a sku._

#### Requirements ####

**sku**

  - Description: Sku code


### `GET` /api/{sku}.{_format} ###

_Get a sku._

#### Requirements ####

**sku**

  - Description: Sku code
  
**_format**

  - Requirement: json

#### Response ####

project:

  * type: string

foreignType:

  * type: string

foreignId:

  * type: string

code:

  * type: string

created_at:

  * type: DateTime

deleted_at:

  * type: DateTime

id:

  * type: string

type:

  * type: string

active:

  * type: boolean

permalink:

  * type: string


### `PUT` /api/{sku}.{_format} ###

_Edit an sku from the submitted data._

#### Requirements ####

**sku**

  - Requirement: [\d\w]*
  - Type: string

**_format**

  - Requirement: json

#### Parameters ####

sku:

  * type: object (SkuType)
  * required: false

sku[project]:

  * type: string
  * required: true
  * description: Name of the application

sku[type]:

  * type: string
  * required: true
  * description: Type of the entity to be skued

sku[id]:

  * type: string
  * required: true
  * description: Id of the entity to be skued

sku[permalink]:

  * type: string
  * required: false
  * description: Permanent link of the entity.
