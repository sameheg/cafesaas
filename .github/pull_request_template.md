# Pull Request

Please ensure the following before requesting review:

- [ ] Events use `domain.action` naming (e.g. `order.created`).
- [ ] Permissions use `module.action` naming (e.g. `pos.refund`).
- [ ] API routes follow `/api/v1/{module}/...`.
- [ ] Database tables are singular `snake_case` and include a `tenant_id` column.

---

Describe your changes and provide any context necessary for reviewers.

