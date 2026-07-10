import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';

export function CompanySwitcherSkeleton() {
  return (
    <SidebarMenu>
      <SidebarMenuItem>
        <SidebarMenuButton size='lg' disabled className='animate-pulse'>
          <div className='bg-muted size-8 shrink-0 rounded-lg' />
          <div className='grid flex-1 gap-1'>
            <div className='bg-muted h-3 w-24 rounded' />
            <div className='bg-muted h-2.5 w-16 rounded' />
          </div>
        </SidebarMenuButton>
      </SidebarMenuItem>
    </SidebarMenu>
  );
}
