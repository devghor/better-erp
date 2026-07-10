import { auth } from '@/auth';
import { UserAvatarProfile } from '@/components/user-avatar-profile';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

export default async function ProfileViewPage() {
  const session = await auth();
  const user = session?.user;

  return (
    <div className='flex w-full flex-col gap-4 p-4'>
      <Card>
        <CardHeader>
          <CardTitle>Profile</CardTitle>
        </CardHeader>
        <CardContent className='flex items-center gap-4'>
          <UserAvatarProfile className='h-16 w-16 rounded-full' user={user ?? null} />
          <div className='flex flex-col gap-1'>
            <span className='text-lg font-semibold'>{user?.name}</span>
            <span className='text-muted-foreground text-sm'>{user?.email}</span>
            {user?.role && (
              <Badge variant='secondary' className='mt-1 w-fit capitalize'>
                {user.role}
              </Badge>
            )}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
