# Copilot Instructions

## Project structure
- All application code resides in the `src/` directory.
- The structure is based on **modules** – each module contains its own `Entity/`, `Command/`, `Query/`, and `Repository/` directories.
- **Command/Handler** classes handle operations that modify the state.
- **Query/Handler** classes handle operations that read data.
- **Repository** classes contain direct database access implementations (Doctrine), without interfaces or adapters.
- **Controllers** (HTTP entrypoints) are located in `src/Controller/`. Their only responsibility is to dispatch the appropriate command or query.

Example module:
```
src/Task/
  Entity/Task.php
  Command/AddTaskCommand.php
  Command/AddTaskHandler.php
  Query/GetTasksForPlayerQuery.php
  Query/GetTasksForPlayerHandler.php
  Repository/TaskRepository.php
```

## Coding guidelines
- Use PHP 8.3+ and Symfony 6.4+.
- Each new feature should be implemented inside the appropriate module (e.g., `Task`, `Player`, `Competition`).
- Handlers should be named with the `Handler.php` suffix, commands and queries with `Command.php` / `Query.php`.
- Doctrine entities should be placed under `Entity/` with mapping defined in XML.
- Follow TDD/BDD mindset: write a test scenario first, then implement the code.
- Tests are stored in the `tests/` directory, mirroring the structure of `src/`.

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
  feat(task): add AddTaskCommand and AddTaskHandler
  
