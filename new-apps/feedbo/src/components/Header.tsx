import { __ } from '@wordpress/i18n';

interface HeaderProps {
  title: string;
  description?: string;
  logoImg?: string;
  headerBackground?: string;
}

/**
 * Determines if a color is light or dark using HSP equation
 * @param color - Color in HEX or RGB format
 * @returns 'light' or 'dark'
 */
function checkColor(color: string): 'light' | 'dark' {
  let r: number;
  let g: number;
  let b: number;

  // Check the format of the color, HEX or RGB?
  if (color.match(/^rgb/)) {
    // If RGB --> extract the red, green, blue values
    const match = color.match(
      /^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/
    );
    if (match) {
      r = parseInt(match[1]);
      g = parseInt(match[2]);
      b = parseInt(match[3]);
    } else {
      return 'dark';
    }
  } else {
    // If HEX --> Convert it to RGB
    const hex = color.slice(1).replace(color.length < 5 ? /./g : '', '$&$&');
    const colorNum = parseInt(hex, 16);
    r = (colorNum >> 16) & 255;
    g = (colorNum >> 8) & 255;
    b = colorNum & 255;
  }

  // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
  const hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));

  // Using the HSP value, determine whether the color is light or dark
  if (hsp > 160) {
    return 'light';
  } else {
    return 'dark';
  }
}

export default function Header({
  title,
  description = '',
  logoImg = '',
  headerBackground = '#1890ff',
}: HeaderProps) {
  const colorScheme = checkColor(headerBackground);
  const isLight = colorScheme === 'light';
  const textColor = isLight ? 'text-black' : 'text-white';

  return (
    <div
      className="relative break-words rounded-t-lg px-7 py-6 md:px-8"
      style={{ backgroundColor: headerBackground }}
    >
      <div className={`${textColor}`}>
        {/* Title Section */}
        <div className="text-xl font-semibold leading-normal tracking-normal">
          {logoImg === '' ? (
            <div>
              <span>
                <h1 className={`text-lg ${textColor}`}>
                  {title}
                </h1>
              </span>
            </div>
          ) : (
            <div>
              <div className="flex items-center gap-4">
                <img
                  src={logoImg}
                  alt={__('Board Logo', 'feedbo')}
                  className="h-8"
                />
                <div>
                  <h1 className={`text-lg ${textColor}`}>
                    {title}
                  </h1>
                </div>
              </div>
            </div>
          )}

          {/* Description Section */}
          {description && (
            <div className="mt-2 flex-1 text-sm font-normal leading-[1.29] tracking-normal">
              <p
                dangerouslySetInnerHTML={{ __html: description }}
                className={textColor}
              />
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

