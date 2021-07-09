### Xây dựng dự án sử dụng API

- Backend: (PHP) -> logic nghiệp vụ
- Client:
- Mobile, PC Application
- Frontend -> HTML/CSS/JS

===========================================================================

### Mini Project:

- Backend:
  API
  Authen:
  - login
  - register
  - userList
  - update/delete/add => Tự viết thêm => Bài tập
    Product:
  - productList
  - add/update/delete => Tự viết thêm => Bài tập
- Client: Web Client -> Gọi API
  login page
  register page
  userList page
  productList page

### Triển khai:

#### B1. Xây dựng database.

- Tạo Tables: users, products
- Fake dữ liệu

```sql
    CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        fullname VARCHAR(50) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        birthday DATE,
        password VARCHAR(32),
        address VARCHAR(200)
    )
```

```sql
    CREATE TABLE login_tokens (
        id_user INT REFERENCES users(id),
        token VARCHAR(32) NOT NULL UNIQUE,
        PRIMARY KEY (id_user, token)
    )
```

#### B2. Phân tích API

BASE_URL: http://localhost/project/authen-api

> Authen:

#### API: login

```js
    - URL: api/authen.php
    - Method: POST
    - Request: {
        "action": "login",
        "email": "tranvandiep.it@gmail.com",
        "password": "123456"
    }
    - Response: {
        "status": 1 (1: success, 2 failed),
        "msg": "Error ???"
    }
```

#### API: logout

```js
    - URL: api/authen.php
    - Method: POST
    - Request: {
        "action": "logout"
        }
        - Response: {
        "status": 1 (1: success, 2 failed),
        "msg": "Error ???"
    }
```

#### API: register

```js
    - URL: api/authen.php
    - Method: POST
    - Request: {
        "action": "register",
        "fullname": "TRAN VAN DIEP",
        "username": "dieptv",
        "email": "tranvandiep.it@gmail.com",
        "password": "123456",
        "address": "Ha Noi"
    }
    - Response: {
        "status": 1 (1: success, 2 failed),
        "msg": "Error ???"
    }
```

#### API userList

```js
    - URL: api/authen.php
    - Method: POST
    - Request: {
        "action": "list"
    }
    - Response: {
        "status": 1 (1: success, 2 failed),
        "msg": "Error ???",
        "userList": [
            {
                "id": "1",
                "fullname": "TRAN VAN DIEP",
                "username": "dieptv",
                "email": "tranvandiep.it@gmail.com",
                "address": "Ha Noi"
            },
            {
                "id": "2",
                "fullname": "TRAN VAN DIEP",
                "username": "dieptv",
                "email": "tranvandiep.it@gmail.com",
                "address": "Ha Noi"
            }
        ]
    }
```

#### API productList:

```js
    - URL: api/product.php
    - Method: POST
    - Request: {
        "action": "list"
    }
    - Response: {
        "status": 1 (1: success, 2 failed),
        "msg": "Error ???",
        "productList": [
            {
                "id": "1",
                "title": "Bai viet",
                "thumbnail": "URL",
                "updated_at": "2021-06-12 12:02"
            },
            {
                "id": "2",
                "title": "Bai viet",
                "thumbnail": "URL",
                "updated_at": "2021-06-12 12:02"
            }
        ]
    }
```

#### B3. Code Server Backend

### Xây dựng khung chương trình

```
app
|__ db
|  |__ config.php
|  |__ dbhelper.php
|
|__ utils
|  |__ utility.php
|
|__ api
  |__ authen.php
  |__ product.php
```
