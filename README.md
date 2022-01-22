### Usage

```php
test('it ignores excessive substraction', function() {
	testable(Inventory::class)
		->setCount(1)
		->substract(10)
		->getCount()->assertEquals(0)
		->add(2)
		->getCount()->assertEquals(2)
});
```
### How it works:

* Provide `testable` with an instance or a resolvable classname.
* Make preparatory calls as needed.
* Reach for the property or method you want to assert against.
* State the assertion. It will target the most recent operation.
* After an assertion, it will return to the state before the assertion target, making it possible to chain more assertions.