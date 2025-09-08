# Domain Events & Retry Flows

## Supported Events

All events follow the `domain.action` naming convention. The orchestrator consumes and publishes domain topics across modules. Initial topics include:

- `tenant.created`
- `tenant.activated`
- `user.invited`
- `module.toggled`
- `module.enabled`
- `module.disabled`
- `tenant.suspended`
- `order.created`
- `payment.failed`
- `invoice.issued`
- `invoice.paid`
- `candidate.accepted`
- `ticket.resolved`

These events are dispatched through the internal `EventBus` which fans out to module listeners.

## Retry & Dead-Letter Strategy

1. `EventBus::publish()` enqueues `DispatchDomainEvent`.
2. The job attempts to deliver the event and logs telemetry using `event.published`.
3. On failure, it retries with exponential backoff `(1s, 5s, 10s)` and logs `event.retry`.
4. After three failed attempts the payload is pushed to the Redis list `events:dlq` and an
   `event.dead_lettered` log entry is emitted for investigation.

This flow ensures reliable delivery and observability for all domain events.
