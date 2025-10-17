import { useState, useEffect } from 'react';
import { Check } from '@phosphor-icons/react';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import { toast } from 'sonner';

interface NotificationSettings {
  sendEmail: boolean;
  OwnPost: boolean;
  Comment: boolean;
}

interface NotificationProps {
  user: {
    ID: number;
    notification: NotificationSettings[];
  };
  onSave?: (settings: NotificationSettings) => void;
  onCancel?: () => void;
  onUnsubscribeAll?: (userId: number) => void;
}

const notificationOptions = [
  {
    name: 'sendEmail' as keyof NotificationSettings,
    title: 'Email me when someone cites me with an @mention',
  },
  {
    name: 'OwnPost' as keyof NotificationSettings,
    title: 'Auto-subscribe to own posts and get notified when someone responds',
  },
  {
    name: 'Comment' as keyof NotificationSettings,
    title: 'Auto-subscribe to posts that you comment on',
  },
];

export default function Notification({
  user,
  onSave,
  onCancel,
  onUnsubscribeAll,
}: NotificationProps) {
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [unsubscribeText, setUnsubscribeText] = useState('Unsubscribe from all posts');
  const [settings, setSettings] = useState<NotificationSettings>({
    sendEmail: false,
    OwnPost: false,
    Comment: false,
  });
  const [originalSettings, setOriginalSettings] = useState<NotificationSettings>({
    sendEmail: false,
    OwnPost: false,
    Comment: false,
  });

  useEffect(() => {
    if (user.notification && user.notification[0]) {
      const initialSettings = {
        sendEmail: user.notification[0].sendEmail,
        OwnPost: user.notification[0].OwnPost,
        Comment: user.notification[0].Comment,
      };
      setSettings(initialSettings);
      setOriginalSettings(initialSettings);
    }
  }, [user.notification]);

  const handleToggle = (name: keyof NotificationSettings) => {
    setSettings((prev) => ({
      ...prev,
      [name]: !prev[name],
    }));
  };

  const handleUnsubscribeAll = async () => {
    setLoading(true);
    
    // Simulate API call
    setTimeout(() => {
      onUnsubscribeAll?.(user.ID);
      setUnsubscribeText('Unsubscribed');
      setLoading(false);
      toast.success('Unsubscribed from all posts');
    }, 1000);
  };

  const handleCancel = () => {
    setSettings(originalSettings);
    onCancel?.();
  };

  const handleSave = async () => {
    setSaving(true);
    
    // Simulate API call
    setTimeout(() => {
      onSave?.(settings);
      setOriginalSettings(settings);
      setSaving(false);
      toast.success('Notification settings updated successfully');
    }, 1000);
  };

  return (
    <div className="space-y-4">
      {/* Notification List */}
      <div className="divide-y">
        {notificationOptions.map((option) => (
          <div
            key={option.name}
            className="flex items-center justify-between py-4"
          >
            <div className="flex-1 pr-4">
              <p className="text-sm leading-relaxed text-foreground">
                {option.title}
              </p>
            </div>
            <Switch
              checked={settings[option.name]}
              onCheckedChange={() => handleToggle(option.name)}
            />
          </div>
        ))}
      </div>

      {/* Divider */}
      <div className="h-px rounded-md bg-gray-200" />

      {/* Unsubscribe All */}
      <div className="py-2">
        <Button
          variant="link"
          className="h-auto p-0 text-sm font-normal text-blue-600 hover:text-blue-700"
          onClick={handleUnsubscribeAll}
          disabled={loading}
        >
          {loading ? (
            '...'
          ) : (
            <>
              {unsubscribeText === 'Unsubscribed' && (
                <Check size={16} className="mr-1" weight="bold" />
              )}
              {unsubscribeText}
            </>
          )}
        </Button>
      </div>

      {/* Action Buttons */}
      <div className="flex justify-end gap-2.5 pt-6">
        <Button
          variant="outline"
          className="h-[35px] w-[70px]"
          onClick={handleCancel}
        >
          Cancel
        </Button>
        <Button
          className="h-[35px] w-[70px]"
          onClick={handleSave}
          disabled={saving}
        >
          {saving ? '...' : 'Save'}
        </Button>
      </div>
    </div>
  );
}

