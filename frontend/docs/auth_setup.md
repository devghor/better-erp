# Auth Setup Guide

This guide covers authentication in this starter template, powered by **Auth.js (NextAuth v5)**
with a **Credentials provider**.

## How It Works

- `src/auth.config.ts` — edge-safe config (no providers, just the `authorized` callback that
  protects `/dashboard(.*)`). Used by `src/proxy.ts` so the Credentials provider (and its
  `bcryptjs` dependency) never gets bundled into the Edge runtime.
- `src/auth.ts` — the full config: adds the Credentials provider, JWT session strategy, and the
  `jwt`/`session` callbacks that attach `id`/`role` to the session. Exports `auth`, `signIn`,
  `signOut`, and `handlers`.
- `src/app/api/auth/[...nextauth]/route.ts` — wires `handlers` up as the Auth.js API route.
- `src/constants/mock-api-auth.ts` — an in-memory user store (bcrypt-hashed passwords), matching
  this project's `mock-api-*.ts` convention. **Resets on every server restart.**

## Demo Login

```
email: demo@example.com
password: demo12345
```

Sign-up creates a new in-memory user and signs them in immediately — it does not persist across
restarts.

## Adding a Real Backend

`src/constants/mock-api-auth.ts` is the only file you need to change:

1. Replace `findByEmail`/`verifyPassword`/`create` with calls to your database/ORM (Prisma,
   Drizzle, Supabase, etc.).
2. If you want persistent sessions instead of JWT, add an Auth.js
   [database adapter](https://authjs.dev/getting-started/adapters) in `src/auth.ts` and switch
   `session.strategy` to `'database'`.
3. Everything else (middleware, forms, sign-in/sign-up actions) keeps working unchanged.

## Role-Based Navigation

Navigation items in `src/config/nav-config.ts` can restrict visibility with `access: { role: '...' }`,
checked client-side in `src/hooks/use-nav.ts` against `useSession().data.user.role`. This is UX
only — enforce real authorization server-side (route handlers, server actions, or the
`authorized` callback in `src/auth.config.ts`).
