<template>
  <div>
    <div v-show="post.tabActive != 'roadmap'" class="feature-container">
      <div class="feature-wrap">
        <div
          id="feedbo-list-post"
          v-infinite-scroll="handleInfiniteOnLoad"
          class="demo-infinite-container"
          :infinite-scroll-disabled="busy"
          :infinite-scroll-distance="10"
        >
          <ASkeleton :loading="post.loadingListPost" active>
            <AList :data-source="post.listPost">
              <AListItem slot="renderItem" slot-scope="item, index">
                <AListItemMeta>
                  <p
                    slot="description"
                    class="post-content-text"
                    :style="{ color: category.theme.text }"
                  >
                    {{ item.post_content }}
                  </p>
                  <a
                    slot="title"
                    :style="{ color: category.theme.title }"
                    @click="setModalVisible(item)"
                  >
                    <h2
                      style="display:inline-block;font-size:1.2em;vertical-align: middle;"
                      :style="{ color: category.theme.title }"
                    >
                      {{ item.post_title }}
                    </h2>
                    <span class="post-title-status">
                      <ABadge
                        :count="item.post_status"
                        :number-style="{
                          backgroundColor: getStatusColor(item.post_status),
                        }"
                      />
                    </span>
                  </a>
                </AListItemMeta>

                <AButton
                  v-if="checkDownVote() == false"
                  class="button-vote"
                  :style="[
                    checkAnonymousVote(item)
                      ? { borderLeft: 'solid 4px ' + category.theme.accent }
                      : {},
                  ]"
                  @click.prevent="handleClick(item)"
                >
                  <span class="vote-icon">
                    <template v-if="checkAnonymousVote(item) === true">
                      <AIcon type="check" />
                    </template>
                    <template v-else>
                      <AIcon type="caret-up" />
                    </template>
                  </span>
                  <span class="vote-count">
                    {{ setcountVote(item).length }}
                  </span>
                </AButton>
                <div v-else>
                  <AButton
                    class="button-vote"
                    :style="[
                      checkAnonymousVote(item)
                        ? { borderLeft: 'solid 4px ' + category.theme.accent }
                        : {},
                    ]"
                    @click.prevent="handleClick(item)"
                  >
                    <span class="vote-icon">
                      <template v-if="checkAnonymousVote(item) === true">
                        <AIcon type="check" />
                      </template>
                      <template v-else>
                        <AIcon type="caret-up" />
                      </template>
                    </span>
                    <span class="vote-count">
                      {{ item.vote_length }}
                    </span>
                  </AButton>
                  <AButton
                    class="button-vote button-vote-down"
                    :style="[
                      checkAnonymousDownVote(item)
                        ? { borderLeft: 'solid 4px ' + category.theme.accent }
                        : {},
                    ]"
                    @click.prevent="downVoteClick(item)"
                  >
                    <span class="vote-icon">
                      <template v-if="checkAnonymousDownVote(item) === true">
                        <AIcon type="check" />
                      </template>
                      <template v-else>
                        <AIcon type="caret-down" />
                      </template>
                    </span>
                    <span class="vote-count">
                      {{ item.down_vote_length }}
                    </span>
                  </AButton>
                </div>
              </AListItem>
              <div v-if="loading" class="demo-loading-container">
                <ASpin />
              </div>
            </AList>
          </ASkeleton>
          <AModal
            v-model="modalVisible"
            class="modal-post-content"
            :class="{
              'modal-post-content-anonymous': user.userAnonymous == true,
            }"
            :title="title"
            centered
            :footer="null"
            @cancel="handleCloseComment"
          >
            <ASkeleton :loading="comment.isLoadingComment" active>
              <Comment
                :post-item="postItem"
                @deletePost="deletePost"
                @updateCommentStatus="updateCommentStatus($event)"
              />
            </ASkeleton>
          </AModal>
        </div>
      </div>
    </div>

    <div v-show="post.tabActive == 'roadmap'" class="roadmap-wrap">
      <ASkeleton :loading="post.loadingRoadmap" active>
        <template
          v-if="post.status && category.board.features.indexOf('roadmap') > -1"
        >
          <ATimeline>
            <div v-for="(value, name) in group" :key="name">
              <div>
                <ATimelineItem :color="category.status[name]">
                  {{ name }}
                  <div v-for="posts in value" :key="posts.post_id" class="post">
                    <h3 class="post-title">
                      <ABadge
                        :color="category.status[name]"
                        :text="posts.post_title"
                        @click="showModalPost(posts)"
                      />
                    </h3>
                  </div>
                </ATimelineItem>
              </div>
            </div>
          </ATimeline>
          <AModal
            v-model="modalVisibleRoadmap"
            class="modal-post-content"
            :class="{
              'modal-post-content-anonymous': user.userAnonymous == true,
            }"
            :title="postItem != null ? postItem.post_title : ''"
            centered
            :footer="null"
            @cancel="handleCloseComment"
          >
            <ASkeleton :loading="comment.isLoadingComment" active>
              <Comment
                :post-item="postItem"
                @deletePost="deletePost"
                @updateCommentStatus="updateCommentStatus($event)"
              />
            </ASkeleton>
          </AModal>
        </template>
        <template v-else>
          <div class="roadmap">
            <h3 class="roadmap-header">
              The roadmap will appear here
            </h3>
            when a post is assigned to a status
          </div>
        </template>
      </ASkeleton>
    </div>
  </div>
</template>

<script>
import Comment from "@/components/Comment.vue";
import infiniteScroll from "vue-infinite-scroll";
import { getBoardIdFromUrl } from "@/helper/helper.js";
import { mapState } from "vuex";
import { setTimeout } from "timers";
export default {
  directives: { infiniteScroll },
  components: {
    Comment,
  },
  data() {
    return {
      skeletonLoading: false,
      modalVisible: false,
      modalVisibleRoadmap: false,
      title: "",
      postItem: null,
      loading: false,
      busy: false,
      testVar: null,
    };
  },
  computed: {
    ...mapState(["category", "post", "comment", "user"]),
    group() {
      const item = this.post.status;
      const group = item.reduce((r, a) => {
        r[a.post_status] = [...(r[a.post_status] || []), a];
        return r;
      }, {});
      return group;
    },
    listPost() {
      return this.post.listPost;
    },
  },
  watch: {
    listPost: function(newValue) {
      if (newValue.length > 0) {
        if (
          this.$route.hash !== "" &&
          this.$route.hash.substring(0, 1) === "#"
        ) {
          const postSlug = this.$route.hash.substring(1);
          newValue.some((post) => {
            if (post.post_slug === postSlug) {
              this.title = post.post_title;
              this.postItem = post;
              this.$store.dispatch("comment/fetchComments", post.post_id);
              this.modalVisible = true;
              return true;
            }
          });
        }
      }
    },
  },
  beforeMount() {
    const component = this;
    const boardId = getBoardIdFromUrl();
    this.$store.dispatch("board/getPosts", { boardId, component });
  },
  mounted() {
    const listElm = document.querySelector(".board-main .board-list-post");
    const boardElemnt = document.querySelector(".board-main");

    listElm.addEventListener("scroll", (e) => {
      if (this.mobileCheck()) {
        const scrollTop = listElm.scrollTop;

        if (scrollTop > 0) {
          boardElemnt.style.marginTop = "-" + this.matchHeight();
        } else {
          boardElemnt.style.marginTop = 0;
        }
      }
    });
  },
  methods: {
    fetchData() {
      const res = [];
      const maxLength = this.post.posts.length;
      const loadIndex =
        this.post.postIndexLoad + 10 > maxLength
          ? maxLength
          : this.post.postIndexLoad + 10;
      for (let index = this.post.postIndexLoad; index < loadIndex; index++) {
        res.push(this.post.posts[index]);
      }
      setTimeout(() => {
        this.$store.commit("post/UPDATE_LIST_POST", res);
        this.$store.commit("post/SET_GENERNAL", { postIndexLoad: loadIndex });
        this.loading = false;
      }, 800);
    },
    handleInfiniteOnLoad() {
      if (this.loading == false) {
        this.loading = true;
        if (this.post.postIndexLoad == this.post.posts.length) {
          this.busy = true;
          this.loading = false;
          return;
        }
        this.fetchData();
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
    setcountVote(item) {
      if (item.vote_ids == "") {
        return [];
      } else {
        return item.vote_ids.split(" , ");
      }
    },
    setcountDownVote(item) {
      if (item.down_vote_ids == "" || item.down_vote_ids == null) {
        return [];
      } else {
        return item.down_vote_ids.split(" , ");
      }
    },
    handleClick(post) {
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
          this.$store.commit("user/SET_VOTE_ANONYMOUS", post.post_id);
          const beforeVoteList = post.vote_ids;
          const beforeDownVoteList = post.down_vote_ids;
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
    },
    downVoteClick(post) {
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
    setModalVisible(item) {
      this.title = item.post_title;
      this.postItem = item;
      this.$store.dispatch("comment/fetchComments", item.post_id);
      this.modalVisible = true;
      const boardId = getBoardIdFromUrl();
      this.$router.push({
        path: "/board/" + boardId + "/",
        hash: `#${item.post_slug}`,
      });
    },
    checkVote(user_vote_ids) {
      if (this.user.user.length == 0 || user_vote_ids == null) {
        return false;
      } else {
        return user_vote_ids.includes(this.user.user.ID.toString());
      }
    },
    checkUserDownVote(user_down_vote_ids) {
      if (this.user.user.length == 0 || user_down_vote_ids == null) {
        return false;
      } else {
        return user_down_vote_ids.includes(this.user.user.ID.toString());
      }
    },
    deletePost() {
      this.modalVisible = false;
      this.handleCloseComment();
    },
    showModalPost(item) {
      this.postItem = item;
      this.skeletonLoading = true;
      this.modalVisibleRoadmap = true;
      const boardId = getBoardIdFromUrl();
      this.$router.push({
        path: "/board/" + boardId + "/",
        hash: `#${item.post_slug}`,
      });
      this.$store.dispatch("comment/fetchComments", item.post_id);
      setTimeout(() => {
        this.skeletonLoading = false;
      }, 800);
    },
    checkDownVote() {
      const str = this.category.board.features;
      return str.includes("downvoting");
    },
    checkAllowAnonymous() {
      const str = this.category.board.features;
      return str.includes("anonymous");
    },
    updateCommentStatus(requestParams) {
      this.postItem.comment_status = requestParams;
    },
    mobileCheck() {
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
        if (waWidth < 500) {
          check = true;
        }
      }
      return check;
    },
    handleCloseComment() {
      this.$router.push({ hash: `` });
    },
    getStatusColor(status) {
      if (status === "Unassigned") {
        return "#a4a4a4";
      }
      return this.category.status[status];
    },
    matchHeight() {
      const bannerOffset =
        document.querySelector(".board-home-link").offsetHeight +
        document.querySelector(".board-header").offsetHeight;
      return bannerOffset + "px";
    },
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  .feature-container {
    position: relative;
    padding: 1rem 2rem 2rem;
  }

  .feature-wrap {
    margin: -1.5rem 0px;
  }

  .ant-list-item-meta {
    padding-right: 10px;
  }

  .button-vote {
    width: 60px;
    height: 32px;
  }

  .roadmap {
    position: relative;
    text-align: center;
    max-width: 370px;
    width: 100%;
    color: rgba(73, 73, 73, 0.714);
    font-size: 0.9rem;
    padding: 15vh 1rem;
    margin: 0px auto;
  }

  .roadmap-wrap {
    padding: 1rem 2rem 2rem;
  }

  .roadmap-header {
    color: rgb(73, 73, 73);
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.5;
  }

  .post {
    position: relative;
    margin: 1.5rem 0px;
  }

  .post-title {
    cursor: pointer;
    font-size: 0.95rem;
    color: rgb(0, 0, 0);
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }

  .button-roadmap {
    width: 60px;
    height: 35px;
  }

  .button-vote-down {
    display: list-item;
    margin-top: 5px;
  }

  .button-vote .vote-icon {
    vertical-align: 0.125em;
    font-size: 0.6rem;
    margin: 0 0.5rem 0 0;
  }

  .demo-loading-container {
    position: absolute;
    bottom: 40px;
    width: 100%;
    text-align: center;
  }

  .button-vote:hover,
  .button-vote:focus {
    color: #494949;
    border-color: #494949;
  }

  .post-title-status {
    display: inline-block;
    line-height: 1.75em;
  }

  .post-title-status .ant-badge {
    vertical-align: top;
  }

  @media screen and (max-width: 600px) {
    .feature-container {
      padding: 0 15px;
    }

    .feature-wrap {
      margin: 0;
    }
  }
}
</style>
