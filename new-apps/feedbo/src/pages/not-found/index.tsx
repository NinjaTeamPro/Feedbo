import { ArrowLeft } from '@phosphor-icons/react';
import { Button } from '@/components/ui/button';
import { __ } from '@wordpress/i18n';
import { useNavigate } from 'react-router-dom';
import { useEffect } from 'react';

export default function NotFoundBoard() {
  const navigate = useNavigate();

  useEffect(() => {
    // Set document title
    if (window.feedbo?.siteName) {
      document.title = window.feedbo.siteName + ' | Page Not Found';
    }
  }, []);

  const handleGoHome = () => {
    navigate('/');
  };

  return (
    <div className="flex min-h-screen w-full flex-col items-center justify-center bg-[#f8fafb] px-4">
      <div className="mx-auto max-w-md text-center">
        {/* 404 Icon */}
        <div className="mb-6">
          <h2 className="text-9xl font-bold text-[#1890ff]">404</h2>
        </div>

        {/* Title */}
        <h1 className="mb-4 text-3xl font-bold text-[#1a2a37]">
          {__('Board Not Found', 'feedbo')}
        </h1>

        {/* Description */}
        <p className="mb-8 text-base leading-relaxed text-gray-600">
          {__(
            "The board you're looking for doesn't exist or has been removed. Please check the URL or go back to the home page.",
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

