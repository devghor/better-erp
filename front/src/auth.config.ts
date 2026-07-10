import type { NextAuthConfig } from 'next-auth';

export default {
  pages: {
    signIn: '/auth/sign-in'
  },
  providers: [],
  callbacks: {
    authorized({ auth, request: { nextUrl } }) {
      const isLoggedIn = !!auth?.user;
      const isProtectedRoute = nextUrl.pathname.startsWith('/dashboard');

      if (isProtectedRoute) return isLoggedIn;
      return true;
    }
  }
} satisfies NextAuthConfig;
