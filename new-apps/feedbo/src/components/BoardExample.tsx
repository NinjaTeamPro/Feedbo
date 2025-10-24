/**
 * Example implementation of the converted Vue components
 * This demonstrates how to use Menu, ListPost, and Comment components together
 */

import { useState } from 'react';
import { __ } from '@wordpress/i18n';
import Header from './Header';
import Menu from './Menu';
import ListPost from './ListPost';
import Comment from './Comment';
import { Button } from './ui/button';

// Mock data for demonstration
const mockBoard = {
  name: 'Product Feedback',
  description: 'Share your feedback and ideas with our team',
  logoImg: '',
  theme: {
    header: '#1890ff',
    background: '#f8fafb',
    title: '#1a2a37',
    text: '#666666',
    accent: '#1890ff',
  },
  features: 'roadmap,anonymous,downvoting',
};

const mockPosts = [
  {
    post_id: 1,
    post_title: 'Add dark mode support',
    post_content: 'It would be great to have a dark mode option for better viewing at night.',
    post_status: 'Planned',
    post_slug: 'add-dark-mode',
    vote_ids: '1 , 2 , 3',
    vote_length: 3,
    down_vote_ids: '',
    down_vote_length: 0,
    comment_status: 'open',
    term_id: 1,
  },
  {
    post_id: 2,
    post_title: 'Improve mobile responsiveness',
    post_content: 'The mobile view needs some improvements for better usability.',
    post_status: 'In Progress',
    post_slug: 'improve-mobile',
    vote_ids: '1 , 2',
    vote_length: 2,
    down_vote_ids: '',
    down_vote_length: 0,
    comment_status: 'open',
    term_id: 1,
  },
];

const mockComments = [
  {
    comment_ID: 1,
    comment_author: 'John Doe',
    comment_content: 'This is a great idea! I would love to see this implemented.',
    comment_date: '2025-10-20 10:30:00',
    comment_date_format: '4 days ago',
    comment_image: [],
    users_like: '1 , 2',
    userslike_length: 2,
  },
];

const mockStatusColors = {
  'Planned': '#2db7f5',
  'In Progress': '#87d068',
  'Completed': '#108ee9',
  'Unassigned': '#ccc',
};

const mockUser = {
  ID: 1,
  display_name: 'John Doe',
  user_email: 'john@example.com',
};

export function BoardExample() {
  const [activeTab, setActiveTab] = useState('vote');
  const [posts, setPosts] = useState(mockPosts);
  const [comments, setComments] = useState(mockComments);
  const [voteAnonymous, setVoteAnonymous] = useState<number[]>([]);
  const [downVoteAnonymous, setDownVoteAnonymous] = useState<number[]>([]);
  const [likeAnonymous, setLikeAnonymous] = useState<number[]>([]);

  const showRoadmap = mockBoard.features.includes('roadmap');
  const allowAnonymous = mockBoard.features.includes('anonymous');
  const allowDownvote = mockBoard.features.includes('downvoting');

  const handleTabChange = (tab: string) => {
    setActiveTab(tab);
    console.log('Tab changed to:', tab);
  };

  const handleNewPost = () => {
    console.log('Open new post dialog');
    // Implement new post dialog
  };

  const handleVote = (post: any) => {
    console.log('Vote on post:', post.post_id);
    // Implement vote logic
  };

  const handleDownVote = (post: any) => {
    console.log('Downvote on post:', post.post_id);
    // Implement downvote logic
  };

  const handlePostClick = (post: any) => {
    console.log('Post clicked:', post.post_id);
    // This will open the comment modal in ListPost component
  };

  const handleAddComment = (comment: string, files?: File[]) => {
    console.log('Add comment:', comment, files);
    // Implement add comment logic
  };

  const handleEditComment = (commentId: number, content: string) => {
    console.log('Edit comment:', commentId, content);
    // Implement edit comment logic
  };

  const handleDeleteComment = (commentId: number) => {
    console.log('Delete comment:', commentId);
    // Implement delete comment logic
  };

  const handleLikeComment = (commentId: number) => {
    console.log('Like comment:', commentId);
    // Implement like comment logic
  };

  const handleStatusChange = (status: string) => {
    console.log('Change status to:', status);
    // Implement status change logic
  };

  const handleLockComment = () => {
    console.log('Lock comments');
    // Implement lock comment logic
  };

  const handleUnlockComment = () => {
    console.log('Unlock comments');
    // Implement unlock comment logic
  };

  const handleDeletePost = () => {
    console.log('Delete post');
    // Implement delete post logic
  };

  const handleExportVoters = () => {
    console.log('Export voters');
    // Implement export voters logic
  };

  const handleExportSubscribers = () => {
    console.log('Export subscribers');
    // Implement export subscribers logic
  };

  return (
    <div className="board-example" style={{ backgroundColor: mockBoard.theme.background }}>
      {/* Board Header */}
      <Header
        title={mockBoard.name}
        description={mockBoard.description}
        logoImg={mockBoard.logoImg}
        headerBackground={mockBoard.theme.header}
      />

      {/* Menu / Tabs */}
      <Menu
        activeTab={activeTab}
        showRoadmap={showRoadmap}
        onTabChange={handleTabChange}
        NewPostButton={
          <Button onClick={handleNewPost} size="sm">
            {__('New Post', 'feedbo')}
          </Button>
        }
      />

      {/* Post List */}
      <ListPost
        posts={posts}
        loading={false}
        activeTab={activeTab}
        theme={mockBoard.theme}
        statusColors={mockStatusColors}
        user={mockUser}
        isAnonymous={false}
        allowAnonymous={allowAnonymous}
        allowDownvote={allowDownvote}
        voteAnonymous={voteAnonymous}
        downVoteAnonymous={downVoteAnonymous}
        onVote={handleVote}
        onDownVote={handleDownVote}
        onPostClick={handlePostClick}
        onLoadMore={() => console.log('Load more posts')}
        hasMore={false}
        CommentComponent={({ postItem, onClose }) => (
          <Comment
            postItem={postItem}
            comments={comments}
            theme={mockBoard.theme}
            statusColors={mockStatusColors}
            user={mockUser}
            isAnonymous={false}
            allowAnonymous={allowAnonymous}
            allowDownvote={allowDownvote}
            currentUserLevel={1}
            voteAnonymous={voteAnonymous}
            downVoteAnonymous={downVoteAnonymous}
            likeAnonymous={likeAnonymous}
            onVote={handleVote}
            onDownVote={handleDownVote}
            onAddComment={handleAddComment}
            onEditComment={handleEditComment}
            onDeleteComment={handleDeleteComment}
            onLikeComment={handleLikeComment}
            onStatusChange={handleStatusChange}
            onLockComment={handleLockComment}
            onUnlockComment={handleUnlockComment}
            onDeletePost={handleDeletePost}
            onExportVoters={handleExportVoters}
            onExportSubscribers={handleExportSubscribers}
            onClose={onClose}
          />
        )}
      />
    </div>
  );
}

