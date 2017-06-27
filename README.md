[![Build status](https://travis-ci.org/ustal/RapidAPI-library.svg?branch=master)](https://travis-ci.org/ustal/RapidAPI-library)
[![Latest Stable Version](https://poser.pugx.org/ustal/rapidapi-library/v/stable)](https://packagist.org/packages/ustal/rapidapi-library)
[![Total Downloads](https://poser.pugx.org/ustal/rapidapi-library/downloads)](https://packagist.org/packages/ustal/rapidapi-library)
[![Latest Unstable Version](https://poser.pugx.org/ustal/rapidapi-library/v/unstable)](https://packagist.org/packages/ustal/rapidapi-library)
[![License](https://poser.pugx.org/ustal/rapidapi-library/license)](https://packagist.org/packages/ustal/rapidapi-library)
[![codecov](https://codecov.io/gh/ustal/RapidAPI-library/branch/master/graph/badge.svg)](https://codecov.io/gh/ustal/RapidAPI-library/branch/master)
#### Manual
Вызываем менеджер
`$manager = $this->get('manager');`

Передаем название блока (т.е. какой именно блок будем юзать).
`$manager->setBlockName($blockName);`

Получаем все валидные данные (согласно метадате)
`$validData = $manager->getValidData();`

Парсим урл из метадаты и переданный. Заменяем {someVar} на значение из $validData. Если в метадате только куски, например `/someAction/`. Смотря как делали метадату и может для каждоого Endpoint разные ссылки.
`$url = $manager->createFullUrl($validData, $url);`

Создаем `headers`. Обязательно удаляем из `$validData` переменные, которые передавать не надо (использовались для генерации хедера. Для этого нужна ссылка `function createHeaders(&$data)`. Эту функцию описываем в пакете.
`$headers = $manager->createHeaders($validData);`

Получаем переменные. Иногда бывает что и для POST запросов надо отправить кучу в URL. Поэтому, используя, `urlParam` можно разделить параметры на две части. 
`$urlParams = $manager->getUrlParams();`
`$bodyParams = $manager->getBodyParams();`

$result = $manager->send($url, $urlParams, $bodyParams, $headers);


### Custom tags use only in assoc array with `"custom"` key variable of block and block's arguments. 

#### Blocks
| TagName   | Type    | Description |
|-----------|---------|-------------|
| method*   | String  | One of `PUT`, `POST`, `GET`, `DELETE`, `PATCH` etc|
| url*      | String  | Second part of endpoint `/endpointName/action` or whole endpoint `http://example/com/api/v1/endpointName/action` can contain {var}, which will be replaced args by name (in case) or vendorName. Args to replace must be without `wrapName` tag|
| type      | String  | `multipart` or `json`. Default: json|
| snakeCase | Boolean | True if you want to change all name from `exampleVendorName` to `example_vendor_name`. Priority is given to same tag of args. Default: false|

## Specific tags (depends on dataType of argument)

#### Map
| TagName       | Type    | Description |
|---------------|---------|-------------|
| divide        | Boolean | Use with Map. Divide string from marketPlace to List.
| toFloat       | Boolean | Convert `value` or `list of values` into Float. If u divided Map and vendor wants get float values not strings
| length        | Number  | Length of float or string param. Set 1 to convert 123.1111 to 123.1 or "123.1111" to "123.1"

#### Map (temporary not available)
| lat           | String  | If divide is true, change list info assocc Array with key = `lat` value
| lng           | String  | If divide is true, change list info assocc Array with key = `lng` value

#### DatePicker
| TagName       | Type    | Description |
|---------------|---------|-------------|
| dateTime.fromFormat   | Array   | Create date from one of format. Like [`Y-m-d\TH:i:s\Z`, `Y-m-d`, `timestamp`].
| dateTime.toFormat     | String  | Convert data to current format. Like `Y-m-d\TH:i:s\Z`

#### Boolean
| TagName       | Type    | Description |
|---------------|---------|-------------|
| toInt         | Boolean | Convert true/false into integer 1/0|
| toString      | Boolean | Boolean convert `true` into `"true"` and `false` into `"false"` Useful if need send var in url.
 
#### Number

#### String

#### Select

#### File
| TagName       | Type    | Description |
|---------------|---------|-------------|
| jsonParse     | Boolean | парсит файл и вставляет в собранный JSON|
| base64encode  | Boolean | закодировать содержимое файла в base64|

#### Array
| TagName       | Type    | Description |
|---------------|---------|-------------|
| keyValue      | Array   | Use to create key=>value from array with params key and value.
| keyValue.key  | String  | Work only with Arrays. Create `key->value` array from multi-dimensional array. Set one of the structure parameters as the key. Do the same with the value. 
| keyValue.value| String  | Work only with Arrays. Create `key->value` array from multi-dimensional array. Set one of the structure parameters as the key. Do the same with the value.

#### List
| TagName       | Type    | Description |
|---------------|---------|-------------|
| toArray       | Boolean | Convert string into List (use `slug` to implode)
| toString      | Boolean | Convert List into String with delimetr `slug`. Default `slug` = `,`
| slug          | String  | Implode or explode arrays/strings by this `slug`. Default `slug` = `,`
| toFloat       | Boolean | Convert `list of values` into Float
| floatLength?   | Number  | Length of float param. Set 1 to convert 123.1111 to 123.1

#### All tags (work with all dataTypes)
| TagName       | Type    | Description |
|---------------|---------|-------------|
| wrapName      | String  | используется для вложенности параметров. Создания дерева вложенности переменных. forum.post.comment создаст массивы forum:{post:{comment:{name}}} где name имя переменной, для которой указан wrapName. Соотв не забывать что последнее всегда будет имя переменной. Не стоит дублировать name: commentContent, wrapName: forum.post.comment.commentContent. Будет: forum:{post:{comment:{commentContetn:{commentContent:{value}}}}}|
| vendorName    | String  | имя аргумента, которое хочет получить вендор. Если не указано, вендор получит имя аргумента в snake case.|
| urlParam      | Boolean | параметр используется в ссылке. В ссылке никаких {var=value&foo=bar} не надо. Просто эта переменная (по name или vendorName) будет добавлена со своим значением к ссылке. Использовать с GET параметром не надо. Параметры автоматически будут переданы в url|
| snakeCase     | Boolean | true/false. Если стоит true, даже если у блока стоит false, переменная будет преобразована в camel_case|
| complex       | Array   | сложный параметр. Когда значение одного поля является ключ, другого - значение. Например когда хотят получить {type: email, value: {value}}. Соотв будет только одно поле email и его значение {value}. Но добавится параметр complex: true|
| complex.key   | String  | имя для ключа. В примере выше это будет "type". Используется когда complex: true|
| complex.value | String  | имя для значения. В примере выше это будет "value". Используется когда complex: true|

#### Пример комплекса
```
{"contacts":
    [
    "type": "facebook",
    "value": {value}
    ],
    [
    "type": "twitter",
    "value": {value}
    ]
}

```

Первый пример (мультипарт)
POST https://your-domain-name.example.com/forum/1/category/2/newPost?insertPostSafeAndWhatEver=1&draft=true
И ожидает получить title, content, attachment

```
{
  "name": "createNewPost",
  "description": "Create new post",
  "custom": {
    "method": "POST",
    "type": "multipart", //default json
    "url": "https://{domain}.example.com/forum/{forumId}/category/{category_id}/newPost"
  },
  "args": [
    {
      "name": "domain",
      "type": "String",
      "info": "",
      "required": true
    },
    {
      "name": "forumId",
      "type": "String",
      "info": "",
      "required": true,
      "custom": {
        "vendorName": "forumId" // always use vendorName or snake style of "name". Repace forumId, not forum_id
      }
    },
    {
      "name": "categoryId", // transform to category_id, and replace it in url
      "type": "String",
      "info": "",
      "required": true
    },
    {
      "name": "insertSafe",
      "type": "Boolean",
      "info": "Insert post safe and what ever bla-bla-bla",
      "required": false,
      "custom": {
        "vendorName": "insertPostSafeAndWhatEver", //if not set, param name will be insert_safe (snake case)
        "toInt": true, //default false
        "urlParam": true // default false
      }
    },
    {
      "name": "draft",
      "type": "Boolean",
      "info": "",
      "required": true,
      "custom": {
        "urlParam": true
      }
    },
    {
      "name": "title",
      ....
    },
    {
      "name": "content"
    },
    {
      "name": "attachment",
      "type": "File",
      "info": "",
      "required": false
    }
  ],
  "callbacks": [
    {
      "name": "error",
      "info": "Error"
    },
    {
      "name": "success",
      "info": "Success"
    }
  ]
}
```


Второй пример (base64)
POST https://your-domain-name.example.com/forum/1/category/2/newPost?insertPostSafeAndWhatEver=1&draft=true
И ожидает получить title, content, attachment

```
{
  "name": "createNewPost",
  "description": "Create new post",
  "custom": {
    "method": "POST",
    "url": "https://{domain}.example.com/forum/{forumId}/category/{categoryId}/newPost"
  },
  "args": [
    {
      "name": "domain",
      "type": "String",
      "info": "",
      "required": true
    },
    {
      "name": "forumId",
      "type": "String",
      "info": "",
      "required": true,
      "custom": {
        "vendorName": "forumId"
      }
    },
    {
      "name": "categoryId",
      "type": "String",
      "info": "",
      "required": true,
      "custom": {
        "vendorName": "categoryId"
      }
    },
    {
      "name": "insertSafe",
      "type": "Boolean",
      "info": "Insert post safe and what ever bla-bla-bla",
      "required": false,
      "custom": {
        "vendorName": "insertPostSafeAndWhatEver",
        "toInt": true,
        "urlParam": true
      }
    },
    {
      "name": "draft",
      "type": "Boolean",
      "info": "",
      "required": true,
      "custom": {
        "urlParam": true
      }
    },
    {
      "name": "title",
      ....
    },
    {
      "name": "content"
    },
    {
      "name": "attachment",
      "type": "File",
      "info": "",
      "required": false,
      "custom": {
        "base64encode": true
      }
    }
  ],
  "callbacks": [
    {
      "name": "error",
      "info": "Error"
    },
    {
      "name": "success",
      "info": "Success"
    }
  ]
}
```

Третий пример
POST https://your-domain-name.example.com/forum/1/category/2/newPost
И ожидает получить JSON такого вида
```
{
  "post": {
    "title": "Post title",
    "content": "Post content",
    "attachment": "base64(image)"
    "user": {
      "name": "John Doe",
      "contacts": [
        {"typeContact": "email", "valueContact": "john@example.com"},
        {"typeContact": "twitter", "valueContact": "john111"}
      ]
    },
    "voting": {
      "question": "Be or not to be?",
      "answers": [
        "yes",
        "no",
        "dont know"
      ]
    },
    "other": {Object}
  }
}
```

```
{
  "name": "createNewPost",
  "description": "Create new post",
  "custom": {
    "method": "POST",
    "url": "https://{domain}.example.com/forum/{forum_id}/category/{category_id}/newPost"
  }
  "args": [
    {
      "name": "domain",
      "type": "String",
      "info": "",
      "required": true
    },
    {
      "name": "forumId",
      "type": "String",
      "info": "",
      "required": true
    },
    {
      "name": "categoryId",
      "type": "String",
      "info": "",
      "required": true
    },
    {
      "name": "title",
      "type": "String",
      "info": "",
      "required": "",
      "custom": {
        "wrapName": "post"
      }
    },
    {
      "name": "content",
      "type": "String",
      "info": "",
      "required": "",
      "custom": {
        "wrapName": "post"
      }
    },
    {
      "name": "file",
      "type": "File",
      "info": "",
      "required": "",
      "custom": {
        "wrapName": "post",
        "vendorName": "attachment",
        "base64encode": true
      }
    },
    {
      "name": "userName",
      "type": "String",
      "info": "",
      "required": "",
      "custom": {
        "vendorName": "name",
        "wrapName": "post.user"
      }
    },
    {
      "name": "email",
      "type": "String",
      "info": "User contact email",
      "required": "",
      "custom": {
        "wrapName": "post.user.contacts",
        "complex": true,
        "keyName": "typeContact",
        "valueName": "valueContact"
      }
    },
    {
      "name": "twitter",
      "type": "String",
      "info": "User contact twitter",
      "required": "",
      "custom": {
        "wrapName": "post.user.contacts",
        "complex": true,
        "keyName": "typeContact",
        "valueName": "valueContact"
      }
    },
    {
      "name": "votingQuestion",
      "type": "String",
      "info": "",
      "required": "",
      "custom": {
        "vendorName": "question",
        "wrapName": "post.voting"
      }
    },
    {
      "name": "answers",
      "type": "Array",
      "info": "If send String comma separated it make array???",
      "required": "",
      "custom": {
        "wrapName": "post.voting"
      }
    },
    {
      "name": "other",
      "type": "File",
      "info": "Data in JSON format unknown structure or cannot be translate into metadata. Example: Unknown elements"
      "required": "",
      "custom": {
        "jsonParse": true
        "wrapName": "post"
      }
    }
  ],
  "callbacks": [
    {
      "name": "error",
      "info": "Error"
    },
    {
      "name": "success",
      "info": "Success"
    }
  ]
}
```
