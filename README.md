# Service Chat - Common

[![GitHub release](https://img.shields.io/github/release/flash-global/chat-common.svg?style=for-the-badge)](README.md)

## Table of contents
- [Entities](#entities)
- [Contribution](#contribution)

## Entities

### Message
| Properties    | Type              | Required | Default value |
|---------------|-------------------|----------|---------------|
| id          | `int`      | No       |               |
| createdAt          | `datetime`      | No       | Now()              |
| body     | `string`        | Yes       | 2         |
| username     | `string`          | Yes       |               |
| displayUsername     | `string`          | No       |               |
| room     | `Room`          | Yes       |               |    
| contexts     | `ArrayCollection`          | No       |               |

### MessageContext
| Properties    | Type              | Required | Default value |
|---------------|-------------------|----------|---------------|
| id          | `int`      | No       |               |
| message          | `Message`      | Yes       |               |
| key     | `string`        | Yes       |          |
| value     | `string`          | Yes       |               |


### Room
| Properties    | Type              | Required | Default value |
|---------------|-------------------|----------|---------------|
| id          | `int`      | No       |               |
| createdAt          | `datetime`      | No       | Now()              |
| key     | `string`        | Yes       |          |
| status     | `int`          | Yes       | 1              |
| name     | `string`          | Yes       |               |
| messages     | `ArrayCollection`          | No       |               |    
| contexts     | `ArrayCollection`          | No       |               |

### RoomContext
| Properties    | Type              | Required | Default value |
|---------------|-------------------|----------|---------------|
| id          | `int`      | No       |               |
| room          | `Room`      | Yes       |               |
| key     | `string`        | Yes       |          |
| value     | `string`          | Yes       |               |


## Contribution
As FEI Service, designed and made by OpCoding. The contribution workflow will involve both technical teams. Feel free to contribute, to improve features and apply patches, but keep in mind to carefully deal with pull request. Merging must be the product of complete discussions between Flash and OpCoding teams :) 



