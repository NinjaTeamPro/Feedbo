import ApiService from "@/services/ApiService";

export const namespaced = true;
export const state = {
  isLoadingBoard: false,
  isLoadingComplete: false,
};
export const mutations = {
  SET_GENERNAL(state, data) {
    for (var key in data) {
      if (data.hasOwnProperty(key)) {
        let value = data[key];
        state[key] = value;
      }
    }
  },
};
export const actions = {
  getBoardContent({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    commit("SET_GENERNAL", { isLoadingBoard: false });
    ApiService.getBoardContent(requestParams.boardId)
      .then(function(response) {
        if (response.status == 200 && response.data) {
          commit(
            "category/SET_GENERNAL",
            {
              board: response.data.data.boardInfo.setting,
              theme: response.data.data.boardInfo.theme,
              rootTheme: response.data.data.boardInfo.theme,
              status: response.data.data.boardInfo.status,
              logoimg: response.data.data.boardInfo.logo_favicon.logo,
              faviconimg: response.data.data.boardInfo.logo_favicon.favicon,
              userCreate: response.data.data.boardInfo.user_created,
              userTeam: response.data.data.userInBoard,
              allUserAvatar: response.data.data.avatarAllUser,
              listAllBoard: response.data.data.urlAllBoard,
            },
            { root: true }
          );
          document.body.style.backgroundColor =
            response.data.data.boardInfo.theme.background;
          commit(
            "post/SET_GENERNAL",
            {
              // posts: response.data.data.listPost,
              tabActive: response.data.data.boardInfo.setting.default_sort,
            },
            { root: true }
          );
          commit("user/SET_USER", response.data.data.currentUser, {
            root: true,
          });
          commit(
            "user/SET_CURRENT_LEVEL",
            response.data.data.currentUserLevel,
            { root: true }
          );
          commit("SET_GENERNAL", {
            isLoadingBoard: false,
            isLoadingComplete: true,
          });
          thisComp.renderBoard();
        }
      })
      .catch((error) => {
        commit("SET_GENERNAL", { isLoadingBoard: false });
        console.log("There was an error:", error.response);
      });
  },
  getPosts({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    commit(
      "post/SET_GENERNAL",
      {
        posts: [],
        listPost: [],
        postIndexLoad: 0,
        loadingListPost: true,
      },
      { root: true }
    );
    ApiService.getPosts(requestParams.boardId)
      .then(function(response) {
        if (response.status == 200 && response.data) {
          commit(
            "post/SET_GENERNAL",
            {
              posts: response.data.data,
            },
            { root: true }
          );
          thisComp.fetchData();
          setTimeout(() => {
            commit("post/SET_LOADING_LIST_POST", false, { root: true });
          }, 1000);
        }
      })
      .catch((error) => {
        commit("post/SET_LOADING_LIST_POST", false, { root: true });
        console.log("There was an error:", error.response);
      });
  },
};
