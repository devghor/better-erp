'use client';
import { SessionProvider } from 'next-auth/react';
import type { Session } from 'next-auth';
import React from 'react';
import { ActiveThemeProvider } from '../themes/active-theme';
import QueryProvider from './query-provider';

export default function Providers({
  activeThemeValue,
  session,
  children
}: {
  activeThemeValue: string;
  session: Session | null;
  children: React.ReactNode;
}) {
  return (
    <>
      <ActiveThemeProvider initialTheme={activeThemeValue}>
        <SessionProvider session={session}>
          <QueryProvider>{children}</QueryProvider>
        </SessionProvider>
      </ActiveThemeProvider>
    </>
  );
}
