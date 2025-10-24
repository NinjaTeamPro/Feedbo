import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Textarea } from '@/components/ui/textarea';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import {
  CheckIcon,
  ChevronUpIcon,
  ChevronDownIcon,
  HeartIcon,
  MoreVerticalIcon,
  UploadIcon,
  TrashIcon,
  LockIcon,
  UnlockIcon,
} from 'lucide-react';

interface Comment {
  comment_ID: number;
  comment_author: string;
  comment_content: string;
  comment_date: string;
  comment_date_format: string;
  comment_image?: string[];
  users_like?: string;
  userslike_length: number;
}

interface PostItem {
  post_id: number;
  post_title: string;
  post_content: string;
  post_status: string;
  post_author?: number;
  vote_ids: string;
  vote_length: number;
  down_vote_ids?: string;
  down_vote_length?: number;
  comment_status: string;
  term_id: number;
}

interface CommentProps {
  postItem: PostItem;
  comments: Comment[];
  theme: {
    title?: string;
    text?: string;
    accent?: string;
  };
  statusColors: Record<string, string>;
  user: any;
  isAnonymous: boolean;
  allowAnonymous: boolean;
  allowDownvote: boolean;
  currentUserLevel: number;
  voteAnonymous: number[];
  downVoteAnonymous: number[];
  likeAnonymous: number[];
  onVote: (post: PostItem) => void;
  onDownVote: (post: PostItem) => void;
  onAddComment: (comment: string, files?: File[]) => void;
  onEditComment: (commentId: number, content: string, images?: string[]) => void;
  onDeleteComment: (commentId: number) => void;
  onLikeComment: (commentId: number) => void;
  onStatusChange: (status: string) => void;
  onLockComment: () => void;
  onUnlockComment: () => void;
  onDeletePost: () => void;
  onExportVoters: () => void;
  onExportSubscribers: () => void;
  onClose?: () => void;
}

export default function Comment({
  postItem,
  comments,
  theme,
  statusColors,
  user,
  isAnonymous,
  allowAnonymous,
  allowDownvote,
  currentUserLevel,
  voteAnonymous,
  downVoteAnonymous,
  likeAnonymous,
  onVote,
  onDownVote,
  onAddComment,
  onEditComment,
  onDeleteComment,
  onLikeComment,
  onStatusChange,
  onLockComment,
  onUnlockComment,
  onDeletePost,
  onExportVoters,
  onExportSubscribers,
  onClose,
}: CommentProps) {
  const [commentText, setCommentText] = useState('');
  const [editingComment, setEditingComment] = useState<Comment | null>(null);
  const [editText, setEditText] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [uploadedFiles, setUploadedFiles] = useState<File[]>([]);

  const isLocked = postItem.comment_status === 'closed';

  const checkVote = () => {
    if (isAnonymous) {
      return voteAnonymous.includes(postItem.post_id);
    } else {
      if (!postItem.vote_ids) return false;
      const voteIds = postItem.vote_ids.split(' , ');
      return user?.ID ? voteIds.includes(user.ID.toString()) : false;
    }
  };

  const checkDownVote = () => {
    if (isAnonymous) {
      return downVoteAnonymous.includes(postItem.post_id);
    } else {
      if (!postItem.down_vote_ids) return false;
      const downVoteIds = postItem.down_vote_ids.split(' , ');
      return user?.ID ? downVoteIds.includes(user.ID.toString()) : false;
    }
  };

  const checkLike = (comment: Comment) => {
    if (isAnonymous) {
      return likeAnonymous.includes(comment.comment_ID);
    } else {
      if (!comment.users_like) return false;
      const userLikes = comment.users_like.split(' , ');
      return user?.ID ? userLikes.includes(user.ID.toString()) : false;
    }
  };

  const checkIsCommentAuthor = (comment: Comment) => {
    if (isAnonymous) return false;
    return user?.display_name === comment.comment_author;
  };

  const handleSubmitComment = (e: React.FormEvent) => {
    e.preventDefault();
    if (!commentText.trim()) return;

    setIsSubmitting(true);
    onAddComment(commentText, uploadedFiles);
    setCommentText('');
    setUploadedFiles([]);
    setIsSubmitting(false);
  };

  const handleEditComment = () => {
    if (!editingComment || !editText.trim()) return;

    onEditComment(editingComment.comment_ID, editText);
    setEditingComment(null);
    setEditText('');
  };

  const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      setUploadedFiles(Array.from(e.target.files));
    }
  };

  return (
    <div className="comment-component">
      {/* Admin Actions Dropdown */}
      {currentUserLevel < 3 && (
        <div className="button-lock-delete absolute right-2.5 top-2">
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="icon" className="h-8 w-8">
                <MoreVerticalIcon className="h-5 w-5" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-44">
              <DropdownMenuItem onClick={isLocked ? onUnlockComment : onLockComment}>
                {isLocked ? (
                  <>
                    <UnlockIcon className="mr-2 h-4 w-4" />
                    {__('Unlock', 'feedbo')}
                  </>
                ) : (
                  <>
                    <LockIcon className="mr-2 h-4 w-4" />
                    {__('Lock', 'feedbo')}
                  </>
                )}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={onExportVoters}>
                {__('Export Voter', 'feedbo')}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={onExportSubscribers}>
                {__('Export Subscriber', 'feedbo')}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={onDeletePost} className="text-red-600">
                <TrashIcon className="mr-2 h-4 w-4" />
                {__('Delete', 'feedbo')}
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      )}

      {/* Post Status and Voting */}
      <div className="post-status-container border-b border-gray-200 pb-6">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <span className="text-sm" style={{ color: theme.text || '#666' }}>
              {__('Status:', 'feedbo')}
            </span>
            {currentUserLevel < 3 ? (
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="outline" size="sm" className="h-9 gap-2">
                    {postItem.post_status !== 'Unassigned' && (
                      <div
                        className="h-2 w-2 rounded-full"
                        style={{ backgroundColor: statusColors[postItem.post_status] || '#ccc' }}
                      />
                    )}
                    {postItem.post_status}
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent>
                  {Object.entries(statusColors).map(([status, color]) => (
                    <DropdownMenuItem key={status} onClick={() => onStatusChange(status)}>
                      <div className="flex w-full items-center gap-2">
                        <div className="h-2 w-2 rounded-full" style={{ backgroundColor: color }} />
                        {status}
                      </div>
                    </DropdownMenuItem>
                  ))}
                </DropdownMenuContent>
              </DropdownMenu>
            ) : (
              <span className="flex items-center gap-2 text-sm font-medium">
                {postItem.post_status !== 'Unassigned' && (
                  <div
                    className="h-2 w-2 rounded-full"
                    style={{ backgroundColor: statusColors[postItem.post_status] || '#ccc' }}
                  />
                )}
                {postItem.post_status}
              </span>
            )}
          </div>

          {/* Vote Buttons */}
          <div className={`flex gap-2 ${allowDownvote ? 'flex-row' : ''}`}>
            <Button
              variant={checkVote() ? 'default' : 'outline'}
              size="sm"
              className="h-9"
              onClick={() => onVote(postItem)}
              style={
                checkVote()
                  ? {
                      backgroundColor: theme.accent || '#1890ff',
                      borderColor: theme.accent || '#1890ff',
                    }
                  : {}
              }
            >
              <span className="vote-icon mr-2 text-xs">
                {checkVote() ? (
                  <CheckIcon className="h-3 w-3" />
                ) : (
                  <ChevronUpIcon className="h-3 w-3" />
                )}
              </span>
              {__('Upvoted', 'feedbo')} {postItem.vote_length}
            </Button>
            {allowDownvote && (
              <Button
                variant={checkDownVote() ? 'default' : 'outline'}
                size="sm"
                className="h-9"
                onClick={() => onDownVote(postItem)}
                style={
                  checkDownVote()
                    ? {
                        backgroundColor: theme.accent || '#1890ff',
                        borderColor: theme.accent || '#1890ff',
                      }
                    : {}
                }
              >
                <span className="vote-icon mr-2 text-xs">
                  {checkDownVote() ? (
                    <CheckIcon className="h-3 w-3" />
                  ) : (
                    <ChevronDownIcon className="h-3 w-3" />
                  )}
                </span>
                {__('Downvoted', 'feedbo')} {postItem.down_vote_length || 0}
              </Button>
            )}
          </div>
        </div>
      </div>

      {/* Comments List */}
      <div className="comment-list space-y-6 py-6">
        {comments.map((comment) => (
          <div key={comment.comment_ID} className="flex gap-4">
            <Avatar>
              <AvatarImage src={comment.comment_author || undefined} />
              <AvatarFallback>
                {comment.comment_author ? comment.comment_author[0].toUpperCase() : 'A'}
              </AvatarFallback>
            </Avatar>
            <div className="flex-1">
              <div className="mb-1 flex items-center justify-between">
                <span className="font-medium" style={{ color: theme.text || '#000' }}>
                  {comment.comment_author || __('Anonymous', 'feedbo')}
                </span>
                {(checkIsCommentAuthor(comment) || currentUserLevel < 3) && (
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button variant="ghost" size="icon" className="h-6 w-6">
                        <MoreVerticalIcon className="h-4 w-4" />
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                      <DropdownMenuItem
                        onClick={() => {
                          setEditingComment(comment);
                          setEditText(comment.comment_content);
                        }}
                      >
                        {__('Edit', 'feedbo')}
                      </DropdownMenuItem>
                      <DropdownMenuItem
                        onClick={() => onDeleteComment(comment.comment_ID)}
                        className="text-red-600"
                      >
                        {__('Delete', 'feedbo')}
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                )}
              </div>
              <p
                className="comment-content-text mb-2 whitespace-pre-wrap break-words text-sm"
                style={{ color: theme.text || '#666' }}
              >
                {comment.comment_content}
              </p>
              {comment.comment_image && comment.comment_image.length > 0 && (
                <div className="mb-2 flex flex-wrap gap-2">
                  {comment.comment_image.map((img, idx) => (
                    <a
                      key={idx}
                      href={img}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="comment-image-list"
                    >
                      <img
                        src={img}
                        alt="Comment attachment"
                        className="comment-image h-26 w-32 rounded border border-gray-200 object-cover p-1"
                      />
                    </a>
                  ))}
                </div>
              )}
              <div className="flex items-center gap-4 text-xs text-gray-500">
                <TooltipProvider>
                  <Tooltip>
                    <TooltipTrigger asChild>
                      <span>{comment.comment_date_format}</span>
                    </TooltipTrigger>
                    <TooltipContent>
                      <span className="text-xs">{comment.comment_date}</span>
                    </TooltipContent>
                  </Tooltip>
                </TooltipProvider>
                <button
                  onClick={() => onLikeComment(comment.comment_ID)}
                  className="flex items-center gap-1 hover:text-red-500"
                >
                  <HeartIcon
                    className={`h-4 w-4 ${checkLike(comment) ? 'fill-red-500 text-red-500' : ''}`}
                  />
                  <span>{comment.userslike_length}</span>
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Add Comment Form */}
      <form onSubmit={handleSubmitComment} className="space-y-3">
        <Textarea
          value={commentText}
          onChange={(e) => setCommentText(e.target.value)}
          placeholder={__('Leave a comment', 'feedbo')}
          disabled={isLocked}
          rows={4}
          className="feedbo-input"
        />
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <Button
              type="button"
              variant="outline"
              size="sm"
              disabled={isLocked}
              onClick={() => document.getElementById('comment-file-upload')?.click()}
            >
              <UploadIcon className="mr-2 h-4 w-4" />
              {__('Upload image', 'feedbo')}
            </Button>
            <input
              id="comment-file-upload"
              type="file"
              accept="image/*"
              multiple
              className="hidden"
              onChange={handleFileUpload}
            />
            {uploadedFiles.length > 0 && (
              <span className="text-xs text-gray-500">
                {uploadedFiles.length} {__('file(s) selected', 'feedbo')}
              </span>
            )}
          </div>
          <Button
            type="submit"
            size="sm"
            disabled={isLocked || isSubmitting || !commentText.trim()}
            style={{
              backgroundColor: theme.accent || '#1890ff',
              borderColor: theme.accent || '#1890ff',
            }}
          >
            {__('Comment', 'feedbo')}
          </Button>
        </div>
      </form>

      {/* Edit Comment Dialog */}
      <Dialog open={!!editingComment} onOpenChange={() => setEditingComment(null)}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>{__('Edit Comment', 'feedbo')}</DialogTitle>
          </DialogHeader>
          <Textarea
            value={editText}
            onChange={(e) => setEditText(e.target.value)}
            rows={4}
            className="feedbo-input"
          />
          <DialogFooter>
            <Button variant="outline" onClick={() => setEditingComment(null)}>
              {__('Cancel', 'feedbo')}
            </Button>
            <Button
              onClick={handleEditComment}
              style={{
                backgroundColor: theme.accent || '#1890ff',
                borderColor: theme.accent || '#1890ff',
              }}
            >
              {__('Save changes', 'feedbo')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
}

