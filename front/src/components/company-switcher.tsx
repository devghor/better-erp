'use client';

import { Icons } from '@/components/icons';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuShortcut,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { companiesQueryOptions } from '@/features/companies/api/queries';
import { useSuspenseQuery } from '@tanstack/react-query';
import Image from 'next/image';
import { useState } from 'react';

export function CompanySwitcher() {
  const { isMobile, state } = useSidebar();
  const { data: companies } = useSuspenseQuery(companiesQueryOptions());
  const [activeCompanyId, setActiveCompanyId] = useState(companies[0]?.id);

  const activeCompany = companies.find((company) => company.id === activeCompanyId) ?? companies[0];

  if (!activeCompany) {
    return null;
  }

  return (
    <SidebarMenu>
      <SidebarMenuItem>
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <SidebarMenuButton
              size='lg'
              className='data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground'
            >
              <div className='bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 shrink-0 items-center justify-center overflow-hidden rounded-lg'>
                {activeCompany.logoUrl ? (
                  <Image
                    src={activeCompany.logoUrl}
                    alt={activeCompany.name}
                    width={32}
                    height={32}
                    className='size-full object-cover'
                  />
                ) : (
                  <Icons.galleryVerticalEnd className='size-4' />
                )}
              </div>
              <div
                className={`grid flex-1 text-left text-sm leading-tight transition-all duration-200 ease-in-out ${
                  state === 'collapsed'
                    ? 'invisible max-w-0 overflow-hidden opacity-0'
                    : 'visible max-w-full opacity-100'
                }`}
              >
                <span className='truncate font-medium'>{activeCompany.name}</span>
                <span className='text-muted-foreground truncate text-xs'>{activeCompany.role}</span>
              </div>
              <Icons.chevronsUpDown
                className={`ml-auto transition-all duration-200 ease-in-out ${
                  state === 'collapsed' ? 'invisible max-w-0 opacity-0' : 'visible max-w-full opacity-100'
                }`}
              />
            </SidebarMenuButton>
          </DropdownMenuTrigger>
          <DropdownMenuContent
            className='w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg'
            align='start'
            side={isMobile ? 'bottom' : 'right'}
            sideOffset={4}
          >
            <DropdownMenuLabel className='text-muted-foreground text-xs'>Companies</DropdownMenuLabel>
            {companies.map((company, index) => {
              const isActive = company.id === activeCompany.id;
              return (
                <DropdownMenuItem
                  key={company.id}
                  onClick={() => setActiveCompanyId(company.id)}
                  className='gap-2 p-2'
                >
                  <div className='flex size-6 items-center justify-center overflow-hidden rounded-md border'>
                    {company.logoUrl ? (
                      <Image
                        src={company.logoUrl}
                        alt={company.name}
                        width={24}
                        height={24}
                        className='size-full object-cover'
                      />
                    ) : (
                      <Icons.galleryVerticalEnd className='size-3.5 shrink-0' />
                    )}
                  </div>
                  {company.name}
                  {isActive && <Icons.check className='ml-auto size-4' />}
                  {!isActive && <DropdownMenuShortcut>⌘{index + 1}</DropdownMenuShortcut>}
                </DropdownMenuItem>
              );
            })}
          </DropdownMenuContent>
        </DropdownMenu>
      </SidebarMenuItem>
    </SidebarMenu>
  );
}
