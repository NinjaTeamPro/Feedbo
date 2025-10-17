import jQuery from '@types/jquery';

declare global {
  interface Window {
    jQuery: typeof jQuery;
    feedbo: {
      axiosUrl: string;
      pluginUrl: string;
      siteUrl: string;
      siteName: string;
      logoutUrl: string;
      apiNonce: string;
      defineUrlBoard: string;
    };
  }
}

export {};
