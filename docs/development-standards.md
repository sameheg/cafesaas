# Development Standards

## Architecture & Packages
- Follow modular monolith architecture with the module registry.
- Use only Composer and NPM packages approved for the platform.

## Repository Structure & Patterns
- Keep code in the monorepo layout with per-module directories.
- Implement multi-tenant awareness via `tenant_id` in all domain models.
- Encapsulate business logic in Service classes and data access in Repositories.

## Security
- Enforce authentication and authorization on all requests.
- Log and audit security-sensitive actions with immutable records.
- Apply rate limiting, input validation, and secret management best practices.

## Testing & Monitoring
- Provide unit and feature tests for new functionality using Pest.
- Run static analysis and style checks before commits.
- Emit metrics and traces for critical operations and failures.

## API & Frontend Design
- Expose RESTful endpoints under `/api/v1/{module}` with consistent naming.
- Validate and sanitize all client input.
- Build frontend components with Vue 3 and Pinia following reactive patterns.
