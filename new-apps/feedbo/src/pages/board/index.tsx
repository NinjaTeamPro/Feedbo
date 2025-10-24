import { useState, useEffect } from 'react';
import { useParams, useNavigate, Outlet } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import { BoardExample } from '@/components';
import TopNavigation from '@/components/top-nav';
import { BoardManage } from '@/components/ManagerBoard';

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
          logoImg: '', // You can add a logo URL here
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
      <TopNavigation
        user={user}
        isAnonymous={isAnonymous}
        currentBoardUrl={id}
        onBoardChange={handleBoardChange}
        onCreateBoard={handleCreateBoard}
      />

      {/* Board Content */}
      <div className="mx-auto max-w-7xl px-4 py-8">
        {/* Board Main Content - Placeholder */}
        <BoardExample />
        <BoardManage />
        {/* Nested routes (like Comment) will render here */}
        <Outlet />
      </div>
    </div>
  );
}


