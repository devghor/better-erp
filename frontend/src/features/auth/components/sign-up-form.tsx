'use client';

import { useAppForm, useFormFields } from '@/components/ui/tanstack-form';
import { signIn } from 'next-auth/react';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import * as z from 'zod';
import { signUpAction } from '../actions/sign-up';

const formSchema = z
  .object({
    name: z.string().min(2, { message: 'Name must be at least 2 characters' }),
    email: z.string().email({ message: 'Enter a valid email address' }),
    password: z.string().min(8, { message: 'Password must be at least 8 characters' }),
    confirmPassword: z.string().min(1, { message: 'Confirm your password' })
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: 'Passwords do not match',
    path: ['confirmPassword']
  });

type FormValues = z.infer<typeof formSchema>;

export default function SignUpForm() {
  const router = useRouter();

  const form = useAppForm({
    defaultValues: {
      name: '',
      email: '',
      password: '',
      confirmPassword: ''
    } as FormValues,
    validators: {
      onSubmit: formSchema
    },
    onSubmit: async ({ value }) => {
      const result = await signUpAction({
        name: value.name,
        email: value.email,
        password: value.password
      });

      if (!result.success) {
        toast.error(result.error);
        return;
      }

      const signInResult = await signIn('credentials', {
        email: value.email,
        password: value.password,
        redirect: false
      });

      if (signInResult?.error) {
        toast.error('Account created — please sign in.');
        router.push('/auth/sign-in');
        return;
      }

      toast.success('Account created successfully!');
      router.push('/dashboard/overview');
      router.refresh();
    }
  });

  const { FormTextField } = useFormFields<FormValues>();

  return (
    <form.AppForm>
      <form.Form className='w-full space-y-2'>
        <FormTextField name='name' label='Name' placeholder='Enter your name...' />
        <FormTextField name='email' label='Email' type='email' placeholder='Enter your email...' />
        <FormTextField
          name='password'
          label='Password'
          type='password'
          placeholder='Create a password...'
        />
        <FormTextField
          name='confirmPassword'
          label='Confirm Password'
          type='password'
          placeholder='Confirm your password...'
        />
        <form.SubmitButton className='mt-2 w-full'>Create Account</form.SubmitButton>
      </form.Form>
    </form.AppForm>
  );
}
