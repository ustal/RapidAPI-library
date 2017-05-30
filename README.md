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

#### Blocks
`method` - метод API Endpoint вендора (PUT, POST, GET etc)

`url` - ссылка или часть ссылки на API Endpoint вендора. Может быть целой ссылкой, или частичной. Тогда надо будет при создании ссылки отправлять не только валидные параметры, но и начало ссылки. Если встречаются переменные в {var}, будут заменены на значения из полученных данных. Внимание переменные {var} должны быть required и без wrapName. Заменяются только по vendorName или по name (в snake case формате). Соотв если переменная forumId, а в ссылке {forumId}, то будет ошибка, так как переменная, по умолчанию, будет forum_id. Или надо изменить {forum_id} или добавить к переменной "vendorName": "forumId"

`type` - Может быть multipart/json. По умолчанию json. Используется если надо отправить мультипарт вендору.

`snakeCase` - true/false. Если установлено в true, то все переменные блока, кроме тех у которых есть snakeCase == false, будут переименованы в camel_case

#### Args
`wrapName` - используется для вложенности параметров. Создания дерева вложенности переменных. forum.post.comment создаст массивы forum:{post:{comment:{name}}} где name имя переменной, для которой указан wrapName. Соотв не забывать что последнее всегда будет имя переменной. Не стоит дублировать name: commentContent, wrapName: forum.post.comment.commentContent. Будет:
forum:{post:{comment:{commentContetn:{commentContent:{value}}}}}

`vendorName` - имя аргумента, которое хочет получить вендор. Если не указано, вендор получит имя аргумента в snake case.

`toInt` - Конвертирует true/false в целочисленное представление 1/0

`toString` - Конвертирует true/fase в строковые "true"/"false". Иногда вендору надо получить строками и/или, получает параметры в url. Guzzle/Client использует http_build_query, который массив query парсит и булевые значения конвертирует в int.

`complex` - сложный параметр. Когда значение одного поля является ключ, другого - значение. Например когда хотят получить {type: email, value: {value}}. Соотв будет только одно поле email и его значение {value}. Но добавится параметр complex: true

`keyName` - имя для ключа. В примере выше это будет "type". Используется когда complex: true

`keyValue` - имя для значения. В примере выше это будет "value". Используется когда complex: true

`jsonParse` - парсит файл и вставляет в собранный JSON. Не знаю зачем надо.

`base64encode` - закодировать содержимое файла в base64. Не знаю зачем надо.

`urlParam` - параметр используется в ссылке. В ссылке никаких {var=value&foo=bar} не надо. Просто эта переменная (по name или vendorName) будет добавлена со своим значением к ссылке. Использовать с GET параметром не надо. Параметры автоматически будут переданы в url

`snakeCase` - true/false. Если стоит true, даже если у блока стоит false, переменная будет преобразована в camel_case

Первый пример (мультипарт)
POST https://your-domain-name.example.com/forum/1/category/2/newPost?insertPostSafeAndWhatEver=1&draft=true
И ожидает получить title, content, attachment

```
{
  "name": "createNewPost",
  "description": "Create new post",
  "method": "POST",
  "type": "multipart", //default json
  "url": "https://{domain}.example.com/forum/{forumId}/category/{category_id}/newPost",
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
      "vendorName": "forumId" // always use vendorName or snake style of "name". Repace forumId, not forum_id
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
      "vendorName": "insertPostSafeAndWhatEver", //if not set, param name will be insert_safe (snake case)
      "toInt": true, //default false
      "urlParam": true // default false
    },
    {
      "name": "draft",
      "type": "Boolean",
      "info": "",
      "required": true,
      "urlParam": true
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
  "method": "POST",
  "url": "https://{domain}.example.com/forum/{forumId}/category/{categoryId}/newPost",
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
      "vendorName": "forumId"
    },
    {
      "name": "categoryId",
      "type": "String",
      "info": "",
      "required": true,
      "vendorName": "categoryId"
    },
    {
      "name": "insertSafe",
      "type": "Boolean",
      "info": "Insert post safe and what ever bla-bla-bla",
      "required": false,
      "vendorName": "insertPostSafeAndWhatEver",
      "toInt": true,
      "urlParam": true
    },
    {
      "name": "draft",
      "type": "Boolean",
      "info": "",
      "required": true,
      "urlParam": true
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
      "base64encode": true
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
  "method": "POST",
  "url": "https://{domain}.example.com/forum/{forum_id}/category/{category_id}/newPost",
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
      "wrapName": "post"
    },
    {
      "name": "content",
      "type": "String",
      "info": "",
      "required": "",
      "wrapName": "post",
    },
    {
      "name": "file",
      "type": "File",
      "info": "",
      "required": "",
      "wrapName": "post",
      "vendorName": "attachment",
      "base64encode": true
    },
    {
      "name": "userName",
      "type": "String",
      "info": "",
      "required": "",
      "vendorName": "name",
      "wrapName": "post.user"
    },
    {
      "name": "email",
      "type": "String",
      "info": "User contact email",
      "required": "",
      "wrapName": "post.user.contacts",
      "complex": true,
      "keyName": "typeContact",
      "keyValue": "valueContact"
    },
    {
      "name": "twitter",
      "type": "String",
      "info": "User contact twitter",
      "required": "",
      "wrapName": "post.user.contacts",
      "complex": true,
      "keyName": "typeContact",
      "keyValue": "valueContact"
    },
    {
      "name": "votingQuestion",
      "type": "String",
      "info": "",
      "required": "",
      "vendorName": "question",
      "wrapName": "post.voting"
    },
    {
      "name": "answers",
      "type": "Array",
      "info": "If send String comma separated it make array???",
      "required": "",
      "wrapName": "post.voting"
    },
    {
      "name": "other",
      "type": "File",
      "info": "Data in JSON format unknown structure or cannot be translate into metadata. Example: Unknown elements"
      "required": "",
      "jsonParse": true
      "wrapName": "post"
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
