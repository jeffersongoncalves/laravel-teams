# Changelog

All notable changes to `laravel-teams` will be documented in this file.

## 2.0.0 - 2026-08-01

### Security

Drop Laravel 11 support (EOL, unpatched). Require `laravel/framework: ^12.61.1|^13.12.0` to fix:

- GHSA-crmm-hgp2-wgrp — Temporary Signed URL Path Confusion
- GHSA-5vg9-5847-vvmq — CRLF injection in default email validation rule

**Breaking change**: Laravel 11 is no longer supported.

## 1.0.0 - 2026-06-24

Initial release. Framework-agnostic Teams core: Eloquent models (Team, Membership, TeamInvitation), HasTeams trait, TeamPolicy, observers, configurable models/tables, and migrations.
