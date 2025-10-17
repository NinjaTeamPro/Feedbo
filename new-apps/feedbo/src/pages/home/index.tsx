import { useState, useEffect } from 'react';
import { ArrowRight } from '@phosphor-icons/react';
import { Button } from '@/components/ui/button';
import { __ } from '@wordpress/i18n';
import TopNavigation from '@/components/top-nav';

export default function Home() {
  const [user, setUser] = useState<any>(null);
  const [isAnonymous, setIsAnonymous] = useState(false);

  useEffect(() => {
    // TODO: Replace with actual API call to fetch user data
    // For now, using mock data
    const fetchUser = async () => {
      try {
        // Example API call:
        // const response = await fetch('/wp-json/feedbo/v1/user');
        // const userData = await response.json();
        
        // Mock user data for development
        const mockUser = {
          ID: 1,
          display_name: 'John Doe',
          user_email: 'john@example.com',
          user_avatar: window.feedbo?.pluginUrl + 'assets/img/default-avatar.png',
          list_board: [
            {
              term_id: 1,
              name: 'Product Feedback',
              board_URL: 'product-feedback'
            },
            {
              term_id: 2,
              name: 'Feature Requests',
              board_URL: 'feature-requests'
            }
          ],
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

  const handleCreateBoard = () => {
    // TODO: Navigate to NewBoard page
    console.log('Navigate to New Board');
  };

  const handleBoardChange = (boardUrl: string) => {
    // TODO: Navigate to board
    console.log('Navigate to board:', boardUrl);
  };

  return (
    <div className="min-h-screen w-full bg-[#f8fafb]">
      {/* Header with logo and account */}
      <TopNavigation
        user={user}
        isAnonymous={isAnonymous}
        onBoardChange={handleBoardChange}
        onCreateBoard={handleCreateBoard}
      />
      {/* Main Content - Centered */}
      <div className="flex w-full flex-col items-center justify-center px-6 pt-[15vh] md:pt-[30vh]">
        {/* Title Section */}
        <div className="mb-10 w-full max-w-[535px] text-center">
          <h1 className="text-4xl! font-bold! leading-[1.29] text-[#1a2a37] md:text-4xl!">
            {__('Feedback manager software and roadmap tool', 'feedbo')}
          </h1>
        </div>

        {/* CTA Button Section */}
        <div className="flex justify-center">
          <Button
            type="button"
            size="lg"
            className="h-[50px] w-[230px] rounded-[5px]"
            onClick={handleCreateBoard}
          >
            {__('Create New Board', 'feedbo')}
            <ArrowRight weight="bold" size={16} />
          </Button>
        </div>
      </div>
    </div>
  );
}