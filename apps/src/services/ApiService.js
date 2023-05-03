import axios from 'axios'
const wpData = window.bigNinjaVoteWpdata;
if (!wpData) throw new Error("wpData is required");
const axiosUrl  = wpData.axiosUrl;
const { apiNonce } = wpData;
const apiClient = axios.create({
  baseURL: axiosUrl,
  withCredentials: false, // This is the default
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'X-WP-Nonce': apiNonce
  }
})

export default {
  postVote(post) {
    return apiClient.post('/v1/wp_add_vote', post)
  },
  postDownVote(post) {
    return apiClient.post('/v1/wp_add_down_vote', post)
  },
  getNewPostByCategory(id) {
    return apiClient.get(`/v1/wp_get_new_post_by_category?id=${id}`)
  },
  getMostVotePostByCategory(id) {
    return apiClient.get(`/v1/wp_get_most_vote_post_by_category?id=${id}`)
  },
  getDownVoteMostVotePost(id) {
    return apiClient.get(`/v1/wp_get_down_vote_most_vote_post?id=${id}`)
  },
  getDownVoteNewPost(id) {
    return apiClient.get(`/v1/wp_get_down_vote_new_post?id=${id}`)
  },
  getStatusByCategory(id) {
    return apiClient.get(`/v1/wp_get_status_by_category?id=${id}`)
  },
  addPost(post) {
     return apiClient.post('/v1/wp_add_post', post)
  },
  getCommentsByPost(id) {
    return apiClient.get(`/v1/wp_get_comments?id=${id}`)
  },
  getUsersLikeByPost(id) {
    return apiClient.get(`/v1/wp_get_users_like_by_post_id?id=${id}`)
  },
  getUserSubscribe(id) {
    return apiClient.get(`/v1/wp_get_user_subscribe?id=${id}`)
  },
  unsubscribedAllPost(){
    return apiClient.post('/v1/wp_unsubscribed_all_post')
  },
  updateUserSubscribe(id) {
    return apiClient.post('/v1/wp_update_user_subscribe',id)
  },
  getCommentImagesByPost(id) {
    return apiClient.get(`/v1/wp_get_comment_images_by_post_id?id=${id}`)
  },
  addComment(comment,img) {
  return apiClient.post('/v1/wp_add_comment', {comment,img})
  },
  addCategory(category) {
    return apiClient.post('/v1/wp_add_category', category)
  },
  getSearchPosts(value) {
    return apiClient.get(`/v1/wp_get_search_posts?id=${value.id}&search=${value.text}`)
  },
  getCurrentUser() {
    return apiClient.get('/v1/wp_get_current_user')
  },
  getCurrentUserAvatar() {
    return apiClient.get('/v1/wp_get_current_user_avatar')
  },
  getNotification() {
    return apiClient.get('/v1/wp_get_notification')
  },
  getUserNotification() {
    return apiClient.get('/v1/wp_get_user_notification')
  },
  updateUserNotification(requestParams) {
    return apiClient.post('/v1/wp_update_user_notification',requestParams)
  },
  updateUserAccount(requestParams) {
    return apiClient.post('/v1/wp_update_user_account',requestParams)
  },
  updateComment(comment){
    return apiClient.post('/v1/wp_update_comment',comment)
  },
  deleteComment(comment){
    return apiClient.post('/v1/wp_delete_comment',comment)
  },
  updateUsersLike(comment){
    return apiClient.post('/v1/wp_update_users_like', comment)
  },
  getPostByID(id){
    return apiClient.get(`/v1/wp_get_post_by_id?id=${id}`)
  },
  changePostStatus(value){
    return apiClient.post('/v1/wp_update_post_status',{value})
  },
  uploadimg(data,config){
    return apiClient.post(`/v1/wp_upload_file`,data,config)
  },
  deletePost(post){
    return apiClient.post('/v1/wp_delete_post',post)
  },
  lockComment(post){
    return apiClient.post('/v1/wp_lock_comment_post',post)
  },
  unLockComment(post){
    return apiClient.post('/v1/wp_unlock_comment_post',post)
  },
  deleteTerm(id){
    return apiClient.post('/v1/wp_delete_term',id)
  },
  getActivity(id){
    return apiClient.get(`/v1/wp_get_activity_by_category?id=${id}`)
  },
  updateBoardInfo(values){
    return apiClient.post('/v1/wp_update_termmeta',values)
  },
  updateStatusColor(requestParams){
    return apiClient.post('/v1/wp_update_status_color', requestParams)
  },
  updateAppearance(requestParams){
    return apiClient.post('/v1/wp_update_appearance',requestParams)
  },
  inviteTeam(values){
    return apiClient.post('/v1/wp_add_user', values)
  },
  getPostActivity(id){
    return apiClient.get(`/v1/wp_get_post_activity?id=${id}`)
  },
  createUser(requestParams){
    return apiClient.post('/v1/wp_create_user',requestParams)
  },
  getUserTeam(id){
    return apiClient.get(`/v1/wp_get_term_users?id=${id}`)
  },
  getUserAvatarTeam(id){
    return apiClient.get(`/v1/wp_get_term_users_avatar?id=${id}`)
  },
  changeRole(requestParams){
    return apiClient.post('/v1/wp_change_role',requestParams)
  },
  fetchVoter(post){
    return apiClient.get(`/v1/wp_get_list_vote?post=${post}`)
  },
  addUserBehalf(user){
    return apiClient.post('/v1/wp_add_user_upvote',user)
  },
  exportListVoter(post){
    return apiClient.get(`/v1/wp_get_export_vote_list?post=${post}`)
  },
  exportListSubscriber(post){
    return apiClient.get(`/v1/wp_get_export_subscribed_list?post=${post}`)
  },
  getBoardContent(requestParams) {
    return apiClient.get(`/v1/wp_get_board_content?id=${requestParams}`);
  },
  getPosts(requestParams) {
    return apiClient.get(`/v1/wp_get_posts?id=${requestParams}`);
  }
}
