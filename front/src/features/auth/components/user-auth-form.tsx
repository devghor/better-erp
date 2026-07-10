'use client';

import { useAppForm, useFormFields } from '@/components/ui/tanstack-form';
import { signIn } from 'next-auth/react';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import * as z from 'zod';

const formSchema = z.object({
  email: z.string().email({ message: 'Enter a valid email address' }),
  password: z.string().min(1, { message: 'Password is required' })
});

type FormValues = z.infer<typeof formSchema>;

export default function UserAuthForm() {
  const router = useRouter();

  const form = useAppForm({
    defaultValues: {
      email: 'demo@example.com',
      password: 'demo12345'
    } as FormValues,
    validators: {
      onSubmit: formSchema
    },
    onSubmit: async ({ value }) => {
      const result = await signIn('credentials', {
        email: value.email,
        password: value.password,
        redirect: false
      });

      if (result?.error) {
        toast.error('Invalid email or password');
        return;
      }

      toast.success('Signed in successfully!');
      router.push('/dashboard/overview');
      router.refresh();
    }
  });

  const { FormTextField } = useFormFields<FormValues>();

  return (
    <form.AppForm>
      <form.Form className='w-full space-y-2'>
        <FormTextField name='email' label='Email' type='email' placeholder='Enter your email...' />
        <FormTextField name='password' label='Password' type='password' placeholder='Enter your password...' />
        <form.SubmitButton className='mt-2 w-full'>Sign In</form.SubmitButton>
      </form.Form>
    </form.AppForm>
  );
}
