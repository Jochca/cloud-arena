# Copilot Instructions

## Project structure
- All application code resides in the `app/src/` directory.
- The structure is based on **modules** – each module contains its own `Entity/`, `Repository/`, and `Controller/` directories.
- **Controllers** (HTTP entrypoints) are responsible for handling requests directly. Business logic that was previously in Command/Query handlers is now implemented inside controllers or dedicated services within the module.
- **Repository** classes contain direct database access implementations (Doctrine), without interfaces or adapters.

Example module:
```
app/src/Task/
  Entity/Task.php
  Repository/TaskRepository.php
  Controller/TaskController.php
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

## AI-Driven Development workflow
- Every prompt describing a new feature should be stored under `/prompts` as a `.yaml` file.
- Prompt file structure:
  ```yaml
  name: Prompt name
  description: Short description of the goal (e.g., "Add PIN + JWT login support")
  date: YYYY-MM-DD
  content: |
    Full prompt content here
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
  