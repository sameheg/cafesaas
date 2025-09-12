# Marketplace Module

Provides supplier storefronts, listings and bid workflow.

## Bid State
```mermaid
stateDiagram-v2
    [*] --> Open
    Open --> Bidded: bid()
    Bidded --> Awarded: award()
    Bidded --> Expired: timeout()
    Awarded --> Fulfilled: fulfill()
```

## Events
- Emits `marketplace.bid.awarded@v1`
- Consumes `procurement.rfq.created@v1`

## API
- `POST /v1/marketplace/bids`
- `GET /v1/marketplace/stores/{supplier_id}`
