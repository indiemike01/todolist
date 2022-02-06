The application provides a RESTful API and aims to expose a `TO DO List` CRUD. No framework has been used in the implementation.

All data is saved locally in a JSON file.

Below you can find illustrated all the available endpoints of the application.

`GET todolist/index.php/tasks`

## Example response

      [
        {
            "title": "De facut tema",
            "notes": "De scris frumos",
            "date": "02-05-2023",
            "priority": "",
            "id": 3
        },
        {
            "title": "De facut tema",
            "notes": "De scris frumos",
            "date": "02-05-2023",
            "priority": "false",
            "id": 4
        }]

`POST todolist/index.php/tasks`

## Parameters

| Name     | Required | Type   | Description                                         |
| -------- | -------- | ------ | --------------------------------------------------- |
| title    | True     | String | The name of the task                                |
| notes    | False    | String | Notes                                               |
| date     | True     | String | The deadline of the task                            |
| priority | False    | String | Whether is required or not. False is set as default |

## Example request

| Key      | Value            |
| -------- | ---------------- |
| notes    | De notat chestii |
| date     | 02-05-2022       |
| title    | De citit         |
| priority | true             |

| notes

## Example response

      {
        "data": {
            "created": true,
            "object": {
                "title": "De citit",
                "notes": "De notat chestii",
                "date": "02-05-2022",
                "priority": "true",
                "id": 30
            }
        }

}

## Bad request

| Key   | Value          |
| ----- | -------------- |
| id    | 100            |
| title | De facut curat |

## Example response

       {
            "data": {
                "created": false,
                "date": "required"
            }
        }

`PUT todolist/index.php/tasks`

## Parameters

| Name     | Required | Type    | Description                                         |
| -------- | -------- | ------- | --------------------------------------------------- |
| title    | False    | String  | The name of the task                                |
| notes    | False    | String  | Notes                                               |
| date     | False    | String  | The deadline of the task                            |
| priority | False    | String  | Whether is required or not. False is set as default |
| id       | True     | Integer | The id of the task                                  |

## Example request

| Key   | Value              |
| ----- | ------------------ |
| title | De terminat cartea |
| id    | 30                 |

## Example response

      {
            "title": "De terminat cartea",
            "id": "30"
        }

}

## Bad request

| Key   | Value          |
| ----- | -------------- |
| id    | 100            |
| title | De facut curat |

## Example response

       {
            "data": {
                "deleted": false,
                "message": "id invalid"
            }
        }

`DELETE todolist/index.php/tasks`

## Parameters

| Name | Required | Type    | Description        |
| ---- | -------- | ------- | ------------------ |
| id   | True     | Integer | The id of the task |

## Example request

| Key | Value |
| --- | ----- |
| id  | 30    |

## Example response

      {
        "data": {
            "deleted": true
        }
    }

}

## Bad request

| Key | Value |
| --- | ----- |
| id  | 100   |

## Example response

       {
            "data": {
                "deleted": false,
                "message": "id invalid"
            }
        }
