import { useState, useRef } from 'react';
import { Camera, Plus } from '@phosphor-icons/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';


interface AccountProps {
  user: {
    display_name: string;
    user_email: string;
    user_avatar: string;
  };
  onSave?: (data: { name: string; email: string }) => void;
  onCancel?: () => void;
}

export default function Account({ user, onSave, onCancel }: AccountProps) {
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [avatarUrl, setAvatarUrl] = useState(user.user_avatar || '');
  const [uploadedAvatar, setUploadedAvatar] = useState('');
  const [formData, setFormData] = useState({
    name: user.display_name || '',
    email: user.user_email || '',
  });
  const [errors, setErrors] = useState({
    name: '',
    email: '',
  });
  const fileInputRef = useRef<HTMLInputElement>(null);

  const validateEmail = (email: string) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  };

  const validateForm = () => {
    const newErrors = {
      name: '',
      email: '',
    };

    if (!formData.name.trim()) {
      newErrors.name = 'Please input Display name';
    }

    if (formData.email && !validateEmail(formData.email)) {
      newErrors.email = 'Please enter a valid email address';
    }

    setErrors(newErrors);
    return !newErrors.name && !newErrors.email;
  };

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    // Validate file type
    const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
    if (!isJpgOrPng) {
      toast.error('You can only upload JPG or PNG files!');
      return;
    }

    // Validate file size
    const isLt2M = file.size / 1024 / 1024 < 2;
    if (!isLt2M) {
      toast.error('Image must be smaller than 2MB!');
      return;
    }

    setLoading(true);

    // Create FormData and upload
    const formData = new FormData();
    formData.append('file', file);

    try {
      const response = await fetch(
        window.feedbo?.axiosUrl + '/v1/wp_upload_file',
        {
          method: 'POST',
          body: formData,
        }
      );

      if (response.ok) {
        const data = await response.json();
        setUploadedAvatar(data.url);
        setAvatarUrl(data.url);
        toast.success('Avatar uploaded successfully');
      }
    } catch (error) {
      toast.error('Failed to upload avatar');
    } finally {
      setLoading(false);
    }
  };

  const handleAvatarClick = () => {
    fileInputRef.current?.click();
  };

  const handleCancel = () => {
    setFormData({
      name: user.display_name,
      email: user.user_email,
    });
    setUploadedAvatar('');
    setAvatarUrl(user.user_avatar);
    setErrors({ name: '', email: '' });
    onCancel?.();
  };

  const handleSubmit = async () => {
    if (!validateForm()) {
      toast.error('Please fix the errors in the form');
      return;
    }

    setSaving(true);
    
    // Simulate API call
    setTimeout(() => {
      onSave?.(formData);
      toast.success('Account info updated successfully');
      setSaving(false);
    }, 1000);
  };

  const currentAvatar = uploadedAvatar || avatarUrl;

  return (
    <div className="space-y-6">
      {/* Avatar Upload */}
      <div className="mx-auto w-[115px]">
        <div
          className="relative h-[115px] w-[115px] cursor-pointer rounded-full border-4 border-white"
          onClick={handleAvatarClick}
        >
          {currentAvatar ? (
            <img
              src={currentAvatar}
              alt="Avatar"
              className="h-full w-full rounded-full object-cover"
            />
          ) : (
            <div className="flex h-full w-full flex-col items-center justify-center rounded-full bg-gray-100">
              {loading ? (
                <div className="animate-spin text-gray-400">⏳</div>
              ) : (
                <>
                  <Plus size={32} className="text-gray-400" />
                  <div className="mt-2 text-sm text-gray-500">Upload</div>
                </>
              )}
            </div>
          )}
          <div className="absolute right-[30%] top-[36%] flex h-7 w-7 items-center justify-center rounded-full bg-[#e6e8eb]">
            <Camera size={17} weight="fill" className="text-[#1a2a37]" />
          </div>
        </div>
        <input
          ref={fileInputRef}
          type="file"
          accept="image/jpeg,image/png"
          className="hidden"
          onChange={handleFileChange}
        />
      </div>

      {/* Form */}
      <div className="space-y-4">
        <div className="space-y-2">
          <Label htmlFor="username">Username</Label>
          <Input
            id="username"
            value={formData.name}
            onChange={(e) =>
              setFormData({ ...formData, name: e.target.value })
            }
            onBlur={() => {
              if (!formData.name.trim()) {
                setErrors({ ...errors, name: 'Please input Display name' });
              } else {
                setErrors({ ...errors, name: '' });
              }
            }}
            className={errors.name ? 'border-red-500' : ''}
          />
          {errors.name && (
            <p className="text-sm text-red-500">{errors.name}</p>
          )}
        </div>

        <div className="space-y-2">
          <Label htmlFor="email">Email Address</Label>
          <Input
            id="email"
            type="email"
            value={formData.email}
            onChange={(e) =>
              setFormData({ ...formData, email: e.target.value })
            }
            onBlur={() => {
              if (formData.email && !validateEmail(formData.email)) {
                setErrors({
                  ...errors,
                  email: 'Please enter a valid email address',
                });
              } else {
                setErrors({ ...errors, email: '' });
              }
            }}
            className={errors.email ? 'border-red-500' : ''}
          />
          {errors.email && (
            <p className="text-sm text-red-500">{errors.email}</p>
          )}
        </div>

        <div className="flex justify-end gap-2.5 pt-2">
          <Button
            variant="outline"
            className="h-[35px] w-[70px]"
            onClick={handleCancel}
          >
            Cancel
          </Button>
          <Button
            className="h-[35px] w-[70px]"
            onClick={handleSubmit}
            disabled={saving}
          >
            {saving ? '...' : 'Save'}
          </Button>
        </div>
      </div>
    </div>
  );
}

