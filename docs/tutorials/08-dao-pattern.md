# Data access object (DAO) pattern
The framework provides a data access object (DAO) pattern that allows you to easily access and manipulate data in a database with objects.

There are so-called "model objects" that represent the data that's being stored in the database, and for each table there is an own model object. They are located in the `📁 project/src/object/` directory.

There's also a data access object interface that defines the standard operations that can be performed on the model objects, such as creating, reading and updating entries. For every model object, there is an own belonging data access object that's located in the `📁 project/src/dao/` directory.

To prevent you from having to write the same code over and over again, there are classes called `GenericObject` (model object) and `GenericObjectDAO` (data access object interface) that every custom object should extend from. The `GenericObject` class already implements the table columns
- `id` (integer) - The unique identifier of the object
- `created` (datetime) - The date and time when the object was created
- `updated` (datetime) - The date and time when the object was last updated

and the `GenericObjectDAO` the standard operations
- `GenericObjectDAO::save(GenericObject $object)` to create or update an object's database entry
- `GenericObjectDAO::delete(GenericObject $object)` to delete an object's database entry
- `GenericObjectDAO::getObject(array $filter, string $orderBy, bool $orderAsc, int $limit, int $offset)` to get a single object from the database
- `GenericObjectDAO::getObjects(array $filter, string $orderBy, bool $orderAsc, int $limit, int $offset)` to get multiple objects from the database

## Setting up a new object
To set up a new object, you need to create a new class within the `📁 project/src/object/` directory. The class name has to be the same as the table name in the database.
```php
class MyObject extends GenericObject {
    public string $myAttribute;
 
    public function getMyAttribute(): string {
        return $this->myAttribute;
    }
    
    public function setMyAttribute(string $myAttribute) : void {
        $this->myAttribute = $myAttribute;
    }
}
```
Next, you'll have to create a new class for the DAO in the `📁 project/src/dao/` directory.

> [!IMPORTANT]
> The class name has to be the same as the object class with appended `DAO`.

```php
class MyObjectDAO extends GenericObjectDAO {
    // Basic DAO methods already implemented by GenericObjectDAO
}
```
If you need to use functions with custom queries or other non-default methods for this specific object, you can implement them in the DAO class.

The above example would allow us to access and manipulate the database table called `MyObject` with the following structure:

| `id`    | `myAttribute` | `created` | `updated` |
|---------|---------------|-----------|-----------|
| integer | varchar       | datetime  | datetime  |

The database tables need to be set up manually. Use the following template to do that:
```sql
CREATE TABLE IF NOT EXISTS `MyObject` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `myAttribute` VARCHAR(255) NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
> [!TIP]
> There is a file `📄 project/src/schema/tables.sql` where you can document the SQL statements that are required for your project. This allows you or other persons to easily recreate tables in case you want to set up another instance of your project in the future.

## Saving an object to the database
To create a new database entry for an object, you have to create an instance of the object, assign the attributes and save it with the DAO's `save` method:
```php
// Create an instance of the object
$myObject = new MyObject();
$myObject->setMyAttribute("Hello World!");

// Save the object to the database
MyObject::dao()->save($myObject);
```
The `save` method will automatically set the `id` attribute of the object if a new database entry was inserted.

To update an existing database entry, the existing object needs to be modified and saved again:
```php
// Create an instance of the object
$myObject = new MyObject();
$myObject->setMyAttribute("Hello World!");

// Save the object to the database
MyObject::dao()->save($myObject);

// Modify the object
$myObject->setMyAttribute("Hello World! This is an update.");
$myObject->setUpdated(new DateTime());

// Save the object to the database
MyObject::dao()->save($myObject);
```
Instead of creating a new object, you can also [load an existing object from the database](#loading-objects-from-the-database) and then modify it.

## Loading objects from the database
To load objects from the database, you can use the DAO's `getObject` or `getObjects` methods:
```php
// Get the object with the ID 1
$myObject = MyObject::dao()->getObject(["id" => 1]);

// Get all objects
$myObjects = MyObject::dao()->getObjects();
```
For both methods you can set the following parameters:
- `filters`: An associative array that contains requirements for the objects that should be returned with the column name as key and the value that the column should have as value
- `orderBy`: A column name that the returned objects should be ordered by
- `orderAsc`: Whether the returned objects should be ordered ascending or descending
- `limit`: The maximum amount of objects that should be returned (-1 for no limit)
- `offset`: The offset from which the objects should be returned

You can also write more detailed queries by using complex filters.
They allow you to use other operators than the equality check.
Use it as follows:
```php
$objects = MyObject::dao()->getObjects([
    [
        "field" => "myAttribute",
        "filterType" => DAOFilterType,
        "filterValue" => FILTER_VALUE
    ],
    [
        "field" => "myAttribute",
        "filterType" => DAOFilterType,
        "filterValue" => FILTER_VALUE
    ]
]);
```
`DAOFilterType` has to be replaced with one case of the `DAOFilterType` enumeration and `FILTER_VALUE` stands for the value after which you want to filter.
There are the following filter types:
- `EQUALS`: Returns entries where the given `field` equals to the given `filterValue`
- `NOT_EQUALS`: Returns entries where the given `field` does not equal to the given `filterValue`
- `GREATER_THAN`: Returns entries where the given `field` is greater than the given `filterValue`
- `LESS_THAN`: Returns entries where the given `field` is less than the given `filterValue`
- `GREATER_THAN_EQUALS`: Returns entries where the given `field` is greater than or equals the given `filterValue`
- `LESS_THAN_EQUALS`: Returns entries where the given `field` is less than or equals the given `filterValue`
- `LIKE`: Returns entries where the given `field` is like the given `filterValue`
- `IN`: Returns entries where the given `field` lies within the given `filterValue` array
- `NOT_IN`: Returns entries where the given `field` doesn't lie within the given `filterValue` array

All given filters are logically combined with an `AND`.
You can also combine complex with non-complex filters.

### Deleting objects from the database
To delete an object from the database, call the DAO's `delete` method:
```php
// Delete the object with the ID 1
$myObject = MyObject::dao()->getObject(["id" => 1]);
MyObject::dao()->delete($myObject);
```
