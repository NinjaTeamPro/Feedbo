import { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { CheckIcon, CopyIcon } from 'lucide-react';

interface WidgetProps {
  boardId: string;
  scriptUrl: string;
}

export default function Widget({ boardId, scriptUrl }: WidgetProps) {
  const [copied1, setCopied1] = useState(false);
  const [copied2, setCopied2] = useState(false);
  const [activeKey, setActiveKey] = useState('1');

  const code1 = `<button data-feedbo-id="${boardId}">Feedback</button><script async src="${scriptUrl}"></script>`;
  const code2 = `<script async src="${scriptUrl}"></script>
<script>
var vote = {
\tid: '${boardId}', 
\tselector: '.YOUR-CSS-SELECTOR'
};
</script>`;

  const handleCopy = async (text: string, setCopiedState: (val: boolean) => void) => {
    try {
      await navigator.clipboard.writeText(text);
      setCopiedState(true);
      setTimeout(() => setCopiedState(false), 2000);
    } catch (err) {
      console.error('Failed to copy:', err);
    }
  };

  return (
    <div>
      <div className="mb-8 mt-2.5 flex items-center">
        <h3 className="text-lg font-semibold text-black">
          {__('Widget', 'feedbo')}
        </h3>
      </div>

      <div className="space-y-2">
        {/* Widget Link */}
        <Collapsible open={activeKey === '1'} onOpenChange={(open) => setActiveKey(open ? '1' : '')}>
          <CollapsibleTrigger className="flex w-full items-center justify-between rounded-lg border bg-white p-4 text-left hover:bg-gray-50">
            <span className="font-medium">{__('Widget Link', 'feedbo')}</span>
          </CollapsibleTrigger>
          <CollapsibleContent className="border-x border-b bg-white p-4">
            <p className="mb-4 text-sm text-gray-600">
              {__(
                'Copy and paste the following code into the HTML of your website wherever you would like the button to appear.',
                'feedbo'
              )}
            </p>
            <pre className="mb-4 overflow-x-auto rounded-md bg-gray-50 p-4 text-sm">
              <code>{code1}</code>
            </pre>
            <div className="flex justify-end">
              <Button
                onClick={() => handleCopy(code1, setCopied1)}
                size="sm"
                className="gap-2"
              >
                {copied1 ? (
                  <>
                    <CheckIcon className="h-4 w-4" />
                    {__('Copied', 'feedbo')}
                  </>
                ) : (
                  <>
                    <CopyIcon className="h-4 w-4" />
                    {__('Copy Code', 'feedbo')}
                  </>
                )}
              </Button>
            </div>
          </CollapsibleContent>
        </Collapsible>

        {/* Initialize Modal */}
        <Collapsible open={activeKey === '2'} onOpenChange={(open) => setActiveKey(open ? '2' : '')}>
          <CollapsibleTrigger className="flex w-full items-center justify-between rounded-lg border bg-white p-4 text-left hover:bg-gray-50">
            <span className="font-medium">{__('Initialize the Feedbo modal', 'feedbo')}</span>
          </CollapsibleTrigger>
          <CollapsibleContent className="border-x border-b bg-white p-4">
            <p className="mb-4 text-sm text-gray-600">
              {__(
                'Replace the CSS selector placeholder so that it targets the element that should open the modal.',
                'feedbo'
              )}
            </p>
            <pre className="mb-4 overflow-x-auto rounded-md bg-gray-50 p-4 text-sm">
              <code>{code2}</code>
            </pre>
            <div className="flex justify-end">
              <Button
                onClick={() => handleCopy(code2, setCopied2)}
                size="sm"
                className="gap-2"
              >
                {copied2 ? (
                  <>
                    <CheckIcon className="h-4 w-4" />
                    {__('Copied', 'feedbo')}
                  </>
                ) : (
                  <>
                    <CopyIcon className="h-4 w-4" />
                    {__('Copy Code', 'feedbo')}
                  </>
                )}
              </Button>
            </div>
          </CollapsibleContent>
        </Collapsible>
      </div>
    </div>
  );
}

