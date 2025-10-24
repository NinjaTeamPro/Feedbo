import { useState, useEffect, useRef, useCallback } from 'react';
import { __ } from '@wordpress/i18n';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Skeleton } from '@/components/ui/skeleton';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { CheckIcon, ChevronUpIcon, ChevronDownIcon } from 'lucide-react';

interface Post {
  post_id: number;
  post_title: string;
  post_content: string;
  post_status: string;
  post_slug: string;
  vote_ids: string;
  vote_length: number;
  down_vote_ids?: string;
  down_vote_length?: number;
}

interface ListPostProps {
  posts: Post[];
  loading: boolean;
  activeTab: string;
  theme: {
    title?: string;
    text?: string;
    accent?: string;
  };
  statusColors: Record<string, string>;
  roadmapData?: any;
  user: any;
  isAnonymous: boolean;
  allowAnonymous: boolean;
  allowDownvote: boolean;
  voteAnonymous: number[];
  downVoteAnonymous: number[];
  onVote: (post: Post) => void;
  onDownVote: (post: Post) => void;
  onPostClick: (post: Post) => void;
  onLoadMore: () => void;
  hasMore: boolean;
  CommentComponent?: React.ComponentType<any>;
}

export default function ListPost({
  posts,
  loading,
  activeTab,
  theme,
  statusColors,
  roadmapData,
  user,
  isAnonymous,
  allowAnonymous,
  allowDownvote,
  voteAnonymous,
  downVoteAnonymous,
  onVote,
  onDownVote,
  onPostClick,
  onLoadMore,
  hasMore,
  CommentComponent,
}: ListPostProps) {
  const [modalVisible, setModalVisible] = useState(false);
  const [selectedPost, setSelectedPost] = useState<Post | null>(null);
  const observerTarget = useRef(null);

  // Infinite scroll observer
  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && hasMore && !loading) {
          onLoadMore();
        }
      },
      { threshold: 0.1 }
    );

    if (observerTarget.current) {
      observer.observe(observerTarget.current);
    }

    return () => {
      if (observerTarget.current) {
        observer.unobserve(observerTarget.current);
      }
    };
  }, [hasMore, loading, onLoadMore]);

  const checkVote = useCallback(
    (post: Post) => {
      if (isAnonymous) {
        return voteAnonymous.includes(post.post_id);
      } else {
        if (!post.vote_ids) return false;
        const voteIds = post.vote_ids.split(' , ');
        return user?.ID ? voteIds.includes(user.ID.toString()) : false;
      }
    },
    [isAnonymous, voteAnonymous, user]
  );

  const checkDownVote = useCallback(
    (post: Post) => {
      if (isAnonymous) {
        return downVoteAnonymous.includes(post.post_id);
      } else {
        if (!post.down_vote_ids) return false;
        const downVoteIds = post.down_vote_ids.split(' , ');
        return user?.ID ? downVoteIds.includes(user.ID.toString()) : false;
      }
    },
    [isAnonymous, downVoteAnonymous, user]
  );

  const getVoteCount = (post: Post) => {
    if (!post.vote_ids || post.vote_ids === '') return 0;
    return post.vote_ids.split(' , ').length;
  };

  const getDownVoteCount = (post: Post) => {
    if (!post.down_vote_ids || post.down_vote_ids === '') return 0;
    return post.down_vote_ids.split(' , ').length;
  };

  const handlePostClick = (post: Post) => {
    setSelectedPost(post);
    setModalVisible(true);
    onPostClick(post);
  };

  const handleCloseModal = () => {
    setModalVisible(false);
    setSelectedPost(null);
  };

  // Render Roadmap View
  if (activeTab === 'roadmap') {
    if (loading) {
      return (
        <div className="p-8">
          <Skeleton className="h-48 w-full" />
        </div>
      );
    }

    const groupedPosts = roadmapData || {};
    const hasRoadmapPosts = Object.keys(groupedPosts).length > 0;

    if (!hasRoadmapPosts) {
      return (
        <div className="roadmap mx-auto max-w-[370px] px-4 py-[15vh] text-center">
          <h3 className="mb-2 text-base font-medium leading-6 text-[#494949]">
            {__('The roadmap will appear here', 'feedbo')}
          </h3>
          <p className="text-sm text-[rgba(73,73,73,0.714)]">
            {__('when a post is assigned to a status', 'feedbo')}
          </p>
        </div>
      );
    }

    return (
      <div className="roadmap-wrap p-8">
        <div className="relative">
          {Object.entries(groupedPosts).map(([status, statusPosts]: [string, any]) => (
            <div key={status} className="mb-6">
              <div className="flex items-start gap-4">
                <div
                  className="mt-1 h-3 w-3 rounded-full"
                  style={{ backgroundColor: statusColors[status] || '#ccc' }}
                />
                <div className="flex-1">
                  <h3 className="mb-4 text-base font-medium text-[#494949]">{status}</h3>
                  {statusPosts.map((post: Post) => (
                    <div
                      key={post.post_id}
                      className="post mb-6 cursor-pointer"
                      onClick={() => handlePostClick(post)}
                    >
                      <div className="flex items-center gap-2">
                        <div
                          className="h-2 w-2 rounded-full"
                          style={{ backgroundColor: statusColors[status] || '#ccc' }}
                        />
                        <h4 className="overflow-hidden text-ellipsis whitespace-nowrap text-sm text-black">
                          {post.post_title}
                        </h4>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  // Render List View (Most Voted / Newest)
  return (
    <div className="feature-container relative px-8 py-4">
      <div className="feature-wrap -my-6">
        {loading && posts.length === 0 ? (
          <Skeleton className="h-32 w-full" />
        ) : (
          <>
            <div className="space-y-4">
              {posts.map((post) => (
                <div
                  key={post.post_id}
                  className="flex items-start gap-4 border-b border-gray-200 py-4"
                >
                  <div className="flex-1 pr-4">
                    <a
                      onClick={() => handlePostClick(post)}
                      className="cursor-pointer"
                      style={{ color: theme.title || '#000' }}
                    >
                      <h2
                        className="mb-1 inline-block align-middle text-xl font-medium"
                        style={{ color: theme.title || '#000' }}
                      >
                        {post.post_title}
                      </h2>
                      {post.post_status && post.post_status !== 'Unassigned' && (
                        <Badge
                          className="ml-2 align-middle"
                          style={{
                            backgroundColor: statusColors[post.post_status] || '#ccc',
                            borderColor: statusColors[post.post_status] || '#ccc',
                          }}
                        >
                          {post.post_status}
                        </Badge>
                      )}
                    </a>
                    <p
                      className="post-content-text mt-1 text-sm"
                      style={{ color: theme.text || '#666' }}
                    >
                      {post.post_content}
                    </p>
                  </div>

                  {/* Vote Buttons */}
                  <div className="flex flex-col gap-1">
                    {!allowDownvote ? (
                      <Button
                        variant="outline"
                        size="sm"
                        className="button-vote h-8 px-2"
                        style={
                          checkVote(post)
                            ? { borderLeft: `solid 4px ${theme.accent || '#1890ff'}` }
                            : {}
                        }
                        onClick={(e) => {
                          e.stopPropagation();
                          onVote(post);
                        }}
                      >
                        <span className="vote-icon mr-2 align-[0.125em] text-xs">
                          {checkVote(post) ? (
                            <CheckIcon className="h-3 w-3" />
                          ) : (
                            <ChevronUpIcon className="h-3 w-3" />
                          )}
                        </span>
                        <span className="vote-count">{getVoteCount(post)}</span>
                      </Button>
                    ) : (
                      <>
                        <Button
                          variant="outline"
                          size="sm"
                          className="button-vote h-8 px-2"
                          style={
                            checkVote(post)
                              ? { borderLeft: `solid 4px ${theme.accent || '#1890ff'}` }
                              : {}
                          }
                          onClick={(e) => {
                            e.stopPropagation();
                            onVote(post);
                          }}
                        >
                          <span className="vote-icon mr-2 align-[0.125em] text-xs">
                            {checkVote(post) ? (
                              <CheckIcon className="h-3 w-3" />
                            ) : (
                              <ChevronUpIcon className="h-3 w-3" />
                            )}
                          </span>
                          <span className="vote-count">{post.vote_length || 0}</span>
                        </Button>
                        <Button
                          variant="outline"
                          size="sm"
                          className="button-vote button-vote-down h-8 px-2"
                          style={
                            checkDownVote(post)
                              ? { borderLeft: `solid 4px ${theme.accent || '#1890ff'}` }
                              : {}
                          }
                          onClick={(e) => {
                            e.stopPropagation();
                            onDownVote(post);
                          }}
                        >
                          <span className="vote-icon mr-2 align-[0.125em] text-xs">
                            {checkDownVote(post) ? (
                              <CheckIcon className="h-3 w-3" />
                            ) : (
                              <ChevronDownIcon className="h-3 w-3" />
                            )}
                          </span>
                          <span className="vote-count">{post.down_vote_length || 0}</span>
                        </Button>
                      </>
                    )}
                  </div>
                </div>
              ))}
            </div>

            {/* Infinite Scroll Observer */}
            {hasMore && (
              <div ref={observerTarget} className="py-8 text-center">
                {loading && <Skeleton className="mx-auto h-8 w-8 rounded-full" />}
              </div>
            )}
          </>
        )}
      </div>

      {/* Comment Modal */}
      <Dialog open={modalVisible} onOpenChange={setModalVisible}>
        <DialogContent
          className="modal-post-content max-w-3xl"
          onPointerDownOutside={handleCloseModal}
        >
          <DialogHeader>
            <DialogTitle className="text-lg">{selectedPost?.post_title}</DialogTitle>
          </DialogHeader>
          {CommentComponent && selectedPost && (
            <CommentComponent postItem={selectedPost} onClose={handleCloseModal} />
          )}
        </DialogContent>
      </Dialog>
    </div>
  );
}

