# Architecture Overview

This project applies a Clean Architecture style where each bounded context is organised in four concentric layers:

- **Domain** – Entities, Value Objects, Domain Events and domain-specific exceptions that capture the core business rules.
- **Application** – Use cases expressed as Commands/Queries plus their handlers and DTOs.
- **Infrastructure** – Framework specific glue (Doctrine mappings, Symfony forms, transport adapters, etc.).
- **User Interface** – Delivery mechanisms (HTTP controllers, templating, Vue entry-points).

All modules live under `src/` and follow the same folder layout, which allows each context to evolve independently while sharing cross-cutting concerns implemented in `src/Common` (value objects, buses, Doctrine base types…).

## Cross-cutting Common layer

The `App\Common` namespace provides abstractions reused by every module:

- Base domain primitives such as `AggregateRoot`, `AggregateRootId`, specialised `StringValue`/`Uuid` types and the `DateTime` immutable wrapper.
- Command/Query buses with marker interfaces and handler contracts.
- Doctrine base types (`UuidType`, `StringType`, `TextType`, …) and, newly added, a serialisable `MoneyType` that converts the shared `Money` value object to JSON for persistence. 【F:src/Common/Domain/Entity/AggregateRoot.php†L8-L21】【F:src/Common/Domain/ValueObject/Money.php†L1-L94】【F:src/Common/Infrastructure/Doctrine/Type/MoneyType.php†L1-L45】

## Existing bounded contexts

- **Authentication** exposes user credential management and JWT integration.
- **User** handles profile information and pagination-ready search. Example files: `User\Domain\Entity\User`, `User\Application\UseCase\CreateUser`. 【F:src/User/Domain/Entity/User.php†L1-L78】【F:src/User/Application/UseCase/CreateUser/CreateUserCommandHandler.php†L1-L53】
- **Messaging** implements conversations with participants and messages, relying on Doctrine collections and domain events to keep aggregates cohesive. 【F:src/Messaging/Domain/Entity/Conversation.php†L1-L115】

## New Product bounded context

The Product module introduces catalogue management with the following building blocks:

- Domain entities and value objects under `src/Product/Domain`, notably `Product`, `ProductId`, `ProductName`, a rich `Stock` guard and domain exceptions such as `InsufficientStock`. 【F:src/Product/Domain/Entity/Product.php†L1-L94】【F:src/Product/Domain/ValueObject/Stock.php†L1-L45】【F:src/Product/Domain/Exception/InsufficientStock.php†L1-L11】
- Application layer exposing `CreateProductCommand`/handler and a `GetProductByIdQuery` to retrieve catalogue entries via immutable DTOs. 【F:src/Product/Application/UseCase/CreateProduct/CreateProductCommand.php†L1-L21】【F:src/Product/Application/UseCase/CreateProduct/CreateProductCommandHandler.php†L1-L35】【F:src/Product/Application/UseCase/GetProductById/GetProductByIdQueryHandler.php†L1-L26】
- Doctrine infrastructure registering dedicated DBAL types (`product_id`, `product_name`, `stock`, …) plus XML mappings for persistence. 【F:src/Product/Infrastructure/Doctrine/Type/ProductIdType.php†L1-L11】【F:src/Product/Infrastructure/Doctrine/Type/StockType.php†L1-L45】【F:src/Product/Infrastructure/Doctrine/Mapping/Product.orm.xml†L1-L20】

Products can be renamed, repriced or have their stock adjusted while preserving invariants. The repository abstraction (`ProductRepository`) supports persistence-agnostic use cases, with the Doctrine adapter providing concrete database access. 【F:src/Product/Domain/Repository/ProductRepository.php†L1-L29】【F:src/Product/Infrastructure/Doctrine/Repository/DoctrineProductRepository.php†L1-L52】

## New Order bounded context

The Order module models the “passage commande” workflow:

- Domain: `Order` aggregate coordinates `OrderItem` entities, `OrderStatus`, `Quantity` and the shared `Money` primitive to guarantee totals remain consistent. Empty orders are rejected via the `EmptyOrder` domain exception. 【F:src/Order/Domain/Entity/Order.php†L1-L92】【F:src/Order/Domain/Entity/OrderItem.php†L1-L73】【F:src/Order/Domain/Exception/EmptyOrder.php†L1-L11】
- Application: `PlaceOrderCommand` orchestrates stock reservation and order creation, with results returned through `OrderDTO`/`OrderItemDTO`. 【F:src/Order/Application/UseCase/PlaceOrder/PlaceOrderCommand.php†L1-L17】【F:src/Order/Application/UseCase/PlaceOrder/PlaceOrderCommandHandler.php†L1-L41】【F:src/Order/Application/DTO/OrderDTO.php†L1-L29】
- Infrastructure: Doctrine types (`order_id`, `order_status`, `quantity`, …) and XML mappings persist aggregates, while `DoctrineOrderRepository` implements the repository contract. 【F:src/Order/Infrastructure/Doctrine/Type/OrderIdType.php†L1-L11】【F:src/Order/Infrastructure/Doctrine/Type/QuantityType.php†L1-L45】【F:src/Order/Infrastructure/Doctrine/Mapping/Order.orm.xml†L1-L32】【F:src/Order/Infrastructure/Doctrine/Repository/DoctrineOrderRepository.php†L1-L37】

Order placement uses the Product repository to fetch and reserve stock before aggregating `OrderItem` snapshots. Money amounts are stored as integer cents to avoid floating point issues and carry their ISO currency code.

## Module interactions

- The Order use case depends on the Product repository abstraction to validate product existence and enforce stock constraints. This dependency is wired at the application layer, keeping domain models decoupled from persistence concerns.
- Shared primitives (UUIDs, Money, DateTime) travel between contexts as value objects, ensuring invariants remain enforced at module boundaries.

## Infrastructure wiring

`config/packages/doctrine.yaml` registers all custom Doctrine types and mapping directories, including the new Product and Order contexts. `config/services.yaml` autowires every namespace so Symfony can discover handlers, repositories and adapters automatically. 【F:config/packages/doctrine.yaml†L6-L56】【F:config/services.yaml†L31-L52】

## Execution flow examples

1. **Create product**: a `CreateProductCommand` is dispatched → handler builds domain value objects → `ProductRepository::add` persists the aggregate → a `ProductDTO` is returned to the caller.
2. **Place order**: a `PlaceOrderCommand` enters the Order module → handler loads each `Product`, calling `reserve()` to lower stock → an `Order` aggregate is instantiated with `OrderItem` snapshots → the Doctrine repository persists the order while updated product stock is flushed by the ORM → `OrderDTO` summarises the transaction.

## Extensibility notes

- Additional read models (e.g. product catalogue search) can be added by introducing new queries/handlers without touching domain code.
- Payment or shipment workflows can listen to `Order` domain events or extend the aggregate with new status transitions.
- The Money value object and Doctrine type are reusable for future pricing modules (promotions, taxes, etc.).
