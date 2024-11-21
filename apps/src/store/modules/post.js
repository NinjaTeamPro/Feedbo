import ApiService from "@/services/ApiService";

export const namespaced = true;
export const state = {
  posts: [],
  post: {},
  listPost: [],
  postIndexLoad: 0,
  postActivity: [],
  tabActive: "",
  results: [],
  downVote: [],
  subscribed_ids: [],
  status: null,
  listVoter: [],
  loadingListPost: false,
  loadingRoadmap: false,
  isLoadingCreatePost: false,
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
  SET_POSTS(state, post) {
    state.posts = post;
  },
  ADD_POST(state, post) {
    if (state.tabActive == "vote") {
      state.posts.push(post);
      if (state.listPost.length <= 10) {
        state.listPost.push(post);
      }
    } else {
      state.posts.unshift(post);
      if (state.listPost.length <= 10) {
        state.listPost.unshift(post);
      }
    }
  },
  SET_POST(state, post) {
    state.post = post;
  },
  SET_LOADING_LIST_POST(state, loadingListPost) {
    state.loadingListPost = loadingListPost;
  },
  SET_LOADING_ROADMAP(state, loadingRoadmap) {
    state.loadingRoadmap = loadingRoadmap;
  },
  SET_POST_ACTIVITY(state, postActivity) {
    state.postActivity = postActivity;
  },
  SET_RESULTS(state, results) {
    state.results = results;
  },
  SET_TABACTIVE(state, key) {
    state.tabActive = key;
  },
  SET_STATUS(state, status) {
    if (status.length != 0) state.status = status;
  },
  UPDATE_USER_SUBSCRIBE(state, postUpdate) {
    let index = state.posts.indexOf(postUpdate.requestParams.post);
    state.posts[index].subscribe_ids = postUpdate.valueUpdate;
  },
  UNSUBSCRIBE_ALL_POST(state, userId) {
    state.posts.forEach((element) => {
      if (element.subscribe_ids.length > 0) {
        for (let i = 0; i < element.subscribe_ids.length; i++) {
          if (element.subscribe_ids[i] == userId) {
            element.subscribe_ids.splice(i, 1);
          }
        }
      }
    });
  },
  SET_DOWN_VOTE(state, downVote) {
    state.downVote = downVote;
  },
  SET_LIST_VOTER(state, listVoter) {
    state.listVoter = listVoter;
  },
  REVERT_UPATE_VOTE(state, postUpdate) {
    const index = state.posts.indexOf(postUpdate.post);
    state.posts[index].vote_ids = postUpdate.beforeVoteList;
    state.posts[index].down_vote_ids = postUpdate.beforeDownVoteList;
    const voteIds = postUpdate.beforeVoteList.split(" , ");
    const downVoteIds =
      postUpdate.beforeDownVoteList !== null
        ? postUpdate.beforeDownVoteList.split(" , ")
        : [];
    state.posts[index].vote_length = voteIds.length;
    state.posts[index].down_vote_length = downVoteIds.length;
  },
  UPDATE_VOTE(state, postUpdate) {
    let index = state.posts.indexOf(postUpdate.post);
    let voteIds =
      state.posts[index].vote_ids != null
        ? state.posts[index].vote_ids.split(" , ")
        : [];
    let downVoteIds =
      state.posts[index].down_vote_ids != null
        ? state.posts[index].down_vote_ids.split(" , ")
        : [];
    if (postUpdate.checkVote == false) {
      state.posts[index].vote_length = state.posts[index].vote_length + 1;
      voteIds.push(postUpdate.userVote);
      if (postUpdate.checkDownVote == true) {
        let downVoteIndex = downVoteIds.indexOf(postUpdate.userVote);
        if (downVoteIndex > -1) {
          downVoteIds.splice(downVoteIndex, 1);
          state.posts[index].down_vote_length =
            state.posts[index].down_vote_length - 1;
        }
      }
    } else {
      state.posts[index].vote_length = state.posts[index].vote_length - 1;
      let voteIndex =
        postUpdate.checkAnonymous == true
          ? voteIds.lastIndexOf(postUpdate.userVote)
          : voteIds.indexOf(postUpdate.userVote);
      voteIds.splice(voteIndex, 1);
    }
    state.posts[index].vote_ids =
      state.posts[index].vote_length != 1
        ? voteIds.join(" , ")
        : voteIds.join("");
    state.posts[index].down_vote_ids =
      state.posts[index].down_vote_length != 1
        ? downVoteIds.join(" , ")
        : downVoteIds.join("");
  },
  UPDATE_DOWN_VOTE(state, postUpdate) {
    let index = state.posts.indexOf(postUpdate.post);
    let voteIds =
      state.posts[index].vote_ids != null
        ? state.posts[index].vote_ids.split(" , ")
        : [];
    let downVoteIds =
      state.posts[index].down_vote_ids != null
        ? state.posts[index].down_vote_ids.split(" , ")
        : [];
    if (postUpdate.checkDownVote == false) {
      state.posts[index].down_vote_length =
        state.posts[index].down_vote_length + 1;
      downVoteIds.push(postUpdate.userVote);
      if (postUpdate.checkVote == true) {
        let voteIndex = voteIds.lastIndexOf(postUpdate.userVote);
        if (voteIndex > -1) {
          voteIds.splice(voteIndex, 1);
          state.posts[index].vote_length = state.posts[index].vote_length - 1;
        }
      }
    } else {
      state.posts[index].down_vote_length =
        state.posts[index].down_vote_length - 1;
      let downVoteIndex =
        postUpdate.checkAnonymous == true
          ? downVoteIds.lastIndexOf(postUpdate.userVote)
          : downVoteIds.indexOf(postUpdate.userVote);
      downVoteIds.splice(downVoteIndex, 1);
    }
    state.posts[index].vote_ids =
      state.posts[index].vote_length != 1
        ? voteIds.join(" , ")
        : voteIds.join("");
    state.posts[index].down_vote_ids =
      state.posts[index].down_vote_length != 1
        ? downVoteIds.join(" , ")
        : downVoteIds.join("");
  },
  UPDATE_VOTELIST(state, postUpdate) {
    if (postUpdate.checkVote == false) {
      state.listVoter.push({
        id: postUpdate.userVote,
        user_avatar: postUpdate.user.user_avatar,
        user_email: postUpdate.user.user_email,
        user_name: postUpdate.user.display_name,
      });
    } else {
      let voteIndex = -1;
      state.listVoter.forEach(function(item, index) {
        if (item.id == postUpdate.userVote) {
          voteIndex = index;
        }
      });
      if (voteIndex > -1) {
        state.listVoter.splice(voteIndex, 1);
      }
    }
  },
  UPDATE_VOTE_BEHALF(state, postUpdate) {
    let index = state.posts.indexOf(postUpdate);
    let voteIds =
      state.posts[index].vote_ids != null
        ? state.posts[index].vote_ids.split(" , ")
        : [];
    voteIds.push(0);
    state.posts[index].vote_ids = voteIds.join(" , ");
    state.posts[index].vote_length = state.posts[index].vote_length + 1;
  },
  UPDATE_CONTENT_POST(state, postUpdate) {
    state.posts.forEach((element) => {
      if (element.post_id == postUpdate.id) {
        element.post_content = postUpdate.value;
      }
    });
  },
  UPDATE_LIST_POST(state, listPost) {
    state.listPost = state.listPost.concat(listPost);
  },
  UPDATE_COMMENT_STATUS(state, postUpdate) {
    state.posts.forEach((element) => {
      if (element.post_id == postUpdate.requestParams.post.post_id) {
        postUpdate.action == "lock"
          ? (element.comment_status = "closed")
          : (element.comment_status = "open");
      }
    });
  },
  DELETE_POST(state, postDelete) {
    let index = state.posts.indexOf(postDelete.post);
    if (index > -1) {
      state.posts.splice(index, 1);
    }
    let listPostIndex = state.listPost.indexOf(postDelete.post);
    if (listPostIndex > -1) {
      state.listPost.splice(listPostIndex, 1);
      state.postIndexLoad = state.postIndexLoad - 1;
    }
  },
};
export const actions = {
  fetchPosts({ state, commit }, id) {
    if (state.tabActive === "vote") {
      ApiService.getMostVotePostByCategory(id)
        .then((response) => {
          commit("SET_POSTS", response.data);
        })
        .catch((error) => {
          console.log("There was an error:", error.response);
        });
    } else if (state.tabActive === "new") {
      ApiService.getNewPostByCategory(id)
        .then((response) => {
          commit("SET_POSTS", response.data);
        })
        .catch((error) => {
          console.log("There was an error:", error.response);
        });
    } else if (state.tabActive === "roadmap") {
      ApiService.getStatusByCategory(id)
        .then((response) => {
          commit("SET_POSTS", null);
          commit("SET_STATUS", response.data);
        })
        .catch((error) => {
          console.log("There was an error:", error.response);
        });
    }
  },
  changeTabActive({ commit }, { key, id }) {
    commit("SET_LOADING_LIST_POST", true);
    if (key === "vote") {
      ApiService.getMostVotePostByCategory(id)
        .then((response) => {
          commit("SET_POSTS", response.data);
          let listPost = [];
          let loadIndex = response.data.length < 10 ? response.data.length : 10;
          for (let index = 0; index < loadIndex; index++) {
            listPost.push(response.data[index]);
          }
          commit("SET_GENERNAL", {
            listPost: listPost,
            postIndexLoad: loadIndex,
          });
          commit("SET_LOADING_LIST_POST", false);
        })
        .then(commit("SET_TABACTIVE", "vote"))
        .catch((error) => {
          console.log("There was an error:", error.response);
          commit("SET_LOADING_LIST_POST", false);
        });
    } else if (key === "new") {
      ApiService.getNewPostByCategory(id)
        .then((response) => {
          commit("SET_POSTS", response.data);
          let listPost = [];
          let loadIndex = response.data.length < 10 ? response.data.length : 10;
          for (let index = 0; index < loadIndex; index++) {
            listPost.push(response.data[index]);
          }
          commit("SET_GENERNAL", {
            listPost: listPost,
            postIndexLoad: loadIndex,
          });
          commit("SET_LOADING_LIST_POST", false);
        })
        .then(commit("SET_TABACTIVE", "new"))
        .catch((error) => {
          console.log("There was an error:", error.response);
          commit("SET_LOADING_LIST_POST", true);
        });
    } else if (key === "roadmap") {
      commit("SET_LOADING_ROADMAP", true);
      ApiService.getStatusByCategory(id)
        .then((response) => {
          commit("SET_POSTS", response.data);
          commit("SET_STATUS", response.data);
          commit("SET_LOADING_ROADMAP", false);
        })
        .then(commit("SET_TABACTIVE", "roadmap"))
        .catch((error) => {
          console.log("There was an error:", error.response);
          commit("SET_LOADING_ROADMAP", false);
        });
    }
  },
  fetchDownVote({ commit }, id) {
    if (state.tabActive === "vote") {
      ApiService.getDownVoteMostVotePost(id)
        .then((response) => {
          commit("SET_DOWN_VOTE", response.data);
        })
        .catch((error) => {
          console.log("There was an error:", error.response);
        });
    } else if (state.tabActive === "new") {
      ApiService.getDownVoteNewPost(id)
        .then((response) => {
          commit("SET_DOWN_VOTE", response.data);
        })
        .catch((error) => {
          console.log("There was an error:", error.response);
        });
    }
  },
  createPost({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    commit("SET_GENERNAL", { isLoadingCreatePost: true });
    grecaptcha.ready(function() {
      grecaptcha
        .execute(FB_RECAPTCHA_KEY, { action: "submit" })
        .then(function(token) {
          requestParams.captcha = token;
          return ApiService.addPost(requestParams)
            .then((response) => {
              if (response.status == 200 && response.data) {
                commit("ADD_POST", response.data);
                commit("SET_GENERNAL", { isLoadingCreatePost: false });
                thisComp.visible = false;
                thisComp.form.resetFields();
                thisComp.$message.success("Add new post success");
              }
            })
            .catch((error) => {
              commit("SET_GENERNAL", { isLoadingCreatePost: false });
              thisComp.$message.error(error.response.data);
            });
        });
    });
  },
  updateVote({ state, commit }, requestParams) {
    const thisComp = requestParams.component;
    const beforeVoteList = requestParams.beforeVoteList;
    const beforeDownVoteList = requestParams.beforeDownVoteList;
    const post = requestParams.post;
    const updateVoteList = requestParams.updateVoteList;
    const beforeUpdateVoteList =
      updateVoteList !== false
        ? JSON.parse(JSON.stringify(requestParams.listVoter))
        : null;

    delete requestParams.component;
    delete requestParams.beforeVoteList;
    delete requestParams.updateVoteList;
    if (updateVoteList !== false) {
      commit("UPDATE_VOTELIST", updateVoteList);
    }
    grecaptcha.ready(function() {
      grecaptcha
        .execute(FB_RECAPTCHA_KEY, { action: "submit" })
        .then(function(token) {
          requestParams.captcha = token;
          return ApiService.postVote(requestParams)
            .then(function(response) {
              thisComp.$message.success(response.data);
            })
            .catch((error) => {
              // console.log('There was an error:', error.response)
              commit("REVERT_UPATE_VOTE", {
                post,
                beforeVoteList,
                beforeDownVoteList,
              });
              if (updateVoteList !== false) {
                commit("SET_GENERNAL", { listVoter: beforeUpdateVoteList });
              }
              thisComp.$message.error(error.response.data);
            });
        });
    });
  },
  updateDownVote({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    return ApiService.postDownVote(requestParams)
      .then(function(response) {
        thisComp.$message.success(response.data);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  searchPosts({ commit }, value) {
    ApiService.getSearchPosts(value)
      .then((response) => {
        commit("SET_RESULTS", response.data);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  getPostByID({ commit }, id) {
    return ApiService.getPostByID(id)
      .then((response) => {
        commit("SET_POST", response.data);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  getPostByActivity({ commit }, id) {
    return ApiService.getPostActivity(id)
      .then((response) => {
        commit("SET_POST_ACTIVITY", response.data);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  changePostStatus({ commit, state }, value) {
    const thisComp = value.component;
    delete value.component;
    return ApiService.changePostStatus(value)
      .then((response) => {
        commit("SET_POSTS", response.data.posts);
        thisComp.$message.success(response.data.message);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  deletePost({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    return ApiService.deletePost(requestParams)
      .then(() => {
        commit("DELETE_POST", requestParams);
        thisComp.$message.success("This post has been delete");
      })
      .catch((error) => {
        console.log("There was an error:", error);
      });
  },
  lockComment({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    return ApiService.lockComment(requestParams)
      .then(() => {
        let action = "lock";
        commit("UPDATE_COMMENT_STATUS", { requestParams, action });
        thisComp.$message.success("Comment has been lock");
      })
      .catch((error) => {
        console.log("There was an error:", error);
      });
  },
  unLockComment({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    return ApiService.unLockComment(requestParams)
      .then(() => {
        let action = "unlock";
        commit("UPDATE_COMMENT_STATUS", { requestParams, action });
        thisComp.$message.success("Unlock comment");
      })
      .catch((error) => {
        console.log("There was an error:", error);
      });
  },
  fetchUserSubscribe({ commit }, id) {
    return ApiService.getUserSubscribe(id)
      .then((response) => {
        commit("UPDATE_USER_SUBSCRIBE", response.data);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  updateUserSubscribe({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    return ApiService.updateUserSubscribe(requestParams)
      .then((response) => {
        let valueUpdate = response.data;
        commit("UPDATE_USER_SUBSCRIBE", { requestParams, valueUpdate });
        if (requestParams.checkSubscribe == false)
          thisComp.$message.success("Subscribed this post success");
        else thisComp.$message.success("This post has been unsubscribe");
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  unsubscribedAllPost({ commit }, userId) {
    return ApiService.unsubscribedAllPost()
      .then(() => {
        commit("UNSUBSCRIBE_ALL_POST", userId);
      })
      .catch((error) => {
        console.log("There was an error:", error);
      });
  },
  listVoter({ commit }, post) {
    return ApiService.fetchVoter(post)
      .then((response) => {
        commit("SET_LIST_VOTER", response.data);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  addUserBehalf({ commit }, requestParams) {
    const thisComp = requestParams.component;
    delete requestParams.component;
    return ApiService.addUserBehalf(requestParams)
      .then((response) => {
        commit("SET_LIST_VOTER", response.data.voters);
        thisComp.$message.success(response.data.message);
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  exportListVoter({ commit }, post) {
    return ApiService.exportListVoter(post)
      .then((response) => {
        var headers = response.data.title;
        var data = response.data.data;
        var filename = response.data.filename;

        var csv = headers + "\n";
        data.forEach(function(row) {
          csv += row.join(",");
          csv += "\n";
        });

        var hiddenElement = document.createElement("a");
        hiddenElement.href = "data:text/csv;charset=utf-8," + encodeURI(csv);
        hiddenElement.target = "_blank";
        hiddenElement.download = filename + ".csv";
        document
          .getElementById("export_email_voter")
          .appendChild(hiddenElement);
        hiddenElement.click();
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
  exportListSubscriber({ commit }, post) {
    return ApiService.exportListSubscriber(post)
      .then((response) => {
        var headers = response.data.title;
        var data = response.data.data;
        var filename = response.data.filename;

        var csv = headers + "\n";
        data.forEach(function(row) {
          csv += row.join(",");
          csv += "\n";
        });

        var hiddenElement = document.createElement("a");
        hiddenElement.href = "data:text/csv;charset=utf-8," + encodeURI(csv);
        hiddenElement.target = "_blank";
        hiddenElement.download = filename + ".csv";
        document
          .getElementById("export_email_subscriber")
          .appendChild(hiddenElement);
        hiddenElement.click();
      })
      .catch((error) => {
        console.log("There was an error:", error.response);
      });
  },
};
