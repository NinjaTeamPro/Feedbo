import { ArrowLeft } from '@phosphor-icons/react';
import { Button } from '@/components/ui/button';
import { __ } from '@wordpress/i18n';
import { useNavigate } from 'react-router-dom';
import { useEffect } from 'react';

export default function PrivateBoard() {
  const navigate = useNavigate();

  useEffect(() => {
    // Set document title
    if (window.feedbo?.siteName) {
      document.title = window.feedbo.siteName + ' | Private Board';
    }
  }, []);

  const handleGoHome = () => {
    navigate('/');
  };

  return (
    <div className="flex min-h-screen w-full flex-col items-center justify-center bg-[#f8fafb] px-4">
      <div className="mx-auto max-w-md text-center">
        {/* Lock Icon */}
        <div className="mb-6 flex justify-center">
          <div className="flex h-24 w-24 items-center justify-center rounded-full bg-gray-200">
            <svg
              className="h-12 w-12 text-gray-600"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
              />
            </svg>
          </div>
        </div>

        {/* Title */}
        <h1 className="mb-4 text-3xl font-bold text-[#1a2a37]">
          {__('Private Board', 'feedbo')}
        </h1>

        {/* Description */}
        <p className="mb-8 text-base leading-relaxed text-gray-600">
          {__(
            "This board is private. You don't have permission to access it. Please contact the board owner if you think this is a mistake.",
            'feedbo'
          )}
        </p>

        {/* Back Button */}
        <Button
          onClick={handleGoHome}
          className="h-12 px-6"
          size="lg"
        >
          <ArrowLeft size={20} weight="bold" className="mr-2" />
          {__('Go Back Home', 'feedbo')}
        </Button>
      </div>
    </div>
  );
}


