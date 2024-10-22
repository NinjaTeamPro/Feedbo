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

export { getBoardIdFromUrl };
