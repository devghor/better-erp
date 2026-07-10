'use server';

import * as z from 'zod';
import { fakeAuthUsers } from '@/constants/mock-api-auth';

const signUpSchema = z.object({
  name: z.string().min(2, { message: 'Name must be at least 2 characters' }),
  email: z.string().email({ message: 'Enter a valid email address' }),
  password: z.string().min(8, { message: 'Password must be at least 8 characters' })
});

export type SignUpResult = { success: true } | { success: false; error: string };

export async function signUpAction(input: z.infer<typeof signUpSchema>): Promise<SignUpResult> {
  const parsed = signUpSchema.safeParse(input);
  if (!parsed.success) {
    return { success: false, error: 'Invalid sign-up details' };
  }

  const { name, email, password } = parsed.data;

  if (fakeAuthUsers.findByEmail(email)) {
    return { success: false, error: 'An account with this email already exists' };
  }

  await fakeAuthUsers.create({ name, email, password });
  return { success: true };
}
