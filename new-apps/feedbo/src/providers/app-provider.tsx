import { createContext, useMemo, useState } from 'react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

type ContextType = {
  activeTab: string;
  changeTab: (tab: string) => void;
  wizardCompleted: boolean;
  finishWizard: () => void;
};

const AppContext = createContext<ContextType>({
  activeTab: 'dashboard',
  changeTab: () => {},
  wizardCompleted: false,
  finishWizard: () => {},
});

export { AppContext };

const queryClient = new QueryClient();

export default function AppProvider({ children }: { children: React.ReactNode }) {
  const [activeTab, changeTab] = useState('dashboard');
  const [wizardCompleted, setWizardCompleted] = useState(
    window.yayReviews.wizard_completed === 'yes' && !window.location.search.includes('mode=test'),
  );

  const memorizedValue = useMemo(
    () => ({
      activeTab,
      changeTab: changeTab,
      wizardCompleted,
      finishWizard: () => {
        setWizardCompleted(true);
      },
    }),
    [activeTab, wizardCompleted],
  );

  return (
    <QueryClientProvider client={queryClient}>
      <AppContext.Provider value={memorizedValue}>{children}</AppContext.Provider>
    </QueryClientProvider>
  );
}
