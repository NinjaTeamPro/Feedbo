import { useState } from 'react';
import { Plus, Check, CaretRight, ArrowLeft } from '@phosphor-icons/react';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { ScrollArea } from '@/components/ui/scroll-area';
import Account from './Account';
import Notification from './Notification';
import { __ } from '@wordpress/i18n';
import { useNavigate } from 'react-router-dom';
// Type declarations for WordPress plugin data
declare global {
  interface Window {
    bigNinjaVoteWpdata?: {
      pluginUrl: string;
      siteName: string;
      siteUrl: string;
      axiosUrl: string;
      logoutUrl: string;
    };
  }
}

interface Board {
  term_id: number;
  name: string;
  board_URL: string;
}

interface User {
  ID: number;
  display_name: string;
  user_email: string;
  user_avatar: string;
  list_board: Board[];
  notification: any[];
}

interface AccountViewProps {
  user: User;
  isAnonymous?: boolean;
  currentBoardUrl?: string;
  onSignIn?: () => void;
  onBoardChange?: (boardUrl: string) => void;
  onCreateBoard?: () => void;
  onAccountUpdate?: (data: { name: string; email: string }) => void;
  onNotificationUpdate?: (settings: any) => void;
  onUnsubscribeAll?: (userId: number) => void;
}

export default function AccountView({
  user,
  isAnonymous = false,
  currentBoardUrl,
  onSignIn,
  onBoardChange,
  onCreateBoard,
  onAccountUpdate,
  onNotificationUpdate,
  onUnsubscribeAll,
}: AccountViewProps) {
  const [isSettingsOpen, setIsSettingsOpen] = useState(false);
  const [isEditProfileOpen, setIsEditProfileOpen] = useState(false);
  const [isNotificationOpen, setIsNotificationOpen] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const navigate = useNavigate();
  const logoutUrl = window.bigNinjaVoteWpdata?.logoutUrl || '/wp-login.php?action=logout';

  const handleSignIn = () => {
    onSignIn?.();
    window.location.href = window.bigNinjaVoteWpdata?.siteUrl + '/wp-login.php';
  };

  const handleBoardClick = (boardUrl: string) => {
    navigate(`/board/${boardUrl}`);
    setDropdownOpen(false);
    onBoardChange?.(boardUrl);
  };

  const handleCreateBoard = () => {
    setDropdownOpen(false);
    onCreateBoard?.();
  };

  const handleEditProfileClick = () => {
    setIsSettingsOpen(false);
    setTimeout(() => setIsEditProfileOpen(true), 100);
  };

  const handleNotificationClick = () => {
    setIsSettingsOpen(false);
    setTimeout(() => setIsNotificationOpen(true), 100);
  };

  const handleEditProfileClose = () => {
    setIsEditProfileOpen(false);
    setTimeout(() => setIsSettingsOpen(true), 100);
  };

  const handleNotificationClose = () => {
    setIsNotificationOpen(false);
    setTimeout(() => setIsSettingsOpen(true), 100);
  };

  const isActiveBoard = (boardUrl: string) => {
    return currentBoardUrl === boardUrl;
  };

  // If user is anonymous, show sign in button
  if (isAnonymous) {
    return (
      <div className="ml-3">
        <Button onClick={handleSignIn}>{__('Sign in', 'feedbo')}</Button>
      </div>
    );
  }

  return (
    <>
      <DropdownMenu open={dropdownOpen} onOpenChange={setDropdownOpen}>
        <DropdownMenuTrigger asChild>
          <div className="ml-3 flex cursor-pointer items-center outline-none">
            <div className="relative h-10 w-10 overflow-hidden rounded-full">
              <img
                src={user.user_avatar}
                alt={user.display_name}
                className="h-10 w-10 rounded-full border border-white object-cover"
              />
            </div>
          </div>
        </DropdownMenuTrigger>

        <DropdownMenuContent className="w-[415px] max-w-[calc(100vw-2rem)] p-0" align="end">
          {/* Account Info */}
          <div
            className="cursor-pointer bg-[#f8fafb] p-4 hover:bg-gray-100"
            onClick={() => {
              setDropdownOpen(false);
              setIsSettingsOpen(true);
            }}
          >
            <div className="flex items-center gap-4">
              <img
                src={user.user_avatar}
                alt={user.display_name}
                className="h-10 w-10 rounded-full border border-white object-cover"
              />
              <div className="flex-1">
                <div className="text-sm text-[#74788d]">{user.display_name}</div>
                <div className="mt-2 text-base font-semibold text-[#1a2a37]">
                  {__('Account settings', 'feedbo')}
                </div>
              </div>
            </div>
          </div>

          <DropdownMenuSeparator className="my-0" />
          {/* {window.location.pathname !== '/new-board' && ( */}
          <div className="px-4 py-3">
            <button
              onClick={handleCreateBoard}
              className="flex items-center gap-2 text-sm font-medium text-[#1890ff] hover:underline"
            >
              <Plus size={14} weight="bold" />
              <span>{__('Create New Board', 'feedbo')}</span>
            </button>
          </div>
          {/* )} */}
          {/* Board List */}
          {user.list_board && user.list_board.length > 0 && (
            <ScrollArea className="max-h-[300px]">
              <div className="px-4 pb-2">
                {user.list_board.map((board) => (
                  <button
                    key={board.term_id}
                    onClick={() => handleBoardClick(board.board_URL)}
                    className="flex w-full items-center gap-2 py-2 text-left text-sm text-[#1a2a37] hover:text-blue-600"
                  >
                    <div className="h-2 w-2 rounded-full border border-current" />
                    <span className="flex-1">{board.name}</span>
                    {isActiveBoard(board.board_URL) && (
                      <Check size={14} weight="bold" />
                    )}
                  </button>
                ))}
              </div>
            </ScrollArea>
          )}
        </DropdownMenuContent>
      </DropdownMenu>

      {/* Account Settings Modal */}
      <Dialog open={isSettingsOpen} onOpenChange={setIsSettingsOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle>{__('Account settings', 'feedbo')}</DialogTitle>
          </DialogHeader>
          <div className="space-y-2">
            <div
              className="flex h-[50px] cursor-pointer items-center justify-between bg-white px-6 hover:bg-[#f8fafb] hover:font-medium"
              onClick={handleEditProfileClick}
            >
              <span className="text-sm text-[#1a2a37] hover:text-[#1890ff]">
                {__('Edit Profile', 'feedbo')}
              </span>
              <CaretRight size={14} />
            </div>
            <div
              className="flex h-[50px] cursor-pointer items-center justify-between bg-white px-6 hover:bg-[#f8fafb] hover:font-medium"
              onClick={handleNotificationClick}
            >
              <span className="text-sm text-[#1a2a37] hover:text-[#1890ff]">
                {__('Notification Settings', 'feedbo')}
              </span>
              <CaretRight size={14} />
            </div>
            <a href={logoutUrl}>
              <div className="flex h-[50px] cursor-pointer items-center bg-white px-6 hover:bg-[#f8fafb] hover:font-medium">
                <span className="text-sm text-[#1a2a37] hover:text-[#1890ff]">
                  {__('Logout', 'feedbo')}
                </span>
              </div>
            </a>
          </div>
        </DialogContent>
      </Dialog>

      {/* Edit Profile Modal */}
      <Dialog open={isEditProfileOpen} onOpenChange={setIsEditProfileOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle className="text-center">
              <button
                onClick={handleEditProfileClose}
                className="absolute left-6 top-6 inline-flex cursor-pointer items-center"
              >
                <ArrowLeft size={20} />
              </button>
              {__('Edit Profile', 'feedbo')}
            </DialogTitle>
          </DialogHeader>
          <Account
            user={user}
            onSave={(data) => {
              onAccountUpdate?.(data);
              setIsEditProfileOpen(false);
            }}
            onCancel={handleEditProfileClose}
          />
        </DialogContent>
      </Dialog>

      {/* Notification Settings Modal */}
      <Dialog open={isNotificationOpen} onOpenChange={setIsNotificationOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle className="text-center">
              <button
                onClick={handleNotificationClose}
                className="absolute left-6 top-6 inline-flex cursor-pointer items-center"
              >
                <ArrowLeft size={20} />
              </button>
              {__('Notification Settings', 'feedbo')}
            </DialogTitle>
          </DialogHeader>
          <Notification
            user={user}
            onSave={(settings) => {
              onNotificationUpdate?.(settings);
              setIsNotificationOpen(false);
            }}
            onCancel={handleNotificationClose}
            onUnsubscribeAll={onUnsubscribeAll}
          />
        </DialogContent>
      </Dialog>
    </>
  );
}

