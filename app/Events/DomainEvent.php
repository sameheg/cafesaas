<?php

namespace App\Events;

enum DomainEvent: string
{
    case TENANT_CREATED = 'tenant.created';
    case TENANT_ACTIVATED = 'tenant.activated';
    case USER_INVITED = 'user.invited';
    case MODULE_TOGGLED = 'module.toggled';
    case MODULE_ENABLED = 'module.enabled';
    case MODULE_DISABLED = 'module.disabled';
    case TENANT_SUSPENDED = 'tenant.suspended';
    case ORDER_CREATED = 'order.created';
    case PAYMENT_FAILED = 'payment.failed';
    case INVOICE_ISSUED = 'invoice.issued';
    case INVOICE_PAID = 'invoice.paid';
    case AUTH_MFA_CHALLENGE = 'auth.mfa_challenge';
    case AUDIT_LOGGED = 'audit.logged';
    case PRICE_RULE_APPLIED = 'price.rule_applied';
    case WEBHOOK_RECEIVED = 'webhook.received';
    case PROVIDER_ERROR = 'provider.error';
    case SUBSCRIPTION_CREATED = 'subscription.created';
    case JOB_POSTED = 'job.posted';
    case CANDIDATE_ACCEPTED = 'candidate.accepted';
    case CV_ACCEPTED = 'cv.accepted';
    case TICKET_RESOLVED = 'ticket.resolved';
}
