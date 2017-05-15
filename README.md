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
  
```

newVariableName rename to someStupidName and move deeper to topic: {}. Also true change to 1, false to 0.

wrapName
vendorName
toInt
method
url
type (binary?)
complex
keyName
keyValue
jsonParse
base64encode
urlParam
