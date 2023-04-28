<template>
  <div>
    <div v-if="user.currentLevel < 3" class="button-lock-delete">
      <ADropdown>
        <AMenu slot="overlay" style="width:170px;">
          <AMenuItem
            v-if="checkLock == false"
            key="lock"
            @click="lockComment(postItem)"
          >
            <span>Lock</span>
          </AMenuItem>
          <AMenuItem
            v-if="checkLock == true"
            key="unlock"
            @click="unLockComment(postItem)"
          >
            <span>UnLock</span>
          </AMenuItem>
          <AMenuItem key="export-voter" @click="exportListVoter">
            <span>Export Voter</span>
            <div id="export_email_voter" style="display:none;" />
          </AMenuItem>
          <AMenuItem
            v-if="user.currentLevel < 3"
            key="export-subscriber"
            @click="exportListSubscriber"
          >
            <span>Export Subscriber</span>
            <div id="export_email_subscriber" style="display: none;" />
          </AMenuItem>
          <AMenuItem key="delete" @click="deletePost(postItem)">
            <span>Delete</span>
          </AMenuItem>
          <AMenuItem
            v-if="!user.userAnonymous && displayMobile()"
            key="close"
            @click="closeComment()"
          >
            <span>Close</span>
          </AMenuItem>
        </AMenu>
        <AButton
          class="feedbo-simple-button"
          icon="more"
          style="border: none; box-shadow: none; font-size: 20px;"
        />
      </ADropdown>
    </div>
    <Voter
      v-if="user.currentLevel < 4"
      :post-item="postItem"
      @changeUpvoted="changeUpvoted()"
    />
    <template>
      <div class="post-status-container">
        <div class="post-status" :style="{ color: category.theme.text }">
          Status:
        </div>
        <div class="status">
          <ADropdown
            :trigger="['click']"
            :disabled="checkChangeStatus() == false"
          >
            <AMenu slot="overlay" :style="{ color: category.theme.text }">
              <AMenuItem v-for="(value, name) in category.status" :key="name">
                <div>
                  <div>
                    <ABadge
                      style="width:100px;"
                      :color="value"
                      :text="name"
                      @click.prevent="handleChange(postItem, name)"
                    />
                    <AIcon
                      type="more"
                      style="margin-left:50px;"
                      @click.prevent="showModalColor(value, name)"
                    />
                  </div>
                </div>
              </AMenuItem>
            </AMenu>
            <AButton class="status-button template-button ant-btn-custom">
              <span
                :style="[
                  postItem.post_status == 'Unassigned'
                    ? { display: 'none' }
                    : { display: 'inline-block' },
                ]"
              >
                <ABadge :color="statusColor()" />
              </span>
              <span>{{ postItem.post_status }}</span>
            </AButton>
          </ADropdown>
          <AModal
            title="Edit status"
            centered
            :visible="modalColor"
            :confirm-loading="confirmLoading"
            @ok="modalColorOk"
            @cancel="modalColorCancel"
          >
            <h3 v-if="statusChange.name">
              {{ statusChange.name }}
            </h3>
            <div class="layout-wrap">
              <div class="layout">
                <div class="layout-table">
                  <div
                    class="item-color"
                    style="background: #f50;"
                    @click="changeColor(statusChange.name, '#f50')"
                  >
                    <span
                      v-if="statusChange.color == '#f50'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #ff0000;"
                    @click="changeColor(statusChange.name, '#ff0000')"
                  >
                    <span
                      v-if="statusChange.color == '#ff0000'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #a901db;"
                    @click="changeColor(statusChange.name, '#a901db')"
                  >
                    <span
                      v-if="statusChange.color == '#a901db'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #fe2ef7;"
                    @click="changeColor(statusChange.name, '#fe2ef7')"
                  >
                    <span
                      v-if="statusChange.color == '#fe2ef7'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #87d068;"
                    @click="changeColor(statusChange.name, '#87d068')"
                  >
                    <span
                      v-if="statusChange.color == '#87d068'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #2db7f5;"
                    @click="changeColor(statusChange.name, '#2db7f5')"
                  >
                    <span
                      v-if="statusChange.color == '#2db7f5'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #108ee9;"
                    @click="changeColor(statusChange.name, '#108ee9')"
                  >
                    <span
                      v-if="statusChange.color == '#108ee9'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #f7fe2e;"
                    @click="changeColor(statusChange.name, '#f7fe2e')"
                  >
                    <span
                      v-if="statusChange.color == '#f7fe2e'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #a4a4a4;"
                    @click="changeColor(statusChange.name, '#a4a4a4')"
                  >
                    <span
                      v-if="statusChange.color == '#a4a4a4'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                  <div
                    class="item-color"
                    style="background: #000;"
                    @click="changeColor(statusChange.name, '#000')"
                  >
                    <span
                      v-if="statusChange.color == '#000'"
                      class="itemActive"
                    >
                      <AIcon type="check" />
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </AModal>
        </div>
        <div
          class="comment-button-vote"
          :class="{ 'allow-downvote': checkDownVote() }"
        >
          <AButton
            :loading="loading"
            class="template-button"
            :type="checkTheme()"
            :class="{ 'button-comment': checkTheme() == '' }"
            @click.prevent="handleVoteClick(postItem)"
          >
            <span class="vote-icon">
              <template v-if="checkAnonymousVote(postItem) === true">
                <AIcon type="check" />
              </template>
              <template v-else>
                <AIcon type="caret-up" />
              </template>
            </span>
            <span class="vote-count"> Upvoted {{ postItem.vote_length }} </span>
          </AButton>
          <AButton
            v-if="checkDownVote() === true"
            :loading="loadingDownVote"
            class="template-button"
            :type="checkTheme()"
            :class="{ 'button-comment': checkTheme() == '' }"
            @click.prevent="handleDownVoteClick(postItem)"
          >
            <span class="vote-icon">
              <template v-if="checkAnonymousDownVote(postItem) === true">
                <AIcon type="check" />
              </template>
              <template v-else>
                <AIcon type="caret-down" />
              </template>
            </span>
            <span class="vote-count">
              Downvoted {{ postItem.down_vote_length }}
            </span>
          </AButton>
        </div>
      </div>
    </template>
    <AList
      class="comment-list"
      item-layout="horizontal"
      :data-source="comment.comments"
      :style="{ color: category.theme.text }"
    >
      <AListItem slot="renderItem" slot-scope="item, index">
        <AComment>
          <p slot="author" :style="{ color: category.theme.text }">
            <span v-if="item.comment_author != ''">
              {{ item.comment_author }}
            </span>
            <span v-else>
              Anonymous
            </span>
          </p>
          <p slot="avatar">
            <AAvatar
              v-if="
                item.comment_author == '' || item.comment_author == 'Anonymous'
              "
              size="large"
              icon="user"
            />
            <img v-else :src="avatarCommentAuthor(item.comment_author)" />
          </p>
          <div
            slot="content"
            class="comment-content-text"
            :style="{ color: category.theme.text }"
          >
            {{ item.comment_content }}
          </div>
          <div>
            <a
              v-for="(image, index) in item.comment_image"
              :key="index"
              :href="image"
              class="comment-image-list"
              title="Image"
              target="_blank"
            >
              <img :src="image" alt="avatar" class="comment-image" />
            </a>
          </div>
          <p slot="datetime" :style="{ color: category.theme.text }">
            <ATooltip>
              <template slot="title">
                <span style="font-size: 10px;">
                  {{ item.comment_date }}
                </span>
              </template>
              <span>{{ item.comment_date_format }}</span>
            </ATooltip>
            <span
              key="comment-basic-like"
              style="padding-left: 10px; padding-right:5px;"
            >
              <ATooltip title="Like">
                <AIcon
                  type="heart"
                  :class="{ islike: checkLike(index) }"
                  :theme="checkLike(index) === true ? 'filled' : 'outlined'"
                  @click="like(index)"
                />
              </ATooltip>
              <span style="padding-left: '8px';cursor: 'auto'">
                {{ item.userslike_length }}
              </span>
            </span>
            <ADropdown v-show="checkUser(item.comment_author)">
              <AMenu slot="overlay" style="width:100px;">
                <AMenuItem key="edit-comment" @click="hide(item)">
                  <span>Edit</span>
                </AMenuItem>
              </AMenu>
              <AIcon type="more" />
            </ADropdown>
          </p>
          <AModal v-model="visibleUpdate" centered>
            <template slot="footer">
              <div class="modal-update-button">
                <AButton key="back" class="button-attach" @click="handleCancel">
                  Cancel
                </AButton>
                <AButton
                  key="submit"
                  :class="{ 'button-comment': checkTheme() == '' }"
                  type="primary"
                  :loading="comment.isLoadingEditComment"
                  @click="handleUpdate(item)"
                >
                  Save changes
                </AButton>
              </div>
            </template>
            <br />
            <AMentions
              v-model="inputUpdate"
              rows="4"
              placeholder="Leave a comment"
              class="feedbo-input"
            >
              <AMentionsOption
                v-for="(item, index) in userList"
                :key="index"
                :value="item"
              >
                {{ item }}
              </AMentionsOption>
            </AMentions>
            <div v-for="comment in comment.comments" :key="comment.comment_ID">
              <div v-if="comment.comment_ID == id" style="margin-top:10px;">
                <div
                  v-for="(image, index) in comment.comment_image"
                  :key="index"
                  class="comment-image-list comment-image-list-edit"
                >
                  <img :src="image" alt="avatar" class="comment-image" />
                  <div
                    class="upload-image-text"
                    @click="deleteimgUpload(image)"
                  >
                    <p>
                      <ATooltip title="Delete Image" placement="bottom">
                        <AIcon type="delete" />
                      </ATooltip>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <AUpload
              accept="image/*"
              name="file"
              :multiple="true"
              :action="linkUpload"
              list-type="picture"
              class="upload-list-inline"
              :before-upload="beforeUpload"
              @change="updateImage"
            >
              <AButton class="template-button button-attach">
                <AIcon type="upload" /> Upload image
              </AButton>
            </AUpload>
          </AModal>
        </AComment>
      </AListItem>
    </AList>
    <div>
      <AForm :form="form" @submit.prevent="handleSubmit(postItem)">
        <AFormItem style="margin-bottom: 7px;">
          <AMentions
            v-decorator="[
              'comment',
              {
                rules: [
                  { required: true, message: 'This is a required field' },
                ],
              },
            ]"
            rows="4"
            placeholder="Leave a comment"
            :disabled="checkLock"
            class="feedbo-input"
          >
            <AMentionsOption
              v-for="(item, index) in userList"
              :key="index"
              :value="item"
            >
              {{ item }}
            </AMentionsOption>
          </AMentions>
        </AFormItem>
        <AFormItem class="submit-button-wrap">
          <div
            class="button-upload"
            :style="[
              form.getFieldValue('file') == undefined ||
              Object.keys(form.getFieldValue('file')).length === 0
                ? { display: 'inline-block' }
                : { display: 'block', marginBottom: '10px' },
            ]"
          >
            <AUpload
              v-decorator="[
                'file',
                {
                  valuePropName: 'fileList',
                  getValueFromEvent: normFile,
                },
              ]"
              name="file"
              accept="image/*"
              :multiple="true"
              :action="linkUpload"
              list-type="picture"
              class="upload-list-inline"
              :before-upload="beforeUpload"
              @change="handelUpload"
            >
              <AButton
                class="template-button button-attach"
                :disabled="checkLock"
              >
                <AIcon type="upload" /> Upload image
              </AButton>
            </AUpload>
          </div>
          <div class="button-add-comment">
            <AButton
              :type="checkTheme()"
              class="template-button"
              :class="{ 'button-comment': checkTheme() == '' }"
              :loading="comment.isLoadingAddComment"
              html-type="submit"
              :disabled="checkLock"
            >
              Comment
            </AButton>
          </div>
        </AFormItem>
      </AForm>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import { mapState } from "vuex";
import Voter from "@/components/Voter.vue";
import { getBoardIdFromUrl } from "@/helper/helper.js";
export default {
  components: {
    Voter,
  },
  props: {
    postItem: Object,
  },
  data() {
    return {
      statusChange: {},
      modalColor: false,
      confirmLoading: false,
      id: null,
      inputUpdate: "",
      visibleUpdate: false,
      loading: false,
      form: this.$form.createForm(this, { name: "coordinated" }),
      loading1: false,
      moment,
      countVote: [],
      uploadFileList: [],
      linkUpload: "",
      userList: [],
      countDownVote: [],
      loadingDownVote: false,
    };
  },
  computed: {
    ...mapState(["category", "post", "comment", "user"]),
    setcountVote() {
      if (this.postItem.vote_ids == "") {
        this.countVote = [];
      } else {
        this.countVote = this.postItem.vote_ids.split(" , ");
      }
      return this.countVote;
    },
    setcountDownVote() {
      if (this.postItem.down_vote_ids == "") {
        this.countDownVote = [];
      } else {
        this.countVote = this.postItem.down_vote_ids.split(" , ");
      }
      return this.countDownVote;
    },
    setStatus() {
      if (this.post.post.post_status == "") {
        return "Unassigned";
      } else {
        return this.post.post.post_status;
      }
    },
    checkLock() {
      if (this.postItem.comment_status == "closed") {
        return true;
      } else {
        return false;
      }
    },
  },
  mounted() {
    this.setUserList();
    this.linkUpload = window.bigNinjaVoteWpdata.axiosUrl + "/v1/wp_upload_file";
  },
  methods: {
    setUserList() {
      const arr = [];
      this.category.userTeamAvatar.forEach((item) => {
        if (item.user_name != null) {
          arr.push(item.user_name);
        }
      });
      this.userList = arr;
    },
    checkTheme() {
      if (this.category.theme.accent == "#1890ff") {
        return "primary";
      } else {
        if (this.category.theme.accent == "#ff4d4f") {
          return "danger";
        } else {
          return "";
        }
      }
    },
    onOpen(key) {
      if (key === "@") {
        this.items = this.category.userTeamAvatar;
      }
    },
    beforeUpload(file) {
      const isJpgOrPng =
        file.type === "image/jpeg" || file.type === "image/png";
      if (!isJpgOrPng) {
        this.$message.error("You can only upload image file!", 5);
      }
      const isLt2M = file.size / 1024 / 1024 < 5;
      if (!isLt2M) {
        this.$message.error("Image exceeds the maximum file size 5MB!", 5);
      }
      return isJpgOrPng && isLt2M;
    },
    handelUpload(info) {
      if (info.file.hasOwnProperty("status")) {
        if (info.file.status === "uploading") {
          return;
        }
        if (info.file.status === "done") {
          this.$message.success(`${info.file.name} file uploaded successfully`);
        } else if (info.file.status === "error") {
          this.$message.error(`${info.file.name} file upload failed.`);
        }
      } else {
        info.fileList.forEach((file, index) => {
          if (!file.hasOwnProperty("status")) {
            info.fileList.splice(index, 1);
          }
        });
      }
    },
    updateImage({ file, fileList }) {
      if (file.status === "uploading") {
        return;
      }
      if (file.status === "done") {
        this.$message.success(`${file.name} file uploaded successfully`);
        this.uploadFileList = fileList;
      } else if (file.status === "error") {
        this.$message.error(`${file.name} file upload failed.`);
      }
    },
    avatarCommentAuthor(commentAuthor) {
      let avatar;
      this.category.allUserAvatar.forEach((item) => {
        if (commentAuthor == item.user_name) {
          avatar = item.user_avatar;
        }
      });
      return avatar;
    },
    checkOwnerBoard() {
      if (
        this.category.category[0].meta_value != this.user.user.ID.toString()
      ) {
        return true;
      } else {
        return false;
      }
    },
    handleLockComment(post) {
      const requestParams = {
        post: post,
        component: this,
      };
      this.$store.dispatch("post/lockComment", requestParams);
      this.$emit("updateCommentStatus", "closed");
    },
    handleUnLockComment(post) {
      const requestParams = {
        post: post,
        component: this,
      };
      this.$store.dispatch("post/unLockComment", requestParams);
      this.$emit("updateCommentStatus", "open");
    },
    handleDeletePost(post) {
      const requestParams = {
        post: post,
        component: this,
      };
      this.$store.dispatch("post/deletePost", requestParams);
      this.$emit("deletePost");
    },
    lockComment(post) {
      this.$confirm({
        title: "Are you sure you want to lock this comment?",
        content:
          "It will become impossible to add new comments.You can always unlock this conversation again in the future.",
        okText: "Lock",
        cancelText: "Cancel",
        centered: true,
        onOk: () => this.handleLockComment(post),
      });
    },
    unLockComment(post) {
      this.$confirm({
        title: "Are you sure you want to unlock this comment?",
        content: "It will allow users to comment",
        okText: "Unlock",
        cancelText: "Cancel",
        centered: true,
        onOk: () => this.handleUnLockComment(post),
      });
    },
    closeComment() {
      this.$emit("deletePost");
    },
    deletePost(post) {
      this.$confirm({
        content: "Are you sure you want to delete this post?",
        okText: "Delete",
        cancelText: "Cancel",
        centered: true,
        onOk: () => this.handleDeletePost(post),
      });
    },
    exportListVoter(e) {
      e.preventDefault();
      this.$store.dispatch("post/exportListVoter", this.postItem.post_id);
    },
    showModalColor(value, name) {
      this.modalColor = true;
      const params = {
        name: name,
        color: value,
      };
      this.statusChange = params;
    },
    modalColorOk(e) {
      this.confirmLoading = true;
      setTimeout(() => {
        this.modalColor = false;
        this.confirmLoading = false;
      }, 800);
    },
    modalColorCancel(e) {
      this.modalColor = false;
    },
    handleChange(post, status) {
      const postId = post.post_id;
      const component = this;
      this.$store.dispatch("post/changePostStatus", {
        postId,
        status,
        component,
      });
      this.postItem.post_status = status;
    },
    statusColor() {
      const status = this.postItem.post_status;
      let color = "";
      if (status == "Unassigned") {
        color = "#fff";
      } else {
        color = this.category.status[status];
      }
      return color;
    },
    changeColor(name, color) {
      this.statusChange.color = color;
      const boardId = getBoardIdFromUrl();
      this.$store.dispatch("category/updateStatusColor", {
        boardId,
        name,
        color,
      });
      setTimeout(() => {
        this.$message.success("Change color success");
      }, 1000);
    },
    checkVote(user_vote_ids) {
      if (this.user.user.length == 0 || user_vote_ids == null) {
        return false;
      } else {
        return user_vote_ids.includes(this.user.user.ID.toString());
      }
    },
    checkAnonymousVote(item) {
      if (this.user.userAnonymous == true) {
        if (this.user.voteAnonymous.includes(item.post_id)) {
          return true;
        } else {
          return false;
        }
      } else {
        let arr = [];
        if (item.vote_ids == "" || item.vote_ids == null) {
          arr = [];
        } else {
          arr = item.vote_ids.split(" , ");
        }
        return arr.includes(this.user.user.ID.toString());
      }
    },
    checkAnonymousDownVote(item) {
      if (this.user.userAnonymous == true) {
        if (this.user.downVoteAnonymous.includes(item.post_id)) {
          return true;
        } else {
          return false;
        }
      } else {
        let arr = [];
        if (item.down_vote_ids == "" || item.down_vote_ids == null) {
          arr = [];
        } else {
          arr = item.down_vote_ids.split(" , ");
        }
        return arr.includes(this.user.user.ID.toString());
      }
    },
    handleVoteClick(post) {
      if (this.user.userAnonymous == true) {
        if (this.checkAllowAnonymous() == false) {
          this.$message.error(
            "Anonymous does not vote on this board. Please login and continue."
          );
        } else {
          const checkVote = this.checkAnonymousVote(post);
          const checkDownVote = this.checkAnonymousDownVote(post);
          const userVote = "0";
          const checkAnonymous = this.user.userAnonymous;
          const beforeVoteList = post.vote_ids;
          const beforeDownVoteList = post.down_vote_ids;
          this.$store.commit("user/SET_VOTE_ANONYMOUS", post.post_id);
          this.$store.commit("post/UPDATE_VOTE", {
            post,
            checkVote,
            checkDownVote,
            checkAnonymous,
            userVote,
          });
          this.$store.dispatch("post/updateVote", {
            post: post,
            checkVote: checkVote,
            userVote: userVote,
            beforeVoteList: beforeVoteList,
            beforeDownVoteList: beforeDownVoteList,
            updateVoteList: false,
            component: this,
          });
        }
      } else {
        const checkVote = this.checkVote(post.vote_ids);
        const checkDownVote = this.checkUserDownVote(post.down_vote_ids);
        const userVote = this.user.user.ID;
        const checkAnonymous = this.user.userAnonymous;
        const beforeVoteList = post.vote_ids;
        const beforeDownVoteList = post.down_vote_ids;
        const user = this.user.user;
        this.$store.commit("post/UPDATE_VOTE", {
          post,
          checkVote,
          checkDownVote,
          checkAnonymous,
          userVote,
        });
        // this.$store.commit('post/UPDATE_VOTELIST', {
        //     user,
        //     checkVote,
        //     checkDownVote,
        //     userVote,
        // });
        this.$store.dispatch("post/updateVote", {
          post: post,
          checkVote: checkVote,
          userVote: userVote,
          beforeVoteList: beforeVoteList,
          beforeDownVoteList: beforeDownVoteList,
          updateVoteList: {
            user,
            checkVote,
            checkDownVote,
            userVote,
          },
          listVoter: this.post.listVoter,
          component: this,
        });
      }
    },
    checkLike(index) {
      const userslike = this.comment.comments;
      if (this.user.userAnonymous == true) {
        if (this.user.likeAnonymous.includes(userslike[index].comment_ID)) {
          return true;
        } else {
          return false;
        }
      } else {
        if (userslike[index].users_like == undefined) {
          return false;
        } else {
          if (this.user.user.length == 0) {
            return false;
          } else {
            if (
              userslike[index].users_like != null &&
              userslike[index].users_like.indexOf(
                this.user.user.ID.toString()
              ) >= 0
            ) {
              return true;
            }
            return false;
          }
        }
      }
    },
    like(index) {
      const userslike = this.comment.comments;
      const comment = userslike[index];
      const boardId = getBoardIdFromUrl();
      if (this.user.userAnonymous == true) {
        if (this.checkAllowAnonymous() == false) {
          this.$message.error(
            "Anonymous does not like comment on this board. Please login and continue."
          );
        } else {
          const checkLike = this.checkLike(index);
          const userLike = "0";
          const checkAnonymous = this.user.userAnonymous;
          this.$store.commit(
            "user/SET_USERLIKE_ANONYMOUS",
            userslike[index].comment_ID
          );
          this.$store.commit("comment/UPDATE_LIKE", {
            comment,
            checkLike,
            checkAnonymous,
            userLike,
          });
          this.$store.dispatch("comment/updateUsersLike", {
            comment: comment,
            checkLike: checkLike,
            userLike: userLike,
            boardId: boardId,
            component: this,
          });
        }
      } else {
        const checkLike = this.checkLike(index);
        const userLike = this.user.user.ID;
        const checkAnonymous = this.user.userAnonymous;
        this.$store.commit("comment/UPDATE_LIKE", {
          comment,
          checkLike,
          checkAnonymous,
          userLike,
        });
        this.$store.dispatch("comment/updateUsersLike", {
          comment: comment,
          checkLike: checkLike,
          userLike: userLike,
          boardId: boardId,
          component: this,
        });
      }
    },
    countLikes(index) {
      const userslike = this.comment.userslike;
      if (
        userslike[index].meta_value == null ||
        userslike[index].meta_value == ""
      ) {
        return 0;
      } else {
        const count = userslike[index].meta_value.split(" , ");
        return count.length;
      }
    },
    checkUser(comment_author) {
      if (
        this.user.userAnonymous == false &&
        this.user.user.display_name == comment_author
      ) {
        return true;
      }
      return false;
    },
    hide(item) {
      document
        .querySelectorAll(".ant-popover")
        .forEach((el) => (el.style.display = "none"));
      const image = item.comment_image != null ? item.comment_image : [];
      this.id = item.comment_ID;
      this.inputUpdate = item.comment_content;
      this.$store.commit("comment/SET_IMGUPDATE", image);
      this.visibleUpdate = true;
      this.visible = false;
    },
    deleteimgUpload(image) {
      const index = this.comment.commentImgUpdate.indexOf(image);
      if (index > -1) {
        this.comment.commentImgUpdate.splice(index, 1);
      }
    },
    handleCancel(e) {
      this.inputUpdate = "";
      this.$store.commit("comment/SET_IMGUPDATE", []);
      this.uploadFileList = [];
      this.visibleUpdate = false;
      document
        .querySelectorAll(".ant-popover")
        .forEach((el) => (el.style.display = "block"));
    },
    handleUpdate(item) {
      const postId = item.comment_post_ID;
      const termId = getBoardIdFromUrl();
      const commentId = this.id;
      const values = this.inputUpdate;
      const image = [];
      let arrImage = [];
      const component = this;
      this.uploadFileList.forEach((values) => {
        image.push(values.response.url);
      });
      if (
        this.comment.commentImgUpdate.length == 0 ||
        this.comment.commentImgUpdate == false
      ) {
        arrImage = image;
      } else {
        arrImage = this.comment.commentImgUpdate.concat(image);
      }
      this.$store.dispatch("comment/updateComment", {
        termId,
        postId,
        values,
        commentId,
        arrImage,
        component,
      });
      this.$store.commit("comment/SET_IMGUPDATE", []);
      this.uploadFileList = [];
      setTimeout(() => {
        this.visibleUpdate = false;
      }, 500);
    },
    handleSubmit(item) {
      const termId = item.term_id;
      const id = item.post_id;
      const author = item.post_author != null ? item.post_author : 0;
      this.form.validateFields((err, values) => {
        if (!err) {
          if (
            this.checkAllowAnonymous() == false &&
            this.user.userAnonymous == true
          ) {
            this.$message.error(
              "Anonymous does not comment on this board. Please login and continue."
            );
          } else {
            let commentImages = {};
            if (values.file != undefined) {
              const imageList = [];
              values.file.forEach((item) => {
                imageList.push(item.response.url);
              });
              commentImages = imageList;
            } else {
              commentImages = null;
            }
            const comment = {
              comment_ID: 0,
              comment_id: 0,
              comment_author:
                this.user.userAnonymous == false
                  ? this.user.user.display_name
                  : "Anonymous",
              comment_post_ID: id,
              comment_content: values.comment,
              comment_date: moment(),
              comment_date_format: "1 seconds ago",
              users_like: null,
              userslike_length: 0,
              comment_image: commentImages,
            };
            const component = this;
            this.$store.commit("comment/ADD_COMMENT", comment);
            this.$store.dispatch("comment/createComments", {
              values,
              id,
              author,
              termId,
              component,
            });
            setTimeout(() => {
              this.form.resetFields();
            }, 500);
          }
        }
      });
    },
    checkDownVote() {
      const str = this.category.board.features;
      return str.includes("downvoting");
    },
    checkUserDownVote(user_down_vote_ids) {
      if (this.user.user.length == 0 || user_down_vote_ids == null) {
        return false;
      } else {
        return user_down_vote_ids.includes(this.user.user.ID.toString());
      }
    },
    handleDownVoteClick(post) {
      if (this.user.userAnonymous == true) {
        if (this.checkAllowAnonymous() == false) {
          this.$message.error(
            "Anonymous does not downvote on this board. Please login and continue."
          );
        } else {
          const checkVote = this.checkAnonymousVote(post);
          const checkDownVote = this.checkAnonymousDownVote(post);
          const userVote = "0";
          const checkAnonymous = this.user.userAnonymous;
          this.$store.commit("user/SET_DOWNVOTE_ANONYMOUS", post.post_id);
          this.$store.commit("post/UPDATE_DOWN_VOTE", {
            post,
            checkVote,
            checkDownVote,
            checkAnonymous,
            userVote,
          });
          this.$store.dispatch("post/updateDownVote", {
            post: post,
            checkDownVote: checkDownVote,
            userVote: userVote,
            component: this,
          });
        }
      } else {
        const checkVote = this.checkVote(post.vote_ids);
        const checkDownVote = this.checkUserDownVote(post.down_vote_ids);
        const userVote = this.user.user.ID;
        const checkAnonymous = this.user.userAnonymous;
        this.$store.commit("post/UPDATE_DOWN_VOTE", {
          post,
          checkVote,
          checkDownVote,
          checkAnonymous,
          userVote,
        });
        this.$store.dispatch("post/updateDownVote", {
          post: post,
          checkDownVote: checkDownVote,
          userVote: userVote,
          component: this,
        });
      }
    },
    changeUpvoted() {
      this.postItem.vote_ids = this.postItem.vote_ids + ", 0";
      this.postItem.vote_length = this.postItem.vote_length + 1;
    },
    exportListSubscriber(e) {
      e.preventDefault();
      this.$store.dispatch("post/exportListSubscriber", this.postItem.post_id);
    },
    checkUserInBoard() {
      let check = false;
      if (this.user.user.length != 0) {
        this.category.userTeam.forEach((item) => {
          if (this.user.user.user_email == item.user_email) {
            check = true;
          }
        });
      }
      return check;
    },
    checkChangeStatus() {
      if (this.checkUserInBoard() == false) {
        return false;
      } else {
        if (this.user.currentLevel < 3) {
          return true;
        } else {
          return false;
        }
      }
    },
    checkAllowAnonymous() {
      const str = this.category.board.features;
      return str.includes("anonymous");
    },
    normFile(e) {
      if (Array.isArray(e)) {
        return e;
      }
      return e && e.fileList;
    },
    displayMobile() {
      let check = false;
      (function(a) {
        if (
          /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
            a
          ) ||
          /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
            a.substr(0, 4)
          )
        ) {
          check = true;
        }
      })(navigator.userAgent || navigator.vendor || window.opera);
      if (check === false) {
        const waWidth =
          window.innerWidth > 0 ? window.innerWidth : screen.width;
        if (waWidth < 600) {
          check = true;
        }
      }
      return check;
    },
  },
};
</script>

<style lang="scss">
.big-ninja-feedbo {
  a {
    color: inherit;
    cursor: pointer;
    text-decoration: none;
  }
  .layout-wrap {
    font-size: 0.9rem;
    padding: 0.5rem 2rem;
  }
  .layout {
    position: relative;
  }
  .layout-table {
    opacity: 1;
    min-width: 11.5rem;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
    grid-auto-rows: 1fr;
    gap: 0.5rem;
  }
  .button-lock-delete {
    display: flex;
    position: absolute;
    right: 10px;
    top: 9px;
  }
  .post-status-container {
    border-bottom: 1px solid #e8e8e8;
    padding-bottom: 24px;
  }
  .post-status {
    display: inline-flex;
    flex-wrap: wrap;
    -webkit-box-align: center;
    align-items: center;
    text-align: inherit;
    min-height: 2rem;
    line-height: 1.4;
    user-select: none;
    max-width: 100%;
    width: auto;
    color: inherit;
    background-color: inherit;
    padding: 0.25rem 0px;
    border-radius: 5px;
    outline: none;
  }
  .status {
    display: inline-flex;
    .ant-btn[disabled] {
      cursor: auto;
      background: #fff !important;
      border: none;
      color: inherit !important;
      padding: 0;
    }
  }
  .status-color {
    display: inline-flex;
    padding-left: 9px;
  }
  .item-color {
    padding-top: 100%;
    box-sizing: border-box;
    position: relative;
    cursor: pointer;
    border-radius: 50%;
  }
  .comment-button-vote {
    float: right;
  }
  .vote-icon {
    font-size: 0.6rem;
    margin: 0px 0.5rem 0px 0px;
    vertical-align: 0.125em;
  }
  .vote-count {
    opacity: 1;
  }
  .ischeck {
    border-left: solid 4px rgb(253, 106, 101);
  }
  .islike {
    color: crimson !important;
  }
  .upload-image-text {
    cursor: pointer;
    width: 20px;
  }
  .status-button {
    font-weight: 600 !important;
  }
  .itemActive {
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
  }
  .comment-image-list {
    width: 104px;
    height: 104px;
    margin: 0 8px 8px 0;
  }
  .comment-image-list-edit {
    display: inline-table;
  }
  .comment-image {
    position: relative;
    height: 104px;
    width: 130px;
    object-fit: cover;
    padding: 5px;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
  }
  .button-comment {
    color: white !important;
    background-color: var(--main-color) !important;
    border-color: var(--main-color) !important;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12) !important;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.045) !important;
  }
  .button-comment:hover {
    filter: brightness(1.15);
    border-color: var(--hover-color) !important;
    text-decoration: none;
  }
  .submit-button-wrap {
    margin: 0 !important;
  }
  .export-subscribe-list {
    position: absolute;
    right: 24px;
    margin-top: 6px;
  }
  .button-upload {
    display: inline-block;
  }
  .button-add-comment {
    display: inline-block;
    float: right;
  }
  /* tile uploaded pictures */
  .upload-list-inline >>> .ant-upload-list-item {
    float: left;
    width: 200px;
    margin-right: 8px;
  }
  .ant-upload-list-picture .ant-upload-list-item-name {
    max-width: 90% !important;
  }
  .upload-list-inline >>> .ant-upload-animate-enter {
    animation-name: uploadAnimateInlineIn;
  }
  .upload-list-inline >>> .ant-upload-animate-leave {
    animation-name: uploadAnimateInlineOut;
  }
  .status .ant-btn[disabled] {
    cursor: auto;
  }
  .modal-update-button {
    padding-bottom: 6px;
  }
  .modal-update-button button {
    height: 35px;
  }
  .comment-content-text {
    white-space: break-spaces;
    word-wrap: break-word;
    word-break: break-word;
  }

  @media only screen and (max-width: 600px) {
    .comment-button-vote.allow-downvote {
      float: unset;
      display: flex;
      gap: 5px;
      justify-content: space-between;
      margin-top: 10px;
    }
  }
}
</style>
