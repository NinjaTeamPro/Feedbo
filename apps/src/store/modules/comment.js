import ApiService from '@/services/ApiService'

export const namespaced = true
export const state = { 
  comments: [],
  userslike:[],
  commentImages: [],
  commentImgUrl: [],
  commentImgUpdate: [],
  isLoadingComment: false,
  isLoadingAddComment: false,
  isLoadingEditComment: false,
  postId: null,
}
export const mutations = {
  SET_COMMENTS(state, comment) {
    state.comments = comment
  },
  SET_USERSLIKE(state, userslike) {
    state.userslike = userslike
  },
  SET_COMMENT_IMAGE(state, commentImages) {
    state.commentImages = commentImages
  },
  ADD_COMMENT(state, comment) {
    state.comments.push(comment)
  },
  DELETE_COMMENT(state, index) {
    state.comments.splice(index, 1)
  },
  ADD_USERSLIKE(state, userslike) {
    state.userslike.push(userslike)
  },
  ADD_COMMENT_IMAGE(state, commentImages) {
    state.commentImages.push(commentImages)
  },
  SET_COMMENTIMG(state,url) {
    state.commentImgUrl = url
  },
  SET_IMGUPDATE(state,commentImgUpdate) {
    state.commentImgUpdate = commentImgUpdate
  },
  SET_GENERNAL(state, data) {
    for (var key in data) {
      if (data.hasOwnProperty(key)) {
        let value = data[key];
        state[key] = value;
      }
    }
  },
  UPDATE_LIKE(state,commentUpdate) {
    let index = state.comments.indexOf(commentUpdate.comment);
    let userLikeIds = state.comments[index].users_like != null ? state.comments[index].users_like.split(" , "): [];
    if(commentUpdate.checkLike == false) {
      state.comments[index].userslike_length = state.comments[index].userslike_length + 1;
      userLikeIds.push(commentUpdate.userLike);
    } else {
      state.comments[index].userslike_length = state.comments[index].userslike_length - 1;
      let likeIndex = commentUpdate.checkAnonymous == true ? userLikeIds.lastIndexOf(commentUpdate.userLike) : userLikeIds.indexOf(commentUpdate.userLike);
      userLikeIds.splice(likeIndex,1);
    }
    state.comments[index].users_like = state.comments[index].userslike_length == 1 ? commentUpdate.userLike :  userLikeIds.join(" , ");
    ;
  },
}
export const actions = {
    fetchComments({ state, commit }, id) {
      if(id !== state.postId) {
        commit("SET_GENERNAL", {isLoadingComment: true})
        ApiService.getCommentsByPost(id)
        .then(function(response) {
            if (response.status == 200 && response.data) {
              commit("SET_GENERNAL", {
                comments: response.data.data.comments,
                postId: id,
                isLoadingComment: false
              });
              commit("post/SET_GENERNAL", {
                listVoter: response.data.data.listVoter,
                subscribed_ids: response.data.data.listSubscribe
              }, { root: true });
            }
          })
        .catch(error => {
            commit("SET_GENERNAL", {isLoadingComment: false})
            console.log('There was an error:', error.response);
        })
      } 
    },
    fetchUsersLike({ commit }, id) {
      ApiService.getUsersLikeByPost(id)
          .then(response => {
          commit('SET_USERSLIKE', response.data)
          })
          .catch(error => {
          console.log('There was an error:', error.response)
          })
    },
    fetchCommentImages({ commit }, id) {
    ApiService.getCommentImagesByPost(id)
        .then(response => {
        commit('SET_COMMENT_IMAGE', response.data)
        })
        .catch(error => {
        console.log('There was an error:', error.response)
        })
    },
    updateUsersLike({ commit }, requestParams) {
      const thisComp = requestParams.component;
      delete requestParams.component;
        return ApiService.updateUsersLike(requestParams)
        .then(function(response) {
          thisComp.$message.success(response.data);
        })
        .catch(error => {
          console.log('There was an error:', error.response)
        })
    },
    createComments({commit,state}, comment) {
      const thisComp = comment.component;
      delete comment.component;
      commit("SET_GENERNAL", {isLoadingAddComment: true})
      grecaptcha.ready(function () {
        grecaptcha.execute(mv_recaptcha_key, { action: 'submit' }).then(function (token) {
          comment.captcha = token
          return ApiService.addComment(comment,state.commentImgUrl).then((response) => {
            commit("SET_GENERNAL", {
              comments: response.data.comments ,
              isLoadingAddComment: false
            });
            thisComp.$message.success(response.data.message);
          })
          .catch(error => {
            // console.log('There was an error:', error.response)
            commit("SET_GENERNAL", {
              comments: state.comments.splice(state.comments.length - 1, 1),
              isLoadingAddComment: false
            });
            thisComp.$message.error(error.response.data.message);
          })
        })
      })
    },
    updateComment({commit}, comment) {
      const thisComp = comment.component;
      delete comment.component;
      commit('SET_GENERNAL', {isLoadingEditComment: true})
      return ApiService.updateComment(comment).then((response) => {
        commit("SET_GENERNAL", {
          comments: response.data.comments,
          isLoadingEditComment: false
        });
        if( response.data.isPost == true) {
          commit("post/UPDATE_CONTENT_POST", {
            id: comment.postId,
            value: comment.values
          }, { root: true });
        }
        thisComp.$message.success(response.data.message);
      })
      .catch(error => {
        console.log('There was an error:', error.response)
        commit("SET_GENERNAL", {isLoadingEditComment: false})
      })
   },
   deleteComment({commit},data) {
    const thisComp = data.component;
    delete data.component;
    return ApiService.deleteComment(data).then((response) => {
      commit("SET_GENERNAL", {
        comments: response.data.comments,
      });
      thisComp.$message.success(response.data.message);
    })
    .catch(error => {
      console.log('There was an error:', error.response)
    })
   },
  imgupdate({commit}, {data,config}) {
    return ApiService.uploadimg(data,config)
    .then(response => {
      commit('SET_IMGUPDATE', response.data)
      })
      .catch(error => {
      console.log('There was an error:', error.response)
      })
  },     
}
