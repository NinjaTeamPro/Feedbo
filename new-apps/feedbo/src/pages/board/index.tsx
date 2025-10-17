import { useState, useEffect } from 'react';
import { useParams, useNavigate, Outlet } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import { AccountView } from '@/components/Account';

export default function Board() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [user, setUser] = useState<any>(null);
  const [isAnonymous, setIsAnonymous] = useState(false);
  const [board, setBoard] = useState<any>(null);

  useEffect(() => {
    // Fetch board data
    const fetchBoardData = async () => {
      try {
        // TODO: Replace with actual API call
        // const response = await fetch(`/wp-json/feedbo/v1/boards/${id}`);
        // const data = await response.json();

        // Mock board data
        const mockBoard = {
          name: 'Product Feedback',
          description: 'Share your feedback and ideas',
          visibility: 'public',
          theme: {
            background: '#f8fafb',
            header: '#1890ff',
            accent: '#1890ff',
          },
        };

        setBoard(mockBoard);

        // Set document title
        if (window.feedbo?.siteName) {
          document.title = `${mockBoard.name} | ${window.feedbo.siteName}`;
        }

        setLoading(false);
      } catch (error) {
        console.error('Failed to fetch board:', error);
        navigate('/not-found');
      }
    };

    // Fetch user data
    const fetchUser = async () => {
      try {
        // TODO: Replace with actual API call
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

    fetchBoardData();
    fetchUser();
  }, [id, navigate]);

  const handleBoardChange = (boardUrl: string) => {
    navigate(`/board/${boardUrl}`);
  };

  const handleCreateBoard = () => {
    navigate('/new-board');
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

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-[#f8fafb]">
        <div className="text-center">
          <div className="animate-spin text-4xl">⏳</div>
          <p className="mt-4 text-sm text-gray-600">
            {__('Loading board...', 'feedbo')}
          </p>
        </div>
      </div>
    );
  }

  if (!board) {
    return null;
  }

  return (
    <div
      className="min-h-screen"
      style={{ backgroundColor: board.theme.background }}
    >
      {/* Header with logo */}
      <div className="flex items-center justify-between px-4 py-5">
        <div className="project-name">
          <a
            href="/"
            className="flex items-center text-2xl font-bold text-[#1a2a37] no-underline md:text-2xl"
          >
            <img
              src={window.feedbo?.pluginUrl + 'assets/img/feedbo-logo-square.png'}
              alt="Feedbo Logo"
              className="h-[42px] w-[42px] md:h-[50px] md:w-[50px]"
            />
            <span className="pl-2.5">{__('Feedbo', 'feedbo')}</span>
          </a>
        </div>
        {user && (
          <AccountView
            user={user}
            isAnonymous={isAnonymous}
            currentBoardUrl={id}
            onBoardChange={handleBoardChange}
            onCreateBoard={handleCreateBoard}
            onAccountUpdate={handleAccountUpdate}
            onNotificationUpdate={handleNotificationUpdate}
            onUnsubscribeAll={handleUnsubscribeAll}
          />
        )}
      </div>

      {/* Board Content */}
      <div className="mx-auto max-w-7xl px-4 py-8">
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-[#1a2a37]">{board.name}</h1>
          {board.description && (
            <p className="mt-2 text-gray-600">{board.description}</p>
          )}
        </div>

        {/* Board Main Content - Placeholder */}
        <div className="rounded-lg bg-white p-8 shadow-sm">
          <p className="text-gray-600">
            {__('Board ID:', 'feedbo')} <strong>{id}</strong>
          </p>
          <div className="mt-4">
            <p className="text-sm text-gray-500">
              {__(
                'This is a placeholder for the Board component. Implement Menu, ListPost, NavBar, and BoardManage components here.',
                'feedbo'
              )}
            </p>
          </div>
        </div>

        {/* Nested routes (like Comment) will render here */}
        <Outlet />
      </div>
    </div>
  );
}

