import { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface DangerZoneProps {
  boardName: string;
  isLoading: boolean;
  onDeleteBoard: () => void;
}

export default function DangerZone({
  boardName,
  isLoading,
  onDeleteBoard,
}: DangerZoneProps) {
  const [inputName, setInputName] = useState('');

  const isDeleteDisabled = inputName !== boardName;

  return (
    <div>
      <div className="mb-8 mt-2.5 flex items-center">
        <h3 className="text-lg font-semibold text-black">
          {__('Danger Zone', 'feedbo')}
        </h3>
      </div>

      <div className="space-y-4">
        <div>
          <Label className="font-bold">{__('Delete board', 'feedbo')}</Label>
          <div className="mt-2 flex items-center gap-2">
            <Input
              value={inputName}
              onChange={(e) => setInputName(e.target.value)}
              placeholder={__(`Enter the board name (${boardName})`, 'feedbo')}
              className="flex-1"
            />
            <Button
              variant="destructive"
              disabled={isDeleteDisabled}
              loading={isLoading}
              onClick={onDeleteBoard}
            >
              {__('Delete forever', 'feedbo')}
            </Button>
          </div>
        </div>

        <div className="text-sm">
          {__('This action will permanently delete the board', 'feedbo')}{' '}
          <span className="font-bold text-red-600">{boardName}</span>{' '}
          {__('and its content.', 'feedbo')}
        </div>
      </div>
    </div>
  );
}

