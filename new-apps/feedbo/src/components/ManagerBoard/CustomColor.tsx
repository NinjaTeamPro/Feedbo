import { useState, useRef, useEffect } from 'react';
import { Button } from '@/components/ui/button';

interface CustomColorProps {
  title: string;
  color: string;
  onChange: (params: { name: string; color: string }) => void;
}

/**
 * Check if a color is light or dark using HSP equation
 */
function checkColor(color: string): 'light' | 'dark' {
  let r: number;
  let g: number;
  let b: number;

  if (color.match(/^rgb/)) {
    const match = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
    if (match) {
      r = parseInt(match[1]);
      g = parseInt(match[2]);
      b = parseInt(match[3]);
    } else {
      return 'dark';
    }
  } else {
    const hex = color.slice(1).replace(color.length < 5 ? /./g : '', '$&$&');
    const colorNum = parseInt(hex, 16);
    r = (colorNum >> 16) & 255;
    g = (colorNum >> 8) & 255;
    b = colorNum & 255;
  }

  // HSP (Highly Sensitive Poo) equation
  const hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));

  return hsp > 160 ? 'light' : 'dark';
}

export default function CustomColor({ title, color, onChange }: CustomColorProps) {
  const [displayPicker, setDisplayPicker] = useState(false);
  const [colorValue, setColorValue] = useState(color);
  const pickerRef = useRef<HTMLDivElement>(null);

  const isHeader = title === 'Header';
  const textColor = isHeader && checkColor(color) === 'light' ? 'text-black' : 'text-white';

  useEffect(() => {
    setColorValue(color);
  }, [color]);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (pickerRef.current && !pickerRef.current.contains(event.target as Node)) {
        setDisplayPicker(false);
      }
    };

    if (displayPicker) {
      document.addEventListener('click', handleClickOutside);
    }

    return () => {
      document.removeEventListener('click', handleClickOutside);
    };
  }, [displayPicker]);

  const handleColorChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newColor = e.target.value;
    setColorValue(newColor);
    onChange({
      name: title,
      color: newColor,
    });
  };

  return (
    <div ref={pickerRef} className="relative">
      <div className="pointer-events-auto relative min-w-0 pl-6">
        <Button
          variant="ghost"
          className="flex min-w-40 max-w-40 items-center gap-4 overflow-hidden text-ellipsis whitespace-nowrap rounded-md px-4 py-2 text-base outline-none hover:bg-gray-100"
          onClick={() => setDisplayPicker(!displayPicker)}
        >
          <div
            className="absolute bottom-1.5 flex h-4 w-4 rounded-full border border-gray-400"
            style={{ backgroundColor: color }}
          />
          <span className={`pl-10 ${textColor}`}>{title}</span>
        </Button>

        {displayPicker && (
          <div className="absolute left-0 top-full z-10 mt-2 flex items-center justify-center">
            <div className="relative flex w-full items-center justify-center rounded-md border bg-white p-4 shadow-lg">
              <div className="space-y-4">
                <div className="flex items-center gap-4">
                  <input
                    type="color"
                    value={colorValue}
                    onChange={handleColorChange}
                    className="h-10 w-20 cursor-pointer rounded border"
                  />
                  <input
                    type="text"
                    value={colorValue}
                    onChange={(e) => {
                      setColorValue(e.target.value);
                      if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                        onChange({
                          name: title,
                          color: e.target.value,
                        });
                      }
                    }}
                    className="flex-1 rounded border px-3 py-2 text-sm"
                    placeholder="#000000"
                  />
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

