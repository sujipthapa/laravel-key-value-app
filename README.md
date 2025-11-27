## Laravel Key Value App

#### Routes

| Method | URI                     | Action | Description         |
| ------ | ----------------------- | ------ | ------------------- |
| POST   | /object                 | store  | Store new object    |
| GET    | /object/{object}        | show   | Find records by key |
| GET    | /object/get_all_records | index  | Get all records     |

### 1. `GET` All Records [/api/object/get_all_records](http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records)

**Response**

```json
{
    "data": [
        {
            "key": "greeting",
            "value": "Hello world!"
        }
    ],
    "links": {
        "first": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records?page=1",
        "last": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/get_all_records",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

### 2. `POST` Create Key Value Records [/api/object](http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object)

**Request Body**

#### **1. Simple JSON blob value**

```json
{
    "key": "user:1",
    "value": {
        "name": "Suman",
        "age": 30,
        "role": "admin",
        "active": true
    }
}
```

#### **2. Another JSON blob for later versions**

```json
{
    "key": "user:1",
    "value": {
        "name": "Suman",
        "age": 31,
        "role": "admin",
        "active": false,
        "updated": "2025-01-30"
    }
}
```

---

#### **3. Using a simple string as the value**

```json
{
    "key": "greeting",
    "value": "Hello world!"
}
```

#### **4. Another version of the same key**

```json
{
    "key": "greeting",
    "value": "Hello world updated!"
}
```

**Response**

```json
{
    "data": {
        "key": "greeting",
        "value": "Hello world updated!"
    },
    "success": true
}
```

## 3. `GET` [/api/object/greeting](http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting)

**Response**

```json
{
    "data": [
        {
            "key": "greeting",
            "value": "Hello world!"
        }
    ],
    "links": {
        "first": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?page=1",
        "last": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

**Optional: ?timestamp**

`GET` [/api/object/greeting?timestamp=1764236772](http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?timestamp=1764236772)

```json
{
    "data": [
        {
            "key": "greeting",
            "value": "Hello world!"
        }
    ],
    "links": {
        "first": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?page=1",
        "last": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://laravel-key-value-app-env.eba-nrifpbwg.ap-southeast-1.elasticbeanstalk.com/api/object/greeting",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

**API Rate Limiting**

In this scenario, API requests are restricted to 60 per minute to prevent a high volume of calls.
