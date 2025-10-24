import { __ } from '@wordpress/i18n';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Skeleton } from '@/components/ui/skeleton';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';

interface Activity {
  activity_id: number;
  display_name: string | null;
  activity_name: string;
  post_title: string;
  post_id: number;
  post_slug: string;
  activity_date: string;
  activity_date_format: string;
  user_avatar?: string;
}

interface DashBoardProps {
  activities: Activity[];
  loading: boolean;
  onActivityClick: (activity: Activity) => void;
}

export default function DashBoard({ activities, loading, onActivityClick }: DashBoardProps) {
  return (
    <ScrollArea className="h-[90vh] rounded-lg">
      <div>
        <div className="mb-8 mt-2.5 flex items-center">
          <h3 className="text-lg font-semibold text-black">{__('Dashboard', 'feedbo')}</h3>
        </div>

        <div className="relative rounded-md bg-white p-8 text-sm text-black">
          <h3 className="mb-4 flex items-center">{__('Activity', 'feedbo')}</h3>

          {loading ? (
            <div className="space-y-4">
              {[...Array(3)].map((_, i) => (
                <div key={i} className="flex items-start gap-4">
                  <Skeleton className="h-12 w-12 rounded-full" />
                  <div className="flex-1 space-y-2">
                    <Skeleton className="h-4 w-3/4" />
                    <Skeleton className="h-3 w-1/2" />
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="space-y-4">
              {activities.map((item) => (
                <div key={item.activity_id} className="flex items-start gap-4">
                  <div className="relative flex h-8 w-8 items-center overflow-hidden rounded-full">
                    {item.display_name ? (
                      <Avatar>
                        <AvatarImage src={item.user_avatar} alt={item.display_name} />
                        <AvatarFallback>{item.display_name[0]?.toUpperCase()}</AvatarFallback>
                      </Avatar>
                    ) : (
                      <Avatar>
                        <AvatarFallback>A</AvatarFallback>
                      </Avatar>
                    )}
                  </div>

                  <div className="flex-1">
                    <div className="text-description text-sm">
                      <span className="text-black">
                        {item.display_name || __('Anonymous', 'feedbo')}
                      </span>{' '}
                      {item.activity_name}
                    </div>

                    <a
                      onClick={() => onActivityClick(item)}
                      className="mt-2 block cursor-pointer bg-gray-100 px-3 py-2"
                    >
                      <span className="text-black">{item.post_title}</span>
                    </a>

                    <TooltipProvider>
                      <Tooltip>
                        <TooltipTrigger asChild>
                          <p className="mt-1 text-xs font-bold">{item.activity_date_format}</p>
                        </TooltipTrigger>
                        <TooltipContent>
                          <span>{item.activity_date}</span>
                        </TooltipContent>
                      </Tooltip>
                    </TooltipProvider>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </ScrollArea>
  );
}

