# Code review summary

## Strengths

- **Consistent modular layout** – Every bounded context respects the Domain/Application/Infrastructure layering, which improves readability and enforces dependency direction. The new Product and Order modules reuse this structure and introduce rich value objects for money, stock and quantities.
- **Use of value objects** – Existing contexts already rely on immutable primitives (e.g. `UserId`, `Email`). The new `Money`, `Stock`, `Quantity` and `OrderStatus` value objects continue this trend and keep invariants centralised.
- **Doctrine integration** – Custom DBAL types with XML mappings keep persistence concerns isolated from the domain model. The added Money/Quantity/Stock types follow the same approach and register their mappings in `config/packages/doctrine.yaml`. 【F:config/packages/doctrine.yaml†L6-L56】【F:src/Common/Infrastructure/Doctrine/Type/MoneyType.php†L1-L45】

## Issues discovered

1. **Broken `IntegerValue` abstraction** – `src/Common/Domain/ValueObject/IntegerValue.php` stores an `IntegerValue` instance instead of a primitive integer, making `fromInt()` unusable and breaking concrete subclasses. This should be refactored to wrap an `int` and expose helpers similar to `StringValue`. 【F:src/Common/Domain/ValueObject/IntegerValue.php†L1-L29】
2. **Domain exceptions leaking generic runtime errors** – For example `User::remove()` throws a `RuntimeException` when the user is already removed. Dedicated domain exceptions (e.g. `UserAlreadyRemoved`) would provide clearer intent and better error handling. 【F:src/User/Domain/Entity/User.php†L49-L67】
3. **Missing persistence flush strategy** – Application handlers (existing and new) rely on Doctrine’s unit of work to flush changes, but no transactional middleware is configured. Consider adding an explicit command bus middleware that wraps handlers in a transaction and flushes the entity manager to avoid stale data when running outside HTTP requests.

## Recommendations

- Fix the `IntegerValue` implementation and review any classes extending it to ensure they behave correctly (no such subclasses currently exist, but future work should avoid inheriting from the buggy base until repaired).
- Introduce domain-specific exceptions in the User aggregate to replace the generic runtime error and document expected failure modes in handlers.
- Add automated tests for the new Product and Order use cases (unit tests around stock reservation and total computation would help guard against regressions).
- Consider extracting application service interfaces for pricing, taxes or payments so that the Order module can integrate with external systems without polluting the domain model.
