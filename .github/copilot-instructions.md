# Copilot Instructions

You are an expert PHP/Symfony and React developer working on a project that uses AI-assisted development. Follow these guidelines to ensure consistency and quality in the codebase.

## Code Quality Standards

**Before implementing any feature or making changes:**
1. **Always run PHPStan** to ensure type safety: `docker compose exec php phpstan analyse --memory-limit=1G`
2. **Always run CS-Fix** to maintain code style: `docker compose exec php composer csfix`
3. **Use the lint command** for quick checks: `docker compose exec php composer lint`

**Code quality requirements:**
- All code must pass PHPStan level 8 (strictest static analysis)
- All code must follow PSR-12 and Symfony coding standards
- Use proper type annotations for all arrays, collections, and return types
- Add PHPDoc annotations where PHPStan requires them (e.g., `@return Task[]` for array returns)
- All repository classes must specify generic types (e.g., `@extends ServiceEntityRepository<Task>`)
- All Doctrine Collection properties must specify types (e.g., `@var Collection<int, Player>`)

## Code Organization Standards

**Strict code organization requirements:**
- **NO COMMENTS** - Delete all comments from classes in Auth, Controller, Player, Session, Task contexts
- **NO COMPLEX ARRAY RESPONSES** - Never return complex arrays from methods/controllers. Create DTO classes in the appropriate context's DTO/ directory instead
- **NO ERROR ARRAY RESPONSES** - Never return errors like `$this->json(['error' => 'Message'], 404)`. Always throw specific exceptions instead
- **ONE CLASS PER FILE** - Never create multiple classes in one file. Each DTO must be in its own separate file
- **ONE EXCEPTION PER FILE** - Never create multiple exceptions in one file. Each exception must be in its own separate file

**DTO and Exception organization:**
- DTOs belong in `{Context}/DTO/` directory (e.g., `App\Controller\DTO\`, `App\Session\DTO\`)
- Exceptions belong in `{Context}/Exception/` directory (e.g., `App\Task\Exception\`, `App\Player\Exception\`)
- Each class must have its own file with the same name as the class

## Type Safety Requirements
- **Always add PHPDoc annotations** for array return types: `@return Task[]`
- **Use generic type annotations** for repository classes: `@extends ServiceEntityRepository<Task>`
- **Specify Collection types** in entities: `@var Collection<int, Player>`
- **Add proper type checks** in controllers using `instanceof` before accessing object properties
- **Handle null values** properly with null checks or null coalescing operators

## Project structure
- All application code resides in the `app/src/` directory.
- The structure is based on **modules** – each module contains its own `Entity/`, `Repository/`, and `Controller/` directories.
- **Controllers** (HTTP entrypoints) are responsible for handling requests directly. Business logic that was previously in Command/Query handlers is now implemented inside controllers or dedicated services within the module.
- **Repository** classes contain direct database access implementations (Doctrine).
- **Services, repositories, factories, managers, validators, etc.** must always have corresponding **interfaces**.
    - Interfaces are placed next to implementations in the same module.
    - All dependencies are injected via **interfaces**, not implementations.
    - If an existing service/repository is injected directly as a class (implementation), it must be refactored to follow this rule.

Example module:
```
app/src/Task/
  Entity/Task.php
  Repository/TaskRepositoryInterface.php
  Repository/DoctrineTaskRepository.php
  Controller/TaskController.php
  Service/TaskServiceInterface.php
  Service/TaskService.php
```

## Coding guidelines
- Use PHP 8.4+ and Symfony 6.4+.
- Each new feature should be implemented inside the appropriate module (e.g., `Task`, `Player`, `Competition`).
- Take full advantage of PHP 8.4 features, such as:
    - **Class property promotion** in constructors.
    - **Real getters/setters** instead of ad-hoc functions.
    - Attributes for Doctrine entity mapping.
    - Typed properties everywhere.
- Example of getters/setters in PHP 8.4:
  ```php
  class User
  {
      public string $email {
         get => $this->email;
         set(string $value) => $this->email = strtolower($value);
      }
  }
  ```
- Doctrine entities should be placed under `Entity/` with mapping defined using attributes.
- Tests are stored in the `tests/` directory, mirroring the structure of `app/src/`.
- When working with enumerations (enums), always reference the enum cases directly instead of using hardcoded strings. For example:
  ```php
  $status = TaskStatus::Pending->value;
  ```
  This ensures consistency and prevents errors due to typos or mismatched strings.

## AI-Driven Development workflow
- Every prompt describing a new feature should be stored under `/prompts` as a `.yaml` file. File name should match the `task` field inside the file.
- Prompt file structure:
  ```yaml
  name: Prompt name
  task: CAR-***
  description: Short description of the goal (e.g., "Add PIN + JWT login support")
  date: YYYY-MM-DD
  content: |
    ONLY RAW prompt text here, no markdown formatting
  ```
- Prompts serve as documentation of design decisions and as input material for code generation.

## Commits
- At the end of the AI-generated output, always provide a suggested commit message in the **Conventional Commits** format, e.g.:
  ```
  feat(task): add TaskController with basic CRUD endpoints
  ```

## Execution inside containers
- Every Symfony console command must be executed inside the PHP container, e.g.:
  ```bash
  docker compose exec php php bin/console c:c
  ```

## Payloads and Validation
- For any controller action that receives request data (e.g., POST/PUT/PATCH), always create a dedicated **Payload** object (e.g., `UpdateTaskStatusPayload`) in the appropriate module (usually under `Payload/`).
- Use Symfony's `#[MapRequestPayload]` attribute in controller actions to map and validate incoming request data to the Payload object.
- **All validation logic must be defined inside the Payload** using Symfony's `Assert` constraints. Do not perform manual validation or JSON decoding in controllers.
- Controllers should only orchestrate the flow, relying on the Payload for validation and type safety.
- Example Payload:
  ```php
  class UpdateTaskStatusPayload
  {
      #[Assert\NotBlank(message: 'Status is required.')]
      #[Assert\Choice(callback: [TaskStatus::class, 'cases'], message: 'Invalid status value.')]
      public string $status;
  }
  ```
- Example controller usage:
  ```php
  #[Route('/{uuid}/update', methods: ['POST'])]
  public function update(string $uuid, #[MapRequestPayload] UpdateTaskStatusPayload $payload): Response
  {
      // ...controller logic using $payload...
  }
  ```
