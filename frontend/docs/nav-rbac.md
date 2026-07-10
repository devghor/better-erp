# Simplified Navigation RBAC System

## Overview

This document explains the fully client-side RBAC (Role-Based Access Control) system for
navigation items.

**Key Insight**: Navigation visibility is UX only, not security. We can check everything
client-side using the Auth.js session!

## Architecture

### Core Files

1. **`src/hooks/use-nav.ts`** - Single hook that handles all filtering logic (fully client-side)
2. **`src/types/index.ts`** - Type definitions with `access` property

### Why Client-Side?

- **Navigation visibility is UX only** - Users can't bypass security by seeing/hiding nav items
- **`useSession()` provides the role client-side** - the JWT session carries `user.role`
- **Zero server calls** - Instant filtering, no loading states, no UI flashing
- **Better performance** - No network latency, no async complexity

**Note**: For actual security (API routes, server actions, page protection), always use
server-side checks (`auth()` in `src/auth.ts`, or the `authorized` callback in
`src/auth.config.ts`).

## Usage

### In `nav-config.ts`

```typescript
{
  title: 'Admin Panel',
  url: '/dashboard/admin',
  icon: 'settings',
  // Client-side check against session.user.role — instant!
  access: { role: 'admin' }
}
```

### In Components

```typescript
import { useFilteredNavItems } from '@/hooks/use-nav';

function MyComponent() {
  const filteredItems = useFilteredNavItems(navItems);
  // filteredItems is automatically filtered based on RBAC
}
```

## Scalability

### Adding New Items

Just add to `nav-config.ts`:

```typescript
{
  title: 'New Feature',
  url: '/dashboard/new',
  icon: 'star',
  access: { role: 'admin' }  // That's it!
}
```

### Adding a Role to a User

Roles are assigned wherever the mock/real user store creates users — see
`src/constants/mock-api-auth.ts` and the `jwt`/`session` callbacks in `src/auth.ts`, which copy
`user.role` onto the session.

## Best Practices

1. **Only gate items that truly need it** - most nav items should have no `access` property
2. **Never rely on this for real security** - always re-check server-side for protected routes/actions
3. **Keep role names consistent** with whatever your user store defines

## History

This system previously integrated with Clerk Organizations (`requireOrg`, `permission`, `plan`,
`feature` checks against `useOrganization()`/`membership`). Since the app now uses Auth.js
Credentials-only authentication (no multi-tenant organizations), those checks were removed —
`role` is the only supported access check today. See `docs/auth_setup.md` for the auth setup.
