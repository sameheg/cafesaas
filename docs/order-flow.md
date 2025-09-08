# Order to Payment to Notification Flow

This pipeline covers a typical customer order lifecycle:

1. **Order Created** – an `Order` record is stored with status `pending` and a corresponding `OrderCreated` event is raised.
2. **Payment Processed** – the `MarkOrderPaid` pipe creates a `Payment` entry, updates the order status to `paid` and emits `payment.processed` through the `PaymentProcessed` event.
3. **Customer Notified** – `SendConfirmation` dispatches `SendNotification` which ultimately fires `notification.sent`.

The `OrderPipeline` service wires these steps using Laravel's `Pipeline` allowing the flow to be executed synchronously or queued as needed.
