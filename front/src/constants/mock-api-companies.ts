////////////////////////////////////////////////////////////////////////////////
// 🛑 In-memory demo company store — resets on server restart. Swap for a real
// database by editing src/features/companies/api/service.ts only.
//
// To use real logos, drop image files under public/logos/*.png and set
// `logoUrl: '/logos/your-file.png'` below — the switcher falls back to a
// generic icon whenever logoUrl is null.
////////////////////////////////////////////////////////////////////////////////

export const delay = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

export type Company = {
  id: string;
  name: string;
  role: string;
  logoUrl: string | null;
};

const companies: Company[] = [
  { id: '1', name: 'Acme Inc.', role: 'Owner', logoUrl: null },
  { id: '2', name: 'Globex Corporation', role: 'Admin', logoUrl: null },
  { id: '3', name: 'Initech', role: 'Member', logoUrl: null }
];

export const fakeCompanies = {
  async getAll(): Promise<Company[]> {
    await delay(300);
    return companies;
  }
};
