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

## /api/{project} ##

### `POST` /api/{project} ###

_Create a new sku record without details and return code allocated._

#### Parameters ####

sku:

  * type: object (SkuType)
  * required: true

sku[project]:

  * type: string
  * required: true
  * description: Name of the application

#### Response ####

code:

  * type: string

## /api/{sku} ##

### `DELETE` /api/{sku} ###

_Removes a sku._

#### Requirements ####

**sku**

  - Description: Sku code


### `GET` /api/{sku} ###

_Get a sku._

#### Requirements ####

**sku**

  - Description: Sku code
  
#### Response ####

project:

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


### `PUT` /api/{sku} ###

_Edit an sku from the submitted data._

#### Requirements ####

**sku**

  - Requirement: [\d\w]*
  - Type: string

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
