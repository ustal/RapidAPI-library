Если у вендора url генерируется с пользовательскими параметрами вида:
GET https://company.example.com/user/1

Блок будет выглядеть так
```
"name": "copyFile",
"description": "Get user by ID",
"method": "GET",
"url": "https://{company}.example.com/user/{userId}",
"args": [
  {
    "name": "company",
    "type": "String",
    "info": "The ID of the file.",
    "required": true
  },
  {
    "name": "userId",
    "type": "Number",
    "info": "User ID",
    "required": true
  }
```

Бывает что надо отправлять данные и в теле и по ссылке
POST https://company.example.com/forum/1?safe=true
И в теле передаем что-то вроде
```
{
  "topic": 
  {
    "title": "New title",
    "someStupidName": 1
  }
}
```
Блок будет выглядеть так:
```
"name": "createPost",
"description": "Create a new post",
"method": "POST",
"url": "https://{company}.example.com/forum/{categoryId}",
"args": [
  {
    "name": "company",
    "type": "String",
    "info": "The ID of the file.",
    "required": true
  },
  {
    "name": "categoryId",
    "type": "Number",
    "info": "User ID",
    "required": true
  },
  {
    "name": "title",
    "type": "String",
    "info": "Title of new topic",
    "required": true,
    "wrapName": "topic"
  },
  {
    "name": "newVariableName",
    "type": "Boolean",
    "info": "New name for vendor variable",
    "required": true,
    "vendorName": "someStupicName",
    "wrapName": "topic",
    "toInt": true
  },
  {
    "name": "safe",
    "type": "Boolean",
    "info": "Variable to url",
    "required": false,
    "urlParam": true
  }
  
```

newVariableName rename to someStupidName and move deeper to topic: {}. Also true change to 1, false to 0.

wrapName - category.topic.comments  превратиться в {"category": {"topic": {"comments: "value"}}}. Уровень вложенности
vendorName - name that vendor want to get
toInt - convert boolean to int (1 or 0)
method - vendor Method Endpoint
url - ссылка или часть ссылки на Endpoint
type (binary?)
complex - сложный параметр. Когда значение одного поля является ключ, другого - значение 
```
{
"name": "facebook",
"type": "String",
"info": "User's facebook",
"required": false,
"wrapName": "user.identities",
"complex": true,
"keyName": "typeName",
"keyValue": "valueName"
}
```
keyName - имя для ключа
keyValue - имя для значения
Получим
```
{
"user": 
  {
    "identities": {
      "typeName": "facebook",
      "valueName": "тут значение"
    }
  }
}
```
jsonParse
base64encode - закодировать содержимое файла в base64
urlParam - параметр используется в ссылке. ключ=значение&ключ2=значение2
