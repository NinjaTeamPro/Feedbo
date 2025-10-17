import { useParams } from 'react-router-dom';
import { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';

export default function Comment() {
  const { id, postSlug } = useParams<{ id: string; postSlug: string }>();
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // TODO: Fetch comment/post data
    // For now, just log the params
    console.log('Board ID:', id);
    console.log('Post Slug:', postSlug);

    // Simulate loading
    setTimeout(() => {
      setLoading(false);
    }, 500);
  }, [id, postSlug]);

  if (loading) {
    return (
      <div className="flex items-center justify-center p-8">
        <div className="text-center">
          <div className="animate-spin text-4xl">⏳</div>
          <p className="mt-4 text-sm text-gray-600">
            {__('Loading comment...', 'feedbo')}
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6">
      <h2 className="mb-4 text-2xl font-bold text-[#1a2a37]">
        {__('Comment View', 'feedbo')}
      </h2>
      <div className="rounded-lg bg-white p-6 shadow-sm">
        <p className="text-gray-600">
          {__('Board:', 'feedbo')} <strong>{id}</strong>
        </p>
        <p className="text-gray-600">
          {__('Post:', 'feedbo')} <strong>{postSlug}</strong>
        </p>
        <div className="mt-4">
          <p className="text-sm text-gray-500">
            {__('This is a placeholder for the Comment component. Implement the full comment functionality here.', 'feedbo')}
          </p>
        </div>
      </div>
    </div>
  );
}

