import { useState, useEffect } from 'react';
import { ArrowRight } from '@phosphor-icons/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { __ } from '@wordpress/i18n';
import { AccountView } from '@/components/Account';
import { toast } from 'sonner';
import TopNavigation from '@/components/top-nav';

export default function NewBoard() {
  const [user, setUser] = useState<any>(null);
  const [isAnonymous, setIsAnonymous] = useState(false);
  const [boardName, setBoardName] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    // Set document title
    if (window.feedbo?.siteName) {
      document.title = window.feedbo.siteName + ' | New Board';
    }

    // Set favicon
    const link = document.querySelector("link[rel*='icon']") as HTMLLinkElement || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'shortcut icon';
    link.href = window.feedbo?.pluginUrl + 'assets/img/feedbo-logo-square.png';
    if (!document.querySelector("link[rel*='icon']")) {
      document.getElementsByTagName('head')[0].appendChild(link);
    }

    // Fetch user data
    const fetchUser = async () => {
      try {
        // TODO: Replace with actual API call
        // const response = await fetch('/wp-json/feedbo/v1/user');
        // const userData = await response.json();

        // Mock user data for development
        const mockUser = {
          ID: 1,
          display_name: 'John Doe',
          user_email: 'john@example.com',
          user_avatar: window.feedbo?.pluginUrl + 'assets/img/default-avatar.png',
          list_board: [],
          notification: [
            {
              sendEmail: true,
              OwnPost: true,
              Comment: false
            }
          ]
        };

        setUser(mockUser);
        setIsAnonymous(false);
      } catch (error) {
        console.error('Failed to fetch user:', error);
        setIsAnonymous(true);
      }
    };

    fetchUser();
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    // Validate
    if (!boardName.trim()) {
      setError('This is a required field');
      return;
    }

    // Check if user is anonymous
    if (isAnonymous || !user) {
      window.location.href =
        window.feedbo?.siteUrl + '/wp-login.php?message=Please sign in to create your own board';
      return;
    }

    setIsLoading(true);
    setError('');

    try {
      // TODO: Replace with actual API call
      // const response = await fetch('/wp-json/feedbo/v1/boards', {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/json' },
      //   body: JSON.stringify({ name: boardName })
      // });
      // const data = await response.json();

      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 2000));

      toast.success('Board created successfully!');
      
      // Redirect to the new board
      // window.location.href = `/#/board/${data.board_URL}`;
      
      // For now, redirect to home
      window.location.href = '/';
    } catch (error) {
      console.error('Failed to create board:', error);
      toast.error('Failed to create board. Please try again.');
      setIsLoading(false);
    }
  };

  const handleBoardChange = (boardUrl: string) => {
    window.location.href = `/#/board/${boardUrl}`;
  };

  const handleCreateBoard = () => {
    // Already on create board page
  };

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
    <div className="min-h-screen w-full bg-[#27ae60]">
      <TopNavigation
        user={user}
        headerColor={'#ffffff'}
        isAnonymous={isAnonymous}
        onBoardChange={handleBoardChange}
        onCreateBoard={handleCreateBoard}
      />

      {/* Form Content */}
      <div className="mx-auto w-full max-w-[430px] px-4 pt-[15vh] md:pt-[30vh]">
        <div className="form-container">
          <h2 className="text-[21px] font-semibold leading-[1.48] text-white">
            {__('Create New Board', 'feedbo')}
          </h2>
          <p className="mb-6 text-sm leading-[1.71] text-white">
            {__('Enter your board name. You can change it later.', 'feedbo')}
          </p>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <Input
                type="text"
                placeholder={__('Enter name project', 'feedbo')}
                value={boardName}
                onChange={(e) => {
                  setBoardName(e.target.value);
                  if (error) setError('');
                }}
                className={`h-[50px] ${error ? 'border-red-500' : ''}`}
                disabled={isLoading}
              />
              {error && (
                <p className="mt-1 text-sm text-red-200">{error}</p>
              )}
            </div>

            <Button
              type="submit"
              className="h-[50px] w-full"
              disabled={isLoading}
            >
              {isLoading ? (
                __('Creating...', 'feedbo')
              ) : (
                <>
                  {__('Create', 'feedbo')}
                  <ArrowRight size={16} weight="bold" className="ml-2" />
                </>
              )}
            </Button>
          </form>
        </div>
      </div>
    </div>
  );
}

