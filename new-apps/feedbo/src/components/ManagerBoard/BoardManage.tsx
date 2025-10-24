import { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import {
  LayoutDashboardIcon,
  UsersIcon,
  SettingsIcon,
  PaletteIcon,
  AlertTriangleIcon,
  CodeIcon,
} from 'lucide-react';
import { cn } from '@/lib/utils';

// Import completed components
import DashBoard from './DashBoard';
import Widget from './Widget';
import CustomColor from './CustomColor';
import DangerZone from './DangerZone';

// TODO: Import when completed
// import Setting from './Setting';
// import InviteTeam from './InviteTeam';
// import Appearance from './Appearance';

type MenuKey = 'dashboard' | 'inviteteam' | 'setting' | 'appearance' | 'dangerzone' | 'widget';

interface BoardManageProps {
  board: any;
  user: any;
  theme: {
    accent?: string;
  };
  activities?: any[];
  scriptUrl?: string;
  onUpdate?: (data: any) => void;
  onDelete?: () => void;
  onInvite?: (email: string) => void;
  onActivityClick?: (activity: any) => void;
}

export default function BoardManage({
  board,
  user,
  theme,
  activities = [],
  scriptUrl = '',
  onUpdate,
  onDelete,
  onInvite,
  onActivityClick,
}: BoardManageProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [currentMenu, setCurrentMenu] = useState<MenuKey>('dashboard');

  const checkThemeColor = () => {
    if (theme.accent === '#1890ff') return 'bg-blue-500';
    if (theme.accent === '#ff4d4f') return 'bg-red-500';
    return 'bg-blue-500';
  };

  const menuItems = [
    { key: 'dashboard' as MenuKey, icon: LayoutDashboardIcon, label: __('Dashboard', 'feedbo') },
    { key: 'inviteteam' as MenuKey, icon: UsersIcon, label: __('Invite Team', 'feedbo') },
    { key: 'setting' as MenuKey, icon: SettingsIcon, label: __('Settings', 'feedbo') },
    { key: 'appearance' as MenuKey, icon: PaletteIcon, label: __('Appearance', 'feedbo') },
    { key: 'dangerzone' as MenuKey, icon: AlertTriangleIcon, label: __('Danger zone', 'feedbo') },
    { key: 'widget' as MenuKey, icon: CodeIcon, label: __('Widget', 'feedbo') },
  ];

  const renderContent = () => {
    switch (currentMenu) {
      case 'dashboard':
        return (
          <DashBoard
            activities={activities}
            loading={false}
            onActivityClick={onActivityClick || (() => {})}
          />
        );
      
      case 'widget':
        return <Widget boardId={board.id} scriptUrl={scriptUrl} />;
      
      case 'dangerzone':
        return (
          <DangerZone
            boardName={board.name}
            isLoading={false}
            onDeleteBoard={onDelete || (() => {})}
          />
        );
      
      case 'inviteteam':
        return (
          <div className="p-8 text-center text-gray-500">
            <p>{__('InviteTeam component - TODO: Implement', 'feedbo')}</p>
          </div>
        );
      
      case 'setting':
        return (
          <div className="p-8 text-center text-gray-500">
            <p>{__('Setting component - TODO: Implement', 'feedbo')}</p>
          </div>
        );
      
      case 'appearance':
        return (
          <div className="p-8 text-center text-gray-500">
            <p>{__('Appearance component - TODO: Implement', 'feedbo')}</p>
          </div>
        );
      
      default:
        return null;
    }
  };

  return (
    <>
      {/* Fixed Manage Board Button */}
      <div className="fixed bottom-8 left-8 z-101">
        <Button
          onClick={() => setIsOpen(true)}
          className={cn(checkThemeColor(), 'shadow-lg hover:brightness-110')}
        >
          {__('Manage Board', 'feedbo')}
        </Button>
      </div>

      {/* Drawer/Sheet */}
      <Sheet open={isOpen} onOpenChange={setIsOpen}>
        <SheetContent
          side="left"
          className="w-full overflow-hidden bg-gray-100 p-0 sm:max-w-[900px]"
        >
          <div className="flex h-full">
            {/* Side Navigation */}
            <div className="flex w-48 flex-col bg-white">
              <div className="flex-1 py-4">
                {menuItems.map((item) => {
                  const Icon = item.icon;
                  const isActive = currentMenu === item.key;
                  
                  return (
                    <button
                      key={item.key}
                      onClick={() => setCurrentMenu(item.key)}
                      className={cn(
                        'flex w-full items-center gap-3 px-6 py-4 text-left text-sm transition-colors',
                        isActive
                          ? 'border-l-2 border-blue-500 bg-blue-50 font-medium text-blue-700'
                          : 'text-gray-700 hover:bg-gray-50'
                      )}
                    >
                      <Icon className="h-4 w-4" />
                      <span className="hidden sm:inline">{item.label}</span>
                    </button>
                  );
                })}
              </div>
            </div>

            {/* Content Area */}
            <div className="flex-1 overflow-auto p-8">
              {renderContent()}
            </div>
          </div>
        </SheetContent>
      </Sheet>
    </>
  );
}

