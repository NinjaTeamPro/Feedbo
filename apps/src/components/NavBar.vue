<template>
  <div
    class="nav-container"
    :class="{ 'nav-container-anonymous': user.userAnonymous == true }"
  >
    <div class="nav-wrap">
      <AInput
        v-model="text"
        class="search-input"
        placeholder="Search"
        @keyup.enter.native="showSearchResults"
      >
        <AIcon slot="prefix" type="search" />
      </AInput>
      <AModal
        title="Search Post"
        centered
        :visible="visible1"
        :footer="false"
        @cancel="handleCloseSearchPost"
      >
        <AInputSearch
          id="search"
          placeholder="Search"
          :loading="loading2"
          v-model="text"
          @search="onSearch"
        />
        <AList
          v-show="post.results.length > 0"
          item-layout="horizontal"
          :data-source="post.results"
        >
          <div slot="header">{{ post.results.length }} results</div>
          <AListItem slot="renderItem" slot-scope="item">
            <AListItemMeta :description="item.post_content">
              <a slot="title" @click="setModalVisible(item)">
                {{ item.post_title }}
              </a>
            </AListItemMeta>
          </AListItem>
        </AList>
      </AModal>
      <AModal
        v-model="modalVisible"
        :title="this.postItem != null ? this.postItem.post_title : ''"
        class="modal-post-content"
        :class="{ 'modal-post-content-anonymous': user.userAnonymous == true }"
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
      <AccountView />
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import AccountView from "@/components/Account/AccountView.vue";
import { getBoardIdFromUrl } from "@/helper/helper.js";
import Comment from "@/components/Comment.vue";
export default {
  components: {
    AccountView,
    Comment,
  },
  data() {
    return {
      postItem: null,
      visible1: false,
      modalVisible: false,
      loading2: false,
      text: "",
    };
  },
  methods: {
    showSearchResults() {
      const text = this.text;
      const id = getBoardIdFromUrl();
      this.$store.dispatch("post/searchPosts", { text, id });
      this.visible1 = true;
    },
    onSearch(value) {
      const text = value;
      this.text = value;
      const id = getBoardIdFromUrl();
      this.loading2 = true;
      this.$store.dispatch("post/searchPosts", { text, id });
      setTimeout(() => {
        this.loading2 = false;
      }, 1000);
    },
    setModalVisible(item) {
      this.post.posts.forEach((element) => {
        if (element.post_id == item.post_id) {
          this.postItem = element;
        }
      });
      this.$store.dispatch("comment/fetchComments", item.post_id);
      this.modalVisible = true;
      const boardId = getBoardIdFromUrl();
      this.$router.push({
        path: "/board/" + boardId + "/",
        hash: `#${item.post_slug}`,
      });
    },
    handleCloseSearchPost(e) {
      this.text = "";
      this.visible1 = false;
    },
    deletePost() {
      this.modalVisible = false;
      this.handleCloseComment();
    },
    updateCommentStatus(requestParams) {
      this.postItem.comment_status = requestParams;
    },
    handleCloseComment() {
      this.$router.push({ hash: `` });
    },
  },
  computed: {
    ...mapState(["post", "comment", "user"]),
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  a {
    color: inherit;
    text-decoration: none;
    cursor: pointer;
  }
  .nav-container {
    position: absolute;
    right: 30px;
    top: 31px;
  }
  .nav-wrap {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
  }
  @media only screen and (max-width: 600px) {
    .nav-container {
      top: 20px;
      right: 15px;
    }
    .nav-container.nav-container-anonymous {
      right: 0;
    }
    .search-input {
      width: 120px !important;
    }
  }
}
</style>
