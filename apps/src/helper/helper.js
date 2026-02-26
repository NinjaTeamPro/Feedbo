const getBoardIdFromUrl = () => {
  const currentPath = window.location.pathname;
  const lastTextCurrentPath = currentPath.substring(
    currentPath.length - 1,
    currentPath.length
  );
  const defineUrlBoard = window.bigNinjaVoteWpdata.defineUrlBoard;
  let boardId = '';
  if (lastTextCurrentPath === "/") {
    boardId = currentPath.substring(defineUrlBoard.length, currentPath.length - 1);
  } else {
    boardId = currentPath.substring(defineUrlBoard.length, currentPath.length);
  }
  const commentIndex = boardId.indexOf('/');
  if ( commentIndex > -1 ) {
    return boardId.substring(0, commentIndex);
  }
  return boardId;
};

const setCookie = (key, value, expiry) => {
  const expires = new Date();
  expires.setTime(expires.getTime() + expiry * 24 * 60 * 60 * 1000);
  const cookieString = key + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
  document.cookie = cookieString;
  // Return a Promise that resolves on next tick so the browser has time to persist the cookie before redirect
  return new Promise((resolve) => {
    setTimeout(resolve, 0);
  });
};


export { getBoardIdFromUrl, setCookie };
