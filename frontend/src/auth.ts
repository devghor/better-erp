import NextAuth from 'next-auth';
import Credentials from 'next-auth/providers/credentials';
import * as z from 'zod';
import { fakeAuthUsers } from '@/constants/mock-api-auth';
import authConfig from './auth.config';

const credentialsSchema = z.object({
  email: z.string().email(),
  password: z.string().min(1)
});

export const { handlers, auth, signIn, signOut } = NextAuth({
  ...authConfig,
  session: { strategy: 'jwt' },
  trustHost: true,
  providers: [
    Credentials({
      credentials: {
        email: { label: 'Email', type: 'email' },
        password: { label: 'Password', type: 'password' }
      },
      async authorize(credentials) {
        const parsed = credentialsSchema.safeParse(credentials);
        if (!parsed.success) return null;

        const { email, password } = parsed.data;
        const user = fakeAuthUsers.findByEmail(email);
        if (!user) return null;

        const isValid = await fakeAuthUsers.verifyPassword(user, password);
        if (!isValid) return null;

        return { id: user.id, name: user.name, email: user.email, image: user.image, role: user.role };
      }
    })
  ],
  callbacks: {
    ...authConfig.callbacks,
    async jwt({ token, user }) {
      if (user) {
        token.id = user.id!;
        token.role = user.role;
      }
      return token;
    },
    async session({ session, token }) {
      session.user.id = token.id;
      session.user.role = token.role;
      return session;
    }
  }
});
