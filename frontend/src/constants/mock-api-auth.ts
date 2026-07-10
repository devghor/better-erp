////////////////////////////////////////////////////////////////////////////////
// 🛑 In-memory demo user store — resets on server restart. Swap for a real
// database (Prisma/Drizzle/Supabase) by editing this file only. See docs/auth_setup.md
////////////////////////////////////////////////////////////////////////////////

import { compare, hash, hashSync } from 'bcryptjs';

export type AuthUser = {
  id: string;
  name: string;
  email: string;
  passwordHash: string;
  image?: string | null;
  role: string;
};

const users: AuthUser[] = [
  {
    id: '1',
    name: 'Demo User',
    email: 'demo@example.com',
    passwordHash: hashSync('demo12345', 10),
    image: null,
    role: 'admin'
  }
];

export const fakeAuthUsers = {
  findByEmail(email: string) {
    return users.find((user) => user.email.toLowerCase() === email.toLowerCase()) ?? null;
  },

  async verifyPassword(user: AuthUser, password: string) {
    return compare(password, user.passwordHash);
  },

  async create({ name, email, password }: { name: string; email: string; password: string }) {
    const newUser: AuthUser = {
      id: String(users.length + 1),
      name,
      email,
      passwordHash: await hash(password, 10),
      image: null,
      role: 'member'
    };
    users.push(newUser);
    return newUser;
  }
};
