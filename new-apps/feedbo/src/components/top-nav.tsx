import { __ } from '@wordpress/i18n';
import { AccountView } from '@/components/Account';

interface TopNavigationProps {
  user?: any;
  isAnonymous?: boolean;
  currentBoardUrl?: string;
  onBoardChange?: (boardUrl: string) => void;
  onCreateBoard?: () => void;
}

export default function TopNavigation({
  user,
  isAnonymous = false,
  currentBoardUrl,
  onBoardChange,
  onCreateBoard,
}: TopNavigationProps) {
  const handleAccountUpdate = (data: { name: string; email: string }) => {
    console.log('Update account:', data);
    // TODO: Add API call to update user account
  };

  const handleNotificationUpdate = (settings: any) => {
    console.log('Update notifications:', settings);
    // TODO: Add API call to update notification settings
  };

  const handleUnsubscribeAll = (userId: number) => {
    console.log('Unsubscribe user:', userId);
    // TODO: Add API call to unsubscribe from all posts
  };

  return (
    <div className="flex w-full items-center justify-between px-4 py-5 tracking-wide">
      <div className="project-name">
        <a
          href="/"
          className="focus:outline-none! no-underline! flex w-fit items-center text-2xl font-bold text-[#1a2a37] md:text-2xl"
        >
          <img
            src={window.feedbo?.pluginUrl + 'assets/img/nav-logo.png'}
            alt={__('Nav Logo', 'feedbo')}
            className="h-[42px] w-[42px] md:h-[50px] md:w-[50px]"
          />
          <span className="pl-2.5">{__('Feedbo', 'feedbo')}</span>
        </a>
      </div>

      {/* Account View */}
      {user && (
        <AccountView
          user={user}
          isAnonymous={isAnonymous}
          currentBoardUrl={currentBoardUrl}
          onBoardChange={onBoardChange}
          onCreateBoard={onCreateBoard}
          onAccountUpdate={handleAccountUpdate}
          onNotificationUpdate={handleNotificationUpdate}
          onUnsubscribeAll={handleUnsubscribeAll}
        />
      )}
    </div>
  );
}
