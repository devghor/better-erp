import { queryOptions } from '@tanstack/react-query';
import { getCompanies } from './service';
import type { Company } from './types';

export type { Company };

export const companyKeys = {
  all: ['companies'] as const,
  list: () => [...companyKeys.all, 'list'] as const
};

export const companiesQueryOptions = () =>
  queryOptions({
    queryKey: companyKeys.list(),
    queryFn: getCompanies
  });
